<?php
ob_start();

global $client; // Declare at the top of your script
function start_session()
{
  if (!session_id()) {
    session_start();
  }
}

add_action('init', 'start_session'); // Ensures session starts before any HTML output

function plugin_init()
{
  global $client;
  $client = new Google_Client();
  // Other client setup code
}

add_action('init', 'plugin_init'); // Initialize early but after start_session

function ga_add_admin_menu()
{
  add_menu_page('Google Analytics Auth', 'GA Auth', 'manage_options', 'ga-dashboard-auth', 'ga_dashboard_auth_page_content', 'dashicons-chart-area', 3);
  add_submenu_page('ga-dashboard-auth', 'Google Analytics Dashboard', 'Dashboard', 'manage_options', 'ga-dashboard', 'ga_dashboard_page_content');
  add_submenu_page('ga-dashboard-auth', 'Google Analytics Settings', 'Settings', 'manage_options', 'ga-settings', 'ga_settings_page_content');
}

add_action('admin_menu', 'ga_add_admin_menu');

function ga_dashboard_auth_page_content()
{
  global $client;

  // Authenticate here
  ga_authenticate();

  echo '<h1>Google Analytics Auth</h1>';
  if ($client->getAccessToken()) {
    echo '<p>Authenticated. You can now access the dashboard.</p>';
  } else {
    echo '<p>Not authenticated. Please authenticate to access the dashboard.</p>';
  }
}

function ga_dashboard_page_content()
{
  global $client;


  if (isset($client)) {
    if ($client->getAccessToken()) {
      // If there's a valid access token, the user is authenticated
      echo '<h1>Google Analytics Dashboard</h1>';
      // Proceed with displaying the dashboard content
    } else {
      // No valid access token, user is not authenticated
      echo '<h1>Authentication Required</h1>';
      echo '<p>You need to authenticate with Google Analytics to view this page.</p>';
    }
  } else {
    // The Google Client is not set up
    echo '<h1>Configuration Error</h1>';
    echo '<p>The Google Client has not been configured correctly.</p>';
  }
  // The rest of your dashboard page content logic
}

function ga_settings_page_content()
{
?>
  <div class="wrap">
    <h1>Google Analytics Settings</h1>
    <form method="post" action="options.php">
      <?php
      settings_fields('ga-settings-group');
      do_settings_sections('ga-settings-group');
      ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Client ID</th>
          <td><input type="text" name="ga_client_id" value="<?php echo esc_attr(get_option('ga_client_id')); ?>" /></td>
        </tr>

        <tr valign="top">
          <th scope="row">Client Secret</th>
          <td>
            <input type="password" id="ga_client_secret" name="ga_client_secret" value="<?php echo esc_attr(get_option('ga_client_secret')); ?>" />
            <button type="button" onclick="toggleSecretVisibility()">👁</button>
          </td>
        </tr>


        <tr valign="top">
          <th scope="row">Redirect URI</th>
          <td><input type="text" name="ga_redirect_uri" value="<?php echo esc_attr(get_option('ga_redirect_uri')); ?>" /></td>
        </tr>
      </table>

      <script>
        function toggleSecretVisibility() {
          var secretInput = document.getElementById("ga_client_secret");
          if (secretInput.type === "password") {
            secretInput.type = "text";
          } else {
            secretInput.type = "password";
          }
        }
      </script>

      <?php submit_button(); ?>
    </form>
  </div>
<?php
}

function ga_authenticate()
{

  global $client;

  $client_id = get_option('ga_client_id');
  $client_secret = get_option('ga_client_secret');
  $redirect_uri = get_option('ga_redirect_uri');

  $client = new Google_Client();
  $client->setClientId($client_id);
  $client->setClientSecret($client_secret);
  $client->setRedirectUri($redirect_uri);
  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
  $client->setAccessType('offline');
  $client->setPrompt('select_account consent');

  // Check if we have an authentication code
  if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Store the token in the session for later use
    $_SESSION['access_token'] = $token;
    return;
  }

  // Check if we have an access token in the session
  if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
  } else {
    // Request authorization from the user
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
  }

  // Check for token expiry and refresh if necessary
  if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
      $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
      // Redirect to authorization URL if refresh token is not available
      $authUrl = $client->createAuthUrl();
      header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
      exit;
    }
  }
}


function ga_register_settings()
{
  register_setting('ga-settings-group', 'ga_client_id');
  register_setting('ga-settings-group', 'ga_client_secret');
  register_setting('ga-settings-group', 'ga_redirect_uri');
}

add_action('admin_init', 'ga_register_settings');

<div id="connectNow" class="organizations-navigator">
  <div>
    <h2>Connect Now</h2>
    <?php
    $page_id = get_the_ID();
    $state_value = get_post_meta($page_id, 'state', true);

    if ($state_value === 'National') {
      echo '<p class="disclaimer">Green Workforce Connect currently only connects organizations active in Oklahoma, Pennsylvania, and Wisconsin. Stay tuned as we add more states and check out our resources (link to the resources page) that are useful for all types of organizations and people around the country.</p>';
    }
    ?>
    <form id="custom-searchbox">
      <input type="text" id="zipcode" name="zipcode" placeholder="Enter zipcode">
      <button id="geolocButton" class="crosshairs-button" type="button">
        <img src="/wp-content/plugins/irec-green-connect/public/img/crosshairs.svg" />
      </button>
      <button class="submit-button" type="submit">Go</button>
    </form>
    <div class="filters">
      <h6>Filter By:</h6>
      <hr />
      <div class="org-filters">

        <button class="org-filter" data-filter="Info & Help">Info & Help</button>
        <p>Organizations that offer general support and information</p>
        <button class="org-filter" data-filter="Training">Training</button>
        <p>Organizations that provide training to get workers certified</p>

        <button class="org-filter" data-filter="Employment">Employment</button>
        <p>Organizations that hire workers directly</p>

        <button class="org-filter" data-filter="For Contractors">For Contractors</button>
        <p>Find contracts for my company to bid on</p>

      </div>
    </div>
  </div>
  <div id="map">
  </div>
</div>
<hr class="hits-divider">
<div id="hits"></div>
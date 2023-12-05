<?php

/**
 * Class for handling what to show when clicking on log in the menu in wp-admin
 * @since 1.0.0
 */

namespace WPSynchro\Pages;

use WPSynchro\Utilities\CommonFunctions;
use WPSynchro\Logger\SyncMetadataLog;
use WPSynchro\Migration\MigrationFactory;
use WPSynchro\Utilities\Licensing\Licensing;

class AdminLog
{
    /**
     *  Called from WP menu to show setup
     *  @since 1.0.0
     */
    public function render()
    {
        // If showing log
        if (isset($_REQUEST['showlog']) && isset($_REQUEST['migration_id'])) {
            $job_id = sanitize_key($_REQUEST['showlog']);
            $migration_id = sanitize_key($_REQUEST['migration_id']);
            $this->showLog($job_id, $migration_id);
            return;
        }

        // Remove all logs
        if (isset($_REQUEST['removelogs']) && $_REQUEST['removelogs'] == 1) {
            $nonce = $_GET['nonce'] ?? '';
            if (!wp_verify_nonce($nonce, 'wpsynchro_delete_logs')) {
                echo "<div class='notice wpsynchro-notice'><p>" . __('Security token is no longer valid - Go back and try again.', 'wpsynchro') . '</p></div>';
                return;
            }
            $metalog = new SyncMetadataLog();
            $metalog->removeAllLogs();
            echo "<script>window.location='" . menu_page_url('wpsynchro_log', false) . "';</script>";
            return;
        }

        // Get data
        $metadatalog = new SyncMetadataLog();
        $data = $metadatalog->getAllLogs();
        $data = array_reverse($data);

        // Links
        $remove_logs_link = add_query_arg(
            [
                'removelogs' => 1,
                'nonce' => wp_create_nonce('wpsynchro_delete_logs')
            ],
            menu_page_url('wpsynchro_log', false)
        );
        $show_log_url = add_query_arg(
            [
                'nonce' => wp_create_nonce('wpsynchro_show_log')
            ],
            menu_page_url('wpsynchro_log', false)
        );
        $download_log_url = add_query_arg(
            [
                'action' => 'wpsynchro_frontend_download_log',
                'nonce' => wp_create_nonce('wpsynchro_download_log')
            ],
            get_home_url()
        );

        // Data for JS
        $data_for_js = [
            "logData" => $data,
            "removeAllLogs" => $remove_logs_link,
            "showLogUrl" => $show_log_url,
            "downloadLogUrl" => $download_log_url,
        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_logs_data', $data_for_js);

        // Print content
        echo '<div id="wpsynchro-log" class="wpsynchro"></div>';
    }

    /**
     *  Show the log file for job
     *  @since 1.0.5
     */
    public function showLog($job_id, $migration_id)
    {
        $nonce = $_GET['nonce'] ?? '';
        if (!wp_verify_nonce($nonce, 'wpsynchro_show_log')) {
            echo "<div class='notice wpsynchro-notice'><p>" . __('Security token is no longer valid - Go back and try again.', 'wpsynchro') . '</p></div>';
            return;
        }

        // Check if file exist
        $common = new CommonFunctions();
        $migration_factory = MigrationFactory::getInstance();

        $logpath = $common->getLogLocation();
        $filename = $common->getLogFilename($job_id);

        $job_obj = get_option("wpsynchro_" . $migration_id . "_" . $job_id, "");
        $migration_obj = $migration_factory->retrieveMigration($migration_id);


        if (file_exists($logpath . $filename)) {
            $logcontents = file_get_contents($logpath . $filename);

            echo "<h1>Log file for job_id " . $job_id . "</h1> ";
            echo "<h2>Beware: Do not share this file with other people than WP Synchro support - It contains data that can compromise your site.</h3>";

            echo '<h3>Licensing:</h3>';
            if (CommonFunctions::isPremiumVersion()) {
                $licensing = new Licensing();
                echo '<pre>';
                print_r($licensing->getLicenseState());
                echo '</pre>';
            } else {
                echo "<p>License key: FREE version</p>";
            }

            echo '<h3>Log:</h3>';
            echo '<pre>';
            echo $logcontents;
            echo '</pre>';

            echo '<h3>Migration object:</h3>';
            echo '<pre>';
            print_r($migration_obj);
            echo '</pre>';

            echo '<h3>Job object:</h3>';
            echo '<pre>';
            print_r($job_obj);
            echo '</pre>';
        }
    }
}

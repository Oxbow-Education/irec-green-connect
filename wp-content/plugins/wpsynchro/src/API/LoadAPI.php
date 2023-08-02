<?php

namespace WPSynchro\API;

use WPSynchro\Transport\TransferToken;

/**
 * Class for handling API for WP Synchro
 * @since 1.8.0
 */
class LoadAPI
{
    private $action_to_handler_mapping = [];

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->action_to_handler_mapping = [
            'wpsynchro_initiate' => [
                'check_permission' => false,
                'class' => '\WPSynchro\API\Initiate',
            ],
            'wpsynchro_masterdata' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\MasterData',
            ],
            'wpsynchro_backupdatabase' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\DatabaseBackup',
            ],
            'wpsynchro_db_sync' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\ClientSyncDatabase',
            ],
            'wpsynchro_file_populate' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\PopulateFileList',
            ],
            'wpsynchro_file_populate_status' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\PopulateFileListStatus',
            ],
            'wpsynchro_file_push' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\FileTransfer',
            ],
            'wpsynchro_file_pull' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\GetFiles',
            ],
            'wpsynchro_file_finalize' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\FileFinalize',
            ],
            'wpsynchro_frontend_filesystem' => [
                'check_permission' => function ($token) {
                    if ($this->permissionCheck($token)) {
                        return true;
                    } else {
                        return current_user_can('manage_options');
                    }
                },
                'class' => '\WPSynchro\API\Filesystem',
            ],
            'wpsynchro_frontend_verify_remote' => [
                'check_permission' => function ($token) {
                    return current_user_can('manage_options');
                },
                'class' => '\WPSynchro\API\VerifyMigration',
            ],
            'wpsynchro_frontend_healthcheck' => [
                'check_permission' => function ($token) {
                    return current_user_can('manage_options');
                },
                'class' => '\WPSynchro\API\HealthCheck',
            ],
            'wpsynchro_test' => [
                'check_permission' => function ($token) {
                    return true;
                },
                'class' => function () {
                    echo "it-works";
                    return;
                },
            ],
            'wpsynchro_execute_action' => [
                'check_permission' => true,
                'class' => '\WPSynchro\API\ExecuteAction',
            ],
            'wpsynchro_frontend_download_log' => [
                'check_permission' => function ($token) {
                    return current_user_can('manage_options');
                },
                'class' => '\WPSynchro\API\DownloadLog',
            ],
            'wpsynchro_run_synchronize' => [
                'check_permission' => function ($token) {
                    if ($this->permissionCheck($token)) {
                        return true;
                    } else {
                        return current_user_can('manage_options');
                    }
                },
                'class' => '\WPSynchro\API\Migrate',
            ],
            'wpsynchro_run_status' => [
                'check_permission' => function ($token) {
                    if ($this->permissionCheck($token)) {
                        return true;
                    } else {
                        return current_user_can('manage_options');
                    }
                },
                'class' => '\WPSynchro\API\Status',
            ],
            'wpsynchro_run_status_file_changed_get' => [
                'check_permission' => true,
                'class' => function () {
                    $obj = new StatusFileChanges();
                    $obj->getFileChanges();
                },
            ],
            'wpsynchro_run_status_file_changed_accept' => [
                'check_permission' => true,
                'class' => function () {
                    $obj = new StatusFileChanges();
                    $obj->acceptFileChanges();
                },
            ],
        ];
    }

    /**
     * Load and handle API request if it is one
     * @since 1.8.0
     */
    public function setup()
    {
        // Check if it is a WP Synchro service request
        $request_query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        if (strpos(strval($request_query), "action=wpsynchro") !== false) {
            // We have a WP Synchro action
            $query_parsed = [];
            parse_str($request_query, $query_parsed);
            $action = "";
            if (isset($query_parsed['action'])) {
                $action = $query_parsed['action'];
            }

            // Check if it is known action
            if (isset($this->action_to_handler_mapping[$action])) {
                // Get handler
                $handler = $this->action_to_handler_mapping[$action];

                // If we need to check permission, do that first
                if ($handler['check_permission']) {
                    $token = "";
                    if (isset($_REQUEST['token'])) {
                        $token = $_REQUEST['token'];
                    }
                    // Check if check_permission is a custom function or we just check the token
                    if (is_callable($handler['check_permission'])) {
                        $permission_check_result = $handler['check_permission']($token);
                    } else {
                        $permission_check_result = $this->permissionCheck($token);
                    }
                    if ($permission_check_result != true) {
                        http_response_code(401);
                        die();
                    }
                }

                if (is_callable($handler['class'])) {
                    $handler['class']();
                } else {
                    $handler_class = $handler['class'];
                    $obj = new $handler_class();
                    $obj->service();
                }

                ob_flush();
                flush();

                die();
            }
        }
    }

    /**
     *  Validates access to WP Synchro services
     */
    public function permissionCheck($token)
    {
        if ($token == null || strlen($token) < 20) {
            return false;
        }
        $token = trim($token);

        // Check if it is a transfer token
        return TransferToken::validateTransferToken($token);
    }
}

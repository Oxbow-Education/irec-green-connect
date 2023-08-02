<?php

class Local_Sync_Batch_Upload {
	private $files_this_batch = [];
	private $queued_files_count = 0;

    public function __construct(){
		$this->local_sync_options = new Local_Sync_Options();
		// $this->exclude_option = new Local_Sync_Exclude_Option();
		$this->allowed_free_disk_space = 1024 * 1024 * 10; //10 MB
		$this->retry_allowed_http_status_codes = array(5, 6, 7);
		// $this->utils_base = new Local_Sync_Utils();
		$this->init_db();
    }

	public function init_db(){
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function push_current_batch_of_small_files(){
		if(empty($this->files_this_batch)){

			return false;
		}

		$current_sync_unique_id = $this->local_sync_options->get_option('current_sync_unique_id');
		$URL = rtrim($this->local_sync_options->get_option('prod_site_url'), '/') . '/index.php';

		$prev_result = $this->local_sync_options->get_option('upload_current_result');
		$prev_result = json_decode($prev_result, true);

		$bridge_fs_obj = new LocalSyncFileSystem();
		$upload_result = $bridge_fs_obj->batch_files_upload_using_curl($URL, $this->files_this_batch, $uploadResponseHeaders, $prev_result, false, 'full');

		if(empty($upload_result)){

			local_sync_log($bridge_fs_obj->last_error, "--------push_current_batch_of_small_file---error-----");

			$this->local_sync_options->set_this_current_action_step('error');

			local_sync_die_with_json_encode(array(
				'error' =>  $bridge_fs_obj->last_error,
				'sync_sub_action' => $this->local_sync_options->get_option('sync_sub_action'),
				'sync_current_action' => $this->local_sync_options->get_option('sync_current_action'),
				'requires_next_call' => false
			));
		}

		local_sync_log($upload_result, "--------upload_result-----push_current_batch_of_small_file---");
		local_sync_log($uploadResponseHeaders, "--------uploadResponseHeaders-----push_current_batch_of_small_file---");

		return $upload_result;
	}

	public function push_single_big_file($file_obj) {
		$current_sync_unique_id = $this->local_sync_options->get_option('current_sync_unique_id');

		$URL = rtrim($this->local_sync_options->get_option('prod_site_url'), '/') . '/index.php';

		$prev_uploaded_size = $this->local_sync_files_op->get_prev_uploaded_size($file_obj->file_path);

		$bridge_fs_obj = new LocalSyncFileSystem();
		$upload_result = $bridge_fs_obj->single_big_file_upload_using_curl($URL, $file_obj, $uploadResponseHeaders, $prev_uploaded_size, false, 'full');

		if(empty($upload_result)){

			local_sync_log($bridge_fs_obj->last_error, "--------push_single_big_file---error-----");

			$this->local_sync_options->set_this_current_action_step('error');

			local_sync_die_with_json_encode(array(
				'error' =>  $bridge_fs_obj->last_error,
				'sync_sub_action' => $this->local_sync_options->get_option('sync_sub_action'),
				'sync_current_action' => $this->local_sync_options->get_option('sync_current_action'),
				'requires_next_call' => false
			));
		}

		local_sync_log($upload_result, "--------upload_result-----single_big_file_upload_using_curl---");
		local_sync_log($uploadResponseHeaders, "--------uploadResponseHeaders-----single_big_file_upload_using_curl---");

		$this->local_sync_files_op->update_current_process_prev_uploaded_size($file_obj->file_path, $upload_result['endRange']);
		
		if(empty($upload_result['is_upload_multi_call'])){
			$this->local_sync_files_op->update_current_file_status($file_obj->file_path, 'P');
		}

		$this->queued_files_count = $this->local_sync_files_op->get_queued_files_count();

		local_sync_die_with_json_encode(array(
			'success' =>  true,
			'sync_sub_action' => $this->local_sync_options->get_option('sync_sub_action'),
			'sync_current_action' => $this->local_sync_options->get_option('sync_current_action'),
			'queued_files_count' => $this->queued_files_count,
			'requires_next_call' => true
		));
	}

    public function batch_upload_files() {
		try{
			$current_sync_unique_id = $this->local_sync_options->get_option('current_sync_unique_id');

			local_sync_log('', "--------batch_upload_files---start-----");

			$backup_dir = $this->local_sync_options->get_backup_dir();
			if (!is_dir($backup_dir) && !mkdir($backup_dir, 0755)) {

				$this->local_sync_options->set_this_current_action_step('error');

				$err_msg = "Could not create backup directory ($backup_dir)";
				local_sync_die_with_json_encode(array(
					'error' =>  $err_msg,
					'sync_sub_action' => $this->local_sync_options->get_option('sync_sub_action'),
					'sync_current_action' => $this->local_sync_options->get_option('sync_current_action'),
					'requires_next_call' => false
				));
			}

			$this->local_sync_files_op = new Local_Sync_Files_Op();
			$files_to_zip = $this->local_sync_files_op->get_limited_files_to_zip();

			local_sync_log(count($files_to_zip), "--------files_to_upload--------");

			$can_continue = true;
			$is_completed = false;

			if(empty($files_to_zip)){
				local_sync_log('', "--------no files to be upload this time----empty----");

				$files_to_zip = array();
				$can_continue = false;
				$is_completed = true;
			}

			$files_size_so_far = 0;
			$files_to_zip_this_call = 0;
			$files_to_zip_this_time = 0;

			$this_start_time = time();

			do{

				local_sync_manual_debug('', 'during_batch_uploading', 1000);

				$files_status_completed = array();
				foreach ($files_to_zip as $kk => $file_obj) {
					$file_path = trim($file_obj->file_path, '/');
					$file_full_path = ABSPATH . $file_path;
					$add_as = $file_path;

					$file_obj->file_full_path = $file_full_path;
					$file_obj->add_as = $add_as;

					if( file_exists($file_full_path) 
						&& filesize($file_full_path) > LOCAL_SYNC_UPLOAD_CHUNK_SIZE
						&& $files_to_zip_this_call == 0 ){

						local_sync_log($file_path, "--------spotted big file during batch upload first call so uploading it--------");

						$this->push_single_big_file($file_obj);

						local_sync_manual_debug('', 'after_uploading_single_big_files');

						$can_continue = false;
						break;
					}

					if( file_exists($file_full_path) 
						&& filesize($file_full_path) > LOCAL_SYNC_UPLOAD_CHUNK_SIZE
						&& $files_to_zip_this_call != 0 ){

						local_sync_log($file_path, "--------spotted big file during batch upload so skipping it--------");

						// $can_continue = false;
						continue;
					}

					if(file_exists($file_full_path)){
						$this->files_this_batch[] = $file_obj;
						$files_size_so_far = $files_size_so_far + filesize($file_full_path);
					}

					$files_to_zip_this_call++;
					$files_to_zip_this_time++;

					$files_status_completed[] = '"'.$file_obj->file_path.'"';

					// local_sync_log($files_size_so_far, "--------files_size_so_far--------");

					if( $files_size_so_far > LOCAL_SYNC_UPLOAD_CHUNK_SIZE ){
						$this_time_diff = time() - $this_start_time;
						local_sync_log($this_time_diff, "--------this_time_diff--before close------");

						local_sync_log($files_size_so_far, "--------files_size_so_far--reached---$this_time_diff---");
						local_sync_log($files_to_zip_this_time, "--------files_to_zip_this_time--reached------");

						$files_size_so_far = 0;
						$files_to_zip_this_time = 0;

						$can_continue = false;

						break;
					}

					if(is_local_sync_timeout_cut(false, 13)){
						$can_continue = false;

						$this_time_diff = time() - $this_start_time;

						local_sync_log('', "--------breaking batch upload loop----$this_time_diff----");

						break;
					}
				}

				$this_time_diff = time() - $this_start_time;

				local_sync_log($this_time_diff, "--------after batch files upload---this_time_diff-----");

				$files_status_completed_str = implode(',', $files_status_completed);

				if(!empty($files_status_completed_str)){
					$sql = "UPDATE `{$this->wpdb->base_prefix}local_sync_current_process` SET status='P' WHERE file_path IN ({$files_status_completed_str})";
					$db_result = $this->wpdb->query($sql);

					// local_sync_log($sql, "--------sql--files_status_completed_str------");

					if($db_result === false){
						local_sync_log($sql, "--------db_result_error---files_status_completed_str-----");
					}
				}

				$files_to_zip = $this->local_sync_files_op->get_limited_files_to_zip();
				if( empty($files_to_zip) ){

					local_sync_log('', "--------empty files to upload--------");

					$can_continue = false;
					$is_completed = true;
				}

				$can_continue = false;
			} while ($can_continue);

			$this->push_current_batch_of_small_files();

			$this_time_diff = time() - $this_start_time;
			$site_type = $this->local_sync_options->get_option('site_type');

			local_sync_manual_debug('', 'after_uploading_batch_files');

			if($is_completed){

				local_sync_log('', "--------uploading the files completed--------");

				// exit;

				$this->local_sync_options->set_this_current_action_step('done');

				if(empty($site_type) || $site_type == 'local'){
					$this->local_sync_options->set_option('sync_current_action', 'initiate_bridge_files');
					$this->local_sync_options->set_option('sync_sub_action', 'initiate_bridge_files');
					$this->local_sync_options->set_this_current_action_step('processing');
				} elseif($site_type == 'production'){
					$this->local_sync_options->set_option('sync_current_action', 'initiate_bridge_files');
					$this->local_sync_options->set_option('sync_sub_action', 'initiate_bridge_files');
					$this->local_sync_options->set_this_current_action_step('processing');
				}
			}

			local_sync_log('', "--------batch_upload_files sending response--------");

			$this->queued_files_count = $this->local_sync_files_op->get_queued_files_count();

			local_sync_die_with_json_encode(array(
				'success' =>  true,
				'sync_sub_action' => $this->local_sync_options->get_option('sync_sub_action'),
				'sync_current_action' => $this->local_sync_options->get_option('sync_current_action'),
				'queued_files_count' => $this->queued_files_count,
				'requires_next_call' => true
			));

		} catch(Exception $e){
			local_sync_log($e->getMessage(), "--------batch_upload----exception----");
		}
	}

}

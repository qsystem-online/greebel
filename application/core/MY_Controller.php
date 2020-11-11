<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $data = [];
	public $ajxResp = [
		'status' => 'SUCCESS', // 0 process success; else failed
		'messages'=>'',
		'data'=>[]
	];

	public function __construct()
	{
		
		parent::__construct();
		//$this->output->enable_profiler(TRUE);

		$this->load->model("msbranches_model");

		$this->lang->load("general", "english");

		// Check User Login and Session expired
		if (!$this->aauth->user()) {			
			redirect(site_url() . 'login', 'refresh');
		}else{
			$user = $this->aauth->user();
			if(!isset($user->fbl_is_hq)){
				redirect(site_url() . 'login', 'refresh');
			}
		}

		

		if ($this->aauth->is_session_timeout()) {
			if ($this->input->is_ajax_request()) {			
				$this->ajxResp["status"] = AJAX_STATUS_SESSION_EXPIRED;
				$this->session->unset_userdata("last_uri");
				$this->json_output(null,401);
				die();
			} else {				
				$lastURI = $this->uri->uri_string();
				$this->session->set_userdata("last_uri", $lastURI);
				redirect('/signout/expired', 'refresh');
			}
		} else {
			
			$this->aauth->renew_session_timeout();
		}
		//End - Check User Login and Session expired



	}

	public function json_output($data = null, $http_code = 200)
	{
		header('Content-Type: application/json');
		http_response_code($http_code);

		if ($data == null) {
			echo json_encode($this->ajxResp);
		} else {
			echo json_encode($data);
		}
	}
}

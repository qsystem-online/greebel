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
	public $menuName="";

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

	public function index(){
		$isPermit = $this->aauth->is_permit($this->menuName,false,null,"view");
		if (!$isPermit){
			$this->redirect_nopermision();
			die();
		}				
	}

	public function add(){
		$isPermit = $this->aauth->is_permit($this->menuName,false,null,"add");
		if (!$isPermit){
			$this->redirect_nopermision();
			die();
		}
	}

	public function edit($id){
		$isPermit = $this->aauth->is_permit($this->menuName,false,null,"update");
		if (!$isPermit){
			$this->redirect_nopermision();
			die();
		}
	}

	public function ajx_add_save(){
		$isPermit = $this->aauth->is_permit($this->menuName,false,null,"add");
		if (!$isPermit){
			echo $this->json_output([
				"status"=>"FAILED",
				"messages"=>lang("Anda Tidak memiliki otoritas !"),
				"data"=>[]
			]);
			die();
		}
	}

	public function ajx_edit_save(){
		$isPermit = $this->aauth->is_permit($this->menuName,false,null,"update");
		if (!$isPermit){
			echo $this->json_output([
				"status"=>"FAILED",
				"messages"=>lang("Anda Tidak memiliki otoritas !"),
				"data"=>[]
			]);
			die();
		}
	}

	public function delete($id){
		$isPermit = $this->aauth->is_permit($this->menuName,false,null,"delete");
		if (!$isPermit){
			echo $this->json_output([
				"status"=>"FAILED",
				"messages"=>lang("Anda Tidak memiliki otoritas !"),
				"data"=>[]
			]);
			die();
		}
	}

	function redirect_nopermision(){
		$this->load->library("menus");		
		$this->load->model("users_model");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		
		$data = [
			"title"=>"Access Restricted."
		];

		$page_content = $this->parser->parse('pages/no_permission', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		
		$a  = $this->parser->parse('template/main', $this->data, true);
		echo $a;

	}
	



}

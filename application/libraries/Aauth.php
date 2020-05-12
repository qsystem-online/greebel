<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Aauth {
	public $CI;
	private $user;
	public function __construct() {
		$this->CI = & get_instance();		
		$this->CI->load->library("session");		
		$this->user = $this->CI->session->userdata("active_user");
		/*
		if ($this->user == null){
			if ($this->CI->input->is_ajax_request()){
				$resp =[];
				$resp["status"] = AJAX_STATUS_SESSION_EXPIRED;
				$this->CI->session->unset_userdata("last_uri");
				header('Content-Type: application/json');
				http_response_code($http_code);
				echo json_encode($resp);

			}else{
				redirect('/login', 'refresh');
			}
		}
		*/
		

	}

	public function user(){
		return $this->user;
	}

	public function get_user_id(){
		if ($this->user){
			return $this->user->fin_user_id;
		}else{
			return 0;
		}
		
	}
	public function is_login(){		
		if ($this->user == null){
			return false;
		}
		if ($this->is_session_timeout()){
			return false;
		}
		//cek session timeout;
		return true;
	}


	public function renew_session_timeout(){
		$this->CI->session->set_userdata("last_login_session",time());

	}

	public function is_session_timeout(){
		//Cek Login Session Timeout
		$lastTimestamp = $this->CI->session->userdata("last_login_session");
		$currentTimestamp = time();
		$loginTimeout = $this->CI->config->item("login_timeout"); //seconds
		//echo $loginTimeout . ':' . ($currentTimestamp - $lastTimestamp);
		if ($currentTimestamp - $lastTimestamp  > $loginTimeout){
			return true;
		}else{
			return false;
		}
	}
	
	public function is_permit($permission_name,$notRecordDefault = true,$user = null,$mode = "view"){		
		if ($permission_name == "dashboard_v2"){
			return false;	
		}
		if ($user == null){
			$user = $this->CI->aauth->user();
		}
		//Cek privileges by id
		$ssql ="SELECT * FROM usergroupprivileges where fin_user_id = ? and fst_menu_name = ? and fst_active ='A'";
		$qr = $this->CI->db->query($ssql,[$user->fin_user_id,$permission_name]);
		$rw = $qr->row();
		if ($rw != null){
			switch($mode){
				case "view":
					return $rw->fbl_view;
					break;
				case "add":
					return $rw->fbl_add;
					break;
				case "update":
					return $rw->fbl_update;
					break;
				case "delete":
					return $rw->fbl_delete;
					break;
				default:
					return $notRecordDefault;
					break;
			}
		}else{
			//cek privileges by group
			$privilegesGroup = $user->fst_privilege_group;
			$ssql ="SELECT * FROM usergroupprivileges where fst_privilege_group = ? and fst_menu_name = ? and fst_active ='A'";
			$qr = $this->CI->db->query($ssql,[$privilegesGroup,$permission_name]);
			$rw = $qr->row();
			if ($rw != null){
				switch($mode){
					case "view":
						return $rw->fbl_view;
						break;
					case "add":
						return $rw->fbl_add;
						break;
					case "update":
						return $rw->fbl_update;
						break;
					case "delete":
						return $rw->fbl_delete;
						break;
					default:
						return $notRecordDefault;
						break;
				}
			}
		}	
		return $notRecordDefault;
	}

	public function get_active_branch_id(){
		return (int) $this->CI->session->userdata('active_branch_id');
	}
	
	public function get_active_branch(){
		$this->CI->load->model("msbranches_model");
		return $this->CI->msbranches_model->getBranchById($this->get_active_branch_id());
	}
}
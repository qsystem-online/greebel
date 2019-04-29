<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Aauth {
	public $CI;
	private $user;
	public function __construct() {
		$this->CI = & get_instance();		
		$this->CI->load->library("session");
		$this->user = $this->CI->session->userdata("active_user");
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
	



	public function is_permit($permission_name,$notRecordDefault = true,$user = null){		
		if ($permission_name == "dashboard_v2"){
			return false;	
		}
		return true;		
	}

}
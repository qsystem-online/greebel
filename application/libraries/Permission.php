<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class delete_Permission {
	public $CI;

	public function __construct() {
		$this->CI = & get_instance();		
	}

	public function is_permit($permission_name,$notRecordDefault = true,$user = null){
		if ($permission_name == "dashboard_v2"){
			return false;	
		}
		return true;
		
	}
	
}
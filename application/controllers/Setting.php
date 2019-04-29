<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends MY_Controller {
	function set_sidebar_collapse($value){		
		$this->session->set_userdata('sidebar_collapse', $value);	
		//$this->session->userdata('sidebar_collapse');
	}

	
}

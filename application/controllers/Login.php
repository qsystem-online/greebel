<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public $data = [];
	public function index(){
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		if ($username != ""){
			$ssql = "select * from users where fst_username = ?";
			$query = $this->db->query($ssql,[$username]);
			$rw = $query->row();
			$strIvalidLogin = "Invalid Username / Password";

			if ($rw){
				if (md5($password) == $rw->fst_password){
					$this->session->set_userdata("active_user",$rw);
					$this->session->set_userdata("last_login_session",time());
					if($this->session->userdata("last_uri")){
						redirect(site_url().$this->session->userdata("last_uri"), 'refresh');
					}else{
						redirect(site_url().'home', 'refresh');	
					}
					
				}else{
					$this->data["message"] = $strIvalidLogin;	
				}
			}else{
				$this->data["message"] = $strIvalidLogin;
			}
		}
		$this->parser->parse('pages/login',$this->data);
	}

	public function signout($type = "logout"){
		$this->session->unset_userdata("active_user");
		if($type != "expired"){
			$this->session->unset_userdata("last_uri");
		}		
		redirect('/login', 'refresh');
	}
	
}
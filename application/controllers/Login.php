<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	public $data = [];
	public function index()
	{
		
		$this->load->model("users_model");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		if ($username != "") {
			$ssql = "select a.*,b.fin_branch_id AS ActiveBranch ,b.fst_branch_name, b.fbl_is_hq, c.fin_group_id,c.fst_group_name,c.fin_level from users a left join msbranches b on a.fin_branch_id = b.fin_branch_id left join usersgroup c on a.fin_group_id = c.fin_group_id where fst_username = ?";

			$query = $this->db->query($ssql, [$username]);
			$rw = $query->row();
			$strIvalidLogin = "Invalid Username / Password";

			if ($rw) {
				if (md5($password) == $rw->fst_password) {
					$this->session->set_userdata("active_user", $this->users_model->getDataById($rw->fin_user_id)["user"]);
					$this->session->set_userdata("active_branch_id", $rw->ActiveBranch);
					$this->session->set_userdata("last_login_session", time());
					if ($this->session->userdata("last_uri")) {
						redirect(site_url() . $this->session->userdata("last_uri"), 'refresh');
					} else {
						redirect(site_url() . 'home', 'refresh');
					}
				} else {
					$this->data["message"] = $strIvalidLogin;
				}
			} else {
				$this->data["message"] = $strIvalidLogin;
			}
		}
		$this->parser->parse('pages/login', $this->data);
		
	}

	public function signout($type = "logout")
	{
		$this->session->unset_userdata("active_user");
		if ($type != "expired") {
			$this->session->unset_userdata("last_uri");
		}
		redirect('/login', 'refresh');
	}
	public function test(){
		echo "TEST";
	}
}

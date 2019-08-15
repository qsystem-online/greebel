<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');
	}
	
	public function index(){
		$this->load->library("menus");
		
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$this->data["title"] = "Dashboard";
		$this->data["ttlApproved"] = formatNumber($this->dashboard_model->getTtlApproved());
		$this->data["ttlNeedApproval"] = formatNumber($this->dashboard_model->getTtlNeedApproval());
		$this->data["ttlChangeAfterApproved"] = formatNumber($this->dashboard_model->getTtlChangeAfterApproved());
		$this->data["ttlVoidAuthorize"] = formatNumber($this->dashboard_model->getTtlVoidAuthorize());

		$page_content = $this->parser->parse('pages/dashboard/dashboard', $this->data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
	}
	
}
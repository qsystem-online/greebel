<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		$this->load->library("menus");

		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
		$page_content = $this->parser->parse('pages/sample/template_sample',[],true);
		$main_footer = $this->parser->parse('inc/main_footer',[],true);
		//$control_sidebar = $this->parser->parse('inc/control_sidebar',[],true);
		$control_sidebar = NULL;

		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;


		$this->parser->parse('template/main',$this->data);
	}


	public function dashboard1(){
		$this->index();
	}

	public function dashboard2(){
		$this->index();
	}
	public function general_element(){
		
		$this->load->library("menus");
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
		$page_content = $this->parser->parse('pages/sample/general_form',[],true);
		$main_footer = $this->parser->parse('inc/main_footer',[],true);
		//$control_sidebar = $this->parser->parse('inc/control_sidebar',[],true);
		$control_sidebar = NULL;

		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);

	}
	public function advanced_element(){
		$this->index();
	}

	public function editor($var=1){
		$this->index();
	}

	public function pagination(){
		$this->load->library("pagination");

		$paginationConfig = $this->config->item("pagination");

		$paginationConfig['base_url'] = site_url('welcome/pagination');
		$paginationConfig['total_rows'] = 300;
		$paginationConfig['per_page'] = 30;
		$paginationConfig['uri_segment'] =3;

		$choice = $paginationConfig['total_rows'] / $paginationConfig["per_page"];
        $paginationConfig["num_links"] = floor($choice);

		

        $this->pagination->initialize($paginationConfig);


        //$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;        
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->library("menus");
		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
		$page_content = $this->parser->parse('pages/sample/pagging',$this->data,true);
		$main_footer = $this->parser->parse('inc/main_footer',[],true);
		//$control_sidebar = $this->parser->parse('inc/control_sidebar',[],true);
		$control_sidebar = NULL;

		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);







	}





}

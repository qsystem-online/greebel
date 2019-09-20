<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kas_bank extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
    }

    public function pengeluaran_add(){
        $this->openFormPengeluaran("ADD", 0);

    }


    private function openFormPengeluaran($mode = "ADD", $fin_inv_id = 0){
        $this->load->library("menus");		
        $this->load->model("glaccounts_model");
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Pengeluaran") : lang("Update Pengeluaran");
     
		if($mode == 'ADD'){
					
		}else{
			
        }
        
		
		$page_content = $this->parser->parse('pages/tr/kas_bank/pengeluaran/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}


}    
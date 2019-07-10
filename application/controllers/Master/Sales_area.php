<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_area extends MY_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->view('area');
        $this->load->view('regional');
        $this->load->view('national');
    }

    public function area() {
        $this->area_lizt();
    }

    public function area_lizt() {
        $this->load->library('menus');
        $this->list['page_name'] = "Master Sales Area";
        $this->list['list_name'] = "Sales Area List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/sales_area/add_area';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/sales_area/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/sales_area/delete_area/';
        $this->list['edit_ajax_url'] = site_url() . 'master/sales_area/edit_area/';
        $this->list['arrSearch'] = [
            'fin_sales_area_id' => 'Sales Area ID',
            'fst_name' => 'Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Sales Area', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        
        $this->list['columns'] = [
            ['title' => 'Sales Area ID', 'width' => '8%', 'data' => 'fin_sales_area_id'],
            ['title' => 'Name', 'width' => '15%', 'data' => 'fst_name'],
            ['title' => 'Sales Regional Name', 'width' => '15%', 'data' => 'fin_sales_regional_id'],
            ['title' => 'Sales Name', 'width' => '12%', 'data' => 'fin_sales_id'],
            ['title' => 'Action', 'width' => '5%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
        ];
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $page_content = $this->parser->parse('template/standardList', $this->list, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main', $this->data);
    }

    private function areaForm($mode = "ADD", $fin_sales_area_id = 0) {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Sales Area" : "Update Master Sales Area";
        $data["fin_sales_area_id"] = $fin_sales_area_id;

        $page_content = $this->parser->parse('pages/master/sales_area/area/form', $data, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);

        $control_sidebar = NULL;
        $this->data["MAIN_HEADER"] = $main_header;
        $this->data["MAIN_SIDEBAR"] = $main_sidebar;
        $this->data["PAGE_CONTENT"] = $page_content;
        $this->data["MAIN_FOOTER"] = $main_footer;
        $this->data["CONTROL_SIDEBAR"] = $control_sidebar;
        $this->parser->parse('template/main', $this->data);
    }

    public function add_area() {
        $this->areaForm("ADD", 0);
    }

    public function Edit_area($fin_sales_area_id){
        $this->areaForm("EDIT", $fin_sales_area_id);
    }

    public function ajx_add_save() {
        $this->load->model('mssalesarea_model');
        $this->form_validation->set_rules($this->mssalesarea_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fst_name" => $this->input->post("fst_name"),
            "fin_sales_regional_id" => $this->input->post("fin_sales_regional_id"),
            "fin_sales_id" => $this->input->post("fin_sales_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->mssalesarea_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function ajx_edit_save() {
        $this->load->model('mssalesarea_model');
		$fin_sales_area_id = $this->input->post("fin_sales_area_id");
		$data = $this->mssalesarea_model->getDataById($fin_sales_area_id);
		$mssalesarea = $data["sales_area_area"];
		if (!$mssalesarea) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_sales_area_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

        $this->form_validation->set_rules($this->mssalesarea_model->getRules("EDIT", $fin_sales_area_id));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fin_sales_area_id" => $fin_sales_area_id,
            "fst_name" => $this->input->post("fst_name"),
            "fin_sales_regional_id" => $this->input->post("fin_sales_regional_id"),
            "fin_sales_id" => $this->input->post("fin_sales_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $this->mssalesarea_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $fin_sales_area_id;
        $this->json_output();
    }

    public function fetch_list_data() {
        $this->load->library("datatables");
        $this->datatables->setTableName("mssalesarea");

        $selectFields = "fin_sales_area_id,fst_name,fin_sales_regional_id,fin_sales_id,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $searchFields =[];
		$searchFields[] = $this->input->get('optionSearch');
		$this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";
        
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='".$data["fin_sales_area_id"]."'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='".$data["fin_sales_area_id"]."' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }

    public function fetch_data($fin_sales_area_id) {
		$this->load->model("mssalesarea_model");
		$data = $this->mssalesarea_model->getDataById($fin_sales_area_id);
	
		$this->json_output($data);
    }

//== SALES AREA REGIONAL ================================================================================================================================================

    public function regional() {
        $this->regional_lizt();
    }

    private function regionalForm($mode = "ADD", $fin_sales_regional_id = 0) {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Sales Regional" : "Update Master Sales Regional";
        $data["fin_sales_regional_id"] = $fin_sales_regional_id;

        $page_content = $this->parser->parse('pages/master/sales_area/regional/form', $data, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);

        $control_sidebar = NULL;
        $this->data["MAIN_HEADER"] = $main_header;
        $this->data["MAIN_SIDEBAR"] = $main_sidebar;
        $this->data["PAGE_CONTENT"] = $page_content;
        $this->data["MAIN_FOOTER"] = $main_footer;
        $this->data["CONTROL_SIDEBAR"] = $control_sidebar;
        $this->parser->parse('template/main', $this->data);
    }

    public function regional_lizt() {
        $this->load->library('menus');
        $this->list['page_name'] = "Master Sales Regional";
        $this->list['list_name'] = "Sales Regional List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/sales_area/add_regional';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/sales_area/regional_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/sales_area/delete_reg/';
        $this->list['edit_ajax_url'] = site_url() . 'master/sales_area/edit_reg/';
        $this->list['arrSearch'] = [
            'fin_sales_regional_id' => 'Sales Regional ID',
            'fst_name' => 'Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Sales Area', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        
        $this->list['columns'] = [
            ['title' => 'Sales Regional ID', 'width' => '8%', 'data' => 'fin_sales_regional_id'],
            ['title' => 'Name', 'width' => '15%', 'data' => 'fst_name'],
            ['title' => 'Sales National Name', 'width' => '15%', 'data' => 'fin_sales_national_id'],
            ['title' => 'Sales Name', 'width' => '12%', 'data' => 'fin_sales_id'],
            ['title' => 'Action', 'width' => '5%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
        ];
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $page_content = $this->parser->parse('template/standardList', $this->list, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main', $this->data);
    }

    public function add_regional() {
        $this->regionalForm("ADD", 0);
    }

    public function Edit_reg($fin_sales_regional_id){
        $this->regionalForm("EDIT", $fin_sales_regional_id);
    }

    public function reg_add_save() {
        $this->load->model('mssalesregional_model');
        $this->form_validation->set_rules($this->mssalesregional_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fst_name" => $this->input->post("fst_name"),
            "fin_sales_national_id" => $this->input->post("fin_sales_national_id"),
            "fin_sales_id" => $this->input->post("fin_sales_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->mssalesregional_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function reg_edit_save() {
        $this->load->model('mssalesregional_model');
		$fin_sales_regional_id = $this->input->post("fin_sales_regional_id");
		$data = $this->mssalesregional_model->getDataById($fin_sales_regional_id);
		$mssalesregional = $data["sales_area_regional"];
		if (!$mssalesregional) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_sales_regional_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

        $this->form_validation->set_rules($this->mssalesregional_model->getRules("EDIT", $fin_sales_regional_id));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fin_sales_regional_id" => $fin_sales_regional_id,
            "fst_name" => $this->input->post("fst_name"),
            "fin_sales_national_id" => $this->input->post("fin_sales_national_id"),
            "fin_sales_id" => $this->input->post("fin_sales_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $this->mssalesregional_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $fin_sales_regional_id;
        $this->json_output();
    }

    public function regional_list_data() {
        $this->load->library("datatables");
        $this->datatables->setTableName("mssalesregional");

        $selectFields = "fin_sales_regional_id,fst_name,fin_sales_national_id,fin_sales_id,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $searchFields =[];
		$searchFields[] = $this->input->get('optionSearch');
		$this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";
        
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='".$data["fin_sales_regional_id"]."'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='".$data["fin_sales_regional_id"]."' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }
    
    public function regional_data($fin_sales_regional_id) {
        $this->load->model("mssalesregional_model");
        $data = $this->mssalesregional_model->getDataById($fin_sales_regional_id);

        $this->json_output($data);
    }

//== SALES AREA NATIONAL ================================================================================================================================================

    public function national() {
        $this->national_lizt();
    }

    private function nationalForm($mode = "ADD", $fin_sales_national_id = 0) {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Sales National" : "Update Master Sales National";
        $data["fin_sales_national_id"] = $fin_sales_national_id;

        $page_content = $this->parser->parse('pages/master/sales_area/national/form', $data, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);

        $control_sidebar = NULL;
        $this->data["MAIN_HEADER"] = $main_header;
        $this->data["MAIN_SIDEBAR"] = $main_sidebar;
        $this->data["PAGE_CONTENT"] = $page_content;
        $this->data["MAIN_FOOTER"] = $main_footer;
        $this->data["CONTROL_SIDEBAR"] = $control_sidebar;
        $this->parser->parse('template/main', $this->data);
    }

    public function national_lizt() {
        $this->load->library('menus');
        $this->list['page_name'] = "Master Sales National";
        $this->list['list_name'] = "Sales National List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/sales_area/add_national';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/sales_area/national_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/sales_area/delete_nat/';
        $this->list['edit_ajax_url'] = site_url() . 'master/sales_area/edit_nat/';
        $this->list['arrSearch'] = [
            'fin_sales_national_id' => 'Sales National ID',
            'fst_name' => 'Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Sales National', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        
        $this->list['columns'] = [
            ['title' => 'Sales National ID', 'width' => '10%', 'data' => 'fin_sales_national_id'],
            ['title' => 'Name', 'width' => '15%', 'data' => 'fst_name'],
            ['title' => 'Sales Name', 'width' => '15%', 'data' => 'fin_sales_id'],
            ['title' => 'Action', 'width' => '5%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
        ];
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $page_content = $this->parser->parse('template/standardList', $this->list, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main', $this->data);
    }

    public function add_national() {
        $this->nationalForm("ADD", 0);
    }

    public function Edit_nat($fin_sales_national_id){
        $this->nationalForm("EDIT", $fin_sales_national_id);
    }

    public function nat_add_save() {
        $this->load->model('mssalesnational_model');
        $this->form_validation->set_rules($this->mssalesnational_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fst_name" => $this->input->post("fst_name"),
            "fin_sales_id" => $this->input->post("fin_sales_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->mssalesnational_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function nat_edit_save() {
        $this->load->model('mssalesnational_model');
		$fin_sales_national_id = $this->input->post("fin_sales_national_id");
		$data = $this->mssalesnational_model->getDataById($fin_sales_national_id);
		$mssalesnational = $data["sales_area_national"];
		if (!$mssalesnational) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_sales_national_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

        $this->form_validation->set_rules($this->mssalesnational_model->getRules("EDIT", $fin_sales_national_id));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fin_sales_national_id" => $fin_sales_national_id,
            "fst_name" => $this->input->post("fst_name"),
            "fin_sales_id" => $this->input->post("fin_sales_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $this->mssalesnational_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $fin_sales_national_id;
        $this->json_output();
    }

    public function national_list_data() {
        $this->load->library("datatables");
        $this->datatables->setTableName("mssalesnational");

        $selectFields = "fin_sales_national_id,fst_name,fin_sales_id,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_sales_national_id", "fst_name"];
		$this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";
        
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='".$data["fin_sales_national_id"]."'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='".$data["fin_sales_national_id"]."' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }

    public function national_data($fin_sales_national_id) {
        $this->load->model("mssalesnational_model");
        $data = $this->mssalesnational_model->getDataById($fin_sales_national_id);

        $this->json_output($data);
    }

    //=========================================================================================================================================================

	public function delete($id) {
		if(!$this->aauth->is_permit("")){
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}
		
        $this->load->model("mssalesarea_model");
        $this->load->model("mssalesregional_model");
        $this->load->model("mssalesnational_model");
        $this->mssalesarea_model->delete($id);
        $this->mssalesregional_model->delete($id);
        $this->mssalesnational_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
    }
    
    public function get_salesId(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_user_id, fst_username from users where fin_department_id = 2";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
		$this->json_output();
    }
    
    public function get_salRegional(){
		$term = $this->input->get("term");
		$ssql = "SELECT fst_name, fin_sales_regional_id FROM mssalesregional WHERE fst_name LIKE ? ORDER BY fst_name";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
    }

    public function get_salNational(){
        $term = $this->input->get("term");
        $ssql = "SELECT fst_name, fin_sales_national_id FROM mssalesnational WHERE fst_name LIKE ? ORDER BY fst_name";
        $qr = $this->db->query($ssql,['%'.$term.'%']);
        $rs = $qr->result();

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
    }

}
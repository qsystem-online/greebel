<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prefix_cash_bank extends MY_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('kasbank_model');
    }

    public function index(){
        $this->lizt();
    }

    public function lizt(){
        $this->load->library('menus');
        $this->list['page_name'] = "Master Prefix Trans Kas/Bank";
        $this->list['list_name'] = "Prefix Trans Kas/Bank List";
        $this->list['addnew_ajax_url'] = site_url() . 'gl/config/prefix_cash_bank/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'gl/config/prefix_cash_bank/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'gl/config/prefix_cash_bank/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'gl/config/prefix_cash_bank/edit/';
        $this->list['arrSearch'] = [
            'fin_kasbank_id' => 'Kas/Bank ID',
            'fst_kasbank_name' => 'Kas/Bank Name'
        ];
        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Prefix Trans Kas/Bank', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Kas/Bank ID', 'width' => '10%', 'data' => 'fin_kasbank_id'],
            ['title' => 'Kas/Bank Name', 'width' => '20%', 'data' => 'fst_kasbank_name'],
            ['title' => 'Prefix Pengeluaran', 'width' => '13%', 'data' => 'fst_prefix_pengeluaran'],
            ['title' => 'Prefix Pemasukan', 'width' => '13%', 'data' => 'fst_prefix_pemasukan'],
            ['title' => 'Type', 'width' => '8%', 'data' => 'fst_type'],
            ['title' => 'GL Account Code', 'width' => '13%', 'data' => 'fst_gl_account_code'],
            ['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
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

    public function openForm($mode = 'ADD', $fin_kasbank_id = 0){
        $this->load->library("menus");
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Prefix Trans Kas/Bank" : "Update Prefix Trans Kas/Bank";
		$data["fin_kasbank_id"] = $fin_kasbank_id;

		$page_content = $this->parser->parse('pages/gl/prefix_cash_bank/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
    }

    public function add(){
        $this->openForm("ADD", 0);
    }

    public function Edit($fin_kasbank_id){
        $this->openForm("EDIT", $fin_kasbank_id);
    }

    public function ajx_add_save(){
        $this->load->model('kasbank_model');
		$this->form_validation->set_rules($this->kasbank_model->getRules("ADD", 0));
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
            "fst_kasbank_name" => $this->input->post("fst_kasbank_name"),
            "fst_prefix_pengeluaran" => $this->input->post("fst_prefix_pengeluaran"),
            "fst_prefix_pemasukan" => $this->input->post("fst_prefix_pemasukan"),
            "fst_type" => $this->input->post("fst_type"),
            "fst_gl_account_code" =>$this->input->post("fst_gl_account_code"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->kasbank_model->insert($data);
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

    public function ajx_edit_save(){
        $this->load->model('kasbank_model');
		$fin_kasbank_id = $this->input->post("fin_kasbank_id");
		$data = $this->kasbank_model->getDataById($fin_kasbank_id);
		$mskasbank = $data["ms_kasbank"];
		if (!$mskasbank) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_kasbank_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->kasbank_model->getRules("EDIT", $fin_kasbank_id));
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
			"fin_kasbank_id" => $fin_kasbank_id,
            "fst_kasbank_name" => $this->input->post("fst_kasbank_name"),
            "fst_prefix_pengeluaran" => $this->input->post("fst_prefix_pengeluaran"),
            "fst_prefix_pemasukan" => $this->input->post("fst_prefix_pemasukan"),
            "fst_type" => $this->input->post("fst_type"),
            "fst_gl_account_code" =>$this->input->post("fst_gl_account_code"),
			"fst_active" => 'A' 
		];

		$this->db->trans_start();

		$this->kasbank_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fin_kasbank_id;
		$this->json_output();
    }

    public function fetch_list_data(){
        $this->load->library("datatables");
		$this->datatables->setTableName("mskasbank");
		
		$selectFields = "fin_kasbank_id,fst_kasbank_name,fst_prefix_pengeluaran,fst_prefix_pemasukan,fst_type,fst_gl_account_code,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_kasbank_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_kasbank_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }

    public function fetch_data($fin_kasbank_id){
        $this->load->model("kasbank_model");
		$data = $this->kasbank_model->getDataById($fin_kasbank_id);
		
		$this->json_output($data);
    }

    public function get_Glaccounts(){
        $term = $this->input->get("term");
        $ssql = "SELECT fst_glaccount_code from glaccounts where fst_glaccount_code like ? ";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function delete(){
        $this->db->trans_start();
        $this->kasbank_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }

    public function getAllList(){
        $this->load->model('kasbank_model');
        $result = $this->kasbank_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
	}
}
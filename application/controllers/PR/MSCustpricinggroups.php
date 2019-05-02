<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MSCustpricinggroups extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('MSCustpricinggroups_model');
	}

	public function index(){
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Master Cust Pricing Groups";
		$this->list['list_name'] = "Master Cust Pricing Groups List";
		$this->list['addnew_ajax_url'] = site_url() . 'pr/mscustpricinggroups/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'pr/mscustpricinggroups/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'pr/mscustpricinggroups/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'pr/mscustpricinggroups/edit/';
		$this->list['arrSearch'] = [
			'CustPricingGroupId' => 'Groups ID',
			'CustPricingGroupName' => 'Group Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Master Cust Pricing Groups', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Pricing Group Id', 'width' => '20%', 'data' => 'CustPricingGroupId'],
            ['title' => 'Pricing Group Name', 'width' => '30%', 'data' => 'CustPricingGroupName'],
            ['title' => 'Percent (%)', 'width' => '10%', 'data' => 'PercentOfPriceList'],
            ['title' => 'Amount', 'width' => '15%', 'data' => 'DifferenceInAmount'],
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

	private function openForm($mode = "ADD", $CustPricingGroupId = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Cust Pricing Groups" : "Update Master Cust Pricing Groups";
		$data["CustPricingGroupId"] = $CustPricingGroupId;

		$page_content = $this->parser->parse('pages/pr/mscustpricinggroups/form', $data, true);
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

	public function Edit($CustPricingGroupId){
		$this->openForm("EDIT", $CustPricingGroupId);
	}

	public function ajx_add_save(){
		$this->load->model('mscustpricinggroups_model');
		$this->form_validation->set_rules($this->mscustpricinggroups_model->getRules("ADD", 0));
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
            "CustPricingGroupName" => $this->input->post("CustPricingGroupName"),
            "PercentOfPriceList" => $this->input->post("PercentOfPriceList"),
            "DifferenceInAmount" =>$this->input->post("DifferenceInAmount"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->mscustpricinggroups_model->insert($data);
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
		$this->load->model('mscustpricinggroups_model');
		$CustPricingGroupId = $this->input->post("CustPricingGroupId");
		$data = $this->mscustpricinggroups_model->getDataById($CustPricingGroupId);
		$mscustpricinggroups = $data["mscustpricinggroups"];
		if (!$mscustpricinggroups) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $CustPricingGroupId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->mscustpricinggroups_model->getRules("EDIT", $CustPricingGroupId));
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
			"CustPricingGroupId" => $CustPricingGroupId,
            "CustPricingGroupName" => $this->input->post("CustPricingGroupName"),
            "PercentOfPriceList" => $this->input->post("PercentOfPriceList"),
            "DifferenceInAmount" =>$this->input->post("DifferenceInAmount"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->mscustpricinggroups_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $CustPricingGroupId;
		$this->json_output();
	}

	public function add_save(){
		$this->load->model('mscustpricinggroups_model');

		$data = [
			'CustPricingGroupName' => $this->input->get("CustPricingGroupName")
		];
		if ($this->db->insert('mscustpricinggroups', $data)) {
			echo "insert success";
		} else {
			$error = $this->db->error();
			print_r($error);
		}
		die();

		echo "Table Name :" . $this->mscustpricinggroups_model->getTableName();
		print_r($this->mscustpricinggroups_model->getRules());

		$this->form_validation->set_rules($this->mscustpricinggroups_model->rules);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			echo form_error();
		} else {
			echo "Success";
		}

		//print_r($upload_data);

		print_r($_FILES);
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("mscustpricinggroups");

		$selectFields = "CustPricingGroupId,CustPricingGroupName,PercentOfPriceList,DifferenceInAmount,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["CustPricingGroupId","CustPricingGroupName"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["CustPricingGroupId"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["CustPricingGroupId"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($CustPricingGroupId){
		$this->load->model("mscustpricinggroups_model");
		$data = $this->mscustpricinggroups_model->getDataById($CustPricingGroupId);
		
		$this->json_output($data);
	}

	public function delete($id){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->load->model("mscustpricinggroups_model");

		$this->mscustpricinggroups_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}
}
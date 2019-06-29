<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Discounts extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msitemdiscounts_model');
	}

	public function index(){
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Discounts";
		$this->list['list_name'] = "Discounts List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/discounts/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/discounts/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/discounts/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/discounts/edit/';
		$this->list['arrSearch'] = [
			'RecId' => 'Rec ID',
			'ItemDiscount' => 'Discounts'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Discounts', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Rec ID', 'width' => '10%', 'data' => 'RecId'],
			['title' => 'Discounts', 'width' => '25%', 'data' => 'ItemDiscount'],
			['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
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

	private function openForm($mode = "ADD", $RecId = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Discounts" : "Update Discounts";
		$data["RecId"] = $RecId;

		$page_content = $this->parser->parse('pages/master/discounts/form', $data, true);
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

	public function Edit($RecId){
		$this->openForm("EDIT", $RecId);
	}

	public function ajx_add_save(){
		$this->load->model('msitemdiscounts_model');
		$this->form_validation->set_rules($this->msitemdiscounts_model->getRules("ADD", 0));
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
			"ItemDiscount" => $this->input->post("ItemDiscount"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msitemdiscounts_model->insert($data);
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

	public function ajx_edit_save()
	{
		$this->load->model('msitemdiscounts_model');
		$RecId = $this->input->post("RecId");
		$data = $this->msitemdiscounts_model->getDataById($RecId);
		$Discountss = $data["Discountss"];
		if (!$Discountss) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $RecId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msitemdiscounts_model->getRules("EDIT", $RecId));
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
			"RecId" => $RecId,
			"ItemDiscount" => $this->input->post("ItemDiscount"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->msitemdiscounts_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $RecId;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("msitemdiscounts");
		
		$selectFields = "RecId,ItemDiscount,'action' as action";
		$this->datatables->setSelectFields($selectFields);
		
		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["RecId","ItemDiscount"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["RecId"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["RecId"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($RecId){
		$this->load->model("msitemdiscounts_model");
		$data = $this->msitemdiscounts_model->getDataById($RecId);

		$this->json_output($data);
	}

	public function delete($id){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->load->model("msitemdiscounts_model");

		$this->msitemdiscounts_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}

	/*public function report_Discounts(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("msitemdiscounts_model");
		$listDiscounts = $this->msitemdiscounts_model->get_Discountss();
        $data = [
			"datas" => $listDiscounts
		];
			
        $this->pdf->load_view('report/discounts_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }*/
}

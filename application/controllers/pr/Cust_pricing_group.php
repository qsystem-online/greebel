<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cust_pricing_group extends MY_Controller{
	public $menuName="pricing_group"; 
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('mscustpricinggroups_model');
	}

	public function index(){
		parent::index();
		$this->lizt();
	}

	public function lizt(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Master Cust Pricing Groups";
		$this->list['list_name'] = "Master Cust Pricing Groups List";
		$this->list['addnew_ajax_url'] = site_url() . 'pr/cust_pricing_group/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'pr/cust_pricing_group/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'pr/cust_pricing_group/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'pr/cust_pricing_group/edit/';
		$this->list['arrSearch'] = [
			'fin_cust_pricing_group_id' => 'Groups ID',
			'fst_cust_pricing_group_name' => 'Group Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Master Cust Pricing Groups', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Pricing Group Id', 'width' => '20%', 'data' => 'fin_cust_pricing_group_id'],
            ['title' => 'Pricing Group Name', 'width' => '30%', 'data' => 'fst_cust_pricing_group_name'],
            ['title' => 'Percent (%)', 'width' => '10%', 'data' => 'fdc_percent_of_price_list'],
			['title' => 'Amount', 'width' => '15%', 'data' => 'fdc_difference_in_amount',
				'render' => '$.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT)'
			],
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

	private function openForm($mode = "ADD", $fin_cust_pricing_group_id = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Cust Pricing Groups" : "Update Master Cust Pricing Groups";
		$data["fin_cust_pricing_group_id"] = $fin_cust_pricing_group_id;

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
		parent::add();
		$this->openForm("ADD", 0);
	}

	public function edit($fin_cust_pricing_group_id){
		parent::edit($fin_cust_pricing_group_id);
		$this->openForm("EDIT", $fin_cust_pricing_group_id);
	}

	public function ajx_add_save(){
		parent::ajx_add_save();
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
            "fst_cust_pricing_group_name" => $this->input->post("fst_cust_pricing_group_name"),
            "fdc_percent_of_price_list" => $this->input->post("fdc_percent_of_price_list"),
            "fdc_difference_in_amount" =>$this->input->post("fdc_difference_in_amount"),
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
		parent::ajx_edit_save();
		$this->load->model('mscustpricinggroups_model');
		$fin_cust_pricing_group_id = $this->input->post("fin_cust_pricing_group_id");
		$data = $this->mscustpricinggroups_model->getDataById($fin_cust_pricing_group_id);
		$mscustpricinggroups = $data["ms_custpricinggroups"];
		if (!$mscustpricinggroups) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_cust_pricing_group_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->mscustpricinggroups_model->getRules("EDIT", $fin_cust_pricing_group_id));
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
			"fin_cust_pricing_group_id" => $fin_cust_pricing_group_id,
            "fst_cust_pricing_group_name" => $this->input->post("fst_cust_pricing_group_name"),
            "fdc_percent_of_price_list" => $this->input->post("fdc_percent_of_price_list"),
            "fdc_difference_in_amount" =>$this->input->post("fdc_difference_in_amount"),
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
		$this->ajxResp["data"]["insert_id"] = $fin_cust_pricing_group_id;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("mscustpricinggroups");

		$selectFields = "fin_cust_pricing_group_id,fst_cust_pricing_group_name,fdc_percent_of_price_list,fdc_difference_in_amount,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_cust_pricing_group_id","fst_cust_pricing_group_name"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_cust_pricing_group_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fin_cust_pricing_group_id){
		$this->load->model("mscustpricinggroups_model");
		$data = $this->mscustpricinggroups_model->getDataById($fin_cust_pricing_group_id);
		
		$this->json_output($data);
	}

	public function delete($id) {
		parent::delete($id);
		$this->load->model("mscustpricinggroups_model");
		$this->db->trans_start();
        $this->mscustpricinggroups_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function report_custpricinggroups(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("mscustpricinggroups_model");
		$listCustPricingGroups = $this->mscustpricinggroups_model->get_CustPricingGroups();
        $data = [
			"datas" => $listCustPricingGroups
		];
			
        $this->pdf->load_view('report/custpricinggroups_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
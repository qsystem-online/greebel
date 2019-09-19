<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfitCostCenter extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msprofitcostcenter_model');
	}

	public function index()
	{
		$this->lizt();
	}

	public function lizt()
	{
		$this->load->library('menus');
		$this->list['page_name'] = "Profit & Cost Center";
		$this->list['list_name'] = "Profit & Cost Center List";
		$this->list['addnew_ajax_url'] = site_url() . 'gl/profit_cost_center/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'gl/profit_cost_center/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'gl/profit_cost_center/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'gl/profit_cost_center/edit/';
		$this->list['arrSearch'] = [
			'fin_pcc_id' => 'Profit & Cost Center ID',
			'fst_pcc_name' => 'Profit & Cost Center Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Profit & Cost Center', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Profit & Cost Center ID', 'width' => '10%', 'data' => 'fin_pcc_id'],
			['title' => 'Profit & Cost Center Name', 'width' => '25%', 'data' => 'fst_pcc_name'],
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

	private function openForm($mode = "ADD", $fin_pcc_id = 0)
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Profit & Cost Center" : "Update Profit & Cost Center";
		$data["fin_pcc_id"] = $fin_pcc_id;

		$page_content = $this->parser->parse('pages/gl/profit_cost_center/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function add()
	{
		$this->openForm("ADD", 0);
	}

	public function Edit($fin_pcc_id)
	{
		$this->openForm("EDIT", $fin_pcc_id);
	}

	public function ajx_add_save()
	{
		$this->load->model('msprofitcostcenter_model');
		$this->form_validation->set_rules($this->msprofitcostcenter_model->getRules("ADD", 0));
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
			"fst_pcc_name" => $this->input->post("fst_pcc_name"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msprofitcostcenter_model->insert($data);
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
		$this->load->model('msprofitcostcenter_model');
		$fin_pcc_id = $this->input->post("fin_pcc_id");
		$data = $this->msprofitcostcenter_model->getDataById($fin_pcc_id);
		$profitcostcenter = $data["profit_cost_center"];
		if (!$profitcostcenter) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_pcc_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msprofitcostcenter_model->getRules("EDIT", $fin_pcc_id));
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
			"fin_pcc_id" => $fin_pcc_id,
			"fst_pcc_name" => $this->input->post("fst_pcc_name"),
			"fst_active" => 'A' 
		];

		$this->db->trans_start();

		$this->msprofitcostcenter_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fin_pcc_id;
		$this->json_output();
	}

	public function add_save()
	{
		$this->load->model('msprofitcostcenter_model');

		$data = [
			'fst_pcc_name' => $this->input->get("fst_pcc_name")
		];
		if ($this->db->insert('msprofitcostcenter', $data)) {
			echo "insert success";
		} else {
			$error = $this->db->error();
			print_r($error);
		}
		die();

		echo "Table Name :" . $this->msprofitcostcenter_model->getTableName();
		print_r($this->msprofitcostcenter_model->getRules());

		$this->form_validation->set_rules($this->msprofitcostcenter_model->rules);
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
		$this->datatables->setTableName("msprofitcostcenter");
		
		$selectFields = "fin_pcc_id,fst_pcc_name,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_pcc_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_pcc_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($fin_pcc_id)
	{
		$this->load->model("msprofitcostcenter_model");
		$data = $this->msprofitcostcenter_model->getDataById($fin_pcc_id);

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($id){
		$this->db->trans_start();
        $this->msprofitcostcenter_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->msprofitcostcenter_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

	public function report_profitcostcenter(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("msprofitcostcenter_model");
		$listProfitCostCenter = $this->msprofitcostcenter_model->get_profitcostcenter();
        $data = [
			"datas" => $listProfitCostCenter
		];
			
        $this->pdf->load_view('report/profitcostcenter_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}

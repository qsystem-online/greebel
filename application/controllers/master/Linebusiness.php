<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Linebusiness extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('mslinebusiness_model');
	}

	public function index()
	{
		$this->lizt();
	}

	public function lizt()
	{
		$this->load->library('menus');
		$this->list['page_name'] = "Line of Business";
		$this->list['list_name'] = "Line of Business List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/linebusiness/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/linebusiness/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/linebusiness/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/linebusiness/edit/';
		$this->list['arrSearch'] = [
			'fin_linebusiness_id' => 'Line of business ID',
			'fst_linebusiness_name' => 'Line of business Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Line of business', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Line of business ID', 'width' => '10%', 'data' => 'fin_linebusiness_id'],
			['title' => 'Line of business Name', 'width' => '25%', 'data' => 'fst_linebusiness_name'],
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

	private function openForm($mode = "ADD", $fin_linebusiness_id = 0)
	{
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Line of Business" : "Update Line of Business";
		$data["fin_linebusiness_id"] = $fin_linebusiness_id;

		$page_content = $this->parser->parse('pages/master/linebusiness/form', $data, true);
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

	public function Edit($fin_linebusiness_id)
	{
		$this->openForm("EDIT", $fin_linebusiness_id);
	}

	public function ajx_add_save()
	{
		$this->load->model('mslinebusiness_model');
		$this->form_validation->set_rules($this->mslinebusiness_model->getRules("ADD", 0));
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
			"fst_linebusiness_name" => $this->input->post("fst_linebusiness_name"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->mslinebusiness_model->insert($data);
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
		$this->load->model('mslinebusiness_model');
		$fin_linebusiness_id = $this->input->post("fin_linebusiness_id");
		$data = $this->mslinebusiness_model->getDataById($fin_linebusiness_id);
		$linebusiness = $data["mslinebusiness"];
		if (!$linebusiness) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_linebusiness_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->mslinebusiness_model->getRules("EDIT", $fin_linebusiness_id));
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
			"fin_linebusiness_id" => $fin_linebusiness_id,
			"fst_linebusiness_name" => $this->input->post("fst_linebusiness_name"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();

		$this->mslinebusiness_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fin_linebusiness_id;
		$this->json_output();
	}

	public function add_save()
	{
		$this->load->model('mslinebusiness_model');

		$data = [
			'fst_linebusiness_name' => $this->input->get("fst_linebusiness_name")
		];
		if ($this->db->insert('departments', $data)) {
			echo "insert success";
		} else {
			$error = $this->db->error();
			print_r($error);
		}
		die();

		echo "Table Name :" . $this->mslinebusiness_model->getTableName();
		print_r($this->mslinebusiness_model->getRules());

		$this->form_validation->set_rules($this->mslinebusiness_model->rules);
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
		$this->datatables->setTableName("mslinebusiness");
		
		$selectFields = "fin_linebusiness_id,fst_linebusiness_name,'action' as action";
		$this->datatables->setSelectFields($selectFields);
		
		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fst_fullname","fst_birthplace"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_linebusiness_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($fin_linebusiness_id)
	{
		$this->load->model("mslinebusiness_model");
		$data = $this->mslinebusiness_model->getDataById($fin_linebusiness_id);

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($id){
		$this->db->trans_start();
        $this->mslinebusiness_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function getAllList()
	{
		$result = $this->mslinebusiness_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();
	}

	public function report_linebusiness(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("mslinebusiness_model");
		$listlinebusiness = $this->mslinebusiness_model->get_linebusiness();
        $data = [
			"datas" => $listlinebusiness
		];
			
        $this->pdf->load_view('report/departments_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}

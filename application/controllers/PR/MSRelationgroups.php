<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MSRelationgroups extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('MSRelationgroups_model');
	}

	public function index(){
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Master Relation Groups";
		$this->list['list_name'] = "Master Relation Groups List";
		$this->list['addnew_ajax_url'] = site_url() . 'pr/msrelationgroups/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'pr/msrelationgroups/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'pr/msrelationgroups/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'pr/msrelationgroups/edit/';
		$this->list['arrSearch'] = [
			'RelationGroupId' => 'Groups ID',
			'RelationGroupName' => 'Groups Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Master Relation Groups', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Relation Groups ID', 'width' => '10%', 'data' => 'RelationGroupId'],
			['title' => 'Relation Groups Name', 'width' => '25%', 'data' => 'RelationGroupName'],
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

	private function openForm($mode = "ADD", $RelationGroupId = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Relation Groups" : "Update Master Relation Groups";
		$data["RelationGroupId"] = $RelationGroupId;

		$page_content = $this->parser->parse('pages/pr/msrelationgroups/form', $data, true);
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

	public function Edit($RelationGroupId){
		$this->openForm("EDIT", $RelationGroupId);
	}

	public function ajx_add_save(){
		$this->load->model('MSRelationgroups_model');
		$this->form_validation->set_rules($this->MSRelationgroups_model->getRules("ADD", 0));
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
			"RelationGroupName" => $this->input->post("RelationGroupName"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->MSRelationgroups_model->insert($data);
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
		$this->load->model('MSRelationgroups_model');
		$RelationGroupId = $this->input->post("RelationGroupId");
		$data = $this->MSRelationgroups_model->getDataById($RelationGroupId);
		$msrelationgroups = $data["msrelationgroups"];
		if (!$msrelationgroups) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $RelationGroupId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->MSRelationgroups_model->getRules("EDIT", $RelationGroupId));
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
			"RelationGroupId" => $RelationGroupId,
			"RelationGroupName" => $this->input->post("RelationGroupName"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->MSRelationgroups_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $RelationGroupId;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("msrelationgroups");

		$selectFields = "RelationGroupId,RelationGroupName,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["RelationGroupId","RelationGroupName"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["RelationGroupId"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["RelationGroupId"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($RelationGroupId){
		$this->load->model("MSRelationgroups_model");
		$data = $this->MSRelationgroups_model->getDataById($RelationGroupId);

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($id){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->load->model("MSRelationgroups_model");

		$this->MSRelationgroups_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}

	public function report_relationgroups(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("msrelationgroups_model");
		$listRelationGroups = $this->msrelationgroups_model->get_RelationGroups();
        $data = [
			"datas" => $listRelationGroups
		];
			
        $this->pdf->load_view('report/relationgroups_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
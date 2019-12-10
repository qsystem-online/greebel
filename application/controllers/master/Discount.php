<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Discount extends MY_Controller{

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
		$this->list['addnew_ajax_url'] = site_url() . 'master/discount/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/discount/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/discount/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/discount/edit/';
		$this->list['arrSearch'] = [
			'fin_rec_id' => 'Rec ID',
			'fst_item_discount' => 'Discounts'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Discounts', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Rec ID', 'width' => '10%', 'data' => 'fin_rec_id'],
			['title' => 'Discounts', 'width' => '25%', 'data' => 'fst_item_discount'],
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

	private function openForm($mode = "ADD", $fin_rec_id = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Discounts" : "Update Discounts";
		$data["fin_rec_id"] = $fin_rec_id;

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

	public function Edit($fin_rec_id){
		$this->openForm("EDIT", $fin_rec_id);
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
			"fst_item_discount" => $this->input->post("fst_item_discount"),
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
		$fin_rec_id = $this->input->post("fin_rec_id");
		$data = $this->msitemdiscounts_model->getDataById($fin_rec_id);
		$msitemdiscounts = $data["ms_Discounts"];
		if (!$msitemdiscounts) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_rec_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msitemdiscounts_model->getRules("EDIT", $fin_rec_id));
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
			"fin_rec_id" => $fin_rec_id,
			"fst_item_discount" => $this->input->post("fst_item_discount"),
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
		$this->ajxResp["data"]["insert_id"] = $fin_rec_id;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("msitemdiscounts");
		
		$selectFields = "fin_rec_id,fst_item_discount,'action' as action";
		$this->datatables->setSelectFields($selectFields);
		
		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_rec_id","fst_item_discount"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_rec_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_rec_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($fin_rec_id){
		$this->load->model("msitemdiscounts_model");
		$data = $this->msitemdiscounts_model->getDataById($fin_rec_id);

		$this->json_output($data);
	}

	public function delete($id){
		$this->load->model("msitemdiscounts_model");
		$this->db->trans_start();
        $this->msitemdiscounts_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
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

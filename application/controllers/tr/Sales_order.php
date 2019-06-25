<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('sales_order_model');
	}

	public function index(){
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Sales Order";
		$this->list['list_name'] = "Sales Order List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/sales_order/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/sales_order/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/sales_order/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/sales_order/edit/';
		$this->list['arrSearch'] = [
			'fin_salesorder_id' => 'Sales Order ID',
			'fst_salesorder_no' => 'Sales Order No'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Sales Order', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Sales Order ID', 'width' => '20%', 'data' => 'fin_salesorder_id'],
			['title' => 'Sales Order No', 'width' => '20%', 'data' => 'fst_salesorder_no'],
			['title' => 'Sales Order Date', 'width' => '20%', 'data' => 'fdt_salesorder_date'],
            ['title' => 'Memo', 'width' => '20%', 'data' => 'fst_memo'],
			['title' => 'Action', 'width' => '15%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
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

	private function openForm($mode = "ADD", $fin_salesorder_id = 0){
		$this->load->library("menus");
		$this->load->model('sales_order_model');

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Sales Order" : "Update Sales Order";
		$data["fin_salesorder_id"] = $fin_salesorder_id;
		$data["fst_salesorder_no"] = $this->sales_order_model->GenerateSONo();
		$data["percent_ppn"] = (int) getDbConfig("percent_ppn");

		$page_content = $this->parser->parse('pages/tr/sales_order/form', $data, true);
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

	public function Edit($fin_salesorder_id){
		$this->openForm("EDIT", $fin_salesorder_id);
	}

	public function ajx_add_save(){
		$this->load->model('sales_order_model');
		$this->form_validation->set_rules($this->sales_order_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$fst_salesorder_no = $this->sales_order_model->GenerateSONo();

		$data = [
            "fst_salesorder_no" => $fst_salesorder_no,
			"fdt_salesorder_date" => dBDateFormat($this->input->post("fdt_salesorder_date")),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_sales_spv_id" => $this->input->post("fin_sales_spv_id"),
			"fin_sales_mgr_id" => $this->input->post("fin_sales_mgr_id"),
			"fst_memo" =>$this->input->post("fst_memo"),
			"fbl_is_hold" => ($this->input->post("fbl_is_hold") == false) ? 0 : 1,
			"fbl_is_vat_include" => ($this->input->post("fbl_is_vat_include") == false) ? 0 : 1,
			"fdc_vat_percent" => $this->input->post("fdc_vat_percent"),
			"fdc_vat_amount" => $this->input->post("fdc_vat_amount"),
			"fdc_disc_percent" => $this->input->post("fdc_disc_percent"),
			"fdc_disc_amount" => $this->input->post("fdc_disc_amount"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->sales_order_model->insert($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		// Save SO Details
		$this->load->model("sales_order_details_model");
		
		$this->form_validation->set_rules($this->sales_order_details_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"fin_salesorder_id"=> $insertId,
				"ItemId"=> $item->ItemId,
				"fdc_qty"=> $item->fdc_qty,
				"fdc_price"=> $item->fdc_price
			];

			// Validate SO Details
			$this->form_validation->set_data($data);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = "Error Validation Forms";
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;	
			}
			
			$this->sales_order_details_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0){			
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}

		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save(){
		$this->load->model('Sales_order_model');
		$fin_salesorder_id = $this->input->post("fin_salesorder_id");
		$data = $this->Sales_order_model->getDataById($fin_salesorder_id);
		$sales_order = $data["sales_order"];
		if (!$sales_order) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_salesorder_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->sales_order_model->getRules("EDIT", $fin_salesorder_id));
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
			"fin_salesorder_id" => $fin_salesorder_id,
            "fst_salesorder_no" => $this->input->post("fst_salesorder_no"),
			"fdt_salesorder_date" => dBDateFormat($this->input->post("fdt_salesorder_date")),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_sales_spv_id" => $this->input->post("fin_sales_spv_id"),
			"fin_sales_mgr_id" => $this->input->post("fin_sales_mgr_id"),
			"fst_memo" => $this->input->post("fst_memo"),
			"fbl_is_hold" => $this->input->post("fbl_is_hold"),
			"fbl_is_vat_include" => $this->input->post("fbl_is_vat_include"),
			"fdc_vat_percent" => $this->input->post("fdc_vat_percent"),
			"fdc_vat_amount" => $this->input->post("fdc_vat_amount"),
			"fdc_disc_percent" => $this->input->post("fdc_disc_percent"),
			"fdc_disc_amount" => $this->input->post("fdc_disc_amount"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->sales_order_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		// Save Details
		/*$this->load->model("sales_order_details_model");

		$this->form_validation->set_rules($this->sales_order_details_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$this->sales_order_details_model->deleteByDetail($fin_salesorder_id);

		//$this->load->model("sales_order_details_model");		
		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"fin_salesorder_id"=> $fin_salesorder_id,
				"ItemId"=> $item->$ItemId,
				"fdc_qty"=> $item->fdc_qty,
				"fdc_price"=> $item-> $fdc_price
			];

			// Validate Data Items
			$this->form_validation->set_data($data);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = "Error Validation Forms";
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;	
			}
			
			$this->sales_order_details_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0){			
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}*/

		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $fin_salesorder_id;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("trsalesorder");

		$selectFields = "fin_salesorder_id,fst_salesorder_no,fdt_salesorder_date,fst_memo,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDate = strtotime($data["fdt_salesorder_date"]);						
			$data["fdt_salesorder_date"] = date("d-M-Y",$insertDate);
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_salesorder_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_salesorder_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fin_salesorder_id){
		$this->load->model("Sales_order_model");
		$data = $this->sales_order_model->getDataById($fin_salesorder_id);
		
		$this->json_output($data);
	}

	public function delete($id){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->load->model("sales_order_model");
		$this->sales_order_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}

	public function get_msrelations(){
		$term = $this->input->get("term");
		$ssql = "select RelationId, RelationName from msrelations where RelationName like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_mswarehouse(){
		$term = $this->input->get("term");
		$ssql = "select fin_warehouse_id, fst_warehouse_name from mswarehouse where fst_warehouse_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_users(){
		$term = $this->input->get("term");
		$ssql = "select fin_user_id, fst_username from users where fst_username like ? order by fst_username";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_data_item(){
		$term = $this->input->get("term");
		$ssql = "select ItemId, CONCAT(ItemCode,' - ' ,ItemName) as ItemName from msitems where CONCAT(ItemCode,' - ' ,ItemName) like ? order by ItemName";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->json_output($rs);
	}

	public function get_data_disc(){
		$term = $this->input->get("term");
		$ssql = "select ItemDiscount from msitemdiscounts where ItemDiscount like ? order by ItemDiscount";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();

		$this->json_output($rs);
	}

	public function getSoDetail($fin_salesorder_id){
        $this->load->model("sales_order_details_model");
        $result = $this->sales_order_details_model->getSoDetail($fin_salesorder_id);
        $this->ajxResp["data"] = $result;
        $this->json_output();
	}

	/*public function report_sales_order(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("sales_order_model");
		$listSalesOrder = $this->sales_order_model->getSales_order();
        $data = [
			"datas" => $listSalesOrder
		];
			
        $this->pdf->load_view('report/sales_order_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }*/
}
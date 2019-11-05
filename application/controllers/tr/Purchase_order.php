<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Purchase_order extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trpo_model');
		$this->load->model('trpodetails_model');		
		$this->load->model("msrelations_model");
		$this->load->model("mswarehouse_model");
		$this->load->model("msitemdiscounts_model");
		
		

	}
	public function index(){
		$this->lizt();
	}
	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Purchase Order";
		$this->list['list_name'] = "Purchase Order List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/purchase_order/add';
		$this->list['pKey'] = "fin_po_id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase_order/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/purchase_order/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/purchase_order/edit/';
		$this->list['arrSearch'] = [
			'fin_po_id' => 'Purchase Order ID',
			'fst_po_no' => 'Purchase Order No'
		];
		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Purchase Order', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Purchase Order ID.', 'width' => '20%', 'data' => 'fin_po_id'],
			['title' => 'Purchase Order No.', 'width' => '20%', 'data' => 'fst_po_no'],
			['title' => 'Purchase Order Date', 'width' => '20%', 'data' => 'fdt_po_datetime'],
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
	private function openForm($mode = "ADD", $fin_po_id = 0){
		$this->load->library("menus");		
		

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlJurnal =$this->parser->parse('template/mdlJurnal.php', [], true);
		$mdlPrint =$this->parser->parse('template/mdlPrint.php', [], true);
		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Purchase Order" : "Update Purchase Order";
		$data["mdlJurnal"] = $mdlJurnal;
		$data["mdlPrint"] = $mdlPrint;
		if($mode == 'ADD'){
			$data["fin_po_id"] = 0;
			$data["fst_po_no"] = $this->trpo_model->GeneratePONo();			
		}else if($mode=="EDIT"){
			$data["fin_po_id"] = $fin_po_id;
			$data["fst_po_no"] = "";			
		}else if($mode == "VIEW"){
			$data["fin_po_id"] = $fin_po_id;
			$data["fst_po_no"] = "";			
		}
		
		$page_content = $this->parser->parse('pages/tr/purchase_order/form', $data, true);
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
	public function edit($fin_po_id){
		$this->openForm("EDIT", $fin_po_id);
	}
	public function view($finPOId){
		$this->openForm("VIEW", $finPOId);
	}

	public function ajx_add_save(){	
		$this->form_validation->set_rules($this->trpo_model->getRules("ADD", 0));

		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Invalid data input !";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}
		
		$fdt_po_datetime = dBDateTimeFormat($this->input->post("fdt_po_datetime"));
		$fst_po_no = $this->trpo_model->GeneratePONo();
		$fblDPIncPPN = $this->input->post("fbl_dp_inc_ppn");
		$fblDPIncPPN =  ($fblDPIncPPN == null) ? 0 : 1;

		$dataH = [
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fbl_is_import"=>$this->input->post("fbl_is_import"),
            "fst_po_no" => $fst_po_no,
			"fdt_po_datetime" => $fdt_po_datetime,
			"fst_curr_code"=>$this->input->post("fst_curr_code"),
			"fdc_exchange_rate_idr"=>$this->input->post("fdc_exchange_rate_idr"),
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
			"fin_term"=>$this->input->post("fin_term"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),	
			"fst_do_no" => $this->input->post("fst_do_no"),	
			"fst_contract_no" => $this->input->post("fst_contract_no"),	
			"fst_delivery_address" =>$this->input->post("fst_delivery_address"),			
			"fst_memo" =>$this->input->post("fst_memo"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_ppn_percent"=>$this->input->post("fdc_ppn_percent"),
			"fdc_ppn_amount"=>0,
			"fdc_downpayment"=>$this->input->post("fdc_downpayment"),
			"fdc_downpayment_paid"=>0,
			"fbl_dp_inc_ppn" => $fblDPIncPPN,
			"fbl_is_closed"=>0,
			"fst_active" => 'S'
		];

		if ($dataH["fbl_is_import"]){
			$dataH["fdc_ppn_percent"] = 0;
			$dataH["fdc_ppn_amount"] = 0;
			$dataH["fbl_dp_inc_ppn"] = 0;			
		}

		$this->form_validation->set_rules($this->trpodetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$details = $this->input->post("detail");
		$details = json_decode($details);

		$total = 0;
		$discAmount= 0;
		$ppnAmount = 0;
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			$tmpTtl = $item->fdb_qty * $item->fdc_price;
			$details[$i]->fdc_disc_amount = calculateDisc($item->fst_disc_item,$tmpTtl);			
			$total += $tmpTtl;
			$tmpDisc = calculateDisc($item->fst_disc_item,$tmpTtl);
			$discAmount += $tmpDisc;
			// Validate PO Details
			$this->form_validation->set_data((array)$details[$i]);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Error Validation Forms");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;
				
				$this->json_output();
				return;	
			}
		}

		$dataH["fdc_subttl"] = $total - $discAmount;
		$dataH["fdc_disc_amount"] = $discAmount;
		$dataH["fdc_ppn_amount"] = $dataH["fdc_subttl"] * ($dataH["fdc_ppn_percent"] / 100);

		if($dataH["fdc_subttl"] <= 0){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Total transaction is zero !";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}

		
		$this->db->trans_start();
		//Insert Data Header
		$insertId = $this->trpo_model->insert($dataH);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}
		
		//Insert Data Detail
		foreach ($details as $item) {
			$dataDetail = (array) $item;
			$dataDetail =[
  				"fin_po_id"=>$insertId,
  				"fin_item_id"=>$item->fin_item_id,
  				"fst_custom_item_name"=>$item->fst_custom_item_name,
				"fst_unit"=>$item->fst_unit,
				"fdb_qty"=>$item->fdb_qty,
				"fdc_price"=>$item->fdc_price,
				"fst_disc_item"=>$item->fst_disc_item,
				"fdc_disc_amount"=>$item->fdc_disc_amount,
				"fst_notes"=>$item->fst_notes,				
				"fst_active"=> 'A'
			];
			$this->trpodetails_model->insert($dataDetail);			
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

		//Create Approval record
		$this->load->model("trverification_model");
		$message = "Purchase Order " .$dataH["fst_po_no"] ." Need Approval";
		$this->trverification_model->createAuthorize("PO","default",$insertId,$message);

		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}


	public function ajx_edit_save(){
		$this->load->model("trverification_model");

		// cek if this PO have  approval record
		if($this->trverification_model->haveAprrovalRecord("PO","default",$this->input->post("fin_po_id"))){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Can't edit !, This transaction is approved or rejected !";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trpo_model->getRules("ADD", 0));

		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms 1";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}
		
		$dataH = $this->input->post();		

		unset($dataH["fst_po_no"]);
		$fdt_po_datetime = dBDateTimeFormat($this->input->post("fdt_po_datetime"));
		$fblDPIncPPN = $this->input->post("fbl_dp_inc_ppn");
		$fblDPIncPPN =  ($fblDPIncPPN == null) ? 0 : 1;

		$dataH["fdt_po_datetime"] = $fdt_po_datetime;
		$dataH["fbl_dp_inc_ppn"] = $fblDPIncPPN;		
		$dataH["fdc_subttl"] = 0;		
		$dataH["fdc_disc_amount"] = 0;
		$dataH["fdc_ppn_amount"] = 0;

		if ($dataH["fbl_is_import"]){
			$dataH["fdc_ppn_percent"] = 0;
			$dataH["fdc_ppn_amount"] = 0;
			$dataH["fbl_dp_inc_ppn"] = 0;			
		}

		$this->form_validation->set_rules($this->trpodetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');


		$details = $this->input->post("detail");
		$details = json_decode($details);
		$total = 0;
		$discAmount= 0;
		$ppnAmount = 0;
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			$tmpTtl = $item->fdb_qty * $item->fdc_price;
			$details[$i]->fdc_disc_amount = calculateDisc($item->fst_disc_item,$tmpTtl);			
			$total += $tmpTtl;
			$tmpDisc = calculateDisc($item->fst_disc_item,$tmpTtl);
			$discAmount += $tmpDisc;
			// Validate PO Details
			$this->form_validation->set_data((array)$details[$i]);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Error Validation Forms");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;
				
				$this->json_output();
				return;	
			}
		}

		$dataH["fdc_subttl"] = $total - $discAmount;
		$dataH["fdc_disc_amount"] = $discAmount;
		$dataH["fdc_ppn_amount"] = $dataH["fdc_subttl"] * ($dataH["fdc_ppn_percent"] / 100);

		$this->db->trans_start();
		//Update Data Header
		$this->trpo_model->update($dataH);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}
		
		//Delete & Insert Data Detail
		$this->trpodetails_model->deleteByPOId($dataH["fin_po_id"]);
		foreach ($details as $item) {
			$dataDetail = (array) $item;
			$dataDetail =[
				"fin_po_detail_id"=>$item->fin_po_detail_id,
  				"fin_po_id"=>$dataH["fin_po_id"],
  				"fin_item_id"=>$item->fin_item_id,
  				"fst_custom_item_name"=>$item->fst_custom_item_name,
				"fst_unit"=>$item->fst_unit,
				"fdb_qty"=>$item->fdb_qty,
				"fdb_qty_plb"=>$this->trpodetails_model->getQtyPLB($item->fin_po_detail_id),
				"fdc_price"=>$item->fdc_price,
				"fst_disc_item"=>$item->fst_disc_item,
				"fdc_disc_amount"=>$item->fdc_disc_amount,
				"fst_notes"=>$item->fst_notes,				
				"fst_active"=> 'A'
			];
			$this->trpodetails_model->insert($dataDetail);			
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
		$this->ajxResp["data"]["insert_id"] = $dataH["fin_po_id"];
		$this->json_output();
	}

	
	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("trpo");
		$selectFields = "fin_po_id,fst_po_no,fdt_po_datetime,fst_memo,'action' as action";
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
			$insertDate = strtotime($data["fdt_po_datetime"]);						
			$data["fdt_po_datetime"] = date("d-M-Y",$insertDate);
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_po_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_po_id"] . "'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($fin_po_id){
		$data = $this->trpo_model->getDataById($fin_po_id);	
		$this->json_output($data);
	}
	public function delete($finPOId){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->db->trans_start();

		$result = $this->trpo_model->delete($finPOId);
		$this->db->trans_complete();
		if ($result["status"] ==  true){
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("PO Telah dihapus");		
		}else{
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = $result["message"];
		}
		$this->json_output();
	}

	public function get_msrelations(){
		$term = $this->input->get("term");
		$ssql = "select fin_relation_id, fst_relation_name,fin_sales_id,fin_warehouse_id,fin_terms_payment from msrelations where fin_branch_id = ? and fst_relation_name like ? and FIND_IN_SET(1,fst_relation_type)";
		$qr = $this->db->query($ssql,[$this->aauth->get_active_branch_id(),'%'.$term.'%']);
		//lastQuery();
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
	public function getValueFormInit($fin_salesorder_id){
		$salesDeptId = getDbConfig("sales_department_id");
		$activeBranchId = $this->aauth->get_active_branch_id();
		$ssql = "select fin_user_id, fst_username from users where  fin_branch_id =? and fin_department_id = $salesDeptId order by fst_username";
		$qr = $this->db->query($ssql,[$activeBranchId]);
		$rsSales = $qr->result();
		
		$ssql = "select fin_warehouse_id, fst_warehouse_name from mswarehouse where fin_branch_id =?";
		$qr = $this->db->query($ssql,[$activeBranchId]);
		$rsWarehouse = $qr->result();
	
		$rscurrencies = [];
		$rscurrencies[] = getDefaultCurrency();
		$ssql = "select fst_item_discount from msitemdiscounts where fst_active ='A'";
		$qr = $this->db->query($ssql,[]);
		$rsdiscount = $qr->result();
		$data = [
			"sales" => $rsSales,
			"warehouse" => $rsWarehouse,
			"currencies" => $rscurrencies,
			"discounts" => $rsdiscount,
			"min_date_time"=>getDbConfig("lock_transaction_date")
		];
		if ($fin_salesorder_id != 0){
			$this->load->model("trsalesorder_model");
			$tmp = $this->trsalesorder_model->getDataById($fin_salesorder_id);		
			$data["sales_order"] = $tmp["sales_order"];
			$data["so_details"] = $tmp["so_details"];
		}
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $data;
		$this->json_output();
	
	}
	public function get_sales(){
		$term = $this->input->get("term");
		$salesDeptId = getDbConfig("sales_department_id");
		$ssql = "select fin_user_id, fst_username from users where fst_username like ? and fin_department_id = $salesDeptId order by fst_username";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}
	
	public function initVarForm($poId){
        $this->load->model("mswarehouse_model");
        $this->load->model("users_model");
        $this->load->library("select2");
        
        $branchId = $this->aauth->get_active_branch_id();
        
        //Get Data Supplier
		$arrSupplier = $this->select2->get_supplier($branchId);
		
		//Get Warehouse 
		$arrWarehouse = $this->select2->get_warehouse($branchId);
		
		//get List Disc
		$arrDisc = $this->select2->get_discountList();
		
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
			"arrSupplier"=>$arrSupplier,
			"arrWarehouse"=>$arrWarehouse,
			"arrDisc"=>$arrDisc
            //"arrSJ"=>$arrSJ,
        ];
        $this->json_output();
    }
	
	public function get_item($supplierId){
		$this->load->library("select2");
		$arrItem = $this->select2->get_itemBySupplier($supplierId);
		$this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
			"arrItem"=>$arrItem,
        ];
        $this->json_output();
	}
	public function get_item_unit($itemId){
		$this->load->library("select2");
		$arrUnit = $this->select2->get_buyItemUnit($itemId);
		$this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
			"arrUnit"=>$arrUnit,
        ];
        $this->json_output();
	}
	public function print_po(){
		$this->load->library("phpspreadsheet");
		//$spreadsheet = $this->phpspreadsheet->load(FCPATH . "assets/templates/template_sales_log.xlsx");
		//$spreadsheet = $this->phpspreadsheet->test();
		//die();
		$spreadsheet = $this->phpspreadsheet->load();
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		$sheet->getPageMargins()->setTop(1);
		$sheet->getPageMargins()->setRight(0.5);
		$sheet->getPageMargins()->setLeft(0.5);
		$sheet->getPageMargins()->setBottom(1);
		$sheet->setCellValue('A1', 'Hello World !'); 
		$filename = 'coba.xls';
		
		$this->phpspreadsheet->save($filename,$spreadsheet);
		
		/*
		var_dump($this->input->post("layoutColumn"));
		$arrLayoutCol = json_decode($this->input->post("layoutColumn"));
		var_dump($arrLayoutCol);
		//echo "PRINT......";
		*/
	}
}
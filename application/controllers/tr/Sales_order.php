<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends MY_Controller{
	public $menuName="sales_order"; 
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trsalesorder_model');
		$this->load->model("trsalesorderdetails_model");
		$this->load->model('mscurrencies_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('users_model');
		$this->load->model('msitemdiscounts_model');
		$this->load->model("trinventory_model");
		$this->load->model("msitems_model");
		$this->load->model("msrelations_model");

	}

	public function index(){
		parent::index();
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		parent::index();
		$this->list['page_name'] = "Sales Order";
		$this->list['list_name'] = "Sales Order List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/sales_order/add';
		$this->list['pKey'] = "fin_salesorder_id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/sales_order/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/sales_order/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/sales_order/edit/';

		$this->list['arrSearch'] = [
			'fst_salesorder_no' => 'Sales Order No',
			'fst_customer' => 'Customer'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Sales Order', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Sales Order ID.', 'width' => '10px', 'visible' => 'false', 'data' => 'fin_salesorder_id'],
			['title' => 'S/O No.', 'width' => '120px', 'data' => 'fst_salesorder_no'],
			['title' => 'S/O Date', 'width' => '80px', 'data' => 'fdt_salesorder_datetime'],
			['title' => 'Customer', 'width' => '130px', 'data' => 'fst_customer'],
			
			['title' => 'Total', 'width' => '100px','className'=>'text-right',
				'render'=>"function(data,type,row){

					var total = parseFloat(row.fdc_subttl) - parseFloat(row.fdc_disc_amount);
					if (row.fbl_is_vat_include == 0){
						total +=  parseFloat(row.fdc_vat_amount);						
					}
					
					return row.fst_curr_code + ':' + App.money_format(total);
				}"
			],
			['title' => 'DP', 'width' => '70px', 'data' => 'fdc_downpayment','className' => 'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}"
			],
			['title' => 'DP paid', 'width' => '70px', 'data' => 'fdc_downpayment_paid','className' => 'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}"
			],
			['title' => 'Status', 'width' => '70px', 'data' => 'fst_active','className'=>'text-center',
				'render'=>"function(data,type,row){
					if(data == 'A'){
						return 'Active';
					}else if (data == 'S'){
						return 'Suspend';
					}else if (data == 'D'){
						return 'Deleted';
					}else if (data == 'R'){
						return 'Rejected';
					}else if (data == 'P'){
						return 'PENDING PROMO';
					}

				}"
			],
			['title' => 'Closed', 'width' => '20px', 'data' => 'fbl_is_closed','className'=>'text-center',
				'render'=>"function(data,type,row){
					if(data == 1){
						return '<input class=\"isClosed\" type=\"checkbox\" value=\"1\" checked>';
					}else{
						return '<input class=\"isClosed\" type=\"checkbox\" value=\"0\" >';
					}					
				}"
			],

			['title' => 'Action', 'width' => '100px', 'sortable' => false, 'className' => 'dt-body-center text-center',
				'render'=>"function(data,type,row){
					action = \"<div style='font-size:16px'>\";
					action += \"<a class='btn-edit' href='#' data-id='\" + row.fin_salesorder_id + \"'><i class='fa fa-pencil'></i></a>\";
					action += \"</div>\";
					return action;
				}",
			]
		];

		$mdlPopupNotes = $this->parser->parse('template/mdlPopupNotes', [], true);
		$this->list['jsfile'] = $this->parser->parse('pages/tr/sales_order/listjs', ["mdlPopupNotes"=>$mdlPopupNotes], true);

		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$this->list["mdlEditForm"] = $edit_modal;
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
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlJurnal = $this->parser->parse('template/mdlJurnal.php', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);
		$mdlConfirmAuthorize = $this->parser->parse('template/mdlConfirmAuthorize.php', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		
		

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Sales Order" : "Update Sales Order";
		$data["mdlJurnal"] = $mdlJurnal;
		$data["mdlPrint"] = $mdlPrint;
		$data["mdlConfirmAuthorize"] = $mdlConfirmAuthorize;
		$data["mdlEditForm"] = $edit_modal;
		
		if($mode == 'ADD'){
			$data["fin_salesorder_id"] = 0;
			$data["fst_salesorder_no"] = $this->trsalesorder_model->GenerateSONo();
			$data["percent_ppn"] = (int) getDbConfig("percent_ppn");
			$data["default_currency"] = getDefaultCurrency();	
		}else{
			$data["fin_salesorder_id"] = $fin_salesorder_id;
			$data["fst_salesorder_no"] = "";
			$data["percent_ppn"] = (int) getDbConfig("percent_ppn");
			$data["default_currency"] = getDefaultCurrency();	
		}
		
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
		parent::add();
		$this->openForm("ADD", 0);
	}

	public function edit($fin_salesorder_id){
		parent::edit($fin_salesorder_id);
		$this->openForm("EDIT", $fin_salesorder_id);
	}

	public function view($finSalesOrderId){
		$this->openForm("VIEW", $finSalesOrderId);
	}
	
	public function ajx_add_save(){		
		parent::ajx_add_save();
		/*$this->load->model('trsalesorder_model');
		$this->load->model('trvoucher_model');
		$this->load->model('trverification_model');
		*/
		$fst_salesorder_no = $this->trsalesorder_model->GenerateSONo();
		$fdt_salesorder_datetime = dBDateTimeFormat($this->input->post("fdt_salesorder_datetime"));
		$fdc_downpayment = parseNumber($this->input->post("fdc_downpayment"));
		$isHold = ($this->input->post("fbl_is_hold") == false) ? 0 : 1;
		$exchangeRateIDR = parseNumber($this->input->post("fdc_exchange_rate_idr"));


		try{
			//CEK tgl lock dari transaksi yg di kirim
			$resp = dateIsLock($fdt_salesorder_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],"3003",$resp["status"],[]);
			}

			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$details = $preparedData["details"];
			
			$this->validateData($dataH,$details);

			//** Cek if this transaction need authorization */		
			$needAuthorizeList = $this->trsalesorder_model->getAuthorizationList($dataH,$details);
			$dataH["fst_active"] = ($needAuthorizeList["need_authorize"] == true) ? "S" :"A";	


			$this->db->trans_start();
			$insertId = $this->trsalesorder_model->insert($dataH);
			//Insert Data Detail
			foreach ($details as $item) {
				//$dataDetail = (array) $item;
				$dataDetail =[
					"fin_salesorder_id"=>$insertId,
					"fin_item_id"=>$item->fin_item_id,
					"fst_custom_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"fdc_price"=>$item->fdc_price,
					"fst_disc_item"=>$item->fst_disc_item,
					"fdc_disc_amount_per_item"=>$item->fdc_disc_amount_per_item,
					"fst_memo_item"=>$item->fst_memo_item,
					"fin_promo_id"=>$item->fin_promo_id,
					"fst_active"=> 'A'
				];
				$this->trsalesorderdetails_model->insert($dataDetail);
				throwIfDBError();			
			}
			
			//Cek Promo
			$arrPromo = $this->trsalesorder_model->getDataPromo($insertId);

			if (sizeof($arrPromo) == 0){
				//Create authorize record
				$this->trsalesorder_model->generateApprovalData($needAuthorizeList,$insertId,$dataH["fst_salesorder_no"]);

				$this->trsalesorder_model->posting($insertId);
			}else{
				//Change
				$ssql ="UPDATE trsalesorder set fst_active = 'P' where fin_salesorder_id = ?";
				$this->db->query($ssql,[$insertId]);
			}

			

			$this->db->trans_complete();

			


			if (sizeof($arrPromo) > 0){
				$this->json_output([
					"status"=>"CEK_PROMO",
					"message"=>"Data Saved !",
					"data"=>["insert_id"=>$insertId]
				]);
			}else{
				$this->json_output([
					"status"=>"SUCCESS",
					"message"=>"Data Saved !",
					"data"=>["insert_id"=>$insertId]
				]);
			}
			
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->json_output([
				"status"=>$e->getStatus(),
				"message"=> $e->getMessage(),
				"data"=> $e->getData()
			]);
			return;
		}
		

	}

	public function ajx_edit_save(){
		parent::ajx_edit_save();
		$this->load->model('trsalesorder_model');
		$this->load->model('trvoucher_model');
		$this->load->model('trverification_model');
				
		$fin_salesorder_id = $this->input->post("fin_salesorder_id");	
		$dataHOld = $this->trsalesorder_model->getSimpleDataById($fin_salesorder_id);
		$fdt_salesorder_datetime = dBDateTimeFormat($this->input->post("fdt_salesorder_datetime"));

		try{
			
			//CEK Lock Tanggal
			$resp = dateIsLock($dataHOld->fdt_salesorder_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],[]);		
			}

			$resp = dateIsLock($fdt_salesorder_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],[]);		
			}

			//Is Editable ?
			$resp = $this->trsalesorder_model->isEditable($fin_salesorder_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],[]);		
			}	

			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_salesorder_id"] = $dataHOld->fin_salesorder_id;
			$dataH["fst_salesorder_no"] = $dataHOld->fst_salesorder_no;

			$details = $preparedData["details"];

			$this->validateData($dataH,$details);

			//** Cek if this transaction need authorization */		
			$needAuthorizeList = $this->trsalesorder_model->getAuthorizationList($dataH,$details);
			$dataH["fst_active"] = ($needAuthorizeList["need_authorize"] == true) ? "S" :"A";	


			$this->db->trans_start();
			
			//Unposting
			$this->trsalesorder_model->unposting($fin_salesorder_id);

			//Delete data detail & voucher & verification
			$ssql = "DELETE from trsalesorderdetails where fin_salesorder_id = ?";
			$this->db->query($ssql,[$fin_salesorder_id]);
			throwIfDBError();

			$this->trvoucher_model->deleteVoucher("SALESORDER",$fin_salesorder_id);
			$this->trverification_model->deleteApproval("SO",$fin_salesorder_id);	

			
			$this->trsalesorder_model->update($dataH);
			$insertId = $fin_salesorder_id;

			//Insert Data Detail
			foreach ($details as $item) {
				//$dataDetail = (array) $item;
				$dataDetail =[
					"fin_salesorder_id"=>$insertId,
					"fin_item_id"=>$item->fin_item_id,
					"fst_custom_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"fdc_price"=>$item->fdc_price,
					"fst_disc_item"=>$item->fst_disc_item,
					"fdc_disc_amount_per_item"=>$item->fdc_disc_amount_per_item,
					"fst_memo_item"=>$item->fst_memo_item,
					"fin_promo_id"=>$item->fin_promo_id,
					"fst_active"=> 'A'
				];
				$this->trsalesorderdetails_model->insert($dataDetail);			
				throwIfDBError();
			}
			
			//Cek Promo
			$arrPromo = $this->trsalesorder_model->getDataPromo($insertId);

			if (sizeof($arrPromo) == 0){
				//Create authorize record
				$this->trsalesorder_model->generateApprovalData($needAuthorizeList,$insertId,$dataH["fst_salesorder_no"]);

				$this->trsalesorder_model->posting($insertId);
			}else{
				//Change
				$ssql ="UPDATE trsalesorder set fst_active = 'P' where fin_salesorder_id = ?";
				$this->db->query($ssql,[$insertId]);
				throwIfDBError();
			}			

			$this->db->trans_complete();		
			if (sizeof($arrPromo) > 0){
				$this->json_output([
					"status"=>"CEK_PROMO",
					"message"=>"Add Promo !",
					"data"=>["insert_id"=>$insertId]
				]);
			}else{
				$this->json_output([
					"status"=>"SUCCESS",
					"message"=>"Data Saved !",
					"data"=>["insert_id"=>$insertId]
				]);
			}			

		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->json_output([
				"status"=>$e->getStatus(),
				"message" => $e->getMessage(),
				"data" => $e->getData()
			]);
			return;
		}
	}


	public function prepareData(){

		$fin_salesorder_id = $this->input->post("fin_salesorder_id");
		$fdt_salesorder_datetime = dBDateTimeFormat($this->input->post("fdt_salesorder_datetime"));
		$fdc_downpayment = parseNumber($this->input->post("fdc_downpayment"));
		$isHold = ($this->input->post("fbl_is_hold") == false) ? 0 : 1;
		$exchangeRateIDR = parseNumber($this->input->post("fdc_exchange_rate_idr"));

		$vat = 0;
		$incVat = 0;
		if (getDbConfig("sales_price_inc_ppn") == "1"){
			$vat = getDbConfig("sales_ppn_percent");
			$incVat = 1;
		}else{
			$vat =  $this->input->post("fdc_vat_percent");
		}

		//PREPARE DATA
		$dataH = [
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
            "fst_salesorder_no" => $this->input->post("fst_salesorder_no"),
			"fdt_salesorder_datetime" => $fdt_salesorder_datetime,
			"fst_curr_code"=>$this->input->post("fst_curr_code"),
			"fdc_exchange_rate_idr"=> $exchangeRateIDR,
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_terms_payment"=>$this->input->post("fin_terms_payment"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),			
			"fbl_is_hold" => $isHold,
			"fbl_is_vat_include" => $incVat,
			"fin_shipping_address_id" =>$this->input->post("fin_shipping_address_id"),			
			"fst_memo" =>$this->input->post("fst_memo"),
			"fdc_subttl"=>0,// calculate from detail
			"fdc_disc_amount" => 0, //get Total Disc recalculate
			"fdc_dpp_amount" => 0, // calculate from detail
			"fdc_vat_percent" => $vat,
			"fdc_vat_amount" => 0, //total vat recalculate
			"fdc_total" => 0, //total recalculate
			"fdc_downpayment" => $fdc_downpayment,
			"fbl_dp_inc_ppn" => ($this->input->post("fbl_dp_inc_ppn") == null) ? 0 : 1,
			"fdc_downpayment_paid"=>0,
			"fbl_is_closed"=>0,
			"fst_active" => 'S' //JIKA ITEM YANG DI ORDER TIDAK ADA MAKA STATUS BERUBAH JADI SUSPEND
		];


		$details = $this->input->post("detail");
		$details = json_decode($details);
		
		$subTotal = 0;
		$ttlDiscAmount = 0;

		for($i = 0; $i < sizeof($details) ; $i++){
			//$dataD = $details[$i];
			

			//remove if promo item (Clear All Item Promo)
			if ($details[$i]->fin_promo_id != 0 && $details[$i]->fin_promo_id != null){
				unset($details[$i]);
				continue;
			}

			//Get Max Disc
			$item = $this->msitems_model->getSimpleDataById($details[$i]->fin_item_id);
			$details[$i]->fst_max_item_discount = $item->fst_max_item_discount;
			
			//get Price from system
			$price = $this->msitems_model->getSellingPrice($item->fin_item_id,$details[$i]->fst_unit,$dataH["fin_relation_id"]);

			if ($price == 0 ){ // Bila dimaster harga 0, berarti user boleh menentukan harga sendiri
				$price = $details[$i]->fdc_price;
			}
			$details[$i]->fdc_price = $price;						

			
			$subTotal += $price * $details[$i]->fdb_qty;
			$ttlDiscAmount += $details[$i]->fdc_disc_amount_per_item * $details[$i]->fdb_qty;			
		}
		
		$hSubTotal = $subTotal - $ttlDiscAmount;
		$dataH["fdc_subttl"] = $hSubTotal; //Setelah Potong disc 
		$dataH["fdc_disc_amount"]= $ttlDiscAmount;
		$dataH["fdc_dpp_amount"]= $incVat == 1 ? ($hSubTotal / (1 + ($vat / 100) ) ) : $hSubTotal;
		$dataH["fdc_vat_amount"]= $dataH["fdc_dpp_amount"] * ($vat / 100) ;
		$dataH["fdc_total"]= $dataH["fdc_dpp_amount"] + $dataH["fdc_vat_amount"];


		return [
			"dataH"=>$dataH,
			"details"=>$details
		];
	}

	public function validateData($dataH,$details){

		//VALIDATION
		$this->form_validation->set_rules($this->trsalesorder_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}



		foreach($details as $dataD){
			$this->form_validation->set_rules($this->trsalesorderdetails_model->getRules("ADD",0));
			$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
			$this->form_validation->set_data((array)$dataD);
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





		//Cek Price item kalau di system 0 baru boleh di tetapkan oleh user
		
		
		//Cek Maximal Disc


		/*
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			//remove if promo item
			if ($details[$i]->fin_promo_id != null){
				unset($details[$i]);
				continue;
			}

			$objItem = $arrItem[$item->fin_item_id];
			$details[$i]->fst_max_item_discount = $objItem->fst_max_item_discount;
			//get Price from system
			$price = $this->msitems_model->getSellingPrice($item->fin_item_id,$item->fst_unit,$dataH["fin_relation_id"]);
			if ($price == 0 ){ // Bila dimaster harga 0, berarti user boleh menentukan harga sendiri
				$price = $item->fdc_price;
			}
			$details[$i]->fdc_price = $price;						

			$subTtl += $price * $details[$i]->fdb_qty;
			$ttlDiscAmount += $details[$i]->fdc_disc_amount_per_item * $details[$i]->fdb_qty;			
		}
		*/


	}

	public function ajx_save_promo(){	
		$this->load->model("trvoucher_model");	

		$finSalesOrderId = $this->input->post("fin_salesorder_id");
		$dataH = (array) $this->trsalesorder_model->getsimpleDataById($finSalesOrderId);
		$ssql = "SELECT * FROM trsalesorderdetails where fin_salesorder_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finSalesOrderId]);
		$details = $qr->result();

		try{

			$resp = $this->trsalesorder_model->isEditable($finSalesOrderId);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"], 3003,$resp["status"],[]);
			}	

			$this->db->trans_start();
			$this->trsalesorder_model->unposting($finSalesOrderId);
			//delete item promo and voucher
			$this->db->query("DELETE FROM trsalesorderdetails where fin_salesorder_id = ? and fin_promo_id != 0",[$finSalesOrderId]);
			throwIfDBError();
			//Delete Voucher
			$this->db->query("DELETE FROM trvoucher where fst_transaction_type ='SALESORDER' and fin_transaction_id = ?",[$finSalesOrderId]);
			throwIfDBError();

			for($i=0;$i<sizeof($details);$i++){			
				$detail = $details[$i];
				$item = $this->msitems_model->getSimpleDataById($detail->fin_item_id);
				$detail->fst_max_item_discount ="";
				if ($item != null){
					$detail->fst_max_item_discount = $item->fst_max_item_discount;
				}
				
				$details[$i] = $detail;
			}

			//** Cek if this transaction need authorization */				
			$needAuthorizeList = $this->trsalesorder_model->getAuthorizationList($dataH,$details);
			if ($needAuthorizeList["need_authorize"] == true){
				$this->db->query("Update trsalesorder set fst_active = 'S' where fin_salesorder_id = ?",[$finSalesOrderId]);
				$this->trsalesorder_model->generateApprovalData($needAuthorizeList,$finSalesOrderId,$dataH["fst_salesorder_no"]);
			}else{
				$this->db->query("Update trsalesorder set fst_active = 'A' where fin_salesorder_id = ?",[$finSalesOrderId]);
			}		
			

			//get list promo
			$selectedPromos = json_decode($this->input->post("details"));
			$arrPromo = $this->trsalesorder_model->getDataPromo($finSalesOrderId);

			//cek all selected is exist
			for($i=0;$i<sizeof($selectedPromos);$i++){
				
				$selectedPromo =$selectedPromos[$i];
				$found = false;

				foreach($arrPromo as $promo){

					if ($selectedPromo->fin_promo_id == $promo->fin_promo_id){

						if ($selectedPromo->fst_prize_type == "CSHBCK"){
							if ($selectedPromo->fdc_cashback == $promo->fdc_cashback){
								
								$found = true;
								//$selectedPromo["fin_item_id"]= 0;
								//$selectedPromo["fst_item_name"] ="Cashback " . $selectedPromo["fdc_cashback"];
								//$selectedPromos[$i] = $selectedPromo;
								continue;
							}
						}

						if ($selectedPromo->fst_prize_type == "OTH_ITEM"){
							if ($selectedPromo->fst_item_name == $promo->fst_other_prize){								
								$found = true;
								continue;
							}
						}

						if ($selectedPromo->fst_prize_type == "FREE_ITEM"){
							$ssql = "SELECT * FROM mspromoprizes where fin_promo_id =? and fst_active ='A'";
							$qr = $this->db->query($ssql,[$selectedPromo->fin_promo_id]);
							$rstemp = $qr->result();
							foreach($rstemp as $rw){
								if ($rw->fin_item_id == $selectedPromo->fin_item_id){
									$selectedPromo->fdb_qty= $rw->fdb_qty;
									$found = true;
									continue;
								}

							}
						}
					}
				}
				if ($found == false){
					throw new CustomException("Invalid prize !", 9009, "FAILED",["prize"=>$selectedPromo]);					
				}

				
			}

			// All selected Promo is valid add to detail SO
			foreach($selectedPromos as $selectedPromo){

				if ($selectedPromo->fst_prize_type == "CSHBCK"){
					
					$dataVoucher = [
						"fst_transaction_type"=>"SALESORDER",
						"fin_transaction_id"=>$finSalesOrderId,
						"fin_promo_id"=>$selectedPromo->fin_promo_id,
						"fin_branch_id"=>$dataH["fin_branch_id"],
						"fin_relation_id"=>$dataH["fin_relation_id"],
						"fdc_value"=> $selectedPromo->fdc_cashback,
						"fst_active"=>$dataH["fst_active"] //Bila tidak active maka diaktifkan pada saat approval SO
					];
					$this->trvoucher_model->createVoucher($dataVoucher);
				}

				if ($selectedPromo->fst_prize_type == "OTH_ITEM"){
					$data = [
						"fin_promo_id"=>$selectedPromo->fin_promo_id,
						"fin_salesorder_id"=>$finSalesOrderId,
						"fst_item_code"=> "PRZ",
						"fin_item_id" => 0,
						"fst_custom_item_name"=>$selectedPromo->fst_item_name,
						"fst_max_item_discount" => "100",
						"fst_unit"=>"PCS",
						"fdb_qty"=>1,
						"fdc_price"=>1,
						"fst_disc_item"=>"100",
						"fdc_disc_amount_per_item"=>1,
						"fst_active"=>"A"
					];
					$this->trsalesorderdetails_model->insert($data);
				}

				if ($selectedPromo->fst_prize_type == "FREE_ITEM"){
				
					$ssql = "SELECT a.*,b.fst_item_name,b.fst_item_code FROM mspromoprizes a 
						INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
						where a.fin_promo_id = ? and a.fin_item_id = ? and a.fst_active ='A'";
					$qr = $this->db->query($ssql,[$selectedPromo->fin_promo_id,$selectedPromo->fin_item_id]);
					$promo = $qr->row();
					$data = [
						"fin_promo_id"=>$selectedPromo->fin_promo_id,
						"fin_salesorder_id"=>$finSalesOrderId,
						"fst_item_code"=> $promo->fst_item_code,
						"fst_item_name"=>$promo->fst_item_name,
						"fin_item_id" => $selectedPromo->fin_item_id,
						"fst_custom_item_name"=>$selectedPromo->fst_item_name,
						"fst_max_item_discount" => "100",
						"fst_unit"=>$promo->fst_unit,
						"fdb_qty"=>$promo->fdb_qty,
						"fdc_price"=>1,
						"fst_disc_item"=>"100",
						"fdc_disc_amount_per_item"=>1,
						"fst_active"=>"A"
					];
					$this->trsalesorderdetails_model->insert($data);
				}
					
					
			}
			$this->trsalesorder_model->posting($finSalesOrderId);

			$this->db->trans_complete();
			$this->json_output([
				"status"=>"SUCCESS",
				"messages"=>"Data Saved !",
				"data"=>[]
			]);


		}catch(CustomException $e){
			$this->db->trans_rollback();

			$this->json_output([
				"status"=>$e->getStatus(),
				"messages"=>$e->getMessage(),
				"data"=>$e->getData()
			]);
		}

	}

	public function cek_promo($finSalesOrderId){
		$this->load->library("menus");		
		

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		
				
		$data["title"] = "Add Items Promo";
		
		$arrPromo = $this->trsalesorder_model->getDataPromo($finSalesOrderId);
		$data["promos"] = $arrPromo;
		$data["fin_salesorder_id"] = $finSalesOrderId;

		$page_content = $this->parser->parse('pages/tr/sales_order/form_promo', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("(select a.*,b.fst_relation_name as fst_customer from trsalesorder a inner join msrelations b on a.fin_relation_id = b.fin_relation_id) a");

		$selectFields = "a.fin_salesorder_id,a.fst_salesorder_no,a.fdt_salesorder_datetime,a.fst_memo,a.fst_customer,a.fst_active,a.fdc_subttl,a.fdc_disc_amount,a.fdc_vat_amount,a.fst_curr_code,a.fdc_downpayment,a.fdc_downpayment_paid,a.fbl_is_closed,'action' as action";
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
			$insertDate = strtotime($data["fdt_salesorder_datetime"]);
			$data["fdt_salesorder_date"] = date("d-M-Y",$insertDate);
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_salesorder_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_salesorder_id"] . "'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fin_salesorder_id){
		$this->load->model("trsalesorder_model");
		$data = $this->trsalesorder_model->getDataById($fin_salesorder_id);	
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = $data;		
		$this->json_output();
	}

	public function delete($finSalesOrderId){
		parent::delete($finSalesOrderId);
		$this->load->model("trsalesorder_model");
		$this->load->model('trsalesorder_model');
		$this->load->model('trvoucher_model');
		$this->load->model('trverification_model');
		
		
		$salesOrder = $this->trsalesorder_model->createObject($finSalesOrderId);

		//Is Editable ?		
		//CEK tgl lock dari transaksi yg di kirim	
		$resp = dateIsLock($salesOrder->getValue("fdt_salesorder_datetime"));
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}
		$resp = $this->trsalesorder_model->isEditable($finSalesOrderId);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();
			$this->trsalesorder_model->unposting($finSalesOrderId);
			$this->trsalesorder_model->delete($finSalesOrderId);
			$this->db->trans_complete();

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("Data dihapus !");
			$this->json_output();
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
		}
        
	}

	
	public function ajxGetCustomerList(){
		$this->load->model("msrelations_model");

		$custList = $this->msrelations_model->getCustomerList();
		$arrData =[];
		foreach($custList as $cust){
			$arrData[] = [
				"fin_relation_id"=>$cust->fin_relation_id,
				"fst_relation_name"=>$cust->fst_relation_name,		
				"fin_terms_payment"=>$cust->fin_terms_payment,
				"fin_sales_id"=>$cust->fin_sales_id,
				"fin_cust_pricing_group_id"=>$cust->fin_cust_pricing_group_id,
			];
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$arrData
		]);
	}

	public function ajxGetDataItem(){
		$term = $this->input->get("term");		
		$ssql = "SELECT fin_item_id, fst_item_code,fst_item_name,fst_max_item_discount 
			FROM msitems 
			WHERE CONCAT(fst_item_code,' - ' ,fst_item_name) LIKE ? 
			AND fin_item_type_id='4' AND fst_active ='A' 
			ORDER BY CONCAT(fst_item_code,' - ' ,fst_item_name)";

		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}

	public function ajxGetUnitList(){
		$finItemId = $this->input->get("fin_item_id");

		$ssql ="SELECT fst_unit,fdc_conv_to_basic_unit 
			FROM msitemunitdetails where fin_item_id = ? and fbl_is_selling = 1 and fst_active = 'A'";

		$qr = $this->db->query($ssql,[$finItemId]);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$qr->result()
		]);
		

	}

	public function ajxGetSellingInfo(){

		//Return stock in basic unit

		$this->load->model("msitems_model");
		$this->load->model("trinventory_model");

		
		$finItemId = $this->input->get("fin_item_id");
		$finWarehouseId = $this->input->get("fin_warehouse_id");
		$fstUnit = $this->input->get("fst_unit");
		$finCustomerId =$this->input->get("fin_relation_id");			
		$sellingPrice = $this->msitems_model->getSellingPrice($finItemId,$fstUnit,$finCustomerId);

		$fstBasicUnit = $this->msitems_model->getBasicUnit($finItemId);
		$realStock = $this->trinventory_model->getStock($finItemId,$fstBasicUnit,$finWarehouseId);
		$marketingStock = $this->trinventory_model->getMarketingStock($finItemId,$fstBasicUnit,$finWarehouseId);

		$result = (object) [
			"fst_unit"=>$fstUnit,
			"fst_basic_unit"=>$fstBasicUnit,
			"sellingPrice" => $sellingPrice,
			"real_stock" => $realStock,
			"marketing_stock" => $marketingStock
		];

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$result,
		]);
	}

	public function ajxGetPromoItem(){		
		$dataH = $this->input->post();
		$finCustomerId = $dataH["fin_relation_id"];
		unset($dataH["detail"]);
		$details = $this->input->post("detail");		
		$isIncludePPN = getDbConfig("sales_price_inc_ppn") == 1 ?  true : false;
		$ppn = $isIncludePPN ? (double) getDbConfig("sales_ppn_percent") : (double) $dataH["fdc_vat_percent"];

		$details = json_decode($details);
		//$rsPromoItem = $this->trsalesorder_model->getDataPromo($finCustomerId,$dataH["fdc_vat_percent"],$isIncludePPN,$details);
		$rsPromoItem = $this->trsalesorder_model->getDataPromo($finCustomerId,$ppn,$isIncludePPN,$details);		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = $rsPromoItem;
		$this->json_output();
		return;
	}
	
	public function ajxGetDetailPromo($promoId){
		$this->load->model("mspromo_model");

		$promo = $this->mspromo_model->getSimpleDataById($promoId);

		$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name FROM mspromoprizes a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			WHERE a.fin_promo_id = ? and a.fst_active = 'A'";

		$qr = $this->db->query($ssql,[$promoId]);
		$rs = $qr->result();

		$data = [
			"promo"=>$promo,
			"free_items"=>$rs
		];

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$data
		]);


	}

//===== UNHOLD SALES ORDER ==============================================================================================================================================================================

	public function unhold(){
		$this->menuName = "unhold_so";
		parent::index();

		$this->load->library('menus');

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
		$data["title"] = lang("Unhold Sales Order");

        $page_content = $this->parser->parse('pages/tr/sales_order/unhold',$data, true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar = NULL;
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;        
		$this->parser->parse('template/main',$this->data);
	}

	public function unhold_fetch_list_data(){
		$this->load->library("datatables");

		$useractive = $this->aauth->get_user_id();
        $user = $this->aauth->user();

		/*
		$this->datatables->setTableName("(select a.*,b.fst_relation_name from trsalesorder a left join msrelations b
			on a.fin_relation_id = b.fin_relation_id where a.fbl_is_hold = '1' and a.fin_insert_id = $useractive) a ");
		*/
		$this->datatables->setTableName("(select a.*,b.fst_relation_name from trsalesorder a inner join msrelations b
			on a.fin_relation_id = b.fin_relation_id where a.fbl_is_hold = '1') a ");
		
		$selectFields = "a.fin_salesorder_id,a.fst_salesorder_no,a.fdt_salesorder_datetime,a.fst_relation_name,a.fst_memo,a.fdt_unhold_datetime,a.fin_unhold_id";
		$this->datatables->setSelectFields($selectFields);

		$searchFields = [];
		$searchFields[] = $this->input->get('optionSearch');
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";

		//Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//$insertDate = strtotime($data["fdt_unhold_datetime"]);
			//$data["fdt_unhold_datetime"] = date("d-M-Y H:i:s",$insertDate);
			$arrDataFormated[] =$data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function doUnhold($finSalesOrderId){
		$this->menuName = "unhold_so";
		parent::ajx_add_save();

		$this->load->model('trsalesorder_model');

        $this->db->trans_start();
        $this->trsalesorder_model->unhold($finSalesOrderId);
        $this->db->trans_complete();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]=[];
        $this->json_output();
	}	

	public function check_authorization(){
		$dataH = $this->input->post();
		unset($dataH["detail"]);
		$details = $this->input->post("detail");
		$details = json_decode($details);
		
		$needAuthorizeList = $this->trsalesorder_model->getAuthorizationList($dataH,$details);
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = $needAuthorizeList;
		$this->json_output();
		return;
	}

	public function close_status_po($isChecked){
		$finSalesOrderId = $this->input->post("fin_salesorder_id");
		$fstClosedNote = $this->input->post("fst_closed_note");
		$this->trsalesorder_model->closeManual($finSalesOrderId,$fstClosedNote,$isChecked);
		try{
			throwIfDBError();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"] = "";
			$this->json_output();
			return;

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}
		
	
	}

	public function print_voucher($finSalesOrderId){

		$this->data = $this->trsalesorder_model->getDataVoucher($finSalesOrderId);

		$this->data["title"] = "SALES ORDER S/O";		
		$page_content = $this->parser->parse('pages/tr/sales_order/voucher', $this->data, true);
		$dataMain=["PAGE_CONTENT"=>$page_content];	
	    $this->parser->parse('template/voucher_pdf',$dataMain);

	}
}
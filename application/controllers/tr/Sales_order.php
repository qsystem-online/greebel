<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends MY_Controller{

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
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
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
			['title' => 'Sales Order No.', 'width' => '120px', 'data' => 'fst_salesorder_no'],
			['title' => 'Sales Order Date', 'width' => '80px', 'data' => 'fdt_salesorder_datetime'],
			['title' => 'Customer', 'width' => '130px', 'data' => 'fst_customer'],
			['title' => 'Memo', 'width' => '120px', 'data' => 'fst_memo'],
			
			['title' => 'Total', 'width' => '100px','className'=>'text-right',
				'render'=>"function(data,type,row){
					var total = parseFloat(row.fdc_subttl) - parseFloat(row.fdc_disc_amount) + parseFloat(row.fdc_vat_amount);
					return row.fst_curr_code + ':' + App.money_format(total);
				}"
			],
			['title' => 'DP', 'width' => '80px', 'data' => 'fdc_downpayment','className' => 'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}"
			],
			['title' => 'DP paid', 'width' => '80px', 'data' => 'fdc_downpayment_paid','className' => 'text-right',
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
					}
				}"
			],
			['title' => 'Closed', 'width' => '50px', 'data' => 'fbl_is_closed','className'=>'text-center',
				'render'=>"function(data,type,row){
					if(data == 1){
						return '<input class=\"isClosed\" type=\"checkbox\" value=\"1\" checked>';
					}else{
						return '<input class=\"isClosed\" type=\"checkbox\" value=\"0\" >';
					}					
				}"
			],

			['title' => 'Action', 'width' => '60px', 'sortable' => false, 'className' => 'dt-body-center text-center',
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
		$this->openForm("ADD", 0);
	}

	public function edit($fin_salesorder_id){
		$this->openForm("EDIT", $fin_salesorder_id);
	}

	public function view($finSalesOrderId){
		$this->openForm("VIEW", $finSalesOrderId);
	}
	
	public function ajx_add_save(){		
		$fst_salesorder_no = $this->trsalesorder_model->GenerateSONo();
		$fdt_salesorder_datetime = dBDateTimeFormat($this->input->post("fdt_salesorder_datetime"));
		$fdc_downpayment = parseNumber($this->input->post("fdc_downpayment"));
		$isHold = ($this->input->post("fbl_is_hold") == false) ? 0 : 1;
		$exchangeRateIDR = parseNumber($this->input->post("fdc_exchange_rate_idr"));

		//CEK tgl lock dari transaksi yg di kirim
		$resp = dateIsLock($fdt_salesorder_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}



		//PREPARE DATA
		$dataH = [
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
            "fst_salesorder_no" => $fst_salesorder_no,
			"fdt_salesorder_datetime" => $fdt_salesorder_datetime,
			"fst_curr_code"=>$this->input->post("fst_curr_code"),
			"fdc_exchange_rate_idr"=> $exchangeRateIDR,
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_terms_payment"=>$this->input->post("fin_terms_payment"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),			
			"fbl_is_hold" => $isHold,
			"fbl_is_vat_include" => ($this->input->post("fbl_is_vat_include") == null) ? 0 : 1,
			"fin_shipping_address_id" =>$this->input->post("fin_shipping_address_id"),			
			"fst_memo" =>$this->input->post("fst_memo"),
			"fdc_subttl"=>0,// calculate from detail
			"fdc_disc_amount" => 0, //get Total Disc recalculate
			"fdc_dpp_amount" => 0, // calculate from detail
			"fdc_vat_percent" => $this->input->post("fdc_vat_percent"),
			"fdc_vat_amount" => 0, //total vat recalculate
			"fdc_total" => 0, //total recalculate
			"fdc_downpayment" => $fdc_downpayment,
			"fbl_dp_inc_ppn" => ($this->input->post("fbl_dp_inc_ppn") == null) ? 0 : 1,
			"fdc_downpayment_paid"=>0,
			"fbl_is_closed"=>0,
			"fst_active" => 'S' //SEMUA SO HARUS DI APPROVE DULU
		];
		

		$details = $this->input->post("detail");
		$details = json_decode($details);
		$arrItem = $this->msitems_model->getDetailbyArray(array_column($details, 'fin_item_id'));

		$subTtl = 0;
		$ttlDiscAmount = 0;
		$ttlVATAmount = 0;

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

		$details = array_values($details);

		if ($dataH["fbl_is_vat_include"] ==  1){
			$ttlDPPAmount = ($subTtl - $ttlDiscAmount) / (1 + ($dataH["fdc_vat_percent"] / 100)) ;
		}else{
			$ttlDPPAmount = $subTtl - $ttlDiscAmount;	
		}

		$ttlVATAmount  =  $ttlDPPAmount * ($dataH["fdc_vat_percent"] / 100);
		$dataH["fdc_subttl"] = $subTtl;	
		$dataH["fdc_disc_amount"] = $ttlDiscAmount;	
		$dataH["fdc_dpp_amount"] = $ttlDPPAmount;
		$dataH["fdc_vat_amount"] = $ttlVATAmount;
		$dataH["fdc_total"] = $ttlDPPAmount + $ttlVATAmount;
		

		//VALIDATION
		$this->form_validation->set_rules($this->trsalesorder_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms 1";
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

		//Get Promo Item		
		$rsPromoItem = $this->trsalesorder_model->getDataPromo($dataH["fin_relation_id"],$dataH["fdc_vat_percent"],$dataH["fbl_is_vat_include"],$details);
		

		//** Cek if this transaction need authorization */		
		$needAuthorizeList = $this->trsalesorder_model->getAuthorizationList($dataH,$details);
		$dataH["fst_active"] = ($needAuthorizeList["need_authorize"] == true) ? "S" :"A";		

		
		try{
			$this->db->trans_start();
			//SAVE
			$insertId = $this->trsalesorder_model->insert($dataH);
			$this->my_model->throwIfDBError();

			foreach($rsPromoItem as $promo){ //Add Detail promo			
				if ($promo->fin_promo_item_id != null && $promo->fin_promo_item_id != ""){
					$dataD = new stdClass();
					$dataD->fin_rec_id = "0";
					$dataD->fin_promo_id = $promo->fin_promo_id;
					$dataD->fin_item_id = $promo->fin_promo_item_id;
					$dataD->fst_item_name = $promo->fst_item_name;
					$dataD->fst_item_code = $promo->fst_item_code;
					$dataD->fst_custom_item_name = $promo->fst_item_name;
					$dataD->fst_max_item_discount = "100";
					$dataD->fdb_qty = $promo->fdb_promo_qty;
					$dataD->fst_unit = $promo->fst_promo_unit;
					$dataD->fdc_price = 1;
					$dataD->fst_disc_item = 100;
					$dataD->fdc_disc_amount_per_item = 1;
					$dataD->fst_memo_item = "";
					$dataD->total = 0;
					$dataD->real_stock = 0;
					$dataD->marketing_stock = 0;
					$dataD->fdc_conv_to_basic_unit = 1;
					$dataD->fst_basic_unit = "";				
					$details[] = $dataD;
				}
				if ($promo->fst_other_prize != null && $promo->fst_other_prize != ""){
					$dataD = new stdClass();
					$dataD->fin_rec_id = "0";
					$dataD->fin_promo_id = $promo->fin_promo_id;
					$dataD->fin_item_id = 0;
					$dataD->fst_item_name = $promo->fst_other_prize;
					$dataD->fst_item_code = "PRZ";
					$dataD->fst_custom_item_name = $promo->fst_other_prize;
					$dataD->fst_max_item_discount = "100";
					$dataD->fdb_qty = 1;
					$dataD->fst_unit = "PCS";
					$dataD->fdc_price = 1;
					$dataD->fst_disc_item = 100;
					$dataD->fdc_disc_amount_per_item = 1;
					$dataD->fst_memo_item = "";
					$dataD->total = 0;
					$dataD->real_stock = 0;
					$dataD->marketing_stock = 0;
					$dataD->fdc_conv_to_basic_unit = 1;
					$dataD->fst_basic_unit = "";				
					$details[] = $dataD;
				}
	
				if ($promo->fdc_cashback > 0 ){ //Create Cash back Voucher					
					$this->load->model("trvoucher_model");
					$dataVoucher = [
						"fst_transaction_type"=>"SALESORDER",
						"fin_transaction_id"=>$insertId,
						"fin_promo_id"=>$promo->fin_promo_id,
						"fin_branch_id"=>$dataH["fin_branch_id"],
						"fin_relation_id"=>$dataH["fin_relation_id"],
						"fdc_value"=> $promo->fdc_cashback,
						"fst_active"=>$dataH["fst_active"] //Bila tidak active maka diaktifkan pada saat approval SO
					];
					$this->trvoucher_model->createVoucher($dataVoucher);
					/*
					$dataD = new stdClass();
					$dataD->fin_rec_id = "0";
					$dataD->fin_promo_id = $promo->fin_promo_id;
					$dataD->fin_item_id = 0;
					$dataD->fst_item_name = lang("Voucher cashback promotion") . "(" .  formatNumber($promo->fdc_cashback) . ")";
					$dataD->fst_item_code = "CSHBACK";
					$dataD->fst_custom_item_name = lang("Voucher cashback promotion") . "(" .  formatNumber($promo->fdc_cashback) . ")";
					$dataD->fst_max_item_discount = "100";
					$dataD->fdb_qty = 1;
					$dataD->fst_unit = "";
					$dataD->fdc_price = 1;
					$dataD->fst_disc_item = 100;
					$dataD->fdc_disc_amount = 1;
					$dataD->fst_memo_item = "";
					$dataD->total = 0;
					$dataD->real_stock = 0;
					$dataD->marketing_stock = 0;
					$dataD->fdc_conv_to_basic_unit = 1;
					$dataD->fst_basic_unit = "";	
					$dataD->fdc_cashback =$promo->fdc_cashback;			
					$details[] = $dataD;
					*/
				}
			}

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
			}

			//Create authorize record
			$this->trsalesorder_model->generateApprovalData($needAuthorizeList,$insertId,$dataH["fst_salesorder_no"]);

			//POSTING
			$this->trsalesorder_model->posting($insertId);
						
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $insertId;
			$this->json_output();

		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}
	}

	public function ajx_edit_save(){
		$this->load->model('trsalesorder_model');
		$this->load->model('trvoucher_model');
		$this->load->model('trverification_model');
		
		$fin_salesorder_id = $this->input->post("fin_salesorder_id");
		$fdt_salesorder_datetime = dBDateTimeFormat($this->input->post("fdt_salesorder_datetime"));
		$fdc_downpayment = parseNumber($this->input->post("fdc_downpayment"));
		$isHold = ($this->input->post("fbl_is_hold") == false) ? 0 : 1;
		$exchangeRateIDR = parseNumber($this->input->post("fdc_exchange_rate_idr"));

		
		//Is Editable ?
		//CEK tgl lock dari transaksi yg di kirim	
		$resp = dateIsLock($fdt_salesorder_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;		
		}

		$salesOrder = $this->trsalesorder_model->createObject($fin_salesorder_id);
		$resp = dateIsLock($salesOrder->getValue("fdt_salesorder_datetime"));
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}
		$resp = $this->trsalesorder_model->isEditable($fin_salesorder_id);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}	

		try{
			$this->db->trans_start();
			//Unposting
			$this->trsalesorder_model->unposting($fin_salesorder_id);

			//Delete data detail & voucher & verification
			$ssql = "DELETE from trsalesorderdetails where fin_salesorder_id = ?";
			$this->db->query($ssql,[$fin_salesorder_id]);
			throwIfDBError();

			$this->trvoucher_model->deleteVoucher("SALESORDER",$fin_salesorder_id);
			$this->trverification_model->deleteApproval("SO",$fin_salesorder_id);			

			//PREPARE DATA
			$dataH = [
				"fin_salesorder_id"=>$fin_salesorder_id,
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
				"fbl_is_vat_include" => ($this->input->post("fbl_is_vat_include") == null) ? 0 : 1,
				"fin_shipping_address_id" =>$this->input->post("fin_shipping_address_id"),			
				"fst_memo" =>$this->input->post("fst_memo"),
				"fdc_subttl"=>0,// calculate from detail
				"fdc_disc_amount" => 0, //get Total Disc recalculate
				"fdc_dpp_amount" => 0, // calculate from detail
				"fdc_vat_percent" => $this->input->post("fdc_vat_percent"),
				"fdc_vat_amount" => 0, //total vat recalculate
				"fdc_total" => 0, //total recalculate
				"fdc_downpayment" => $fdc_downpayment,
				"fbl_dp_inc_ppn" => ($this->input->post("fbl_dp_inc_ppn") == null) ? 0 : 1,
				"fdc_downpayment_paid"=>0,
				"fbl_is_closed"=>0,
				"fst_active" => 'A'				
			];

			$details = $this->input->post("detail");
			$details = json_decode($details);
			$arrItem = $this->msitems_model->getDetailbyArray(array_column($details, 'fin_item_id'));

			$subTtl = 0;
			$ttlDiscAmount = 0;
			$ttlVATAmount = 0;

			for($i = 0; $i < sizeof($details) ; $i++){
				$item = $details[$i];
				//remove if promo item
				if ($details[$i]->fin_promo_id != 0){
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
				//$details[$i]->fdc_disc_amount = calculateDisc($item->fst_disc_item,$subTtl);			

				$ttlDiscAmount += $details[$i]->fdc_disc_amount_per_item * $details[$i]->fdb_qty;
			}
			$details = array_values($details);

			if ($dataH["fbl_is_vat_include"] ==  1){
				$ttlDPPAmount = ($subTtl - $ttlDiscAmount) / (1 + ($dataH["fdc_vat_percent"] / 100)) ;
			}else{
				$ttlDPPAmount = $subTtl - $ttlDiscAmount;	
			}

			$ttlVATAmount  =  $ttlDPPAmount * ($dataH["fdc_vat_percent"] / 100);
			$dataH["fdc_subttl"] = $subTtl;	
			$dataH["fdc_disc_amount"] = $ttlDiscAmount;	
			$dataH["fdc_dpp_amount"] = $ttlDPPAmount;
			$dataH["fdc_vat_amount"] = $ttlVATAmount;
			$dataH["fdc_total"] = $ttlDPPAmount + $ttlVATAmount;
			

			//VALIDATION
			$this->form_validation->set_rules($this->trsalesorder_model->getRules("ADD", 0));
			$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
			$this->form_validation->set_data($dataH);

			if ($this->form_validation->run() == FALSE) {
				//print_r($this->form_validation->error_array());
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = "Error Validation Forms 1";
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

			//Get Promo Item		
			$rsPromoItem = $this->trsalesorder_model->getDataPromo($dataH["fin_relation_id"],$dataH["fdc_vat_percent"],$dataH["fbl_is_vat_include"],$details);
			
			//** Cek if this transaction need authorization */		
			$needAuthorizeList = $this->trsalesorder_model->getAuthorizationList($dataH,$details);
			$dataH["fst_active"] = ($needAuthorizeList["need_authorize"] == true) ? "S" :"A";		
					
			//SAVE
			$insertId = $dataH["fin_salesorder_id"];
			$this->trsalesorder_model->update($dataH);
			foreach($rsPromoItem as $promo){ //Add Detail promo			
				if ($promo->fin_promo_item_id != null && $promo->fin_promo_item_id != ""){
					$dataD = new stdClass();
					$dataD->fin_rec_id = "0";
					$dataD->fin_promo_id = $promo->fin_promo_id;
					$dataD->fin_item_id = $promo->fin_promo_item_id;
					$dataD->fst_item_name = $promo->fst_item_name;
					$dataD->fst_item_code = $promo->fst_item_code;
					$dataD->fst_custom_item_name = $promo->fst_item_name;
					$dataD->fst_max_item_discount = "100";
					$dataD->fdb_qty = $promo->fdb_promo_qty;
					$dataD->fst_unit = $promo->fst_promo_unit;
					$dataD->fdc_price = 1;
					$dataD->fst_disc_item = 100;
					$dataD->fdc_disc_amount = 1;
					$dataD->fst_memo_item = "";
					$dataD->total = 0;
					$dataD->real_stock = 0;
					$dataD->marketing_stock = 0;
					$dataD->fdc_conv_to_basic_unit = 1;
					$dataD->fst_basic_unit = "";				
					$details[] = $dataD;
				}
				if ($promo->fst_other_prize != null && $promo->fst_other_prize != ""){
					$dataD = new stdClass();
					$dataD->fin_rec_id = "0";
					$dataD->fin_promo_id = $promo->fin_promo_id;
					$dataD->fin_item_id = 0;
					$dataD->fst_item_name = $promo->fst_other_prize;
					$dataD->fst_item_code = "PRZ";
					$dataD->fst_custom_item_name = $promo->fst_other_prize;
					$dataD->fst_max_item_discount = "100";
					$dataD->fdb_qty = 1;
					$dataD->fst_unit = "PCS";
					$dataD->fdc_price = 1;
					$dataD->fst_disc_item = 100;
					$dataD->fdc_disc_amount = 1;
					$dataD->fst_memo_item = "";
					$dataD->total = 0;
					$dataD->real_stock = 0;
					$dataD->marketing_stock = 0;
					$dataD->fdc_conv_to_basic_unit = 1;
					$dataD->fst_basic_unit = "";				
					$details[] = $dataD;
				}
	
				if ($promo->fdc_cashback > 0 ){ //Create Cash back Voucher					
					$this->load->model("trvoucher_model");
					$dataVoucher = [
						"fst_transaction_type"=>"SALESORDER",
						"fin_transaction_id"=>$insertId,
						"fin_promo_id"=>$promo->fin_promo_id,
						"fin_branch_id"=>$dataH["fin_branch_id"],
						"fin_relation_id"=>$dataH["fin_relation_id"],
						"fdc_value"=> $promo->fdc_cashback,
						"fst_active"=>$dataH["fst_active"] //Bila tidak active maka diaktifkan pada saat approval SO
					];
					$this->trvoucher_model->createVoucher($dataVoucher);
				}
			}

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
			}

			//Create authorize record
			$this->trsalesorder_model->generateApprovalData($needAuthorizeList,$insertId,$dataH["fst_salesorder_no"]);

			//POSTING
			$this->trsalesorder_model->posting($insertId);
			$this->db->trans_complete();

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $insertId;
			$this->json_output();

		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;

		}
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

	public function get_customers(){
		$this->load->model("msrelations_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msrelations_model->getCustomerList();
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

	public function getSelect2_sales_warehouse_currency(){
		$salesDeptId = getDbConfig("sales_department_id");
		$activeBranchId = $this->aauth->get_active_branch_id();

		$ssql = "select fin_user_id, fst_username from users where  fin_branch_id =? and fin_department_id = $salesDeptId order by fst_username";
		$qr = $this->db->query($ssql,[$activeBranchId]);
		//lastQuery();
		$rsSales = $qr->result();
		
		$ssql = "select fin_warehouse_id, fst_warehouse_name from mswarehouse where fin_branch_id =?";
		$qr = $this->db->query($ssql,[$activeBranchId]);
		$rsWarehouse = $qr->result();
	
		//$ssql = "select CurrCode, CurrName from mscurrencies";
		//$qr = $this->db->query($ssql,[]);
		//$rscurrencies = $qr->result();
		$rscurrencies = [];
		$rscurrencies[] = getDefaultCurrency();

		$data = [
			"sales" => $rsSales,
			"warehouse" => $rsWarehouse,
			"currencies" => $rscurrencies
		];
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

	public function get_data_item(){
		$term = $this->input->get("term");		
		$ssql = "select fin_item_id, CONCAT(fst_item_code,' - ' ,fst_item_name) as ItemCodeName,fst_item_code,fst_item_name,fst_max_item_discount from msitems where CONCAT(fst_item_code,' - ' ,fst_item_name) like ? order by fst_item_name";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		$this->json_output($rs);
	}

	public function get_data_disc(){
		$term = $this->input->get("term");
		$ssql = "select fst_item_discount from msitemdiscounts where fst_item_discount like ? order by fst_item_discount";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();

		$this->json_output($rs);
	}

	public function getSoDetail($fin_salesorder_id){
        $this->load->model("trsalesorderdetails_model");
        $result = $this->trsalesorderdetails_model->getSoDetail($fin_salesorder_id);
        $this->ajxResp["data"] = $result;
        $this->json_output();
	}

	/*public function report_sales_order(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("trsalesorder_model");
		$listSalesOrder = $this->trsalesorder_model->getSales_order();
        $data = [
			"datas" => $listSalesOrder
		];
			
        $this->pdf->load_view('report/sales_order_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
	}*/
	
	public function coba(){
		$ssql = "create temporary table tbltmp select * from users";
		$this->db->query($ssql,[]);
		
		$ssql = "select * from tbltmp";
		$qr = $this->db->query($ssql,[]);
		//print_r($qr->result());
		
	}
	public function coba2(){
		$ssql = "select * from tbltmp";
		$qr = $this->db->query($ssql,[]);
		print_r($qr->result());
		
		
	}

	public function testPromo(){
		$this->load->model("trsalesorder_model");
		$fin_customer_id =9;
		$arrItem = [
			["fin_item_id"=>"2","fdb_qty"=>22,"fst_unit"=>"pack","fdc_price"=>10000,"fst_disc_item"=>0,"fdc_disc_amount"=>0],
			["fin_item_id"=>"1","fdb_qty"=>9,"fst_unit"=>"pack","fdc_price"=>20000,"fst_disc_item"=>0,"fdc_disc_amount"=>0]
		];
		$details = json_encode($arrItem);
		$details = json_decode($details);


		$promo = $this->trsalesorder_model->getDataPromo($fin_customer_id,$details);
		echo "<br><br><br><br><br>Promo Item :  :<br>";
		var_dump($promo);
	}

//===== UNHOLD SALES ORDER ==============================================================================================================================================================================

	public function unhold(){
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

		//$this->datatables->setTableName("(select * from trsalesorder where fbl_is_hold = '1' and fin_insert_id = $useractive) a ");
		$this->datatables->setTableName("(select a.*,b.fst_relation_name from trsalesorder a left join msrelations b
			on a.fin_relation_id = b.fin_relation_id where a.fbl_is_hold = '1' and a.fin_insert_id = $useractive) a ");

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
		$this->load->model('trsalesorder_model');

        $this->db->trans_start();
        $this->trsalesorder_model->unhold($finSalesOrderId);
        $this->db->trans_complete();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]=[];
        $this->json_output();
	}
	
	public function get_promo_item(){		
		$dataH = $this->input->post();
		$finCustomerId = $dataH["fin_relation_id"];
		unset($dataH["detail"]);
		$details = $this->input->post("detail");
		$isIncludePPN = isset($dataH["fbl_is_vat_include"]) ?  true : false;
		$details = json_decode($details);

		$rsPromoItem = $this->trsalesorder_model->getDataPromo($finCustomerId,$dataH["fdc_vat_percent"],$isIncludePPN,$details);
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = $rsPromoItem;
		$this->json_output();
		return;
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
}
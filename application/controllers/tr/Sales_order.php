<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trsalesorder_model');
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
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Sales Order" : "Update Sales Order";
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

	public function Edit($fin_salesorder_id){
		$this->openForm("EDIT", $fin_salesorder_id);
	}

	public function ajx_add_save(){
		$this->load->model('trsalesorder_model');
		$this->load->model("trsalesorderdetails_model");
		$this->load->model("trinventory_model");
		$this->load->model("msitems_model");
		$this->load->model("msrelations_model");

		$cekPromo = $this->input->post("cekPromo");
		$confirmAuthorize = $this->input->post("confirmAuthorize");

		$this->form_validation->set_rules($this->trsalesorder_model->getRules("ADD", 0));
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
		$fst_salesorder_no = $this->trsalesorder_model->GenerateSONo();

		$dataH = [
            "fst_salesorder_no" => $fst_salesorder_no,
			"fdt_salesorder_date" => dBDateFormat($this->input->post("fdt_salesorder_date")),
			"fst_curr_code"=>$this->input->post("fst_curr_code"),
			"fdc_exchange_rate_idr"=>$this->input->post("fdc_exchange_rate_idr"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_terms_payment"=>$this->input->post("fin_terms_payment"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),			
			"fbl_is_hold" => ($this->input->post("fbl_is_hold") == false) ? 0 : 1,
			"fbl_is_vat_include" => ($this->input->post("fbl_is_vat_include") == false) ? 0 : 1,
			"fst_shipping_address" =>$this->input->post("fst_shipping_address"),			
			"fst_memo" =>$this->input->post("fst_memo"),
			"fdc_dpp_amount" => 0, // calculate from detail
			"fdc_vat_percent" => $this->input->post("fdc_vat_percent"),
			"fdc_vat_amount" => 0, //total vat recalculate
			"fdc_disc_amount" => 0, //get Total Disc recalculate
			"fdc_downpayment" =>$this->input->post("fdc_downpayment"),
			"fst_active" => 'A'
		];


		$this->form_validation->set_rules($this->trsalesorderdetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$details = $this->input->post("detail");
		$details = json_decode($details);
		
		$arrItem = $this->msitems_model->getDetailbyArray(array_column($details, 'fin_item_id'));

		for($i = 0; $i < sizeof($details) ; $i++){
		//foreach ($details as $item) {
			$item = $details[$i];
			$objItem = $arrItem[$item->fin_item_id];

			$details[$i]->fdc_disc_amount = calculateDisc($item->fst_disc_item,$item->fdc_price);

			
			$dataDetail = [
				"fin_item_id"=> $item->fin_item_id,
				"fdb_qty"=> $item->fdb_qty,
				"fdc_price"=> $item->fdc_price, 
				"fst_disc_item" => $item->fst_disc_item,
				"fdc_disc_amount" => calculateDisc($item->fst_disc_item,$item->fdc_price),				
			];

			//calculate max disc
			$maxDiscItemMoney = calculateDisc($objItem->fst_max_item_discount,$dataDetail["fdc_price"]);
			if ($maxDiscItemMoney > 0 ){
				if($dataDetail["fdc_disc_amount"] > $maxDiscItemMoney ){
					show_404(); //harusnya tidak terjadi karena sudah di crosscek diclient
				}
			}
			
			// Validate SO Details
			$this->form_validation->set_data($dataDetail);
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
		$rsPromoItem = $this->trsalesorder_model->getDataPromo($this->input->post("fin_relation_id"),$details);
		if ( $rsPromoItem != false){
			if ($cekPromo == 1){
				//return ajax data promo
				$this->ajxResp["status"] = "INFOPROMO";
				$this->ajxResp["confirm_message"] = lang("You got promo item, check it ?");
				$this->ajxResp["message"] = "";
				$this->ajxResp["data"] = $rsPromoItem;
				$this->json_output();
				return;
			}
		}

		//** Cek if this transaction need authorization */
		$needAuthorize = false;
		//Cek Qty is Available, need authorization if qty not available
		$arrOutofStock =[];
		$authorizeOutofStock = false;
		foreach ($details as $item){
			$stock = $this->trinventory_model->getStock($item->fin_item_id,$item->fst_unit,$dataH["fin_warehouse_id"]);
			if($item->fdb_qty > $stock){
				$authorizeOutofStock = true;
				$needAuthorize = true;
				$arrOutofStock[] = [
					"fin_item_id"=>$item->fin_item_id,
					"fst_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"stock"=>$stock
				];
			}
		}
		
		//Cek Credit Limit, need authorization if credit limit is over
		$grandTotal = 0;
		foreach ($details as $item){
			$price = $this->msitems_model->getSellingPrice($item->fin_item_id,$item->fst_unit,$dataH["fin_relation_id"]);
			$total = ($item->fdb_qty * $price);

			//cek max disc
			$maxDiscPersen = $arrItem[$item->fin_item_id]->fst_max_item_discount;
			$maxDiscValue = calculateDisc($maxDiscPersen,$total);
			//get disc
			$itemDiscValue = calculateDisc($item->fst_disc_item,$total);
			if ($maxDiscValue < $itemDiscValue){
				//Invalid Data
			}
			$grandTotal += $total;			
		}
		$maxCreditLimit = $this->msrelations_model->getCreditLimit($dataH["fin_relation_id"]);		
		$arrOutstanding = $this->trsalesorder_model->getDataOutstanding($dataH["fin_relation_id"],$maxCreditLimit);
		$totalOutstanding = $arrOutstanding["totalOutstanding"];
		$authorizeCreditLimit = false;
		if ($totalOutstanding + $grandTotal > $maxCreditLimit){
			$arrOutstanding["maxCreditLimit"] = $maxCreditLimit;
			$authorizeCreditLimit = true;
			$needAuthorize = true;
		}

		if ($needAuthorize == true){
			if ($confirmAuthorize == 0){								
				$this->ajxResp["status"] = "CONFIRM_AUTHORIZE";
				$this->ajxResp["confirm_message"] = lang("Transaction need authorization, OK ?");
				$this->ajxResp["message"] = "";
				$this->ajxResp["data"] = [
					"arrOutofStock"=>$arrOutofStock,
					"arrOutstanding" => $arrOutstanding,
				];				
				$this->json_output();
				return;
			}
		}


		//Competed data header before save
		$totalDPP = 0;
		$subTotal =0;
		$totalDisc =0;
		foreach ($details as $item) {
			$subTotal += $item->fdb_qty * $item->fdc_price;
			$totalDisc += calculateDisc($item->fst_disc_item,$subTotal)   ;
		}
		if($dataH["fbl_is_vat_include"] == 1 ){
			$total = $subTotal - $totalDisc;			
			$vat = 1 + ($dataH["fdc_vat_percent"] * 1) / 100;
			$totalDPP = $total / $vat;
		}else{
			$totalDPP = $subTotal -  $totalDisc;			
		}

		$vat = ($dataH["fdc_vat_percent"] * 1) / 100;			

		$dataH["fdc_dpp_amount"] = $totalDPP; // calculate from detail
		$dataH["fdc_vat_amount"] = $totalDPP * $vat; //total vat recalculate
		$dataH["fdc_disc_amount"] = $totalDisc; //get Total Disc recalculate
		$dataH["fst_active"] = ($needAuthorize == true) ? "S" :"A";
					
		$this->db->trans_start();
		//Insert Data Header
		$insertId = $this->trsalesorder_model->insert($dataH);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}
		
		//Generate Data Promo to Detail & Create Voucher cash back
		foreach($rsPromoItem as $promoItem){
			if ($promoItem["modelPromotion"] == "ITEM" || $promoItem["modelPromotion"] == "OTHER ITEM" ){
				$item = [
					"fin_salesorder_id"=>0,
					"fin_item_id"=>$promoItem["fin_item_id"],
					"fst_custom_item_name"=>$promoItem["fst_custom_item_name"],
					"fst_unit"=>$promoItem["fst_unit"],
					"fdb_qty"=>$promoItem["fdb_qty"],
					"fdc_price"=>1,
					"fst_disc_item"=>100,
					"fdc_disc_amount"=>$promoItem["fdb_qty"] * 1,
					"fst_memo_item"=>"",
					"fin_promo_id"=>$promoItem["fin_promo_id"],
				];
				$details[] = (object) $item;
			}else{
				//Create Cash back Voucher hold dulu
			}
		}


		//Insert Data Detail
		foreach ($details as $item) {
			$dataDetail = (array) $item;


			$dataDetail =[
  				"fin_salesorder_id"=>$insertId,
  				"fin_item_id"=>$item->fin_item_id,
  				"fst_custom_item_name"=>$item->fst_custom_item_name,
				"fst_unit"=>$item->fst_unit,
				"fdb_qty"=>$item->fdb_qty,
				"fdc_price"=>$item->fdc_price,
				"fst_disc_item"=>$item->fst_disc_item,
				"fdc_disc_amount"=>$item->fdc_disc_amount,
				"fst_memo_item"=>$item->fst_memo_item,
				"fin_promo_id"=>$item->fin_promo_id,
				"fst_active"=> 'A'
			];

			$this->trsalesorderdetails_model->insert($dataDetail);			
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
		
		//Create authorize record
		if ($needAuthorize){
			//$authorizeOutofStock
			//Get Master
			$this->load->model("msverification_model");
			$this->load->model("trverification_model");

			if ($authorizeOutofStock){
				$arrVerify = $this->msverification_model->getData("SO","QtyOutStock");
				foreach($arrVerify as $verify){
					$dataVerify =[	
						"fst_controller"=>$verify->fst_controller,
						"fin_transaction_id"=>$insertId,
						"fin_seqno"=>$verify->fin_seqno,
						"fst_message"=>"Item for Sales Order " .$dataH["fst_salesorder_no"] ." Out of stock",
						"fin_department_id"=>$verify->fin_department_id,
						"fin_user_group_id"=>$verify->fin_user_group_id,
						"fst_verification_status"=>$verify->fin_user_group_id,
						"fst_notes"=>$verify->fin_user_group_id,
						"fst_active"=>$verify->fin_user_group_id,
					];				
					$this->trverification_model->insert($dataVerify);
				}
			}

			if ($authorizeCreditLimit){
				$arrVerify = $this->msverification_model->getData("SO","CreditLimit");
				foreach($arrVerify as $verify){
					$dataVerify =[	
						"fst_controller"=>$verify->fst_controller,
						"fin_transaction_id"=>$insertId,
						"fin_seqno"=>$verify->fin_seqno,
						"fst_message"=>"Sales Order " .$dataH["fst_salesorder_no"] . " Customer credit limit is reached",
						"fin_department_id"=>$verify->fin_department_id,
						"fin_user_group_id"=>$verify->fin_user_group_id,
						"fst_verification_status"=>$verify->fin_user_group_id,
						"fst_notes"=>$verify->fin_user_group_id,
						"fst_active"=>$verify->fin_user_group_id,
					];				
					$this->trverification_model->insert($dataVerify);
				}
			}
		}

		//Posting Transaction
		$this->trsalesorder_model->posting_so($insertId);



		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajx_edit_save(){

		echo "EDIT";
		return;
		
		$this->load->model('Sales_order_model');
		$fin_salesorder_id = $this->input->post("fin_salesorder_id");
		$data = $this->trsalesorder_model->getDataById($fin_salesorder_id);
		$sales_order = $data["sales_order"];
		if (!$sales_order) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_salesorder_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trsalesorder_model->getRules("EDIT", $fin_salesorder_id));
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
		$this->trsalesorder_model->update($data);
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
		/*$this->load->model("trsalesorderdetails_model");

		$this->form_validation->set_rules($this->trsalesorderdetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$this->trsalesorderdetails_model->deleteByDetail($fin_salesorder_id);

		//$this->load->model("trsalesorderdetails_model");		
		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"fin_salesorder_id"=> $fin_salesorder_id,
				"fin_item_id"=> $item->$fin_item_id,
				"fdb_qty"=> $item->fdb_qty,
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
			
			$this->trsalesorderdetails_model->insert($data);
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
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fin_salesorder_id){
		$this->load->model("trsalesorder_model");
		$data = $this->trsalesorder_model->getDataById($fin_salesorder_id);		
		$this->json_output($data);
	}

	public function delete($id){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->load->model("trsalesorder_model");
		$this->trsalesorder_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}

	public function get_msrelations(){
		$term = $this->input->get("term");
		$ssql = "select fin_relation_id, fst_relation_name,fin_sales_id,fst_shipping_address,fin_warehouse_id,fin_terms_payment from msrelations where fst_relation_name like ? and FIND_IN_SET(1,fst_relation_type)";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
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

	public function getSelect2_sales_warehouse_currency(){
		$salesDeptId = getDbConfig("sales_department_id");
		$ssql = "select fin_user_id, fst_username from users where  fin_department_id = $salesDeptId order by fst_username";
		$qr = $this->db->query($ssql,[]);
		
		$rsSales = $qr->result();
		
		$ssql = "select fin_warehouse_id, fst_warehouse_name from mswarehouse";
		$qr = $this->db->query($ssql,[]);
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

//===== UNHOLD ==============================================================================================================================================================================

	public function unhold(){
		$this->load->library('menus');
		$this->list['page_name'] = "Unhold Sales Order";
		$this->list['list_name'] = "Unhold SO List";
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url().'tr/sales_order/unhold_list_data';
		$this->list['arrSearch'] = [
			'fin_salesorder_id' => 'Sales Order ID',
			'fst_salesorder_no' => 'Sales Order No'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Unhold Sales Order', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Sales Order ID', 'width' => '15%', 'data' => 'fin_salesorder_id'],
			['title' => 'Sales Order No', 'width' => '15%', 'data' => 'fst_salesorder_no'],
			['title' => 'Sales Order Date', 'width' => '15%', 'data' => 'fdt_salesorder_date'],
			['title' => 'Memo', 'width' => '15%', 'data' => 'fst_memo'],
			['title' => 'Customer', 'width' => '15%', 'data' => 'fst_relation_name'],
			['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
		];
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('pages/tr/sales_order/unhold_list',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar=null;
        $this->data['ACCESS_RIGHT']="A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR']= $main_sidebar;
        $this->data['PAGE_CONTENT']= $page_content;
        $this->data['MAIN_FOOTER']= $main_footer;        
		$this->parser->parse('template/main',$this->data);
	}

	/*public function unhold_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("trsalesorder");

		$selectFields = "fin_salesorder_id,fst_salesorder_no,fdt_salesorder_date,fst_memo,fst_relation_name,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields = [];
		$searchFields[] = $this->input->get('optionSearch');
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		//Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDateTime = strtotime($data["fdt_unhold_datetime"]);
			$data["fdt_unhold_datetime"] = dBDateFormat("d-M-Y H:i:s",$fdt_unhold_datetime);

			//action
			$data["action"] = "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_salesorder_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_salesorder_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] =$data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);

	}*/
}
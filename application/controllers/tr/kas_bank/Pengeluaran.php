<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluaran extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trcbpayment_model');
		$this->load->model('glaccounts_model');
	}
	
	public function index(){

		$this->load->library('menus');
        $this->list['page_name'] = "Cash Bank - Paymnet";
        $this->list['list_name'] = "Cash & Bank Payment List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/kas_bank/pengeluaran/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/kas_bank/pengeluaran/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_cbpayment_no' => 'No Pengeluaran',
			'fst_supplier_name' => 'Supplier'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Cash & Bank', 'link' => '#', 'icon' => ''],
            ['title' => 'Payment', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. ', 'width' => '10px','visible'=>'false', 'data' => 'fin_cbpayment_id'],
            ['title' => 'Payment No.', 'width' => '80px', 'data' => 'fst_cbpayment_no'],
            ['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_cbpayment_datetime'],
            ['title' => 'Supplier', 'width' => '100px', 'data' => 'fst_supplier_name'],		
			['title' => 'Memo', 'width' => '100px', 'data' => 'fst_memo'],
			['title' => 'Currency', 'width' => '50px', 'data' => 'fst_curr_code'],
			['title' => 'Total Amount', 'width' => '50px', 'data' => 'fdc_total_payment','className'=>'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}",
			],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/kas_bank/pengeluaran/edit/' + row.fin_cbpayment_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/kas_bank/pengeluaran/listjs', [], true);

		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$this->list['mdlEditForm'] = $edit_modal;

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $page_content = $this->parser->parse('template/standardList_v2_0_0', $this->list, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main', $this->data);
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
        $this->datatables->setTableName("(
			SELECT a.fin_cbpayment_id,a.fst_cbpayment_no,a.fdt_cbpayment_datetime,a.fst_memo,b.fst_relation_name as fst_supplier_name,a.fst_curr_code,a.fst_active,sum(c.fdc_payment) as fdc_total_payment FROM trcbpayment a 
			INNER JOIN msrelations b on a.fin_supplier_id = b.fin_relation_id 
			INNER JOIN trcbpaymentitems c on a.fin_cbpayment_id = c.fin_cbpayment_id 
			GROUP BY a.fin_cbpayment_id,a.fst_cbpayment_no,a.fdt_cbpayment_datetime,a.fst_memo,b.fst_relation_name,a.fst_curr_code 
			) a");

        $selectFields = "a.fin_cbpayment_id,a.fst_cbpayment_no,a.fdt_cbpayment_datetime,a.fst_supplier_name,a.fst_memo,a.fst_curr_code,a.fdc_total_payment,a.fst_active";
        $this->datatables->setSelectFields($selectFields);

        $Fields = $this->input->get('optionSearch');
        $searchFields = [$Fields];
        $this->datatables->setSearchFields($searchFields);
        
        // Format Data
		$datasources = $this->datatables->getData();
		
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {        
            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
		
	}


    public function add(){
        $this->openForm("ADD", 0);

	}
	
	public function edit($finCBPaymentId){
        $this->openForm("EDIT", $finCBPaymentId);

    }

    private function openForm($mode = "ADD", $finCBPaymentId = 0){
        $this->load->library("menus");		
        $this->load->model("glaccounts_model");
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint', [], true);
		

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Pengeluaran") : lang("Update Pengeluaran");
		$data["fin_cbpayment_id"] = $finCBPaymentId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		if($mode == 'ADD'){
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
			$data["mdlJurnal"] = $jurnal_modal;
        }        
		
		$page_content = $this->parser->parse('pages/tr/kas_bank/pengeluaran/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function ajx_add_save(){	
		$this->load->model('trcbpaymentitems_model');
		$this->load->model('trcbpaymentitemstype_model');
		$this->load->model('kasbank_model');
		$this->load->model('glaccounts_model');

		/*
		_c9da2c3066cf64f25a59677d1666d7ac: 9e6ac86fd396250f7e536cab28e6d27b
		fin_cbpayment_id: 0
		type-pengeluaran: Cash
		fin_kasbank_id: 1
		fdt_cbpayment_datetime: 04-10-2019 13:54:06
		fst_cbpayment_no: ADJ/JKT/2019/10/00001
		fst_curr_code: IDR
		fdc_exchange_rate_idr: 1.00
		fin_supplier_id: 142
		fst_memo: 
		detailTrans: [{"fin_rec_id":0,"fst_trans_type":"DP_PO","fst_trans_type_name":"DP LPB Pembelian","fin_trans_id":"6","fst_trans_no":"PO/JKT/2019/09/00005","fdc_trans_amount":50000,"ttl_paid":0,"fdc_return_amount":0,"fdc_payment":50000}]
		detailPayment: [{"fin_rec_id":0,"fst_cbpayment_type":"TUNAI","fst_cbpayment_type_name":"Tunai","fst_curr_code":"IDR","fdc_exchange_rate_idr":"1","fst_glaccount_code":"111.111.002","fst_glaccount_code_name":"111.111.002 - ADJUSMENT","fin_ppc_id":"1","fin_ppc_id_name":"Divisi Accounting","fdc_amount":"50000","fst_referensi":"","fst_bilyet_no":"","fdt_clear_date":""}]
		*/

		//PREPARE DATA
		$fdt_cbpayment_datetime = dBDateTimeFormat($this->input->post("fdt_cbpayment_datetime"));
		$resp = dateIsLock($fdt_cbpayment_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$fst_cbpayment_no = $this->trcbpayment_model->generateCBPaymentNo($this->input->post("fin_kasbank_id"),$fdt_cbpayment_datetime);

		$fstCurrCode = $this->input->post("fst_curr_code");
		$fdcExchangeRateIdr = parseNumber($this->input->post("fdc_exchange_rate_idr"));
		if($fstCurrCode ==  null){
			$defaultCurr = getDefaultCurrency();
			$fstCurrCode = $defaultCurr["CurrCode"];
			$fdcExchangeRateIdr = 1;
		}

		$dataH = [
			//"fin_cbpayment_id"=>
			"fst_cbpayment_no"=>$fst_cbpayment_no,
			"fin_kasbank_id"=>$this->input->post("fin_kasbank_id"),
			"fdt_cbpayment_datetime"=>$fdt_cbpayment_datetime,
			"fin_supplier_id"=>$this->input->post("fin_supplier_id"),
			"fst_curr_code"=>$fstCurrCode,
			"fdc_exchange_rate_idr"=>$fdcExchangeRateIdr,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',			
		];
		$totalTransaksiIDR = 0;
		$totalPaymentIDR = 0;
		
		$detailsTransaksi = $this->input->post("detailTrans");
		$detailsTransaksi = json_decode($detailsTransaksi);

		$detailsPayment = $this->input->post("detailPayment");
		$detailsPayment = json_decode($detailsPayment);
		
		//VALIDASI DATA
		$this->form_validation->set_rules($this->trcbpayment_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Header";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->trcbpaymentitems_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($detailsTransaksi) ; $i++){
			$item = $detailsTransaksi[$i];
			// Validate item Details
			$this->form_validation->set_data((array)$detailsTransaksi[$i]);
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

		$this->form_validation->set_rules($this->trcbpaymentitemstype_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($detailsPayment) ; $i++){
			$item = $detailsPayment[$i];
			$detailsPayment[$i]->fdt_clear_date = dBDateFormat($item->fdt_clear_date);
			$detailsPayment[$i]->fin_pcc_id = $detailsPayment[$i]->fin_pcc_id == "" ? NULL : $detailsPayment[$i]->fin_pcc_id;
			$detailsPayment[$i]->fin_pc_divisi_id =  $detailsPayment[$i]->fin_pc_divisi_id == "" ? NULL : $detailsPayment[$i]->fin_pc_divisi_id;
			$detailsPayment[$i]->fin_pc_customer_id = $detailsPayment[$i]->fin_pc_customer_id ==""  ? NULL : $detailsPayment[$i]->fin_pc_customer_id;
			$detailsPayment[$i]->fin_pc_project_id = $detailsPayment[$i]->fin_pc_project_id == "" ? NULL : $detailsPayment[$i]->fin_pc_project_id;
			$detailsPayment[$i]->fin_relation_id = $detailsPayment[$i]->fin_relation_id == "" ? NULL : $detailsPayment[$i]->fin_relation_id;



			// Validate itemType Details
			if ($detailsPayment[$i]->fst_cbpayment_type == "TUNAI" ||$detailsPayment[$i]->fst_cbpayment_type == "TRANSFER"){
				$acc = $this->kasbank_model->getDataById($dataH["fin_kasbank_id"]);
				$acc = $acc["ms_kasbank"];										
			}else if($detailsPayment[$i]->fst_cbpayment_type == "GIRO"){
				$acc = $this->trcbpayment_model->getOutGiroAccount();
			}else if($detailsPayment[$i]->fst_cbpayment_type == "GLACCOUNT"){
				$acc = (object) ["fst_glaccount_code" => $detailsPayment[$i]->fst_glaccount_code];
				//Check if account id Profit Cost Center, Analisa Divisi, Analisa Customer, Analisa Project
				$glAccount = $this->glaccounts_model->getSimpleDataHeader($detailsPayment[$i]->fst_glaccount_code);

				if ($glAccount->fst_glaccount_type == "PROFIT_LOST"){
					if(empty($detailsPayment[$i]->fin_pcc_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan profit & cost center !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

				if ($glAccount->fbl_pc_divisi){
					if(empty($detailsPayment[$i]->fin_pc_divisi_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan analisa divisi !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

				if ($glAccount->fbl_pc_customer){
					if(empty($detailsPayment[$i]->fin_pc_customer_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan analisa customer !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

				if ($glAccount->fbl_pc_project){
					if(empty($detailsPayment[$i]->fin_pc_project_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan analisa project !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

			}

			if ($acc){
				$detailsPayment[$i]->fst_glaccount_code = $acc->fst_glaccount_code;
			}else{
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Invalid Request");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> "Invalid Kas/Bank ID"
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;
			}

			$this->form_validation->set_data((array)$detailsPayment[$i]);
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

		//Total IDR Harus sama
		if($totalTransaksiIDR != $totalPaymentIDR){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Total transaksi & Pembayaran tidak sama !");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> lang("Total transaksi & Pembayaran tidak sama !"),
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;
		}
		
		//INSERT DATA
		$this->db->trans_start();

		$insertId = $this->trcbpayment_model->insert($dataH);

		//Insert Data Detail Transaksi
		foreach ($detailsTransaksi as $transaksi) {		
			$dataTransaksi = (array) $transaksi;
			$dataTransaksi["fin_cbpayment_id"] = $insertId;
			$dataTransaksi["fst_active"] = "A";					
			$this->trcbpaymentitems_model->insert($dataTransaksi);			
		}

		//Insert Data Detail Payment		
		foreach ($detailsPayment as $transaksi) {
			$dataPayment = (array) $transaksi;
			$dataPayment["fin_cbpayment_id"] = $insertId;
			$dataPayment["fst_active"] = "A";			
			$this->trcbpaymentitemstype_model->insert($dataPayment);
		}

		//POSTING DATA
		$resp = $this->trcbpayment_model->posting($insertId);
		if($resp["status"] != "SUCCESS"){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->ajxResp["data"] = isset($resp["data"]) ? $resp["data"] : null;
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$this->db->trans_complete();

		//OUTPUT SUCCESS
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();		
	}

	public function ajx_edit_save(){
		$this->load->model('trcbpaymentitems_model');
		$this->load->model('trcbpaymentitemstype_model');
		$this->load->model('kasbank_model');
		$this->load->model('glledger_model');


		//IS EDITABLE
		$dataHOld = $this->trcbpayment_model->getDataHeaderById($this->input->post("fin_cbpayment_id"));
		if($dataHOld == null){
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = lang("ID transaksi tidak ditemukan");			
			$this->json_output();
			return;
		}

		$resp = dateIsLock($dataHOld->fdt_cbpayment_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}


		$fdt_cbpayment_datetime = dBDateTimeFormat($this->input->post("fdt_cbpayment_datetime"));				
		$resp = dateIsLock($fdt_cbpayment_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}



		$this->form_validation->set_rules($this->trcbpayment_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Header";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}

		//$fst_cbpayment_no = $this->trcbpayment_model->generateCBPaymentNo($this->input->post("fin_kasbank_id"),$fdt_cbpayment_datetime);
		$fstCurrCode = $this->input->post("fst_curr_code");
		$fdcExchangeRateIdr = parseNumber($this->input->post("fdc_exchange_rate_idr"));
		if($fstCurrCode ==  null){
			$defaultCurr = getDefaultCurrency();
			$fstCurrCode = $defaultCurr["CurrCode"];
			$fdcExchangeRateIdr = 1;
		}
		$dataH = [
			"fin_cbpayment_id"=>$this->input->post("fin_cbpayment_id"),
			"fin_kasbank_id"=>$this->input->post("fin_kasbank_id"),
			"fdt_cbpayment_datetime"=>$fdt_cbpayment_datetime,
			"fin_supplier_id"=>$this->input->post("fin_supplier_id"),
			"fst_curr_code"=>$fstCurrCode,
			"fdc_exchange_rate_idr"=>$fdcExchangeRateIdr,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',	
			"fin_user_id_request_by"=>$this->input->post("fin_user_id_request_by"),
			"fst_edit_notes"=>$this->input->post("fst_edit_notes")		
		];

		$totalTransaksiIDR = 0;
		$totalPaymentIDR = 0;

		$detailsTransaksi = $this->input->post("detailTrans");
		$detailsTransaksi = json_decode($detailsTransaksi);
		$this->form_validation->set_rules($this->trcbpaymentitems_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($detailsTransaksi) ; $i++){
			$item = $detailsTransaksi[$i];
			// Validate item Details
			$this->form_validation->set_data((array)$detailsTransaksi[$i]);
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

		$detailsPayment = $this->input->post("detailPayment");
		$detailsPayment = json_decode($detailsPayment);
		$this->form_validation->set_rules($this->trcbpaymentitemstype_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($detailsPayment) ; $i++){
			$item = $detailsPayment[$i];
			// Validate itemType Details
			$detailsPayment[$i]->fdt_clear_date = dBDateFormat($item->fdt_clear_date);
			$detailsPayment[$i]->fin_pcc_id = $detailsPayment[$i]->fin_pcc_id == "" ? NULL : $detailsPayment[$i]->fin_pcc_id;
			$detailsPayment[$i]->fin_pc_divisi_id =  $detailsPayment[$i]->fin_pc_divisi_id == "" ? NULL : $detailsPayment[$i]->fin_pc_divisi_id;
			$detailsPayment[$i]->fin_pc_customer_id = $detailsPayment[$i]->fin_pc_customer_id ==""  ? NULL : $detailsPayment[$i]->fin_pc_customer_id;
			$detailsPayment[$i]->fin_pc_project_id =$detailsPayment[$i]->fin_pc_project_id == "" ? NULL : $detailsPayment[$i]->fin_pc_project_id;
			$detailsPayment[$i]->fin_relation_id =$detailsPayment[$i]->fin_relation_id == "" ? NULL : $detailsPayment[$i]->fin_relation_id;
			

			if ($detailsPayment[$i]->fst_cbpayment_type == "TUNAI" ||$detailsPayment[$i]->fst_cbpayment_type == "TRANSFER"){
				$acc = $this->kasbank_model->getDataById($dataH["fin_kasbank_id"]);
				$acc = $acc["ms_kasbank"];
											
			}else if($detailsPayment[$i]->fst_cbpayment_type == "GIRO"){
				$acc = $this->trcbpayment_model->getOutGiroAccount();
			}else if($detailsPayment[$i]->fst_cbpayment_type == "GLACCOUNT"){
				$acc = (object) ["fst_glaccount_code" => $detailsPayment[$i]->fst_glaccount_code];

				//Check if account id Profit Cost Center, Analisa Divisi, Analisa Customer, Analisa Project
				$glAccount = $this->glaccounts_model->getSimpleDataHeader($detailsPayment[$i]->fst_glaccount_code);

				if ($glAccount->fst_glaccount_type == "PROFIT_LOST"){
					if(empty($detailsPayment[$i]->fin_pcc_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan profit & cost center !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

				if ($glAccount->fbl_pc_divisi){
					if(empty($detailsPayment[$i]->fin_pc_divisi_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan analisa divisi !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

				if ($glAccount->fbl_pc_customer){
					if(empty($detailsPayment[$i]->fin_pc_customer_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan analisa customer !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}

				if ($glAccount->fbl_pc_project){
					if(empty($detailsPayment[$i]->fin_pc_project_id)){
						$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
						$this->ajxResp["message"] = lang("Invalid Request");
						$this->ajxResp["request_data"] = $dataH;
						$error = [
							"detail"=> sprintf(lang("%s membutuhkan analisa project !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						$this->ajxResp["data"] = $error;
						$this->json_output();
						return;
					}
				}
			}

			if ($acc){
				$detailsPayment[$i]->fst_glaccount_code = $acc->fst_glaccount_code;
			}else{
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Invalid Request");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> "Invalid Kas/Bank ID"
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;
			}


			$this->form_validation->set_data((array)$detailsPayment[$i]);


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

	

		//Total IDR Harus sama
		if($totalTransaksiIDR != $totalPaymentIDR){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Total transaksi & Pembayaran tidak sama !");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> lang("Total transaksi & Pembayaran tidak sama !"),
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;
		}



		$this->db->trans_start();
		$insertId = $dataH["fin_cbpayment_id"];

		$this->trcbpayment_model->unposting($dataH["fin_cbpayment_id"],$dataH["fdt_cbpayment_datetime"]);

		
		$this->trcbpayment_model->update($dataH);

		//Insert Data Detail Transaksi
		foreach ($detailsTransaksi as $transaksi) {		
			$dataTransaksi = (array) $transaksi;
			$dataTransaksi["fin_cbpayment_id"] = $insertId;
			$dataTransaksi["fst_active"] = "A";					
			$this->trcbpaymentitems_model->insert($dataTransaksi);			
		}
		
		
		//Insert Data Detail Payment
		foreach ($detailsPayment as $transaksi) {
			$dataPayment = (array) $transaksi;
			$dataPayment["fin_cbpayment_id"] = $insertId;
			$dataPayment["fst_active"] = "A";			
			$this->trcbpaymentitemstype_model->insert($dataPayment);
		}

		$postingResult = $this->trcbpayment_model->posting($insertId);
		if($postingResult["status"] !== "SUCCESS"){
			$this->ajxResp["status"] = $postingResult["status"];
			$this->ajxResp["message"] = $postingResult["message"];
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}


		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
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

	public function fetch_data($finCBPaymentId){
		$data = $this->trcbpayment_model->getDataById($finCBPaymentId);	
		if ($data == null){
			$data = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$data["status"] = "SUCCESS";
			$data["message"] = "";
		}		
		$this->json_output($data);
	}

	public function delete($finCBPaymentId){
		//IS EDITABLE
		$dataHOld = $this->trcbpayment_model->getDataHeaderById($finCBPaymentId);
		if($dataHOld == null){
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = lang("ID transaksi tidak ditemukan");			
			$this->json_output();
			return;
		}
		$resp = dateIsLock($dataHOld->fdt_cbpayment_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$resp = $this->trcbpayment_model->isEditable($finCBPaymentId);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$this->db->trans_start();
		//UNPOSTING
		$resp = $this->trcbpayment_model->unposting($finCBPaymentId);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		//DELETE RECORD
		$resp = $this->trcbpayment_model->delete($finCBPaymentId);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->json_output();




	}

	public function print_voucher($finCBReceiveId){
		$data = $this->trcbpayment_model->getDataVoucher($finCBReceiveId);
		$data["title"]= "Pengeluaran Kas & Bank";
		$this->data["title"]= $data["title"];				
		$page_content = $this->parser->parse('pages/tr/kas_bank/pengeluaran/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;				
		//$mpdf->SetHTMLFooterByName('MyFooter');
		$mpdf->WriteHTML($data);
		$mpdf->Output();

	}


}    
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan extends MY_Controller{
	public $menuName="penerimaan_kas";
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trcbreceive_model');
	}
	
	public function index(){
		parent::index();
		$this->load->library('menus');
        $this->list['page_name'] = "Cash Bank - Receive";
        $this->list['list_name'] = "Cash & Bank Receive List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/kas_bank/penerimaan/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/kas_bank/penerimaan/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_cbreceive_no' => 'No Penerimaan',
			'fst_customer_name' => 'Customer'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Cash & Bank', 'link' => '#', 'icon' => ''],
            ['title' => 'Receive', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. ', 'width' => '10px','visible'=>'false', 'data' => 'fin_cbreceive_id'],
            ['title' => 'Receive No.', 'width' => '100px', 'data' => 'fst_cbreceive_no'],
            ['title' => 'Tanggal', 'width' => '100px', 'data' => 'fdt_cbreceive_datetime'],
            ['title' => 'Customer', 'width' => '150px', 'data' => 'fst_customer_name'],		
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_memo'],
			['title' => 'Currency', 'width' => '50px', 'data' => 'fst_curr_code'],
			['title' => 'Total Amount', 'width' => '80px', 'data' => 'fdc_total_receive','className'=>'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}",
			],
			['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/kas_bank/penerimaan/edit/' + row.fin_cbreceive_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/kas_bank/penerimaan/listjs', [], true);

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
			SELECT a.fin_cbreceive_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,a.fst_memo,b.fst_relation_name as fst_customer_name,a.fst_curr_code,a.fst_active,sum(c.fdc_receive_amount) as fdc_total_receive FROM trcbreceive a 
			INNER JOIN msrelations b on a.fin_customer_id = b.fin_relation_id 
			INNER JOIN trcbreceiveitems c on a.fin_cbreceive_id = c.fin_cbreceive_id 
			GROUP BY a.fin_cbreceive_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,a.fst_memo,b.fst_relation_name,a.fst_curr_code 
			) a");

        $selectFields = "a.fin_cbreceive_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,a.fst_customer_name,a.fst_memo,a.fst_curr_code,a.fdc_total_receive,a.fst_active";
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
		parent::add();
        $this->openForm("ADD", 0);

	}
	
	public function edit($finCBReceiveId){
		parent::edit($finCBReceiveId);
        $this->openForm("EDIT", $finCBReceiveId);

    }

    private function openForm($mode = "ADD", $finCBReceiveId = 0){
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
        $data["title"] = $mode == "ADD" ? lang("Penerimaan") : lang("Update Penerimaan");
		$data["fin_cbreceive_id"] = $finCBReceiveId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;

		if($mode == 'ADD'){
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
			$data["mdlJurnal"] = $jurnal_modal;
        }        
		
		$page_content = $this->parser->parse('pages/tr/kas_bank/penerimaan/form', $data, true);
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
		parent::ajx_add_save();
		$this->load->model('trcbreceiveitems_model');
		$this->load->model('trcbreceiveitemstype_model');
		$this->load->model('kasbank_model');
		$this->load->model('glaccounts_model');

		try{
			$fdt_cbreceive_datetime = dBDateTimeFormat($this->input->post("fdt_cbreceive_datetime"));
			$resp = dateIsLock($fdt_cbreceive_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}
		
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$detailsTransaksi = $preparedData["detailsTransaksi"];
			$detailsReceive = $preparedData["detailsReceive"];
			
			$this->validationData($dataH,$detailsTransaksi,$detailsReceive);
			
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
		}

		try{
			//INSERT DATA
			$this->db->trans_start();

			$insertId = $this->trcbreceive_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($detailsTransaksi as $transaksi) {		
				$dataTransaksi = (array) $transaksi;
				$dataTransaksi["fin_cbreceive_id"] = $insertId;
				$dataTransaksi["fst_active"] = "A";					
				$this->trcbreceiveitems_model->insert($dataTransaksi);			
			}

			//Insert Data Detail Receive		
			foreach ($detailsReceive as $transaksi) {
				$dataReceive = (array) $transaksi;
				$dataReceive["fin_cbreceive_id"] = $insertId;
				$dataPayment["fst_active"] = "A";			
				$this->trcbreceiveitemstype_model->insert($dataReceive);
			}

			//POSTING DATA
			$this->trcbreceive_model->posting($insertId);

			$this->db->trans_complete();

			//OUTPUT SUCCESS
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
		}
	}

	public function ajx_edit_save(){
		parent::ajx_edit_save();
		$this->load->model('trcbreceiveitems_model');
		$this->load->model('trcbreceiveitemstype_model');
		$this->load->model('kasbank_model');
		$this->load->model('glledger_model');
		
		

		$finCBReceiveId = $this->input->post("fin_cbreceive_id");

		try{
			//IS EDITABLE			
			$dataHOld = $this->trcbreceive_model->getDataHeaderById($finCBReceiveId);
			if($dataHOld == null){
				throw new CustomException(lang("ID Penerimaan kas bank tidak dikenal !"),9009,"FAILED",["fin_cbreceive_id"=>$finCBReceiveId]);
			}

			$resp = dateIsLock($dataHOld->fdt_cbreceive_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$fdt_cbreceive_datetime = dBDateTimeFormat($this->input->post("fdt_cbreceive_datetime"));				
			$resp = dateIsLock($fdt_cbreceive_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$resp = $this->trcbreceive_model->isEditable($finCBReceiveId);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}


		try{
			$this->db->trans_start();

			//UNPOSTING
			$this->trcbreceive_model->unposting($finCBReceiveId);

			//DELETE DETAIL
			$this->trcbreceive_model->deleteDetail($finCBReceiveId);

			//PREPARE DATA
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$detailsTransaksi = $preparedData["detailsTransaksi"];
			$detailsReceive = $preparedData["detailsReceive"];

			
			$dataH["fin_cbreceive_id"] = $finCBReceiveId;
			$dataH["fst_cbreceive_no"] = $dataHOld->fst_cbreceive_no;
			
			$this->validationData($dataH,$detailsTransaksi,$detailsReceive);

			//UPDATE HEADER
			$insertId = $finCBReceiveId;
			$this->trcbreceive_model->update($dataH);

			//INSERT DETAIL
			//Insert Data Detail Transaksi
			foreach ($detailsTransaksi as $transaksi) {
				$dataTransaksi = (array) $transaksi;
				$dataTransaksi["fin_cbreceive_id"] = $insertId;
				$dataTransaksi["fst_active"] = "A";					
				$this->trcbreceiveitems_model->insert($dataTransaksi);			
			}
			//Insert Data Detail Payment
			foreach ($detailsReceive as $transaksi) {
				$dataReceive = (array) $transaksi;
				$dataReceive["fin_cbreceive_id"] = $insertId;
				$dataPayment["fst_active"] = "A";			
				$this->trcbreceiveitemstype_model->insert($dataReceive);
			}

			//POSTING
			$this->trcbreceive_model->posting($insertId);			
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


	private function prepareData(){
		//PREPARE DATA
		$fdt_cbreceive_datetime = dBDateTimeFormat($this->input->post("fdt_cbreceive_datetime"));				
		$fst_cbreceive_no = $this->trcbreceive_model->generateCBReceiveNo($this->input->post("fin_kasbank_id"),$fdt_cbreceive_datetime);

		$fstCurrCode = $this->input->post("fst_curr_code");
		$fdcExchangeRateIdr = parseNumber($this->input->post("fdc_exchange_rate_idr"));
		if($fstCurrCode ==  null){
			$defaultCurr = getDefaultCurrency();
			$fstCurrCode = $defaultCurr["CurrCode"];
			$fdcExchangeRateIdr = 1;
		}

		$dataH = [
			//"fin_cbpayment_id"=>
			"fst_cbreceive_no"=>$fst_cbreceive_no,
			"fin_kasbank_id"=>$this->input->post("fin_kasbank_id"),
			"fdt_cbreceive_datetime"=>$fdt_cbreceive_datetime,
			"fin_customer_id"=>$this->input->post("fin_customer_id"),
			"fst_curr_code"=>$fstCurrCode,
			"fdc_exchange_rate_idr"=>$fdcExchangeRateIdr,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',			
		];

		$totalTransaksiIDR = 0;
		$totalReceiveIDR = 0;

		$detailsTransaksi = $this->input->post("detailTrans");
		$detailsTransaksi = json_decode($detailsTransaksi);

		$detailsReceive = $this->input->post("detailReceive");
		$detailsReceive = json_decode($detailsReceive);
		
		for($i = 0; $i < sizeof($detailsReceive) ; $i++){

			$detailsReceive[$i]->fin_pcc_id =  $detailsReceive[$i]->fin_pcc_id == "" ? null : $detailsReceive[$i]->fin_pcc_id;
			$detailsReceive[$i]->fin_pc_divisi_id =  $detailsReceive[$i]->fin_pc_divisi_id == "" ? null : $detailsReceive[$i]->fin_pc_divisi_id;
			$detailsReceive[$i]->fin_pc_customer_id =  $detailsReceive[$i]->fin_pc_customer_id == "" ? null : $detailsReceive[$i]->fin_pc_customer_id;
			$detailsReceive[$i]->fin_pc_project_id =  $detailsReceive[$i]->fin_pc_project_id == "" ? null : $detailsReceive[$i]->fin_pc_project_id;
			$detailsReceive[$i]->fin_relation_id =  $detailsReceive[$i]->fin_relation_id == "" ? null : $detailsReceive[$i]->fin_relation_id;


			$detailsReceive[$i]->fdt_clear_date = dBDateFormat($detailsReceive[$i]->fdt_clear_date);			

			// Validate itemType Details
			if ($detailsReceive[$i]->fst_cbreceive_type == "TUNAI" ||$detailsReceive[$i]->fst_cbreceive_type == "TRANSFER"){
				$acc = $this->kasbank_model->getDataById($dataH["fin_kasbank_id"]);
				$acc = $acc["ms_kasbank"];										
			}else if($detailsReceive[$i]->fst_cbreceive_type == "GIRO"){
				$acc = $this->trcbreceive_model->getInGiroAccount();
			}else if($detailsReceive[$i]->fst_cbreceive_type == "GLACCOUNT"){
				$acc = (object) ["fst_glaccount_code" => $detailsReceive[$i]->fst_glaccount_code];
			}
			if ($acc){
				$detailsReceive[$i]->fst_glaccount_code = $acc->fst_glaccount_code;
			}else{
				throw new CustomException(lang("Type penerimaan tidak valid !"),9009,"FAILED",["detail"=> "Invalid Kas/Bank ID"]);
			}
		}
		
		return [
			"dataH"=>$dataH,
			"detailsTransaksi"=>$detailsTransaksi,
			"detailsReceive"=>$detailsReceive
		];

	}

	private function validationData($dataH,$detailsTransaksi,$detailsReceive){
		//VALIDASI DATA
		$this->form_validation->set_rules($this->trcbreceive_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		if  ( sizeof($detailsTransaksi) == 0  && sizeof($detailsReceive) == 0 ){
			throw new CustomException(lang("Tidak ada transaksi !"),3003,"FAILED",null);			
		}

		$totalTransaksi= 0;
		$totalReceive = 0;

		$this->form_validation->set_rules($this->trcbreceiveitems_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($detailsTransaksi) ; $i++){
			$totalTransaksi += $detailsTransaksi[$i]->fdc_receive_amount;

			// Validate item Details
			$this->form_validation->set_data((array)$detailsTransaksi[$i]);
			if ($this->form_validation->run() == FALSE){				
				throw new CustomException("Error Validation Forms Detail ",3003,"VALIDATION_FORM_FAILED",["detail_trans"=> $this->form_validation->error_string()]);			
			}
		}

		$this->form_validation->set_rules($this->trcbreceiveitemstype_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($detailsReceive) ; $i++){
			//$item = $detailsReceive[$i];
			$totalReceive += $detailsReceive[$i]->fdc_amount;

			if($detailsReceive[$i]->fst_cbreceive_type == "GLACCOUNT"){
				//$acc = (object) ["fst_glaccount_code" => $detailsReceive[$i]->fst_glaccount_code]
				//Check if account id Profit Cost Center, Analisa Divisi, Analisa Customer, Analisa Project
				$glAccount = $this->glaccounts_model->getSimpleDataHeader($detailsReceive[$i]->fst_glaccount_code);

				if ($glAccount->fst_glaccount_type == "PROFIT_LOST"){
					if(empty($detailsReceive[$i]->fin_pcc_id)){
						$error = [
							"detail_receive"=> sprintf(lang("%s membutuhkan profit & cost center !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						throw new CustomException("",3003,"VALIDATION_FORM_FAILED",$error);						
					}
				}

				if ($glAccount->fbl_pc_divisi){
					if(empty($detailsReceive[$i]->fin_pc_divisi_id)){
						$error = [
							"detail_receive"=> sprintf(lang("%s membutuhkan analisa divisi !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						throw new CustomException("",3003,"VALIDATION_FORM_FAILED",$error);						
					}
				}

				if ($glAccount->fbl_pc_customer){
					if(empty($detailsReceive[$i]->fin_pc_customer_id)){
						$error = [
							"detail_receive"=> sprintf(lang("%s membutuhkan analisa customer !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						throw new CustomException("",3003,"VALIDATION_FORM_FAILED",$error);						
					}
				}

				if ($glAccount->fbl_pc_project){
					if(empty($detailsReceive[$i]->fin_pc_project_id)){
						$error = [
							"detail_receive"=> sprintf(lang("%s membutuhkan analisa project !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						throw new CustomException("",3003,"VALIDATION_FORM_FAILED",$error);
					}
				}

				if ($glAccount->fbl_controll_card_relation){
					if(empty($detailsReceive[$i]->fin_relation_id)){
						$error = [
							"detail_receive"=> sprintf(lang("%s membutuhkan data relasi !"),$glAccount->fst_glaccount_code . " - " .$glAccount->fst_glaccount_name)
						];
						throw new CustomException("",3003,"VALIDATION_FORM_FAILED",$error);
					}
				}


			}			

			$this->form_validation->set_data((array)$detailsReceive[$i]);
			if ($this->form_validation->run() == FALSE){
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				throw new CustomException(lang("Error Validation Detail Receive"),3003,"VALIDATION_FORM_FAILED",$error);
			}
		}

		//Total IDR Harus sama
		if($totalTransaksi != $totalReceive){
			$error = [
				"detail_trans"=> lang("Total transaksi & Penerimaan tidak sama !"),
				"detail_receive"=> lang("Total transaksi & Penerimaan tidak sama !"),
			];			
			throw new CustomException(lang("Total transaksi & Penerimaan tidak sama !"),3003,"VALIDATION_FORM_FAILED",$error);
			
		}
	}

	public function fetch_data($finCBReceiveId){
		$data = $this->trcbreceive_model->getDataById($finCBReceiveId);	
		if ($data == null){
			$data = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$data["status"] = "SUCCESS";
			$data["message"] = "";
		}		
		$this->json_output($data);
	}

	public function delete($finCBReceiveId){
		parent::delete($finCBReceiveId);
		$this->load->model("trcbreceiveitems_model");
		$this->load->model("trcbreceiveitemstype_model");
	
		//IS EDITABLE
		$dataHOld = $this->trcbreceive_model->getDataHeaderById($finCBReceiveId);
		if($dataHOld == null){
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = lang("ID transaksi tidak ditemukan");			
			$this->json_output();
			return;
		}
		$resp = dateIsLock($dataHOld->fdt_cbreceive_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$resp = $this->trcbreceive_model->isEditable($finCBReceiveId);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}
		
		try{
			$this->db->trans_start();
			//UNPOSTING
			$this->trcbreceive_model->unposting($finCBReceiveId);
			
			//DELETE RECORD
			$this->trcbreceive_model->delete($finCBReceiveId);
			//$this->trcbreceiveitems_model->delete($finCBReceiveId);
			//$this->trcbreceiveitemstype_model->delete($finCBReceiveId);
			
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

	
	public function print_voucher($finCBReceiveId){
		$data = $this->trcbreceive_model->getDataVoucher($finCBReceiveId);
		$data["title"]= "Penerimaan Kas & Bank";
		$this->data["title"]= $data["title"];				
		$page_content = $this->parser->parse('pages/tr/kas_bank/penerimaan/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;				
		//$mpdf->SetHTMLHeaderByName('MyFooter');
		$mpdf->WriteHTML($data);
		$mpdf->Output();

	}


}    
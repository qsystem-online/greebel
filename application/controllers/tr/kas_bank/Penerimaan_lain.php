<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_lain extends MY_Controller{
	public $menuName="penerimaan_kas_lain";
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trcbreceiveother_model');
		$this->load->model('trcbreceiveotheritems_model');
		$this->load->model('mscurrencies_model');
		$this->load->model("profitcostcenter_model");
        $this->load->model("msdepartments_model");
        $this->load->model("msrelations_model");
		$this->load->model("msprojects_model");
		$this->load->model("glaccounts_model");    
		$this->load->model('kasbank_model');    
		
	}
	
	public function index(){
		parent::index();
		$this->load->library('menus');
        $this->list['page_name'] = "Cash Bank - Receive Other";
        $this->list['list_name'] = "Cash & Bank Receive Other List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/kas_bank/penerimaan_lain/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/kas_bank/penerimaan_lain/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_cbreceiveother_no' => 'No Penerimaan',
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Cash & Bank', 'link' => '#', 'icon' => ''],
            ['title' => 'Receive', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. ', 'width' => '10px','visible'=>'false', 'data' => 'fin_cbreceiveoth_id'],
            ['title' => 'Receive No.', 'width' => '120px', 'data' => 'fst_cbreceiveoth_no'],
            ['title' => 'Tanggal', 'width' => '80px', 'data' => 'fdt_cbreceiveoth_datetime'],
            ['title' => 'Terima Dari', 'width' => '150px', 'data' => 'fst_receive_from'],		
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_memo'],
			['title' => 'Currency', 'width' => '50px', 'data' => 'fst_curr_code'],
			['title' => 'Nominal', 'width' => '80px', 'data' => 'fdc_nominal','className'=>'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}",
			],
			['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/kas_bank/penerimaan_lain/edit/' + row.fin_cbreceiveoth_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/kas_bank/penerimaan_lain/listjs', [], true);

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
			SELECT a.fin_cbreceiveoth_id,a.fst_cbreceiveoth_no,a.fdt_cbreceiveoth_datetime,a.fst_receive_from,a.fst_memo,a.fst_curr_code,a.fst_active,fdc_nominal
			FROM trcbreceiveother a) a");

        $selectFields = "a.fin_cbreceiveoth_id,a.fst_cbreceiveoth_no,a.fdt_cbreceiveoth_datetime,a.fst_receive_from,a.fst_memo,a.fst_curr_code,a.fdc_nominal,a.fst_active";
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

    private function openForm($mode = "ADD", $finCBReceiveOthId = 0){
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
        $data["title"] = $mode == "ADD" ? lang("Penerimaan Lain") : lang("Update Penerimaan Lain");
		$data["fin_cbreceiveoth_id"] = $finCBReceiveOthId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;

		
		if($mode == 'ADD'){
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
			$data["mdlJurnal"] = $jurnal_modal;
        }        
		
		$page_content = $this->parser->parse('pages/tr/kas_bank/penerimaan_lain/form', $data, true);
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
		try{
			$fdt_cbreceiveoth_datetime = dBDateTimeFormat($this->input->post("fdt_cbreceiveoth_datetime"));
			$resp = dateIsLock($fdt_cbreceiveoth_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}
		
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataDetails = $preparedData["dataDetails"];			

			$this->validationData($dataH,$dataDetails);
			
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}
		
		try{
			//INSERT DATA
			$this->db->trans_start();

			$insertId = $this->trcbreceiveother_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($dataDetails as $dataD) {		
				$dataD = (array) $dataD;
				$dataD["fin_cbreceiveoth_id"] = $insertId;
				$dataD["fst_active"] = "A";					
				$this->trcbreceiveotheritems_model->insert($dataD);			
			}

			
			//POSTING DATA
			$this->trcbreceiveother_model->posting($insertId);

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
			return;
		}
	}

	public function ajx_edit_save(){
		parent::ajx_edit_save();
		$this->load->model('trcbreceiveitems_model');
		$this->load->model('trcbreceiveitemstype_model');
		$this->load->model('kasbank_model');
		$this->load->model('glledger_model');
		
		$finCBReceiveOthId = $this->input->post("fin_cbreceiveoth_id");

		try{
			//IS EDITABLE			
			$dataHOld = $this->trcbreceiveother_model->getDataHeaderById($finCBReceiveOthId);
			if($dataHOld == null){
				throw new CustomException(lang("ID Penerimaan kas bank tidak dikenal !"),9009,"FAILED",["fin_cbreceive_id"=>$finCBReceiveId]);
			}

			$resp = dateIsLock($dataHOld->fdt_cbreceiveoth_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$fdt_cbreceiveoth_datetime = dBDateTimeFormat($this->input->post("fdt_cbreceiveoth_datetime"));				
			$resp = dateIsLock($fdt_cbreceiveoth_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$resp = $this->trcbreceiveother_model->isEditable($finCBReceiveOthId);
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
			$this->trcbreceiveother_model->unposting($finCBReceiveOthId);

			//DELETE DETAIL
			$this->trcbreceiveother_model->deleteDetail($finCBReceiveOthId);

			//PREPARE DATA
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataDetails = $preparedData["dataDetails"];
					
			$dataH["fin_cbreceiveoth_id"] = $finCBReceiveOthId;
			$dataH["fst_cbreceiveoth_no"] = $dataHOld->fst_cbreceiveoth_no;
			
			$this->validationData($dataH,$dataDetails);

			//UPDATE HEADER
			$insertId = $finCBReceiveOthId;
			$this->trcbreceiveother_model->update($dataH);

			//INSERT DETAIL
			//Insert Data Detail Transaksi
			foreach ($dataDetails as $dataD) {
				$dataD = (array) $dataD;
				$dataD["fin_cbreceiveoth_id"] = $insertId;
				$dataD["fst_active"] = "A";					
				$this->trcbreceiveotheritems_model->insert($dataD);
			}
			

			//POSTING
			$this->trcbreceiveother_model->posting($insertId);			
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
		$fdt_cbreceiveoth_datetime = dBDateTimeFormat($this->input->post("fdt_cbreceiveoth_datetime"));				
		$fst_cbreceiveoth_no = $this->trcbreceiveother_model->generateCBNo($this->input->post("fin_kasbank_id"),$fdt_cbreceiveoth_datetime);

		$fstCurrCode = $this->input->post("fst_curr_code");
		$fdcExchangeRateIdr = parseNumber($this->input->post("fdc_exchange_rate_idr"));
		if($fstCurrCode ==  null){
			$defaultCurr = getDefaultCurrency();
			$fstCurrCode = $defaultCurr["CurrCode"];
			$fdcExchangeRateIdr = 1;
		}

		$fdcNominal = parseNumber($this->input->post("fdc_nominal"));
		$fdcCashTransfer = parseNumber($this->input->post("fdc_cash_transfer"));
		$fdcBilyet = parseNumber($this->input->post("fdc_bilyet"));
		$fdtClearDate = $this->input->post("fdt_clear_date") == "" ? null : dBDateFormat($this->input->post("fdt_clear_date"));

		$dataH = [
			"fst_cbreceiveoth_type" =>1, 
			"fst_cbreceiveoth_no"=>$fst_cbreceiveoth_no, 
			"fdt_cbreceiveoth_datetime"=>$fdt_cbreceiveoth_datetime,
			"fin_kasbank_id"=>$this->input->post("fin_kasbank_id"),
			"fst_receive_from"=>$this->input->post("fst_receive_from"),
			"fst_curr_code"=>$fstCurrCode,
			"fdc_exchange_rate_idr"=>$fdcExchangeRateIdr,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fdc_nominal"=>$fdcNominal,
			"fdc_cash_transfer"=>$fdcCashTransfer,
			"fdc_bilyet"=>$fdcBilyet,
			"fst_bilyet_no"=>$this->input->post("fst_bilyet_no"),
			"fdt_clear_date"=>$fdtClearDate,
			"fst_active"=>"A"
		];				
		
		$details = $this->input->post("details");
		$dataDetails = json_decode($details);						
		return [
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails,			
		];

	}

	private function validationData($dataH,$dataDetails){
		//VALIDASI DATA
		$this->form_validation->set_rules($this->trcbreceiveother_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		if  ( sizeof($dataDetails) == 0 ){
			throw new CustomException(lang("Tidak ada transaksi !"),3003,"FAILED",null);			
		}		

		$this->form_validation->set_rules($this->trcbreceiveotheritems_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($dataDetails) ; $i++){	
			$dataD = (array)$dataDetails[$i];
			$this->form_validation->set_data($dataD);
			
			//cek pcc, analysys, account kartu hutang piutang
			$acc = $this->glaccounts_model->getSimpleDataHeader($dataD["fst_glaccount_code"]);
			if ($acc->fst_glaccount_type == "PROFIT_LOST"){
				$acc->fbl_pcc = true;
			}else{
				$acc->fbl_pcc = false;
			}

			if($acc->fbl_pcc){
				if ($dataD["fin_pcc_id"] == null){
					throw new CustomException(sprintf(lang("%s membutuhkan data profit/cost center"),$acc->fst_glaccount_code . " - " .$acc->fst_glaccount_name),3003,"FAILED",null);
				}
			}
			if($acc->fbl_pc_divisi){
				if ($dataD["fin_pc_divisi_id"] == null){
					throw new CustomException(sprintf(lang("%s membutuhkan analisa divisi"),$acc->fst_glaccount_code . " - " .$acc->fst_glaccount_name),3003,"FAILED",null);
				}
			}
			if($acc->fbl_pc_customer){
				if ($dataD["fin_pc_customer_id"] == null){
					throw new CustomException(sprintf(lang("%s membutuhkan analisa customer"),$acc->fst_glaccount_code . " - " .$acc->fst_glaccount_name),3003,"FAILED",null);
				}
			}
			if($acc->fbl_pc_project){
				if ($dataD["fin_pc_project_id"] == null){
					throw new CustomException(sprintf(lang("%s membutuhkan analisa project"),$acc->fst_glaccount_code . " - " .$acc->fst_glaccount_name),3003,"FAILED",null);
				}
			}
			if($acc->fbl_controll_card_relation){
				if ($dataD["fin_relation_id"] == null){
					throw new CustomException(sprintf(lang("%s membutuhkan kontrol kartu hutang/piutang"),$acc->fst_glaccount_code . " - " .$acc->fst_glaccount_name),3003,"FAILED",null);
				}
			}



			if ($this->form_validation->run() == FALSE){
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				throw new CustomException(lang("Error Validation Detail Receive"),3003,"VALIDATION_FORM_FAILED",$error);
			}
		}

		//total nominal hars sama dengan total cash/transfer + total cheque/giro
		if($dataH["fdc_nominal"] != $dataH["fdc_cash_transfer"] + $dataH["fdc_bilyet"]){			
			throw new CustomException(lang("Total cash/transfer & total cheque/giro harus sesuai dengan jumlah nominal !"),3003,"FAILED",null);			
		}

		//total Credit - debit harus sama dengan Nominal
		$ttlDebit = 0;
		$ttlCredit =0 ;
		foreach($dataDetails as $dataD){
			$dataD = (array) $dataD;
			$ttlDebit += floatval($dataD["fdc_debit"]);
			$ttlCredit += floatval($dataD["fdc_credit"]);
		}
		$ttl = $ttlCredit - $ttlDebit;
		if ($dataH["fdc_nominal"] != $ttl ){
			throw new CustomException(lang("Total detail harus sesuai dengan jumlah nominal !"),3003,"FAILED",null);			
		}

		if ($dataH["fdc_bilyet"] > 0){
			if ($dataH["fst_bilyet_no"] == "" ){
				throw new CustomException(lang("No Cheque / Giro tidak boleh kosong"),3003,"FAILED",null);
			}
			if ($dataH["fdt_clear_date"] == null ){
				throw new CustomException(lang("tgl pencairan Cheque / Giro tidak boleh kosong"),3003,"FAILED",null);
			}

		}

	}

	public function fetch_data($finCBReceiveOthId){
		$data = $this->trcbreceiveother_model->getDataById($finCBReceiveOthId);	
		if ($data == null){
			$data = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$data["status"] = "SUCCESS";
			$data["message"] = "";
		}		
		$this->json_output($data);
	}

	public function delete($finCBReceiveOthId){
		parent::delete($finCBReceiveOthId);
		//$this->load->model("trcbreceiveitems_model");		
	
		//IS EDITABLE
		
		
		try{
			$dataHOld = $this->trcbreceiveother_model->getDataHeaderById($finCBReceiveOthId);
			if($dataHOld == null){
				throw new CustomException(lang("ID transaksi tidak ditemukan"),3003,"FAILED",null);				
			}
			$resp = dateIsLock($dataHOld->fdt_cbreceiveoth_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$resp = $this->trcbreceiveother_model->isEditable($finCBReceiveOthId);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			
			$this->db->trans_start();
			//UNPOSTING
			$this->trcbreceiveother_model->unposting($finCBReceiveOthId);
			
			//DELETE RECORD
			$this->trcbreceiveother_model->delete($finCBReceiveOthId);
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

	public function print_voucher($finCBReceiveOthId){
		$data = $this->trcbreceiveother_model->getDataVoucher($finCBReceiveOthId);
		$data["title"]= "Penerimaan Lain-lain Kas & Bank";
		$this->data["title"]= $data["title"];				
		$page_content = $this->parser->parse('pages/tr/kas_bank/penerimaan_lain/voucher', $data, true);
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
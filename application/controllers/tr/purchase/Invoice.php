<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trlpbgudang_model');
		$this->load->model('trlpbpurchase_model');
		$this->load->model('mscurrencies_model');
		$this->load->model('trpo_model');
		
    }

	public function index(){

		$this->load->library('menus');
        $this->list['page_name'] = "Purchase - Invoice";
        $this->list['list_name'] = "Invoice Pembelian List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/purchase/invoice/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase/invoice/fetch_list_data';
        $this->list['arrSearch'] = [
            'fst_lpbpurchase_no' => 'No LPB Pembelian'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Invoice', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. LPB Pembelian', 'width' => '10px','visible'=>'false', 'data' => 'fin_lpbpurchase_id'],
            ['title' => 'No. LPB Pembelian', 'width' => '100px', 'data' => 'fst_lpbpurchase_no'],
            ['title' => 'Tanggal', 'width' => '100px', 'data' => 'fdt_lpbpurchase_datetime'],
            ['title' => 'Purchase Order No.', 'width' => '100px', 'data' => 'fst_po_no'],
			['title' => 'Supplier', 'width' => '100px', 'data' => 'fst_supplier_name'],
			['title' => 'Memo', 'width' => '200px', 'data' => 'fst_memo'],
			['title' => 'Action', 'width' => '100px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/purchase/invoice/edit/' + row.fin_lpbpurchase_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase/invoice/listjs', [], true);

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
			SELECT a.*,b.fst_po_no,c.fst_relation_name as fst_supplier_name FROM trlpbpurchase a 
			INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
			INNER JOIN msrelations c on b.fin_supplier_id = c.fin_relation_id 
			) a");

        $selectFields = "a.fin_lpbpurchase_id,a.fst_lpbpurchase_no,a.fdt_lpbpurchase_datetime,a.fst_po_no,a.fst_supplier_name,a.fst_memo";
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
	
	public function edit($finLPBPurchaseId){
        $this->openForm("EDIT", $finLPBPurchaseId);

    }


    private function openForm($mode = "ADD", $finLPBPurchaseId = 0){
        $this->load->library("menus");		
        //$this->load->model("glaccounts_model");		

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Faktur Pembelian") : lang("Update Faktur Pembelian");
		$data["fin_lpbpurchase_id"] = $finLPBPurchaseId;
		$data["mdlEditForm"] = $edit_modal;
		$data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();
		
		
		if($mode == 'ADD'){
			$data["fst_lpbpurchase_no"]=$this->trlpbpurchase_model->generateLPBPurchaseNo(); 
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$data["fst_lpbpurchase_no"]="";	
			$data["mdlJurnal"] = $jurnal_modal;

			/*
			$cbPayment = $this->trcbpayment_model->getDataById($finCBPaymentId);	
			
			$data["initData"] = $this->trcbpayment_model->getDataById($finCBPaymentId);	
			if ($cbPayment == null){
				show_404();
			}		
			$data["initData"] = $cbPayment;
			*/
        }        
		
		$page_content = $this->parser->parse('pages/tr/purchase/invoice/form', $data, true);
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
		$this->load->model("trlpbpurchaseitems_model");
		/*
		__c9da2c3066cf64f25a59677d1666d7ac: 6def1b1f8e380109d34d980906d3fe73
		fin_lpbpurchase_id: 0
		fst_lpbpurchase_no: FB/JKT/2019/10/00001
		fdt_lpbpurchase_datetime: 15-10-2019 18:32:52
		fin_po_id: 6
		fin_lpbgudang_id[]: 10
		fin_lpbgudang_id[]: 11
		fst_memo:
		*/
		


		$this->form_validation->set_rules($this->trlpbpurchase_model->getRules("ADD", 0));

		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data"); ;
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}
		
		
		$fdt_lpbpurchase_datetime = dBDateTimeFormat($this->input->post("fdt_lpbpurchase_datetime"));
		$resp = dateIsLock($fdt_lpbpurchase_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$fst_lpbpurchase_no = $this->trlpbpurchase_model->generateLPBPurchaseNo($fdt_lpbpurchase_datetime);


		$tmp = $this->trpo_model->getDataById($this->input->post("fin_po_id"));
		$po = $tmp["po"];
		if($po == null){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Invalid PO Number";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}
		
		//CEK APAKAH OVER CLAIM DP
		$dpClaim = $this->input->post("fdc_downpayment_claim");
		$availClaimDp = $po->fdc_downpayment_paid - $po->fdc_downpayment_claimed;
		
		if($dpClaim > $availClaimDp ){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data");
			$this->ajxResp["data"] = ["fdc_downpayment_claim"=>"Klaim DP tidak boleh melebihi " . $availClaimDp];
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}

		$dataH = [			
			"fst_lpbpurchase_no"=>$fst_lpbpurchase_no,
			"fdt_lpbpurchase_datetime"=>$fdt_lpbpurchase_datetime,
			"fin_po_id"=>$this->input->post("fin_po_id"),
			"fin_supplier_id"=>$po->fin_supplier_id,
			"fin_term"=>$po->fin_term,
			"fst_curr_code"=> $po->fst_curr_code,
			"fdc_exchange_rate_idr"=>$this->input->post("fdc_exchange_rate_idr"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_ppn_percent"=>$po->fdc_ppn_percent,
			"fdc_ppn_amount"=>0,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fdc_downpayment_claim"=>$dpClaim,
			"fdc_total"=>0,
			"fst_active"=>"A",
			"fin_branch_id"=>$this->aauth->get_active_branch_id()
		];

		$details =  $this->trlpbpurchase_model->getListItemByLPBGudangIds($this->input->post("fin_lpbgudang_id"));
		$subTotal =0;
		$ttlDisc =0;
		$ttlAfterDisc = 0;
		$ttlPpnAmount = 0;
		$total =0;

		foreach($details as $detail){
			$qty = $detail->fdb_qty_total;
			$price = $detail->fdc_price;
			$discItem =$detail->fst_disc_item;
			$discAmount =  calculateDisc($discItem , $qty * $price);
			$subTotal += ($qty * $price);
			$ttlDisc += $discAmount;
		}
		$ttlAfterDisc = $subTotal - $ttlDisc;
		$ttlPpnAmount = $ttlAfterDisc * ($dataH["fdc_ppn_percent"] /100);
		$total = $ttlAfterDisc + $ttlPpnAmount;

		$dataH["fdc_subttl"]=$subTotal;
		$dataH["fdc_disc_amount"]=$ttlDisc;
		$dataH["fdc_ppn_amount"]=$ttlPpnAmount;
		$dataH["fdc_total"]=$dataH["fdc_subttl"] + $dataH["fdc_ppn_amount"] - $dataH["fdc_disc_amount"] - $dataH["fdc_downpayment_claim"];

	
		try{
			$this->db->trans_start();

			$insertId = $this->trlpbpurchase_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($this->input->post("fin_lpbgudang_id") as $finLPBGudangId) {		
				
				$dataD =[
					"fin_lpbpurchase_id"=>$insertId,
					"fin_lpbgudang_id"=>$finLPBGudangId,
					"fst_active"=> "A"
				];
				$this->trlpbpurchaseitems_model->insert($dataD);			
			}
			
			$result = $this->trlpbpurchase_model->posting($insertId);

			if ($result["status"] != "SUCCESS"){
				$this->ajxResp["status"] = "FAILED";
				$this->ajxResp["message"] = $result["message"];
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}

			$this->db->trans_complete();
		}catch(Exception $e){
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = $e->message;
			$this->ajxResp["data"] = $e;
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();

	}

	public function ajx_edit_save(){
		$this->load->model("trlpbpurchaseitems_model");	
		$tmp = $this->trlpbpurchase_model->getDataById($this->input->post("fin_lpbpurchase_id"));
		$dataHOld = $tmp["lpbPurchase"];
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_lpbpurchase_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		//CEK tgl lock dari transaksi yg di kirim
		$fdt_lpbpurchase_datetime = dBDateTimeFormat($this->input->post("fdt_lpbpurchase_datetime"));		
		$resp = dateIsLock($fdt_lpbpurchase_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$resp = $this->trlpbpurchase_model->isEditable($dataHOld->fin_lpbpurchase_id);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}



		$this->db->trans_start();

		//UNPOSTING
		$resp = $this->trlpbpurchase_model->unposting($dataHOld->fin_lpbpurchase_id);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			$this->db->trans_rollback();			
			return;
		}



		//PREPARE DATA		
		$tmp = $this->trpo_model->getDataById($this->input->post("fin_po_id"));				
		$po = $tmp["po"];
		if($po == null){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Invalid PO Number";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->db->trans_rollback();
			$this->json_output();
			return;
		}

		$dataH = [			
			"fin_lpbpurchase_id"=>$dataHOld->fin_lpbpurchase_id,
			"fst_lpbpurchase_no"=>$dataHOld->fst_lpbpurchase_no,
			"fdt_lpbpurchase_datetime"=>$fdt_lpbpurchase_datetime,
			"fin_po_id"=>$this->input->post("fin_po_id"),
			"fin_supplier_id"=>$po->fin_supplier_id,
			"fin_term"=>$po->fin_term,
			"fst_curr_code"=> $po->fst_curr_code,
			"fdc_exchange_rate_idr"=>$this->input->post("fdc_exchange_rate_idr"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_ppn_percent"=>$po->fdc_ppn_percent,
			"fdc_ppn_amount"=>0,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fdc_downpayment_claim"=>$this->input->post("fdc_downpayment_claim"),
			"fdc_total"=>0,
			"fst_active"=>"A",
			"fin_branch_id"=>$this->aauth->get_active_branch_id()
		];
		
		$details =  $this->trlpbpurchase_model->getListItemByLPBGudangIds($this->input->post("fin_lpbgudang_id"));
		$subTotal =0;
		$ttlDisc =0;
		$ttlAfterDisc = 0;
		$ttlPpnAmount = 0;
		$total =0;

		foreach($details as $detail){
			$qty = $detail->fdb_qty_total;
			$price = $detail->fdc_price;
			$discItem =$detail->fst_disc_item;
			$discAmount =  calculateDisc($discItem , $qty * $price);
			$subTotal += ($qty * $price);
			$ttlDisc += $discAmount;
		}
		$ttlAfterDisc = $subTotal - $ttlDisc;
		$ttlPpnAmount = $ttlAfterDisc * ($dataH["fdc_ppn_percent"] /100);
		$total = $ttlAfterDisc + $ttlPpnAmount;

		$dataH["fdc_subttl"]=$subTotal;
		$dataH["fdc_disc_amount"]=$ttlDisc;
		$dataH["fdc_ppn_amount"]=$ttlPpnAmount;
		$dataH["fdc_total"]=$dataH["fdc_subttl"] + $dataH["fdc_ppn_amount"] - $dataH["fdc_disc_amount"] - $dataH["fdc_downpayment_claim"];

		//VALIDATION
		$this->form_validation->set_rules($this->trlpbpurchase_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data"); ;
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->db->trans_rollback();
			$this->json_output();
			return;
		}

		//CEK APAKAH OVER CLAIM DP
		$dpClaim = floatval($this->input->post("fdc_downpayment_claim"));
		$availClaimDp = floatval($po->fdc_downpayment_paid) - floatval($po->fdc_downpayment_claimed);
		if($dpClaim > $availClaimDp ){
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data");
			$this->ajxResp["data"] = ["fdc_downpayment_claim"=>"Klaim DP tidak boleh melebihi " . $availClaimDp];
			$this->ajxResp["request_data"] = $_POST;
			$this->db->trans_rollback();
			$this->json_output();
			return;
		}

		//UPDATING (DELETE & SAVE) HEADER & DETAILS
		$this->trlpbpurchase_model->update($dataH);

		//Insert Data Detail Transaksi		
		foreach ($this->input->post("fin_lpbgudang_id") as $finLPBGudangId) {					
			$dataD =[
				"fin_lpbpurchase_id"=>$dataH["fin_lpbpurchase_id"],
				"fin_lpbgudang_id"=>$finLPBGudangId,
				"fst_active"=> "A"
			];
			$this->trlpbpurchaseitems_model->insert($dataD);			
		}
		

		//POSTING NEW UPDATE
		$result = $this->trlpbpurchase_model->posting($dataH["fin_lpbpurchase_id"]);
		if ($result["status"] != "SUCCESS"){
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = $result["message"];
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = lang("Update data Failed !");
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $dataH["fin_lpbpurchase_id"];
		$this->json_output();
	}


	public function fetch_data($finLPBPurchaseId){
		$data = $this->trlpbpurchase_model->getDataById($finLPBPurchaseId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finLPBPurchaseId){
		
		$tmp = $this->trlpbpurchase_model->getDataById($finLPBPurchaseId);
		$dataHOld = $tmp["lpbPurchase"];
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_lpbpurchase_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$isEditable = $this->trlpbpurchase_model->isEditable($finLPBPurchaseId);
        if($isEditable["status"] != "SUCCESS"){
            return $isEditable;
		}
		

		$this->db->trans_start();

		$data =[
			"fin_user_id_request_by"=>$this->input->post("fin_user_id_request_by"),
			"fst_edit_notes"=>$this->input->post("fst_edit_notes"),
		];

		$resp = $this->trlpbpurchase_model->unposting($finLPBPurchaseId);               
        if($resp["status"] != "SUCCESS"){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
            return;
        }

		$resp = $this->trlpbpurchase_model->delete($finLPBPurchaseId,true,$data);
		
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){
			$this->db->trans_rollback();	
			$resp["status"] = "DB_FAILED";
			$resp["message"] = $dbError["message"];
			return $resp;
		}
		
		$this->db->trans_complete();


		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->json_output();
	}
}    
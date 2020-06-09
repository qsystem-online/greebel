<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trlpbgudang_model');
		$this->load->model('trlpbpurchase_model');
		$this->load->model('trlpbpurchasedetails_model');
		$this->load->model('trlpbpurchaseitems_model');
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
            ['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_lpbpurchase_datetime'],
            ['title' => 'Purchase Order No.', 'width' => '100px', 'data' => 'fst_po_no'],
			['title' => 'Supplier', 'width' => '150px', 'data' => 'fst_supplier_name'],
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_memo'],
			['title' => 'Total Amount', 'width' => '80px', 'data' => 'fdc_total','className'=>'text-right',
				'render'=>"function(data,type,row){
					return row.fst_curr_code + ':' + App.money_format(data);
				}"
			],
			['title' => 'Action', 'width' => '60px', 'sortable' => false, 'className' => 'text-center',
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

        $selectFields = "a.fin_lpbpurchase_id,a.fst_lpbpurchase_no,a.fdt_lpbpurchase_datetime,a.fst_po_no,a.fst_supplier_name,a.fst_memo,a.fdc_total,a.fst_curr_code";
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
		$mdlPrint =$this->parser->parse('template/mdlPrint.php', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Faktur Pembelian") : lang("Update Faktur Pembelian");
		$data["fin_lpbpurchase_id"] = $finLPBPurchaseId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
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

		try{
			$fdt_lpbpurchase_datetime = dBDateTimeFormat($this->input->post("fdt_lpbpurchase_datetime"));
			$resp = dateIsLock($fdt_lpbpurchase_datetime);
			if($resp["status"] !=  "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$dataPrepared = $this->prepareData();
			$dataH =  $dataPrepared["dataH"];
			$dataDetails =  $dataPrepared["dataDetails"];
			$po = $dataPrepared["po"];
			
			$this->validateData($dataH,$po);

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}


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
				$this->trlpbpurchasedetails_model->insert($dataD);			
			}

			foreach ($dataDetails as $dataD) {	
				$dataD =(array) $dataD;	
				$dataD["fin_lpbpurchase_id"] = $insertId;
				$dataD["fdb_qty"] = $dataD["fdb_qty_total"];
				$dataD["fst_active"] = "A";

				$this->trlpbpurchaseitems_model->insert($dataD);			
			}
						
			$this->trlpbpurchase_model->posting($insertId);

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
		}

	}

	public function ajx_edit_save(){
		$this->load->model("trlpbpurchaseitems_model");	


		try{
			$finLPBPurchaseId =  $this->input->post("fin_lpbpurchase_id");
			$dataHOld = $this->db->get_where("trlpbpurchase",["fin_lpbpurchase_id"=>$finLPBPurchaseId])->row();
			if ($dataHOld == null){
				throw new CustomException("Invalid LPB Purchase ID",3003,"FAILED",["fin_lpbpurchase_id"=>$finLPBPurchaseId]);				
			}

			$resp = dateIsLock($dataHOld->fdt_lpbpurchase_datetime);
			if($resp["status"] !=  "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$fdt_lpbpurchase_datetime = dBDateTimeFormat($this->input->post("fdt_lpbpurchase_datetime"));
			$resp = dateIsLock($fdt_lpbpurchase_datetime);
			if($resp["status"] !=  "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$resp = $this->trlpbpurchase_model->isEditable($finLPBPurchaseId);
			if($resp["status"] !=  "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
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
			$this->trlpbpurchase_model->unposting($dataHOld->fin_lpbpurchase_id);
			$this->trlpbpurchase_model->deleteDetail($dataHOld->fin_lpbpurchase_id);

			$dataPrepared = $this->prepareData();
			$dataH =  $dataPrepared["dataH"];
			$dataDetails =  $dataPrepared["dataDetails"];
			$po = $dataPrepared["po"];
			$dataH["fin_lpbpurchase_id"] = $dataHOld->fin_lpbpurchase_id;
			$dataH["fst_lpbpurchase_no"] = $dataHOld->fst_lpbpurchase_no;			
			
			$this->validateData($dataH,$po);

			$insertId = $dataHOld->fin_lpbpurchase_id;
			$this->trlpbpurchase_model->update($dataH);

			//Insert Data Detail Transaksi
			foreach ($this->input->post("fin_lpbgudang_id") as $finLPBGudangId){				
				$dataD =[
					"fin_lpbpurchase_id"=>$insertId,
					"fin_lpbgudang_id"=>$finLPBGudangId,
					"fst_active"=> "A"
				];
				$this->trlpbpurchasedetails_model->insert($dataD);			
			}

			foreach ($dataDetails as $dataD) {	
				$dataD =(array) $dataD;	
				$dataD["fin_lpbpurchase_id"] = $insertId;
				$dataD["fdb_qty"] = $dataD["fdb_qty_total"];
				$dataD["fst_active"] = "A";
				$this->trlpbpurchaseitems_model->insert($dataD);			
			}
						
			$this->trlpbpurchase_model->posting($insertId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_lpbpurchase_id"];
			$this->json_output();
			return;

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
		$fdt_lpbpurchase_datetime = dBDateTimeFormat($this->input->post("fdt_lpbpurchase_datetime"));
		$fst_lpbpurchase_no = $this->trlpbpurchase_model->generateLPBPurchaseNo($fdt_lpbpurchase_datetime);
		$finPOId =$this->input->post("fin_po_id");
		$po = $this->trpo_model->getDataHeaderById($finPOId);
		
		if($po == null){
			throw new CustomException("Invalid PO ID",3003,"FAILED",["fin_po_id"=>$finPOId]);
		}

		$exchangeRate = parseNumber($this->input->post("fdc_exchange_rate_idr"));
		$dpClaim = parseNumber($this->input->post("fdc_downpayment_claim"));

		$dataH = [
			"fst_lpbpurchase_no"=>$fst_lpbpurchase_no,
			"fdt_lpbpurchase_datetime"=>$fdt_lpbpurchase_datetime,
			"fin_po_id"=>$finPOId,
			"fin_supplier_id"=>$po->fin_supplier_id,
			"fin_term"=> $this->input->post("fin_term"),
			"fst_curr_code"=> $po->fst_curr_code,
			"fdc_exchange_rate_idr"=>$exchangeRate,
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


		$dataDetails =  $this->trlpbpurchase_model->getListItemByLPBGudangIds($this->input->post("fin_lpbgudang_id"));
		$subTotal =0;
		$ttlDisc =0;		
		foreach($dataDetails as $dataD){
			$qty = $dataD->fdb_qty_total;
			$price = $dataD->fdc_price;
			$discItem =$dataD->fst_disc_item;
			$discAmountPerItem = $dataD->fdc_disc_amount_per_item;
			//$discAmount =  calculateDisc($discItem , $qty * $price);
			$discAmount =  $discAmountPerItem * $qty;
			$subTotal += ($qty * $price);
			$ttlDisc += $discAmount;
		}

		$dataH["fdc_subttl"] = $subTotal;
		$dataH["fdc_disc_amount"] = $ttlDisc;
		$dataH["fdc_ppn_amount"] = ($subTotal - $ttlDisc) * ($dataH["fdc_ppn_percent"] /100); 
		$dataH["fdc_total"] = $subTotal - $ttlDisc + $dataH["fdc_ppn_amount"] - $dataH["fdc_downpayment_claim"];
		
		
		return [
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails,
			"po"=>$po
		];

	}

	private function validateData($dataH,$po){
		//CEK APAKAH OVER CLAIM DP		
		$availClaimDp = $po->fdc_downpayment_paid - $po->fdc_downpayment_claimed;		
		if($dataH["fdc_downpayment_claim"] > $availClaimDp ){
			throw new CustomException(lang("Over Claim DP"),3003,"VALIDATION_FORM_FAILED",["fdc_downpayment_claim"=>sprintf(lang("Klaim DP tidak boleh melebihi %s"),formatNumber($availClaimDp)) ]);
		}

		//CEK Total Invoice negatif
		if($dataH["fdc_total"] < 0 ){
			throw new CustomException(lang("Total tagihan negatif"),3003,"FAILED", null);
		}

		$this->form_validation->set_rules($this->trlpbpurchase_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}
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

		try{
			
			$dataHOld = $this->db->get_where("trlpbpurchase",["fin_lpbpurchase_id"=>$finLPBPurchaseId])->row();
			if ($dataHOld == null){
				throw new CustomException("Invalid LPB Purchase ID",3003,"FAILED",["fin_lpbpurchase_id"=>$finLPBPurchaseId]);				
			}

			$resp = dateIsLock($dataHOld->fdt_lpbpurchase_datetime);
			if($resp["status"] !=  "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$resp = $this->trlpbpurchase_model->isEditable($finLPBPurchaseId);
			if($resp["status"] !=  "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
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
			
			$this->trlpbpurchase_model->unposting($finLPBPurchaseId);
			$this->trlpbpurchase_model->delete($finLPBPurchaseId,$softDelete = true,$data=null);			

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->json_output();
			return;

		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

	}

	public function print_voucher($finLPBPurchaseId){
		$this->data = $this->trlpbpurchase_model->getDataVoucher($finLPBPurchaseId);
		//$data=[];
		$this->data["title"] = "Invoice";		
		$page_content = $this->parser->parse('pages/tr/purchase/invoice/voucher', $this->data, true);
		$this->data["PAGE_CONTENT"] = $page_content;	
		$strHtml = $this->parser->parse('template/voucher_pdf', $this->data, true);

		//$this->parser->parse('template/voucher', $this->data);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;				
		
		$mpdf->WriteHTML($strHtml);	
		//$mpdf->SetHTMLHeaderByName('MyFooter');

		//echo $data;
		$mpdf->Output();




	}
}    
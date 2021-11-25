<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_request extends MY_Controller{
	public $menuName="payment_request"; 

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model("trpaymentrequest_model");		
		$this->load->model("trpaymentrequestitems_model");
		$this->load->model("msrelations_model");		
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Payment Request";
		$this->list['list_name'] = "Payment Request List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/kas_bank/payment_request/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/kas_bank/payment_request/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_paymentrequest_no' => 'No Payment Request'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Kas Bank', 'link' => '#', 'icon' => ''],
			['title' => 'Payment Request', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '0px','visible'=>'false', 'data' => 'fin_paymentrequest_id'],
			['title' => 'Company', 'width' => '20px', 'data' => 'fst_company_code'],
			['title' => 'No. Payment Request', 'width' => '50px', 'data' => 'fst_paymentrequest_no'],
			['title' => 'Tgl Pengajuan', 'width' => '40px', 'data' => 'fdt_paymentrequest_datetime'],
            ['title' => 'Jatuh Tempo', 'width' => '20px', 'data' => 'fdt_payment_due_date'],
			['title' => 'Total', 'width' => '40px', 'data' => 'fdc_total','className' => 'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}"
			],
			['title' => 'Status', 'width' => '30px', 'data' => 'fst_active','className'=>'text-center',
			'render'=>"function(data,type,row){
				if(data == 'A'){
					return 'OK';
				}else if (data == 'S'){
					return 'Suspend';
				}else if (data == 'R'){
					return 'Rejected';
				}
			}"
		],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/kas_bank/payment_request/edit/' + row.fin_paymentrequest_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$mdlPopupNotes = $this->parser->parse('template/mdlPopupNotes', [], true);
		$this->list['jsfile'] = $this->parser->parse('pages/tr/kas_bank/payment_request/listjs', ["mdlPopupNotes"=>$mdlPopupNotes], true);

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
				SELECT a.fin_paymentrequest_id,a.fst_company_code,a.fst_paymentrequest_no,a.fdt_paymentrequest_datetime,a.fdt_payment_due_date,a.fdc_total,a.fst_active 
                FROM trpaymentrequest a) a");
		$selectFields = "a.fin_paymentrequest_id,a.fst_company_code,a.fst_paymentrequest_no,a.fdt_paymentrequest_datetime,a.fdt_payment_due_date,a.fdc_total,a.fst_active";
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
	
	public function edit($finPaymentRequestId){
		parent::edit($finPaymentRequestId);
		$this->openForm("EDIT", $finPaymentRequestId);

	}
	public function view($finPaymentRequestId){
		$this->openForm("VIEW", $finPaymentRequestId);
	}


	private function openForm($mode = "ADD", $finPaymentRequestId = 0){
		$this->load->library("menus");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Payment Request") : lang("Update Payment Request");
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		
		if($mode == 'ADD'){
			$data["fin_paymentrequest_id"] = 0;
			$data["fst_paymentrequest_no"]=$this->trpaymentrequest_model->GeneratePaymentRequestNo(); 
		}else if($mode == 'EDIT'){
			$data["fin_paymentrequest_id"] = $finPaymentRequestId;
			$data["fst_paymentrequest_no"]="";	
		}else if($mode == 'VIEW'){
			$data["fin_paymentrequest_id"] = $finPaymentRequestId;
			$data["fst_paymentrequest_no"]="";	
		}        
		
		$page_content = $this->parser->parse('pages/tr/kas_bank/payment_request/form', $data, true);
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
			$fdt_paymentrequest_datetime = dBDateTimeFormat($this->input->post("fdt_paymentrequest_datetime"));
			$fst_paymentrequest_no = $this->trpaymentrequest_model->GeneratePaymentRequestNo($fdt_paymentrequest_datetime);

			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details = $dataPrepared["detail"];

			//validation
			$this->validateData($dataH,$details);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}

		try{
			//Save
			$this->db->trans_start();
			//insert data Header
			$insertId = $this->trpaymentrequest_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($details as $item) {		
				$detail = (array) $item;
				$detail =[
					"fin_paymentrequest_id"=>$insertId,
					"fst_notes"=>$item->fst_notes,
					"fdb_qty"=>$item->fdb_qty,
					"fdc_amount"=>$item->fdc_amount,
					"fst_active"=>'A'
				];									
				$this->trpaymentrequestitems_model->insert($detail);			
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
			
			$this->trpaymentrequest_model->posting($insertId);		
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
		parent::ajx_edit_save();
		$this->load->model("trverification_model");

		try{
			$fdt_paymentrequest_datetime = dBDateTimeFormat($this->input->post("fdt_paymentrequest_datetime"));
			$fdt_payment_due_date = dBDateFormat($this->input->post("fdt_payment_due_date"));
			$fin_paymentrequest_id = $this->input->post("fin_paymentrequest_id");
			$tmpH = $this->trpaymentrequest_model->getDataHeaderById($fin_paymentrequest_id);
			if (!$tmpH) {
				$this->ajxResp["status"] = "DATA_NOT_FOUND";
				$this->ajxResp["message"] = "Data id $fin_paymentrequest_id Not Found ";
				$this->ajxResp["data"] = [];
				$this->json_output();
				return;
			}

			$resp = $this->trpaymentrequest_model->isEditable($tmpH->fin_paymentrequest_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"], null);
			}

			$this->db->trans_start();     
			//UNPOSTING
			$this->trpaymentrequest_model->unposting($tmpH->fin_paymentrequest_id);
			$this->trpaymentrequestitems_model->deleteById($fin_paymentrequest_id);
			

			//PREPARE DATA
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details = $dataPrepared["detail"];
			$dataH["fin_paymentrequest_id"]  = $tmpH->fin_paymentrequest_id;

			//validation
			$this->validateData($dataH,$details);
			//SAVE   
			$this->trpaymentrequest_model->update($dataH);
			$insertId = $dataH["fin_paymentrequest_id"];
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0) {
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
			//Insert Data Detail Transaksi
			foreach ($details as $item) {		
				$detail = (array) $item;
				$detail =[
					"fin_paymentrequest_id"=>$insertId,
					"fst_notes"=>$item->fst_notes,
					"fdb_qty"=>$item->fdb_qty,
					"fdc_amount"=>$item->fdc_amount,
					"fst_active"=>'A'
				];									
				$this->trpaymentrequestitems_model->insert($detail);			
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
			//POSTING
			$result = $this->trpaymentrequest_model->posting($fin_paymentrequest_id);
			if($result["status"] != "SUCCESS"){
				$this->ajxResp["status"] = $result["status"];
				$this->ajxResp["message"] = $result["message"];
				$this->json_output();			
				$this->db->trans_rollback();
				return;
			}

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $fin_paymentrequest_id;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();			
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
		}
	}

	private function prepareData(){
		$fdt_paymentrequest_datetime = dBDateTimeFormat($this->input->post("fdt_paymentrequest_datetime"));
		$fdt_payment_due_date = dBDateFormat($this->input->post("fdt_payment_due_date"));
		$fst_paymentrequest_no = $this->trpaymentrequest_model->GeneratePaymentRequestNo($fdt_paymentrequest_datetime);		
		$dataH = [
			"fst_paymentrequest_no"=>$fst_paymentrequest_no,
			"fdt_paymentrequest_datetime"=>$fdt_paymentrequest_datetime,
			"fdt_payment_due_date"=>$fdt_payment_due_date,
			"fst_company_code"=>$this->input->post("fst_company_code"),							
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fin_supplier_id"=>$this->input->post("fin_supplier_id"),
			"fst_pp_memo"=>$this->input->post("fst_pp_memo"),	
			"fst_active"=>'S'		
		];


		$details = $this->input->post("detail");
		$details = json_decode($details);
		$total = 0;
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			$tmpTtl = $item->fdb_qty * $item->fdc_amount;			
			$total += $tmpTtl;			
		}

		$dataH["fdc_total"] = $total;


		return[
			"dataH"=>$dataH,
			"detail"=>$details
		];
		
	}
	
	private function validateData($dataH,$details){

		$this->form_validation->set_rules($this->trpaymentrequest_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		$this->form_validation->set_rules($this->trpaymentrequestitems_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		
		foreach($details as $dataD){
			$this->form_validation->set_data((array) $dataD);
			if ($this->form_validation->run() == FALSE) {
				//print_r($this->form_validation->error_array());
				throw new CustomException("Error Validation Details",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
			}

		}
		

		
	}

	public function fetch_data($finPaymentRequestId){
		$data = $this->trpaymentrequest_model->getDataById($finPaymentRequestId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finPaymentRequestId){
		parent::delete($finPaymentRequestId);
		$this->load->model("trverification_model");
		try{
			
			$dataHOld = $this->trpaymentrequest_model->getDataHeaderById($finPaymentRequestId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid PP ID"),3003,"FAILED",null);
			}

			$resp = $this->trpaymentrequest_model->isDeletable($dataHOld->fin_paymentrequest_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"], null);
			}
									
			//$resp = $this->trpaymentrequest_model->isEditable($finPaymentRequestId,$dataHOld);
			//if ($resp["status"] != "SUCCESS"){
			//	throw new CustomException($resp["message"],3003,"FAILED",null);
			//}			
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();
			
			$this->trpaymentrequest_model->unposting($dataHOld->fin_paymentrequest_id);			
			$resp = $this->trpaymentrequest_model->delete($dataHOld->fin_paymentrequest_id,true,null);	

			$this->db->trans_complete();	

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
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

	public function print_voucher($finPaymentRequestId){
		$data = $this->trpaymentrequest_model->getDataVoucher($finPaymentRequestId);

		$data["title"]= "PERMINTAAN PEMBAYARAN";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/kas_bank/payment_request/voucher', $data, true);
		$dataMain=["PAGE_CONTENT"=>$page_content];	
		$this->parser->parse('template/voucher_pdf',$dataMain);

	}
	

}    
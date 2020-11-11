<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_return extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trlpbsalesreturn_model');
		$this->load->model("trlpbsalesreturnitems_model");
		$this->load->model('mswarehouse_model');
		$this->load->model('trpo_model');
		$this->load->model('trsalesreturn_model');		
		$this->load->model('trassembling_model');		
		$this->load->model("msitems_model");		
	}

	public function index(){

		$this->load->library('menus');
		$this->list['page_name'] = "Gudang - Penerimaan Retur";
		$this->list['list_name'] = "Penerimaan Retur List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/gudang/penerimaan_return/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/gudang/penerimaan_return/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_lpbsalesretun_no' => 'No LPB Sales Return'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Gudang', 'link' => '#', 'icon' => ''],
			['title' => 'Penerimaan Pembelian', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [			
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_lpbsalesreturn_id'],
			['title' => 'No. Penerimaan Retur', 'width' => '60px', 'data' => 'fst_lpbsalesreturn_no'],
			['title' => 'Tanggal', 'width' => '50px', 'data' => 'fdt_lpbsalesreturn_datetime'],
			['title' => 'Relation', 'width' => '100px', 'data' => 'fst_customer_name'],
			['title' => 'Gudang', 'width' => '100px', 'data' => 'fst_warehouse_name'],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/gudang/penerimaan_return/edit/' + row.fin_lpbsalesreturn_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('template/listjs', [], true);

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
			SELECT a.*,b.fst_relation_name as fst_customer_name,c.fst_warehouse_name
			from trlpbsalesreturn a 
			INNER JOIN msrelations b on a.fin_customer_id  = b.fin_relation_id
			INNER JOIN mswarehouse c on a.fin_warehouse_id  = c.fin_warehouse_id
		) a");

		$selectFields = "fin_lpbsalesreturn_id,fst_lpbsalesreturn_no,fdt_lpbsalesreturn_datetime,fst_customer_name,fst_warehouse_name";

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
	
	public function edit($finLPBGudangId){
		$this->openForm("EDIT", $finLPBGudangId);

	}


	private function openForm($mode = "ADD", $id = 0){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Penerimaan Retur Barang") : lang("Update Penerimaan Retur Barang");
		$data["fin_lpbsalesreturn_id"] = $id;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		
		if($mode == 'ADD'){
			$data["fst_lpbsalesreturn_no"]=$this->trlpbsalesreturn_model->generateNo(); 
		}else if($mode == 'EDIT'){
			$data["fst_lpbsalesreturn_no"]="";		
		}        
		
		$page_content = $this->parser->parse('pages/tr/gudang/penerimaan_return/form', $data, true);
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
		
		
		try{
			$fdt_lpbsalesreturn_datetime = dBDateTimeFormat($this->input->post("fdt_lpbsalesreturn_datetime"));
			$resp = dateIsLock($fdt_lpbsalesreturn_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}            			
			$dataPrepared = $this->prepareData();

			$dataH = $dataPrepared["dataH"];
			$details = $dataPrepared["details"];

			$this->validateData($dataH,$details);

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		

		try{
			$this->db->trans_start();
			$insertId = $this->trlpbsalesreturn_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;
				unset($dataD["fin_rec_id"]);
				

				$dataD["fin_lpbsalesreturn_id"] = $insertId;				
				$dataD["fst_active"] = "A";			
				
				$this->trlpbsalesreturnitems_model->insert($dataD);			
			}			
			$this->trlpbsalesreturn_model->posting($insertId);			
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

		
		try{
			$finLPBSalesReturnId = $this->input->post("fin_lpbsalesreturn_id");

			$dataHOld = $this->trlpbsalesreturn_model->getSimpleDataById($finLPBSalesReturnId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid LPB Gudang ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_lpbsalesreturn_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
						
			$fdtLPBSalesReturnDatetime = dBDateTimeFormat($this->input->post("fdt_lpbsalesreturn_datetime"));
			$resp = dateIsLock($fdtLPBSalesReturnDatetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
			$this->trlpbsalesreturn_model->isEditable($finLPBSalesReturnId);			
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();

			$this->trlpbsalesreturn_model->unposting($finLPBSalesReturnId);
			$this->trlpbsalesreturn_model->deleteDetail($finLPBSalesReturnId);
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_lpbsalesreturn_id"] = $finLPBSalesReturnId;
			$dataH["fst_lpbsalesreturn_no"] = $dataHOld->fst_lpbsalesreturn_no;
						
			$details = $preparedData["details"];
			$this->validateData($dataH,$details);			
			$this->trlpbsalesreturn_model->update($dataH);
			
			//Insert Data Detail Transaksi
			foreach ($details as $detail) {		
				$detail = (array) $detail;
				$detail["fin_lpbsalesreturn_id"] = $dataH["fin_lpbsalesreturn_id"];
				$detail["fst_active"] = "A";					
				$this->trlpbsalesreturnitems_model->insert($detail);			
			}

			$this->trlpbsalesreturn_model->posting($finLPBSalesReturnId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_lpbsalesreturn_id"];
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
		$fdt_lpbsalesreturn_datetime = dBDateTimeFormat($this->input->post("fdt_lpbsalesreturn_datetime"));
		$fst_lpbsalesreturn_no = $this->trlpbsalesreturn_model->generateNo($fdt_lpbsalesreturn_datetime);
		$dataH = [
			"fdt_lpbsalesreturn_datetime"=>$fdt_lpbsalesreturn_datetime,
			"fst_lpbsalesreturn_no"=>$fst_lpbsalesreturn_no,
			"fst_curr_code"=>$this->input->post("fst_curr_code"),
			"fin_customer_id"=>$this->input->post("fin_customer_id"),
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',			
		];


		$postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);		
		$details=[];
		foreach($postDetails as $dataD){
			$details[] = [
				"fin_rec_id"=>$dataD->fin_rec_id,
				"fin_inv_id"=>$dataD->fin_inv_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty"=>$dataD->fdb_qty,
				"fst_batch_number"=>$dataD->fst_batch_number,
				"fst_serial_number_list"=>json_encode($dataD->fst_serial_number_list),
				"fst_active"=>"A",
			];

		}
		
		
		return[
			"dataH"=>$dataH,
			"details"=>$details
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trlpbsalesreturn_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		$this->form_validation->set_rules($this->trlpbsalesreturnitems_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		foreach($details as $dataD){
			$dataD =(object) $dataD;

			$this->form_validation->set_data((array) $dataD);
			//if ($this->form_validation->run() == FALSE) {
				//print_r($this->form_validation->error_array());
			//	throw new CustomException("Error Validation Details",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
			//}
			

			$itemInfo = $this->msitems_model->getSimpleDataById($dataD->fin_item_id);

			//Cek is item have batch number
			if($itemInfo->fbl_is_batch_number && $dataD->fst_batch_number == "" ){
				throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
			}

			//Cek is item have serial number
			if($itemInfo->fbl_is_serial_number){				
				
				$arrSerial = json_decode($dataD->fst_serial_number_list);

				//if($dataD->fst_serial_number_list == null ){
				if (sizeof($arrSerial) == 0 ){
					throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
				}
								
				if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit) ){
					throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$dataD->fst_custom_item_name,$dataD->fdb_qty),3003,"FAILED",$dataD);
				}

			}

		}
		

		
	}

	public function fetch_data($id){
		$data = $this->trlpbsalesreturn_model->getDataById($id);

		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;
		}		
		$this->json_output($resp);
	}

	public function delete($id){

		try{
			$finLPBSalesReturnId = $id;
			$dataHOld = $this->trlpbsalesreturn_model->getSimpleDataById($finLPBSalesReturnId);
			
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid LPB Sales Return ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_lpbsalesreturn_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
									
			$this->trlpbsalesreturn_model->isEditable($finLPBSalesReturnId);			
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}


		try{
			$this->db->trans_start();		
			$this->trlpbsalesreturn_model->unposting($finLPBSalesReturnId);			
			$this->trlpbsalesreturn_model->delete($finLPBSalesReturnId,true,null);	
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

	public function DELETE_get_transaction_list($lpbType){
		$transList = $this->trlpbgudang_model->getTransactionList($lpbType);
		return $this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$transList,
		]);		
	}

	public function print_voucher($finLPBGudangId){
		$data = $this->trlpbgudang_model->getDataVoucher($finLPBGudangId);

		$data["title"]= "Penerimaan Barang";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/gudang/penerimaan_pembelian/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;

			
		//$mpdf->SetHTMLHeaderByName('MyFooter');
		$mpdf->WriteHTML($data);
		$mpdf->Output();

	}
	
	public function ajxGetInvoiceList(){
		$term = $this->input->get("term");
		$term = "%$term%";
		$finCustomerId = $this->input->get("fin_customer_id");
		$fblLunas = $this->input->get("fbl_lunas") == "true" ? true: false;
		$fstCurrCode = $this->input->get("fst_curr_code");


		//$ssql ="SELECT fin_inv_id,fst_inv_no FROM trinvoice where fdc_total_paid = 0 and fin_relation_id = ? and fst_inv_no like ? and fst_active = 'A' ";

		$ssql ="SELECT distinct b.fin_inv_id,b.fst_inv_no FROM trinvoiceitems a
			INNER JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id 
			WHERE a.fdb_qty > a.fdb_qty_return 
			AND b.fin_relation_id = ? 
			AND b.fst_curr_code = ?  
			AND b.fst_inv_no like ?			
			AND b.fst_active = 'A' ";
		
		if ($fblLunas){
			$ssql .= "AND fdc_total <= fdc_total_paid";
		}


		$qr = $this->db->query($ssql,[$finCustomerId,$fstCurrCode,$term]);
		$rs =  $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}

	public function ajxGetItemList(){
		$this->load->model("msitems_model");

		$term = $this->input->get("term");
		$term = "%$term%";
		$finInvId = $this->input->get("fin_inv_id");

		if ($finInvId == ""){
			$ssql = "SELECT fin_item_id,fst_item_code,fst_item_name,fbl_is_batch_number,fbl_is_serial_number FROM msitems 
				WHERE fst_active ='A' 
				AND CONCAT(fst_item_code,fst_item_name) like ? 
				AND fin_item_type_id in (1,2,3,4) ";
			$qr = $this->db->query($ssql,[$term]);
		}else{
			$ssql ="SELECT distinct b.fin_item_id,b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number 
			FROM trinvoiceitems a
			INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id 
			WHERE a.fin_inv_id = ? 
			AND a.fdb_qty > a.fdb_qty_return 
			AND b.fst_active ='A' and CONCAT(b.fst_item_code,b.fst_item_name) like ? ";
			$qr = $this->db->query($ssql,[$finInvId,$term]);
		}        
		$rs= $qr->result();

		for($i =0;$i<sizeof($rs);$i++){
			$rw = $rs[$i];
			$rw->fst_basic_unit = $this->msitems_model->getBasicUnit($rw->fin_item_id);
			$rs[$i] = $rw;
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}

	public function ajxGetItemUnits(){
		$finInvId = $this->input->get("fin_inv_id");
		$finItemId = $this->input->get("fin_item_id");

		if ($finInvId == ""){
			$ssql ="SELECT fst_unit,fdc_conv_to_basic_unit FROM msitemunitdetails where fin_item_id = ? and fbl_is_selling = 1 and fst_active ='A'";
			$qr = $this->db->query($ssql,[$finItemId]);

		}else{
			$ssql ="SELECT distinct a.fst_unit,b.fdc_conv_to_basic_unit FROM trinvoiceitems a 
			INNER JOIN msitemunitdetails b on a.fin_item_id = b.fin_item_id and a.fst_unit = b.fst_unit
			WHERE a.fin_item_id = ?  and fin_inv_id = ? and a.fst_active ='A'";
			$qr = $this->db->query($ssql,[$finItemId,$finInvId]);            
		}

		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs,
		]);        
	}
}    
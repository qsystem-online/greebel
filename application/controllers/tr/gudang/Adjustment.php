<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Adjustment extends MY_Controller{
	public $menuName="adjustment_stock"; 
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model("tradjustment_model");		
		$this->load->model("tradjustmentitems_model");		
		$this->load->model("mswarehouse_model");
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Adjustment Stock";
		$this->list['list_name'] = "Adjustment List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/gudang/adjustment/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/gudang/adjustment/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_adjustment_no' => 'No Adjustment',
			'fst_reff' => 'No Reff',
			'fst_warehouse_name' => 'Gudang'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Gudang', 'link' => '#', 'icon' => ''],
			['title' => 'Adjustment Stock', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_adjustment_id'],
			['title' => 'No. Adjustment', 'width' => '60px', 'data' => 'fst_adjustment_no'],
			['title' => 'Tanggal', 'width' => '50px', 'data' => 'fdt_adjustment_datetime'],
            ['title' => 'No Reff', 'width' => '60px', 'data' => 'fst_reff'],
			['title' => 'Gudang', 'width' => '60px', 'data' => 'fin_warehouse_id',
				'render'=>'function(data,type,row){
					return row.fst_warehouse_name;
				}'
			],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/gudang/adjustment/edit/' + row.fin_adjustment_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/gudang/adjustment/listjs', [], true);

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
				SELECT a.*,b.fst_warehouse_name as fst_warehouse_name FROM tradjustment a INNER JOIN mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id
			) a");

		$selectFields = "a.*";
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
	
	public function edit($finAdjustmentId){
		parent::edit($finAdjustmentId);
		$this->openForm("EDIT", $finAdjustmentId);

	}


	private function openForm($mode = "ADD", $finAdjustmentId = 0){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		
		$this->load->model("users_model");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Adjustment Stock") : lang("Update Adjustment Stock");
		$data["fin_adjustment_id"] = $finAdjustmentId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		
		if($mode == 'ADD'){
			$data["fst_adjustment_no"]=$this->tradjustment_model->generateNo(); 
		}else if($mode == 'EDIT'){
			$data["fst_adjustment_no"]="";	
		}        
		
		$page_content = $this->parser->parse('pages/tr/gudang/adjustment/form', $data, true);
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
		//$this->load->model("msitems_model");		
		try{
			$fdt_adjustment_datetime = dBDateTimeFormat($this->input->post("fdt_adjustment_datetime"));
			//$resp = dateIsLock($fdt_adjustment_datetime);
			//if ($resp["status"] != "SUCCESS"){
				//throw new CustomException($resp["message"],3003,"FAILED",null);
			//}
			$fst_adjustment_no = $this->tradjustment_model->generateNo($fdt_adjustment_datetime);
			
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
			$insertId = $this->tradjustment_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;
				$dataD["fin_adjustment_id"] = $insertId;
				$dataD["fst_batch_number"] = $dataD["fst_batch_number"];
				$dataD["fst_serial_number_list"] = json_encode($dataD["fst_serial_number_list"]);
				$dataD["fst_active"] = "A";									
				$this->tradjustmentitems_model->insert($dataD);			
			}
			
			$this->tradjustment_model->posting($insertId);		
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
		try{
			$finAdjustmentId = $this->input->post("fin_adjustment_id");

			$dataHOld = $this->tradjustment_model->getDataHeaderById($finAdjustmentId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid ADJUSTMENT ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_adjustment_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
						
			$fdt_adjustment_datetime = dBDateTimeFormat($this->input->post("fdt_adjustment_datetime"));
			$resp = dateIsLock($fdt_adjustment_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			//$resp = $this->tradjustment_model->isEditable($finAdjustmentId);
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
			
			$this->tradjustment_model->unposting($finAdjustmentId,"", true);
			$this->tradjustment_model->deleteDetail($finAdjustmentId);

			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_adjustment_id"] = $finAdjustmentId;
			$dataH["fst_adjustment_no"] = $dataHOld->fst_adjustment_no;
						
			$details = $preparedData["details"];
			$this->validateData($dataH,$details);

			
			$this->tradjustment_model->update($dataH);
			
			//Insert Data Detail Transaksi
			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;				
				$dataD["fin_adjustment_id"] = $finAdjustmentId;
				$dataD["fst_batch_number"] = $dataD["fst_batch_number"];
				$dataD["fst_serial_number_list"] = json_encode($dataD["fst_serial_number_list"]);
				$dataD["fst_active"] = "A";									
				$this->tradjustmentitems_model->insert($dataD);			
			}

			$this->tradjustment_model->posting($finAdjustmentId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_adjustment_id"];
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
		$fdt_adjustment_datetime = dBDateTimeFormat($this->input->post("fdt_adjustment_datetime"));
		$fst_adjustment_no = $this->tradjustment_model->generateNo($fdt_adjustment_datetime);		
		$dataH = [
			"fst_adjustment_no"=>$fst_adjustment_no,
			"fdt_adjustment_datetime"=>$fdt_adjustment_datetime,
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),		
			"fst_reff"=>$this->input->post("fst_reff"),						
			"fst_notes"=>$this->input->post("fst_notes"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',			
		];


		$dataDetails = $this->input->post("detail");
		$dataDetails = json_decode($dataDetails);
		
		$details = [];

		foreach($dataDetails as $detail){
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
                "fst_in_out"=>$detail->fst_in_out,
				"fdb_qty"=>$detail->fdb_qty,
				"fst_batch_number"=>$detail->fst_batch_number,
				"fst_serial_number_list"=>$detail->fst_serial_number_list
			];

			$details[]=(object) $tmp;
		}
		return[
			"dataH"=>$dataH,
			"details"=>$details
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->load->model("msitems_model");
		$this->form_validation->set_rules($this->tradjustment_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		$this->form_validation->set_rules($this->tradjustmentitems_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		
		foreach($details as $dataD){
			
			$this->form_validation->set_data((array) $dataD);
			if ($this->form_validation->run() == FALSE) {
				//print_r($this->form_validation->error_array());
				throw new CustomException("Error Validation Details",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
			}

			$itemInfo = $this->msitems_model->getSimpleDataById($dataD->fin_item_id);

			//Cek is item have batch number
			if($itemInfo->fbl_is_batch_number && $dataD->fst_batch_number == "" ){
				throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
			}

			//Cek is item have serial number
			if($itemInfo->fbl_is_serial_number){				
				//$arrSerial = json_decode($item->arr_serial);
				$arrSerial = $dataD->fst_serial_number_list;
				if($arrSerial == null){
					throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
				}

				if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit) ){
					throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$dataD->fst_custom_item_name,$dataD->fdb_qty),3003,"FAILED",$dataD);
				}

			}

		}
		

		
	}

	public function fetch_data($finAdjustmentId){
		$data = $this->tradjustment_model->getDataById($finAdjustmentId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finAdjustmentId){
		parent::delete($finAdjustmentId);
		try{
			
			$dataHOld = $this->tradjustment_model->getDataHeaderById($finAdjustmentId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid ADJUSTMENT ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_adjustment_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
									
			//$resp = $this->tradjustment_model->isEditable($finAdjustmentId,$dataHOld);
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
			
			$this->tradjustment_model->unposting($finAdjustmentId,"", true);			
			$resp = $this->tradjustment_model->delete($finAdjustmentId,true,null);	

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

	public function ajxListItem(){
		$this->load->model("msitems_model");
		$searchKey = $this->input->get("term");
		$result = $this->msitems_model->getAllList($searchKey,"fin_item_id,fst_item_code,fst_item_name,fbl_is_batch_number,fbl_is_serial_number");        
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=> $result
		]);
	}
	
	public function ajxListUnit(){
		$this->load->model("msitemunitdetails_model");
		$this->load->model("msitemunitdetails_model");
		
		$finItemId = $this->input->get("fin_item_id");
		$basicUnit = $this->msitemunitdetails_model->getBasicUnit($finItemId);
		$result = $this->msitemunitdetails_model->getItemListUnits($finItemId);

		$list = [];
		foreach($result as $unit){
			$list[] = [
				"fst_unit"=>$unit->fst_unit,
				"fbl_is_basic_unit"=>$unit->fbl_is_basic_unit,
				"fdc_conv_to_basic_unit"=>$unit->fdc_conv_to_basic_unit,                 
				"fst_basic_unit"=>$basicUnit,
			];
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$list
		]);
	}

	public function print_voucher($finAdjustmentId){
		$data = $this->tradjustment_model->getDataVoucher($finAdjustmentId);

		$data["title"]= "Adjustment Stock";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/gudang/adjustment/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;	
		$mpdf->WriteHTML($data);
		$mpdf->Output();

	}
	

}    
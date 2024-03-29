<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi extends MY_Controller{
	public $menuName="mag_umum"; 
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model("trmag_model");		
		$this->load->model("trmagitems_model");		
		$this->load->model("mswarehouse_model");
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Mutasi Antar Gudang";
		$this->list['list_name'] = "MAG List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/gudang/mutasi/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/gudang/mutasi/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_mag_no' => 'No MAG'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Gudang', 'link' => '#', 'icon' => ''],
			['title' => 'Mutasi Antar Gudang', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_mag_id'],
			['title' => 'No. MAG', 'width' => '60px', 'data' => 'fst_mag_no'],
			['title' => 'Tanggal', 'width' => '50px', 'data' => 'fdt_mag_datetime'],
			['title' => 'From', 'width' => '60px', 'data' => 'fin_from_warehouse_id',
				'render'=>'function(data,type,row){
					return row.fst_from_warehouse_name;
				}'
			],
			['title' => 'To', 'width' => '100px', 'data' => 'fin_to_warehouse_id',
				'render'=>'function(data,type,row){
					return row.fst_to_warehouse_name;
				}'
			],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/gudang/mutasi/edit/' + row.fin_mag_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/gudang/mutasi/listjs', [], true);

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
				SELECT a.*,b.fst_warehouse_name as fst_from_warehouse_name,c.fst_warehouse_name as fst_to_warehouse_name FROM trmag a
				INNER JOIN mswarehouse b on a.fin_from_warehouse_id = b.fin_warehouse_id
				INNER JOIN mswarehouse c on a.fin_to_warehouse_id = c.fin_warehouse_id
				WHERE a.fbl_mag_production = 0
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
	
	public function edit($finMagId){
		parent::edit($finMagId);
		$this->openForm("EDIT", $finMagId);

	}


	private function openForm($mode = "ADD", $finMagId = 0){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		
		$this->load->model("users_model");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Mutasi Antar Gudang") : lang("Update Mutasi Antar Gudang");
		$data["fin_mag_id"] = $finMagId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		
		if($mode == 'ADD'){
			$data["fst_mag_no"]=$this->trmag_model->generateNo(); 
		}else if($mode == 'EDIT'){
			$data["fst_mag_no"]="";	
		}        
		
		$page_content = $this->parser->parse('pages/tr/gudang/mutasi/form', $data, true);
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
			$fdt_mag_datetime = dBDateTimeFormat($this->input->post("fdt_mag_datetime"));
			$resp = dateIsLock($fdt_mag_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
			$fst_mag_no = $this->trmag_model->generateNo($fdt_mag_datetime);
			
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
			$insertId = $this->trmag_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;
				$dataD["fin_mag_id"] = $insertId;
				$dataD["fst_batch_number"] = $dataD["fst_batch_number"];
				$dataD["fst_serial_number_list"] = json_encode($dataD["fst_serial_number_list"]);
				$dataD["fst_active"] = "A";									
				$this->trmagitems_model->insert($dataD);			
			}
			
			$this->trmag_model->posting($insertId);		
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
			$finMagId = $this->input->post("fin_mag_id");

			$dataHOld = $this->trmag_model->getDataHeaderById($finMagId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid MAG ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_mag_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
						
			$fdt_mag_datetime = dBDateTimeFormat($this->input->post("fdt_mag_datetime"));
			$resp = dateIsLock($fdt_mag_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$resp = $this->trmag_model->isEditable($finMagId);
			if ($resp["status"] != "SUCCESS"){
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
			
			$this->trmag_model->unposting($finMagId);
			$this->trmag_model->deleteDetail($finMagId);

			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_mag_id"] = $finMagId;
			$dataH["fst_mag_no"] = $dataHOld->fst_mag_no;
						
			$details = $preparedData["details"];
			$this->validateData($dataH,$details);

			
			$this->trmag_model->update($dataH);
			
			//Insert Data Detail Transaksi
			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;				
				$dataD["fin_mag_id"] = $finMagId;
				$dataD["fst_batch_number"] = $dataD["fst_batch_number"];
				$dataD["fst_serial_number_list"] = json_encode($dataD["fst_serial_number_list"]);
				$dataD["fst_active"] = "A";									
				$this->trmagitems_model->insert($dataD);			
			}

			$this->trmag_model->posting($finMagId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_mag_id"];
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
		$fdt_mag_datetime = dBDateTimeFormat($this->input->post("fdt_mag_datetime"));
		$fst_mag_no = $this->trmag_model->generateNo($fdt_mag_datetime);		
		$dataH = [
			"fst_mag_no"=>$fst_mag_no,
			"fdt_mag_datetime"=>$fdt_mag_datetime,
			"fin_from_warehouse_id"=>$this->input->post("fin_from_warehouse_id"),
			"fin_to_warehouse_id"=>$this->input->post("fin_to_warehouse_id"),		
			"fin_driver_id"=>$this->input->post("fin_driver_id"),		
			"fst_no_polisi"=>$this->input->post("fst_no_polisi"),						
			"fst_memo"=>$this->input->post("fst_memo"),
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
		$this->form_validation->set_rules($this->trmag_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($dataH["fin_from_warehouse_id"] == $dataH["fin_to_warehouse_id"]){
			throw new CustomException("Data gudang asa dan gudang tujuan tidak boleh sama !",3003,"FAILED",[]);
		}

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		$this->form_validation->set_rules($this->trmagitems_model->getRules("ADD", 0));
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

	public function fetch_data($finMagId){
		$data = $this->trmag_model->getDataById($finMagId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finMagId){
		parent::delete($finMagId);
		try{
			
			$dataHOld = $this->trmag_model->getDataHeaderById($finMagId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid MAG ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_mag_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
									
			$resp = $this->trmag_model->isEditable($finMagId,$dataHOld);
			if ($resp["status"] != "SUCCESS"){
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
			
			$this->trmag_model->unposting($finMagId);			
			$resp = $this->trmag_model->delete($finMagId,true,null);	

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

	public function print_voucher($finMagId){
		$this->data = $this->trmag_model->getDataVoucher($finMagId);

        //$this->data["company"] = "PT.SURYAMAS CIPTA SENTOSA";
		$this->data["title"] = "SURAT JALAN (S/J)";	

		$page_content = $this->parser->parse('pages/tr/gudang/mutasi/voucher', $this->data, true);
		$dataMain=["PAGE_CONTENT"=>$page_content];	
	    $this->parser->parse('template/voucher_pdf',$dataMain);

	}
	

}    
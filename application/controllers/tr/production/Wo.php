<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wo extends MY_Controller{
	public $menuName="workorder";
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("mscurrencies_model");
		$this->load->model("msactivitygroups_model");

		$this->load->model("trwo_model");
		$this->load->model("trwobomdetails_model");
		$this->load->model("trwoactivitydetails_model");
		$this->load->model("trwobatchno_model");
		$this->load->model("mswarehouse_model");
		//$this->load->model("trmpsitems_model");
		
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Workorder";
		$this->list['list_name'] = "Workorder List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/wo/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/wo/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_wo_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'Workorder', 'link' => null, 'icon' => '']			
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_wo_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_wo_no'],
			['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_wo_datetime'],
			['title' => 'Target', 'width' => '60px', 'data' => 'fdt_wo_target_date'],
			['title' => 'Type', 'width' => '50px', 'data' => 'fst_wo_type'],
			['title' => 'Item', 'width' => '50px', 'data' => 'fst_item_name'],
			['title' => 'Qty', 'width' => '50px', 'data' => 'fdb_qty'],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/wo/edit/' + row.fin_wo_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				select a.*,b.fst_item_name from trwo a inner join msitems b on a.fin_item_id = b.fin_item_id where a.fst_active != 'D' 
			) a");

		$selectFields = "a.*";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		
		$datasources = $this->datatables->getData();		
		//$arrData = $datasources["data"];
		/*
		$arrDataFormated = [];
		foreach ($arrData as $data) {        
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		*/
		//$datasources["data"] = $arrData;
		$this->json_output($datasources);
		
	}

	public function add(){
		parent::add();
		$this->openForm("ADD", 0);
	}
	
	public function edit($finId){
		parent::edit($finId);
		$this->openForm("EDIT", $finId);

	}


	private function openForm($mode = "ADD", $finId = 0){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		
		$this->load->model("users_model");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);
		
		$data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>1], true);
		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Workorder") : lang("Update Workorder");
		$data["fin_wo_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;			

		$fstWONo = $this->trwo_model->generateTransactionNo();
		$data["fst_wo_no"] = $fstWONo;	
		$page_content = $this->parser->parse('pages/tr/production/wo/form', $data, true);
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
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$detailsBOMWO =$dataPrepared["detailsBOMWO"];
			$detailsActivity=$dataPrepared["detailsActivity"];

			unset($dataH["fin_wo_id"]);
			$dataH["fst_wo_no"] = $this->trwo_model->generateTransactionNo();			
			$this->validateData($dataH,$detailsBOMWO,$detailsActivity);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		

		try{
			$this->db->trans_start();
			$insertId = $this->trwo_model->insert($dataH);

			foreach($detailsBOMWO as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_wo_id"] = $insertId;
				$this->trwobomdetails_model->insert($dataD);
			}

			foreach($detailsActivity as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_wo_id"] = $insertId;
				$this->trwoactivitydetails_model->insert($dataD);
			}

			//Create 1 active BatchNO		
			$batchNo = substr($dataH["fst_wo_no"],strlen($dataH["fst_wo_no"])-4) . "-01";
			$dataD = [
				"fst_wobatchno_no"=>$batchNo,
				"fdt_wobatchno_datetime"=>date("Y-m-d H:i:s"),
				"fin_wo_id"=>$insertId,
				"fst_notes"=>"Default Batch Number #",
				"fst_active"=>"A"
			];

			$this->trwobatchno_model->insert($dataD);
			
			$this->trwo_model->posting($insertId);
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
        $finWOId = $this->input->post("fin_wo_id");
		try{
            $dataHOld = $this->trwo_model->getDataHeader($finWOId);
            if ($dataHOld == null){
                show_404();
            }
			
			//$resp = dateIsLock($dataHOld->fdt_assembling_datetime);
			//if($resp["status"] != "SUCCESS"){
			//	throw new CustomException($resp["message"],3003,"FAILED",[]);
			//}
            //$this->trassembling_model->isEditable($finAssemblingId);
                        
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$detailsBOMWO = $preparedData["detailsBOMWO"];
			$detailsActivity = $preparedData["detailsActivity"];
			
			$dataH["fin_wo_id"] = $finWOId;
			$dataH["fst_wo_no"] = $dataHOld->fst_wo_no;
			


			$this->db->trans_start();

			$this->trwo_model->deleteDetail($finWOId);
			
			$this->validateData($dataH,$detailsBOMWO,$detailsActivity);

			$this->trwo_model->update($dataH);

			foreach($detailsBOMWO as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_wo_id"] = $finWOId;
				$this->trwobomdetails_model->insert($dataD);
			}

			foreach($detailsActivity as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_wo_id"] = $finWOId;
				$this->trwoactivitydetails_model->insert($dataD);
			}			
			$this->trwo_model->posting($finWOId);
			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finWOId;
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
		$dataH = [
			"fin_wo_id"=>$this->input->post("fin_wo_id"),
			"fst_wo_no"=>$this->input->post("fst_wo_no"),
			"fdt_wo_datetime"=>dBDateTimeFormat($this->input->post("fdt_wo_datetime")),
			"fst_wo_type"=>$this->input->post("fst_wo_type"),
			"fdt_wo_target_date"=>dBDateFormat($this->input->post("fdt_wo_target_date")),
			"fin_supplier_id"=>$this->input->post("fin_supplier_id"),
			"fst_curr_code" =>$this->input->post("fst_curr_code"),
			"fdc_external_cost_per_unit" =>$this->input->post("fdc_external_cost_per_unit"),			
			"fin_item_id"=>$this->input->post("fin_item_id"),
			"fst_unit"=>$this->input->post("fst_unit"),
			"fdb_qty"=>$this->input->post("fdb_qty"),
			"fin_warehouse_target"=>$this->input->post("fin_warehouse_target"),
			"fst_notes" => $this->input->post("fst_notes"),
			"fin_activity_group_id" => $this->input->post("fin_activity_group_id"),
			"fst_active"=>'A',			
			"fin_branch_id"=>$this->aauth->get_active_branch_id()
		];		

		$dataDetails = $this->input->post("detailsBOMWO");
		$dataDetails = json_decode($dataDetails);		
		$detailsBOMWO = [];
		foreach($dataDetails as $detail){			
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
				"fdb_qty"=>$detail->fdb_qty,
				"fst_active"=>"A"
			];
			$detailsBOMWO[]=(object) $tmp;
		}


		$dataDetails = $this->input->post("detailsActivity");
		$dataDetails = json_decode($dataDetails);
		$detailsActivity = [];
		foreach($dataDetails as $detail){			
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_activity_id"=>$detail->fin_activity_id,
				"fst_active"=>"A"
			];
			$detailsActivity[]=(object) $tmp;
		}

		return[
			"dataH"=>$dataH,
			"detailsBOMWO"=>$detailsBOMWO,
			"detailsActivity"=>$detailsActivity
		];
		
	}
	
	private function validateData($dataH,$detailsBOMWO,$detailsActivity){
		$this->form_validation->set_rules($this->trwo_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		//If external supplier tidak boleh kosong
		if ($dataH["fst_wo_type"]=="External"){
			if ($dataH["fin_supplier_id"]== null || $dataH["fin_supplier_id"]== ""){
				throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",["fin_supplier_id"=>lang("Supplier tidak boleh kosong")]);	
			}
		}
	}

	public function fetch_data($finId){
		$data = $this->trwo_model->getDataById($finId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}


	public function delete($finId){
		parent::delete($finId);
		try{
            $dataHOld = $this->trwo_model->getDataHeader($finId);
            if ($dataHOld == null){
                show_404();
			}
            $this->trwo_model->isEditable($finId);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();			
			$resp = $this->trwo_model->delete($finId,true,null);	
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

	public function print_voucher($finMagId){
		$data = $this->trmag_model->getDataVoucher($finMagId);

		$data["title"]= "Mutasi Antar Gudang";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/gudang/mutasi/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;	
		$mpdf->WriteHTML($data);
		$mpdf->Output();

	}


	public function ajxGetItemList(){
		$term = $this->input->get("term");
		$term = "%$term%";

		$finItemGroupId = $this->input->get("fin_item_group_id");

		$ssql = "SELECT a.fin_item_id,a.fst_item_code,a.fst_item_name,a.fdc_scale_for_bom,b.fst_unit as fst_basic_unit
			FROM msitems a
			LEFT JOIN msitemunitdetails b on a.fin_item_id = b.fin_item_id 
			WHERE a.fin_item_group_id = ? AND (a.fst_item_code like ? or a.fst_item_name like ?) 
			AND a.fin_item_type_id IN (2,3,4) AND a.fst_active ='A' and b.fbl_is_basic_unit = true";

		$qr = $this->db->query($ssql,[$finItemGroupId,$term,$term]);
		$rs = $qr->result();

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);

	}

	public function ajxGetUnits(){
		
		$finItemId = $this->input->get("fin_item_id");

		$ssql = "SELECT fst_unit FROM msitemunitdetails where fin_item_id = ? AND fbl_is_production_output = true AND fst_active ='A'";
		$qr = $this->db->query($ssql,[$finItemId]);
		$rs = $qr->result();

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);

	}

	public function ajxGetItemBOM(){
		$finItemId = $this->input->get("fin_item_id");
		$ssql = "SELECT b.fin_item_id,a.fst_unit,a.fdb_qty,b.fst_item_code,b.fst_item_name 
			FROM msitembomdetails a
			INNER JOIN msitems b on a.fin_item_id_bom = b.fin_item_id 
			WHERE a.fin_item_id = ? AND a.fst_active ='A' ";
		$qr = $this->db->query($ssql,[$finItemId]);		
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$qr->result()
		]);
	}

	public function ajxGetSupplierList(){
		$this->load->model("msrelations_model");
		$rs = $this->msrelations_model->getSupplierList();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}

	
	public function ajxGetItemForBom(){
		$term = $this->input->get("term");
		$term = "%$term%";

		$finItemGroupId = $this->input->get("fin_item_group_id");

		$ssql = "SELECT a.fin_item_id,a.fst_item_code,a.fst_item_name 
			FROM msitems a
			WHERE (a.fst_item_code like ? or a.fst_item_name like ?) 
			AND a.fin_item_type_id IN (1,2,3,4) AND a.fst_active ='A'";

		$qr = $this->db->query($ssql,[$term,$term]);
		$rs = $qr->result();

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);

	}
	public function ajxGetUnitForBom(){
		
		$finItemId = $this->input->get("fin_item_id");

		$ssql = "SELECT fst_unit FROM msitemunitdetails where fin_item_id = ? AND fst_active ='A'";
		$qr = $this->db->query($ssql,[$finItemId]);
		$rs = $qr->result();

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);

	}

	public function ajxCalculateMaterialRequirment(){
		$finItemId = $this->input->post("fin_item_id");
		$fstUnit = $this->input->post("fst_unit");
		$fdbQty = (double) $this->input->post("fdb_qty");
		$dataDetails = $this->input->post("details");
		$detailBOMWO = json_decode($dataDetails);
		$detailBOMWO = $this->trwo_model->ajxCalculateMaterialRequirment($finItemId,$fstUnit,$fdbQty,$detailBOMWO);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$detailBOMWO
		]);
	}

	public function ajxGetActivityList(){
		$term = $this->input->get("term");
		$term = "%$term%";

		$finActivityGroupId = $this->input->get("fin_activity_group_id");

		if($finActivityGroupId == null){	
			$ssql = "SELECT * FROM msactivity where fst_active ='A' and fst_name like ?";
			$qr = $this->db->query($ssql,[$term]);
			$rs= $qr->result();

			$this->json_output([
				"status"=>"SUCCESS",
				"messages"=>"",
				"data"=>$rs
			]);

		}else{
			$ssql = "SELECT b.* FROM msactivitygroupdetails a 
				INNER JOIN msactivity b on a.fin_activity_id = b.fin_activity_id 
				where a.fin_activity_group_id = ? AND a.fst_active ='A' and b.fst_active ='A' ";
			$qr = $this->db->query($ssql,[$finActivityGroupId]);
			
			$rs= $qr->result();

			$this->json_output([
				"status"=>"SUCCESS",
				"messages"=>"",
				"data"=>$rs
			]);
			
		}
	}

}    
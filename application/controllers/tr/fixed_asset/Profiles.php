<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profiles extends MY_Controller{
	public $menuName="fixed_asset_profile";
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
        $this->load->model("glaccounts_model");		
		$this->load->model("msfagroups_model");
		$this->load->model("profitcostcenter_model");
		$this->load->model("mswarehouse_model");
		$this->load->model("trfaprofiles_model");
		$this->load->model("trfaprofilesitems_model");		
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Fixed Asset Profile";
		$this->list['list_name'] = "Fixed Asset Profile List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/fixed_asset/profiles/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/fixed_asset/profiles/fetch_list_data';
		$this->list['arrSearch'] = [
            'fst_fa_profile_no' => 'Profile No',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Fixed Assets', 'link' => '#', 'icon' => ''],
			['title' => 'Profile', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_fa_profile_id'],
			['title' => 'No', 'width' => '60px', 'data' => 'fst_fa_profile_no'],
            ['title' => 'Nama', 'width' => '50px', 'data' => 'fst_fa_profile_name'],
			['title' => 'Qty', 'width' => '20px', 'data' => 'fdb_qty','className' => 'text-right'],
			['title' => 'Aquisition price ', 'width' => '50px', 'data' => 'fdc_aquisition_price',
				"render"=>"function(data,type,row){
					return money_format(data);
				}",'className' => 'text-right'
			],
			['title' => 'Aquisition Date ', 'width' => '50px', 'data' => 'fdt_aquisition_date',
				'render'=>"function(data,type,row){
					return dateFormat(data);
				}",'className' => 'text-right'
			],
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/fixed_asset/profiles/edit/' + row.fin_fa_profile_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
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
				select * from trfaprofiles where fst_active != 'D'
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
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);
		$mdlJurnal = $this->parser->parse('template/mdlJurnal', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Profile Fixed Asset") : lang("Update Profile Fixed Asset");
		$data["fin_fa_profile_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $mdlJurnal;				
		
		$data["fst_fa_profile_no"] ="";
		if ($mode == "ADD"){
			$data["fst_fa_profile_no"] = $this->trfaprofiles_model->generateNo();
		}
		$page_content = $this->parser->parse('pages/tr/fixed_asset/profiles/form', $data, true);
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
			unset($dataH["fin_fa_profile_id"]);
			$dataH["fst_fa_profile_no"]=$this->trfaprofiles_model->generateNo();
			$dataH["fst_active"]="A";
			/*
			$resp = dateIsLock($dataH["fdt_aquisition_date"]);
			if ($resp["status"] != "SUCCESS"){
				if (dateBeforeSystem($dataH["fdt_aquisition_date"])){
					//Create fixed asset card
				}else{
					throw new CustomException("Periode is closed",3003,"FAILED",[]);				
				}				
			}
			*/			
			$this->validateData($dataH);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		

		try{
			$this->db->trans_start();
			$insertId = $this->trfaprofiles_model->insert($dataH);
			//Detail Posting
			for($i =0;$i<$dataH["fdb_qty"];$i++){
				$data = [
					"fin_fa_profile_id"=>$insertId,
					"fst_fa_profile_code"=>$this->trfaprofilesitems_model->generateCode($dataH["fin_fa_group_id"]),
					"fst_fa_profile_name"=>$dataH["fst_fa_profile_name"],
					"fst_active"=>"A"
				];
				$this->trfaprofilesitems_model->insert($data);
			}
			
			$this->trfaprofiles_model->posting($insertId);
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESSa";
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
        $finFAProfileId = $this->input->post("fin_fa_profile_id");
		try{
            $dataHOld = $this->trfaprofiles_model->getDataHeader($finFAProfileId);
            if ($dataHOld == null){
                show_404();
            }
            $this->trfaprofiles_model->isEditable($finFAProfileId);     
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();

			$preparedData = $this->prepareData();
			
			$dataH = $preparedData["dataH"];

			$dataH["fin_fa_profile_id"] =$dataHOld->fin_fa_profile_id;
			$dataH["fst_fa_profile_no"] =$dataHOld->fst_fa_profile_no;

			$this->validateData($dataH);			
			

			$this->trfaprofiles_model->unposting($finFAProfileId);
			$this->trfaprofiles_model->deleteDetails($finFAProfileId);		
			$this->trfaprofiles_model->update($dataH);

			//Detail Posting
			for($i =0;$i<$dataH["fdb_qty"];$i++){
				$data = [
					"fin_fa_profile_id"=>$finFAProfileId,
					"fst_fa_profile_code"=>$this->trfaprofilesitems_model->generateCode($dataH["fin_fa_group_id"]),
					"fst_fa_profile_name"=>$dataH["fst_fa_profile_name"],
					"fst_active"=>"A"
				];
				$this->trfaprofilesitems_model->insert($data);
			}			

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finFAProfileId;
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
		$fstType = $this->input->post("fst_type");
		$finLPBPurchaseDetailId = null;
		$finFADisposalDetailId = null;		
		if ($fstType == "PURCHASE"){
			$finLPBPurchaseDetailId = $this->input->post("fin_lpbpurchase_detail_id");
			$ssql = "SELECT a.*,b.fin_item_group_id 
				FROM trlpbpurchaseitems a
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				WHERE a.fin_rec_id = ?";
			$qr = $this->db->query($ssql,[$finLPBPurchaseDetailId]);
			$rw = $qr->row();
			if($rw == null){
				throw new CustomException("Invalid purchase detail id",404,"VALIDATION_FORM_FAILED",[]);				
			}			
			$fdbQty = $rw->fdb_qty - $rw->fdb_qty_return;
			$fdcPrice = $rw->fdc_price;
			$fstAccountCode= getLogisticGLConfig($rw->fin_item_group_id,"PERSEDIAAN");						
		}elseif ($fstType == "MUTASI"){

			$finFADisposalDetailId = $this->input->post("fin_fa_disposal_detail_id");
			$ssql = "SELECT * FROM trfadisposalitems where fin_rec_id = ?";
			$qr = $this->db->query($ssql,[$finFADisposalDetailId]);
			$rw = $qr->row();
			if($rw == null){
				throw new CustomException("Invalid disposal detail id",404,"VALIDATION_FORM_FAILED",[]);				
			}
			$fdbQty = 1;
			$fdcPrice = $rw->fdc_aquisition_price - $rw->fdc_deprecated_amount;
			$fstAccountCode= $this->input->post("fst_account_code");
		}else{
			$fstType = null;
			$fdbQty = $this->input->post("fdb_qty");
			$fdcPrice = $this->input->post("fdc_aquisition_price");
			$fstAccountCode= $this->input->post("fst_account_code");
		}


		//Qty & Nilai tidak boleh berubah		
		$dataH = [
			"fin_fa_profile_id"=>$this->input->post("fin_fa_profile_id"),
			"fst_fa_profile_no"=>$this->input->post("fst_fa_profile_no"),
			"fst_type"=>$fstType,			
			"fin_lpbpurchase_detail_id"=>$finLPBPurchaseDetailId,
			"fin_fa_disposal_detail_id"=>$finFADisposalDetailId,
			"fst_fa_profile_name"=>$this->input->post("fst_fa_profile_name"),			
			"fst_notes"=>$this->input->post("fst_notes"),
			"fdb_qty"=>$fdbQty,
			"fin_fa_group_id"=>$this->input->post("fin_fa_group_id"),
			"fst_method"=>$this->input->post("fst_method"),
			"fin_life_time_period"=>$this->input->post("fin_life_time_period"),
			"fst_depre_period"=>$this->input->post("fst_depre_period"),
			"fst_account_code"=>$fstAccountCode,
			"fst_accum_account_code"=>$this->input->post("fst_accum_account_code"),
			"fst_deprecost_account_code"=>$this->input->post("fst_deprecost_account_code"),
			"fdc_aquisition_price"=> $fdcPrice,
			"fdc_residu_value"=>$this->input->post("fdc_residu_value"),
			"fdt_aquisition_date"=>dBDateFormat($this->input->post("fdt_aquisition_date")),
			"fin_pcc_id"=>$this->input->post("fin_pcc_id"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id()
		];

		return[
			"dataH"=>$dataH,
		];
		
	}
	
	private function validateData($dataH){


		$this->form_validation->set_rules($this->trfaprofiles_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}
		

		//Kondisi True
		/**
		 * 1. tgl akuisisi  harus di bawah tanggal mulai system 
		 * 2. tgl posting diatas tgl mulai system tidak boleh sudah di lock
		 * atau
		 * tgl aquisis yang periodenya belum di lock
		 */
		/*
		 $fdtAquisitionDate = $dataH["fdt_aquisition_date"];
		 $aquisitionPeriod = getPeriod($fdtAquisitionDate);

		 if (dateBeforeSystem($fdtAquisitionDate)){
			$lifeTimeMonth = $dataH["fin_life_time_period"];
			if ($dataH["fst_depre_period"] == "year"){
				$lifeTimeMonth = $dataH["fin_life_time_period"] * 12;
			}			
			$lastDeprecatePeriod = addPeriod($aquisitionPeriod,$lifeTimeMonth);			
			if ($dataH["fst_depre_period"] == "year"){
				$fdtLastDeprecate = explode("-",$lastDeprecatePeriod)[0] ."-12-31";
			}else{
				$fdtLastDeprecate = getPeriodDate($lastDeprecatePeriod);
			}
			$lockDate = getDbConfig("lock_transaction_date");
			$lockDate = strtotime($lockDate);
			$akuisisiDate = strtotime($fdtAquisitionDate);
			$lastDeprecateDate = strtotime($fdtLastDeprecate);			

			if ($lockDate > $akuisisiDate && $lockDate < $lastDeprecateDate){
				throw new CustomException("Period yang dilock (" . date("Y-m-d",$lockDate) .") merupakan bagian dari periode penyusutan hingga (". date("Y-m-d",$lastDeprecateDate).") !",3003,"FAILED",[]);
			}

		 }else{
			$resp = dateIsLock($fdtAquisitionDate);
			if ($resp["status"] != "SUCCESS"){
				$dateLock = $resp["data"]["lock_date"];

				throw new CustomException("Period is already closed at $dateLock !",3003,"FAILED",[]);
			}

		 }
		 */

	}

	public function fetch_data($finId){
		$data = $this->trfaprofiles_model->getDataById($finId);	
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
		$finFAProfileId = $this->input->post("fin_fa_profile_id");
		try{
			$dataHOld = $this->trfaprofiles_model->getDataHeader($finFAProfileId);
			if ($dataHOld == null){
				show_404();
			}
			$this->trfaprofiles_model->isEditable($finFAProfileId);     
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}		



		try{
			$this->db->trans_start();
			$this->trfaprofiles_model->unposting($finFAProfileId);
			$this->trfaprofiles_model->delete($finFAProfileId,true,null);					

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


	public function ajxListLPBPurchase(){
		$term = $this->input->get("term");

		$ssql = "SELECT DISTINCT c.fin_lpbpurchase_id,c.fst_lpbpurchase_no FROM trlpbpurchaseitems a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN trlpbpurchase c on a.fin_lpbpurchase_id = c.fin_lpbpurchase_id
			WHERE a.fbl_fa_profiles = false AND b.fin_item_type_id = 6 and c.fst_lpbpurchase_no like ?";

		$qr = $this->db->query($ssql,["%$term%"]);
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$qr->result()
		]);
	}


	public function ajxListLPBPurchaseItems($finLPBPurchaseId){
		$term = $this->input->get("term");		
		$ssql = "SELECT a.fin_rec_id as fin_lpbpurchase_detail_id,a.fst_custom_item_name,
			a.fdb_qty,a.fdb_qty_return,a.fdc_price,
			b.fin_item_group_id			
			FROM trlpbpurchaseitems a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN trlpbpurchase c on a.fin_lpbpurchase_id = c.fin_lpbpurchase_id
			WHERE a.fbl_fa_profiles = false AND b.fin_item_type_id = 6 and a.fst_custom_item_name like ? and a.fin_lpbpurchase_id = ?";

		$qr = $this->db->query($ssql,["%$term%",$finLPBPurchaseId]);
		$rs = $qr->result();
		for($i=0;$i < sizeof($rs);$i++){
			$rw = $rs[$i];
			$rw->fst_account_code = getLogisticGLConfig($rw->fin_item_group_id,"PERSEDIAAN");
			$rs[$i] = $rw;
		}
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$rs,
		]);
	}

	public function ajxListMutasi(){
		$term = $this->input->get("term");
		$ssql = "SELECT distinct b.fin_fa_disposal_id,b.fst_fa_disposal_no FROM trfadisposalitems a 
			INNER JOIN trfadisposal b on a.fin_fa_disposal_id = b.fin_fa_disposal_id
			where a.fbl_fa_profiles = 0 and b.fst_fa_disposal_no like ? and b.fst_disposal_type ='MUTASI' and b.fin_to_branch_id = ?";
		$qr = $this->db->query($ssql,["%$term%",$this->aauth->get_active_branch_id()]);
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$qr->result()
		]);
	}

	public function ajxListMutasiItems($finFADisposalId){
		$term = $this->input->get("term");
		$ssql = "SELECT a.fin_rec_id as fin_fa_disposal_detail_id,
			(a.fdc_aquisition_price -a.fdc_deprecated_amount) as fdc_aquisition_price,			
			b.fst_fa_profile_code,b.fst_fa_profile_name			
			FROM trfadisposalitems a 
			INNER JOIN trfaprofilesitems b on a.fin_fa_profile_detail_id = b.fin_rec_id
			where a.fin_fa_disposal_id = ?" ;
		$qr = $this->db->query($ssql,[$finFADisposalId]);
		//var_dump($this->db->last_query());
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$qr->result()
		]);

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
	

}    
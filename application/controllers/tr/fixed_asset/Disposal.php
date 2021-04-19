<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Disposal extends MY_Controller{
	public $menuName="fixed_asset_disposal";
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model("trfadisposal_model");		
		$this->load->model("trfadisposalitems_model");	
		$this->load->model("trfaprofilesitems_model");				
		$this->load->model("msbranches_model");
        

	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Disposal Fixed Asset";
		$this->list['list_name'] = "Disposal Fixed Asset List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/fixed_asset/disposal/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/fixed_asset/disposal/fetch_list_data';
		$this->list['arrSearch'] = [
            'fst_fa_disposal_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Fixed Asset', 'link' => '#', 'icon' => ''],
			['title' => 'Disposal', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_fa_disposal_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_fa_disposal_no'],
            ['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_fa_disposal_datetime'],
			['title' => 'Type', 'width' => '50px', 'data' => 'fst_disposal_type'],
			['title' => 'Notes', 'width' => '50px', 'data' => 'fst_notes'],
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/fixed_asset/disposal/edit/' + row.fin_fa_disposal_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				select * from trfadisposal where fst_active != 'D'
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


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Disposal Fixed Asset") : lang("Update Disposal Fixed Asset");
		$data["fin_fa_disposal_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;			

		$fstFADisposalNo = $this->trfadisposal_model->generateTransactionNo();
		$data["fst_fa_disposal_no"] = $fstFADisposalNo;	
		$page_content = $this->parser->parse('pages/tr/fixed_asset/disposal/form', $data, true);
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
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details =$dataPrepared["details"];

			unset($dataH["fin_fa_disposal_id"]);
			$dataH["fst_fa_disposal_no"] = $this->trfadisposal_model->generateTransactionNo();
			$resp = dateIsLock($dataH["fdt_fa_disposal_datetime"]);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
						
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
			$insertId = $this->trfadisposal_model->insert($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_fa_disposal_id"] = $insertId;
				$this->trfadisposalitems_model->insert($dataD);
			}
			
			$this->trfadisposal_model->posting($insertId);

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
        $finFADisposalId = $this->input->post("fin_fa_disposal_id");
		try{
            $dataHOld = $this->trfadisposal_model->getDataHeader($finFADisposalId);
            if ($dataHOld == null){
                show_404();
            }
			
			$resp = dateIsLock($dataHOld->fdt_fa_disposal_datetime);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}

            $this->trfadisposal_model->isEditable($finFADisposalId);
                        
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
			$details = $preparedData["details"];
			
			$dataH["fin_fa_disposal_id"] = $finFADisposalId;
			$dataH["fst_fa_disposal_no"] = $dataHOld->fst_fa_disposal_no;
			


			$this->db->trans_start();
			

			$this->trfadisposal_model->unposting($finFADisposalId);
			$this->trfadisposal_model->deleteDetail($finFADisposalId);
			
			$this->validateData($dataH,$details);

			$this->trfadisposal_model->update($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_fa_disposal_id"] = $finFADisposalId;
				$this->trfadisposalitems_model->insert($dataD);
			}
			$this->trfadisposal_model->posting($finFADisposalId);


			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finFADisposalId;
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
		$disposalType = $this->input->post("fst_disposal_type");

		$fstDestroyBA = null;
		$finRelationId = null;
		$fdcPpnPercent = 0;
		$finToBranchId = null;
		$fstSellCurrCode = null;
		$fstSellExchangeRate = 1;
		$fdcSellSubTotal =0;
		$fdcSellTotal =0;

		if($disposalType == "DESTROY"){
			$fstDestroyBA = $this->input->post("fst_destroy_ba");
		}else if($disposalType == "JUAL"){
			$finRelationId = $this->input->post("fin_customer_id");
			$fdcPpnPercent = $this->input->post("fdc_ppn_percent");		
			$fstSellCurrCode = $this->input->post("fst_sell_curr_code");
			$fstSellExchangeRate = parseNumber($this->input->post("fdc_sell_exchange_rate_idr"));
		

		}else if($disposalType == "MUTASI"){
			$finToBranchId = $this->input->post("fin_to_branch_id");
		}

		$dataH = [
			"fin_fa_disposal_id"=>$this->input->post("fin_fa_mutasiout_id"),
			"fst_fa_disposal_no"=>$this->input->post("fst_fa_mutasiout_no"),
			"fdt_fa_disposal_datetime"=>dBDateTimeFormat($this->input->post("fdt_fa_disposal_datetime")),
			"fst_disposal_type"=>$disposalType,
			"fst_sell_curr_code"=>$fstSellCurrCode,
			"fdc_sell_exchange_rate_idr"=>$fstSellExchangeRate,
			"fst_destroy_ba"=>$fstDestroyBA,
			"fin_customer_id"=>$finRelationId,			
			"fdc_ppn_percent"=>$fdcPpnPercent,						
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fin_to_branch_id"=>$this->input->post("fin_to_branch_id"),
            "fst_notes"=>$this->input->post("fst_notes"),
            "fst_active"=>'A',			
		];

		$dataDetails = $this->input->post("details");
		$dataDetails = json_decode($dataDetails);		
		$details = [];
		$totalNilaiJual = 0;
		foreach($dataDetails as $detail){
			$aquisitionPrice = 0;
			$profileInfo = $this->trfaprofilesitems_model->getInfoById($detail->fin_fa_profile_detail_id);
			if ($profileInfo == null){
				throw new CustomException("Invalid profile detail id ". $detail->fin_fa_profile_detail_id , 404,"FAILED",[]);
			}

			$aquisitionPrice = $profileInfo->fdc_aquisition_price;
			$depreAmount = $profileInfo->fdc_depre_amount;
			if ($dataH["fst_disposal_type"] == "JUAL"){
				$sellPrice = $detail->fdc_sell_price;
				$totalNilaiJual += $sellPrice;
			}else{
				$sellPrice = 0;
			}

			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_fa_profile_detail_id"=>$detail->fin_fa_profile_detail_id,
				"fdc_aquisition_price"=>$aquisitionPrice,
				"fdc_deprecated_amount"=>$depreAmount,
				"fdc_sell_price"=>$sellPrice,
				"fst_notes"=>$detail->fst_notes,
			];
			$details[]=(object) $tmp;
		}


		if($disposalType == "JUAL"){
			$dataH["fdc_sell_subtotal"] = $totalNilaiJual;			
			$dataH["fdc_ppn_amount"] = $totalNilaiJual * ($dataH["fdc_ppn_percent"] /100);
			$dataH["fdc_sell_total"] = $dataH["fdc_sell_subtotal"] + $dataH["fdc_ppn_amount"];

		}else{
			$dataH["fdc_sell_subtotal"] = 0;			
			$dataH["fdc_ppn_amount"] = 0;
			$dataH["fdc_sell_total"] = 0;

		}
		
		return[
			"dataH"=>$dataH,
			"details"=>$details,
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trfadisposal_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		//Validate all Detail is valid
		foreach($details as $dataD){
			$ssql ="SELECT * FROM trfaprofilesitems where fin_rec_id = ? and fbl_disposal = 0 and fst_active ='A'";
			$qr = $this->db->query($ssql,[$dataD->fin_fa_profile_detail_id]);
			$rw = $qr->row();
			if ($rw == null){
				throw new CustomException("ValidateData - Invalid profile detail id " .$dataD->fin_fa_profile_detail_id ,404,"FAILED",[]);
			}
		}			
	}

	public function fetch_data($finId){
		$data = $this->trfadisposal_model->getDataById($finId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}


	public function delete($finFADisposalId){
		parent::delete($finFADisposalId);
		try{
            $dataHOld = $this->trfadisposal_model->getDataHeader($finFADisposalId);
            if ($dataHOld == null){
                show_404();
			}
			$resp = dateIsLock($dataHOld->fdt_fa_disposal_datetime);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
            $this->trfadisposal_model->isEditable($finFADisposalId);                        
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();			
			$this->trfadisposal_model->unposting($finFADisposalId);			
			$resp = $this->trfadisposal_model->delete($finFADisposalId,true,null);	

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


	public function ajxListFixedAsset(){

		//$finWarehouseId =$this->input->get("fin_warehouse_id");
		$finBranchId = $this->aauth->get_active_branch_id();

		$ssql ="SELECT a.fin_rec_id as fin_fa_profile_rec_id,a.fst_fa_profile_code,a.fst_fa_profile_name,
			b.fdc_aquisition_price,IFNULL(c.fdc_depre_amount,0) AS fdc_depre_amount 
			FROM trfaprofilesitems a 
			INNER JOIN trfaprofiles b on a.fin_fa_profile_id = b.fin_fa_profile_id
			LEFT JOIN (
				SELECT fst_fa_profile_code,sum(fdc_depre_amount) as fdc_depre_amount from trfadeprecard 
				group by fst_fa_profile_code
			) c on a.fst_fa_profile_code = c.fst_fa_profile_code 
			WHERE a.fbl_disposal = 0  AND b.fst_active ='A' AND b.fin_branch_id = ? AND 
			(a.fst_fa_profile_code like ? OR a.fst_fa_profile_name like ?)";
		$term ="%".$this->input->get("term") ."%";

		$qr = $this->db->query($ssql,[$finBranchId,$term,$term]);
		//var_dump($this->db->last_query());
		
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$qr->result()
		]);
	}

	public function ajxListRelation(){
		$term = $this->input->get("term");
		$ssql ="SELECT fin_relation_id,fst_relation_name FROM msrelations where fst_active !='D' and fst_relation_name like ?";
		$qr = $this->db->query($ssql,["%$term%"]);
		$rs = $qr->result();
		return $this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$rs,
		]);
	}
	

}    
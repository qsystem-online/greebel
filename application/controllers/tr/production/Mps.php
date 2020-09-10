<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mps extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("trassembling_model");
		$this->load->model("trassemblingitems_model");
		$this->load->model("mswarehouse_model");	
		
	}

	public function index(){

		$this->load->library('menus');
		$this->list['page_name'] = "Assembling / Diassembling";
		$this->list['list_name'] = "Assembling / Diassembling List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/fixed_asset/disposal/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/assembling/fetch_list_data';
		$this->list['arrSearch'] = [
            'fst_fa_disposal_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Fixed Asset', 'link' => '#', 'icon' => ''],
			['title' => 'Disposal', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_assembling_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_assembling_no'],
            ['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_assembling_datetime'],
			['title' => 'Type', 'width' => '50px', 'data' => 'fst_type'],
			['title' => 'Notes', 'width' => '50px', 'data' => 'fst_notes'],
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/assembling/edit/' + row.fin_assembling_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				select * from trassembling where fst_active != 'D'
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
		$this->openForm("ADD", 0);
	}
	
	public function edit($finId){
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
		$data["title"] = $mode == "ADD" ? lang("Add Master Production Schedule") : lang("Update Master Production Schedule");
		$data["fin_assembling_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;			

		$fstAssemblingNo = $this->trassembling_model->generateTransactionNo();
		$data["fst_assembling_no"] = $fstAssemblingNo;	
		$page_content = $this->parser->parse('pages/tr/production/mps/form', $data, true);
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
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details =$dataPrepared["details"];

			unset($dataH["fin_assembling_id"]);
			$dataH["fst_assembling_no"] = $this->trassembling_model->generateTransactionNo();
			$resp = dateIsLock($dataH["fdt_assembling_datetime"]);
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
			$insertId = $this->trassembling_model->insert($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_assembling_id"] = $insertId;
				$this->trassemblingitems_model->insert($dataD);
			}
			
			//$this->trfadisposal_model->posting($insertId);
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
        $finAssemblingId = $this->input->post("fin_assembling_id");
		try{
            $dataHOld = $this->trassembling_model->getDataHeader($finAssemblingId);
            if ($dataHOld == null){
                show_404();
            }
			
			$resp = dateIsLock($dataHOld->fdt_assembling_datetime);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}

            $this->trassembling_model->isEditable($finAssemblingId);
                        
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
			
			$dataH["fin_assembling_id"] = $finAssemblingId;
			$dataH["fst_assembling_no"] = $dataHOld->fst_assembling_no;
			


			$this->db->trans_start();
			

			//$this->trfadisposal_model->unposting($finAssemblingId);
			$this->trassembling_model->deleteDetail($finAssemblingId);
			
			$this->validateData($dataH,$details);

			$this->trassembling_model->update($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_assembling_id"] = $finAssemblingId;
				$this->trassemblingitems_model->insert($dataD);
			}
			//$this->trassembling_model->posting($finFADisposalId);


			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finAssemblingId;
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
			"fin_assembling_id"=>$this->input->post("fin_assembling_id"),
			"fst_assembling_no"=>$this->input->post("fst_assembling_no"),
			"fdt_assembling_datetime"=>dBDateTimeFormat($this->input->post("fdt_assembling_datetime")),
			"fst_type"=>$this->input->post("fst_type"),
			"fin_item_id"=>$this->input->post("fin_item_id"),
			"fst_unit"=>$this->input->post("fst_unit"),
			"fdb_qty"=>$this->input->post("fdb_qty"),
			"fin_source_warehouse_id"=>$this->input->post("fin_source_warehouse_id"),
			"fin_target_warehouse_id"=>$this->input->post("fin_target_warehouse_id"),
			"fdc_hpp_header"=>$this->input->post("fdc_hpp_header"),
			"fst_notes"=>$this->input->post("fst_notes"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
            "fst_active"=>'A',			
		];

		if ($dataH["fst_type"] != "ASSEMBLING"){
			$dataH["fdc_hpp_header"] = 0;
		}

		$dataDetails = $this->input->post("details");
		$dataDetails = json_decode($dataDetails);		
		$details = [];
		$totalNilaiJual = 0;
		$ttlHPPD = 0;
		foreach($dataDetails as $detail){
			$hpp = $detail->fdc_hpp;
			$ttlHPPD += $hpp;
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
				"fdb_qty"=>$detail->fdb_qty,
				"fdc_hpp"=>$hpp,
				"fst_notes"=>$detail->fst_notes,
			];
			$details[]=(object) $tmp;
		}

		/** tip asembling total hpp header dihitung dari detail, kalau total 0 ambil dari input user 
		 * deasembling header hiutng hpp pada saat barang keluar, hpp barang diambil dari perhitungan di web
		*/
		if ($dataH["fst_type"] == "ASSEMBLING" && $ttlHPPD > 0){
			$dataH["fdc_hpp_header"] = $ttlHPPD;
		}

		return[
			"dataH"=>$dataH,
			"details"=>$details,
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trassembling_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}			
	}

	public function fetch_data($finId){
		$data = $this->trassembling_model->getDataById($finId);	
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

		try{
            $dataHOld = $this->trassembling_model->getDataHeader($finId);
            if ($dataHOld == null){
                show_404();
			}
			$resp = dateIsLock($dataHOld->fdt_assembling_datetime);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
            $this->trassembling_model->isEditable($finId);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();			
			$resp = $this->trassembling_model->delete($finId,true,null);	
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
		$this->load->model("msitems_model");
		$term =  $this->input->get("term");
		$list = $this->msitems_model->getItemList($term);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$list
		]);
	}

	public function ajxGetUnits($finItemId){
		$this->load->model("msitemunitdetails_model");
		$list = $this->msitemunitdetails_model->getItemListUnits($finItemId);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$list,
		]);
	}	

	public function ajxGetTotalHPP(){
		$finItemId = $this->input->get("fin_item_id");
		$fstUnit = $this->input->get("fst_unit");
		$fdbQty =  $this->input->get("fdb_qty");
		$finWarehouseId  = $this->input->get("fin_warehouse_id");

		$this->load->model("trinventory_model");
		$totalHpp = $this->trinventory_model->getTotalHPP($finItemId,$fstUnit,$fdbQty,$finWarehouseId);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>[
				"HPP"=>$totalHpp,
			]
		]);
	}
}    
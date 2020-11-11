<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rmout_return extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('trrmoutreturn_model');		
		$this->load->model('trrmoutreturnitems_model');		
		$this->load->model('mswarehouse_model');		
		
	}

	public function index(){

		$this->load->library('menus');
		$this->list['page_name'] = "RM-OUT Produksi";
		$this->list['list_name'] = "RM-OUT Produksi List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/rmout_prod/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/rmout_prod/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_rmout_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'RM-OUT Produksi', 'link' => null, 'icon' => '']			
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_rmout_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_rmout_no'],
			['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_rmout_datetime'],
			['title' => 'Type', 'width' => '50px', 'data' => 'fst_rmout_type'],
			['title' => 'Warehouse', 'width' => '50px', 'data' => 'fst_warehouse_name'],			
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/rmout_prod/edit/' + row.fin_rmout_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				select a.*,b.fst_warehouse_name from trrmout a 
				inner join mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id 
				where a.fst_active != 'D' and a.fin_pagp_id IS NOT NULL
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
		$data["title"] = $mode == "ADD" ? lang("Add RM-Out Produksi") : lang("Update RM-Out Produksi");
		$data["fin_rmout_return_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;					

		$fstRMOutReturnNo = $this->trrmoutreturn_model->generateTransactionNo();
		$data["fst_rmout_return_no"] = $fstRMOutReturnNo;	
		$page_content = $this->parser->parse('pages/tr/production/rmout_return/form', $data, true);
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
			$resp = dateIsLock($dataH["fdt_rmout_datetime"]);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
			unset($dataH["fin_rmout_id"]);
			$dataH["fst_rmout_no"] = $this->trrmout_model->generateProductionTransactionNo();		
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
			$insertId = $this->trrmout_model->insert($dataH);
			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_rmout_id"] = $insertId;
				$this->trrmoutitems_model->insert($dataD);
			}

			$this->trrmout_model->posting($insertId);
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
		$finRMOutId = $this->input->post("fin_rmout_id");
		try{
			$dataHOld = $this->trrmout_model->getDataHeader($finRMOutId);
			if ($dataHOld == null){
				show_404();
			}			
			$resp = dateIsLock($dataHOld->fdt_rmout_datetime);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
			$this->trrmout_model->isEditable($finRMOutId);
						
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
			$dataH["fin_rmout_id"] = $finRMOutId;
			$dataH["fst_rmout_no"] = $dataHOld->fst_rmout_no;
			


			$this->db->trans_start();

			$this->trrmout_model->unposting($finRMOutId);
			
			$this->trrmout_model->deleteDetail($finRMOutId);
			
			$this->validateData($dataH,$details);
			$this->trrmout_model->update($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_rmout_id"] = $finRMOutId;
				$this->trrmoutitems_model->insert($dataD);
			}

			
			$this->trrmout_model->posting($finRMOutId);
			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finRMOutId;
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

		$this->load->model("trinventory_model");
		//'detail' => string '[{"fin_rec_id":0,"fin_item_id":"5","fst_item_code":"ITEM-0001","fst_item_name":"Item with batch and serial","fbl_is_batch_number":"1","fbl_is_serial_number":"1","fst_basic_unit":"PCS","fst_unit":"PCS","fbl_is_basic_unit":"1","fdc_conv_to_basic_unit":"1.00","fdb_qty":"1","fst_batch_number":"BATCH-20200630-001","fst_serial_number_list":["ITM01-004"]}]'
		
		$fdtRMOutDatetime = dBDateTimeFormat($this->input->post("fdt_rmout_datetime"));

		$ssql = "SELECT b.fin_to_warehouse_id 
			FROM trmagconfirm a 
			inner join trmag b on a.fin_mag_id = b.fin_mag_id
			WHERE a.fin_mag_confirm_id = ? ";
		
		$qr = $this->db->query($ssql,[$this->input->post("fin_pagp_id")]);
		$rw = $qr->row();
		if ($rw == null){
			throw new CustomException("[Data Corrupted..] PAG ID No MAG",3003,"FAILED",[]);
		}


		$dataH = [
			"fin_rmout_id"=>$this->input->post("fin_rmout_id"),
			"fst_rmout_no"=>$this->input->post("fst_rmout_no"),
			"fdt_rmout_datetime"=>$fdtRMOutDatetime,
			//"fst_rmout_type" =>$this->input->post("fst_rmout_type"),
			"fin_pagp_id"=>$this->input->post("fin_pagp_id"),
			"fin_warehouse_id"=>$rw->fin_to_warehouse_id,
			"fin_wobatchno_id"=>$this->input->post("fin_wobatchno_id"),
			"fst_memo"=>$this->input->post("fst_memo"),
			"fst_active"=>'A',			
			"fin_branch_id"=>$this->aauth->get_active_branch_id()
		];		

		$postDetails = $this->input->post("detail");
		$postDetails = json_decode($postDetails);		
		$details = [];
		foreach($postDetails as $detail){			
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
				"fdb_qty"=>$detail->fdb_qty,
				"fdc_hpp"=>$this->trinventory_model->getLastHPP($detail->fin_item_id,$dataH["fin_warehouse_id"]),
				"fst_batch_number"=>$detail->fst_batch_number,
				"fst_serial_number_list"=>json_encode($detail->fst_serial_number_list),
				"fst_active"=>"A"
			];
			$details[]=(object) $tmp;
		}

		return[
			"dataH"=>$dataH,
			"details"=>$details
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trrmout_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}		
	}

	public function fetch_data($finId){
		$data = $this->trrmout_model->getDataProductionById($finId);	
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
    

    public function ajxGetWOList(){
		$term = $this->input->get("term");
		$term = "%$term%";
		$ssql = "SELECT fin_wo_id,fst_wo_no FROM trwo where fbl_closed = 0 and fst_active ='A' and fst_wo_no like ?";
		$qr = $this->db->query($ssql,[$term]);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
    }

    public function ajxGetBatchWOList(){
		$term = $this->input->get("term");
		$term = "%$term%";

		$finWOId = $this->input->get("fin_wo_id");

		$ssql = "SELECT a.fin_wobatchno_id,a.fst_wobatchno_no FROM trwobatchno a
			WHERE a.fin_wo_id = ? and a.fst_wobatchno_no like ? and a.fst_active = 'A' and a.fbl_closed = 0";
		
		$qr = $this->db->query($ssql,[$finWOId,$term]);
		$rs = $qr->result();

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
    }
    
    public function ajxGetItemList(){
		//$term = $this->input->get("term");
		//$term = "%$term%";

        $finWOId = $this->input->get("fin_wo_id");
        $finWOBatchNoId = $this->input->get("fin_wobatchno_id");
        
        $ssql = "SELECT distinct a.fin_item_id,c.fst_item_code,c.fst_item_name,c.fbl_is_batch_number,c.fbl_is_serial_number  FROM trrmoutitems a
            INNER JOIN trrmout b on a.fin_rmout_id = b.fin_rmout_id 
            INNER JOIN msitems c on a.fin_item_id = c.fin_item_id
            where fin_wo_id = ? and fin_wobatchno_id = ? and b.fst_active ='A'";

		
        $qr = $this->db->query($ssql,[$finWOId,$finWOBatchNoId]);            
		$rs = $qr->result();

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);

    }
    
    public function ajxGetUnits(){
        $finItemId = $this->input->get("fin_item_id");
        $ssql = "SELECT fst_unit,fbl_is_basic_unit,fdc_conv_to_basic_unit FROM msitemunitdetails where fin_item_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$finItemId]);
        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
    }
    


	
}    
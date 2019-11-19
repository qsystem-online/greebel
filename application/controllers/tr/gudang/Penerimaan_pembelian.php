<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_pembelian extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trlpbgudang_model');
    }

	public function index(){

		$this->load->library('menus');
        $this->list['page_name'] = "Gudang - Penerimaan Pembelian";
        $this->list['list_name'] = "Penerimaan Pembelian List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/gudang/penerimaan_pembelian/fetch_list_data';
        $this->list['arrSearch'] = [
            'fst_lpbgudang_no' => 'No LPB Pembelian'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Gudang', 'link' => '#', 'icon' => ''],
            ['title' => 'Penerimaan Pembelian', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. LPB Pembelian', 'width' => '100px', 'data' => 'fin_lpbgudang_id'],
            ['title' => 'No. LPB Pembelian', 'width' => '100px', 'data' => 'fst_lpbgudang_no'],
            ['title' => 'Tanggal', 'width' => '100px', 'data' => 'fdt_lpbgudang_datetime'],
            ['title' => 'Purchase Order No.', 'width' => '100px', 'data' => 'fst_po_no'],
			['title' => 'Supplier', 'width' => '100px', 'data' => 'fst_supplier_name'],
			['title' => 'Memo', 'width' => '200px', 'data' => 'fst_memo'],
			['title' => 'Action', 'width' => '100px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/gudang/penerimaan_pembelian/edit/' + row.fin_lpbgudang_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/gudang/penerimaan_pembelian/listjs', [], true);

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
			SELECT a.*,b.fst_po_no,b.fin_supplier_id,c.fst_relation_name as fst_supplier_name FROM trlpbgudang a 
			INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
			INNER JOIN msrelations c on b.fin_supplier_id = c.fin_relation_id 
			) a");

        $selectFields = "a.fin_lpbgudang_id,a.fst_lpbgudang_no,a.fdt_lpbgudang_datetime,a.fst_po_no,a.fst_supplier_name,a.fst_memo";
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


    private function openForm($mode = "ADD", $finLPBGudangId = 0){
        $this->load->library("menus");		
        //$this->load->model("glaccounts_model");		

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Penerimaan Pembelian Barang") : lang("Update Penerimaan Pembelian Barang");
		$data["fin_lpbgudang_id"] = $finLPBGudangId;
		$data["mdlEditForm"] = $edit_modal;
		
		
		if($mode == 'ADD'){
			$data["fst_lpbgudang_no"]=$this->trlpbgudang_model->generateLPBGudangNo(); 
		}else if($mode == 'EDIT'){
			$data["fst_lpbgudang_no"]="";	

			/*
			$cbPayment = $this->trcbpayment_model->getDataById($finCBPaymentId);	
			
			$data["initData"] = $this->trcbpayment_model->getDataById($finCBPaymentId);	
			if ($cbPayment == null){
				show_404();
			}		
			$data["initData"] = $cbPayment;
			*/
        }        
		
		$page_content = $this->parser->parse('pages/tr/gudang/penerimaan_pembelian/form', $data, true);
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
		$this->load->model("trlpbgudangitems_model");
		$this->load->model("msitems_model");
		/*
		__c9da2c3066cf64f25a59677d1666d7ac: cb99194f88c06cda68f79c16a2d85a4a
		fin_lpbgudang_id: 0
		fst_lpbgudang_no: GUD/JKT/2019/10/00001
		fdt_lpbgudang_datetime: 11-10-2019 13:52:45
		fin_po_id: 6
		fin_warehouse_id: 2
		fst_memo: 
		details: [{"fin_rec_id":0,"fin_po_detail_id":"24","fin_item_id":"1","fst_item_code":"AB1230","fst_custom_item_name":"Greebel Artists Crayon Oil Pastel","fst_unit":"BOX","fdb_qty_po":20,"fdb_qty":20,"fdc_m3":null}]
		*/


		//PREPARE DATA
		$this->form_validation->set_rules($this->trlpbgudang_model->getRules("ADD", 0));

		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Header";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}
		
		$fdt_lpbgudang_datetime = dBDateTimeFormat($this->input->post("fdt_lpbgudang_datetime"));
		$fst_lpbgudang_no = $this->trlpbgudang_model->generateLPBGudangNo($fdt_lpbgudang_datetime);

		$dataH = [
			"fst_lpbgudang_no"=>$fst_lpbgudang_no,
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),
			"fdt_lpbgudang_datetime"=>$fdt_lpbgudang_datetime,
			"fin_po_id"=>$this->input->post("fin_po_id"),
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',			
		];


		$details = $this->input->post("details");
		$details = json_decode($details);
		$this->form_validation->set_rules($this->trlpbgudangitems_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			// Validate item Details
			$this->form_validation->set_data((array)$details[$i]);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Error Validation Forms");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;				
				$this->json_output();
				return;	
			}

			$itemInfo = $this->msitems_model->geSimpletDataById($item->fin_item_id);

			if($itemInfo->fbl_is_batch_number && $item->fst_batch_no == "" ){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = sprintf("%s harus memiliki batch number",$item->fst_custom_item_name);
				$this->json_output();
				return;	
			}
			if($itemInfo->fbl_is_serial_number){				
				//$arrSerial = json_decode($item->arr_serial);
				$arrSerial = $item->arr_serial;
				if($arrSerial == null){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = sprintf("%s harus memiliki serial number",$item->fst_custom_item_name);
					$this->json_output();
					return;	
				}
				if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($item->fin_item_id,$item->fdb_qty,$item->fst_unit) ){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = sprintf("total serial %s harus sesuai dengan total qty (%u)",$item->fst_custom_item_name,$item->fdb_qty);
					$this->json_output();
					return;	
				}

			}
		}

		$this->db->trans_start();
		$insertId = $this->trlpbgudang_model->insert($dataH);

		//Insert Data Detail Transaksi
		foreach ($details as $detail) {		
			$detail = (array) $detail;
			$detail["fin_lpbgudang_id"] = $insertId;
			$detail["fst_batch_number"] = $detail["fst_batch_no"];
			$detail["fst_serial_number_list"] = json_encode($detail["arr_serial"]);
			$detail["fst_active"] = "A";					
			$this->trlpbgudangitems_model->insert($detail);			
		}

		
		$result = $this->trlpbgudang_model->posting($insertId);

		if ($result["status"] != "SUCCESS"){
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = $result["message"];
			if (isset( $result["data"])){
				$this->ajxResp["data"] = $result["data"];
			}
			
			
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}


		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();

	}

	public function ajx_edit_save(){			

		$this->load->model("trlpbgudangitems_model");
		$this->load->model("msitems_model");
		/*
		__c9da2c3066cf64f25a59677d1666d7ac: cb99194f88c06cda68f79c16a2d85a4a
		fin_lpbgudang_id: 0
		fst_lpbgudang_no: GUD/JKT/2019/10/00001
		fdt_lpbgudang_datetime: 11-10-2019 13:52:45
		fin_po_id: 6
		fin_warehouse_id: 2
		fst_memo: 
		details: [{"fin_rec_id":0,"fin_po_detail_id":"24","fin_item_id":"1","fst_item_code":"AB1230","fst_custom_item_name":"Greebel Artists Crayon Oil Pastel","fst_unit":"BOX","fdb_qty_po":20,"fdb_qty":20,"fdc_m3":null}]
		*/



		$this->form_validation->set_rules($this->trlpbgudang_model->getRules("ADD", 0));

		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Header";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $_POST;
			$this->json_output();
			return;
		}
		
		$fdt_lpbgudang_datetime = dBDateTimeFormat($this->input->post("fdt_lpbgudang_datetime"));
		$fst_lpbgudang_no = $this->trlpbgudang_model->generateLPBGudangNo($fdt_lpbgudang_datetime);

		$dataH = [
			"fin_lpbgudang_id"=>$this->input->post("fin_lpbgudang_id"),
			"fst_lpbgudang_no"=>$fst_lpbgudang_no,
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),
			"fdt_lpbgudang_datetime"=>$fdt_lpbgudang_datetime,
			"fin_po_id"=>$this->input->post("fin_po_id"),
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',	
			"fin_user_id_request_by"=>$this->input->post("fin_user_id_request_by"),
			"fst_edit_notes"=>$this->input->post("fst_edit_notes"),
		];


		$details = $this->input->post("details");
		$details = json_decode($details);
		$this->form_validation->set_rules($this->trlpbgudangitems_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			// Validate item Details
			$this->form_validation->set_data((array)$details[$i]);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Error Validation Forms");
				$this->ajxResp["request_data"] = $dataH;
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;				
				$this->json_output();
				return;	
			}

			$itemInfo = $this->msitems_model->geSimpletDataById($item->fin_item_id);
			if($itemInfo->fbl_is_batch_number && $item->fst_batch_no == "" ){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = sprintf("%s harus memiliki batch number",$item->fst_custom_item_name);
				$this->json_output();
				return;	
			}
			if($itemInfo->fbl_is_serial_number){
				//$arrSerial = json_decode($item->arr_serial);
				$arrSerial = $item->arr_serial;
				if($arrSerial == null){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = sprintf("%s harus memiliki serial number",$item->fst_custom_item_name);
					$this->json_output();
					return;	
				}
				if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($item->fin_item_id,$item->fdb_qty,$item->fst_unit) ){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = sprintf("total serial %s harus sesuai dengan total qty (%u)",$item->fst_custom_item_name,$item->fdb_qty);
					$this->json_output();
					return;	
				}

			}

		}		

		$this->db->trans_start();

		$result = $this->trlpbgudang_model->unposting($dataH["fin_lpbgudang_id"]);
		if ($result["status"] != "SUCCESS"){
			return $result;
		}

		$this->trlpbgudang_model->update($dataH);

		//Insert Data Detail Transaksi
		foreach ($details as $detail) {		
			$detail = (array) $detail;
			$detail["fin_lpbgudang_id"] = $dataH["fin_lpbgudang_id"];
			$detail["fst_batch_number"] = $detail["fst_batch_no"];
			$detail["fst_serial_number_list"] = json_encode($detail["arr_serial"]);
			$detail["fst_active"] = "A";					
			$this->trlpbgudangitems_model->insert($detail);			
		}

		$result = $this->trlpbgudang_model->posting($dataH["fin_lpbgudang_id"]);

		if ($result["status"] != "SUCCESS"){
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = $result["message"];
			if(isset($result["data"])){
				$this->ajxResp["data"] = $result["data"];
			}

			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}


		$this->db->trans_complete();


		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $dataH["fin_lpbgudang_id"];
		$this->json_output();

	}

	public function fetch_data($finLPBGudangId){
		$data = $this->trlpbgudang_model->getDataById($finLPBGudangId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finLPBGudangId){

		$this->db->trans_start();
		$data =[
			"fin_user_id_request_by"=>$this->input->post("fin_user_id_request_by"),
			"fst_edit_notes"=>$this->input->post("fst_edit_notes"),
		];
		
		$resp = $this->trlpbgudang_model->delete($finLPBGudangId,true,$data);	

		if ($resp["status"] != "SUCCESS"){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}else{
			$this->db->trans_complete();
		}

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		//$this->ajxResp["data"]["insert_id"] = $dataH["fin_lpbgudang_id"];
		$this->json_output();
	}



}    
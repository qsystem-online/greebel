<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_pembelian extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model('msrelations_model');	
		$this->load->model('trlpbgudang_model');
		$this->load->model("trlpbgudangitems_model");
		$this->load->model('mswarehouse_model');
		$this->load->model('trpo_model');
		$this->load->model('trsalesreturn_model');		
		$this->load->model('trassembling_model');		
		$this->load->model("msitems_model");		
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
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_lpbgudang_id'],
            ['title' => 'No. LPB', 'width' => '60px', 'data' => 'fst_lpbgudang_no'],
			['title' => 'Tanggal', 'width' => '50px', 'data' => 'fdt_lpbgudang_datetime'],
			['title' => 'LPB Type', 'width' => '50px', 'data' => 'fst_lpb_type',
				'render'=>"function(data,type,row){					
					return data;
				}"
			],
			['title' => 'Reff No.', 'width' => '60px', 'data' => 'fst_trans_no2'],
			['title' => 'Relation', 'width' => '100px', 'data' => 'fst_relation_name'],
			['title' => 'Memo', 'width' => '100px', 'data' => 'fst_memo'],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/gudang/penerimaan_pembelian/edit/' + row.fin_lpbgudang_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
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
				SELECT a.*,b.fin_supplier_id,
				ifnull(ifnull(c.fst_salesreturn_no,b.fst_po_no),e.fst_assembling_no) as fst_trans_no2,
				d.fst_relation_name 
				from trlpbgudang a 
				LEFT JOIN trpo b on a.fin_trans_id = b.fin_po_id and a.fst_lpb_type = 'PO'
				LEFT JOIN trsalesreturn c on a.fin_trans_id = c.fin_salesreturn_id and a.fst_lpb_type = 'SO_RETURN'
				LEFT JOIN trassembling e on a.fin_trans_id = e.fin_assembling_id and a.fst_lpb_type = 'ASSEMBLING_IN'				
				LEFT JOIN msrelations d on ifnull(b.fin_supplier_id,c.fin_customer_id) = d.fin_relation_id
			) a");

        $selectFields = "a.fin_lpbgudang_id,a.fst_lpbgudang_no,a.fdt_lpbgudang_datetime,a.fst_lpb_type,a.fst_trans_no2,a.fst_relation_name,a.fst_memo";
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
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Penerimaan Pembelian Barang") : lang("Update Penerimaan Pembelian Barang");
		$data["fin_lpbgudang_id"] = $finLPBGudangId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		
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
		
		try{
			$fdt_lpbgudang_datetime = dBDateTimeFormat($this->input->post("fdt_lpbgudang_datetime"));
			$resp = dateIsLock($fdt_lpbgudang_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
			$fst_lpbgudang_no = $this->trlpbgudang_model->generateLPBGudangNo($fdt_lpbgudang_datetime);
			
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];

			$this->validateData($dataH,$dataDetails);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		

		try{
			$this->db->trans_start();
			$insertId = $this->trlpbgudang_model->insert($dataH);

			//Insert Data Detail Transaksi
			foreach ($dataDetails as $dataD) {		
				$dataD = (array) $dataD;
				$dataD["fin_lpbgudang_id"] = $insertId;
				$dataD["fst_batch_number"] = $dataD["fst_batch_no"];
				$dataD["fst_serial_number_list"] = json_encode($dataD["arr_serial"]);
				$dataD["fst_active"] = "A";									
				$this->trlpbgudangitems_model->insert($dataD);			
			}
			
			$this->trlpbgudang_model->posting($insertId);	
			

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
			$finLPBGudangId = $this->input->post("fin_lpbgudang_id");

			$dataHOld = $this->trlpbgudang_model->getDataHeaderById($finLPBGudangId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid LPB Gudang ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_lpbgudang_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
						
			$fdt_lpbgudang_datetime = dBDateTimeFormat($this->input->post("fdt_lpbgudang_datetime"));
			$resp = dateIsLock($fdt_lpbgudang_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$resp = $this->trlpbgudang_model->isEditable($finLPBGudangId,$dataHOld);
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
			
			$this->trlpbgudang_model->unposting($finLPBGudangId);
			$this->trlpbgudang_model->deleteDetail($finLPBGudangId);
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_lpbgudang_id"] = $finLPBGudangId;
			$dataH["fst_lpbgudang_no"] = $dataHOld->fst_lpbgudang_no;
						
			$dataDetails = $preparedData["dataDetails"];
			$this->validateData($dataH,$dataDetails);

			
			$this->trlpbgudang_model->update($dataH);
			
			//Insert Data Detail Transaksi
			foreach ($dataDetails as $detail) {		
				$detail = (array) $detail;
				$detail["fin_lpbgudang_id"] = $dataH["fin_lpbgudang_id"];
				$detail["fst_batch_number"] = $detail["fst_batch_no"];
				$detail["fst_serial_number_list"] = json_encode($detail["arr_serial"]);
				$detail["fst_active"] = "A";					
				$this->trlpbgudangitems_model->insert($detail);			
			}

			$this->trlpbgudang_model->posting($finLPBGudangId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_lpbgudang_id"];
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
		$fdt_lpbgudang_datetime = dBDateTimeFormat($this->input->post("fdt_lpbgudang_datetime"));
		$fst_lpbgudang_no = $this->trlpbgudang_model->generateLPBGudangNo($fdt_lpbgudang_datetime);
		$lpbType = $this->input->post("fst_lpb_type");
		$finTransId = $this->input->post("fin_trans_id");

		$fstTransNo = "";
		$finRelationId = 0;

		switch ($lpbType){
			case "PO":
				$po = $this->trpo_model->getDataHeaderById($finTransId);
				if ($po == null){
					throw new CustomException("Invalid PO ID",3003,"FAILED",["fin_po_id"=>$finTransId]);
				}
				$fstTransNo = $po->fst_po_no;
				$finRelationId = $po->fin_supplier_id;
				break;
			case "SO_RETURN":
				$soReturn = $this->trsalesreturn_model->getDataHeaderById($finTransId);
				if ($soReturn == null){
					throw new CustomException("Invalid Sales Return ID",3003,"FAILED",["fin_salesreturn_id"=>$finTransId]);
				}
				$fstTransNo = $soReturn->fst_salesreturn_no;
				$finRelationId = $soReturn->fin_customer_id;
				break;
			case "ASSEMBLING_IN":
				$assembling = $this->trassembling_model->getDataHeader($finTransId);
				if ($assembling == null){
					throw new CustomException("Invalid Assembling ID",3003,"FAILED",["fin_assembling_id"=>$finTransId]);
				}
				$fstTransNo = $assembling->fst_assembling_no;
				$finRelationId = 0;				
				break;
			default:
				throw new CustomException("Invalid LPB Type",3003,"FAILED",["fst_lpb_type"=>$lpbType]);
		}

		//$ssql = "";

		$dataH = [
			"fst_lpbgudang_no"=>$fst_lpbgudang_no,
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),
			"fdt_lpbgudang_datetime"=>$fdt_lpbgudang_datetime,
			"fst_lpb_type"=>$this->input->post("fst_lpb_type"),
			"fin_trans_id"=>$this->input->post("fin_trans_id"),
			"fst_trans_no"=>$fstTransNo,
			"fin_relation_id"=>$finRelationId,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A',			
		];


		$dataDetails = $this->input->post("details");
		$dataDetails = json_decode($dataDetails);		
		return[
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails
		];
		
	}
	
	private function validateData($dataH,$dataDetails){
		$this->form_validation->set_rules($this->trlpbgudang_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		$this->form_validation->set_rules($this->trlpbgudangitems_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		foreach($dataDetails as $dataD){
			$this->form_validation->set_data((array) $dataD);
			if ($this->form_validation->run() == FALSE) {
				//print_r($this->form_validation->error_array());
				throw new CustomException("Error Validation Details",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
			}

			$itemInfo = $this->msitems_model->getSimpleDataById($dataD->fin_item_id);

			//Cek is item have batch number
			if($itemInfo->fbl_is_batch_number && $dataD->fst_batch_no == "" ){
				throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
			}

			//Cek is item have serial number
			if($itemInfo->fbl_is_serial_number){				
				//$arrSerial = json_decode($item->arr_serial);
				$arrSerial = $dataD->arr_serial;
				if($arrSerial == null){
					throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
				}

				if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit) ){
					throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$dataD->fst_custom_item_name,$dataD->fdb_qty),3003,"FAILED",$dataD);
				}

			}

		}
		

		
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

		try{
			
			$dataHOld = $this->trlpbgudang_model->getDataHeaderById($finLPBGudangId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid LPB Gudang ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_lpbgudang_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
									
			$resp = $this->trlpbgudang_model->isEditable($finLPBGudangId,$dataHOld);
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
			
			$this->trlpbgudang_model->unposting($finLPBGudangId);			
			$resp = $this->trlpbgudang_model->delete($finLPBGudangId,true,null);	

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

	public function get_transaction_list($lpbType){
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
}    
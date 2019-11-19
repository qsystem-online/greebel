<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_return extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('trpurchasereturn_model');
		$this->load->model('msitemdiscounts_model');
		
    }
	
	public function index(){

		$this->load->library('menus');
        $this->list['page_name'] = "Purchase - Return";
        $this->list['list_name'] = "Invoice Retur Pembelian List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/purchase/purchase_return/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase/purchase_return/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_purchasereturn_no' => 'No Retur Pembelian',
			'fst_supplier_name' => 'Supplier'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Return', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. Retur Pembelian', 'width' => '10px','visible'=>'false', 'data' => 'fin_purchasereturn_id'],
            ['title' => 'No. Retur Pembelian', 'width' => '100px', 'data' => 'fst_purchasereturn_no'],
            ['title' => 'Tanggal', 'width' => '100px', 'data' => 'fdt_purchasereturn_datetime'],
            ['title' => 'Supplier', 'width' => '100px', 'data' => 'fst_supplier_name'],
			['title' => 'No. Faktur', 'width' => '100px', 'data' => 'fst_lpbpurchase_no'],			
			['title' => 'Memo', 'width' => '200px', 'data' => 'fst_memo'],
			['title' => 'Total Amount', 'width' => '100px', 'data' => 'fdc_total','className'=>'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}",
			],
			['title' => 'Action', 'width' => '100px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/purchase/purchase_return/edit/' + row.fin_purchasereturn_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase/return/listjs', [], true);

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



	public function add(){
        $this->openForm("ADD", 0);
	}

	public function edit($finPurchaseReturnId){
        $this->openForm("EDIT", $finPurchaseReturnId);
    }

	private function openForm($mode = "ADD", $finPurchaseReturnId = 0){
        $this->load->library("menus");			

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Retur Pembelian") : lang("Update Retur Pembelian");
		$data["fin_purchasereturn_id"] = $finPurchaseReturnId;
		$data["mdlEditForm"] = $edit_modal;

		$data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();
		
		
		if($mode == 'ADD'){
			$data["fst_purchasereturn_no"]=$this->trpurchasereturn_model->generatePurchaseReturnNo(); 
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$data["fst_purchasereturn_no"]="";	
			$data["mdlJurnal"] = $jurnal_modal;
        }        
		
		$page_content = $this->parser->parse('pages/tr/purchase/return/form', $data, true);
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
		/*
		'fin_purchasereturn_id' => string '0' (length=1)
		'fbl_is_import' => 1
		'fst_purchasereturn_no' => string 'PRT/JKT/2019/10/00001' (length=21)
		'fdt_purchasereturn_datetime' => string '31-10-2019 11:24:34' (length=19)
		'fin_supplier_id' => string '142' (length=3)
		'fin_lpbpurchase_id' => string '23' (length=2)
		'fdc_exchange_rate_idr' => string '1.00' (length=4)
		'fin_warehouse_id' => string '2' (length=1)
		'fst_memo' => string 'asdas asda asdasd' (length=17)
		'fdc_ppn_percent' => string '10' (length=2)
		'details' => string '[
			{"fin_rec_id":0,"fin_po_detail_id":"12","fin_item_id":"1","fst_item_code":"AB1230","fst_custom_item_name":"Greebel Artists Crayon Oil Pastel","fst_unit":"BOX","fdc_price":"10000.00","fst_disc_item":"10+2.5","fdb_qty_total":3,"fdb_qty_return":"2"},
			{"fin_rec_id":0,"fin_po_detail_id":"13","fin_item_id":"2","fst_item_code":"AB2250","fst_custom_item_name":"Silver Queen","fst_unit":"PACK","fdc_price":"20000.00","fst_disc_item":"10","fdb_qty_total":2,"fdb_qty_return":"1"}
		]' (length=471)
		 */

		$this->load->model("trpurchasereturnitems_model");
		$this->load->model("trlpbpurchase_model");		
		$this->load->model("trpodetails_model");	
		
		//CEK tgl lock dari transaksi yg di kirim
		$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));		
		$resp = dateIsLock($fdt_purchasereturn_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}


		//PREPARE DATA
		$fst_purchasereturn_no = $this->trpurchasereturn_model->generatePurchaseReturnNo();
		$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));
		$fbl_non_faktur = $this->input->post("fbl_non_faktur") == null ? false :true;
		$fin_lpbpurchase_id = $fbl_non_faktur ? null : $this->input->post("fin_lpbpurchase_id");
		$fst_curr_code =  $this->input->post("fst_curr_code");

		$data = [];		
		if($fin_lpbpurchase_id != null){ //retur dengan faktur
			$data = $this->trlpbpurchase_model->getDataById($fin_lpbpurchase_id);
			if( $data == null || $this->input->post("fin_supplier_id") != $data["lpbPurchase"]->fin_supplier_id ){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Error Validation Data");
				$this->ajxResp["data"] = ["fin_lpbpurchase_id"=>lang("Invalid purchase id")];
				$this->json_output();
				die();
			}

			$fst_curr_code = $data["lpbPurchase"]->fst_curr_code;

		}

		$dataH =[
			//"fin_purchasereturn_id"
			"fst_purchasereturn_no" =>$fst_purchasereturn_no,
			"fdt_purchasereturn_datetime"=>$fdt_purchasereturn_datetime,
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
			"fbl_non_faktur"=>$fbl_non_faktur,
			"fin_lpbpurchase_id"=>$fin_lpbpurchase_id,
			"fst_curr_code"=>$fst_curr_code,
			"fdc_exchange_rate_idr"=> $this->input->post("fdc_exchange_rate_idr"),
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_ppn_percent"=>$this->input->post("fdc_ppn_percent"),
			"fdc_ppn_amount"=>0,
			"fdc_total"=>0,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A'
		];

		$postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);
		$dataDetails = [];
		$ttlDisc = 0;
		$ttlBfDisc = 0;
		$ttlAfDisc = 0;		
		foreach($postDetails as $detail){
			$dataD = [
				"fin_rec_id"=>0,
				"fin_purchasereturn_id"=>0,
				"fin_po_detail_id"=>$detail->fin_po_detail_id,
				"fin_item_id"=>$detail->fin_item_id,
				"fst_custom_item_name"=>$detail->fst_custom_item_name,
				"fdb_qty"=>$detail->fdb_qty_return,
				"fst_unit"=>$detail->fst_unit,
				"fdc_price"=>$detail->fdc_price,
				"fst_disc_item"=>$detail->fst_disc_item,
				"fdc_disc_amount"=>calculateDisc($detail->fst_disc_item, $detail->fdb_qty_return * $detail->fdc_price),
				//fst_notes
				"fst_active"=>'A'
			];

			if ($detail->fin_po_detail_id != 0){
				$dPO = $this->trpodetails_model->getDataById($detail->fin_po_detail_id);
				if($dPO == null){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = lang("Error Validation Data");
					$this->ajxResp["data"] = ["details"=> sprintf(lang("Invalid Data Item %s"),$detail->fst_custom_item_name)];
					$this->json_output();
					die();
				}
				$dataD["fin_item_id"] = $dPO->fin_item_id; 
				$dataD["fst_custom_item_name"] = $dPO->fst_custom_item_name;
				$dataD["fst_unit"] = $dPO->fst_unit;
				$dataD["fdc_price"] = $dPO->fdc_price;
				$dataD["fst_disc_item"] = $dPO->fst_disc_item;				
				$dataD["fdc_disc_amount"] = calculateDisc($dPO->fst_disc_item, $dataD["fdb_qty"] * $dataD["fdc_price"]);
			}

			$dataDetails[] = $dataD;
			$ttlDisc +=  floatval($dataD["fdc_disc_amount"]);
			$ttlBfDisc += floatval($dataD["fdb_qty"] * $dataD["fdc_price"]);
		}
		$ttlAfDisc = $ttlBfDisc - $ttlDisc;
		$dataH["fdc_subttl"] = $ttlBfDisc;
		$dataH["fdc_disc_amount"] = $ttlDisc;
		$dataH["fdc_ppn_amount"] = $ttlAfDisc * ($dataH["fdc_ppn_percent"] / 100);		
		$totalReturn = $dataH["fdc_subttl"] -  $dataH["fdc_disc_amount"] + $dataH["fdc_ppn_amount"];
		$dataH["fdc_total"] = $totalReturn;

		
		//VALIDATION

		//validation header
		$this->form_validation->set_rules($this->trpurchasereturn_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data");
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			die();
		}

		if($dataH["fbl_non_faktur"] == false){
			//Cek total retur tidak boleh melebihi total_invoice - (total_pembayaran + total_yang_telah_di_retur)
			$lpbPurchase = $data["lpbPurchase"];
			$totalReturAllow = floatval($lpbPurchase->fdc_total) - ( floatval($lpbPurchase->fdc_total_paid) +  floatval($lpbPurchase->fdc_total_return) );
			if ( $totalReturn > $totalReturAllow ){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = sprintf(lang("Total retur tidak boleh melebihi %s"), formatNumber($totalReturAllow) );
				$this->ajxResp["data"] = [];
				$this->json_output();
				die();
			}

			//validation detail
			$returnedList = $this->trpurchasereturn_model->getSummaryReturnByLPBPurchase($dataH["fin_lpbpurchase_id"]);
			$lPBList = $this->trpurchasereturn_model->getSummaryQtyLPBByLPBPurchase($dataH["fin_lpbpurchase_id"]);
			foreach($dataDetails as $dataD){
				$finPODetailId = $dataD["fin_po_detail_id"];
				$lPB = isset($lPBList[$finPODetailId]) ? $lPBList[$finPODetailId] : null;
				$qtyLPB = $lPB == null ? 0  : (float) $lPB->fdb_qty_lpb;
				$returnLPB = isset($returnedList[$finPODetailId]) ? $returnedList[$finPODetailId] : null;
				$qtyReturned = $returnLPB == null ? 0  : (float) $returnLPB->fdb_qty_return;
				$qtyReturn = (float) $dataD["fdb_qty"];
				$maxReturnAllow = $qtyLPB - $qtyReturned;

				if ($qtyReturn > $maxReturnAllow ){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = sprintf(lang("Total qty retur %s tidak boleh melebihi %s"),$dataD["fst_custom_item_name"], $maxReturnAllow );
					$this->ajxResp["data"] = [];
					$this->json_output();
					die();
				}
			}
		}		
		
		//SAVE
		$this->db->trans_start(); 
		$insertId = $this->trpurchasereturn_model->insert($dataH);
		foreach($dataDetails as $dataD){
			$dataD["fin_purchasereturn_id"] = $insertId;
			$this->trpurchasereturnitems_model->insert($dataD);

		}
		//POSTING
		$result = $this->trpurchasereturn_model->posting($insertId);
		if($result["status"] != "SUCCESS"){
			$this->ajxResp["status"] = $result["status"];
			$this->ajxResp["message"] = $result["message"];
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
		$this->load->model("trpurchasereturnitems_model");
		$this->load->model("trlpbpurchase_model");		
		$this->load->model("trpodetails_model");		

		$finPurchaseReturnId = $this->input->post("fin_purchasereturn_id");		

		//CEK if editable
		$dataHOld = $this->trpurchasereturn_model->getDataHeaderById($finPurchaseReturnId);
		if ($dataHOld == null){
			$this->ajxResp["status"] = "FAILED!";
			$this->ajxResp["message"] = lang("Data Not Found !");
			$this->json_output();
			return;
		}
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_purchasereturn_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		//CEK tgl lock dari transaksi yg di kirim
		$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));		
		$resp = dateIsLock($fdt_purchasereturn_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		//CEK iseditable 
		$resp = $this->trpurchasereturn_model->isEditable($dataHOld->fin_purchasereturn_id);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}



		$this->db->trans_start(); 

		//UNPOSTING
		$result = $this->trpurchasereturn_model->unposting($finPurchaseReturnId);

		//DELETE DETAIL DATA
		$ssql  = "delete from trpurchasereturnitems  where fin_purchasereturn_id = ?";
        $qr = $this->db->query($ssql,[$finPurchaseReturnId]);        


		//PREPARE DATA
		$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));
		$fbl_non_faktur = $this->input->post("fbl_non_faktur") == null ? false :true;
		$fin_lpbpurchase_id = $fbl_non_faktur ? null : $this->input->post("fin_lpbpurchase_id");
		$fst_curr_code =  $this->input->post("fst_curr_code");

		$data = [];		
		if($fin_lpbpurchase_id != null){ //retur dengan faktur
			$data = $this->trlpbpurchase_model->getDataById($fin_lpbpurchase_id);
			if( $data == null || $this->input->post("fin_supplier_id") != $data["lpbPurchase"]->fin_supplier_id ){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = lang("Error Validation Data");
				$this->ajxResp["data"] = ["fin_lpbpurchase_id"=>lang("Invalid purchase id")];
				$this->db->trans_rollback();
				$this->json_output();
				die();
			}
			$fst_curr_code = $data["lpbPurchase"]->fst_curr_code;
		}

		$dataH =[
			"fin_purchasereturn_id"=>$finPurchaseReturnId,
			"fst_purchasereturn_no"=>$dataHOld->fst_purchasereturn_no,
			"fdt_purchasereturn_datetime"=>$fdt_purchasereturn_datetime,
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
			"fbl_non_faktur"=>$fbl_non_faktur,
			"fin_lpbpurchase_id"=>$fin_lpbpurchase_id,
			"fst_curr_code"=>$fst_curr_code,
			"fdc_exchange_rate_idr"=> $this->input->post("fdc_exchange_rate_idr"),
			"fin_warehouse_id"=>$this->input->post("fin_warehouse_id"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_ppn_percent"=>$this->input->post("fdc_ppn_percent"),
			"fdc_ppn_amount"=>0,
			"fdc_total"=>0,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A'
		];

		$postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);
		$dataDetails = [];
		$ttlDisc = 0;
		$ttlBfDisc = 0;
		$ttlAfDisc = 0;		
		foreach($postDetails as $detail){
			$dataD = [
				"fin_rec_id"=> $detail->fin_rec_id == 0 ? null : $detail->fin_rec_id,
				"fin_purchasereturn_id"=>$finPurchaseReturnId,
				"fin_po_detail_id"=>$detail->fin_po_detail_id,
				"fin_item_id"=>$detail->fin_item_id,
				"fst_custom_item_name"=>$detail->fst_custom_item_name,
				"fdb_qty"=>$detail->fdb_qty_return,
				"fst_unit"=>$detail->fst_unit,
				"fdc_price"=>$detail->fdc_price,
				"fst_disc_item"=>$detail->fst_disc_item,
				"fdc_disc_amount"=>calculateDisc($detail->fst_disc_item, $detail->fdb_qty_return * $detail->fdc_price),
				//fst_notes
				"fst_active"=>'A'
			];

			if ($detail->fin_po_detail_id != 0){
				$dPO = $this->trpodetails_model->getDataById($detail->fin_po_detail_id);
				if($dPO == null){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = lang("Error Validation Data");
					$this->ajxResp["data"] = ["details"=> sprintf(lang("Invalid Data Item %s"),$detail->fst_custom_item_name)];
					$this->db->trans_rollback();
					$this->json_output();
					die();
				}
				$dataD["fin_item_id"] = $dPO->fin_item_id; 
				$dataD["fst_custom_item_name"] = $dPO->fst_custom_item_name;
				$dataD["fst_unit"] = $dPO->fst_unit;
				$dataD["fdc_price"] = $dPO->fdc_price;
				$dataD["fst_disc_item"] = $dPO->fst_disc_item;				
				$dataD["fdc_disc_amount"] = calculateDisc($dPO->fst_disc_item, $dataD["fdb_qty"] * $dataD["fdc_price"]);
			}

			$dataDetails[] = $dataD;
			$ttlDisc +=  floatval($dataD["fdc_disc_amount"]);
			$ttlBfDisc += floatval($dataD["fdb_qty"] * $dataD["fdc_price"]);
		}
		$ttlAfDisc = $ttlBfDisc - $ttlDisc;
		$dataH["fdc_subttl"] = $ttlBfDisc;
		$dataH["fdc_disc_amount"] = $ttlDisc;
		$dataH["fdc_ppn_amount"] = $ttlAfDisc * ($dataH["fdc_ppn_percent"] / 100);		
		$totalReturn = $dataH["fdc_subttl"] -  $dataH["fdc_disc_amount"] + $dataH["fdc_ppn_amount"];
		$dataH["fdc_total"] = $totalReturn;

		
		//VALIDATION

		//validation header
		$this->form_validation->set_rules($this->trpurchasereturn_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data");
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->db->trans_rollback();
			$this->json_output();
			die();
		}
		if($dataH["fbl_non_faktur"] == false){
			//Cek total retur tidak boleh melebihi total_invoice - (total_pembayaran + total_yang_telah_di_retur)
			$lpbPurchase = $data["lpbPurchase"];
			$totalReturAllow = floatval($lpbPurchase->fdc_total) - ( floatval($lpbPurchase->fdc_total_paid) +  floatval($lpbPurchase->fdc_total_return) );
			if ( $totalReturn > $totalReturAllow ){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = sprintf(lang("Total retur tidak boleh melebihi %s"), formatNumber($totalReturAllow) );
				$this->ajxResp["data"] = [];
				$this->db->trans_rollback();
				$this->json_output();
				die();
			}

			//validation detail
			$returnedList = $this->trpurchasereturn_model->getSummaryReturnByLPBPurchase($dataH["fin_lpbpurchase_id"]);
			$lPBList = $this->trpurchasereturn_model->getSummaryQtyLPBByLPBPurchase($dataH["fin_lpbpurchase_id"]);
			foreach($dataDetails as $dataD){
				$finPODetailId = $dataD["fin_po_detail_id"];
				$lPB = isset($lPBList[$finPODetailId]) ? $lPBList[$finPODetailId] : null;
				$qtyLPB = $lPB == null ? 0  : (float) $lPB->fdb_qty_lpb;
				$returnLPB = isset($returnedList[$finPODetailId]) ? $returnedList[$finPODetailId] : null;
				$qtyReturned = $returnLPB == null ? 0  : (float) $returnLPB->fdb_qty_return;
				$qtyReturn = (float) $dataD["fdb_qty"];
				$maxReturnAllow = $qtyLPB - $qtyReturned;

				if ($qtyReturn > $maxReturnAllow ){
					$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
					$this->ajxResp["message"] = sprintf(lang("Total qty retur %s tidak boleh melebihi %s"),$dataD["fst_custom_item_name"], $maxReturnAllow );
					$this->ajxResp["data"] = [];
					$this->db->trans_rollback();
					$this->json_output();
					die();
				}
			}
		}		

		//SAVE
		$this->trpurchasereturn_model->update($dataH);
		foreach($dataDetails as $dataD){
			$dataD["fin_purchasereturn_id"] = $finPurchaseReturnId;
			$this->trpurchasereturnitems_model->insert($dataD);

		}
		
		//POSTING
		$result = $this->trpurchasereturn_model->posting($finPurchaseReturnId);
		if($result["status"] != "SUCCESS"){
			$this->ajxResp["status"] = $result["status"];
			$this->ajxResp["message"] = $result["message"];
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

		//$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $finPurchaseReturnId;
		$this->json_output();
	}


	public function fetch_data($finPurchaseReturnId){
		$data = $this->trpurchasereturn_model->getDataById($finPurchaseReturnId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finPurchaseReturnId){
		
		$dataHOld = $this->trpurchasereturn_model->getDataHeaderById($finPurchaseReturnId);
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_purchasereturn_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$isEditable = $this->trpurchasereturn_model->isEditable($finPurchaseReturnId);
        if($isEditable["status"] != "SUCCESS"){
            return $isEditable;
		}
		

		$this->db->trans_start();

		$data =[];

		$resp = $this->trpurchasereturn_model->unposting($finPurchaseReturnId);               
        if($resp["status"] != "SUCCESS"){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
            return;
        }

		$resp = $this->trpurchasereturn_model->delete($finPurchaseReturnId,true,$data);
		
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){
			$this->db->trans_rollback();	
			$resp["status"] = "DB_FAILED";
			$resp["message"] = $dbError["message"];
			return $resp;
		}
		
		$this->db->trans_complete();


		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->json_output();
	}

	
	public function fetch_list_data(){
		$this->load->library("datatables");
        $this->datatables->setTableName("(
			SELECT a.*,b.fst_lpbpurchase_no,c.fst_relation_name as fst_supplier_name FROM trpurchasereturn a 
			LEFT JOIN trlpbpurchase b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id  
			INNER JOIN msrelations c on a.fin_supplier_id = c.fin_relation_id 
			) a");

        $selectFields = "a.fin_purchasereturn_id,a.fst_purchasereturn_no,a.fdt_purchasereturn_datetime,a.fst_lpbpurchase_no,a.fst_supplier_name,a.fst_memo,a.fdc_total";
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

	




















	


	
}    
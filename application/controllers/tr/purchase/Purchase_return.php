<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_return extends MY_Controller{
	public $menuName="purchase_return";

    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('trpurchasereturn_model');
		$this->load->model("trpurchasereturnitems_model");		
		$this->load->model('msitemdiscounts_model');
		$this->load->model("trlpbpurchase_model");
		//$this->load->model("trpodetails_model");	

		
    }
	
	public function index(){
		parent::index();
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
            ['title' => 'No. Retur Pembelian', 'width' => '120px', 'data' => 'fst_purchasereturn_no'],
            ['title' => 'Tanggal', 'width' => '120px', 'data' => 'fdt_purchasereturn_datetime'],
            ['title' => 'Supplier', 'width' => '200px', 'data' => 'fst_supplier_name'],
			['title' => 'No. Faktur', 'width' => '120px', 'data' => 'fst_lpbpurchase_no'],			
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_memo'],
			['title' => 'Total Amount', 'width' => '80px', 'data' => 'fdc_total','className'=>'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}",
			],
			['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
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
		parent::add();
        $this->openForm("ADD", 0);
	}

	public function edit($finPurchaseReturnId){
		parent::edit($finPurchaseReturnId);
        $this->openForm("EDIT", $finPurchaseReturnId);
    }

	private function openForm($mode = "ADD", $finPurchaseReturnId = 0){
        $this->load->library("menus");			

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Retur Pembelian") : lang("Update Retur Pembelian");
		$data["fin_purchasereturn_id"] = $finPurchaseReturnId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;

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
		parent::ajx_add_save();
		$this->load->model("trpurchasereturnitems_model");
		$this->load->model("trlpbpurchase_model");		
		$this->load->model("trpodetails_model");	
		
		//CEK tgl lock dari transaksi yg di kirim
		try{
			$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));		
			$resp = dateIsLock($fdt_purchasereturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}
			$dataPrepared = $this->prepareData();			
			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];
			$lpbPurchase = $dataPrepared["lpbPurchase"];
			
			$this->validateData($dataH,$dataDetails,$lpbPurchase);

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			return;
		}
	
		try{
			//SAVE
			$this->db->trans_start(); 						
			$insertId = $this->trpurchasereturn_model->insert($dataH);
			
			foreach($dataDetails as $dataD){
				$dataD["fin_purchasereturn_id"] = $insertId;
				$this->trpurchasereturnitems_model->insert($dataD);
			}

			//POSTING
			$this->trpurchasereturn_model->posting($insertId);
			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $insertId;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			$this->db->trans_rollback();
			return;
		}
	}
	
	public function ajx_edit_save(){					
		parent::ajx_edit_save();
		try{
			$finPurchaseReturnId = $this->input->post("fin_purchasereturn_id");

			//CEK if editable
			$dataHOld = $this->trpurchasereturn_model->getDataHeaderById($finPurchaseReturnId);
			if ($dataHOld == null){
				throw new CustomException(lang("ID Purchase Return tidak dikenal!",3003,"FAILED",["fin_purchasereturn_id"=>$finPurchaseReturnId]));
			}

			//CEK tgl lock dari transaksi tersimpan
			$resp = dateIsLock($dataHOld->fdt_purchasereturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK tgl lock dari transaksi yg di kirim
			$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));		
			$resp = dateIsLock($fdt_purchasereturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK iseditable 
			$resp = $this->trpurchasereturn_model->isEditable($dataHOld->fin_purchasereturn_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
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

			//UNPOSTING
			$this->trpurchasereturn_model->unposting($finPurchaseReturnId);

			//DELETE DETAIL DATA
			$this->trpurchasereturn_model->deleteDetail($finPurchaseReturnId);


			$dataPrepared = $this->prepareData();

			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];
			$lpbPurchase = $dataPrepared["lpbPurchase"];
			$dataH["fin_purchasereturn_id"] = $this->input->post("fin_purchasereturn_id");
			$dataH["fst_purchasereturn_no"] = $dataHOld->fst_purchasereturn_no;

			//VALIDATION
			$this->validateData($dataH,$dataDetails,$lpbPurchase);

			//SAVE
			$this->trpurchasereturn_model->update($dataH);
			foreach($dataDetails as $dataD){
				$dataD["fin_purchasereturn_id"] = $finPurchaseReturnId;
				$this->trpurchasereturnitems_model->insert($dataD);
			}

			//POSTING
			$this->trpurchasereturn_model->posting($finPurchaseReturnId);
						
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finPurchaseReturnId;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			$this->db->trans_rollback();
			return;
		}		
	}


	private function prepareData(){

		//PREPARE DATA
		$fst_purchasereturn_no = $this->trpurchasereturn_model->generatePurchaseReturnNo();
		$fdt_purchasereturn_datetime = dBDateTimeFormat($this->input->post("fdt_purchasereturn_datetime"));
		$fbl_non_faktur = $this->input->post("fbl_non_faktur") == null ? false :true;
		$fin_lpbpurchase_id = $fbl_non_faktur ? null : $this->input->post("fin_lpbpurchase_id");
		$fst_curr_code =  $this->input->post("fst_curr_code");

		$lpbPurchase = null;		
		if($fin_lpbpurchase_id != null){ //retur dengan faktur
			$lpbPurchase = $this->db->get_where("trlpbpurchase",["fin_lpbpurchase_id"=>$fin_lpbpurchase_id])->row();

			if( $lpbPurchase == null || $this->input->post("fin_supplier_id") != $lpbPurchase->fin_supplier_id ){
				throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",["fin_lpbpurchase_id"=>lang("Invalid purchase id")]);
			}
			$fst_curr_code = $lpbPurchase->fst_curr_code;
		}

		$dataH =[
			//"fin_purchasereturn_id"
			"fst_purchasereturn_no" =>$fst_purchasereturn_no,
			"fbl_is_import"=> $this->input->post("fbl_is_import") == null ? 0 : $this->input->post("fbl_is_import"),			
			"fdt_purchasereturn_datetime"=>$fdt_purchasereturn_datetime,
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
			"fbl_non_faktur"=>$fbl_non_faktur,
			"fin_lpbpurchase_id"=>$fin_lpbpurchase_id,
			"fst_curr_code"=>$fst_curr_code,
			"fdc_exchange_rate_idr"=> $this->input->post("fdc_exchange_rate_idr"),
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
		$subTtl = 0;		
		$ttlQty = 0;
		foreach($postDetails as $detail){

			$ttlQty += $detail->fdb_qty;

			$dataD = [
				"fin_rec_id"=>0,
				"fin_purchasereturn_id"=>0,
				"fin_item_id"=>$detail->fin_item_id,
				"fst_custom_item_name"=>$detail->fst_custom_item_name,
				"fdb_qty"=>$detail->fdb_qty,
				"fst_unit"=>$detail->fst_unit,
				"fdc_price"=>$detail->fdc_price,
				"fst_disc_item"=>$detail->fst_disc_item,
				"fdc_disc_amount_per_item"=>calculateDisc($detail->fst_disc_item,$detail->fdc_price),
				"fst_active"=>'A'
			];

			$dataDetails[] = $dataD;
			$subTtl += floatval($dataD["fdb_qty"] * $dataD["fdc_price"]);

			$disc = $dataD["fdb_qty"] * $dataD["fdc_disc_amount_per_item"];
			$ttlDisc +=  floatval($disc);
			
		}

		if ($ttlQty <= 0){
			throw new CustomException("Total qty harus lebih besar 0",3003,"FAILED",[]);
		}

		$dataH["fdc_subttl"] = $subTtl;
		$dataH["fdc_disc_amount"] = $ttlDisc;
		
		$ttlAfDisc = $subTtl - $ttlDisc;
		$dataH["fdc_ppn_amount"] = $ttlAfDisc * ($dataH["fdc_ppn_percent"] / 100);		
		$totalReturn = $dataH["fdc_subttl"] -  $dataH["fdc_disc_amount"] + $dataH["fdc_ppn_amount"];
		$dataH["fdc_total"] = $totalReturn;

		return [
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails,
			"lpbPurchase"=>$lpbPurchase
		];
	}

	private function validateData($dataH,$dataDetails,$lpbPurchase){
		//validation header
		$this->form_validation->set_rules($this->trpurchasereturn_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		if($dataH["fbl_non_faktur"] == false){
			//Cek total retur tidak boleh melebihi total_invoice - (total_pembayaran + total_yang_telah_di_retur)			
			$totalReturAllow = floatval($lpbPurchase->fdc_total) - ( floatval($lpbPurchase->fdc_total_paid) +  floatval($lpbPurchase->fdc_total_return) );
			if ( $dataH["fdc_total"] > $totalReturAllow ){
				throw new CustomException(sprintf(lang("Total retur tidak boleh melebihi %s"), formatNumber($totalReturAllow) ),3003,"FAILED",null);
			}

			//validation detail
			//$returnedList = $this->trpurchasereturn_model->getSummaryReturnByLPBPurchase($dataH["fin_lpbpurchase_id"]);
			//$lPBList = $this->trpurchasereturn_model->getSummaryQtyLPBByLPBPurchase($dataH["fin_lpbpurchase_id"]);
			$lpbPurchaseDetailList =  $this->trpurchasereturn_model->getLPBPurchase($dataH["fin_lpbpurchase_id"]);
			$arrLPBPurchaseDetail = [];
			foreach($lpbPurchaseDetailList as $lpbPurchaseDetail){
				$arrLPBPurchaseDetail[$lpbPurchaseDetail->fin_item_id] = $lpbPurchaseDetail;
			}

			
			foreach($dataDetails as $dataD){
				$lpbPurchaseDetail = isset($arrLPBPurchaseDetail[$dataD["fin_item_id"]]) ? $arrLPBPurchaseDetail[$dataD["fin_item_id"]] : null;
				if ($lpbPurchaseDetail == null){
					throw new CustomException("Invalid Item ID",3003,"FAILED",$dataD);
				}
				$maxReturnAllow = floatval($lpbPurchaseDetail->fdb_qty) - floatval($lpbPurchaseDetail->fdb_qty_return);
				if ( $maxReturnAllow < $dataD["fdb_qty"]){
					throw new CustomException(sprintf(lang("Total qty retur %s tidak boleh melebihi %s"),$dataD["fst_custom_item_name"], $maxReturnAllow ),3003,"FAILED",$dataD);
				}
			}
		}


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
		parent::delete($finPurchaseReturnId);
		$dataHOld = $this->trpurchasereturn_model->getDataHeaderById($finPurchaseReturnId);
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_purchasereturn_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		
		try{
			
			$isEditable = $this->trpurchasereturn_model->isEditable($finPurchaseReturnId);
			if($isEditable["status"] != "SUCCESS"){
				return $isEditable;
			}
			

			$this->db->trans_start();
			$data =[];
			$this->trpurchasereturn_model->unposting($finPurchaseReturnId);
						
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
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

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

	public function get_lpbpurchase_list(){
		$fblIsImport = $this->input->get("fbl_is_import");
		$finSupplierId = $this->input->get("fin_supplier_id");
		$lpbPurchaseList = $this->trpurchasereturn_model->getListPurchaseFaktur($finSupplierId,$fblIsImport);
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$lpbPurchaseList
		]);
	}


	public function print_voucher($finPurchaseReturnId){
		$this->data = $this->trpurchasereturn_model->getDataVoucher($finPurchaseReturnId);
		//$data=[];
		$this->data["title"] = "Purchase Return";		
		$page_content = $this->parser->parse('pages/tr/purchase/return/voucher', $this->data, true);
		$this->data["PAGE_CONTENT"] = $page_content;	

		
		$strHtml = $this->parser->parse('template/voucher_pdf', $this->data, true);

		//$this->parser->parse('template/voucher', $this->data);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		//echo $strHtml;

		$mpdf->WriteHTML($strHtml);	
		$mpdf->Output();
	}














	


	
}    
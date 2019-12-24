<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_return extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('trsalesreturn_model');
		$this->load->model("trsalesreturnitems_model");
		$this->load->model("trinvoiceitems_model");
		$this->load->model('msitemdiscounts_model');		
    }
	
	public function index(){

		$this->load->library('menus');
        $this->list['page_name'] = "Sales - Return";
        $this->list['list_name'] = "Invoice Retur Pembelian List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/sales/sales_return/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/sales/sales_return/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_purchasereturn_no' => 'No Retur Pembelian',
			'fst_supplier_name' => 'Supplier'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Sales', 'link' => '#', 'icon' => ''],
            ['title' => 'Return', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [			
			['title' => 'No. Retur Penjualan', 'width' => '100px', 'data' => 'fst_salesreturn_no'],
            ['title' => 'Tanggal', 'width' => '100px', 'data' => 'fdt_salesreturn_datetime'],
            ['title' => 'Customer', 'width' => '100px', 'data' => 'fst_customer_name'],
			['title' => 'Memo', 'width' => '200px', 'data' => 'fst_memo'],
			['title' => 'Total Amount', 'width' => '100px', 'data' => 'fdc_total','className'=>'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}",
			],
			['title' => 'Closed', 'width' => '30px', 'data' => 'fbl_is_closed','className'=>'text-center',
				'render'=>"function(data,type,row){
					var checked = data == 1 ? 'checked' : '';
					return '<input type=\"checkbox\" ' + checked + ' disabled/>';
				}",
			],
			
			['title' => 'Action', 'width' => '100px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/sales/sales_return/edit/' + row.fin_salesreturn_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/sales/return/listjs', [], true);

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

	public function edit($finSalesReturnId){
        $this->openForm("EDIT", $finSalesReturnId);
    }

	private function openForm($mode = "ADD", $finSalesReturnId = 0){
        $this->load->library("menus");			

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Retur Penjualan") : lang("Update Retur Penjualan");
		$data["fin_salesreturn_id"] = $finSalesReturnId;
		$data["mdlEditForm"] = $edit_modal;

		$data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();
		
		
		if($mode == 'ADD'){
			$data["fst_salesreturn_no"]=$this->trsalesreturn_model->generateSalesReturnNo(); 
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$data["fst_salesreturn_no"]="";	
			$data["mdlJurnal"] = $jurnal_modal;
        }        
		
		$page_content = $this->parser->parse('pages/tr/sales/return/form', $data, true);
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
			//CEK tgl lock dari transaksi yg di kirim
			$fdt_salesreturn_datetime = dBDateTimeFormat($this->input->post("fdt_salesreturn_datetime"));		
			$resp = dateIsLock($fdt_salesreturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//Prepare Data
			$arrData = $this->prepareData();
			$dataH = $arrData["dataH"];
			$dataDetails = $arrData["dataDetails"];			
			
			//VALIDATION
			$this->validation($dataH,$dataDetails);					
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
			$insertId = $this->trsalesreturn_model->insert($dataH);
			foreach($dataDetails as $dataD){
				unset($dataD["fin_rec_id"]);
				$dataD["fin_salesreturn_id"] = $insertId;
				$this->trsalesreturnitems_model->insert($dataD);
			}
			//POSTING
			$result = $this->trsalesreturn_model->posting($insertId);			
			
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
		$finSalesReturnId = $this->input->post("fin_salesreturn_id");		
		try{
			//CEK if editable
			$dataHOld = $this->trsalesreturn_model->getDataHeaderById($finSalesReturnId);		
			if ($dataHOld == null){
				throw new CustomException(lang("ID Sales return tidak dikenal !"),3003,'FAILED',["fin_salesreturn_id"=>$finSalesReturnId]);
			}
			//CEK tgl lock dari transaksi tersimpan
			$resp = dateIsLock($dataHOld->fdt_salesreturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK tgl lock dari transaksi yg di kirim
			$fdt_salesreturn_datetime = dBDateTimeFormat($this->input->post("fdt_salesreturn_datetime"));		
			$resp = dateIsLock($fdt_salesreturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK iseditable 
			$resp = $this->trsalesreturn_model->isEditable($finSalesReturnId);
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
			$result = $this->trsalesreturn_model->unposting($finSalesReturnId);					

			//DELETE DETAIL DATA
			$this->trsalesreturn_model->deleteDetail($finSalesReturnId);     

			//PREPARE DATA
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];

			$dataH["fin_salesreturn_id"] = $finSalesReturnId;
			$dataH["fst_salesreturn_no"] =  $dataHOld->fst_salesreturn_no;
			
			//VALIDATION
			$this->validation($dataH,$dataDetails);
			
			//SAVE
			$this->trsalesreturn_model->update($dataH);
			foreach($dataDetails as $dataD){
				$dataD["fin_salesreturn_id"] = $finSalesReturnId;
				$this->trsalesreturnitems_model->insert($dataD);				
			}						
			
			//POSTING
			$this->trsalesreturn_model->posting($finSalesReturnId);			
			
			$this->db->trans_complete();		
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finSalesReturnId;
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

	public function prepareData(){
		/*
		fin_salesreturn_id: 0
		fst_salesreturn_no: SRT/JKT/2019/12/00001
		fdt_purchasereturn_datetime: 13-12-2019 18:23:53
		fin_customer_id: 154
		fst_curr_code: IDR
		fdc_exchange_rate_idr: 1.00
		fst_memo: 
		details: [{"fin_rec_id":0,"fin_item_id":"64","fin_inv_id":"54","fst_inv_no":"IV/JKT/2019/12/00006","fin_inv_detail_id":"13","fbl_is_vat_include":true,"fst_item_code":"010103000022","fst_custom_item_name":"GREEBEL PENCIL BAG MICA 2520","fst_unit":"PCS","fdc_price":"4200.00","fdb_qty":"14.00","fst_disc_item":"10+2.5","fdc_disc_amount_per_item":514.5,"fdc_dpp_amount":53454.54545454545,"fdc_ppn_percent":"10.00","fdc_ppn_amount":5345.454545454545}]
		*/

		//PREPARE DATA
		$fst_salesreturn_no = $this->trsalesreturn_model->generateSalesReturnNo();
		$fdt_salesreturn_datetime = dBDateTimeFormat($this->input->post("fdt_salesreturn_datetime"));

		$fbl_non_faktur = $this->input->post("fbl_non_faktur") == null ? false :true;
		$fst_curr_code =  $this->input->post("fst_curr_code");

		$dataH =[
			//"fin_purchasereturn_id"
			"fst_salesreturn_no" =>$fst_salesreturn_no,
			"fdt_salesreturn_datetime"=>$fdt_salesreturn_datetime,
			"fin_customer_id" => $this->input->post("fin_customer_id"),
			"fbl_non_faktur"=>$fbl_non_faktur,			
			"fst_curr_code"=>$fst_curr_code,
			"fdc_exchange_rate_idr"=> $this->input->post("fdc_exchange_rate_idr"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_dpp_amount"=>0,
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
		$ttlDPP = 0;
		$ttlPpn = 0;

		foreach($postDetails as $detail){
			
			
			$subTotal = ($detail->fdb_qty * $detail->fdc_price);
			$discPerItem =calculateDisc($detail->fst_disc_item,$detail->fdc_price);
			$ttlDisc = $discPerItem * $detail->fdb_qty;

			if ($detail->fbl_is_vat_include){				
				$dpp =   ($subTotal - $ttlDisc) / (1 + ($detail->fdc_ppn_percent/100));
			}else{
				$dpp = $subTotal - $ttlDisc;
			}
			$ppnAmount = $dpp * ($detail->fdc_ppn_percent/100);

			$dataD = [
				"fin_rec_id"=>$detail->fin_rec_id,
				"fin_salesreturn_id"=>0,
				"fin_inv_id"=>$detail->fin_inv_id,
				"fin_inv_detail_id"=>$detail->fin_inv_detail_id,
				"fin_item_id"=>$detail->fin_item_id,
				"fst_custom_item_name"=>$detail->fst_custom_item_name,
				"fbl_is_vat_include"=>$detail->fbl_is_vat_include,
				"fdb_qty"=>$detail->fdb_qty,
				"fst_unit"=>$detail->fst_unit,
				"fdc_price"=>$detail->fdc_price,				
				"fdc_subttl"=>$detail->fdb_qty * $detail->fdc_price,
				"fst_disc_item"=>$detail->fst_disc_item,
				"fdc_disc_amount_per_item"=>$discPerItem,
				"fdc_dpp_amount"=>$dpp,
				"fdc_ppn_percent"=>$detail->fdc_ppn_percent,
				"fdc_ppn_amount"=> $ppnAmount,
				"fdc_total"=> $dpp + $ppnAmount,
				"fst_active"=>'A'
			];			

			//Overwrite data detail
			if ($detail->fin_inv_detail_id != 0){
				$dInv = $this->trinvoiceitems_model->getDataById($detail->fin_inv_detail_id);
				if($dInv == null){
					throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",["details"=> sprintf(lang("Invalid Data Item %s"),$detail->fst_custom_item_name)]);
				}

				$subTotal = ($dataD["fdb_qty"] * $dataD["fdc_price"]);
				$discTotal = $dataD["fdb_qty"] * $dInv->fdc_disc_amount_per_item;
				$dpp = 0;
				if ($dInv->fbl_is_vat_include){
					$dpp = ($subTotal - $discTotal) / (1 + ($dInv->fdc_ppn_percent/100));
				}else{
					$dpp = $subTotal - $discTotal;
				}

				$dataD["fin_item_id"] = $dInv->fin_item_id; 
				$dataD["fst_custom_item_name"] = $dInv->fst_custom_item_name;
				$dataD["fbl_is_vat_include"] = $dInv->fbl_is_vat_include;
				$dataD["fst_unit"] = $dInv->fst_unit;
				$dataD["fdc_subttl"] = $dataD["fdb_qty"] * $dataD["fdc_price"];
				$dataD["fst_disc_item"] = $dInv->fst_disc_item;				
				$dataD["fdc_disc_amount_per_item"] = $dInv->fdc_disc_amount_per_item;
				$dataD["fdc_dpp_amount"] = $dpp;
				$dataD["fdc_ppn_percent"] = $dInv->fdc_ppn_percent;
				$dataD["fdc_ppn_amount"] = $dpp * ($dInv->fdc_ppn_percent/100);
				$dataD["fdc_total"] = $dpp + $dataD["fdc_ppn_amount"];
			}

			$dataDetails[] = $dataD;
			$ttlDisc +=  floatval( $dataD["fdb_qty"] * $dataD["fdc_disc_amount_per_item"]);
			$ttlBfDisc += floatval($dataD["fdb_qty"] * $dataD["fdc_price"]);
			$ttlDPP += floatval($dataD["fdc_dpp_amount"]);
			$ttlPpn += floatval($dataD["fdc_ppn_amount"]);
		}
		$ttlAfDisc = $ttlBfDisc - $ttlDisc;
		$dataH["fdc_subttl"] = $ttlBfDisc;
		$dataH["fdc_disc_amount"] = $ttlDisc;
		$dataH["fdc_dpp_amount"] = $ttlDPP;
		$dataH["fdc_ppn_amount"] = $ttlPpn;
		$totalReturn = $dataH["fdc_dpp_amount"] + $dataH["fdc_ppn_amount"];
		$dataH["fdc_total"] = $totalReturn;

		return [
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails
		];

	}

	public function validation($dataH,$dataDetails){
		
		//validation header
		$this->form_validation->set_rules($this->trsalesreturn_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException(lang("Error Validation Data Header"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

			
		if($dataH["fbl_non_faktur"] == false){
			//Return langsung mengurangi tagihan
			
			foreach($dataDetails as $dataD){				
				$dInv = $this->trinvoiceitems_model->getDataById($dataD["fin_inv_detail_id"]);				
				//Invoice tidak boleh kosong
				if($dInv == null){
					throw new CustomException(sprintf(lang("ID %s Invoice detail tidak ditemukan !"),$dataD->fin_inv_detail_id),3003,"FAILED",null);
				}				
				//Invoce tidak boleh ada pembayaran
				if ($dInv->fdc_total_paid > 0 ){
					throw new CustomException(sprintf(lang("Invoice %s sudah dilakukan pembayaran !"),$dInv->fst_inv_no),3003,"FAILED",null);
				}
				//Cek total retur tidak boleh melebihi total_invoice - (total_pembayaran + total_yang_telah_di_retur)	
				$maxReturn = ($dInv->fdc_total -  $dInv->fdc_total_paid + $dInv->fdc_total_return);
				if ($dataH["fdc_total"] > $maxReturn ){
					throw new CustomException(sprintf(lang("Total return Invoice %s tidak boleh melebihi %s"),$dInv->fst_inv_no,formatNumber($maxReturn)),3003,"FAILED",null);
				}
				$maxQtyReturn = ($dInv->fdb_qty -  $dInv->fdb_qty_return);				
				//Cek Qty retur tidak boleh melebih qty di invoice
				if ($dataD["fdb_qty"] > $maxQtyReturn ){
					throw new CustomException(sprintf(lang("Total qty return Invoice %s tidak boleh melebihi %s"),$dInv->fst_inv_no,$maxQtyReturn),3003,"FAILED",null);
				}
			}						
		}else{
			//Return tidak mengurangi tagihan dan harus digunakan sebagai voucher return
			foreach($dataDetails as $dataD){
				if ($dataD["fin_inv_detail_id"] != 0){
					$dInv = $this->trinvoiceitems_model->getDataById($dataD->fin_inv_detail_id);				
					//Invoice tidak boleh kosong
					if($dInv == null){
						throw new CustomException(sprintf(lang("ID %s Invoice detail tidak ditemukan !"),$dataD->fin_inv_detail_id),3003,"FAILED",null);
					}				
					//Invoce harus ada pembayaran
					if ($dInv->fdc_total_paid == 0 ){
						throw new CustomException(sprintf(lang("Invoice %s belum dilakukan pembayaran !"),$dInv->fst_inv_no),3003,"FAILED",null);
					}
					//Cek total retur tidak boleh melebihi total_invoice - (total_pembayaran + total_yang_telah_di_retur)	
					$maxReturn = ($dInv->fdc_total -  $dInv->fdc_total_paid + $dInv->fdc_total_return);
					if ($dataH["fdc_total"] > $maxReturn ){
						throw new CustomException(sprintf(lang("Total return Invoice %s tidak boleh melebihi %s"),$dInv->fst_inv_no,formatNumber($maxReturn)),3003,"FAILED",null);
					}
					$maxQtyReturn = ($dInv->fdb_qty -  $dInv->fdb_qty_return);				
					//Cek Qty retur tidak boleh melebih qty di invoice
					if ($dataD["fdb_qty"] > $maxQtyReturn ){
						throw new CustomException(sprintf(lang("Total qty return Invoice %s tidak boleh melebihi %s"),$dInv->fst_inv_no,$maxQtyReturn),3003,"FAILED",null);
					}
				}else{
					//Return tanpa invoice;
				}
				
			}
		}
	
		
	}

	public function fetch_data($finSalesReturnId){
		$data = $this->trsalesreturn_model->getDataById($finSalesReturnId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finSalesReturnId){
		
		try{
			$dataHOld = $this->trsalesreturn_model->getDataHeaderById($finSalesReturnId);
			//CEK tgl lock dari transaksi tersimpan
			$resp = dateIsLock($dataHOld->fdt_salesreturn_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$resp = $this->trsalesreturn_model->isEditable($finSalesReturnId);
			if($resp["status"] != "SUCCESS"){
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
			$data =[];
			
			$this->trsalesreturn_model->unposting($finSalesReturnId);               
			
			$this->trsalesreturn_model->delete($finSalesReturnId,true,$data);						

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("Data telah dihapus !");
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
			SELECT a.*,c.fst_relation_name as fst_customer_name FROM trsalesreturn a 
			INNER JOIN msrelations c on a.fin_customer_id = c.fin_relation_id 
			) a");

        $selectFields = "a.fin_salesreturn_id,a.fst_salesreturn_no,a.fdt_salesreturn_datetime,a.fst_customer_name,a.fst_memo,a.fdc_total,a.fbl_is_closed";
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

	public function get_item_by_inv(){
		$search = $this->input->get("term");
		$search  = "%$search%";
		$finInvId = $this->input->get("finInvId");		
		$itemList = $this->trsalesreturn_model->getItemListByInv($finInvId,$search);
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$itemList,
		]);
	}

	public function get_list_invoice(){
		$search = $this->input->get("term");

		$search  = "%$search%";
		$fin_customer_id = $this->input->get("finCustomerId");
		$fstCurrCode = $this->input->get("fstCurrCode");
		$isPaidFaktur = $this->input->get("isPaidInv");
		$invList = $this->trsalesreturn_model->getListSalesFaktur($isPaidFaktur,$fin_customer_id,$fstCurrCode,$search);		
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$invList,
		]);
	}
	public function get_sell_unit_list(){
		$this->load->model("msitemunitdetails_model");
		$this->load->model("msitems_model");
		$finCustomerId = $this->input->get("finCustomerId");
		$finItemId = $this->input->get("finItemId");

		$unitList = $this->msitemunitdetails_model->getSellingListUnit($finItemId);
		$result = [];
		foreach($unitList as $unit){
			$result[] = [
				"fst_unit"=>$unit->fst_unit,
				"fdc_price"=>$this->msitems_model->getSellingPrice($finItemId,$unit->fst_unit,$finCustomerId)
			];
		}
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$result,
		]);
		die();

		//params.finCustomerId = $("#fin_customer_id").val();
		//params.finItemId =$("#fin_item_id").val();


	}

}    
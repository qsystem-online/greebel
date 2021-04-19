<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends MY_Controller{
    public $menuName="invoice"; 

    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
        $this->load->model('trinvoice_model');
        $this->load->model("trinvoicedetails_model");
        $this->load->model("trinvoiceitems_model");
        $this->load->model('msrelations_model');
        $this->load->model('users_model');
        $this->load->model('mswarehouse_model');
        $this->load->model('mscurrencies_model');

    }

    public function index(){
        parent::index();
        $this->lizt();
    }
    public function lizt(){
        $this->load->library('menus');
        parent::index();

		$this->list['page_name'] = "Invoice ";
		$this->list['list_name'] = "Invoice List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/sales/invoice/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/sales/invoice/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/sales/invoice/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/sales/invoice/edit/';
		$this->list['arrSearch'] = [
            'fst_inv_no' => 'Invoice No',
            'fst_relation_name'=>'Customer'            
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Surat Jalan', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Invoice ID', 'width' => '10%', 'data' => 'fin_inv_id'],
			['title' => 'Invoice No', 'width' => '10%', 'data' => 'fst_inv_no'],
            ['title' => 'Invoice Date', 'width' => '15%', 'data' => 'fdt_inv_datetime'],
            ['title' => 'Customer', 'width' => '20%', 'data' => 'fst_relation_name'],
            ['title' => 'Memo', 'width' => '20%', 'data' => 'fst_inv_memo'],
            ['title' => 'Action', 'width' => '10%', 'sortable' => false, 'className' => 'dt-body-center text-center',
                'render'=>'function( data, type, row, meta ) {
                    //return "<div style=\'font-size:16px\'><a data-id=\'" + row.fin_inv_id + "\' class=\'btn-edit\' href=\'#\'><i class=\'fa fa-pencil\'></i></a><a class=\'btn-delete\' href=\'#\'><i class=\'fa fa-trash\'></i></a></div>";
                    return "<div style=\'font-size:16px\'><a data-id=\'" + row.fin_inv_id + "\' class=\'btn-edit\' href=\'#\'><i class=\'fa fa-pencil\'></i></a></div>";
                }',
            ]
		];
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('template/standardList', $this->list, true);
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
		$this->datatables->setTableName("(select a.*,b.fst_relation_name from trinvoice a inner join msrelations b on a.fin_relation_id = b.fin_relation_id) a");

		$selectFields = "a.fin_inv_id,a.fst_inv_no,a.fdt_inv_datetime,a.fst_inv_memo,a.fst_relation_name";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
        $arrDataFormated = [];
        $arrDataFormated = $arrData;

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

    private function openForm($mode = "ADD", $fin_inv_id = 0){
        $this->load->library("menus");		
        $this->load->model("glaccounts_model");
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Add Invoice") : lang("Update Invoice");
        $mdlJurnal =$this->parser->parse('template/mdlJurnal.php', [], true);
        $data["mdlJurnal"] = $mdlJurnal;
        $edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
        $data["mdlEditForm"] = $edit_modal;
        $mdlPrint = $this->parser->parse('template/mdlPrint', [], true);
        $data["mdlPrint"] = $mdlPrint;
       
       
		if($mode == 'ADD'){
			$data["fin_inv_id"] = 0;
			$data["fst_inv_no"] = $this->trinvoice_model->generateInvoiceNo();				
		}else{
			$data["fin_inv_id"] = $fin_inv_id;
			$data["fst_inv_no"] = "";
        }
        
		
		$page_content = $this->parser->parse('pages/tr/sales/invoice/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function add(){
        parent::add();
		$this->openForm("ADD", 0);
	}

	public function edit($fin_inv_id){
        parent::edit($fin_inv_id);
		$this->openForm("EDIT", $fin_inv_id);
    }

    public function fetch_data($fin_inv_id){
		$this->load->model("trinvoice_model");
		$data = $this->trinvoice_model->getDataById($fin_inv_id);		
		$this->json_output($data);
	}
    
    public function get_select2_uninvoice_sj($finSalesOrderId){
        $arrSJ = $this->trinvoice_model->get_select2_uninvoice_sj($finSalesOrderId);
        if (!$arrSJ){
            $arrSJ =[];
        }
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
            "arrSJ"=>$arrSJ,
        ];
        $this->json_output();
    }

    public function get_select2_salesorder_list(){
        $arrSalesOrder = $this->trinvoice_model->getSalesOrderList();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
            "arrSalesOrder"=>$arrSalesOrder,
        ];
        $this->json_output();
    }

    public function get_detail_sj(){
        $fst_sj_id_list = $this->input->post("fst_sj_id_list");
        $arrSJIdList = explode(",",$fst_sj_id_list);
        $arrSJDetail = $this->trinvoice_model->getDetailSJ($arrSJIdList);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
            "arrSJDetail"=>$arrSJDetail,
        ];
        $this->json_output();


    }

    public function ajx_add_save(){   
        parent::ajx_add_save();     
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsalesorder_model");
        $this->load->model("trinventory_model");

        try{
            //CEK CLOSE DATE
            $fdt_inv_datetime = dBDateTimeFormat($this->input->post("fdt_inv_datetime"));		
            $resp = dateIsLock($fdt_inv_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            //PREPARE DATA
            $salesOrder = $this->trsalesorder_model->getDataHeaderById($this->input->post("fin_salesorder_id"));
            if($salesOrder == null){
                throw new CustomException(lang("ID Sales Order tidak dikenal !"),3003,"VALIDATION_FORM_FAILED",["fin_salesorder_id"=>lang("ID Sales Order tidak dikenal !")]);
            }

            $fstInvNo = $this->trinvoice_model->generateInvoiceNo();
            $dataH = [
                "fst_inv_no"=>$fstInvNo,
                "fdt_inv_datetime"=>$fdt_inv_datetime,
                "fin_relation_id"=>$salesOrder->fin_relation_id,
                "fin_salesorder_id"=>$this->input->post("fin_salesorder_id"),
                "fin_branch_id"=>$this->aauth->get_active_branch_id(),
                "fin_warehouse_id"=>$salesOrder->fin_warehouse_id,
                "fst_curr_code"=>$salesOrder->fst_curr_code,
                "fdc_exchange_rate_idr"=> parseNumber($this->input->post("fdc_exchange_rate_idr")),
                "fst_inv_memo"=>$this->input->post("fst_inv_memo"),
                "fbl_is_vat_include"=>$salesOrder->fbl_is_vat_include,
                "fin_terms_payment"=>$this->input->post("fin_terms_payment"),
                "fin_sales_id"=>$salesOrder->fin_sales_id,
                "fdc_subttl"=> 0,
                "fdc_disc_amount"=>0,
                "fdc_ppn_percent"=>$salesOrder->fdc_vat_percent,
                "fdc_ppn_amount"=>0,
                "fdc_downpayment_claim"=>$this->input->post("fdc_downpayment_claim"),
                "fdc_total"=>0,
                "fst_reff_no"=>$this->input->post("fst_reff_no"),
                "fst_active"=>"A",
            ];

            
            $ttlNoDisc =0;
            $ttlDisc = 0;
            $subTotalDPP = 0;
            $ppnTotal =0;
            $ttlDisc =0;
            $total = 0;
                        
            $detailData = $this->trinvoice_model->getDetailSJ($this->input->post("fst_sj_id_list"));
            $dataItemList = [];

            for($i = 0;$i < sizeof($detailData);$i++){                
                $dataD = $detailData[$i];
                $dataItemList[] = [
                    "fin_inv_id"=>0,
                    "fin_item_id"=>$dataD->fin_item_id,
                    "fst_custom_item_name"=>$dataD->fst_custom_item_name,
                    "fst_unit"=>$dataD->fst_unit,
                    "fdb_qty"=>$dataD->fdb_qty_sj,
                    "fdc_price"=>$dataD->fdc_price,
                    "fst_disc_item"=>$dataD->fst_disc_item,
                    "fdc_disc_amount_per_item"=>$dataD->fdc_disc_amount_per_item,
                    "fin_promo_id"=>$dataD->fin_promo_id,
                    "fst_active"=>"A",
                ];                
                $ttlNoDisc += $dataD->fdb_qty_sj * $dataD->fdc_price;
                $ttlDisc += $dataD->fdb_qty_sj * $dataD->fdc_disc_amount_per_item;                
            }


            if($dataH["fbl_is_vat_include"] == 1){
                $subTotal = $ttlNoDisc - $ttlDisc;
                $subTotalDPP = $subTotal / (1+ ($dataH["fdc_ppn_percent"] / 100));                
            }else{
                $subTotalDPP = $ttlNoDisc - $ttlDisc;
            }

            
            $dataH["fdc_subttl"] = $ttlNoDisc;
            $dataH["fdc_dpp_amount"] = $subTotalDPP;
            $dataH["fdc_disc_amount"] = $ttlDisc;
            $dataH["fdc_ppn_amount"] = $subTotalDPP * ($dataH["fdc_ppn_percent"] / 100);
            //$dataH["fdc_total"] = $ttlNoDisc - $ttlDisc + $dataH["fdc_ppn_amount"] - $dataH["fdc_downpayment_claim"];
            $dataH["fdc_total"] = $subTotalDPP + $dataH["fdc_ppn_amount"] - $dataH["fdc_downpayment_claim"];
                        
            //VALIDASI DATA
            $this->validation_data($dataH,$detailData,$dataItemList,$salesOrder);
            
        }catch(CustomException $e){
            $this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
        }

        try{
            $this->db->trans_start();
            //SAVE
            $insertId = $this->trinvoice_model->insert($dataH);
            //$detailData = explode(",",$this->input->post("fst_sj_id_list"));
            $detailData = $this->input->post("fst_sj_id_list");           
            foreach($detailData as $finSJId){                
                $data =[
                    "fin_inv_id"=>$insertId,
                    "fin_sj_id"=>$finSJId,
                    "fst_active"=>"A"
                ];                
                $this->trinvoicedetails_model->insert($data);
            }
            foreach($dataItemList as $dataItem){
                $dataItem["fin_inv_id"] = $insertId;
                $this->trinvoiceitems_model->insert($dataItem);
            }
            
            //POSTING
            $this->trinvoice_model->posting($insertId);
            
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = lang("Data saved !");
            $this->json_output();
            return;

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
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsalesorder_model");
        $this->load->model("trinventory_model");
        

        try{
            $finInvId = $this->input->post("fin_inv_id");
            $dataHOld = $this->trinvoice_model->getDataHeaderById($finInvId);
            if ($dataHOld == null){
                throw new CustomException(lang("ID Invoice tidak dikenal !"),3003,"FAILED",null);                
            }

            //CEK CLOSE DATE
            $resp = dateIsLock($dataHOld->fdt_inv_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            $fdt_inv_datetime = dBDateTimeFormat($this->input->post("fdt_inv_datetime"));		
            $resp = dateIsLock($fdt_inv_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }
            
            //IS EDITABLE
            $this->trinvoice_model->isEditable($dataHOld);
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
            $this->trinvoice_model->unposting($finInvId);

            //DELETE DETAIL
            $this->trinvoice_model->deleteDetail($finInvId);

            //PREPARE DATA
            $salesOrder = $this->trsalesorder_model->getDataHeaderById($this->input->post("fin_salesorder_id"));
            if($salesOrder == null){
                throw new CustomException(lang("ID Sales Order tidak dikenal !"),3003,"VALIDATION_FORM_FAILED",["fin_salesorder_id"=>lang("ID Sales Order tidak dikenal !")]);
            }
            
            $dataH = [
                "fin_inv_id"=>$dataHOld->fin_inv_id,
                "fst_inv_no"=>$dataHOld->fst_inv_no,
                "fdt_inv_datetime"=>$fdt_inv_datetime,
                "fin_relation_id"=>$salesOrder->fin_relation_id,
                "fin_salesorder_id"=>$this->input->post("fin_salesorder_id"),
                "fin_branch_id"=>$this->aauth->get_active_branch_id(),
                "fin_warehouse_id"=>$salesOrder->fin_warehouse_id,
                "fst_curr_code"=>$salesOrder->fst_curr_code,
                "fdc_exchange_rate_idr"=> parseNumber($this->input->post("fdc_exchange_rate_idr")),
                "fst_inv_memo"=>$this->input->post("fst_inv_memo"),
                "fbl_is_vat_include"=>$salesOrder->fbl_is_vat_include,
                "fin_terms_payment"=>$this->input->post("fin_terms_payment"),
                "fin_sales_id"=>$salesOrder->fin_sales_id,
                "fdc_subttl"=> 0,
                "fdc_disc_amount"=>0,
                "fdc_ppn_percent"=>$salesOrder->fdc_vat_percent,
                "fdc_ppn_amount"=>0,
                "fdc_downpayment_claim"=>$this->input->post("fdc_downpayment_claim"),
                "fdc_total"=>0,
                "fst_reff_no"=>$this->input->post("fst_reff_no"),
                "fst_active"=>"A",
            ];

            
            $ttlNoDisc =0;
            $ttlDisc = 0;
            $subTotalDPP = 0;
            $ppnTotal =0;
            $ttlDisc =0;
            $total = 0;
                    
            $detailData = $this->trinvoice_model->getDetailSJ($this->input->post("fst_sj_id_list"));
            $dataItemList = [];

            for($i = 0;$i < sizeof($detailData);$i++){
                //b.fin_item_id,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdc_disc_amount_per_item,sum(b.fdb_qty) as fdb_qty_so,sum(a.fdb_qty) as fdb_qty_sj                
                $dataD = $detailData[$i];
                $dataItemList[] = [
                    "fin_inv_id"=>0,
                    "fin_item_id"=>$dataD->fin_item_id,
                    "fst_custom_item_name"=>$dataD->fst_custom_item_name,
                    "fst_unit"=>$dataD->fst_unit,
                    "fdb_qty"=>$dataD->fdb_qty_sj,
                    "fdc_price"=>$dataD->fdc_price,
                    "fst_disc_item"=>$dataD->fst_disc_item,
                    "fdc_disc_amount_per_item"=>$dataD->fdc_disc_amount_per_item,
                    "fin_promo_id"=>$dataD->fin_promo_id,
                    "fst_active"=>"A",
                ];
                $ttlNoDisc += $dataD->fdb_qty_sj * $dataD->fdc_price;
                $ttlDisc += $dataD->fdb_qty_sj * $dataD->fdc_disc_amount_per_item;                
            }
            if($dataH["fbl_is_vat_include"] == 1){
                $subTotal = $ttlNoDisc - $ttlDisc;
                $subTotalDPP = $subTotal / (1+ ($dataH["fdc_ppn_percent"] / 100));                
            }else{
                $subTotalDPP = $ttlNoDisc - $ttlDisc;
            }

            
            $dataH["fdc_subttl"] = $ttlNoDisc;
            $dataH["fdc_dpp_amount"] = $subTotalDPP;
            $dataH["fdc_disc_amount"] = $ttlDisc;
            $dataH["fdc_ppn_amount"] = $subTotalDPP * ($dataH["fdc_ppn_percent"] / 100);
            //$dataH["fdc_total"] = $ttlNoDisc - $ttlDisc + $dataH["fdc_ppn_amount"] - $dataH["fdc_downpayment_claim"];
            $dataH["fdc_total"] = $subTotalDPP + $dataH["fdc_ppn_amount"] - $dataH["fdc_downpayment_claim"];
            
            //VALIDASI DATA
            $this->validation_data($dataH,$detailData,$dataItemList,$salesOrder);
            
            
            
            //SAVE DATA
            $insertId = $dataH["fin_inv_id"];            
            $this->trinvoice_model->update($dataH);

            $detailData = $this->input->post("fst_sj_id_list");           
            foreach($detailData as $finSJId){
                $data =[
                    "fin_inv_id"=>$insertId,
                    "fin_sj_id"=>$finSJId,
                    "fst_active"=>"A"
                ];                
                $this->trinvoicedetails_model->insert($data);
            }
            foreach($dataItemList as $dataItem){
                $dataItem["fin_inv_id"] = $insertId;
                $this->trinvoiceitems_model->insert($dataItem);
            }

            //POSTING
            $this->trinvoice_model->posting($finInvId);

            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = lang("Data saved !");
            $this->json_output();
            return;
        }catch(CustomException $e){
            $this->db->trans_rollback();
            $this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
        }
    }
    
    public function delete($finInvId){
        parent::delete($finInvId);
        try{
            
            $this->db->trans_start();

            $dataHOld = $this->trinvoice_model->getDataHeaderById($finInvId);
            if ($dataHOld == null){
                throw new CustomException(lang("ID Invoice tidak dikenal !"),3003,"FAILED",null);                
            }

            //CEK CLOSE DATE
            $resp = dateIsLock($dataHOld->fdt_inv_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }          
            
            //IS EDITABLE
            $this->trinvoice_model->isEditable($dataHOld);

            $this->trinvoice_model->unposting($finInvId);

            $this->trinvoice_model->delete($finInvId);

            $this->db->trans_complete();

            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = lang("Data dihapus !");
            //$this->ajxResp["data"]["insert_id"] = $insertId;
            $this->json_output();

        }catch(CustomException $e){
            $this->db->trans_rollback();
            $this->ajxResp["status"] = $e->getStatus();
            $this->ajxResp["message"] = $e->getMessage();
            $this->ajxResp["data"] = $e->getData();
            $this->json_output();
        }
        
    }

    private function validation_data($dataH,$dataDetail,$dataItemList,$salesOrder){
        //Validation Header
        $this->form_validation->set_rules($this->trinvoice_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        $this->form_validation->set_data($dataH);
        if ($this->form_validation->run() == FALSE) {
            throw new CustomException(lang("Error Validation Forms"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

        //Validation Detail
        /*
        $this->form_validation->set_rules($this->trinvoicedetails_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');        
        foreach($dataDetail as $dataD){
            var_dump($dataD);
            $this->form_validation->set_data((array) $dataD);
            if ($this->form_validation->run() == FALSE) {
                throw new CustomException(lang("Error Validation Details"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
            }
        } 
        */ 

        //Cek DP Claim tidak melebih batas;
        if ( ($salesOrder->fdc_downpayment_paid - $salesOrder->fdc_downpayment_claimed) < $dataH["fdc_downpayment_claim"] ){
            throw new CustomException(lang("DP yang diklaim melebih batas yang tersedia !"),3003,"FAILED",null);                
        }


    }

    public function print_voucher($finInvId){
		$this->data = $this->trinvoice_model->getDataVoucher($finInvId);
		//$data=[];
		$this->data["title"] = "Invoice";		
		$page_content = $this->parser->parse('pages/tr/sales/invoice/voucher', $this->data, true);
		$this->data["PAGE_CONTENT"] = $page_content;	
		$strHtml = $this->parser->parse('template/voucher_pdf', $this->data, true);

		//$this->parser->parse('template/voucher', $this->data);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;				
		
        $mpdf->WriteHTML($strHtml);	
        $mpdf->Output();		
		//echo $strHtml;
    }
    
}

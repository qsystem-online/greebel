<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trinvoice_model');
    }

    public function index(){
       $this->lizt();
    }
    public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Invoice ";
		$this->list['list_name'] = "Invoice List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/invoice/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/invoice/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/invoice/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/invoice/edit/';
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
			['title' => 'Invoice ID', 'width' => '20%', 'data' => 'fin_inv_id'],
			['title' => 'Invoice No', 'width' => '20%', 'data' => 'fst_inv_no'],
            ['title' => 'Invoice Date', 'width' => '20%', 'data' => 'fdt_inv_date'],
            ['title' => 'Customer', 'width' => '20%', 'data' => 'fst_relation_name'],
            ['title' => 'Memo', 'width' => '20%', 'data' => 'fst_inv_memo'],
            ['title' => 'Action', 'width' => '15%', 'sortable' => false, 'className' => 'dt-body-center text-center',
                'render'=>'function( data, type, row, meta ) {
                    return "<div style=\'font-size:16px\'><a data-id=\'" + row.fin_inv_id + "\' class=\'btn-edit\' href=\'#\'><i class=\'fa fa-pencil\'></i></a><a class=\'btn-delete\' href=\'#\'><i class=\'fa fa-trash\'></i></a></div>";
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

		$selectFields = "a.fin_inv_id,a.fst_inv_no,a.fdt_inv_date,a.fst_inv_memo,a.fst_relation_name";
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

    public function initVarForm(){
        $this->load->model("mswarehouse_model");
        $this->load->model("users_model");
        $this->load->library("select2");
        
        $branchId = $this->aauth->get_active_branch_id();
        
        //Get Data Customer
        $arrCustomer = $this->select2->get_customer($branchId);
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
            "arrCustomer"=>$arrCustomer,
        ];
        $this->json_output();


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
        $mdlJurnal =$this->parser->parse('template/mdlJUrnal.php', [], true);
        $data["mdlJurnal"] = $mdlJurnal;

        $piutangAcc = $this->glaccounts_model->getDataById(getGLConfig("SO_PIUTANG"))["gl_Account"];
        $discAcc = $this->glaccounts_model->getDataById(getGLConfig("INV_DISC"))["gl_Account"];
        $uangMukaAcc= $this->glaccounts_model->getDataById(getGLConfig("SO_DP"))["gl_Account"];
        $salesAcc = $this->glaccounts_model->getDataById(getGLConfig("INV_SALES"))["gl_Account"];
        $ppnAcc = $this->glaccounts_model->getDataById(getGLConfig("INV_PPN"))["gl_Account"];
        $jurnalAcc = [
            "piutang_dagang" => ["code"=>$piutangAcc->fst_glaccount_code,"name"=>$piutangAcc->fst_glaccount_name,"pos"=>"D"],
            "disc_dagang" => ["code"=>$discAcc->fst_glaccount_code,"name"=>$discAcc->fst_glaccount_name,"pos"=>"D"],
            "uang_muka"=> ["code"=>$uangMukaAcc->fst_glaccount_code,"name"=>$uangMukaAcc->fst_glaccount_name,"pos"=>"D"],
            "sales"=> ["code"=>$salesAcc->fst_glaccount_code,"name"=>$salesAcc->fst_glaccount_name,"pos"=>"C"],
            "ppn"=>["code"=>$ppnAcc->fst_glaccount_code,"name"=>$ppnAcc->fst_glaccount_name,"pos"=>"C"],
        ];

        $data["jurnalAcc"] = $jurnalAcc;
        
		if($mode == 'ADD'){
			$data["fin_inv_id"] = 0;
			$data["fst_inv_no"] = $this->trinvoice_model->generateInvoiceNo();				
		}else{
			$data["fin_inv_id"] = $fin_inv_id;
			$data["fst_inv_no"] = "";
        }
        
		
		$page_content = $this->parser->parse('pages/tr/invoice/form', $data, true);
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
		$this->openForm("ADD", 0);
	}

	public function Edit($fin_inv_id){
		$this->openForm("EDIT", $fin_inv_id);
    }
    public function fetch_data($fin_inv_id){
		$this->load->model("trinvoice_model");
		$data = $this->trinvoice_model->getDataById($fin_inv_id);		
		$this->json_output($data);
	}
    

    public function get_select2_uninvoice_sj($customerId,$invId){
        $arrSJ = $this->trinvoice_model->get_select2_uninvoice_sj($customerId,$invId);
        if (!$arrSJ){
            $arrSJ =[];
        }
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
            "arrSJ"=>$arrSJ,
        ];
        $this->json_output();
    }

    public function ajx_add_save(){
        $this->load->model("trinvoice_model");
        $this->load->model("trinvoicedetails_model");
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsalesorder_model");
        $this->load->model("trinventory_model");
        
        
        $dataH = $this->input->post();        
        $dataH["fin_sj_id"] = $this->input->post("fst_sj_id_list");
        
        $this->form_validation->set_rules($this->trinvoice_model->getRules("ADD", 0));
        $this->form_validation->set_data($dataH);        
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $dataH;
			$this->json_output();
			return;
        }
        $dataH["fdt_inv_date"] = dBDateTimeFormat($dataH["fdt_inv_date"]);
        $dataH["fst_inv_no"] = $this->trinvoice_model->generateInvoiceNo($dataH["fdt_inv_date"]);
        
        $dataH["fin_branch_id"] = $this->aauth->get_active_branch_id();
        $sj = $this->trsuratjalan_model->createObject($dataH["fin_sj_id"]);
        
        $dataH["fin_warehouse_id"] = $sj->fin_warehouse_id;
        $so = $this->trsalesorder_model->createObject($sj->fin_salesorder_id);
        
        $dataH["fst_curr_code"] = $so->fst_curr_code;
        $dataH["fdc_exchange_rate_idr"] =  1;
        $dataH["fbl_is_vat_include"] = $so->fbl_is_vat_include;
        $dataH["fdc_vat_percent"] = $so->fdc_vat_percent;
        $dataH["fdt_payment_due_date"] = add_date($dataH["fdt_inv_date"],$dataH["fin_terms_payment"]);
        //$dataH["fdc_downpayment_claimed"] =  $so->fdc_downpayment_paid;
        $dataH["fin_salesorder_id"] = $sj->fin_salesorder_id;
        $dataH["fdc_downpayment_claimed"] = parseNumber($dataH["fdc_downpayment_claimed"]);

        //validasi downpayment claimed not over
        $dpAvailable =$so->getDPAvailableToClaimed($sj->fin_salesorder_id);
        if ($dataH["fdc_downpayment_claimed"]  > $dpAvailable ){
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = ["fdc_downpayment_claimed"=>sprintf(lang("DP yang bisa di klaim tidak boleh melebihi %s"), $dpAvailable)];
			$this->ajxResp["request_data"] = $dataH;
			$this->json_output();
            return;
        }       


        //get Detail from SJ
        $details = $this->trinvoice_model->detailBySJ($dataH["fin_sj_id"]);
        $ttlBfDisc =0;
        $ttlDisc =0;

        for($i = 0; $i< sizeof($details); $i++){
            $detail = $details[$i];
            $total = (float) $detail->fdb_qty *  (float) $detail->fdc_price;
            $ttlBfDisc += $total;
            $discAmount = calculateDisc($detail->fst_disc_item,$total);
            $ttlDisc += $discAmount;
            $details[$i]->fdc_disc_amount = $discAmount;
        }

        if($dataH["fbl_is_vat_include"] == 1 ){
			$total = $ttlBfDisc - $ttlDisc;	
			$vat = 1 + ($dataH["fdc_vat_percent"] * 1) / 100;
			$totalDPP = $total / $vat;
		}else{
			$totalDPP = $subTotal -  $totalDisc;			
        }

        $dataH["fdc_dpp_amount"] = $totalDPP;        
        $dataH["fdc_vat_amount"] = $totalDPP * ($dataH["fdc_vat_percent"]/100);
        $dataH["fdc_disc_percent"] = $ttlDisc / $ttlBfDisc;
        $dataH["fdc_disc_amount"] = $ttlDisc;        
        $dataH["fst_inv_no"] = $this->trinvoice_model->generateInvoiceNo($dataH["fdt_inv_date"]);
        $dataH["fst_active"] = "A";
    

        $this->db->trans_start();
        $insertId = $this->trinvoice_model->insert($dataH);

        foreach($details as $detail){
            $detail = (array) $detail;
            //fin_rec_id
            $detail["fin_inv_id"] = $insertId;
            $detail["fst_active"] ="A";                    
            $this->trinvoicedetails_model->insert((array)$detail);
        }

        $this->trinvoice_model->posting($insertId);

        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    
    }

    public function ajx_edit_save(){
        $this->load->model("trinvoice_model");
        $this->load->model("trinvoicedetails_model");
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsalesorder_model");
        $this->load->model("trinventory_model");
        
        //chek if invoice already paid
        $inv = $this->trinvoice_model->createObject($this->input->post("fin_inv_id"));
        if ($inv->isPaid()){
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Tidak bisa merubah invoice yang telah di bayar");
			$this->ajxResp["data"] = [];
			$this->ajxResp["request_data"] = [];
			$this->json_output();
			return;
        }
        
        
        $dataH = $this->input->post();        
        $dataH["fin_sj_id"] = $this->input->post("fst_sj_id_list");
        
        $this->form_validation->set_rules($this->trinvoice_model->getRules("ADD", 0));
        $this->form_validation->set_data($dataH);        
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->ajxResp["request_data"] = $dataH;
			$this->json_output();
			return;
        }
        $dataH["fdt_inv_date"] = dBDateTimeFormat($dataH["fdt_inv_date"]);
        //$dataH["fst_inv_no"] = $this->trinvoice_model->generateInvoiceNo($dataH["fdt_inv_date"]);
        
        $dataH["fin_branch_id"] = $this->aauth->get_active_branch_id();
        $sj = $this->trsuratjalan_model->createObject($dataH["fin_sj_id"]);        
        $dataH["fin_warehouse_id"] = $sj->fin_warehouse_id;
        $so = $this->trsalesorder_model->createObject($sj->fin_salesorder_id);
        
        $dataH["fst_curr_code"] = $so->fst_curr_code;
        $dataH["fdc_exchange_rate_idr"] =  1;
        $dataH["fbl_is_vat_include"] = $so->fbl_is_vat_include;
        $dataH["fdc_vat_percent"] = $so->fdc_vat_percent;
        $dataH["fdt_payment_due_date"] = add_date($dataH["fdt_inv_date"],$dataH["fin_terms_payment"]);
        $dataH["fdc_downpayment_claimed"] =  parseNumber($dataH["fdc_downpayment_claimed"]); //$so->fdc_downpayment_paid;
        //validasi downpayment claimed not over
        $dpAvailable = $so->getDPAvailableToClaimed($sj->fin_salesorder_id,$dataH["fin_inv_id"]);
        if ($dataH["fdc_downpayment_claimed"]  > $dpAvailable ){
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = ["fdc_downpayment_claimed"=>sprintf(lang("DP yang bisa di klaim tidak boleh melebihi %s"), $dpAvailable)];
			$this->ajxResp["request_data"] = $dataH;
			$this->json_output();
            return;
        }      

        //get Detail from SJ
        $details = $this->trinvoice_model->detailBySJ($dataH["fin_sj_id"]);
        $ttlBfDisc =0;
        $ttlDisc =0;

        for($i = 0; $i< sizeof($details); $i++){
            $detail = $details[$i];

            $total = (float) $detail->fdb_qty *  (float) $detail->fdc_price;
            $ttlBfDisc += $total;
            $discAmount = calculateDisc($detail->fst_disc_item,$total);
            $ttlDisc += $discAmount;
            $details[$i]->fdc_disc_amount = $discAmount;
        }

        if($dataH["fbl_is_vat_include"] == 1 ){
			$total = $ttlBfDisc - $ttlDisc;	
			$vat = 1 + ($dataH["fdc_vat_percent"] * 1) / 100;
			$totalDPP = $total / $vat;
		}else{
			$totalDPP = $subTotal -  $totalDisc;			
        }

        $dataH["fdc_dpp_amount"] = $totalDPP;        
        $dataH["fdc_vat_amount"] = $totalDPP * ($dataH["fdc_vat_percent"]/100);
        $dataH["fdc_disc_percent"] = $ttlDisc / $ttlBfDisc;
        $dataH["fdc_disc_amount"] = $ttlDisc;        
        $dataH["fst_inv_no"] = $this->trinvoice_model->generateInvoiceNo($dataH["fdt_inv_date"]);
        $dataH["fst_active"] = "A";
    

        $this->db->trans_start();
        $this->trinvoice_model->unposting($dataH["fin_inv_id"],$dataH["fdt_inv_date"]);
        //$this->trinvoice_model->delete($dataH["fin_inv_id"]);

        
        $this->trinvoice_model->update($dataH);
        $this->trinvoice_model->deleteDetail($dataH["fin_inv_id"]);


        foreach($details as $detail){
            $detail = (array) $detail;
            //fin_rec_id
            $detail["fin_inv_id"] = $dataH["fin_inv_id"];
            $detail["fst_active"] ="A";                    
            $this->trinvoicedetails_model->insert((array)$detail);
        }

        $this->trinvoice_model->posting($dataH["fin_inv_id"]);

        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $dataH["fin_inv_id"];
		$this->json_output();
    
    }
    
    public function delete($invId){
        $this->db->trans_start();
        $this->trinvoice_model->delete($invId);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }
}

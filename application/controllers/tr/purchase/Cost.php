<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cost extends MY_Controller{
    public function __construct(){
		parent::__construct();
        $this->load->library('form_validation');						
        $this->load->model("trpurchasecost_model");
        $this->load->model("trpurchasecostitems_model");
        $this->load->model('mscurrencies_model');                
        $this->load->model('glaccounts_model');
        $this->load->model('profitcostcenter_model');
        $this->load->model('msdepartments_model');
        $this->load->model('msrelations_model');
        $this->load->model('msprojects_model');
        $this->load->model('trpo_model');
    }

    public function index(){

		$this->load->library('menus');
        $this->list['page_name'] = lang("Biaya - Pembelian");
        $this->list['list_name'] = "Daftar Biaya Pembelian";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/purchase/cost/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> ".lang("Tambah Data")."</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase/cost/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_purchasecost_no' => 'No Retur Pembelian',
			'fst_supplier_name' => 'Supplier'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => lang('Home'), 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => lang('Pembelian'), 'link' => '#', 'icon' => ''],
            ['title' => lang('Biaya'), 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID.', 'width' => '0px','visible'=>'false', 'data' => 'fin_purchasecost_id'],
            ['title' => 'No. Biaya Pembelian', 'width' => '150px', 'data' => 'fst_purchasecost_no'],
            ['title' => 'Tanggal', 'width' => '100px', 'data' => 'fdt_purchasecost_datetime'],
            ['title' => 'Supplier', 'width' => '100px', 'data' => 'fst_supplier_name'],
			['title' => 'No. PO', 'width' => '100px', 'data' => 'fst_po_no'],			
            ['title' => 'Memo', 'width' => '200px', 'data' => 'fst_memo'],
            ['title' => 'Total', 'width' => '100px', 'data' => 'fdc_total','className'=>'text-right',
                'render'=>"function(data,type,row){
                    return App.money_format(data);
                }",
            ],
			['title' => 'Action', 'width' => '100px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/purchase/cost/edit/' + row.fin_purchasecost_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];
		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase/cost/listjs', [], true);
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
			SELECT a.*,b.fst_po_no,c.fst_relation_name as fst_supplier_name FROM trpurchasecost a 
			INNER JOIN trpo b on a.fin_po_id = b.fin_po_id  
			INNER JOIN msrelations c on a.fin_supplier_id = c.fin_relation_id 
			) a");

        $selectFields = "a.fin_purchasecost_id,a.fst_purchasecost_no,a.fdt_purchasecost_datetime,a.fst_po_no,a.fst_supplier_name,a.fst_memo,a.fdc_total";
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
    public function edit($finPurchaseCostId){
        $this->openForm("EDIT", $finPurchaseCostId);
    }
    
    private function openForm($mode = "ADD", $finPurchaseCostId = 0){
        $this->load->library("menus");			

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Biaya Pembelian") : lang("Update Biaya Pembelian");
		$data["fin_purchasecost_id"] = $finPurchaseCostId;
		$data["mdlEditForm"] = $edit_modal;

        $data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();	                			
		if($mode == 'ADD'){
			$data["fst_purchasecost_no"]=$this->trpurchasecost_model->generatePurchaseCostNo(); 
			$data["mdlJurnal"] = "";
		}else if($mode == 'EDIT'){
			$data["fst_purchasecost_no"]="";	
			$data["mdlJurnal"] = $jurnal_modal;
        }        
		
		$page_content = $this->parser->parse('pages/tr/purchase/cost/form', $data, true);
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
            array (size=9)
            'fin_purchasecost_id' => string '0' (length=1)
            'fbl_is_import' => string '0' (length=1)
            'fst_purchasecost_no' => string 'PCS/JKT/2019/11/00001' (length=21)
            'fdt_purchasecost_datetime' => string '12-11-2019 15:29:01' (length=19)
            'fin_po_id' => string '25' (length=2)
            'fin_supplier_id' => string '151' (length=3)
            'fdc_exchange_rate_idr' => string '1' (length=1)
            'fst_memo' => string 'testing memo nih' (length=16)
            'details' => string '[
                {"fin_rec_id":0,"fst_glaccount_code":"612.111.007","fst_glaccount_title":"612.111.007 - BIAYA POS (PENGIRIMAN)","fst_notes":"testing ","fin_pcc_id":"2","fst_pcc_title":"Divisi Marketing","fdc_debet":"200000","fdc_credit":"0.00"},
                {"fin_rec_id":0,"fst_glaccount_code":"611.111.004","fst_glaccount_title":"611.111.004 - BIAYA SERAGAM","fst_notes":"test seragan ","fin_pcc_id":"3","fst_pcc_title":"Divisi Human Resourch","fdc_debet":"300000","fdc_credit":"0.00"},
                {"fin_rec_id":0,"fst_glaccount_code":"612.111.005","'... (length=699)
		 */

		
		//CEK tgl lock dari transaksi yg di kirim
		$fdt_purchasecost_datetime = dBDateTimeFormat($this->input->post("fdt_purchasecost_datetime"));		
		$resp = dateIsLock($fdt_purchasecost_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}


		//PREPARE DATA
		$fst_purchasecost_no = $this->trpurchasecost_model->generatePurchaseCostNo();
        $fst_curr_code =  $this->input->post("fst_curr_code");
        $fdc_exchange_rate_idr =  parseNumber($this->input->post("fdc_exchange_rate_idr"));

		if ($fst_curr_code == null){
            $defaultCurr = getDefaultCurrency();
            $fst_curr_code = $defaultCurr["CurrCode"];
            $fdc_exchange_rate_idr = 1;
        }

		$dataH =[
            //"fin_purchasereturn_id"
            "fbl_is_import"=>$this->input->post("fbl_is_import"),
			"fst_purchasecost_no" =>$fst_purchasecost_no,
            "fdt_purchasecost_datetime"=>$fdt_purchasecost_datetime,
            "fin_po_id" => $this->input->post("fin_po_id"),        
            "fin_supplier_id" => $this->input->post("fin_supplier_id"),            
            "fst_curr_code"=>$fst_curr_code,            
			"fdc_exchange_rate_idr"=> $fdc_exchange_rate_idr,
			"fdc_total"=>0,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A'
		];

		$postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);
        $dataDetails = [];	
        $ttlDebet = 0;
        $ttlCredit = 0;
		foreach($postDetails as $detail){
			$dataD = [
				//"fin_rec_id"=>0,
				"fin_purchasereturn_id"=>0,
				"fst_glaccount_code"=>$detail->fst_glaccount_code,
				"fst_notes"=>$detail->fst_notes,
				"fdc_debet"=>$detail->fdc_debet,
				"fdc_credit"=>$detail->fdc_credit,
                "fin_pcc_id"=>$detail->fin_pcc_id,
                "fin_pc_divisi_id"=>$detail->fin_pc_divisi_id,
                "fin_pc_customer_id"=>$detail->fin_pc_customer_id,
                "fin_pc_project_id"=>$detail->fin_pc_project_id,
				"fst_active"=>'A'
			];			
            $dataDetails[] = $dataD;			
            $ttlDebet += $dataD["fdc_debet"];
            $ttlCredit += $dataD["fdc_credit"];            
        }
        $ttl = $ttlDebet - $ttlCredit;
        $dataH["fdc_total"] = $ttl;
		
		
		//VALIDATION

		//validation header
		$this->form_validation->set_rules($this->trpurchasecost_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data");
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			die();
        }

        //CHECK IF PO fbl_cost_completed true
        $poH = $this->trpo_model->getDataHeaderById($dataH["fin_po_id"]);
        if($poH->fbl_cost_completed){
            $this->ajxResp["status"] = "FAILED";
            $this->ajxResp["message"] = sprintf(lang("Biaya untuk PO %s telah di tutup !"),$poH->fst_po_no);
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            die();
        }
        
        $this->form_validation->set_rules($this->trpurchasecostitems_model->getRules("ADD", 0));		
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');    
        foreach( $dataDetails  as $dataD){
            $this->form_validation->set_data($dataD);
            if ($this->form_validation->run() == FALSE) {                
                $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
                $this->ajxResp["message"] = lang("Error Validation Data");
                $this->ajxResp["data"] = $this->form_validation->error_array();
                $this->json_output();
                die();
            }
            //Cek Profit Cost Center diisi tidak,Analisa Divisi,Analisa Customer,Analisa Project
            $glAccount = $this->glaccounts_model->getSimpleDataHeader($dataD["fst_glaccount_code"]);
            if($glAccount == null){
                $this->ajxResp["status"] = "FAILED";
                $this->ajxResp["message"] = sprintf(lang("Kode Account %s tidak dikenal !"),$dataD["fst_glaccount_code"]);
                $this->ajxResp["data"] = $this->form_validation->error_array();
                $this->json_output();
                die();
            }
            if($glAccount->fst_glaccount_type == "PROFIT_LOST"){
                if ($dataD["fin_pcc_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Profit & cost center harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }
            if($glAccount->fbl_pc_divisi){
                if ($dataD["fin_pc_divisi_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Analisa divisi harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }
            if($glAccount->fbl_pc_customer){
                if ($dataD["fin_pc_customer_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Analisa customer harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }
            if($glAccount->fbl_pc_project){
                if ($dataD["fin_pc_project_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Analisa project harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }

        }
			
		
		//SAVE
		$this->db->trans_start(); 
		$insertId = $this->trpurchasecost_model->insert($dataH);
		foreach($dataDetails as $dataD){
			$dataD["fin_purchasecost_id"] = $insertId;
			$this->trpurchasecostitems_model->insert($dataD);

        }
        
		//POSTING
		$result = $this->trpurchasecost_model->posting($insertId);
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
		/*
        'fin_purchasecost_id' => string '23' (length=2)
        'fbl_is_import' => string '0' (length=1)
        'fst_purchasecost_no' => string 'PCS/JKT/2019/11/00001' (length=21)
        'fdt_purchasecost_datetime' => string '13-11-2019 10:11:48' (length=19)
        'fin_po_id' => string '25' (length=2)
        'fin_supplier_id' => string '149' (length=3)
        'fdc_exchange_rate_idr' => string '1.00' (length=4)
        'fst_memo' => string 'testing memo' (length=12)
        'fin_user_id_request_by' => string '12' (length=2)
        'fst_edit_notes' => string 'testing dongggggg' (length=17)
        'details' => string '[{"fin_rec_id":"30","fst_glaccount_code":"511.111.002","fst_glaccount_title":"511.111.002 - BIAYA ANGKUT/EKSPEDISI IMPORT","fst_notes":"expedisi1","fin_pcc_id":"2","fst_pcc_title":"Divisi Marketing","fin_pc_divisi_id":null,"fst_pc_divisi_title":"","fin_pc_customer_id":null,"fst_pc_customer_name":"","fin_pc_project_id":null,"fst_pc_project_title":"","fdc_debet":"200000.00","fdc_credit":"0.00"},{"fin_rec_id":"31","fst_glaccount_code":"612.111.007","fst_glaccount_title":"612.111.007 - BIAYA POS (PENGIRIMAN)","'... (length=785)
		 */        

        //CHECK DATA EDITABLE
        //CEK tgl lock dari transaksi yg di kirim
		$fdt_purchasecost_datetime = dBDateTimeFormat($this->input->post("fdt_purchasecost_datetime"));		
		$resp = dateIsLock($fdt_purchasecost_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
        }
        //CEK tgl lock dari transaksi yg ada di database
        $tmpH = $this->trpurchasecost_model->getDataHeaderById($this->input->post("fin_purchasecost_id"));
        $resp = dateIsLock($tmpH->fdt_purchasecost_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
        }

        $editAble = $this->trpurchasecost_model->isEditable($this->input->post("fin_purchasecost_id"));
        if($editAble["status"] != "SUCCESS"){
            $this->ajxResp["status"] = $editAble["status"];
			$this->ajxResp["message"] = $editAble["message"];
			$this->json_output();
			return;
        }


        //UNPOSTING
        $this->db->trans_start(); 
        $result = $this->trpurchasecost_model->unposting($this->input->post("fin_purchasecost_id"));
        if($result["status"] != "SUCCESS"){
            $this->ajxResp["status"] = $result["status"];
			$this->ajxResp["message"] = $result["message"];
            $this->json_output();
            $this->db->trans_rollback();
			return;
        }


        //DELETE DATA
        //Delete Detail
        $result  = $this->trpurchasecostitems_model->deleteById($this->input->post("fin_purchasecost_id"));
        if($result["status"] != "SUCCESS"){
            $this->ajxResp["status"] = $result["status"];
			$this->ajxResp["message"] = $result["message"];
            $this->json_output();
            $this->db->trans_rollback();
			return;
        }

        //PREPARE DATA
        $fst_purchasecost_no = $tmpH->fst_purchasecost_no;
        $fst_curr_code =  parseNumber($this->input->post("fst_curr_code"));
        $fdc_exchange_rate_idr =  $this->input->post("fdc_exchange_rate_idr");
        if ($fst_curr_code == null){
            $defaultCurr = getDefaultCurrency();
            $fst_curr_code = $defaultCurr["CurrCode"];
            $fdc_exchange_rate_idr = 1;
        }        
        $dataH =[
            "fin_purchasecost_id"=>$this->input->post("fin_purchasecost_id"),
            "fbl_is_import"=>$this->input->post("fbl_is_import"),
			"fst_purchasecost_no" =>$fst_purchasecost_no,
            "fdt_purchasecost_datetime"=>$fdt_purchasecost_datetime,
            "fin_po_id" => $this->input->post("fin_po_id"),        
            "fin_supplier_id" => $this->input->post("fin_supplier_id"),            
            "fst_curr_code"=>$fst_curr_code,            
			"fdc_exchange_rate_idr"=> $fdc_exchange_rate_idr,
			"fdc_total"=>0,
			"fst_memo"=>$this->input->post("fst_memo"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>'A'
        ];
        $postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);
        $dataDetails = [];	
        $ttlDebet = 0;
        $ttlCredit = 0;
		foreach($postDetails as $detail){
			$dataD = [
				"fin_rec_id"=>$detail->fin_rec_id,
				"fin_purchasecost_id"=>$dataH["fin_purchasecost_id"],
				"fst_glaccount_code"=>$detail->fst_glaccount_code,
				"fst_notes"=>$detail->fst_notes,
				"fdc_debet"=>$detail->fdc_debet,
				"fdc_credit"=>$detail->fdc_credit,
                "fin_pcc_id"=>$detail->fin_pcc_id,
                "fin_pc_divisi_id"=>$detail->fin_pc_divisi_id,
                "fin_pc_customer_id"=>$detail->fin_pc_customer_id,
                "fin_pc_project_id"=>$detail->fin_pc_project_id,
				"fst_active"=>'A'
            ];            
            if ($detail->fin_rec_id == 0){
                unset($dataD["fin_rec_id"]);
            }
            $dataDetails[] = $dataD;			
            $ttlDebet += $dataD["fdc_debet"];
            $ttlCredit += $dataD["fdc_credit"];            
        }
        $ttl = $ttlDebet - $ttlCredit;
        $dataH["fdc_total"] = $ttl;


        //VALIDATION

        //validation header
		$this->form_validation->set_rules($this->trpurchasecost_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = lang("Error Validation Data");
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			die();
        }

        //CHECK IF PO fbl_cost_completed true
        $poH = $this->trpo_model->getDataHeaderById($dataH["fin_po_id"]);
        if($poH->fbl_cost_completed){
            $this->ajxResp["status"] = "FAILED";
            $this->ajxResp["message"] = sprintf(lang("Biaya untuk PO %s telah di tutup !"),$poH->fst_po_no);
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            die();
        }

        $this->form_validation->set_rules($this->trpurchasecostitems_model->getRules("ADD", 0));		
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');    
        foreach( $dataDetails  as $dataD){
            $this->form_validation->set_data($dataD);
            if ($this->form_validation->run() == FALSE) {                
                $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
                $this->ajxResp["message"] = lang("Error Validation Data");
                $this->ajxResp["data"] = $this->form_validation->error_array();
                $this->json_output();
                die();
            }
            //Cek Profit Cost Center diisi tidak,Analisa Divisi,Analisa Customer,Analisa Project
            $glAccount = $this->glaccounts_model->getSimpleDataHeader($dataD["fst_glaccount_code"]);
            if($glAccount == null){
                $this->ajxResp["status"] = "FAILED";
                $this->ajxResp["message"] = sprintf(lang("Kode Account %s tidak dikenal !"),$dataD["fst_glaccount_code"]);
                $this->ajxResp["data"] = $this->form_validation->error_array();
                $this->json_output();
                die();
            }
            if($glAccount->fst_glaccount_type == "PROFIT_LOST"){
                if ($dataD["fin_pcc_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Profit & cost center harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }
            if($glAccount->fbl_pc_divisi){
                if ($dataD["fin_pc_divisi_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Analisa divisi harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }
            if($glAccount->fbl_pc_customer){
                if ($dataD["fin_pc_customer_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Analisa customer harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }
            if($glAccount->fbl_pc_project){
                if ($dataD["fin_pc_project_id"] == null){
                    $this->ajxResp["status"] = "FAILED";
                    $this->ajxResp["message"] = sprintf(lang("Analisa project harus diisi untuk Account %s !"),$dataD["fst_glaccount_code"]);
                    $this->ajxResp["data"] = $this->form_validation->error_array();
                    $this->json_output();
                    die();
                }
            }

        }

        //SAVE        
        $this->trpurchasecost_model->update($dataH);
        $insertId = $dataH["fin_purchasecost_id"];
        
		foreach($dataDetails as $dataD){
			$this->trpurchasecostitems_model->insert($dataD);
        }
        
		//POSTING
		$result = $this->trpurchasecost_model->posting($insertId);
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
    
    public function fetch_data($finPurchaseCostId){
		$data = $this->trpurchasecost_model->getDataById($finPurchaseCostId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
    }
    public function delete($finPurchaseCostId){
		
		$dataHOld = $this->trpurchasecost_model->getDataHeaderById($finPurchaseCostId);
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_purchasecost_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$isEditable = $this->trpurchasecost_model->isEditable($finPurchaseCostId);
        if($isEditable["status"] != "SUCCESS"){
            return $isEditable;
		}
		

		$this->db->trans_start();

		$resp = $this->trpurchasecost_model->unposting($finPurchaseCostId);               
        if($resp["status"] != "SUCCESS"){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
            return;
        }

		$resp = $this->trpurchasecost_model->delete($finPurchaseCostId,true);		
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
}    
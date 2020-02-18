<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Jurnal_umum extends MY_Controller{
	public function __construct(){
		parent::__construct();
        $this->load->library('form_validation');
        
        $this->load->model("glaccounts_model");
        $this->load->model("profitcostcenter_model");
        $this->load->model("msdepartments_model");
        $this->load->model("msrelations_model");
        $this->load->model("msprojects_model");
        $this->load->model("gltrjournal_model");
        $this->load->model("gltrjournalitems_model");
        
		//$this->load->model("mswarehouse_model");
		//$this->load->model("msitemdiscounts_model");
		
		

	}
	public function index(){
		$this->lizt();
	}
	public function lizt(){
		$this->load->library('menus');
        $this->list['page_name'] = "Manual Jurnal";
        $this->list['list_name'] = "Manual Jurnal List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/jurnal_umum/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/jurnal_umum/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_po_no' => 'No Order Pembelian',
			'fst_supplier_name' => 'Supplier',
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Order', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
            ['title' => 'Jurnal Id', 'width' => '10px','visible'=>'false', 'data' => 'fin_journal_id'],
            ['title' => 'Type', 'width' => '20px','visible'=>'true', 'data' => 'fst_journal_type'],
            ['title' => 'No. Jurnal', 'width' => '120px', 'data' => 'fst_journal_no'],
            ['title' => 'Tanggal', 'width' => '80px', 'data' => 'fdt_journal_datetime'],
            ['title' => 'Memo', 'width' => '120px', 'data' => 'fst_desc'],
			['title' => 'Action', 'width' => '70px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/jurnal_umum/edit/' + row.fin_journal_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$mdlPopupNotes = $this->parser->parse('template/mdlPopupNotes', [], true);
		$this->list['jsfile'] = $this->parser->parse('pages/tr/jurnal_umum/listjs', ["mdlPopupNotes"=>$mdlPopupNotes], true);

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
	private function openForm($mode = "ADD", $fin_jurnal_id = 0){
		$this->load->library("menus");		
		

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlJurnal =$this->parser->parse('template/mdlJurnal.php', [], true);
        $mdlPrint =$this->parser->parse('template/mdlPrint.php', [], true);
        $edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		
		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Jurnal Umum" : "Update Jurnal Umum";
		$data["mdlJurnal"] = $mdlJurnal;
        $data["mdlPrint"] = $mdlPrint;
        $data["mdlEditForm"] = $edit_modal;
        
		if($mode == 'ADD'){
			$data["fin_journal_id"] = 0;
			$data["fst_journal_no"] = "";			
		}else if($mode=="EDIT"){
			$data["fin_journal_id"] = $fin_jurnal_id;
			$data["fst_journal_no"] = "";			
		}else if($mode == "VIEW"){
			$data["fin_journal_id"] = $fin_jurnal_id;
			$data["fst_journal_no"] = "";			
		}
		
		$page_content = $this->parser->parse('pages/tr/jurnal_umum/form', $data, true);
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
	public function edit($fin_jurnal_id){
		$this->openForm("EDIT", $fin_jurnal_id);
	}
	public function view($finPOId){
		$this->openForm("VIEW", $finPOId);
	}


	public function ajx_add_save(){

        try{   
            $fdt_journal_datetime = dBDateTimeFormat($this->input->post("fdt_journal_datetime"));
            $resp = dateIsLock($fdt_journal_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }
            
            $dataPrepared = $this->prepareData();
            $dataH = $dataPrepared["dataH"];
            $dataDetails = $dataPrepared["dataDetails"];
            $this->validationData($dataH,$dataDetails);

            
            //Save
            $this->db->trans_start();

            //Insert Data Header
            $insertId = $this->gltrjournal_model->insert($dataH);
            
            //Insert Data Detail
            foreach ($dataDetails as $dataDetail) {
                $dataD =[
                    //`fin_rec_id`, 
                    "fin_journal_id"=>$insertId,
                    "fst_glaccount_code"=>$dataDetail->fst_glaccount_code, 
                    //"fst_reference"=>$dataDetail->fst_reference, 
                    "fst_memo"=>$dataDetail->fst_memo, 
                    "fdc_debit"=>$dataDetail->fdc_debit,
                    "fdc_credit"=>$dataDetail->fdc_credit, 
                    "fin_pcc_id"=>$dataDetail->fin_pcc_id, 
                    "fin_pc_divisi_id"=>$dataDetail->fin_pc_divisi_id, 
                    "fin_pc_customer_id"=>$dataDetail->fin_pc_customer_id, 
                    "fin_pc_project_id"=>$dataDetail->fin_pc_project_id, 
                    "fin_relation_id"=>$dataDetail->fin_relation_id, 
                    "fst_active"=>'A',
                ];

                $this->gltrjournalitems_model->insert($dataD);
            }

            //Posting
            $this->gltrjournal_model->posting($insertId);            
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
        }

	}


	public function ajx_edit_save(){
        try{

            $finJournalId = $this->input->post("fin_journal_id");
            $dataHOld = $this->gltrjournal_model->getDataHeader($finJournalId);
            $resp = dateIsLock($dataHOld->fdt_journal_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }

            $fdt_journal_datetime = dBDateTimeFormat($this->input->post("fdt_journal_datetime"));
            $resp = dateIsLock($fdt_journal_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }

            $resp = $this->gltrjournal_model->isEditable($dataHOld->fin_journal_id);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }

            $this->db->trans_start();
            //UNPOSTING            
            $this->gltrjournal_model->unposting($finJournalId);

            //DELETE DETAIL
            $this->gltrjournal_model->deleteDetail($finJournalId);

            //PREPARE DATA
            $dataPrepared = $this->prepareData();
            $dataH = $dataPrepared["dataH"];
            $dataDetails = $dataPrepared["dataDetails"];
            $dataH["fin_journal_id"] = $finJournalId;
            $dataH["fst_journal_no"] = $this->input->post("fst_journal_no");
            
            //VALIDATION
            $this->validationData($dataH,$dataDetails);

            //SAVE
            $this->gltrjournal_model->update($dataH);
            foreach($dataDetails as $dataD){
                $dataD = (array) $dataD;
                $dataD["fin_journal_id"] = $finJournalId;
                $dataD["fst_active"] = "A";
                $this->gltrjournalitems_model->insert($dataD);
            }

            //POSTING
            $this->gltrjournal_model->posting($finJournalId);        
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $finJournalId;
            $this->json_output();

        }catch(CustomException $e){
            $this->db->trans_rollback();
            $this->ajxResp["status"] = $e->getStatus();
            $this->ajxResp["message"] = $e->getMessage();
            $this->ajxResp["data"] = $e->getData();
            $this->json_output();
        }

	}

    private function prepareData(){
		$fdt_journal_datetime = dBDateTimeFormat($this->input->post("fdt_journal_datetime"));
            $resp = dateIsLock($fdt_journal_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }

            $journalType = $this->input->post("fst_journal_type");
            $fst_journal_no = $this->gltrjournal_model->generateTransNo($journalType, $fdt_journal_datetime);


            $fstCurrCode =  $this->input->post("fst_curr_code");   
            $fdcExchangeRateIdr = parseNumber($this->input->post("fdc_exchange_rate_idr"));

            if($fstCurrCode == null){
                $defaultCurr = getDefaultCurrency();
                $fstCurrCode = $defaultCurr["CurrCode"];
                $fdcExchangeRateIdr = 1;
            }
            

            $dataH = [
                //"fin_journal_id"=>
                "fst_journal_type"=> $journalType,
                "fst_journal_no"=>$fst_journal_no,
                "fdt_journal_datetime"=>$fdt_journal_datetime,
                "fst_curr_code"=>$fstCurrCode,
                "fdc_exchange_rate_idr"=>$fdcExchangeRateIdr, 
                "fst_desc"=>$this->input->post("fst_desc"), 
                "fin_branch_id"=>$this->aauth->get_active_branch_id(), 
                "fst_active"=>'A'
            ];

            $details = $this->input->post("details");
            $details = json_decode($details);

            return [
                "dataH"=>$dataH,
                "dataDetails"=>$details
            ];

	}

    private function validationData($dataH,$dataDetails){
        $this->form_validation->set_rules($this->gltrjournal_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
        }
        

        $this->form_validation->set_rules($this->gltrjournalitems_model->getRules("ADD", 0));
        
        foreach($dataDetails as $dataD){
            $this->form_validation->set_data((array) $dataD);
            if ($this->form_validation->run() == FALSE) {
                throw new CustomException("Error Validation Detail",3003,"VALIDATION_FORM_FAILED",["detail"=>$this->form_validation->error_string()]);
            }

            $account = $this->glaccounts_model->getSimpleDataHeader($dataD->fst_glaccount_code);
            if($account == null){
                throw new CustomException("Invalid Account Code",9009,"FAILED",null);
            }
            
            $fblPcc = $account->fst_glaccount_type == "PROFIT_LOST";
            $fblPcDivisi =  $account->fbl_pc_divisi;
            $fblPcCustomer = $account->fbl_pc_customer;
            $fblPcProject = $account->fbl_pc_project;

            if ($fblPcc && $dataD->fin_pcc_id == null){
                throw new CustomException(sprintf(lang("Profit/Cost Center harus diisi untuk account %s"),$account->fst_glaccount_name),3003,"FAILED",null);
            }
            if ($fblPcDivisi && $dataD->fin_pc_divisi_id == null){
                throw new CustomException(sprintf(lang("Analisa divisi harus diisi untuk account %s"),$account->fst_glaccount_name),3003,"FAILED",null);
            }
            if ($fblPcCustomer && $dataD->fin_pc_customer_id == null){
                throw new CustomException(sprintf(lang("Analisa customer harus diisi untuk account %s"),$account->fst_glaccount_name),3003,"FAILED",null);
            }
            if ($fblPcProject && $dataD->fin_pc_project_id == null){
                throw new CustomException(sprintf(lang("Analisa project harus diisi untuk account %s"),$account->fst_glaccount_name),3003,"FAILED",null);
            }


        }
    }
	
	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("(select * from gltrjournal) a");
		$selectFields = "a.fin_journal_id,a.fst_journal_type,a.fst_journal_no,a.fdt_journal_datetime,a.fst_desc,a.fst_active";
		$this->datatables->setSelectFields($selectFields);
		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDate = strtotime($data["fdt_journal_datetime"]);						
			$data["fdt_journal_datetime"] = date("d-M-Y",$insertDate);
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($fin_jurnal_id){
		$data = $this->gltrjournal_model->getDataById($fin_jurnal_id);	
		$this->json_output($data);
	}
	public function delete($finJournalId){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		try{            
            $dataHOld = $this->gltrjournal_model->getDataHeader($finJournalId);
            $resp = dateIsLock($dataHOld->fdt_journal_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }
          
            $resp = $this->gltrjournal_model->isEditable($dataHOld->fin_journal_id);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3003,$resp["status"],null);
            }

            $this->db->trans_start();
            //UNPOSTING            
            $this->gltrjournal_model->unposting($finJournalId);

            //DELETE DETAIL
            $this->gltrjournal_model->delete($finJournalId);
            
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $finJournalId;
            $this->json_output();

        }catch(CustomException $e){
            $this->db->trans_rollback();
            $this->ajxResp["status"] = $e->getStatus();
            $this->ajxResp["message"] = $e->getMessage();
            $this->ajxResp["data"] = $e->getData();
            $this->json_output();
        }
	}

	


}
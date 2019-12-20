<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengiriman_penjualan extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
        $this->load->model('trsuratjalan_model');
        $this->load->model('mswarehouse_model');
        $this->load->model("users_model");
    }

    public function index(){
       $this->lizt();
    }
    public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Surat Jalan";
		$this->list['list_name'] = "Surat Jalan List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/gudang/pengiriman_penjualan/add';
		$this->list['pKey'] = "fin_sj_id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/gudang/pengiriman_penjualan/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/gudang/pengiriman_penjualan/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/gudang/pengiriman_penjualan/edit/';
		$this->list['arrSearch'] = [
			'fin_sj_id' => 'Surat jalan ID',
			'fst_sj_no' => 'Surat jalan No'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Surat Jalan', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Surat Jalan ID.', 'width' => '20px', 'data' => 'fin_sj_id'],
            ['title' => 'Surat Jalan No.', 'width' => '20px', 'data' => 'fst_sj_no'],
            ['title' => 'Surat Jalan Date.', 'width' => '20px', 'data' => 'fdt_sj_datetime',
                'render'=>"function(data,type,row){
                    return App.dateTimeFormat(data);
                }",
            ],
            ['title' => 'Sales Order No.', 'width' => '20px', 'data' => 'fst_salesorder_no'],
            ['title' => 'Sales Order Date.', 'width' => '20px', 'data' => 'fdt_salesorder_datetime',
                'render'=>"function(data,type,row){
                    return App.dateTimeFormat(data);
                }",
            ],
            ['title' => 'Memo', 'width' => '100px', 'data' => 'fst_sj_memo'],
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'dt-body-center text-center',
                'render'=>'function( data, type, row, meta ) {
                    return "<div style=\'font-size:16px\'><a data-id=\'" + row.fin_sj_id + "\' class=\'btn-edit\' href=\'#\'><i class=\'fa fa-pencil\'></i></a><a class=\'btn-delete\' href=\'#\'><i class=\'fa fa-trash\'></i></a></div>";
                }',
            ]
        ];
        $edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
        $this->list["mdlEditForm"] = $edit_modal;

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
		$this->datatables->setTableName("(select a.*,b.fst_salesorder_no,b.fdt_salesorder_datetime from trsuratjalan a inner join trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id) a");

		$selectFields = "a.fin_sj_id,a.fst_sj_no,a.fdt_sj_datetime,a.fst_sj_memo,a.fst_salesorder_no,a.fdt_salesorder_datetime";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
        $arrDataFormated = [];
        /*
		foreach ($arrData as $data) {
			//$insertDate = strtotime($data["fdt_sj_date"]);						
			//$data["fdt_salesorder_date"] = date("d-M-Y",$insertDate);
			//$arrDataFormated[] = $data;
        }
        */
        $arrDataFormated = $arrData;

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

    private function openForm($mode = "ADD", $fin_sj_id = 0){
		$this->load->library("menus");		
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Add Delivery Order") : lang("Update Delivery Order");
        $edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
        $data["mdlEditForm"] = $edit_modal;

		if($mode == 'ADD'){
			$data["fin_sj_id"] = 0;
			$data["fst_sj_no"] = $this->trsuratjalan_model->GenerateSJNo();				
		}else{
            
			$data["fin_sj_id"] = $fin_sj_id;
			$data["fst_sj_no"] = "";
        }
        
		
		$page_content = $this->parser->parse('pages/tr/gudang/pengiriman_penjualan/form', $data, true);
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

	public function Edit($fin_sj_id){
		$this->openForm("EDIT", $fin_sj_id);
    }
    
    public function fetch_data($fin_sj_id){
		$this->load->model("trsuratjalan_model");
		$data = $this->trsuratjalan_model->getDataById($fin_sj_id);		
		$this->json_output($data);
	}    

    public function ajx_add_save(){        
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsuratjalandetails_model");
        $this->load->model("trinventory_model");
        $this->load->model("msitems_model");
            
        try{
            //CHECK LOCKED DATE            
            $resp = dateIsLock($fdt_sj_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            $dataPrepared = $this->prepareData();
            $dataH  = $dataPrepared["dataH"];
            $dataDetails  = $dataPrepared["dataDetails"];

            $this->validationData($dataH,$dataDetails);

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
            
            $insertId = $this->trsuratjalan_model->insert($dataH);
            foreach($details as $detail){
                $detail = (array) $detail;
                $detail["fin_sj_id"] = $insertId;
                $detail["fst_serial_number_list"] = json_encode($detail["fst_serial_number_list"]);
                $detail["fst_active"] = 'A';
                $this->trsuratjalandetails_model->insert($detail);
            }

            //POSTING
            $this->trsuratjalan_model->posting($insertId);


            //$this->db->trans_complete();
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
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsuratjalandetails_model");
        $this->load->model("trinventory_model");
        $this->load->model("msitems_model");
        

        try{
            $this->db->trans_start();

            //CHECK LOCKED DATE            
            $finSJId = $this->input->post("fin_sj_id");            
            $dataHOld = $this->trsuratjalan_model->getDataHeaderById($finSJId);
            if ($dataHOld == null){
                throw new CustomException(lang("ID Surat Jalan tidak dikenal !"),3003,"FAILED",null);
            }
            $resp = dateIsLock($dataHOld->fdt_sj_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }            
            
            $fdt_sj_datetime = dBDateTimeFormat($this->input->post("fdt_sj_datetime"));		
            $resp = dateIsLock($fdt_sj_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            //IS EDITABLE
            $this->trsuratjalan_model->isEditable($finSJId);


            //UNPOSTING
            $this->trsuratjalan_model->unposting($finSJId);

            //DELETE DATA
            $this->trsuratjalan_model->deleteDetailForUpdate($finSJId);

            //PREPARE DATA
            $dataH = $this->input->post();
            $dataH["fdt_sj_datetime"] = $fdt_sj_datetime;
            $dataH["fbl_is_hold"] = $this->input->post("fbl_is_hold") == null ? 1 : 0;
            unset($dataH["details"]);

            $details = $this->input->post("detail");
            $details = json_decode($details);            


            //VALIDATION
            $this->form_validation->set_rules($this->trsuratjalan_model->getRules("ADD", 0));
            $this->form_validation->set_data($dataH);            
            $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
            if ($this->form_validation->run() == FALSE) {
                throw new CustomException("Error Validation Forms",3009,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
            }


            $arrItem = $this->msitems_model->getDetailbyArray(array_column($details, 'fin_item_id'));
            foreach($details as $detail){
                $this->form_validation->set_rules($this->trsuratjalandetails_model->getRules("ADD", 0));
                $this->form_validation->set_data((array) $detail);            
                $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
                if ($this->form_validation->run() == FALSE) {
                    $error = [
                        "detail"=> $this->form_validation->error_string(),
                    ];
                    throw new CustomException("Error Validation Forms",3009,"VALIDATION_FORM_FAILED",$error);
                }
    
                //Validation is valid batch number & serial number (qty, serial number exist)
                $item = $arrItem[$detail->fin_item_id];
                if ($item->fbl_is_batch_number == 1){
                    if ($detail->fst_batch_number == null || $detail->fst_batch_number == ""){
                        throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$detail->fst_custom_item_name),3009,"FAILED",null);
                    }
                }
                
                if ($item->fbl_is_serial_number == 1){
                    if ($detail->fst_serial_number_list == null || $detail->fst_serial_number_list == ""){
                        if (is_array($detail->fst_serial_number_list)){
                            $arrSerial = $detail->fst_serial_number_list;

                            //Check Jumlah serial no
                            if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($detail->fin_item_id,$detail->fdb_qty,$item->fst_unit) ){
                                throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$item->fst_custom_item_name,$item->fdb_qty),3009,"FAILED",null);
                            }
                            //Check all serial is exist and ready;
                            $arrSerialStatus = $this->trinventory_model->getSummarySerialNo($dataH["fin_warehouse_id"],$detail->fin_item_id,$arrSerial);
                            foreach($arrSerial as $serial){
                                if (isset($arrSerialStatus[$serial]) ){
                                    $serialStatus = $arrSerialStatus[$serial];
                                    if ($serialStatus["fdb_qty_in"] <= $serialStatus["fdb_qty_out"] ){
                                        throw new CustomException(sprintf(lang("No serial %s untuk item %s tidak tersedia"),$serial,$item->fst_custom_item_name),3009,"FAILED",null);    
                                    }
                                }else{
                                    throw new CustomException(sprintf(lang("No serial %s untuk item %s tidak tersedia"),$serial,$item->fst_custom_item_name),3009,"FAILED",null);
                                }
                            }
                        }else{
                            throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$detail->fst_custom_item_name),3009,"FAILED",null);
                        }
                    }
                }

                //Validation if qty more than SO
                if ($detail->fdb_qty > $this->trsuratjalan_model->maxQtyItem($detail->fin_salesorder_detail_id) ){
                    throw new CustomException(sprintf(lang("Qty %s melebihi qty pada sales order") ,$detail->fst_custom_item_name),3009,"FAILED",null);
                }
    

                //Validation stock is available
                if ($item->fbl_stock == 1){
                    $basicUnit = $this->msitems_model->getBasicUnit($detail->fin_item_id);
                    $qtyStockBasicUnit = (float) $this->trinventory_model->getStock($detail->fin_item_id,$basicUnit,$dataH["fin_warehouse_id"]);
                    $qtyReqInBasicUnit = $this->msitems_model->getQtyConvertUnit($detail->fin_item_id,$detail->fdb_qty,$detail->fst_unit,$basicUnit);
                    $qtyStockReqUnit =  $this->msitems_model->getQtyConvertUnit($detail->fin_item_id,$qtyStockBasicUnit,$basicUnit,$detail->fst_unit);
                    if ($qtyReqInBasicUnit > $qtyStockBasicUnit ){
                        throw new CustomException(sprintf(lang("Stock %s tersisa : %d %s") ,$detail->fst_custom_item_name,$qtyStockReqUnit,$detail->fst_unit),3009,"FAILED",null);
                    }

                }
            }

            //SAVE
            $insertId = $dataH["fin_sj_id"];            
            $this->trsuratjalan_model->update($dataH);
            foreach($details as $detail){
                $detail = (array) $detail;
                $detail["fin_sj_id"] = $insertId;
                $detail["fst_serial_number_list"] = json_encode($detail["fst_serial_number_list"]);
                $detail["fst_active"] = 'A';
                $this->trsuratjalandetails_model->insert($detail);
            }

            //POSTING
            $this->trsuratjalan_model->posting($insertId);
        
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("Data saved !");
			$this->ajxResp["data"] = [];
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
        //PREPARE DATA
        $fdt_sj_datetime = dBDateTimeFormat($this->input->post("fdt_sj_datetime"));		        

        $dataH = $this->input->post();        
        $dataH["fdt_sj_datetime"] = $fdt_sj_datetime;
        $dataH["fbl_is_hold"] = isset($dataH["fbl_is_hold"]) ? 1 : 0;
        $dataH["fst_sj_no"] = $this->trsuratjalan_model->GenerateSJNo();	
        $dataH["fst_active"] = "A";            
        unset($dataH["detail"]);

        $details = $details = $this->input->post("detail");
        $details = json_decode($details);

        return ([
            "dataH"=>$dataH,
            "dataDetails"=>$details
        ]);
    }

    private function validationData($dataH,$dataDetails){
        //VALIDATION
        $this->form_validation->set_rules($this->trsuratjalan_model->getRules("ADD", 0));
        $this->form_validation->set_data($dataH);            
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            throw new CustomException("Error Validation Forms",3009,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
        }

        $arrItem = $this->msitems_model->getDetailbyArray(array_column($dataDetails, 'fin_item_id'));

        foreach($dataDetails as $detail){
            $this->form_validation->set_rules($this->trsuratjalandetails_model->getRules("ADD", 0));
            $this->form_validation->set_data((array) $detail);            
            $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
            if ($this->form_validation->run() == FALSE) {
                $error = [
                    "detail"=> $this->form_validation->error_string(),
                ];
                throw new CustomException("Error Validation Forms",3009,"VALIDATION_FORM_FAILED",$error);
            }


            //Validation is valid batch number & serial number (qty, serial number exist)
            $item = $arrItem[$detail->fin_item_id];
            if ($item->fbl_is_batch_number == 1){
                if ($detail->fst_batch_number == null || $detail->fst_batch_number == ""){
                    throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$detail->fst_custom_item_name),3009,"FAILED",null);
                }
            }
            
            if ($item->fbl_is_serial_number == 1){
                if ($detail->fst_serial_number_list == null || $detail->fst_serial_number_list == ""){
                    if (is_array($detail->fst_serial_number_list)){
                        $arrSerial = $detail->fst_serial_number_list;

                        //Check Jumlah serial no
                        if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($detail->fin_item_id,$detail->fdb_qty,$item->fst_unit) ){
                            throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$item->fst_custom_item_name,$item->fdb_qty),3009,"FAILED",null);
                        }
                        //Check all serial is exist and ready;
                        $arrSerialStatus = $this->trinventory_model->getSummarySerialNo($dataH["fin_warehouse_id"],$detail->fin_item_id,$arrSerial);
                        foreach($arrSerial as $serial){
                            if (isset($arrSerialStatus[$serial]) ){
                                $serialStatus = $arrSerialStatus[$serial];
                                if ($serialStatus["fdb_qty_in"] <= $serialStatus["fdb_qty_out"] ){
                                    throw new CustomException(sprintf(lang("No serial %s untuk item %s tidak tersedia"),$serial,$item->fst_custom_item_name),3009,"FAILED",null);    
                                }
                            }else{
                                throw new CustomException(sprintf(lang("No serial %s untuk item %s tidak tersedia"),$serial,$item->fst_custom_item_name),3009,"FAILED",null);
                            }
                        }
                    }else{
                        throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$detail->fst_custom_item_name),3009,"FAILED",null);
                    }
                }
            }

            //Validation if qty more than SO atau PO_RETURN
            if ($detail->fdb_qty > $this->trsuratjalan_model->maxQtyItem($dataH["fst_sj_type"],$detail->fin_trans_detail_id) ){
                throw new CustomException(sprintf(lang("Qty %s melebihi qty pada sales order") ,$detail->fst_custom_item_name),3009,"FAILED",null);
            }


            //Validation stock is available
            if ($item->fbl_stock == 1){
                $basicUnit = $this->msitems_model->getBasicUnit($detail->fin_item_id);
                $qtyStockBasicUnit = (float) $this->trinventory_model->getStock($detail->fin_item_id,$basicUnit,$dataH["fin_warehouse_id"]);
                $qtyReqInBasicUnit = $this->msitems_model->getQtyConvertUnit($detail->fin_item_id,$detail->fdb_qty,$detail->fst_unit,$basicUnit);
                $qtyStockReqUnit =  $this->msitems_model->getQtyConvertUnit($detail->fin_item_id,$qtyStockBasicUnit,$basicUnit,$detail->fst_unit);
                if ($qtyReqInBasicUnit > $qtyStockBasicUnit ){
                    throw new CustomException(sprintf(lang("Stock %s tersisa : %d %s") ,$detail->fst_custom_item_name,$qtyStockReqUnit,$detail->fst_unit),3009,"FAILED",null);
                }

            }
        }

    }

    public function delete($sjId){

        try{
            //CHECK LOCKED DATE
            $finSJId = $sjId;            
            $dataHOld = $this->trsuratjalan_model->getDataHeaderById($finSJId);
            if ($dataHOld == null){
                throw new CustomException(lang("ID Surat Jalan tidak dikenal !"),3003,"FAILED",null);
            }

            $resp = dateIsLock($dataHOld->fdt_sj_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }                                    

            //IS EDITABLE
            $this->trsuratjalan_model->isEditable($finSJId);


            

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
            $this->trsuratjalan_model->unposting($finSJId);
            $this->trsuratjalan_model->delete($finSJId,SOFT_DELETE);

            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("Data dihapus !");
			$this->ajxResp["data"] = [];
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

    public function test(){
        $this->load->model("trinventory_model");

        $qtyStock = (float) $this->trinventory_model->getStock(2,"BOX",1);
        echo $qtyStock;
            
    }

    public function initVarForm(){
        $this->load->model("mswarehouse_model");
       
        
        
        //Get Data warehouse
        $arrWarehouse = $this->mswarehouse_model->getSelect2();
        //Get Data sopir
        $arrDriver = $this->users_model->getSelect2Driver();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
            "arrWarehouse"=>$arrWarehouse,
            "arrDriver"=>$arrDriver
        ];
        $this->json_output();


    }

    public function get_transaction_list(){
        $fstSJType = $this->input->get("fst_sj_type");
        $term = $this->input->get("term") ;
        $term = $term == null ? "" :$term;

        $rs = $this->trsuratjalan_model->getTransactionList($fstSJType,$term);

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
    }

    public function get_detail_trans($sjType,$transId){

        $rs = $this->trsuratjalan_model->getPendingDetailTrans($sjType,$transId); //$this->trsuratjalan_model->getPendingDetailSO($salesOrderId);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();        
    }

}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo_period extends MY_Controller{
    public $menuName="promo_period"; 
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
    }

    public function index(){
        parent::index();
        $this->lizt();
    }
    public function lizt(){
        parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Promo Period";
		$this->list['list_name'] = "Promo Period List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/promotion/add';
		$this->list['pKey'] = "fin_promo_id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/sales/promo_period/fetch_list_data';
		$this->list['edit_ajax_url'] = site_url() . 'tr/sales/ekspedisi/edit/';
        $this->list['delete_ajax_url'] = '';
		$this->list['arrSearch'] = [
            'fin_promo_id' => 'Promo ID',
            'fst_promo_name'=>'Promo Name'
		];
        
        $this->list['boxTools'] = [
			//"<a id='btnNew'  href='".site_url()."tr/gudang/mutasi/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];

        $this->list['jsfile'] = $this->parser->parse('template/listjs', [], true);

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Sales', 'link' => '#', 'icon' => ''],
			['title' => 'Promo Period List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Promo ID', 'width' => '10%', 'data' => 'fin_promo_id'],
			['title' => 'Promo Name', 'width' => '', 'data' => 'fst_promo_name'],
            ['title' => 'Start', 'width' => '15%', 'data' => 'fdt_start'],
            ['title' => 'End', 'width' => '15%', 'data' => 'fdt_end'],
            ['title' => 'Status', 'width' => '50px', 'data' => 'fst_active',
                'render'=>"function(data,type,row){
                    if (data == 'A'){
                        return 'Active';
                    }
                    if (data == 'C'){
                        return 'Closed';
                    }                    
                }"
            ],
            ['title' => 'Action', 'width' => '15px', 'sortable' => false, 'className' => 'dt-body-center text-center',
                'render'=>'function( data, type, row, meta ) {
                    return "<div style=\'font-size:16px\'><a data-id=\'" + row.fin_promo_id + "\' class=\'btn-edit\' href='.site_url() . 'tr/sales/promo_period/open/' . '" + row.fin_promo_id  + "\><i class=\'fa fa-cogs\'></i></a></div>";
                }',
            ]
		];
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
            SELECT * FROM mspromo where fst_promo_type = 'PERIODE' 
        ) a");

        $selectFields = "a.*";
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
    


    public function open($finPromoId){
        $this->load->library("menus");		
        
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		
        $data["title"] =  lang("Closing Periode Promo");

        $edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
        $data["mdlEditForm"] = $edit_modal;
        $mdlPrint = $this->parser->parse('template/mdlPrint', [], true);
        $data["mdlPrint"] = $mdlPrint;
       
        $data["fin_promo_id"] = $finPromoId;
		
		$page_content = $this->parser->parse('pages/tr/sales/periode_promo/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	

    public function fetch_data($fin_salesekspedisi_id){
		$data = $this->trsalesekspedisi_model->getDataById($fin_salesekspedisi_id);		
		$this->json_output($data);
	}
    
    public function ajx_add_save(){
        parent::ajx_add_save();
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsalesorder_model");
        $this->load->model("trinventory_model");

        try{
            //CEK CLOSE DATE
            $fdt_salesekspedisi_datetime = dBDateTimeFormat($this->input->post("fdt_salesekspedisi_datetime"));		
            $resp = dateIsLock($fdt_salesekspedisi_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            //PREPARE DATA
            $preparedData = $this->prepareData();
            $dataH = $preparedData["dataH"];
            $dataDetails =$preparedData["dataDetails"];
            
            //VALIDASI DATA
            $this->validationData($dataH,$dataDetails);
            
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
            $insertId = $this->trsalesekspedisi_model->insert($dataH);
            foreach($dataDetails as $dataD){
                $dataD["fin_salesekspedisi_id"] = $insertId;
                $this->trsalesekspedisiitems_model->insert($dataD);
            }
            
            //POSTING
            $this->trsalesekspedisi_model->posting($insertId);
            
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
        

        $finSalesekspedisiId = $this->input->post("fin_salesekspedisi_id");

        try{            
            $dataHOld = $this->db->get_where("trsalesekspedisi",["fin_salesekspedisi_id"=>$finSalesekspedisiId])->row();
            if ($dataHOld == null){
                throw new CustomException(lang("ID sales ekspedisi tidak dikenal !"),3003,"FAILED",null);                
            }

            $resp = dateIsLock($dataHOld->fdt_salesekspedisi_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            //CEK CLOSE DATE
            $fdt_salesekspedisi_datetime = dBDateTimeFormat($this->input->post("fdt_salesekspedisi_datetime"));		
            $resp = dateIsLock($fdt_salesekspedisi_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            //IS EDITABLE
            $this->trsalesekspedisi_model->isEditable($dataHOld);

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
            $this->trsalesekspedisi_model->unposting($finSalesekspedisiId);

            //DELETE DETAIL
            $this->trsalesekspedisi_model->deleteDetail($finSalesekspedisiId);

            //PREPARE DATA
            $preparedData = $this->prepareData();
            $dataH = $preparedData["dataH"];
            $dataDetails = $preparedData["dataDetails"];
            
            $dataH["fin_salesekspedisi_id"] = $finSalesekspedisiId;
            $dataH["fst_salesekspedisi_no"]=$dataHOld->fst_salesekspedisi_no;

            $this->validationData($dataH,$dataDetails);
            
            //SAVE DATA
            $insertId = $finSalesekspedisiId;            
            $this->trsalesekspedisi_model->update($dataH);

            foreach($dataDetails as $dataD){
                $dataD["fin_salesekspedisi_id"] = $finSalesekspedisiId;
                $this->trsalesekspedisiitems_model->insert($dataD);
            }
            
            //POSTING
            $this->trsalesekspedisi_model->posting($finSalesekspedisiId);

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
    
    public function delete($finSalesekspedisiId){
        parent::delete($finSalesekspedisiId);
        try{
            $dataHOld = $this->db->get_where("trsalesekspedisi",["fin_salesekspedisi_id"=>$fin_salesekspedisi_id])->row();
            if ($dataHOld == null){
                throw new CustomException(lang("ID sales ekspedisi tidak dikenal !"),3003,"FAILED",null);                
            }

            $resp = dateIsLock($dataHOld->fdt_salesekspedisi_datetime);
            if ($resp["status"] != "SUCCESS" ){
                throw new CustomException($resp["message"],3009,$resp["status"],null);
            }

            //IS EDITABLE
            $this->trsalesekspedisi_model->isEditable($dataHOld);
                   
            
            $this->db->trans_start();


            $this->trsalesekspedisi_model->unposting($finSalesekspedisiId);

            $this->trsalesekspedisi_model->delete($finSalesekspedisiId);

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

    private function prepareData(){
        
        $trxNo = $this->trsalesekspedisi_model->generateTransactionNo();
        $fdt_salesekspedisi_datetime = dBDateTimeFormat($this->input->post("fdt_salesekspedisi_datetime"));		
        $dataH = [
            //`fin_salesekspedisi_id`, 
            "fst_salesekspedisi_no" => $trxNo, 
            "fdt_salesekspedisi_datetime"=>$fdt_salesekspedisi_datetime, 
            "fst_no_referensi"=>$this->input->post("fst_no_referensi"), 
            "fin_customer_id"=>$this->input->post("fin_customer_id"), 
            "fin_supplier_id"=>$this->input->post("fin_supplier_id"), 
            "fbl_reclaimable"=>$this->input->post("fbl_reclaimable") == null ? 0 : 1, 
            "fin_shipping_address_id"=>$this->input->post("fin_shipping_address_id"), 
            "fst_curr_code"=>$this->input->post("fst_curr_code"), 
            "fdc_exchange_rate_idr"=> parseNumber($this->input->post("fdc_exchange_rate_idr")), 
            "fdb_qty"=>parseNumber($this->input->post("fdb_qty")), 
            "fdc_price"=>parseNumber($this->input->post("fdc_price")), 
            "fdc_ppn_percent"=>parseNumber($this->input->post("fdc_ppn_percent")), 
            "fdc_ppn_amount"=>parseNumber($this->input->post("fdc_ppn_amount")), 
            "fst_no_faktur_pajak"=>$this->input->post("fst_no_faktur_pajak"), 
            "fdc_other_cost"=>parseNumber($this->input->post("fdc_other")), 
            "fdc_total"=>0,
            "fst_memo"=>$this->input->post("fst_memo"), 
            "fin_branch_id"=>$this->aauth->get_active_branch_id(), 
            "fst_active" =>"A"
        ];
        $dataH["fdc_total"] = $dataH["fdb_qty"] * $dataH["fdc_price"] + $dataH["fdc_ppn_amount"] + $dataH["fdc_other_cost"];

        $arrSJList = $this->input->post("fst_sj_list");
        $dataDetails = [];
        foreach($arrSJList as $fin_sj_id){
            $dataDetails[] = [
                "fin_sj_id"=>$fin_sj_id,
                "fin_salesekspedisi_id"=>0,
                "fst_active"=>"A"
            ];
        }

        return [
            "dataH"=>$dataH,
            "dataDetails"=>$dataDetails
        ];

    }
    private function validationData($dataH,$dataDetails){
        //Validation Header
        $this->form_validation->set_rules($this->trsalesekspedisi_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        $this->form_validation->set_data($dataH);
        if ($this->form_validation->run() == FALSE) {
            throw new CustomException(lang("Error Validation Forms"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

        //Validation Detail
        
        $this->form_validation->set_rules($this->trsalesekspedisiitems_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');        
        foreach($dataDetails as $dataD){
            $this->form_validation->set_data((array) $dataD);
            if ($this->form_validation->run() == FALSE) {
                throw new CustomException(lang("Error Validation Details"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
            }
        }         
    }

    public function get_sj_list(){
        $sjList = $this->trsalesekspedisi_model->getSJList($this->input->get("fin_customer_id"));
        //echo "dodoal";
        echo json_encode([
            "status"=>"SUCCESS",
            "message"=>"",
            "data"=>[
                "sjList"=>$sjList,
            ]        
        ]);
    }

    public function print_voucher($finSalesekspedisiId){
		$this->data = $this->trsalesekspedisi_model->getDataVoucher($finSalesekspedisiId);
		//$data=[];
		$this->data["title"] = "Sales Ekspedisi";
		$page_content = $this->parser->parse('pages/tr/sales/ekspedisi/voucher', $this->data, true);
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

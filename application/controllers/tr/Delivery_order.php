<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Delivery_order extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trsuratjalan_model');
    }

    public function index(){
       $this->lizt();
    }
    public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Surat Jalan";
		$this->list['list_name'] = "Surat Jalan List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/delivery_order/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/delivery_order/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'tr/delivery_order/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'tr/delivery_order/edit/';
		$this->list['arrSearch'] = [
			'fin_ssj_id' => 'Surat jalan ID',
			'fst_sj_no' => 'Surat jalan No'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Surat Jalan', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Sales Order ID', 'width' => '20%', 'data' => 'fin_sj_id'],
			['title' => 'Sales Order No', 'width' => '20%', 'data' => 'fst_sj_no'],
			['title' => 'Sales Order Date', 'width' => '20%', 'data' => 'fdt_sj_date'],
            ['title' => 'Memo', 'width' => '20%', 'data' => 'fst_sj_memo'],
            ['title' => 'Action', 'width' => '15%', 'sortable' => false, 'className' => 'dt-body-center text-center',
                'render'=>'function( data, type, row, meta ) {
                    return "<div style=\'font-size:16px\'><a data-id=\'" + row.fin_sj_id + "\' class=\'btn-edit\' href=\'#\'><i class=\'fa fa-pencil\'></i></a><a class=\'btn-delete\' href=\'#\'><i class=\'fa fa-trash\'></i></a></div>";
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
		$this->datatables->setTableName("trsuratjalan");

		$selectFields = "fin_sj_id,fst_sj_no,fdt_sj_date,fst_sj_memo";
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

    public function initVarForm(){
        $this->load->model("mswarehouse_model");
        $this->load->model("users_model");
        
        
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

    private function openForm($mode = "ADD", $fin_sj_id = 0){
		$this->load->library("menus");		
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Delivery Order") : lang("Update Delivery Order");
		if($mode == 'ADD'){
			$data["fin_sj_id"] = 0;
			$data["fst_sj_no"] = $this->trsuratjalan_model->GenerateSJNo();				
		}else{
			$data["fin_sj_id"] = $fin_sj_id;
			$data["fst_sj_no"] = "";
        }
        
		
		$page_content = $this->parser->parse('pages/tr/delivery_order/form', $data, true);
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
    
    public function sel2_get_so(){

        $term = $this->input->get("term"); 
        $ssql = "SELECT a.fin_salesorder_id,a.fst_salesorder_no,a.fin_relation_id,a.fdt_salesorder_date,
            a.fin_shipping_address_id,a.fin_warehouse_id,
            c.fst_relation_name,d.fst_name,d.fst_shipping_address FROM trsalesorder a
            INNER JOIN trsalesorderdetails b ON a.fin_salesorder_id = b.fin_salesorder_id 
            INNER JOIN msrelations c ON a.fin_relation_id= c.fin_relation_id 
            INNER JOIN msshippingaddress d ON a.fin_shipping_address_id = d.fin_shipping_address_id
            WHERE a.fst_active ='A' 
            AND a.fbl_is_hold = FALSE 
            AND a.fbl_is_closed = FALSE 
            AND a.fdc_downpayment <= a.fdc_downpayment_paid
            AND (a.fst_salesorder_no like ? OR c.fst_relation_name like ? )
            GROUP BY b.fin_salesorder_id HAVING SUM(b.fdb_qty) > SUM(b.fdb_qty_out)";

        $qr = $this->db->query($ssql,["%".$term."%","%".$term."%"]);
        $rs = $qr->result();		
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
    }

    public function get_detail_so($salesOrderId){
        $rs = $this->trsuratjalan_model->getPendingDetailSO($salesOrderId);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
        
    }

    public function ajx_add_save(){
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsuratjalandetails_model");
        
        $dataH = $this->input->post();        
        $dataH["fdt_sj_date"] = dBDateFormat($dataH["fdt_sj_date"]);
        $this->form_validation->set_rules($this->trsuratjalan_model->getRules("ADD", 0));
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
        
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach($details as $detail){
            $valid = true;

            $this->form_validation->set_rules($this->trsuratjalandetails_model->getRules("ADD", 0));
            $this->form_validation->set_data((array) $detail);            
            $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["request_data"] = $dataH;                            

            if ($this->form_validation->run() == FALSE) {
                $error = [
					"detail"=> $this->form_validation->error_string(),
				];
                $this->ajxResp["data"] = $error;
                $valid = false;                
            }

            //Validation if qty more than SO
            if ($detail->fdb_qty > $this->trsuratjalan_model->maxQtyItem($detail->fin_salesorder_detail_id)){
                $error = [
					"detail"=> sprintf(lang("Qty %s melebihi qty pada sales order") ,$detail->fst_custom_item_name),
				];
                $this->ajxResp["data"] = $error;
                $valid = false;
            }


            if ($valid == false){
                $this->json_output();
                return;
            }
        }


        $dataH["fst_sj_no"] = $this->trsuratjalan_model->GenerateSJNo();		
        $dataH["fst_active"] = "A";

        $this->db->trans_start();
        $insertId = $this->trsuratjalan_model->insert($dataH);
        foreach($details as $detail){
            $detail = (array) $detail;
            $detail["fin_sj_id"] = $insertId;
            $this->trsuratjalandetails_model->insert((array)$detail);
        }
        $this->trsuratjalan_model->posting($insertId);

        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    
    }

    public function ajx_edit_save(){
        $this->load->model("trsuratjalan_model");
        $this->load->model("trsuratjalandetails_model");
        
        $dataH = $this->input->post();        
        $dataH["fdt_sj_date"] = dBDateTimeFormat($dataH["fdt_sj_date"]);
        
        $this->form_validation->set_rules($this->trsuratjalan_model->getRules("ADD", 0));
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
        
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach($details as $detail){
            $valid = true;

            $this->form_validation->set_rules($this->trsuratjalandetails_model->getRules("ADD", 0));
            $this->form_validation->set_data((array) $detail);            
            $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["request_data"] = $dataH;                            

            if ($this->form_validation->run() == FALSE) {
                $error = [
					"detail"=> $this->form_validation->error_string(),
				];
                $this->ajxResp["data"] = $error;
                $valid = false;                
            }

            //Validation if qty more than SO
            if ($detail->fdb_qty > $this->trsuratjalan_model->maxQtyItem($detail->fin_salesorder_detail_id,$dataH["fin_sj_id"])){
                $error = [
					"detail"=> sprintf(lang("Qty %s melebihi qty pada sales order") ,$detail->fst_custom_item_name),
				];
                $this->ajxResp["data"] = $error;
                $valid = false;
            }


            if ($valid == false){
                $this->json_output();
                return;
            }
        }


        //$dataH["fst_sj_no"] = $this->trsuratjalan_model->GenerateSJNo();		
        $dataH["fst_active"] = "A";

        $this->db->trans_start();
        $this->trsuratjalan_model->update($dataH);

        //Unposting
        $this->trsuratjalan_model->unposting($dataH["fin_sj_id"]);

        //Delete Detail
        $this->trsuratjalandetails_model->deleteByHId($dataH["fin_sj_id"]);
        
        foreach($details as $detail){
            $detail = (array) $detail;
            $detail["fin_sj_id"] = $dataH["fin_sj_id"];
            $this->trsuratjalandetails_model->insert((array)$detail);
        }
        $this->trsuratjalan_model->posting($dataH["fin_sj_id"]);

        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    
    }
}

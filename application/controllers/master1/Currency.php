<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class currency extends MY_Controller {
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
    }

    public function index(){
        $this->lizt();
    }

    public function lizt(){
        $this->load->library('menus');
        $this->list['page_name']="Master Currencies";
        $this->list['list_name']="Master Currencies List";
        $this->list['addnew_ajax_url']=site_url().'master/currency/add';
        $this->list['pKey']="id";
		$this->list['fetch_list_data_ajax_url']=site_url().'master/currency/fetch_list_data';
        $this->list['delete_ajax_url']=site_url().'master/currency/delete/';
        $this->list['edit_ajax_url']=site_url().'master/currency/edit/';
        $this->list['arrSearch']=[
            'a.CurrCode ' => 'Currencies Code',
            'a.CurrName' => 'Name'
		];
		$this->list['breadcrumbs']=[
			['title'=>'Home','link'=>'#','icon'=>"<i class='fa fa-dashboard'></i>"],
			['title'=>'master Currencies','link'=>'#','icon'=>''],
			['title'=>'List','link'=> NULL ,'icon'=>''],
		];
		$this->list['columns']=[
			['title' => 'Currencies Code', 'width'=>'20%', 'data'=>'CurrCode'],
            ['title' => 'Currencies Name', 'width'=>'20%', 'data'=>'CurrName'],
			['title' => 'Action', 'width'=>'10%', 'data'=>'action','sortable'=>false, 'className'=>'dt-body-center text-center']
		];
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('template/standardList',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar=null;
        $this->data['ACCESS_RIGHT']="A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR']= $main_sidebar;
        $this->data['PAGE_CONTENT']= $page_content;
        $this->data['MAIN_FOOTER']= $main_footer;        
        $this->parser->parse('template/main',$this->data);
    }

    private function openForm($mode="ADD",$CurrCode=0){
		$this->load->library("menus");

		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Currencies" : "Update Master Currencies";
		$data["CurrCode"] = $CurrCode;

		$page_content = $this->parser->parse('pages/master/currency/form',$data,true);
		$main_footer = $this->parser->parse('inc/main_footer',[],true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
    }
    
    public function add(){
        $this->openForm("ADD",0);
    }

    public function Edit($CurrCode){
        $this->openForm("EDIT", $CurrCode);
    }

    public function ajx_add_save(){
		$this->load->model('MSCurrencies_model');
		$this->form_validation->set_rules($this->MSCurrencies_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE){
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$data = [
			"CurrCode" =>$this->input->post("CurrCode"),
			"CurrName"=>$this->input->post("CurrName"),
			"fst_active"=>'A'
		];

		$this->db->trans_start();
		$insertId = $this->MSCurrencies_model->insert($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){	
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        // Save Curr Details
		$this->load->model("MSCurrenciesratedetails_model");
		$this->form_validation->set_rules($this->MSCurrenciesratedetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"CurrCode"=>$item->CurrCode,
				//"CurrCode" => $insertId,
				"Date"=> dBDateFormat($item->Date),
				"ExchangeRate2IDR"=> $item->ExchangeRate2IDR
			];

			// Validate Data Items
			$this->form_validation->set_data($data);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = "Error Validation Forms";
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;	
			}
			
			$this->MSCurrenciesratedetails_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0){			
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}
        
        $this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }

    public function ajx_edit_save(){
        $this->load->model('MSCurrencies_model');		
		$CurrCode = $this->input->post("CurrCode");
		$data = $this->MSCurrencies_model->getDataById($CurrCode);
		$mscurrencies = $data["msCurrency"];
		if (!$mscurrencies){
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $CurrCode Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->MSCurrencies_model->getRules("EDIT",$CurrCode));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE){
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$data = [
			"CurrCode"=>$CurrCode,
            "CurrName"=>$this->input->post("CurrName"),
			"fst_active"=>'A'
		];

		$this->db->trans_start();
		$this->MSCurrencies_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		// Save Details
		$this->load->model("MSCurrenciesratedetails_model");
		$this->form_validation->set_rules($this->MSCurrenciesratedetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$this->MSCurrenciesratedetails_model->deleteByDetail($CurrCode);
		
		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"CurrCode"=> $CurrCode,
				"Date"=> dBDateFormat($item->Date),
				"ExchangeRate2IDR"=> $item->ExchangeRate2IDR
			];

			// Validate Data Items
			$this->form_validation->set_data($data);
			if ($this->form_validation->run() == FALSE){
				$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
				$this->ajxResp["message"] = "Error Validation Forms";
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				$this->ajxResp["data"] = $error;
				$this->json_output();
				return;	
			}
			
			$this->MSCurrenciesratedetails_model->insert($data);
			$dbError  = $this->db->error();
			if ($dbError["code"] != 0){			
				$this->ajxResp["status"] = "DB_FAILED";
				$this->ajxResp["message"] = "Insert Failed";
				$this->ajxResp["data"] = $this->db->error();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}
		}
		
		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $CurrCode;
		$this->json_output();
	}
	
    public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("mscurrencies");

		$selectFields = "CurrCode,CurrName,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["CurrCode","CurrName"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='".$data["CurrCode"]."'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='".$data["CurrCode"]."' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }

    public function fetch_data($CurrCode){
		$this->load->model("MSCurrencies_model");
		$data = $this->MSCurrencies_model->getDataById($CurrCode);
	
		$this->json_output($data);
	}

	public function delete($id){
		if(!$this->aauth->is_permit("")){
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}
		
		$this->load->model("MSCurrencies_model");
		$this->MSCurrencies_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}
}
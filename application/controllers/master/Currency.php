<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends MY_Controller {
	public $menuName="master_currency"; 
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
    }

    public function index(){
		parent::index();
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
            'fst_curr_code ' => 'Currencies Code',
            'fst_curr_name' => 'Name'
		];
		$this->list['breadcrumbs']=[
			['title'=>'Home','link'=>'#','icon'=>"<i class='fa fa-dashboard'></i>"],
			['title'=>'Master Currencies','link'=>'#','icon'=>''],
			['title'=>'List','link'=> NULL ,'icon'=>''],
		];
		$this->list['columns']=[
			['title' => 'Currencies Code', 'width'=>'20%', 'data'=>'fst_curr_code'],
            ['title' => 'Currencies Name', 'width'=>'20%', 'data'=>'fst_curr_name'],
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

    private function openForm($mode="ADD",$fst_curr_code=0){
		$this->load->library("menus");

		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Currencies" : "Update Master Currencies";
		$data["fst_curr_code"] = $fst_curr_code;

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
		parent::add();
        $this->openForm("ADD",0);
    }

    public function edit($fst_curr_code){
		parent::edit($fst_curr_code);
        $this->openForm("EDIT", $fst_curr_code);
    }

    public function ajx_add_save(){
		parent::ajx_add_save();
		$this->load->model('mscurrencies_model');
		$this->form_validation->set_rules($this->mscurrencies_model->getRules("ADD",0));
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
			"fst_curr_code" =>$this->input->post("fst_curr_code"),
			"fst_curr_name"=>$this->input->post("fst_curr_name"),
			"fst_active"=>'A'
		];

		$this->db->trans_start();
		$insertId = $this->mscurrencies_model->insert($data);
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
		$this->load->model("mscurrenciesratedetails_model");
		$this->form_validation->set_rules($this->mscurrenciesratedetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"fst_curr_code"=>$item->fst_curr_code,
				//"fst_curr_code" => $insertId,
				"fdt_date"=> dBDateFormat($item->fdt_date),
				"fdc_exchange_rate_to_idr"=> $item->fdc_exchange_rate_to_idr
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
			
			$this->mscurrenciesratedetails_model->insert($data);
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
		parent::ajx_edit_save();
        $this->load->model('mscurrencies_model');		
		$fst_curr_code = $this->input->post("fst_curr_code");
		$data = $this->mscurrencies_model->getDataById($fst_curr_code);
		$mscurrencies = $data["ms_Currency"];
		if (!$mscurrencies){
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fst_curr_code Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->mscurrencies_model->getRules("EDIT",$fst_curr_code));
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
			"fst_curr_code"=>$fst_curr_code,
            "fst_curr_name"=>$this->input->post("fst_curr_name"),
			"fst_active"=>'A'
		];

		$this->db->trans_start();
		$this->mscurrencies_model->update($data);
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
		$this->load->model("mscurrenciesratedetails_model");
		$this->form_validation->set_rules($this->mscurrenciesratedetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		$this->mscurrenciesratedetails_model->deleteByDetail($fst_curr_code);
		
		$details = $this->input->post("detail");
		$details = json_decode($details);
		foreach ($details as $item) {
			$data = [
				"fst_curr_code"=> $fst_curr_code,
				"fdt_date"=> dBDateFormat($item->fdt_date),
				"fdc_exchange_rate_to_idr"=> $item->fdc_exchange_rate_to_idr
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
			
			$this->mscurrenciesratedetails_model->insert($data);
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
		$this->ajxResp["data"]["insert_id"] = $fst_curr_code;
		$this->json_output();
	}
	
    public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("mscurrencies");

		$selectFields = "fst_curr_code,fst_curr_name,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fst_curr_code","fst_curr_name"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='".$data["fst_curr_code"]."'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }

    public function fetch_data($fst_curr_code){
		$this->load->model("mscurrencies_model");
		$data = $this->mscurrencies_model->getDataById($fst_curr_code);
	
		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model("mscurrencies_model");
		$this->db->trans_start();
        $this->mscurrencies_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function ajxGetList(){
		$this->load->model("mscurrencies_model");
		$date =$this->input->get("trxDateTime");
		$rs = $this->mscurrencies_model->getCurrencyList($date);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs,
		]);


	}
}
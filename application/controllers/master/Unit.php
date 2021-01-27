<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends MY_Controller {
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
        $this->load->model('msunits_model');
    }

    public function index(){
        $this->lizt();
    }

    public function lizt(){
        $this->load->library('menus');
		$this->list['page_name'] = "Master Unit";
		$this->list['list_name'] = "unit List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/unit/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/unit/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/unit/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/unit/edit/';
		$this->list['arrSearch'] = [
			'fst_unit' => 'Unit'
		];
        $this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Project', 'link' => '#', 'icon' => ''],
			['title' => 'Unit', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '10%', 'data' => 'fin_rec_id'],
            ['title' => 'Unit', 'width' => '15%', 'data' => 'fst_unit'],
            ['title' => 'Desc', 'width' => '15%', 'data' => 'fst_desc'],
            ['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
		];
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('template/standardList',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main',$this->data);
    }

    private function openForm($mode = "ADD", $id = 0){
        $this->load->library("menus");

        if($this->input->post("submit") != ""){
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Mesin" : "Update Mesin";
        $edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
        $data["mdlEditForm"] = $edit_modal;
        
        $data["finRecId"] = $id;
        $page_content = $this->parser->parse('pages/master/unit/form',$data,true);
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

    public function Edit($fin_project_id){
        $this->openForm("EDIT", $fin_project_id);
    }

    public function ajx_add_save(){

        try{
            $preparedData = $this->prepareData();
            $this->validateData($preparedData);
            $this->db->trans_start();
            $insertId = $this->msunits_model->insert($preparedData);
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $insertId;
            $this->json_output();

        }catch(CustomException $e){
            $this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
        }        
    }

    public function ajx_edit_save(){
        try{
            $finRecId = $this->input->post("fin_rec_id");
            $dataOld = $this->msunits_model->getSimpleDataById($finRecId);
            if ($dataOld == null){
                throw new Customexception("Id not found !",3003,"FAILED",[]);
            }
            $preparedData = $this->prepareData();            
            $this->validateData($preparedData);
            $preparedData["fin_rec_id"] = $finRecId;
            $this->db->trans_start();
            //$insertId = $this->msmesin_model->insert($preparedData);
            $this->msunits_model->update($preparedData);
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $finRecId;
            $this->json_output();
        }catch(CustomException $e){
            $this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
        }
    }

    private function prepareData(){
        return [
            "fst_unit"=>$this->input->post("fst_unit"),
            "fst_desc"=>$this->input->post("fst_desc"),
            "fst_active"=>"A"
        ];
    }

    private function validateData($data){
        $this->form_validation->set_rules($this->msunits_model->getRules("ADD",0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        $this->form_validation->set_data($data);        
        if ($this->form_validation->run() == FALSE){
            //print_r($this->form_validation->error_array());
            throw new CustomException("VALIDATION_FORM_FAILED",3003,"Error Validation Forms",$this->form_validation->error_array());
        }
    }

    public function fetch_list_data(){
        $this->load->library("datatables");
        $this->datatables->setTableName("(select * from msunits) a");
    
        $selectFields = "*";
        $this->datatables->setSelectFields($selectFields);

        $searchFields = [];
        $searchFields[] = $this->input->get('optionSearch');
        $this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";

        //Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {
            //action
            $data["action"] = "<div style='font-size:16px'>
                    <a class='btn-edit' href='#' data-id='" . $data["fin_rec_id"] . "'><i class='fa fa-pencil'></i></a>
                </div>";
            
            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($id){
        $data = $this->msunits_model->getDataById($id);
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$data,
        ]);
    }

    public function delete($id){
        
        $this->db->trans_start();
        $this->msunits_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = lang("Data dihapus !");
        //$this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mesin extends MY_Controller {
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
        $this->load->model('msmesin_model');
        $this->load->model('msproductiontype_model');
    }

    public function index(){
        $this->lizt();
    }

    public function lizt(){
        $this->load->library('menus');
		$this->list['page_name'] = "Master Mesin";
		$this->list['list_name'] = "Mesin List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/mesin/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/mesin/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/mesin/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/mesin/edit/';
		$this->list['arrSearch'] = [
			'fin_mesin_id' => 'Mesin ID',
			'fst_mesin_name' => 'Mesin Name'
		];
        $this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Project', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '10%', 'data' => 'fin_mesin_id'],
            ['title' => 'Name', 'width' => '15%', 'data' => 'fst_name'],
            ['title' => 'Type', 'width' => '15%', 'data' => 'fst_production_type'],
            ['title' => 'Start Date', 'width' => '15%', 'data' => 'fdt_start_date'],
            ['title' => 'Fixed Asset Code', 'width' => '15%', 'data' => 'fst_fa_profile_code'],
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

    private function openForm($mode = "ADD", $finMesinId = 0){
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
        
        $data["fin_mesin_id"] = $finMesinId;
        $page_content = $this->parser->parse('pages/master/mesin/form',$data,true);
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
            $insertId = $this->msmesin_model->insert($preparedData);
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
            $finMesinId = $this->input->post("fin_mesin_id");
            $dataOld = $this->msmesin_model->getSimpleDataById($finMesinId);
            if ($dataOld == null){
                throw new Customexception("Id not found !",3003,"FAILED",[]);
            }
            $preparedData = $this->prepareData();            
            $this->validateData($preparedData);
            $preparedData["fin_mesin_id"] = $finMesinId;
            $this->db->trans_start();
            //$insertId = $this->msmesin_model->insert($preparedData);
            $this->msmesin_model->update($preparedData);
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $finMesinId;
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
            "fst_name"=>$this->input->post("fst_name"),
            "fin_productiontype_id"=>$this->input->post("fin_productiontype_id"),
            "fdt_start_date"=>dBDateFormat($this->input->post("fdt_start_date")),
            "fst_fa_profile_code"=>$this->input->post("fst_fa_profile_code"),
            "fst_memo"=>$this->input->post("fst_memo"),
            "fin_branch_id"=>$this->aauth->get_active_branch_id(),
            "fst_active"=>"A"
        ];
    }

    private function validateData($data){
        $this->form_validation->set_rules($this->msmesin_model->getRules("ADD",0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        $this->form_validation->set_data($data);        
        if ($this->form_validation->run() == FALSE){
            //print_r($this->form_validation->error_array());
            throw new CustomException("VALIDATION_FORM_FAILED",3003,"Error Validation Forms",$this->form_validation->error_array());
        }
    }

    public function fetch_list_data(){
        $this->load->library("datatables");
        $this->datatables->setTableName("(select a.*,b.fst_production_type from msmesin a inner join msproductiontype b on a.fin_productiontype_id = b.fin_rec_id) a");
    
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
                    <a class='btn-edit' href='#' data-id='" . $data["fin_mesin_id"] . "'><i class='fa fa-pencil'></i></a>
                </div>";
            
            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_mesin_id){
        $data = $this->msmesin_model->getDataById($fin_mesin_id);

        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$data,
        ]);
    }

    public function delete($id){
        
        $this->db->trans_start();
        $this->msmesin_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = lang("Data dihapus !");
        //$this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function ajxGetFA(){
        $term = $this->input->get("term");
        $term = "%$term%";
        $ssql = "select * from trfaprofilesitems 
            where fst_fa_profile_code like ? and fst_fa_profile_name like ? 
            and fbl_disposal = false and fst_active ='A'";
        $qr = $this->db->query($ssql,[$term,$term]);
        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
    }
}
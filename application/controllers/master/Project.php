<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project extends MY_Controller {
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
        $this->load->model('msprojects_model');
    }

    public function index(){
        $this->lizt();
    }

    public function lizt(){
        $this->load->library('menus');
		$this->list['page_name'] = "Master Project";
		$this->list['list_name'] = "Project List";
		$this->list['addnew_ajax_url'] = site_url() . 'master/project/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/project/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'master/project/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'master/project/edit/';
		$this->list['arrSearch'] = [
			'fin_project_id' => 'Project ID',
			'fst_project_name' => 'Project Name'
		];
        $this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Project', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'Project ID.', 'width' => '10%', 'data' => 'fin_project_id'],
			['title' => 'Project Name', 'width' => '15%', 'data' => 'fst_project_name'],
            ['title' => 'Start Date', 'width' => '15%', 'data' => 'fdt_project_start'],
            ['title' => 'End Date', 'width' => '15%', 'data' => 'fdt_project_end'],
            ['title' => 'Memo', 'width' => '15%', 'data' => 'fst_memo'],
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

    private function openForm($mode = "ADD", $fin_project_id = 0){
        $this->load->library("menus");

        if($this->input->post("submit") != ""){
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Project" : "Update Master Project";
        $data["fin_project_id"] = $fin_project_id;

        $page_content = $this->parser->parse('pages/master/project/form',$data,true);
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
        $this->load->model('msprojects_model');
        $this->form_validation->set_rules($this->msprojects_model->getRules("ADD",0));
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
            "fst_project_name" => $this->input->post("fst_project_name"),
            "fdt_project_start" => dBDateFormat($this->input->post("fdt_project_start")),
            "fdt_project_end" => dBDateFormat($this->input->post("fdt_project_end")),
            "fst_memo" => $this->input->post("fst_memo"),
            "fin_branch_id" => $this->aauth->get_active_branch_id(),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->msprojects_model->insert($data);
        $dbError = $this->db->error();
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
        $this->load->model('msprojects_model');
        $fin_project_id = $this->input->post("fin_project_id");
        $data = $this->msprojects_model->getDataById($fin_project_id);
        $msprojects = $data["ms_projects"];
        if (!$msprojects){
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_project_id Not Found";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->msprojects_model->getRules("EDIT", $fin_project_id));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $data = [
            "fin_project_id" => $fin_project_id,
            "fst_project_name" => $this->input->post("fst_project_name"),
            "fdt_project_start" => dBDateFormat($this->input->post("fdt_project_start")),
            "fdt_project_end" => dBDateFormat($this->input->post("fdt_project_end")),
            "fst_memo" => $this->input->post("fst_memo"),
            "fin_branch_id" => $this->aauth->get_active_branch_id(),
            "fst_active" =>'A'
        ];

        $this->db->trans_start();
        $this->msprojects_model->update($data);
        $dbError = $this->db->error();
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
        $this->ajxResp["data"]["insert_id"] = $fin_project_id;
        $this->json_output();
    }

    public function fetch_list_data(){
        $this->load->library("datatables");
        $this->datatables->setTableName("msprojects");
    
        $selectFields = "fin_project_id,fst_project_name,fdt_project_start,fdt_project_end,fst_memo,'action' as action";
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
                    <a class='btn-edit' href='#' data-id='" . $data["fin_project_id"] . "'><i class='fa fa-pencil'></i></a>
                    <a class='btn-delete' href='#' data-id='" . $data["fin_project_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
                </div>";
            
            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_project_id){
        $this->load->model("msprojects_model");
        $data = $this->msprojects_model->getDataById($fin_project_id);

        $this->json_output($data);
    }

    public function delete($id){
        $this->load->model("msprojects_model");
        $this->db->trans_start();
        $this->msprojects_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = lang("Data dihapus !");
        //$this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function getAllList(){
        $this->load->model('msprojects_model');
        $result = $this->msprojects_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }
}
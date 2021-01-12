<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Production_type extends MY_Controller {
    public $menuName="master_production_type"; 
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
        $this->load->model('msproductiontype_model');
    }


    public function index(){
        parent::index();
        $this->load->library('menus');
		$this->list['page_name'] = "Master Project";
        $this->list['list_name'] = "Project List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."master/production_type/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'master/production_type/fetch_list_data';		
		$this->list['arrSearch'] = [
			'fst_production_type' => 'Name'
		];
        $this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Project', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];
		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '10%', 'data' => 'fin_rec_id'],
			['title' => 'Production Type', 'width' => '', 'data' => 'fst_production_type'],
            ['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."master/production_type/edit/' + row.fin_rec_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
        ];
        $this->list['jsfile'] = $this->parser->parse('template/listjs', [], true);
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('template/standardList_v2_0_0',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main',$this->data);
    }

    private function openForm($mode = "ADD", $fin_rec_id = 0){
        $this->load->library("menus");

        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Production Type" : "Update Production Type";
        $data["fin_rec_id"] = $fin_rec_id;

        $page_content = $this->parser->parse('pages/master/production_type/form',$data,true);
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

    public function edit($finId){
        parent::edit($finId);
        $this->openForm("EDIT", $finId);
    }

    public function ajx_add_save(){
        parent::ajx_add_save();
        $this->form_validation->set_rules($this->msproductiontype_model->getRules("ADD",0));
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
            "fst_production_type" => $this->input->post("fst_production_type"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->msproductiontype_model->insert($data);
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
        parent::ajx_edit_save();
        $this->load->model('msprojects_model');
        $id = $this->input->post("fin_rec_id");
        $data = $this->msproductiontype_model->getDataById($id);
        $rw = $data["msproductiontype"];
        if ($rw == null){
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $id Not Found";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->msproductiontype_model->getRules("EDIT", $id));
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
            "fin_rec_id" => $id,
            "fst_production_type" => $this->input->post("fst_production_type"),
            "fst_active" =>'A'
        ];

        $this->db->trans_start();
        $this->msproductiontype_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $id;
        $this->json_output();
    }

    public function fetch_list_data(){
        $this->load->library("datatables");
        $this->datatables->setTableName("msproductiontype");
    
        $selectFields = "fin_rec_id,fst_production_type,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $searchFields = [];
        $searchFields[] = $this->input->get('optionSearch');
        $this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";

        //Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $datasources["data"] = $arrData;
        $this->json_output($datasources);
    }

    public function fetch_data($finId){
        $data = $this->msproductiontype_model->getDataById($finId);

        $this->json_output([
            "status"=>"SUCCESS",
            "message"=>"",
            "data"=>$data["msproductiontype"]
        ]);
    }

    public function delete($id){
        parent::delete($id);
        $this->load->model("msprojects_model");
        
        $this->msproductiontype_model->delete($id);
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = lang("Data dihapus !");
        //$this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }    
}
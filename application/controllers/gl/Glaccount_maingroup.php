<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Glaccount_maingroup extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->lizt();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Master GL Main Groups";
        $this->list['list_name'] = "GL Main Group List";
        $this->list['addnew_ajax_url'] = site_url() . 'GL/GLAccountMainGroups/';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'GL/GLAccountMainGroups/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'GL/GLAccountMainGroups/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'GL/GLAccountMainGroups/edit/';
        $this->list['arrSearch'] = [
            'GLAccountMainGroupId' => 'GL Main Group ID',
            'GLAccountMainGroupName' => 'GL Main Group Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Main Group', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'GL Main Group ID', 'width' => '7%', 'data' => 'GLAccountMainGroupId'],
            ['title' => 'GL Main Group Name', 'width' => '15%', 'data' => 'GLAccountMainGroupName'],
            ['title' => 'Prefix', 'width' => '10%', 'data' => 'GLAccountMainPrefix'],
            ['title' => 'Action', 'width' => '5%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
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

    private function openForm($mode = "ADD", $GLAccountMainGroupId = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add GL Main Group" : "Update GL Main Group";
        $data["GLAccountMainGroupId"] = $GLAccountMainGroupId;

        $page_content = $this->parser->parse('pages/gl/glaccountmaingroups/form', $data, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);

        $control_sidebar = NULL;
        $this->data["MAIN_HEADER"] = $main_header;
        $this->data["MAIN_SIDEBAR"] = $main_sidebar;
        $this->data["PAGE_CONTENT"] = $page_content;
        $this->data["MAIN_FOOTER"] = $main_footer;
        $this->data["CONTROL_SIDEBAR"] = $control_sidebar;
        $this->parser->parse('template/main', $this->data);
    }

    public function add()
    {
        $this->openForm("ADD", 0);
    }

    public function Edit($GLAccountMainGroupId)
    {
        $this->openForm("EDIT", $GLAccountMainGroupId);
    }

    public function ajx_add_save()
    {
        $this->load->model('GLAccountMainGroups_model');
        $this->form_validation->set_rules($this->GLAccountMainGroups_model->getRules("ADD", 0));
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
            "GLAccountMainGroupId" => $this->input->post("GLAccountMainGroupId"),
            "GLAccountMainGroupName" => $this->input->post("GLAccountMainGroupName"),
            "GLAccountMainPrefix" => $this->input->post("GLAccountMainPrefix"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->GLAccountMainGroups_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
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

    public function ajx_edit_save()
    {
        $this->load->model('GLAccountMainGroups_model');
        $GLAccountMainGroupId = $this->input->post("GLAccountMainGroupId");
        $data = $this->GLAccountMainGroups_model->getDataById($GLAccountMainGroupId);
        $gl_maingroups = $data["glAccountMainGroups"];
        if (!$gl_maingroups) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $GLAccountMainGroupId Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->GLAccountMainGroups_model->getRules("EDIT", $GLAccountMainGroupId));
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
            "GLAccountMainGroupId" => $GLAccountMainGroupId,
            "GLAccountMainGroupName" => $this->input->post("GLAccountMainGroupName"),
            "GLAccountMainPrefix" => $this->input->post("GLAccountMainPrefix"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->GLAccountMainGroups_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
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
        $this->ajxResp["data"]["insert_id"] = $GLAccountMainGroupId;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("glaccountmaingroups");

        $selectFields = "GLAccountMainGroupId,GLAccountMainGroupName,GLAccountMainPrefix,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $Fields = $this->input->get('optionSearch');
        $searchFields = [$Fields];
        $this->datatables->setSearchFields($searchFields);
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {
            //action
            $data["action"]    = "<div style='font-size:16px'>
                        <a class='btn-edit' href='#' data-id='" . $data["GLAccountMainGroupId"] . "'><i class='fa fa-pencil'></i></a>
                    </div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($GLAccountMainGroupId)
    {
        $this->load->model("GLAccountMainGroups_model");
        $data = $this->GLAccountMainGroups_model->getDataById($GLAccountMainGroupId);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function delete($id)
    {
        if (!$this->aauth->is_permit("")) {
            $this->ajxResp["status"] = "NOT_PERMIT";
            $this->ajxResp["message"] = "You not allowed to do this operation !";
            $this->json_output();
            return;
        }
        //echo $id;
        //die ();
        $this->load->model("GLAccountMainGroups_model");

        $this->GLAccountMainGroups_model->delete($id);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }
}

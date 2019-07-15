<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_item extends MY_Controller
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
        $this->list['page_name'] = "Master Groups";
        $this->list['list_name'] = "Group List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/group_item/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/group_item/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/group_item/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/group_item/edit/';
        $this->list['arrSearch'] = [
            'fin_item_group_id' => 'Group ID',
            'fst_item_group_name' => 'Group Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Group', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Group ID', 'width' => '10%', 'data' => 'fin_item_group_id'],
            ['title' => 'Group Name', 'width' => '25%', 'data' => 'fst_item_group_name'],
            ['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
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

    private function openForm($mode = "ADD", $fin_item_group_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Group" : "Update Group";
        $data["fin_item_group_id"] = $fin_item_group_id;

        $page_content = $this->parser->parse('pages/master/groupitems/form', $data, true);
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

    public function Edit($fin_item_group_id)
    {
        $this->openForm("EDIT", $fin_item_group_id);
    }

    public function ajx_add_save()
    {
        $this->load->model('msgroupitems_model');
        $this->form_validation->set_rules($this->msgroupitems_model->getRules("ADD", 0));
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
            "fst_item_group_name" => $this->input->post("fst_item_group_name"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->msgroupitems_model->insert($data);
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
        $this->load->model('msgroupitems_model');
        $fin_item_group_id = $this->input->post("fin_item_group_id");
        $data = $this->msgroupitems_model->getDataById($fin_item_group_id);
        $master_groups = $data["groupitems"];
        if (!$master_groups) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_item_group_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->msgroupitems_model->getRules("EDIT", $fin_item_group_id));
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
            "fin_item_group_id" => $fin_item_group_id,
            "fst_item_group_name" => $this->input->post("fst_item_group_name"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->msgroupitems_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_item_group_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("msgroupitems");

        $selectFields = "fin_item_group_id,fst_item_group_name,'action' as action";
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
                        <a class='btn-edit' href='#' data-id='" . $data["fin_item_group_id"] . "'><i class='fa fa-pencil'></i></a>
                        <a class='btn-delete' href='#' data-id='" . $data["fin_item_group_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
                    </div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_item_group_id)
    {
        $this->load->model("msgroupitems_model");
        $data = $this->msgroupitems_model->getDataById($fin_item_group_id);

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
        $this->load->model("msgroupitems_model");

        $this->msgroupitems_model->delete($id);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }
}

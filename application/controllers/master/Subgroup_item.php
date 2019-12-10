<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subgroup_item extends MY_Controller
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
        $this->list['page_name'] = "Master Subgroups";
        $this->list['list_name'] = "Subgroup List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/subgroup_item/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/subgroup_item/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/subgroup_item/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/subgroup_item/edit/';
        $this->list['arrSearch'] = [
            'a.fin_item_subgroup_id' => 'Subgroup ID',
            'a.fst_item_subgroup_name' => 'Subgroup Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Subgroup', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Subgroup ID', 'width' => '10%', 'data' => 'fin_item_subgroup_id'],
            ['title' => 'Subgroup Name', 'width' => '25%', 'data' => 'fst_item_subgroup_name'],
            ['title' => 'Group Name', 'width' => '25%', 'data' => 'fst_item_group_name'],
            ['title' => 'Action', 'width' => '7%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
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

    private function openForm($mode = "ADD", $fin_item_subgroup_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Subgroup" : "Update Subgroup";
        $data["fin_item_subgroup_id"] = $fin_item_subgroup_id;

        $page_content = $this->parser->parse('pages/master/subgroupitems/form', $data, true);
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

    public function Edit($fin_item_subgroup_id)
    {
        $this->openForm("EDIT", $fin_item_subgroup_id);
    }

    public function ajx_add_save()
    {
        $this->load->model('mssubgroupitems_model');
        $this->form_validation->set_rules($this->mssubgroupitems_model->getRules("ADD", 0));
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
            "fst_item_subgroup_name" => $this->input->post("fst_item_subgroup_name"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->mssubgroupitems_model->insert($data);
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
        $this->load->model('mssubgroupitems_model');
        $fin_item_subgroup_id = $this->input->post("fin_item_subgroup_id");
        $data = $this->mssubgroupitems_model->getDataById($fin_item_subgroup_id);
        $master_subgroups = $data["subgroupitems"];
        if (!$master_subgroups) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_item_subgroup_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->mssubgroupitems_model->getRules("EDIT", $fin_item_subgroup_id));
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
            "fin_item_subgroup_id" => $fin_item_subgroup_id,
            "fst_item_subgroup_name" => $this->input->post("fst_item_subgroup_name"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->mssubgroupitems_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_item_subgroup_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("
        (
            select a.*,b.fst_item_group_name from mssubgroupitems a
            left join msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
        ) a 
        ");
        $selectFields = "a.fin_item_subgroup_id,a.fst_item_subgroup_name,a.fst_item_group_name,'action' as action";
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
                        <a class='btn-edit' href='#' data-id='" . $data["fin_item_subgroup_id"] . "'><i class='fa fa-pencil'></i></a>
                    </div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_item_subgroup_id)
    {
        $this->load->model("mssubgroupitems_model");
        $data = $this->mssubgroupitems_model->getDataById($fin_item_subgroup_id);

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
        $this->load->model("mssubgroupitems_model");

        $this->mssubgroupitems_model->delete($id);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }

    public function get_data_ItemGroup()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msgroupitems where fst_item_group_name like ? order by fst_item_group_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }
}

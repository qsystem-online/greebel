<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mssubgroupitems extends MY_Controller
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
        $this->list['addnew_ajax_url'] = site_url() . 'Master/mssubgroupitems/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'Master/mssubgroupitems/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'Master/mssubgroupitems/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'Master/mssubgroupitems/edit/';
        $this->list['arrSearch'] = [
            'a.ItemSubGroupId' => 'Subgroup ID',
            'a.ItemSubGroupName' => 'Subgroup Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Subgroup', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Subgroup ID', 'width' => '10%', 'data' => 'ItemSubGroupId'],
            ['title' => 'Subgroup Name', 'width' => '25%', 'data' => 'ItemSubGroupName'],
            ['title' => 'Group Name', 'width' => '25%', 'data' => 'ItemGroupName'],
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

    private function openForm($mode = "ADD", $ItemSubGroupId = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Subgroup" : "Update Subgroup";
        $data["ItemSubGroupId"] = $ItemSubGroupId;

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

    public function Edit($ItemSubGroupId)
    {
        $this->openForm("EDIT", $ItemSubGroupId);
    }

    public function ajx_add_save()
    {
        $this->load->model('MSSubgroupitems_model');
        $this->form_validation->set_rules($this->MSSubgroupitems_model->getRules("ADD", 0));
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
            "ItemSubGroupName" => $this->input->post("ItemSubGroupName"),
            "ItemGroupId" => $this->input->post("ItemGroupId"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->MSSubgroupitems_model->insert($data);
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
        $this->load->model('MSSubgroupitems_model');
        $ItemSubGroupId = $this->input->post("ItemSubGroupId");
        $data = $this->MSSubgroupitems_model->getDataById($ItemSubGroupId);
        $master_subgroups = $data["subgroupitems"];
        if (!$master_subgroups) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $ItemSubGroupId Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->MSSubgroupitems_model->getRules("EDIT", $ItemSubGroupId));
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
            "ItemSubGroupId" => $ItemSubGroupId,
            "ItemSubGroupName" => $this->input->post("ItemSubGroupName"),
            "ItemGroupId" => $this->input->post("ItemGroupId"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->MSSubgroupitems_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $ItemSubGroupId;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("
        (
            select a.*,b.ItemGroupName from mssubgroupitems a
            left join msgroupitems b on a.ItemGroupId = b.ItemGroupId
        ) a 
        ");
        $selectFields = "a.ItemSubGroupId,a.ItemSubGroupName,a.ItemGroupName,'action' as action";
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
                        <a class='btn-edit' href='#' data-id='" . $data["ItemSubGroupId"] . "'><i class='fa fa-pencil'></i></a>
                        <a class='btn-delete' href='#' data-id='" . $data["ItemSubGroupId"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
                    </div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($ItemSubGroupId)
    {
        $this->load->model("MSSubgroupitems_model");
        $data = $this->MSSubgroupitems_model->getDataById($ItemSubGroupId);

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
        $this->load->model("MSSubgroupitems_model");

        $this->MSSubgroupitems_model->delete($id);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }

    public function get_data_ItemGroup()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msgroupitems where ItemGroupName like ? order by ItemGroupName";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Glaccount extends MY_Controller
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
        $this->list['page_name'] = "Master GL Account";
        $this->list['list_name'] = "GL Account List";
        $this->list['addnew_ajax_url'] = site_url() . 'gl/glaccount/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'gl/glaccount/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'gl/glaccount/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'gl/glaccount/edit/';
        $this->list['arrSearch'] = [
            'a.fst_glaccount_code' => 'GL Account Code',
            'a.fst_glaccount_name' => 'GL Account Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master GL Account', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'GL Account Code', 'width' => '8%', 'data' => 'fst_glaccount_code'],
            ['title' => 'GL Account Name', 'width' => '15%', 'data' => 'fst_glaccount_name'],
            ['title' => 'GL Main Group Name', 'width' => '10%', 'data' => 'fst_glaccount_maingroup_name'],
            ['title' => 'Parent', 'width' => '12%', 'data' => 'ParentGLAccountName'],
            ['title' => 'Default Post', 'width' => '7%', 'data' => 'fst_default_post'],
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

    private function openForm($mode = "ADD", $fst_glaccount_code = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add GL Account" : "Update GL Account";
        $data["fst_glaccount_code"] = $fst_glaccount_code;
        $data["mainGLSeparator"] = getDbConfig("main_glaccount_separator");
        $data["parentGLSeparator"] = getDbConfig("parent_glaccount_separator");
        

        $page_content = $this->parser->parse('pages/gl/glaccounts/form', $data, true);
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

    public function Edit($fst_glaccount_code)
    {
        $this->openForm("EDIT", $fst_glaccount_code);
    }

    public function ajx_add_save()
    {
        $this->load->model('GLaccounts_model');
        $this->form_validation->set_rules($this->GLaccounts_model->getRules("ADD", 0));
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
            "fst_glaccount_code" =>  $this->input->post("fst_glaccount_code"),
            "fst_glaccount_name" => $this->input->post("fst_glaccount_name"),
            "fin_glaccount_maingroup_id" => $this->input->post("fin_glaccount_maingroup_id"),
            "fst_glaccount_level" => $this->input->post("fst_glaccount_level"),
            "fst_parent_glaccount_code" => $this->input->post("fst_parent_glaccount_code"),
            "fst_default_post" => $this->input->post("fst_default_post"),
            "fin_min_user_level_access" => $this->input->post("fin_min_user_level_access"),
            "fst_curr_code" => $this->input->post("fst_curr_code"),
            "fin_seq_no" => $this->input->post("fin_seq_no"),
            "fbl_is_allow_in_cash_bank_module" => ($this->input->post("fbl_is_allow_in_cash_bank_module") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->GLaccounts_model->insert($data);
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
        $this->load->model('GLaccounts_model');
        $fst_glaccount_code = $this->input->post("fst_glaccount_code");
        $data = $this->GLaccounts_model->getDataById($fst_glaccount_code);
        $glaccounts = $data["gl_Account"];
        if (!$glaccounts) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fst_glaccount_code Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->GLaccounts_model->getRules("EDIT", $fst_glaccount_code));
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
            "fst_glaccount_code" => $fst_glaccount_code,
            "fst_glaccount_name" => $this->input->post("fst_glaccount_name"),
            //"fin_glaccount_maingroup_id" => $this->input->post("fin_glaccount_maingroup_id"),
            //"fst_glaccount_level" => $this->input->post("fst_glaccount_level"),
            //"fst_parent_glaccount_code" => $this->input->post("fst_parent_glaccount_code"),
            "fst_default_post" => $this->input->post("fst_default_post"),
            "fin_min_user_level_access" => $this->input->post("fin_min_user_level_access"),
            "fst_curr_code" => $this->input->post("fst_curr_code"),
            "fin_seq_no"=> $this->input->post("fin_seq_no"),
            "fbl_is_allow_in_cash_bank_module" => $this->input->post("fbl_is_allow_in_cash_bank_module"),
            "fst_active" => 'A'
        ];

        if ($this->input->post("fst_glaccount_level") !=  null){
            $data["fst_glaccount_level"] = $this->input->post("fst_glaccount_level");
        }


        $this->db->trans_start();

        $this->GLaccounts_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $fst_glaccount_code;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("(select a.*,b.fst_glaccount_maingroup_name,c.fst_glaccount_name as ParentGLAccountName from glaccounts a left join 
        glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id left join glaccounts c ON a.fst_parent_glaccount_code = c.fst_glaccount_code) a");

        $selectFields = "a.fst_glaccount_code,a.fst_glaccount_name,a.fst_glaccount_maingroup_name,a.ParentGLAccountName,a.fst_default_post,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $searchFields =[];
		$searchFields[] = $this->input->get('optionSearch');
		$this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";
        
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {
            switch ($data["fst_default_post"]) {
                case 'D':
                    $fst_default_post = "Debit";
                    break;
                case 'C':
                    $fst_default_post = "Credit";
                    break;
            }
            $data["fst_default_post"] = $fst_default_post;
            //action
            $data["action"]    = "<div style='font-size:16px'>
                        <a class='btn-edit' href='#' data-id='" . $data["fst_glaccount_code"] . "'><i class='fa fa-pencil'></i></a>
                        <a class='btn-delete' href='#' data-id='" . $data["fst_glaccount_code"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
                    </div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fst_glaccount_code)
    {
        $this->load->model("GLaccounts_model");
        $data = $this->GLaccounts_model->getDataById($fst_glaccount_code);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function get_ParentGL($maingroupid)
    {
        $term = $this->input->get("term");
        $ssql = "SELECT * from glaccounts where fst_glaccount_name like ? and fin_glaccount_maingroup_id = ? and fst_glaccount_level = 'HD'";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $maingroupid]);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_MainGL()
    {
        $term = $this->input->get("term");
        $ssql = "SELECT fin_glaccount_maingroup_id, fst_glaccount_maingroup_name from glaccountmaingroups where fst_glaccount_maingroup_name like ?";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_Currency()
    {
        $term = $this->input->get("term");
        $ssql = "SELECT fst_curr_code, fst_curr_name from mscurrencies";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
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
        $this->load->model("GLaccounts_model");

        $this->GLaccounts_model->delete($id);
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }
}

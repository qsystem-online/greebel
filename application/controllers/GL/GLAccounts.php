<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GLAccounts extends MY_Controller
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
        $this->list['addnew_ajax_url'] = site_url() . 'GL/GLAccounts/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'GL/GLAccounts/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'GL/GLAccounts/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'GL/GLAccounts/edit/';
        $this->list['arrSearch'] = [
            'GLAccountCode' => 'GL Account Code',
            'GLAccountName' => 'GL Account Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master GL Account', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'GL Account Code', 'width' => '7%', 'data' => 'GLAccountCode'],
            ['title' => 'GL Account Name', 'width' => '15%', 'data' => 'GLAccountName'],
            ['title' => 'GL Main Group', 'width' => '10%', 'data' => 'GLAccountMainGroupId'],
            ['title' => 'Parent', 'width' => '10%', 'data' => 'ParentGLAccountCode'],
            ['title' => 'Default Post', 'width' => '7%', 'data' => 'DefaultPost'],
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

    private function openForm($mode = "ADD", $GLAccountCode = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add GL Account" : "Update GL Account";
        $data["GLAccountCode"] = $GLAccountCode;
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

    public function Edit($GLAccountCode)
    {
        $this->openForm("EDIT", $GLAccountCode);
    }

    public function ajx_add_save()
    {
        $this->load->model('GLAccounts_model');
        $this->form_validation->set_rules($this->GLAccounts_model->getRules("ADD", 0));
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
            "GLAccountCode" =>  $this->input->post("GLAccountCode"),
            "GLAccountName" => $this->input->post("GLAccountName"),
            "GLAccountMainGroupId" => $this->input->post("GLAccountMainGroupId"),
            "GLAccountLevel" => $this->input->post("GLAccountLevel"),
            "ParentGLAccountCode" => $this->input->post("ParentGLAccountCode"),
            "DefaultPost" => $this->input->post("DefaultPost"),
            "MinUserLevelAccess" => $this->input->post("MinUserLevelAccess"),
            "CurrCode" => $this->input->post("CurrCode"),
            "fin_seq_no" => $this->input->post("fin_seq_no"),
            "isAllowInCashBankModule" => ($this->input->post("isAllowInCashBankModule") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->GLAccounts_model->insert($data);
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
        $this->load->model('GLAccounts_model');
        $GLAccountCode = $this->input->post("GLAccountCode");
        $data = $this->GLAccounts_model->getDataById($GLAccountCode);
        $gl_accounts = $data["glAccounts"];
        if (!$gl_accounts) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $GLAccountCode Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->GLAccounts_model->getRules("EDIT", $GLAccountCode));
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
            "GLAccountCode" => $GLAccountCode,
            "GLAccountName" => $this->input->post("GLAccountName"),
            "GLAccountMainGroupId" => $this->input->post("GLAccountMainGroupId"),
            "GLAccountLevel" => $this->input->post("GLAccountLevel"),
            "ParentGLAccountCode" => $this->input->post("ParentGLAccountCode"),
            "DefaultPost" => $this->input->post("DefaultPost"),
            "MinUserLevelAccess" => $this->input->post("MinUserLevelAccess"),
            "CurrCode" => $this->input->post("CurrCode"),
            "fin_seq_no"=> $this->input->post("fin_seq_no"),
            "isAllowInCashBankModule" => $this->input->post("isAllowInCashBankModule"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->GLAccounts_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $GLAccountCode;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("glaccounts");

        $selectFields = "GLAccountCode,GLAccountName,GLAccountMainGroupId,ParentGLAccountCode,DefaultPost,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $Fields = $this->input->get('optionSearch');
        $searchFields = [$Fields];
        $this->datatables->setSearchFields($searchFields);
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {

            switch ($data["GLAccountMainGroupId"]) {
                case 1:
                    $GLAccountMainGroupId = "Assets";
                    break;
                case 2:
                    $GLAccountMainGroupId = "Liabilities";
                    break;
                case 3:
                    $GLAccountMainGroupId = "Equity";
                    break;
                case 4:
                    $GLAccountMainGroupId = "Income";
                    break;
                case 5:
                    $GLAccountMainGroupId = "Cost Of Sales";
                    break;
                case 6:
                    $GLAccountMainGroupId = "Expenses";
                    break;
                case 7:
                    $GLAccountMainGroupId = "Other Income";
                    break;
                case 8:
                    $GLAccountMainGroupId = "Other Expense";
                    break;
            }
            $data["GLAccountMainGroupId"] = $GLAccountMainGroupId;

            switch ($data["DefaultPost"]) {
                case 'D':
                    $DefaultPost = "Debit";
                    break;
                case 'C':
                    $DefaultPost = "Credit";
                    break;
            }
            $data["DefaultPost"] = $DefaultPost;
            //action
            $data["action"]    = "<div style='font-size:16px'>
                        <a class='btn-edit' href='#' data-id='" . $data["GLAccountCode"] . "'><i class='fa fa-pencil'></i></a>
                        <a class='btn-delete' href='#' data-id='" . $data["GLAccountCode"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
                    </div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($GLAccountCode)
    {
        $this->load->model("GLAccounts_model");
        $data = $this->GLAccounts_model->getDataById($GLAccountCode);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function get_ParentGL($maingroupid)
    {
        $term = $this->input->get("term");
        $ssql = "select *  from glaccounts where GLAccountName like ? and GLAccountMainGroupId = ? and GLAccountLevel = 'HD'";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $maingroupid]);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_MainGL()
    {
        $term = $this->input->get("term");
        $ssql = "select * from glaccountmaingroups where fst_active ='A'";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_CurrCode()
    {
        $term = $this->input->get("term");
        $ssql = "select CurrCode, CurrName from mscurrencies";
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
        $this->load->model("GLAccounts_model");

        $this->GLAccounts_model->delete($id);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }
}

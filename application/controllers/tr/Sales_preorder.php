<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_preorder extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Trsalespreorder_model');
    }

    public function index()
    {
        $this->lizt();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Sales Pre-order";
        $this->list['list_name'] = "Sales Pre-order List";
        $this->list['addnew_ajax_url'] = site_url() . 'tr/sales_preorder/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/sales_preorder/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'tr/sales_preorder/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'tr/sales_preorder/edit/';
        $this->list['arrSearch'] = [
            'fin_preorder_id' => 'ID',
            'fst_preorder_name' => 'Pre-order Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Sales Pre-order', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'ID', 'width' => '5%', 'data' => 'fin_preorder_id'],
            ['title' => 'Pre-order Name', 'width' => '10%', 'data' => 'fst_preorder_name'],
            ['title' => 'Start Date', 'width' => '5%', 'data' => 'fdt_start_date'],
            ['title' => 'End Date', 'width' => '5%', 'data' => 'fdt_end_date'],
            ['title' => 'Action', 'width' => '5%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
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

    private function openForm($mode = "ADD", $fin_preorder_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Sales Pre-order" : "Update Sales Pre-order";
        $data["fin_preorder_id"] = $fin_preorder_id;
        $data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>1], true);

        $page_content = $this->parser->parse('pages/tr/sales_preorder/form', $data, true);
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

    public function Edit($fin_preorder_id)
    {
        $this->openForm("EDIT", $fin_preorder_id);
    }

    public function ajx_add_save()
    {
        $this->load->model('Trsalespreorder_model');
        $this->form_validation->set_rules($this->Trsalespreorder_model->getRules("ADD", 0));
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
            "fst_preorder_code" => $this->input->post("fst_preorder_code"),
            "fst_preorder_name" => $this->input->post("fst_preorder_name"),
            "fin_item_maingroup_id" => $this->input->post("fin_item_maingroup_id"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fin_item_subgroup_id" => $this->input->post("fin_item_subgroup_id"),
            "fdt_start_date" => dBDateFormat($this->input->post("fdt_start_date")),
            "fdt_end_date" => dBDateFormat($this->input->post("fdt_end_date")),
            "fst_item_name" => $this->input->post("fst_item_name"),
            "fst_curr_code" => $this->input->post("fst_curr_code"),
            "fdc_preorder_price" => $this->input->post("fdc_preorder_price"),
            "fdc_minimal_deposit" => $this->input->post("fdc_minimal_deposit"),
            "fdt_eta_date" => dBDateFormat($this->input->post("fdt_eta_date")),
            "fst_notes" => $this->input->post("fst_notes"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->Trsalespreorder_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        //Save Pre-Order Branch Details

        $this->load->model("Trsalespreorderdetails_model");
        $details = $this->input->post("branchDetail");
        $details = json_decode($details);
        foreach ($details as $preorderbranchdetail) {
            $data = [
                "fin_preorder_id" => $insertId,
                "fin_branch_id" => $preorderbranchdetail->fin_branch_id,
                "fst_active" => 'A'
            ];
            $this->Trsalespreorderdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function ajx_edit_save()
    {
        $this->load->model('Trsalespreorder_model');
        $fin_preorder_id = $this->input->post("fin_preorder_id");
        $data = $this->Trsalespreorder_model->getDataById($fin_preorder_id);
        $preOrder = $data["preOrder"];
        if (!$preOrder) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_preorder_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->Trsalespreorder_model->getRules("EDIT", $fin_preorder_id));
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
            "fin_preorder_id" => $fin_preorder_id,
            "fst_preorder_code" => $this->input->post("fst_preorder_code"),
            "fst_preorder_name" => $this->input->post("fst_preorder_name"),
            "fin_item_maingroup_id" => $this->input->post("fin_item_maingroup_id"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fin_item_subgroup_id" => $this->input->post("fin_item_subgroup_id"),
            "fdt_start_date" => dBDateFormat($this->input->post("fdt_start_date")),
            "fdt_end_date" => dBDateFormat($this->input->post("fdt_end_date")),
            "fst_item_name" => $this->input->post("fst_item_name"),
            "fst_curr_code" => $this->input->post("fst_curr_code"),
            "fdc_preorder_price" => $this->input->post("fdc_preorder_price"),
            "fdc_minimal_deposit" => $this->input->post("fdc_minimal_deposit"),
            "fdt_eta_date" => dBDateFormat($this->input->post("fdt_eta_date")),
            "fst_notes" => $this->input->post("fst_notes"),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $this->Trsalespreorder_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        //Save Pre-Order Branch Details

        $this->load->model("Trsalespreorderdetails_model");
        $this->Trsalespreorderdetails_model->deleteByDetail($fin_preorder_id);
        $details = $this->input->post("branchDetail");
        $details = json_decode($details);
        foreach ($details as $preorderbranchdetail) {
            $data = [
                "fin_preorder_id" => $fin_preorder_id,
                "fin_branch_id" => $preorderbranchdetail->fin_branch_id,
                "fst_active" => 'A'
            ];
            $this->Trsalespreorderdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }

        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $fin_preorder_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("preorder");

        $selectFields = "fin_preorder_id,fst_preorder_name,fdt_start_date,fdt_end_date,'action' as action";
        $this->datatables->setSelectFields($selectFields);

        $searchFields = [];
        $searchFields[] = $this->input->get('optionSearch');
        $this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";

        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {
            //action
            $data["action"]    = "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_preorder_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_preorder_id)
    {
        $this->load->model("Trsalespreorder_model");
        $data = $this->Trsalespreorder_model->getDataById($fin_preorder_id);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function delete($id){
        $this->load->model("Trsalespreorder_model");
        $this->db->trans_start();
        $this->Trsalespreorder_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }

    public function getAllList()
    {
        $this->load->model('Trsalespreorder_model');
        $result = $this->Trsalespreorder_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_data_item_preorder()
    {
        $term = $this->input->get("term");
        $ssql = "select * from preorder where fst_item_name like ? order by fst_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_item_subgroup_preorder()
    {
        $term = $this->input->get("term");
        $ssql = "select * from mssubgroupitems where fst_item_subgroup_name like ? order by fst_item_subgroup_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    
    public function get_data_unitTerms($fin_item_id)
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitemunitdetails where fst_unit like ? and fin_item_id = ?";
        $qr = $this->db->query($ssql,['%'. $term .'%',$fin_item_id]);
        $rs = $qr->result();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
    }

    public function get_data_ItemMainGroupId()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msmaingroupitems where fst_item_maingroup_name like ? order by fst_item_maingroup_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }
    public function get_data_ItemGroupId()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msgroupitems where fst_item_group_name like ? order by fst_item_group_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }
    public function get_data_ItemSubGroupId($fin_item_group_id)
    {
        $term = $this->input->get("term");
        $ssql = "select * from mssubgroupitems where fst_item_subgroup_name like ? and fin_item_group_id = ?";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $fin_item_group_id]);
        $rs = $qr->result();
        $this->json_output($rs);
    }
    public function get_data_Item()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitems where fst_item_name like ? order by fst_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }
}

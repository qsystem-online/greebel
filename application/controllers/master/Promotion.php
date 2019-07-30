<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promotion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('mspromo_model');
    }

    public function index()
    {
        $this->lizt();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Sales Promotion";
        $this->list['list_name'] = "Sales Promotion List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/promotion/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/promotion/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/promotion/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/promotion/edit/';
        $this->list['arrSearch'] = [
            'fin_promo_id' => 'Promo ID',
            'fst_promo_name' => 'Promo Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Sales Promotion', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Promo ID', 'width' => '10%', 'data' => 'fin_promo_id'],
            ['title' => 'Promo Name', 'width' => '10%', 'data' => 'fst_promo_name'],
            ['title' => 'Start Date', 'width' => '15%', 'data' => 'fdt_start'],
            ['title' => 'End Date', 'width' => '15%', 'data' => 'fdt_end'],
            ['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
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

    private function openForm($mode = "ADD", $fin_promo_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Sales Promotion" : "Update Sales Promotion";
        $data["fin_promo_id"] = $fin_promo_id;
        $data["arrBranch"] = $this->msbranches_model->getAllList();


        $page_content = $this->parser->parse('pages/master/promotion/form', $data, true);
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

    public function Edit($fin_promo_id)
    {
        $this->openForm("EDIT", $fin_promo_id);
    }

    public function ajx_add_save()
    {
        $data = [
            "fst_promo_name" => $this->input->post("fst_promo_name"),
            //"fst_list_branch_id" => implode(",",$this->input->post("fst_list_branch_id")),
            "fst_promo_type" => $this->input->post("fst_promo_type"),
            "fdt_start" => dBDateFormat($this->input->post("fdt_start")),
            "fdt_end" => dBDateFormat($this->input->post("fdt_end")),
            "fbl_disc_per_item" => ($this->input->post("fbl_disc_per_item") == null) ? 0 : 1,
            "fin_promo_item_id" => $this->input->post("fin_promo_item_id"),
            "fdb_promo_qty" => $this->input->post("fdb_promo_qty"),
            "fst_promo_unit" => $this->input->post("fst_promo_unit"),
            "fdc_cashback" => $this->input->post("fdc_cashback"),
            "fst_other_prize" => $this->input->post("fst_other_prize"),
            "fdc_other_prize_in_value" => $this->input->post("fdc_other_prize_in_value"),
            "fst_promo_type" => $this->input->post("fst_promo_type"),
            "fbl_promo_gabungan" => ($this->input->post("fbl_promo_gabungan") == null) ? 0 : 1,
            "fbl_qty_gabungan" => ($this->input->post("fbl_qty_gabungan") == null) ? 0 : 1,
            "fdb_qty_gabungan" => $this->input->post("fdb_qty_gabungan"),
            "fst_unit_gabungan" => $this->input->post("fst_unit_gabungan"),
            "fdc_min_total_purchase" => $this->input->post("fdc_min_total_purchase"),
            "fbl_is_multiples_prize" => ($this->input->post("fbl_is_multiples_prize") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        if ($this->input->post("fst_list_branch_id") !=  null){
            $data["fst_list_branch_id"] = implode(",",$this->input->post("fst_list_branch_id"));
        }else{
            $data["fst_list_branch_id"] = $this->input->post("fst_list_branch_id");
        }
        
        $this->load->model('mspromo_model');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($this->mspromo_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $this->db->trans_start();
        $insertId = $this->mspromo_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        //Save Promo Terms

        $this->load->model("mspromoitems_model");
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $promoitem) {
            $data = [
                "fin_promo_id" => $insertId,
                "fst_item_type" => $promoitem->fst_item_type,
                "fin_item_id" => $promoitem->fin_item_id,
                "fdb_qty" => $promoitem->fdb_qty,
                "fst_unit" => $promoitem->fst_unit,
                "fst_active" => 'A'
            ];
            $this->mspromoitems_model->insert($data);
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

        //Save Promo Participants

        $this->load->model("mspromoitemscustomer_model");
        $details = $this->input->post("detailParticipants");
        $details = json_decode($details);
        foreach ($details as $promoparticipants) {
            $data = [
                "fin_promo_id" => $insertId,
                "fst_participant_type" => $promoparticipants->fst_participant_type,
                "fin_customer_id" => $promoparticipants->fin_customer_id
            ];
            $this->mspromoitemscustomer_model->insert($data);
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

        //Save discount per item promo

        $this->load->model("Mspromodiscperitems_model");
        $details = $this->input->post("detaildiscItem");
        $details = json_decode($details);
        foreach ($details as $promodiscitem) {
            $data = [
                "fin_promo_id" => $insertId,
                "fin_item_id" => $promodiscitem->fin_item_id,
                "fin_qty" => $promodiscitem->fin_qty,
                "fst_unit" => $promodiscitem->fst_unit,
                "fdc_disc_persen" => $promodiscitem->fdc_disc_persen,
                "fdc_disc_value" => $promodiscitem->fdc_disc_value,
                "fst_active" => 'A'
            ];
            $this->Mspromodiscperitems_model->insert($data);
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
        $this->load->model('mspromo_model');
        $fin_promo_id = $this->input->post("fin_promo_id");
        $data = $this->mspromo_model->getDataById($fin_promo_id);
        $msitems = $data["mspromo"];
        if (!$msitems) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_promo_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $data = [
            "fin_promo_id" => $fin_promo_id,
            "fst_promo_name" => $this->input->post("fst_promo_name"),
            //"fst_list_branch_id" => implode(",",$this->input->post("fst_list_branch_id")),
            "fst_promo_type" => $this->input->post("fst_promo_type"),
            "fdt_start" => dBDateFormat($this->input->post("fdt_start")),
            "fdt_end" => dBDateFormat($this->input->post("fdt_end")),
            "fbl_disc_per_item" => ($this->input->post("fbl_disc_per_item") == null) ? 0 : 1,
            "fin_promo_item_id" => $this->input->post("fin_promo_item_id"),
            "fdb_promo_qty" => $this->input->post("fdb_promo_qty"),
            "fst_promo_unit" => $this->input->post("fst_promo_unit"),
            "fdc_cashback" => $this->input->post("fdc_cashback"),
            "fst_other_prize" => $this->input->post("fst_other_prize"),
            "fdc_other_prize_in_value" => $this->input->post("fdc_other_prize_in_value"),
            "fst_promo_type" => $this->input->post("fst_promo_type"),
            "fbl_promo_gabungan" => ($this->input->post("fbl_promo_gabungan") == null) ? 0 : 1,
            "fbl_qty_gabungan" => ($this->input->post("fbl_qty_gabungan") == null) ? 0 : 1,
            "fdb_qty_gabungan" => $this->input->post("fdb_qty_gabungan"),
            "fst_unit_gabungan" => $this->input->post("fst_unit_gabungan"),
            "fdc_min_total_purchase" => $this->input->post("fdc_min_total_purchase"),
            "fbl_is_multiples_prize" => ($this->input->post("fbl_is_multiples_prize") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        if ($this->input->post("fst_list_branch_id") !=  null){
            $data["fst_list_branch_id"] = implode(",",$this->input->post("fst_list_branch_id"));
        }else{
            $data["fst_list_branch_id"] = $this->input->post("fst_list_branch_id");
        }

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($this->mspromo_model->getRules("EDIT", $fin_promo_id));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }

        $this->db->trans_start();
        $this->mspromo_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        //Save Promo Terms

        $this->load->model("mspromoitems_model");
        $this->mspromoitems_model->deleteByHeaderId($fin_promo_id);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $promoitem) {
            $data = [
                "fin_promo_id" => $fin_promo_id,
                "fst_item_type" => $promoitem->fst_item_type,
                "fin_item_id" => $promoitem->fin_item_id,
                "fdb_qty" => $promoitem->fdb_qty,
                "fst_unit" => $promoitem->fst_unit,
                "fst_active" => 'A'
            ];

            $this->mspromoitems_model->insert($data);
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

        //Save Promo Participants

        $this->load->model("mspromoitemscustomer_model");
        $this->mspromoitemscustomer_model->deleteByHeaderId($fin_promo_id);
        $details = $this->input->post("detailParticipants");
        $details = json_decode($details);
        foreach ($details as $promoparticipants) {
            $data = [
                "fin_promo_id" => $fin_promo_id,
                "fst_participant_type" => $promoparticipants->fst_participant_type,
                "fin_customer_id" => $promoparticipants->fin_customer_id,
                "fst_active" => 'A'
            ];
            $this->mspromoitemscustomer_model->insert($data);
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

        //Edit save discount per item promo

        $this->load->model("Mspromodiscperitems_model");
        $this->Mspromodiscperitems_model->deleteByHeaderId($fin_promo_id);
        $details = $this->input->post("detaildiscItem");
        $details = json_decode($details);
        foreach ($details as $promodiscitem) {
            $data = [
                "fin_promo_id" => $fin_promo_id,
                "fin_item_id" => $promodiscitem->fin_item_id,
                "fin_qty" => $promodiscitem->fin_qty,
                "fst_unit" => $promodiscitem->fst_unit,
                "fdc_disc_persen" => $promodiscitem->fdc_disc_persen,
                "fdc_disc_value" => $promodiscitem->fdc_disc_value,
                "fst_active" => 'A'
            ];
            $this->Mspromodiscperitems_model->insert($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_promo_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("mspromo");

        $selectFields = "fin_promo_id,fst_promo_name,fdt_start,fdt_end,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_promo_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_promo_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_promo_id)
    {
        $this->load->model("mspromo_model");
        $data = $this->mspromo_model->getDataById($fin_promo_id);

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

        $this->load->model("mspromo_model");

        $this->mspromo_model->delete($id);
        $this->ajxResp["status"] = "DELETED";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }

    public function getAllList()
    {
        $this->load->model('mspromo_model');
        $result = $this->mspromo_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_data_ItemPromo()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitems where fst_item_name like ? order by fst_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_item_SubgroupPromo()
    {
        $term = $this->input->get("term");
        $ssql = "select * from mssubgroupitems where fst_item_subgroup_name like ? order by fst_item_subgroup_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_unit()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msunits where fst_unit like ? order by fst_unit";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_relationpromo()
    {
		$term = $this->input->get("term");
		$ssql = "select * from msrelations where fst_relation_name like ? order by fst_relation_name";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->json_output($rs);
    }
    
    public function get_relationgrouppromo()
    {
		$term = $this->input->get("term");
		$ssql = "select * from msrelationgroups where fst_relation_group_name like ? order by fst_relation_group_name";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->json_output($rs);
    }
    
    public function get_membergrouppromo()
    {
		$term = $this->input->get("term");
		$ssql = "select * from msmembergroups where fst_member_group_name like ? order by fst_member_group_name";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->json_output($rs);
	}

    public function get_data_unitPromo($fin_item_id)
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitemunitdetails where fst_unit like ? and fin_item_id = ? and fbl_is_basic_unit=1  order by fst_unit";
        $qr = $this->db->query($ssql, ['%' . $term .'%',$fin_item_id]);
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

        //$this->json_output($rs);
    }
}

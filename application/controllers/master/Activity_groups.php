<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activity_groups extends MY_Controller
{
    public $menuName="production_group_activity";
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msactivitygroups_model');
    }

    public function index()
    {
        parent::index();
        $this->lizt();
    }

    public function lizt()
    {
        parent::index();
        $this->load->library('menus');
        $this->list['page_name'] = "Master Workstation Groups";
        $this->list['list_name'] = "Master Workstation Groups List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/activity_groups/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/activity_groups/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/activity_groups/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/activity_groups/edit/';
        $this->list['arrSearch'] = [
            'fin_activity_group_id' => 'Workstation Groups ID',
            'fst_activity_group_name' => 'Workstation Groups Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Workstation', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Workstation Groups ID', 'width' => '5%', 'data' => 'fin_activity_group_id'],
            ['title' => 'Workstation Groups Name', 'width' => '20%', 'data' => 'fst_activity_group_name'],
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

    private function openForm($mode = "ADD", $fin_activity_group_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Workstation Groups" : "Update Workstation Groups";
        $data["fin_activity_group_id"] = $fin_activity_group_id;


        $page_content = $this->parser->parse('pages/master/activitygroups/form', $data, true);
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
        parent::add();
        $this->openForm("ADD", 0);
    }

    public function edit($fin_activity_group_id)
    {
        parent::edit($fin_activity_group_id);
        $this->openForm("EDIT", $fin_activity_group_id);
    }

    public function ajx_add_save()
    {
        parent::ajx_add_save();
        $data = [
            "fst_activity_group_name" => $this->input->post("fst_activity_group_name"),
            "fst_active" => 'A'
        ];
        
        $this->load->model('msactivitygroups_model');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($this->msactivitygroups_model->getRules("ADD", 0));
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
        $insertId = $this->msactivitygroups_model->insert($data);
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

        $this->load->model("msactivitygroupdetails_model");
        $details = $this->input->post("groupdetails");
        $details = json_decode($details);
        foreach ($details as $groupdetail) {
            $data = [
                "fin_activity_group_id" => $insertId,
                "fin_activity_id" => $groupdetail->fin_activity_id,
                "fst_active" => 'A'
            ];
            $this->msactivitygroupdetails_model->insert($data);
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
        parent::ajx_edit_save();
        $this->load->model('msactivitygroups_model');
        $fin_activity_group_id = $this->input->post("fin_activity_group_id");
        $data = $this->msactivitygroups_model->getDataById($fin_activity_group_id);
        $msactivitygroups = $data["msactivitygroups"];
        if (!$msactivitygroups) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_activity_group_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $data = [
            "fin_activity_group_id" => $fin_activity_group_id,
            "fst_activity_group_name" => $this->input->post("fst_activity_group_name"),
            "fst_active" => 'A'
        ];

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($this->msactivitygroups_model->getRules("EDIT", $fin_activity_group_id));
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
        $this->msactivitygroups_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }

        //Save Activity borongan detail

        $this->load->model("msactivitygroupdetails_model");
        $this->msactivitygroupdetails_model->deleteByHeaderId($fin_activity_group_id);
        $details = $this->input->post("groupdetails");
        $details = json_decode($details);
        foreach ($details as $groupdetail) {
            $data = [
                "fin_activity_group_id" => $fin_activity_group_id,
                "fin_activity_id" => $groupdetail->fin_activity_id,
                "fst_active" => 'A'
            ];

            $this->msactivitygroupdetails_model->insert($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_activity_group_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("msactivitygroups");

        $selectFields = "fin_activity_group_id,fst_activity_group_name,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_activity_group_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_activity_group_id)
    {
        $this->load->model("msactivitygroups_model");
        $data = $this->msactivitygroups_model->getDataById($fin_activity_group_id);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function delete($id){
        parent::delete($id);
        $this->load->model("msactivitygroups_model");
        $this->db->trans_start();
        $this->msactivitygroups_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }

    public function getAllList()
    {
        $this->load->model('msactivitygroups_model');
        $result = $this->msactivitygroups_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_data_Activity()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msactivity where fst_name like ? order by fst_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function print_voucher($fin_activity_group_id){
		$this->data = $this->msactivitygroups_model->getDataById($fin_activity_group_id);
		//$data=[];
		$this->data["title"] = "ActivityGroups";		
		$page_content = $this->parser->parse('pages/master/activitygroups/voucher', $this->data, true);
		$this->data["PAGE_CONTENT"] = $page_content;	
		$strHtml = $this->parser->parse('template/voucher_pdf', $this->data, true);

		//$this->parser->parse('template/voucher', $this->data);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;				
		
		$mpdf->WriteHTML($strHtml);	
		//$mpdf->SetHTMLHeaderByName('MyFooter');

		//echo $data;
		$mpdf->Output();

	}
}

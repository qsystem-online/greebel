<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activity_teams extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msactivityteams_model');
    }

    public function index()
    {
        $this->lizt();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Master Team Workstation";
        $this->list['list_name'] = "Master Team Workstation List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/activity_teams/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/activity_teams/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/activity_teams/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/activity_teams/edit/';
        $this->list['arrSearch'] = [
            'fin_team_id' => 'Team ID',
            'fst_team_name' => 'Team Name',
            'headteam' => 'Head Team'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Team Workstation', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Team ID', 'width' => '5%', 'data' => 'fin_team_id'],
            ['title' => 'Team Name', 'width' => '20%', 'data' => 'fst_team_name'],
            ['title' => 'Head Team', 'width' => '10%', 'data' => 'headteam'],
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

    private function openForm($mode = "ADD", $fin_team_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Team Workstation" : "Update Team Workstation";
        $data["fin_team_id"] = $fin_team_id;


        $page_content = $this->parser->parse('pages/master/activityteams/form', $data, true);
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

    public function Edit($fin_team_id)
    {
        $this->openForm("EDIT", $fin_team_id);
    }

    public function ajx_add_save()
    {
        $data = [
            "fst_team_name" => $this->input->post("fst_team_name"),
            "fin_headteam_id" => $this->input->post("fin_headteam_id"),
            "fst_active" => 'A'
        ];
        
        $this->load->model('msactivityteams_model');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($this->msactivityteams_model->getRules("ADD", 0));
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
        $insertId = $this->msactivityteams_model->insert($data);
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

        $this->load->model("msactivityteamdetails_model");
        $details = $this->input->post("teamdetails");
        $details = json_decode($details);
        foreach ($details as $teamdetail) {
            $data = [
                "fin_team_id" => $insertId,
                "fin_user_id" => $teamdetail->fin_user_id,
                "fst_active" => 'A'
            ];
            $this->msactivityteamdetails_model->insert($data);
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
        $this->load->model('msactivityteams_model');
        $fin_team_id = $this->input->post("fin_team_id");
        $data = $this->msactivityteams_model->getDataById($fin_team_id);
        $msactivityteams = $data["msactivityteams"];
        if (!$msactivityteams) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_team_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $data = [
            "fin_team_id" => $fin_team_id,
            "fst_team_name" => $this->input->post("fst_team_name"),
            "fin_headteam_id" => $this->input->post("fin_headteam_id"),
            "fst_active" => 'A'
        ];

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($this->msactivityteams_model->getRules("EDIT", $fin_team_id));
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
        $this->msactivityteams_model->update($data);
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

        $this->load->model("msactivityteamdetails_model");
        $this->msactivityteamdetails_model->deleteByHeaderId($fin_team_id);
        $details = $this->input->post("teamdetails");
        $details = json_decode($details);
        foreach ($details as $teamdetail) {
            $data = [
                "fin_team_id" => $fin_team_id,
                "fin_user_id" => $teamdetail->fin_user_id,
                "fst_active" => 'A'
            ];

            $this->msactivityteamdetails_model->insert($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_team_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("(SELECT a.*,b.fst_username as headteam FROM msactivityteams a LEFT JOIN users b ON a.fin_headteam_id = b.fin_user_id) a");

        $selectFields = "fin_team_id,fst_team_name,headteam,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_team_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_team_id)
    {
        $this->load->model("msactivityteams_model");
        $data = $this->msactivityteams_model->getDataById($fin_team_id);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function delete($id){
        $this->load->model("msactivityteams_model");
        $this->db->trans_start();
        $this->msactivityteams_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }

    public function getAllList()
    {
        $this->load->model('msactivityteams_model');
        $result = $this->msactivityteams_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_user_team()
    {
        $term = $this->input->get("term");
        $produksi = getDbConfig("production_department_id");
        $ssql = "select * from users where fin_department_id = $produksi  and fst_username like ? order by fst_username";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function print_voucher($fin_team_id){
		$this->data = $this->msactivityteams_model->getDataById($fin_team_id);
		//$data=[];
		$this->data["title"] = "Workstation Teams";		
		$page_content = $this->parser->parse('pages/master/activityteams/voucher', $this->data, true);
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

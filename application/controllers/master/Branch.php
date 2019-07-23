<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msbranches_model');
    }

    public function index()
    {
        $this->lizt();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Branch";
        $this->list['list_name'] = "Branch List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/branch/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/branch/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/branch/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/branch/edit/';
        $this->list['arrSearch'] = [
            'fin_branch_id' => 'Branch ID',
            'fst_branch_name' => 'Branch Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Branch', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Branch ID', 'width' => '5%', 'data' => 'fin_branch_id'],
            ['title' => 'Branch Name', 'width' => '15%', 'data' => 'fst_branch_name'],
            ['title' => 'Phone', 'width' => '10%', 'data' => 'fst_branch_phone'],
            ['title' => 'Notes', 'width' => '15%', 'data' => 'fst_notes'],
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

    private function openForm($mode = "ADD", $fin_branch_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Branch" : "Update Branch";
        $data["fin_branch_id"] = $fin_branch_id;

        $page_content = $this->parser->parse('pages/master/branches/form', $data, true);
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

    public function Edit($fin_branch_id)
    {
        $this->openForm("EDIT", $fin_branch_id);
    }

    public function ajx_add_save()
    {
        $this->load->model('msbranches_model');
        $this->form_validation->set_rules($this->msbranches_model->getRules("ADD", 0));
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
            "fst_branch_name" => $this->input->post("fst_branch_name"),
            "fst_address" => $this->input->post("fst_address"),
            "fst_postalcode" => $this->input->post("fst_postalcode"),
			"fin_country_id" => $this->input->post("fin_country_id"),
			"fst_area_code" => $this->input->post("fst_kode"),
            "fst_branch_phone" => $this->input->post("fst_branch_phone"),
            "fst_notes" => $this->input->post("fst_notes"),
            "fbl_is_hq" => ($this->input->post("fbl_is_hq") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->msbranches_model->insert($data);
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
        $this->load->model('msbranches_model');
        $fin_branch_id = $this->input->post("fin_branch_id");
        $data = $this->msbranches_model->getDataById($fin_branch_id);
        $branch = $data["branches"];
        if (!$branch) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_branch_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->msbranches_model->getRules("EDIT", $fin_branch_id));
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
            "fin_branch_id" => $fin_branch_id,
            "fst_branch_name" => $this->input->post("fst_branch_name"),
            "fst_address" => $this->input->post("fst_address"),
            "fst_postalcode" => $this->input->post("fst_postalcode"),
			"fin_country_id" => $this->input->post("fin_country_id"),
			"fst_area_code" => $this->input->post("fst_kode"),
            "fst_branch_phone" => $this->input->post("fst_branch_phone"),
            "fst_notes" => $this->input->post("fst_notes"),
            "fbl_is_hq" => ($this->input->post("fbl_is_hq") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->msbranches_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_branch_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("msbranches");

        $selectFields = "fin_branch_id,fst_branch_name,fst_branch_phone,fst_notes,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_branch_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_branch_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_branch_id)
    {
        $this->load->model("msbranches_model");
        $data = $this->msbranches_model->getDataById($fin_branch_id);

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

        $this->load->model("msbranches_model");

        $this->departments_model->delete($id);
        $this->ajxResp["status"] = "DELETED";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }

    public function getAllList()
    {
        $result = $this->msbranches_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_Branch()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msbranches where fst_branch_name like ? order by fst_branch_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function report_branch()
    {
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
        //$this->pdf->setPaper('A4', 'landscape');

        $this->load->model("msbranches_model");
        $listBranch = $this->msbranches_model->get_Branch();
        $data = [
            "datas" => $listBranch
        ];

        $this->pdf->load_view('report/branch_pdf', $data);
        $this->Cell(30, 10, 'Percobaan Header Dan Footer With Page Number', 0, 0, 'C');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'R');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends MY_Controller
{

    public $menuName="warehouse"; 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('mswarehouse_model');
    }

    public function index()
    {
        parent::index();
        $this->lizt();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Warehouse";
        $this->list['list_name'] = "Warehouse List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/warehouse/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/warehouse/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/warehouse/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/warehouse/edit/';
        $this->list['arrSearch'] = [
            'fin_warehouse_id' => 'Warehouse ID',
            'fst_warehouse_name' => 'Warehouse Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Warehouse', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Warehouse ID', 'width' => '5%', 'data' => 'fin_warehouse_id'],
            ['title' => 'Warehouse Name', 'width' => '15%', 'data' => 'fst_warehouse_name'],
            ['title' => 'Branch', 'width' => '10%', 'data' => 'fst_branch_name'],
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

    private function openForm($mode = "ADD", $fin_warehouse_id = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Warehouse" : "Update Warehouse";
        $data["fin_warehouse_id"] = $fin_warehouse_id;

        $page_content = $this->parser->parse('pages/master/warehouse/form', $data, true);
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

    public function edit($fin_warehouse_id)
    {
        parent::edit($fin_warehouse_id);
        $this->openForm("EDIT", $fin_warehouse_id);
    }

    public function ajx_add_save()
    {
        parent::ajx_add_save();
        $this->load->model('mswarehouse_model');
        $this->form_validation->set_rules($this->mswarehouse_model->getRules("ADD", 0));
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
            "fst_warehouse_name" => $this->input->post("fst_warehouse_name"),
            "fin_branch_id" => $this->input->post("fin_branch_id"),
			"fst_delivery_address" => $this->input->post("fst_delivery_address"),
            "fbl_is_external" => ($this->input->post("fbl_is_external") == null) ? 0 : 1,
            "fbl_is_main" => ($this->input->post("fbl_is_main") == null) ? 0 : 1,
            "fbl_logistic" => ($this->input->post("fbl_logistic") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->mswarehouse_model->insert($data);
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
        parent::ajx_edit_save();
        $this->load->model('mswarehouse_model');
        $fin_warehouse_id = $this->input->post("fin_warehouse_id");
        $data = $this->mswarehouse_model->getDataById($fin_warehouse_id);
        $warehouse = $data["warehouse"];
        if (!$warehouse) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_warehouse_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->mswarehouse_model->getRules("EDIT", $fin_warehouse_id));
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
            "fin_warehouse_id" => $fin_warehouse_id,
            "fst_warehouse_name" => $this->input->post("fst_warehouse_name"),
            "fin_branch_id" => $this->input->post("fin_branch_id"),
            "fst_delivery_address" => $this->input->post("fst_delivery_address"),
            "fbl_is_external" => ($this->input->post("fbl_is_external") == null) ? 0 : 1,
            "fbl_is_main" => ($this->input->post("fbl_is_main") == null) ? 0 : 1,
            "fbl_logistic" => ($this->input->post("fbl_logistic") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];

        $this->db->trans_start();

        $this->mswarehouse_model->update($data);
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
        $this->ajxResp["data"]["insert_id"] = $fin_warehouse_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("(select a.*,b.fst_branch_name from mswarehouse a inner join msbranches b on a.fin_branch_id = b.fin_branch_id) a");

        $selectFields = "fin_warehouse_id,fst_warehouse_name,fst_branch_name,fst_delivery_address,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["fin_warehouse_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_warehouse_id)
    {
        $this->load->model("mswarehouse_model");
        $data = $this->mswarehouse_model->getDataById($fin_warehouse_id);

        //$this->load->library("datatables");		
        $this->json_output($data);
    }

	public function delete($id){
        parent::delete($id);
		$this->db->trans_start();
        $this->mswarehouse_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
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

    public function getAllList()
    {
        $result = $this->mswarehouse_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function report_branch()
    {
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
        //$this->pdf->setPaper('A4', 'landscape');

        $this->load->model("mswarehouse_model");
        $listBranch = $this->mswarehouse_model->get_Branch();
        $data = [
            "datas" => $listBranch
        ];

        $this->pdf->load_view('report/branch_pdf', $data);
        $this->Cell(30, 10, 'Percobaan Header Dan Footer With Page Number', 0, 0, 'C');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'R');
    }

    public function ajxGetWarehouseList(){
        $warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$warehouseList
        ]);
    }
}

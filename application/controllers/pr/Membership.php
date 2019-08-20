<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Membership extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msmemberships_model');
	}

	public function index(){
		$this->lizt();
	}

  	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Master Memberships";
		$this->list['list_name'] = "Master Memberships List";
		$this->list['addnew_ajax_url'] = site_url() . 'pr/membership/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'pr/membership/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'pr/membership/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'pr/membership/edit/';
		$this->list['arrSearch'] = [
			'fin_rec_id' => 'Rec ID',
			'fst_name_on_card' => 'Name On Card'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Master Memberships', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Rec ID', 'width' => '8%', 'data' => 'fin_rec_id'],
			['title' => 'Member No', 'width' => '12%', 'data' => 'fst_member_no'],
			['title' => 'Relation Name', 'width' => '15%', 'data' => 'fst_relation_name'],
			['title' => 'Member Group Name', 'width' => '18%', 'data' => 'fst_member_group_name'],
			['title' => 'Name On Card', 'width' => '15%', 'data' => 'fst_name_on_card'],
			['title' => 'Expiry Date', 'width' => '12%', 'data' => 'fdt_expiry_date'],
			['title' => 'Member Disc (%)', 'width' => '10%', 'data' => 'fdc_member_discount_percent'],
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
    
  	private function openForm($mode = "ADD", $fin_rec_id = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Memberships" : "Update Master Memberships";
		$data["fin_rec_id"] = $fin_rec_id;

		$page_content = $this->parser->parse('pages/pr/msmemberships/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
  	}
    
  	public function add(){
		$this->openForm("ADD", 0);
	}

	public function Edit($fin_rec_id){
		$this->openForm("EDIT", $fin_rec_id);
  	}
    
	public function ajx_add_save(){
		$this->load->model('msmemberships_model');
		$this->form_validation->set_rules($this->msmemberships_model->getRules("ADD", 0));
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
			"fst_member_no" => $this->input->post("fst_member_no"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_member_group_id" => $this->input->post("fin_member_group_id"),
			"fst_name_on_card" =>$this->input->post("fst_name_on_card"),
			"fdt_expiry_date" =>dBDateFormat($this->input->post("fdt_expiry_date")),
			"fdc_member_discount_percent" =>$this->input->post("fdc_member_discount_percent"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msmemberships_model->insert($data);
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
		
	public function ajx_edit_save(){
		$this->load->model('msmemberships_model');
		$fin_rec_id = $this->input->post("fin_rec_id");
		$data = $this->msmemberships_model->getDataById($fin_rec_id);
		$msmemberships = $data["ms_memberships"];
		if (!$msmemberships) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_rec_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msmemberships_model->getRules("EDIT", $fin_rec_id));
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
			"fin_rec_id" => $fin_rec_id,
			"fst_member_no" => $this->input->post("fst_member_no"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_member_group_id" => $this->input->post("fin_member_group_id"),
			"fst_name_on_card" =>$this->input->post("fst_name_on_card"),
			"fdt_expiry_date" =>dBDateFormat($this->input->post("fdt_expiry_date")),
			"fdc_member_discount_percent" =>$this->input->post("fdc_member_discount_percent"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->msmemberships_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fin_rec_id;
		$this->json_output();
	}
    
  	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("(select a.*,b.fst_relation_name,c.fst_member_group_name from msmemberships a inner join msrelations b on a.fin_relation_id = b.fin_relation_id
		left join msmembergroups c on a.fin_member_group_id = c.fin_member_group_id) a");

		$selectFields = "a.fin_rec_id,a.fst_member_no,a.fst_relation_name,a.fst_member_group_name,a.fst_name_on_card,a.fdt_expiry_date,a.fdc_member_discount_percent,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_rec_id","fst_name_on_card"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_rec_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_rec_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
  	}
    
  	public function fetch_data($fin_rec_id){
		$this->load->model("msmemberships_model");
		$data = $this->msmemberships_model->getDataById($fin_rec_id);
		
		$this->json_output($data);
  	}
    
  	public function get_relations(){
		$term = $this->input->get("term");
		$ssql = "select fin_relation_id, fst_relation_name from msrelations where fst_relation_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->json_output($rs);
	}

	public function get_MemberGroup(){
		$term = $this->input->get("term");
		$ssql = "select fin_member_group_id, fst_member_group_name from msmembergroups where fst_member_group_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->json_output($rs);
	}

	public function delete($id){
		$this->load->model("msmemberships_model");
		$this->db->trans_start();
        $this->msmemberships_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}

	public function report_memberships(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        //$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("msmemberships_model");
		$listMemberships = $this->msmemberships_model->get_Memberships();
        $data = [
			"datas" => $listMemberships
		];
			
        $this->pdf->load_view('report/memberships_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relation extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('msrelations_model');
	}

	public function index(){
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Master Relations";
		$this->list['list_name'] = "Master Relations List";
		$this->list['addnew_ajax_url'] = site_url() . 'pr/relation/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'pr/relation/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'pr/relation/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'pr/relation/edit/';
		$this->list['arrSearch'] = [
			'fin_relation_id' => 'Relations ID',
			'fst_relation_name' => 'Relations Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Master Relations', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Relation ID', 'width' => '15%', 'data' => 'fin_relation_id'],
			['title' => 'Relation Name', 'width' => '20%', 'data' => 'fst_relation_name'],
			['title' => 'Relation Type', 'width' => '15%', 'data' => 'fst_relation_type',
				'render' => "function (data,type,row){
					var fst_relation_type = data.split(\",\");
					var nama = \"\";
					fst_relation_type.forEach(function(value, index, array){ 				
						if(value == 1){					
							nama = nama + \",\" + \"Customer\";				
						}else if(value == 2){					
							nama = nama + \",\" + \"Supplier/Vendor\";				
						}else if(value == 3){					
							nama = nama + \",\" + \"Ekspedisi\"	;			
						}
					});
					return nama.substring(1);
				}"
			],
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

	private function openForm($mode = "ADD", $fin_relation_id = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Relations" : "Update Master Relations";
		$data["fin_relation_id"] = $fin_relation_id;

		$page_content = $this->parser->parse('pages/pr/msrelations/form', $data, true);
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

	public function edit($fin_relation_id){
		$this->openForm("EDIT", $fin_relation_id);
	}

	public function ajx_add_save(){
		$this->load->model('msrelations_model');
		$this->form_validation->set_rules($this->msrelations_model->getRules("ADD", 0));
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
			"fin_relation_group_id" => $this->input->post("fin_relation_group_id"),
			"fst_relation_type" => implode(",",$this->input->post("fst_relation_type")),
			"fin_parent_id" => $this->input->post("fin_parent_id"),
			"fst_business_type" => $this->input->post("fst_business_type"),
			"fst_relation_name" => $this->input->post("fst_relation_name"),
			"fst_gender" => $this->input->post("fst_gender"),
			"fdt_birth_date" => dBDateFormat($this->input->post("fdt_birth_date")),
			"fst_birth_place" => $this->input->post("fst_birth_place"),
			"fst_nik" => $this->input->post("fst_nik"),
			"fst_address" => $this->input->post("fst_address"),
			"fst_shipping_address" => $this->input->post("fst_shipping_address"),
			"fst_phone" => $this->input->post("fst_phone"),
			"fst_fax" => $this->input->post("fst_fax"),
			"fst_postal_code" => $this->input->post("fst_postal_code"),
			"fin_country_id" => $this->input->post("fin_country_id"),
			"fst_area_code" => $this->input->post("fst_kode"),
			"fin_cust_pricing_group_id" => $this->input->post("fin_cust_pricing_group_id"),
			"fst_npwp" => $this->input->post("fst_npwp"),
			"fst_relation_notes" => $this->input->post("fst_relation_notes"),
			"fdc_credit_limit" => $this->input->post("fdc_credit_limit"),
			"fin_sales_area_id" => $this->input->post("fin_sales_area_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_terms_payment" => $this->input->post("fin_terms_payment"),
			"fin_top_komisi" => $this->input->post("fin_top_komisi"),
			"fin_top_plus_komisi" => $this->input->post("fin_top_plus_komisi"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$insertId = $this->msrelations_model->insert($data);
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
		$this->load->model('msrelations_model');
		$fin_relation_id = $this->input->post("fin_relation_id");
		$data = $this->msrelations_model->getDataById($fin_relation_id);
		$msrelations = $data["ms_relations"];
		if (!$msrelations) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_relation_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msrelations_model->getRules("EDIT", $fin_relation_id));
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
			"fin_relation_id" => $fin_relation_id,
			"fin_relation_group_id" => $this->input->post("fin_relation_group_id"),
			"fst_relation_type" => implode(",",$this->input->post("fst_relation_type")),
			"fin_parent_id" => $this->input->post("fin_parent_id"),
			"fst_business_type" => $this->input->post("fst_business_type"),
			"fst_relation_name" => $this->input->post("fst_relation_name"),
			"fst_gender" => $this->input->post("fst_gender"),
			"fdt_birth_date" => dBDateFormat($this->input->post("fdt_birth_date")),
			"fst_birth_place" => $this->input->post("fst_birth_place"),
			"fst_nik" => $this->input->post("fst_nik"),
			"fst_address" => $this->input->post("fst_address"),
			"fst_shipping_address" => $this->input->post("fst_shipping_address"),
			"fst_phone" => $this->input->post("fst_phone"),
			"fst_fax" => $this->input->post("fst_fax"),
			"fst_postal_code" => $this->input->post("fst_postal_code"),
			"fin_country_id" => $this->input->post("fin_country_id"),
			"fst_area_code" => $this->input->post("fst_kode"),
			"fin_cust_pricing_group_id" => $this->input->post("fin_cust_pricing_group_id"),
			"fst_npwp" => $this->input->post("fst_npwp"),
			"fst_relation_notes" => $this->input->post("fst_relation_notes"),
			"fdc_credit_limit" => $this->input->post("fdc_credit_limit"),
			"fin_sales_area_id" => $this->input->post("fin_sales_area_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_terms_payment" => $this->input->post("fin_terms_payment"),
			"fin_top_komisi" => $this->input->post("fin_top_komisi"),
			"fin_top_plus_komisi" => $this->input->post("fin_top_plus_komisi"),
			"fst_active" => 'A'
		];

		$this->db->trans_start();
		$this->msrelations_model->update($data);
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
		$this->ajxResp["data"]["insert_id"] = $fin_relation_id;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("msrelations");

		$selectFields = "fin_relation_id,fin_relation_group_id,fst_relation_type,fst_relation_name,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_relation_id","fst_relation_name"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		
		$arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_relation_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_relation_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($fin_relation_id){
		$this->load->model("msrelations_model");
		$data = $this->msrelations_model->getDataById($fin_relation_id);
	
		$this->json_output($data);
	}

	public function get_parent_id(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_relation_id, fst_relation_name FROM msrelations WHERE fst_relation_type = 1" ;
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_relation_group(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_relation_group_id, fst_relation_group_name from msrelationgroups where fst_relation_group_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_countries(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_country_id, fst_country_name from mscountries where fst_country_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_provinces($fin_country_id){
		$term = $this->input->get("term");
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 0 ";
		$qr = $this->db->query($ssql,['%'.$term.'%',$fin_country_id]);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_districts($fst_kode){
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 1 and fst_kode like ? ";
		$qr = $this->db->query($ssql,[$fst_kode .'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_subdistricts($fst_kode){
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 2 and fst_kode like ? ";
		$qr = $this->db->query($ssql,[$fst_kode .'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_village($fst_kode){
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 3 and fst_kode like ? ";
		$qr = $this->db->query($ssql,[$fst_kode .'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_cust_pricing_group(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_cust_pricing_group_id, fst_cust_pricing_group_name from mscustpricinggroups where fst_cust_pricing_group_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_relation_print_out_note(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_note_id, fst_notes from msrelationprintoutnotes where fst_notes like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_sales_area(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_sales_area_id, fst_name from mssalesarea where fst_name like?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_sales_id(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_user_id, fst_username from users where fin_department_id = 1 ";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_warehouse(){
		$term = $this->input->get("term");
		$ssql = "SELECT fin_warehouse_id, fst_warehouse_name from mswarehouse where fst_warehouse_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function delete($id){
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->load->model("msrelations_model");

		$this->msrelations_model->delete($id);
		$this->ajxResp["status"] = "DELETED";
		$this->ajxResp["message"] = "File deleted successfully";
		$this->json_output();
	}

	public function report_relations(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("msrelations_model");
		$listRelations = $this->msrelations_model->get_Relations();
        $data = [
			"datas" => $listRelations
		];
			
        $this->pdf->load_view('report/relations_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
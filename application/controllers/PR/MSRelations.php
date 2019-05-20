<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MSRelations extends MY_Controller{

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
		$this->list['addnew_ajax_url'] = site_url() . 'pr/msrelations/add';
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'pr/msrelations/fetch_list_data';
		$this->list['delete_ajax_url'] = site_url() . 'pr/msrelations/delete/';
		$this->list['edit_ajax_url'] = site_url() . 'pr/msrelations/edit/';
		$this->list['arrSearch'] = [
			'RelationId' => 'Relations ID',
			'RelationName' => 'Relations Name'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Master Relations', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['columns'] = [
			['title' => 'Relation ID', 'width' => '15%', 'data' => 'RelationId'],
			['title' => 'Relation Name', 'width' => '20%', 'data' => 'RelationName'],
			['title' => 'Relation Type', 'width' => '15%', 'data' => 'RelationType',
				'render' => "function (data,type,row){
					var relationType = data.split(\",\");
					var nama = \"\";
					relationType.forEach(function(value, index, array){ 				
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

	private function openForm($mode = "ADD", $RelationId = 0){
		$this->load->library("menus");

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Master Relations" : "Update Master Relations";
		$data["RelationId"] = $RelationId;

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

	public function Edit($RelationId){
		$this->openForm("EDIT", $RelationId);
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
			"RelationGroupId" => $this->input->post("RelationGroupId"),
			"RelationType" => implode(",",$this->input->post("RelationType")),
			"BusinessType" => $this->input->post("BusinessType"),
			"RelationName" => $this->input->post("RelationName"),
			"Gender" => $this->input->post("Gender"),
			"BirthDate" => dBDateFormat($this->input->post("BirthDate")),
			"BirthPlace" => $this->input->post("BirthPlace"),
			"Address" => $this->input->post("Address"),
			"Phone" => $this->input->post("Phone"),
			"Fax" => $this->input->post("Fax"),
			"PostalCode" => $this->input->post("PostalCode"),
			"CountryId" => $this->input->post("CountryId"),
			"ProvinceId" => $this->input->post("ProvinceId"),
			"DistrictId" => $this->input->post("DistrictId"),
			"SubDistrictId" => $this->input->post("SubDistrictId"),
			"CustPricingGroupid" => $this->input->post("CustPricingGroupid"),
			"NPWP" => $this->input->post("NPWP"),
			"RelationNotes" => $this->input->post("RelationNotes"),
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
		$RelationId = $this->input->post("RelationId");
		$data = $this->msrelations_model->getDataById($RelationId);
		$msrelations = $data["ms_relations"];
		if (!$msrelations) {
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $RelationId Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

		$this->form_validation->set_rules($this->msrelations_model->getRules("EDIT", $RelationId));
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
			"RelationId" => $RelationId,
			"RelationGroupId" => $this->input->post("RelationGroupId"),
			"RelationType" => implode(",",$this->input->post("RelationType")),
			"BusinessType" => $this->input->post("BusinessType"),
			"RelationName" => $this->input->post("RelationName"),
			"Gender" => $this->input->post("Gender"),
			"BirthDate" => dBDateFormat($this->input->post("BirthDate")),
			"BirthPlace" => $this->input->post("BirthPlace"),
			"Address" => $this->input->post("Address"),
			"Phone" => $this->input->post("Phone"),
			"Fax" => $this->input->post("Fax"),
			"PostalCode" => $this->input->post("PostalCode"),
			"CountryId" => $this->input->post("CountryId"),
			"ProvinceId" => $this->input->post("ProvinceId"),
			"DistrictId" => $this->input->post("DistrictId"),
			"SubDistrictId" => $this->input->post("SubDistrictId"),
			"CustPricingGroupid" => $this->input->post("CustPricingGroupid"),
			"NPWP" => $this->input->post("NPWP"),
			"RelationNotes" => $this->input->post("RelationNotes"),
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
		$this->ajxResp["data"]["insert_id"] = $RelationId;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("msrelations");

		$selectFields = "RelationId,RelationGroupId,RelationType,RelationName,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["RelationId","RelationName"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
		
		$arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["RelationId"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["RelationId"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function fetch_data($RelationId){
		$this->load->model("msrelations_model");
		$data = $this->msrelations_model->getDataById($RelationId);
	
		$this->json_output($data);
	}

	public function get_msrelationgroups(){
		$term = $this->input->get("term");
		$ssql = "select RelationGroupId, RelationGroupName from msrelationgroups where RelationGroupName like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_mscountries(){
		$term = $this->input->get("term");
		$ssql = "select CountryId, CountryName from mscountries where CountryName like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_msprovinces($countryId){
		$term = $this->input->get("term");
		$ssql = "select ProvinceId, ProvinceName from msprovinces where ProvinceName like ? and CountryId = ? order by ProvinceName";
		$qr = $this->db->query($ssql,['%'.$term.'%',$countryId]);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_msdistricts($provinceId){
		$term = $this->input->get("term");
		$ssql = "select DistrictId, DistrictName from msdistricts where DistrictName like ? and ProvinceId = ? order by DistrictName";
		$qr = $this->db->query($ssql,['%'.$term.'%',$provinceId]);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_mssubdistricts($districtId){
		$term = $this->input->get("term");
		$ssql = "select SubDistrictId, SubDistrictName from mssubdistricts where SubDistrictName like ? and DistrictId = ? order by SubDistrictName";
		$qr = $this->db->query($ssql,['%'.$term.'%',$districtId]);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_mscustpricinggroups(){
		$term = $this->input->get("term");
		$ssql = "select CustPricingGroupId, CustPricingGroupName from mscustpricinggroups where CustPricingGroupName like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_msrelationprintoutnotes(){
		$term = $this->input->get("term");
		$ssql = "select NoteId, Notes from msrelationprintoutnotes where Notes like ?";
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
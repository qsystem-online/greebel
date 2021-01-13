<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usersgroup extends MY_Controller {
	public $menuName="user_group"; 
    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
    }

    public function index(){
		parent::index();
        $this->lizt();
    }

    public function lizt(){
		parent::index();
        $this->load->library('menus');
        $this->list['page_name']="Master Groups";
        $this->list['list_name']="Users Group List";
        $this->list['addnew_ajax_url']=site_url().'system/usersgroup/add';
        $this->list['pKey']="id";
		$this->list['fetch_list_data_ajax_url']=site_url().'system/usersgroup/fetch_list_data';
        $this->list['delete_ajax_url']=site_url().'system/usersgroup/delete/';
        $this->list['edit_ajax_url']=site_url().'system/usersgroup/edit/';
        $this->list['arrSearch']=[
            'fin_group_id' => 'Group ID',
            'fst_group_name' => 'Group Name'
		];

		$this->list['breadcrumbs']=[
			['title'=>'Home','link'=>'#','icon'=>"<i class='fa fa-dashboard'></i>"],
			['title'=>'Master Groups','link'=>'#','icon'=>''],
			['title'=>'List','link'=> NULL ,'icon'=>''],
		];
		$this->list['columns']=[
			['title' => 'Group ID', 'width'=>'10%', 'data'=>'fin_group_id'],
            ['title' => 'Group Name', 'width'=>'25%', 'data'=>'fst_group_name'],
            ['title' => 'Level', 'width' =>'15%', 'data'=>'fst_level_name'],
			['title' => 'Action', 'width'=>'10%', 'data'=>'action','sortable'=>false, 'className'=>'dt-body-center text-center']
		];
        $main_header = $this->parser->parse('inc/main_header',[],true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);
        $page_content = $this->parser->parse('template/standardList',$this->list,true);
        $main_footer = $this->parser->parse('inc/main_footer',[],true);
        $control_sidebar=null;
        $this->data['ACCESS_RIGHT']="A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR']= $main_sidebar;
        $this->data['PAGE_CONTENT']= $page_content;
        $this->data['MAIN_FOOTER']= $main_footer;        
        $this->parser->parse('template/main',$this->data);
	}

	private function openForm($mode = "ADD",$fin_group_id = 0){
		$this->load->library("menus");

		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add User group" : "Update User group";
		$data["fin_group_id"] = $fin_group_id;

		$page_content = $this->parser->parse('pages/system/usersgroup/form',$data,true);
		$main_footer = $this->parser->parse('inc/main_footer',[],true);
		
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main',$this->data);
	}

	public function add(){
		parent::add();
		$this->openForm("ADD",0);
	}

	public function edit($fin_group_id){
		parent::edit($fin_group_id);
		$this->openForm("EDIT",$fin_group_id);
	}

	public function ajx_add_save(){
		parent::ajx_add_save();
		$this->load->model('Usersgroup_model');
		$this->form_validation->set_rules($this->Usersgroup_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE){
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$data = [
            "fst_group_name"=>$this->input->post("fst_group_name"),
            "fin_level"=>$this->input->post("fin_level"),
			"fst_active"=>'A'
		];

		$this->db->trans_start();
		$insertId = $this->Usersgroup_model->insert($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
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
		parent::ajx_edit_save();
		$this->load->model('Usersgroup_model');		
		$fin_group_id = $this->input->post("fin_group_id");
		$data = $this->Usersgroup_model->getDataById($fin_group_id);
		$user_groups = $data["usersgroup"];
		if (!$user_groups){
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_group_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}
		
		$this->form_validation->set_rules($this->Usersgroup_model->getRules("EDIT",$fin_group_id));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE){
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}

		$data = [
			"fin_group_id"=>$fin_group_id,
            "fst_group_name"=>$this->input->post("fst_group_name"),
            "fin_level"=>$this->input->post("fin_level"),
			"fst_active"=>'A'
		];

		$this->db->trans_start();

		$this->Usersgroup_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
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
		$this->ajxResp["data"]["insert_id"] = $fin_group_id;
		$this->json_output();
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("usersgroup");

		$selectFields = "fin_group_id,fst_group_name,fin_level,'action' as action";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_flow_control_schema_id","fst_name"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();

		$arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			switch($data["fin_level"]){
				case 0:
					$level_name = "Top Management";
					break;
				case 1:
					$level_name = "Upper Management";
					break;
				case 2:
					$level_name = "Middle Management";
					break;
				case 3:
					$level_name = "Supervisors";
					break;
				case 4:
					$level_name = "Line Workers";
					break;
				case 5:
					$level_name = "Public";
					break;
			}
			$data["fst_level_name"] = $level_name;
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='".$data["fin_group_id"]."'><i class='fa fa-pencil'></i></a>
				</div>";

			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}
	
	public function fetch_data($fin_group_id){
		$this->load->model("Usersgroup_model");
		$data = $this->Usersgroup_model->getDataById($fin_group_id);

		//$this->load->library("datatables");		
		$this->json_output($data);
	}

	public function delete($id){
		parent::delete($id);
		$this->load->model("usersgroup_model");
		$this->db->trans_start();
        $this->usersgroup_model->delete($id);
        $this->db->trans_complete();

        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = lang("Data dihapus !");
		//$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
	}
	
	public function report_master_groups(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        $this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("Usersgroup_model");
		$listMasterGroup = $this->Usersgroup_model->get_master_groups();
        $data = [
			"datas" => $listMasterGroup
		];
			
        $this->pdf->load_view('report/master_groups_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
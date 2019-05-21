<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct(){
        parent:: __construct();
        $this->load->library('form_validation');
    }

    public function index(){
        $this->lizt();
    }

    public function lizt(){
        $this->load->library('menus');
        $this->list['page_name']="User";
        $this->list['list_name']="User List";
        $this->list['addnew_ajax_url']=site_url().'user/add';
        $this->list['pKey']="id";
		$this->list['fetch_list_data_ajax_url']=site_url().'user/fetch_list_data';
        $this->list['delete_ajax_url']=site_url().'user/delete/';
        $this->list['edit_ajax_url']=site_url().'user/edit/';
        $this->list['arrSearch']=[
            'a.fin_user_id' => 'User ID',
            'a.fst_username' => 'User Name'
		];

        $this->list['breadcrumbs']=[
			['title'=>'Home','link'=>'#','icon'=>"<i class='fa fa-dashboard'></i>"],
			['title'=>'User','link'=>'#','icon'=>''],
			['title'=>'List','link'=> NULL ,'icon'=>''],
		];

		$this->list['columns']=[
			['title' => 'User ID', 'width'=>'10%', 'data'=>'fin_user_id'],
			['title' => 'Full Name', 'width'=>'25%', 'data'=>'fst_fullname'],
			['title' => 'Gender', 'width' =>'10%', 'data'=>'fst_gender'],
			['title' => 'Birthdate', 'width' =>'15%', 'data'=>'fdt_birthdate'],
			['title' => 'Birthplace', 'width' =>'15%', 'data'=>'fst_birthplace'],
			['title' => 'Action', 'width'=>'10%', 'data'=>'action','sortable'=>false, 'className'=>'dt-center']
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

    public function openForm($mode="ADD",$fin_user_id=0){
        $this->load->library("menus");
		//$this->load->model("groups_model");

		if($this->input->post("submit") != "" ){
			$this->add_save();
		}

        $main_header = $this->parser->parse('inc/main_header',[],true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar',[],true);

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add User" : "Update User";
		$data["fin_user_id"] = $fin_user_id;

		$page_content = $this->parser->parse('pages/user/form',$data,true);
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
        $this->openForm("ADD",0);
    }

    public function Edit($fin_user_id){
        $this->openForm("EDIT",$fin_user_id);
    }

    public function ajx_add_save(){
        $this->load->model('users_model');
        $this->form_validation->set_rules($this->users_model->getRules("ADD",0));
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
			"fst_username"=>$this->input->post("fst_username"),
			"fst_password"=> md5("inipassword"),
			"fst_fullname"=>$this->input->post("fst_fullname"),
			"fdt_birthdate"=>dBDateFormat($this->input->post("fdt_birthdate")),
			"fst_gender"=>$this->input->post("fst_gender"),
			"fst_active"=>'A',
			"fst_birthplace"=>$this->input->post("fst_birthplace"),
			"fst_address"=>$this->input->post("fst_address"),
			"fst_email"=>$this->input->post("fst_email"),
			"fst_phone"=>$this->input->post("fst_phone"),
			"fin_department_id"=>$this->input->post("fin_department_id"),
			"fbl_admin"=>$this->input->post("fbl_admin")
		];

        $this->db->trans_start();
		$insertId = $this->users_model->insert($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        //Save File
		if(!empty($_FILES['fst_avatar']['tmp_name'])) {
			$config['upload_path']          = './assets/app/users/avatar';
			$config['file_name']			= 'avatar_'. $insertId . '.jpg' ;
			$config['overwrite']			= TRUE;
			$config['file_ext_tolower']		= TRUE;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0; //kilobyte
			$config['max_width']            = 0; //1024; //pixel
			$config['max_height']           = 0; //768; //pixel

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('fst_avatar')){			
				$this->ajxResp["status"] = "IMAGES_FAILED";
				$this->ajxResp["message"] = "Failed to upload images, " . $this->upload->display_errors();
				$this->ajxResp["data"] = $this->upload->display_errors();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}else{
				//$data = array('upload_data' => $this->upload->data());			
			}
			$this->ajxResp["data"]["data_image"] = $this->upload->data();
		}

        $this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $insertId;
		$this->json_output();
    }

    public function ajx_edit_save(){
        $this->load->model('users_model');
        $fin_user_id = $this->input->post('fin_user_id');
        $data = $this->users_model->getDataById($fin_user_id);
		$user = $data["user"];
		if (!$user){
			$this->ajxResp["status"] = "DATA_NOT_FOUND";
			$this->ajxResp["message"] = "Data id $fin_user_id Not Found ";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}

        $this->form_validation->set_rules($this->users_model->getRules("EDIT",$fin_user_id));
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
			"fin_user_id"=>$fin_user_id,
			"fst_username"=>$this->input->post("fst_username"),
			"fst_password"=> md5("defaultpassword"),//$this->input->post("fst_password"),
			"fst_fullname"=>$this->input->post("fst_fullname"),
			"fdt_birthdate"=>dBDateFormat($this->input->post("fdt_birthdate")),
			"fst_gender"=>$this->input->post("fst_gender"),
			"fst_active"=>'A',
			"fst_birthplace"=>$this->input->post("fst_birthplace"),
			"fst_address"=>$this->input->post("fst_address"),
			"fst_email"=>$this->input->post("fst_email"),
			"fst_phone"=>$this->input->post("fst_phone"),
			"fin_department_id"=>$this->input->post("fin_department_id"),
			"fbl_admin"=>$this->input->post("fbl_admin")
		];

		$this->db->trans_start();

		$this->users_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

		//Save File
		if(!empty($_FILES['fst_avatar']['tmp_name'])) {
			$config['upload_path']          = './assets/app/users/avatar';
			$config['file_name']			= 'avatar_'. $fin_id . '.jpg' ;
			$config['overwrite']			= TRUE;
			$config['file_ext_tolower']		= TRUE;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0; //kilobyte
			$config['max_width']            = 0; //1024; //pixel
			$config['max_height']           = 0; //768; //pixel

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('fst_avatar')){			
				$this->ajxResp["status"] = "IMAGES_FAILED";
				$this->ajxResp["message"] = "Failed to upload images, " . $this->upload->display_errors();
				$this->ajxResp["data"] = $this->upload->display_errors();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			}else{
				//$data = array('upload_data' => $this->upload->data());			
			}
				$this->ajxResp["data"]["data_image"] = $this->upload->data();
		}

		$this->db->trans_complete();

		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Data Saved !";
		$this->ajxResp["data"]["insert_id"] = $fin_user_id;
		$this->json_output();
    }

    public function remove_add_save(){
        $this->load->model('users_model');
		$data=[
			'fst_fullname'=>$this->input->get("fst_fullname"),
			'fdt_insert_datetime'=>'sekarang'
		];
		if ($this->db->insert('users', $data)) {
			echo "insert success";
		}else{
			$error = $this->db->error();
			print_r($error);
		}
		die();

		echo "Table Name :" . $this->users_model->getTableName();
		print_r($this->users_model->getRules());
			
		$this->form_validation->set_rules($this->users_model->rules);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE){
			echo form_error();
			//$this->add();
		}else{
			//$this->load->view('formsuccess');
			echo "Success";
		}

        //print_r($_POST);
		$config['allowed_types'] = 'gif|jpg|png'; //Images extensions accepted
		$config['max_size']    = '2048'; 
		$config['max_width']  = '1024'; 
		$config['max_height']  = '768'; 
		$config['overwrite'] = TRUE; 

		$this->load->library('upload',$config);
		$upload_data = $this->upload->data("fst_avatar");

		print_r($upload_data);

		print_r($_FILES);
    }

    public function fetch_list_data(){
        $this->load->library("datatables");
		$this->datatables->setTableName("users");
			
		$selectFields = "fin_user_id,fst_fullname,fst_gender,fdt_birthdate,fst_birthplace,'action' as action";
		$this->datatables->setSelectFields($selectFields);
	
		$searchFields = ["fst_fullname", "fst_birthplace"];
		$this->datatables->setSearchFields($searchFields);
	
		// Format Data
		$datasources = $this->datatables->getData();		
		$arrData = $datasources["data"];		
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$birthdate = strtotime($data["fdt_birthdate"]);			
			$data["fdt_insert_datetime"] = dBDateFormat("fdt_birthdate");
	
			//action
			$data["action"]	= "<div style='font-size:16px'>
				<a class='btn-edit' href='#' data-id='".$data["fin_user_id"]."'><i class='fa fa-pencil'></i></a>
				<a class='btn-delete' href='#' data-id='".$data["fin_user_id"]."' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
			</div>";
	
			$arrDataFormated[] = $data;
			}

		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }

    public function fetch_data($fin_user_id){
        $this->load->model('users_model');
        $data = $this->users_model->getDataById($fin_user_id);
		
		$this->json_output($data);
    }

    public function get_department(){
        $term = $this->input->get("term");
		$ssql = "select fin_department_id, fst_department_name from departments";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
			
		$this->json_output($rs);
    }

    public function delete($id){
        if(!$this->aauth->is_permit("")){
		    $this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}
			
		$this->load->model("users_model");
			
		$this->users_model->delete($id);
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->json_output();
    }

    public function getAllList(){
		$this->load->model('users_model');
        $result = $this->users_model->getAllList();
		$this->ajxResp["data"] = $result;
		$this->json_output();	
	}
	
	public function report_users(){
        $this->load->library('pdf');
        //$customPaper = array(0,0,381.89,595.28);
        //$this->pdf->setPaper($customPaper, 'landscape');
        //$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->setPaper('A4', 'landscape');
		
		$this->load->model("users_model");
		$listUser = $this->users_model->get_Users();
        $data = [
			"datas" => $listUser
		];
			
        $this->pdf->load_view('report/users_pdf', $data);
        $this->Cell(30,10,'Percobaan Header Dan Footer With Page Number',0,0,'C');
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
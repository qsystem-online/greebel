<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_out extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->model("trfamutasiout_model");		
		$this->load->model("mswarehouse_model");
        

	}

	public function index(){

		$this->load->library('menus');
		$this->list['page_name'] = "Fixed Asset Group";
		$this->list['list_name'] = "Fixed Asset Group List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/fixed_asset/groups/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/fixed_asset/groups/fetch_list_data';
		$this->list['arrSearch'] = [
            'fst_fa_group_code' => 'Kode',
            'fst_fa_group_name' => 'Nama'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Gudang', 'link' => '#', 'icon' => ''],
			['title' => 'Mutasi Antar Gudang', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_fa_group_id'],
			['title' => 'Kode', 'width' => '60px', 'data' => 'fst_fa_group_code'],
            ['title' => 'Nama', 'width' => '50px', 'data' => 'fst_fa_group_name'],
            ['title' => 'Metode', 'width' => '50px', 'data' => 'fst_method'],
            ['title' => 'Umur (Bulan)', 'width' => '50px', 'data' => 'fin_life_time_month'],            
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/fixed_asset/groups/edit/' + row.fin_fa_group_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('template/listjs', [], true);

		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$this->list['mdlEditForm'] = $edit_modal;

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = $this->parser->parse('template/standardList_v2_0_0', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['PAGE_CONTENT'] = $page_content;
		$this->data['MAIN_FOOTER'] = $main_footer;
		$this->parser->parse('template/main', $this->data);
	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("(
				select * from msfagroups where fst_active != 'D'
			) a");

		$selectFields = "a.*";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {        
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
		
	}

	public function add(){
		$this->openForm("ADD", 0);
	}
	
	public function edit($finId){
		$this->openForm("EDIT", $finId);

	}


	private function openForm($mode = "ADD", $finId = 0){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		
		$this->load->model("users_model");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Mutasi Fixed Asset") : lang("Update Mutasi Fixed Asset");
		$data["fin_fa_mutasiout_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				

		$fstFAMutasiOutNo = $this->trfamutasiout_model->generateTransactionNo();
		$data["fst_fa_mutasiout_no"] = $fstFAMutasiOutNo;	
		$page_content = $this->parser->parse('pages/tr/fixed_asset/mutasi_out/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

	public function ajx_add_save(){	
		//$this->load->model("msitems_model");		
		
		try{			
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details =$dataPrepared["details"];

			unset($dataH["fin_fa_mutasiout_id"]);
			$dataH["fst_fa_mutasiout_no"] = $this->trfamutasiout_model->generateTransactionNo();

			$resp = dateIsLock($dataH["fdt_fa_mutasiout_datetime"]);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
						
			$this->validateData($dataH,$details);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		

		try{
			$this->db->trans_start();
			$insertId = $this->trfamutasiout_model->insert($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_fa_mutasiout_id"] = $insertId;
				$this->trfamutasioutitems_model->insert($dataD);
			}
			
			$this->trfamutasiout_model->posting($insertId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $insertId;
			$this->json_output();
		}catch(CustomException $e){
			$this->db->trans_rollback();			
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}

	}

	public function ajx_edit_save(){
        $finFAGroupId = $this->input->post("fin_fa_group_id");
		try{
            $dataHOld = $this->msfagroups_model->getDataById($finFAGroupId);
            if ($dataHOld == null){
                show_404();
            }

            $this->msfagroups_model->isEditable($finFAGroupId);
                        
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();
			
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_fa_group_id"] = $finFAGroupId;
						
			$this->validateData($dataH);			
			$this->msfagroups_model->update($dataH);
			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_fa_group_id"];
			$this->json_output();			
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}		

	}

	private function prepareData(){
		$dataH = [
			"fin_fa_mutasiout_id"=>$this->input->post("fin_fa_mutasiout_id"),
			"fst_fa_mutasiout_no"=>$this->input->post("fst_fa_mutasiout_no"),
			"fdt_fa_mutasiout_datetime"=>dBDateTimeFormat($this->input->post("fdt_fa_mutasiout_datetime")),
			"fin_from_warehouse_id"=>$this->input->post("fin_from_warehouse_id"),
			"fin_to_warehouse_id"=>$this->input->post("fin_to_warehouse_id"),
            "fst_notes"=>$this->input->post("fst_notes"),
            "fst_active"=>'A',			
		];

		$dataDetails = $this->input->post("details");
		$dataDetails = json_decode($dataDetails);		
		$details = [];
		foreach($dataDetails as $detail){
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_fa_profile_rec_id"=>$detail->fin_fa_profile_rec_id,
				"fst_notes"=>$detail->fst_notes,
			];

			$details[]=(object) $tmp;
		}
		return[
			"dataH"=>$dataH,
			"details"=>$details,
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trfamutasiout_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		
	}

	public function fetch_data($finId){
		$data = $this->msfagroups_model->getDataById($finId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finMagId){

		try{
			
			$dataHOld = $this->trmag_model->getDataHeaderById($finMagId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid MAG ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_mag_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
									
			$resp = $this->trmag_model->isEditable($finMagId,$dataHOld);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}			
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();
			
			$this->trmag_model->unposting($finMagId);			
			$resp = $this->trmag_model->delete($finMagId,true,null);	

			$this->db->trans_complete();	

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
			$this->json_output();

		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}
	}		

	public function print_voucher($finMagId){
		$data = $this->trmag_model->getDataVoucher($finMagId);

		$data["title"]= "Mutasi Antar Gudang";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/gudang/mutasi/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;	
		$mpdf->WriteHTML($data);
		$mpdf->Output();

	}


	public function ajxListFixedAsset(){

		$finWarehouseId =$this->input->get("fin_warehouse_id");

		$ssql ="SELECT a.fin_rec_id,a.fst_fa_profile_code,a.fst_fa_profile_name FROM trfaprofilesitems a 
			INNER JOIN trfaprofiles b on a.fin_fa_profile_id = b.fin_fa_profile_id
			WHERE a.fbl_disposal = 0  AND b.fst_active ='A' AND b.fin_warehouse_id = ? AND 
			(a.fst_fa_profile_code like ? OR a.fst_fa_profile_name like ?)";
		$term ="%".$this->input->get("term") ."%";

		$qr = $this->db->query($ssql,[$finWarehouseId,$term,$term]);
		//var_dump($this->db->last_query());
		
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$qr->result()
		]);
	}
	

}    
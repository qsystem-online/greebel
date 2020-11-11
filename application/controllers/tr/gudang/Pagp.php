<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pagp extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("trmagconfirm_model");				
		$this->load->model("trmag_model");		
		$this->load->model("trmagitems_model");		
		$this->load->model("mswarehouse_model");
	}

	public function index(){

		$this->load->library('menus');
		$this->list['page_name'] = "Penerimaan Mutasi Antar Gudang";
		$this->list['list_name'] = "PAG List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/gudang/pagp/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/gudang/pagp/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_mag_no_confirm' => 'No PAG'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Gudang', 'link' => '#', 'icon' => ''],
			['title' => 'PAG Produksi', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_mag_confirm_id'],
			['title' => 'No. MAG', 'width' => '50px', 'data' => 'fst_mag_no'],
			['title' => 'No. PAG', 'width' => '50px', 'data' => 'fst_mag_confirm_no'],
			['title' => 'Tanggal', 'width' => '80px', 'data' => 'fdt_mag_confirm_datetime'],
			['title' => 'From', 'width' => '60px', 'data' => 'fin_from_warehouse_id',
				'render'=>'function(data,type,row){
					return row.fst_from_warehouse_name;
				}'
			],
			['title' => 'To', 'width' => '100px', 'data' => 'fin_to_warehouse_id',
				'render'=>'function(data,type,row){
					return row.fst_to_warehouse_name;
				}'
			],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/gudang/pagp/edit/' + row.fin_mag_confirm_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					//action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/gudang/mutasi/listjs', [], true);

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
				SELECT a.*,b.fst_mag_no,c.fst_warehouse_name as fst_from_warehouse_name,d.fst_warehouse_name as fst_to_warehouse_name 
				FROM trmagconfirm a
				INNER JOIN trmag b on a.fin_mag_id = b.fin_mag_id
				INNER JOIN mswarehouse c on b.fin_from_warehouse_id = c.fin_warehouse_id
				INNER JOIN mswarehouse d on b.fin_to_warehouse_id = d.fin_warehouse_id
				where a.fst_active != 'D' and b.fbl_mag_production = 1 
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
	
	public function edit($finMagConfirmId){
		$this->openForm("EDIT", $finMagConfirmId);
	}

	private function openForm($mode = "ADD", $finPagId = 0){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("PAG Produksi") : lang("Update PAG Produksi");
		$data["fin_mag_confirm_id"] = $finPagId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;
		
		
		if($mode == 'ADD'){
			$data["fst_mag_confirm_no"]=$this->trmagconfirm_model->GenerateNo(); 
		}else if($mode == 'EDIT'){
			$data["fst_mag_confirm_no"]="";	
		}        
		
		$page_content = $this->parser->parse('pages/tr/gudang/mag_produksi/form_confirm', $data, true);
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

		try{
			$fdt_mag_confirm_datetime = dBDateTimeFormat($this->input->post("fdt_mag_confirm_datetime"));
			$resp = dateIsLock($fdt_mag_confirm_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
			
			
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details = $dataPrepared["details"];
			//$this->validateData($dataH,$details);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}	
		
		try{
			$this->db->trans_start();

			$insertId = $this->trmagconfirm_model->insert($dataH);			

			//Update Detail
			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;
				$dataD["fst_serial_number_list_confirm"] = json_encode($dataD["fst_serial_number_list_confirm"]);
				$this->trmagitems_model->update($dataD);			
			}
			$this->trmagconfirm_model->posting($insertId);	

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
		$finMagConfirmId = $this->input->post("fin_mag_confirm_id");		
		try{			
			$dataHOld = $this->trmagconfirm_model->getDataHeaderById($finMagConfirmId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid PAG ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_mag_confirm_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
						
			$fdt_mag_confirm_datetime = dBDateTimeFormat($this->input->post("fdt_mag_confirm_datetime"));
			$resp = dateIsLock($fdt_mag_confirm_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}

			$resp = $this->trmagconfirm_model->isEditableProduction($finMagConfirmId);
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
			
			$this->trmagconfirm_model->unposting($finMagConfirmId);
			$this->trmagconfirm_model->deleteDetail($dataHOld->fin_mag_id);

			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$dataH["fin_mag_confirm_id"] = $finMagConfirmId;
			$dataH["fst_mag_confirm_no"] = $dataHOld->fst_mag_confirm_no;
						
			$details = $preparedData["details"];
			//$this->validateData($dataH,$details);

			
			$this->trmagconfirm_model->update($dataH);

			foreach ($details as $dataD) {		
				$dataD = (array) $dataD;
				$dataD["fst_serial_number_list_confirm"] = json_encode($dataD["fst_serial_number_list_confirm"]);
				$this->trmagitems_model->update($dataD);			
			}
			$this->trmagconfirm_model->posting($finMagConfirmId);
			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_mag_confirm_id"];
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
        $fdt_mag_confirm_datetime = dBDateTimeFormat($this->input->post("fdt_mag_confirm_datetime"));
        $finMAGId = $this->input->post("fin_mag_id");

        $fst_mag_confirm_no = $this->trmagconfirm_model->GenerateNoByMAG($finMAGId);
        
		$dataH = [
			"fin_mag_confirm_id"=>$this->input->post("fin_mag_confirm_id"),
			"fst_mag_confirm_no"=>$fst_mag_confirm_no,
			"fdt_mag_confirm_datetime"=>$fdt_mag_confirm_datetime,
			"fin_mag_id"=>$this->input->post("fin_mag_id"),
			"fst_memo"=>$this->input->post("fst_memo"),
			"fst_active"=>"A",
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),			
		];

        $ssql ="SELECT * FROM trmagitems where fin_mag_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finMAGId]);
        $rs = $qr->result();
        $details = [];

        for($i=0;$i<sizeof($rs);$i++){
            $detail = $rs[$i];
            $tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fdb_qty_confirm"=>$detail->fdb_qty,
				"fst_serial_number_list_confirm"=>$detail->fst_serial_number_list
            ];
            $details[]=(object) $tmp;
        }


        /*
		$dataDetails = $this->input->post("detail");
		$dataDetails = json_decode($dataDetails);
		
		

		foreach($dataDetails as $detail){
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fdb_qty_confirm"=>$detail->fdb_qty_confirm,
				"fst_serial_number_list_confirm"=>$detail->fst_serial_number_list_confirm
			];

			$details[]=(object) $tmp;
        }
        */

		return[
			"dataH"=>$dataH,
			"details"=>$details
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->load->model("msitems_model");
		$this->form_validation->set_rules($this->trmag_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}


		$this->form_validation->set_rules($this->trmagitems_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		
		foreach($details as $dataD){
			
			$this->form_validation->set_data((array) $dataD);
			if ($this->form_validation->run() == FALSE) {
				//print_r($this->form_validation->error_array());
				throw new CustomException("Error Validation Details",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
			}

			$itemInfo = $this->msitems_model->getSimpleDataById($dataD->fin_item_id);

			//Cek is item have batch number
			if($itemInfo->fbl_is_batch_number && $dataD->fst_batch_number == "" ){
				throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
			}

			//Cek is item have serial number
			if($itemInfo->fbl_is_serial_number){				
				//$arrSerial = json_decode($item->arr_serial);
				$arrSerial = $dataD->fst_serial_number_list;
				if($arrSerial == null){
					throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$dataD->fst_custom_item_name),3003,"FAILED",$dataD);
				}

				if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit) ){
					throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$dataD->fst_custom_item_name,$dataD->fdb_qty),3003,"FAILED",$dataD);
				}

			}

		}
		

		
	}

	public function fetch_data($finPagId){
		$data = $this->trmagconfirm_model->getDataById($finPagId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finPagId){

		try{
			
			$dataHOld = $this->trmagconfirm_model->getDataHeaderById($finPagId);
			if ($dataHOld == null){
				throw new CustomException(lang("Invalid MAG Confirm ID"),3003,"FAILED",null);
			}

			$resp = dateIsLock($dataHOld->fdt_mag_confirm_datetime);
			if ($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",null);
			}
									
			$resp = $this->trmagconfirm_model->isEditable($finPagId,$dataHOld);
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
			
			$this->trmagconfirm_model->unposting($finPagId);			
			$resp = $this->trmagconfirm_model->delete($finPagId,true,null);	

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

	public function ajxListMAG(){
		$term = $this->input->get("term");
		$ssql ="select a.fin_mag_id,a.fst_mag_no
			from trmag a 
			where fbl_closed = false and fbl_mag_production = 1 and fst_mag_no like ? ";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>$qr->result()
		]);
    }
    

	public function ajxMAGDetail($finMagId){
		$this->load->model("msitems_model");
		$term = $this->input->get("term");
		$ssql ="select a.fin_mag_id,a.fst_mag_no,a.fdt_mag_datetime,
			b.fst_warehouse_name as fst_from_warehouse_name,
            c.fst_warehouse_name as fst_to_warehouse_name,
            d.fst_wo_no 
			from trmag a 
			inner join mswarehouse b on a.fin_from_warehouse_id = b.fin_warehouse_id 
            inner join mswarehouse c on a.fin_to_warehouse_id = c.fin_warehouse_id 
            LEFT JOIN trwo d on a.fin_wo_id = d.fin_wo_id
			where a.fin_mag_id = ?";

		$qr = $this->db->query($ssql,[$finMagId]);
		$header = $qr->row();

		$ssql ="select a.*,b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number 
			from trmagitems a
			inner join msitems b on a.fin_item_id = b.fin_item_id  
			where a.fin_mag_id = ?";
		$qr = $this->db->query($ssql,[$finMagId]);
		$details = $qr->result();

		for($i=0;$i<sizeof($details);$i++){
			$detail = $details[$i];
			$detail->fdb_qty_in_basic = $detail->fdb_qty;			
			$basicUnit = $this->msitems_model->getBasicUnit($detail->fin_item_id);
			$detail->fst_basic_unit = $basicUnit;
			if ($detail->fbl_is_serial_number){
				$detail->fdb_qty_in_basic = $this->msitems_model->getQtyConvertUnit($detail->fin_item_id,$detail->fdb_qty,$detail->fst_unit,$basicUnit);
			}
			$details[$i] = $detail;			
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"data"=>[
				"header"=>$header,
				"details"=>$details,	
			]
		]);
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
	

}    
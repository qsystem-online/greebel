<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mps extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("trmps_model");
		$this->load->model("trmpsitems_model");
		
	}

	public function index(){

		$this->load->library('menus');
		$this->list['page_name'] = "Master Planning Schedule";
		$this->list['list_name'] = "Master Planning Schedule List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/mps/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/mps/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_mps_no' => 'No.',
			'fst_item_group_name'=>'Group'			
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'Planning', 'link' => '#', 'icon' => ''],
			['title' => 'MPS', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_mps_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_mps_no'],
            ['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_mps_datetime'],
			['title' => 'Tahun', 'width' => '50px', 'data' => 'fin_year'],
			['title' => 'Group', 'width' => '50px', 'data' => 'fst_item_group_name'],
			['title' => 'Notes', 'width' => '50px', 'data' => 'fst_notes'],
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/mps/edit/' + row.fin_mps_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				select a.*,b.fst_item_group_name from trmps a inner join msgroupitems b on a.fin_item_group_id = b.fin_item_group_id where a.fst_active != 'D'
			) a");

		$selectFields = "a.*";
		$this->datatables->setSelectFields($selectFields);

		$Fields = $this->input->get('optionSearch');
		$searchFields = [$Fields];
		$this->datatables->setSearchFields($searchFields);
		
		// Format Data
		
		$datasources = $this->datatables->getData();		
		//$arrData = $datasources["data"];
		/*
		$arrDataFormated = [];
		foreach ($arrData as $data) {        
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		*/
		//$datasources["data"] = $arrData;
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
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);
		
		$data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>1], true);
		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Master Production Schedule") : lang("Update Master Production Schedule");
		$data["fin_mps_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;			

		$fstMPSNo = $this->trmps_model->generateTransactionNo();
		$data["fst_mps_no"] = $fstMPSNo;	
		$page_content = $this->parser->parse('pages/tr/production/mps/form', $data, true);
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
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$details =$dataPrepared["details"];

			unset($dataH["fin_mps_id"]);
			$dataH["fst_mps_no"] = $this->trmps_model->generateTransactionNo();			
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
			$insertId = $this->trmps_model->insert($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_mps_id"] = $insertId;
				$this->trmpsitems_model->insert($dataD);
			}
			
			$this->trmps_model->posting($insertId);
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
        $finMPSId = $this->input->post("fin_mps_id");
		try{
            $dataHOld = $this->trmps_model->getDataHeader($finMPSId);
            if ($dataHOld == null){
                show_404();
            }
			
			//$resp = dateIsLock($dataHOld->fdt_assembling_datetime);
			//if($resp["status"] != "SUCCESS"){
			//	throw new CustomException($resp["message"],3003,"FAILED",[]);
			//}
            //$this->trassembling_model->isEditable($finAssemblingId);
                        
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$details = $preparedData["details"];
			
			$dataH["fin_mps_id"] = $finMPSId;
			$dataH["fst_mps_no"] = $dataHOld->fst_mps_no;
			


			$this->db->trans_start();

			$this->trmps_model->deleteDetail($finMPSId);
			
			$this->validateData($dataH,$details);

			$this->trmps_model->update($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_mps_id"] = $finMPSId;
				$this->trmpsitems_model->insert($dataD);
			}
			$this->trmps_model->posting($finMPSId);
			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finMPSId;
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
			"fin_mps_id"=>$this->input->post("fin_mps_id"),
			"fst_mps_no"=>$this->input->post("fst_mps_no"),
			"fdt_mps_datetime"=>dBDateTimeFormat($this->input->post("fdt_mps_datetime")),
			"fin_year"=>$this->input->post("fin_year"),
			"fin_item_group_id"=>$this->input->post("fin_item_group_id"),
			"fst_notes"=>$this->input->post("fst_notes"),
            "fst_active"=>'A',			
		];		

		$dataDetails = $this->input->post("details");
		$dataDetails = json_decode($dataDetails);		
		$details = [];
		$totalNilaiJual = 0;
		$ttlHPPD = 0;
		foreach($dataDetails as $detail){			
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
				"fdb_qty_buffer"=>$detail->fdb_qty_buffer_stock,
				"fdb_qty_m01"=>$detail->fdb_qty_m01,
				"fdb_qty_m02"=>$detail->fdb_qty_m02,
				"fdb_qty_m03"=>$detail->fdb_qty_m03,
				"fdb_qty_m04"=>$detail->fdb_qty_m04,
				"fdb_qty_m05"=>$detail->fdb_qty_m05,
				"fdb_qty_m06"=>$detail->fdb_qty_m06,
				"fdb_qty_m07"=>$detail->fdb_qty_m07,
				"fdb_qty_m08"=>$detail->fdb_qty_m08,
				"fdb_qty_m09"=>$detail->fdb_qty_m09,
				"fdb_qty_m10"=>$detail->fdb_qty_m10,
				"fdb_qty_m11"=>$detail->fdb_qty_m11,
				"fdb_qty_m12"=>$detail->fdb_qty_m12,
				"fst_active"=>"A"
			];
			$details[]=(object) $tmp;
		}
		return[
			"dataH"=>$dataH,
			"details"=>$details,
		];
		
	}
	
	private function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trmps_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}			
	}

	public function fetch_data($finId){
		$data = $this->trmps_model->getDataById($finId);	
		if ($data == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}


	public function delete($finId){

		try{
            $dataHOld = $this->trmps_model->getDataHeader($finId);
            if ($dataHOld == null){
                show_404();
			}
			//$resp = dateIsLock($dataHOld->fdt_assembling_datetime);
			//if($resp["status"] != "SUCCESS"){
			//	throw new CustomException($resp["message"],3003,"FAILED",[]);
			//}
            //$this->trassembling_model->isEditable($finId);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();			
			$resp = $this->trmps_model->delete($finId,true,null);	
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


	public function ajxGetDetailItems(){
		$this->load->model("msitemunitdetails_model");
		$this->load->model("trinventory_model");

		$finItemGroupId= $this->input->get("fin_item_group_id");
		$finYear = $this->input->get("fin_year");

		$ssql ="SELECT a.fin_item_id,a.fst_item_code,a.fst_item_name,a.fdb_qty_buffer_stock,b.fst_unit FROM msitems a
			LEFT JOIN msitemunitdetails b on a.fin_item_id = b.fin_item_id
			WHERE a.fin_item_type_id = 4 and a.fin_item_group_id = ? and a.fst_active = 'A' and b.fbl_is_basic_unit = 1" ;

		$qr = $this->db->query($ssql,[$finItemGroupId]);
		$rs = $qr->result();


		$ssql = "SELECT a.* FROM trmtsitems a 
			INNER JOIN trmts b on a.fin_mts_id = b.fin_mts_id 
			WHERE b.fin_year = ? and b.fin_item_group_id = ? and a.fst_active ='A'";

		$qr = $this->db->query($ssql,[$finYear,$finItemGroupId]);
		$detailsMTS = $qr->result();


		for($i = 0 ;$i < sizeof($rs);$i++){
			$rw = $rs[$i];

			$arrMTS = [
				"fdb_qty_m01"=>0,
				"fdb_qty_m02"=>0,
				"fdb_qty_m03"=>0,
				"fdb_qty_m04"=>0,
				"fdb_qty_m05"=>0,
				"fdb_qty_m06"=>0,
				"fdb_qty_m07"=>0,
				"fdb_qty_m08"=>0,
				"fdb_qty_m09"=>0,
				"fdb_qty_m10"=>0,
				"fdb_qty_m11"=>0,
				"fdb_qty_m12"=>0,				
			];

			foreach($detailsMTS as $dataMTS){
				if ($dataMTS->fin_item_id  == $rw->fin_item_id){
					$arrMTS = [
						"fdb_qty_m01"=>$dataMTS->fdb_qty_m01,
						"fdb_qty_m02"=>$dataMTS->fdb_qty_m02,
						"fdb_qty_m03"=>$dataMTS->fdb_qty_m03,
						"fdb_qty_m04"=>$dataMTS->fdb_qty_m04,
						"fdb_qty_m05"=>$dataMTS->fdb_qty_m05,
						"fdb_qty_m06"=>$dataMTS->fdb_qty_m06,
						"fdb_qty_m07"=>$dataMTS->fdb_qty_m07,
						"fdb_qty_m08"=>$dataMTS->fdb_qty_m08,
						"fdb_qty_m09"=>$dataMTS->fdb_qty_m09,
						"fdb_qty_m10"=>$dataMTS->fdb_qty_m10,
						"fdb_qty_m11"=>$dataMTS->fdb_qty_m11,
						"fdb_qty_m12"=>$dataMTS->fdb_qty_m12
					];

					break;
				}
			}
			$rw->fdb_qty_mts_m01 = $arrMTS["fdb_qty_m01"];
			$rw->fdb_qty_mts_m02 = $arrMTS["fdb_qty_m02"];
			$rw->fdb_qty_mts_m03 = $arrMTS["fdb_qty_m03"];
			$rw->fdb_qty_mts_m04 = $arrMTS["fdb_qty_m04"];
			$rw->fdb_qty_mts_m05 = $arrMTS["fdb_qty_m05"];
			$rw->fdb_qty_mts_m06 = $arrMTS["fdb_qty_m06"];
			$rw->fdb_qty_mts_m07 = $arrMTS["fdb_qty_m07"];
			$rw->fdb_qty_mts_m08 = $arrMTS["fdb_qty_m08"];
			$rw->fdb_qty_mts_m09 = $arrMTS["fdb_qty_m09"];
			$rw->fdb_qty_mts_m10 = $arrMTS["fdb_qty_m10"];
			$rw->fdb_qty_mts_m11 = $arrMTS["fdb_qty_m11"];
			$rw->fdb_qty_mts_m12 = $arrMTS["fdb_qty_m12"];


			$basicUnit = $this->msitemunitdetails_model->getBasicUnit($rw->fin_item_id);
			$rw->fst_unit = $basicUnit;

			//Get production Unit
			//if ($rw->fst_unit == null) {
				//Get Basic Unit as production Unit
			//	$rw->fst_unit = $basicUnit;
			//}
			

			//Get  last Year Balance
			$lastDate = $finYear ."-01-01";
			$qtyStockBasicUnit = $this->trinventory_model->getLastStockAllBranch($rw->fin_item_id,$lastDate);
			$rw->fdb_last_period_qty = $qtyStockBasicUnit;
			$rs[$i] = $rw;			
		}		
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$rs
		]);

	}

	public function ajxGetUnits($finItemId){
		$this->load->model("msitemunitdetails_model");
		$list = $this->msitemunitdetails_model->getItemListUnits($finItemId);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$list,
		]);
	}	

	public function ajxGetTotalHPP(){
		$finItemId = $this->input->get("fin_item_id");
		$fstUnit = $this->input->get("fst_unit");
		$fdbQty =  $this->input->get("fdb_qty");
		$finWarehouseId  = $this->input->get("fin_warehouse_id");

		$this->load->model("trinventory_model");
		$totalHpp = $this->trinventory_model->getTotalHPP($finItemId,$fstUnit,$fdbQty,$finWarehouseId);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>[
				"HPP"=>$totalHpp,
			]
		]);
	}
}    
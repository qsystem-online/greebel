<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mrp extends MY_Controller{
	public $menuName="mrp";

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("trmrp_model");
		$this->load->model("trmrpweekdetails_model");
		$this->load->model("trmrpmaterialdetails_model");		
		$this->load->model("mswarehouse_model");	
		
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "Material Requeriment Plan";
		$this->list['list_name'] = "Material Requeriment Plan List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/fixed_asset/disposal/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
			//"<a id='btnPrint'  href='".site_url()."tr/gudang/penerimaan_pembelian/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> Print </a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/mrp/fetch_list_data';
		$this->list['arrSearch'] = [
            'fst_mrp_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Fixed Asset', 'link' => '#', 'icon' => ''],
			['title' => 'Disposal', 'link' => NULL, 'icon' => ''],
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_mrp_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_mrp_no'],
			['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_mrp_datetime'],
			['title' => 'MPS', 'width' => '60px', 'data' => 'fst_mps_no'],
			['title' => 'MPS Month', 'width' => '60px', 'data' => 'fin_mps_month','className'=>'text-right',
				'render'=>'function(data,type,row){
					data = parseFloat(data);
					switch(data){
						case 1:
							return "Januari";
							break;
						case 2:
							return "Febuari";
							break;
						case 3:
							return "Maret";
							break;
						case 4:
							return "April";
							break;
						case 5:
							return "Mei";
							break;
						case 6:
							return "Juni";
							break;
						case 7:
							return "Juli";
							break;
						case 8:
							return "Agustus";
							break;
						case 9:
							return "September";
							break;
						case 10:
							return "Oktober";
							break;
						case 11:
							return "November";
							break;
						case 12:
							return "Desember";
							break;
						default:
							return "";
							break;
					}					
				}'
			],			
			['title' => 'Notes', 'width' => '50px', 'data' => 'fst_notes'],
            ['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/mrp/edit/' + row.fin_mrp_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				select a.*,b.fst_mps_no from trmrp a inner join trmps b on a.fin_mps_id = b.fin_mps_id where a.fst_active != 'D'
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
		parent::add();
		$this->openForm("ADD", 0);
	}
	
	public function edit($finId){
		parent::edit($finId);
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


		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add Material Requirement Planning") : lang("Update Material Requirement Planning");
		$data["fin_mrp_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;			

		$fstMRPNo = $this->trmrp_model->generateTransactionNo();
		$data["fst_mrp_no"] = $fstMRPNo;	
		$page_content = $this->parser->parse('pages/tr/production/mrp/form', $data, true);
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
		parent::ajx_add_save();
		try{			
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$weekDetails =$dataPrepared["weekDetails"];
			$materialDetails =$dataPrepared["materialDetails"];

			unset($dataH["fin_mrp_id"]);
			$dataH["fst_mrp_no"] = $this->trmrp_model->generateTransactionNo();					
			$this->validateData($dataH,$weekDetails,$materialDetails);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		
		
		try{
			$this->db->trans_start();
			$insertId = $this->trmrp_model->insert($dataH);

			foreach($weekDetails as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_mrp_id"] = $insertId;
				$this->trmrpweekdetails_model->insert($dataD);
			}


			foreach($materialDetails as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_mrp_id"] = $insertId;
				$this->trmrpmaterialdetails_model->insert($dataD);
			}

			
			//$this->trfadisposal_model->posting($insertId);
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
		parent::ajx_edit_save();		
        $finMRPId = $this->input->post("fin_mrp_id");
		try{
            $dataHOld = $this->trmrp_model->getDataHeader($finMRPId);
            if ($dataHOld == null){
                show_404();
            }            
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
			$weekDetails = $preparedData["weekDetails"];
			$materialDetails = $preparedData["materialDetails"];
			
			$dataH["fin_mrp_id"] = $finMRPId;
			$dataH["fst_mrp_no"] = $dataHOld->fst_mrp_no;
			

			$this->db->trans_start();
			
			$this->trmrp_model->deleteDetail($finMRPId);
			
			$this->validateData($dataH,$weekDetails,$materialDetails);

			$this->trmrp_model->update($dataH);
			
			foreach($weekDetails as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_mrp_id"] = $finMRPId;
				$this->trmrpweekdetails_model->insert($dataD);
			}

			foreach($materialDetails as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_mrp_id"] = $finMRPId;
				$this->trmrpmaterialdetails_model->insert($dataD);
			}

			
			
			$this->db->trans_complete();
			//$this->db->trans_rollback();
			//var_dump($materialDetails);
			//die();

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finMRPId;
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
			"fin_mrp_id"=>$this->input->post("fin_mrp_id"),
			"fst_mrp_no"=>$this->input->post("fst_mrp_no"),
			"fdt_mrp_datetime"=>dBDateTimeFormat($this->input->post("fdt_mrp_datetime")),
			"fin_mps_id"=>$this->input->post("fin_mps_id"),
			"fin_mps_month"=>$this->input->post("fin_mps_month"),
			"fst_notes"=>$this->input->post("fst_notes"),
            "fst_active"=>'A',			
		];

		
		$dataDetails = $this->input->post("details");
		$dataDetails = json_decode($dataDetails);		
		$details = [];
		foreach($dataDetails as $detail){
			
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,				
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
				"fdb_qty_w1"=>$detail->fdb_qty_w1,
				"fdb_qty_w2"=>$detail->fdb_qty_w2,
				"fdb_qty_w3"=>$detail->fdb_qty_w3,
				"fdb_qty_w4"=>$detail->fdb_qty_w4,
				"fdb_qty_w5"=>$detail->fdb_qty_w5,
				"fst_active"=>"A"				
			];
			$details[]=(object) $tmp;
		}


		$dataDetails2 = $this->input->post("details2");
		$dataDetails2 = json_decode($dataDetails2);		
		$details2 = [];
		foreach($dataDetails2 as $detail){		
			$tmp = [
				"fin_rec_id"=>$detail->fin_rec_id,
				"fin_level"=>$detail->fin_level,				
				"fin_item_id"=>$detail->fin_item_id,
				"fst_unit"=>$detail->fst_unit,
				"fdb_qty_rq_w1"=>$detail->fdb_qty_rq_w1,
				"fdb_qty_po_w1"=>$detail->fdb_qty_po_w1,
				"fdb_qty_wo_w1"=>$detail->fdb_qty_wo_w1,
				"fdb_qty_rq_w2"=>$detail->fdb_qty_rq_w2,
				"fdb_qty_po_w2"=>$detail->fdb_qty_po_w2,
				"fdb_qty_wo_w2"=>$detail->fdb_qty_wo_w2,
				"fdb_qty_rq_w3"=>$detail->fdb_qty_rq_w3,
				"fdb_qty_po_w3"=>$detail->fdb_qty_po_w3,
				"fdb_qty_wo_w3"=>$detail->fdb_qty_wo_w3,
				"fdb_qty_rq_w4"=>$detail->fdb_qty_rq_w4,
				"fdb_qty_po_w4"=>$detail->fdb_qty_po_w4,
				"fdb_qty_wo_w4"=>$detail->fdb_qty_wo_w4,
				"fdb_qty_rq_w5"=>$detail->fdb_qty_rq_w5,
				"fdb_qty_po_w5"=>$detail->fdb_qty_po_w5,
				"fdb_qty_wo_w5"=>$detail->fdb_qty_wo_w5,
				"fst_active"=>"A"				
			];
			$details2[]=(object) $tmp;
		}

		
		return[
			"dataH"=>$dataH,
			"weekDetails"=>$details,
			"materialDetails"=>$details2
		];
		
	}
	
	private function validateData($dataH,$weekDetails,$materialDetails){
		$this->form_validation->set_rules($this->trmrp_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}			
	}

	public function fetch_data($finId){
		$data = $this->trmrp_model->getDataById($finId);	
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
		parent::delete($finId);
		try{
            $dataHOld = $this->trassembling_model->getDataHeader($finId);
            if ($dataHOld == null){
                show_404();
			}
			$resp = dateIsLock($dataHOld->fdt_assembling_datetime);
			if($resp["status"] != "SUCCESS"){
				throw new CustomException($resp["message"],3003,"FAILED",[]);
			}
            $this->trassembling_model->isEditable($finId);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();			
			$resp = $this->trassembling_model->delete($finId,true,null);	
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


	public function ajxGetItemList(){
		$this->load->model("msitems_model");
		$term =  $this->input->get("term");
		$list = $this->msitems_model->getItemList($term);
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$list
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

	public function ajxGetMPS(){
		$ssql = "SELECT fin_mps_id,fst_mps_no FROM trmps where fst_active ='A'";
		$qr = $this->db->query($ssql,[]);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);		
	}

	public function ajxGetMPSDetails(){
		$finMPSId = $this->input->get("fin_mps_id");
		$finMPSMonth = $this->input->get("fin_mps_month");
		$ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name FROM trmpsitems a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			where fin_mps_id = ?";
		
		$qr = $this->db->query($ssql,[$finMPSId]);
		$rs = $qr->result();

		$result = [];
		foreach($rs as $rw ){

			$qtyMPS =0;
			switch($finMPSMonth){
				case 1:
					$qtyMPS = $rw->fdb_qty_m01;
					break;
				case 2:
					$qtyMPS = $rw->fdb_qty_m02;
					break;
				case 3:
					$qtyMPS = $rw->fdb_qty_m03;
					break;
				case 4:
					$qtyMPS = $rw->fdb_qty_m04;
					break;
				case 5:
					$qtyMPS = $rw->fdb_qty_m05;
					break;
				case 6:
					$qtyMPS = $rw->fdb_qty_m06;
					break;
				case 7:
					$qtyMPS = $rw->fdb_qty_m07;
					break;
				case 8:
					$qtyMPS = $rw->fdb_qty_m08;
					break;
				case 9:
					$qtyMPS = $rw->fdb_qty_m09;
					break;
				case 10:
					$qtyMPS = $rw->fdb_qty_m10;
					break;
				case 11:
					$qtyMPS = $rw->fdb_qty_m11;
					break;
				case 12:
					$qtyMPS = $rw->fdb_qty_m12;
					break;
			}
			
			$result[] = [
				"fin_item_id"=>$rw->fin_item_id,
				"fst_item_code"=>$rw->fst_item_code,
				"fst_item_name"=>$rw->fst_item_name,
				"fst_unit"=>$rw->fst_unit,
				"fdb_qty_mps"=>$qtyMPS,
				"fin_mps_month"=>$finMPSMonth
			];
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$result
		]);

	}


	public function ajxGetMaterialDetails(){
		$this->load->model("msitembomdetails_model");
		$this->load->model("trinventory_model");
		$finMPSId = $this->input->get("fin_mps_id");
		$finMPSMonth = $this->input->get("fin_mps_month");

		$ssql ="SELECT * FROM trmps where fin_mps_id = ? and fst_active ='A'";
		$qr =  $this->db->query($ssql,[$finMPSId]);
		$rw = $qr->row();

		if($rw == null){
			$this->json_output([
				"status"=>"FAILED",
				"messages"=>"Invalid MPS ID",
				"data"=>[]
			]);
		}

		$th = $rw->fin_year;
		$stockDate = $th ."-" . substr("00".$finMPSMonth,strlen("00".$finMPSMonth) -2,2) ."-01";
		//fin_item_id,fst_unit,fdb_req_w1 -w5
		$details = $this->input->get("details");
		//$details = json_decode($details);
		$result = [];
		

		foreach($details as $item){
			$item =(object) $item;
			$bomList = $this->msitembomdetails_model->getBOMList($item->fin_item_id);
			foreach($bomList as $bom){
				if(!isset($result[$bom->fin_item_id])){
					$result[$bom->fin_item_id] = [
						"fin_item_id"=>$bom->fin_item_id,
						"fst_item_code"=>$bom->fst_item_code,
						"fst_item_name"=>$bom->fst_item_name,
						"fst_unit"=>$bom->fst_unit,
						"fdb_qty_balance"=>$this->trinventory_model->getLastStockAllBranch($bom->fin_item_id,$stockDate),
						"fdb_qty_rq_w1"=>$bom->fdb_qty_per_unit * (double) $item->fdb_qty_w1,
						"fdb_qty_rq_w2"=>$bom->fdb_qty_per_unit * (double) $item->fdb_qty_w2,
						"fdb_qty_rq_w3"=>$bom->fdb_qty_per_unit * (double) $item->fdb_qty_w3,
						"fdb_qty_rq_w4"=>$bom->fdb_qty_per_unit * (double) $item->fdb_qty_w4,
						"fdb_qty_rq_w5"=>$bom->fdb_qty_per_unit * (double) $item->fdb_qty_w5,
					];
				}else{
					$result[$bom->fin_item_id]["fdb_qty_rq_w1"] += $bom->fdb_qty_per_unit * (double) $item->fdb_qty_w1;
					$result[$bom->fin_item_id]["fdb_qty_rq_w2"] += $bom->fdb_qty_per_unit * (double) $item->fdb_qty_w2;
					$result[$bom->fin_item_id]["fdb_qty_rq_w3"] += $bom->fdb_qty_per_unit * (double) $item->fdb_qty_w3;
					$result[$bom->fin_item_id]["fdb_qty_rq_w4"] += $bom->fdb_qty_per_unit * (double) $item->fdb_qty_w4;
					$result[$bom->fin_item_id]["fdb_qty_rq_w5"] += $bom->fdb_qty_per_unit * (double) $item->fdb_qty_w5;
				}				
			}
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$result
		]);
	}
}    
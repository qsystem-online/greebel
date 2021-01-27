<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Woe_inv extends MY_Controller{
	public $menuName="woe_memo_in";
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("trwoeinvoice_model");
		$this->load->model("trwoeinvoiceitemcost_model");
		$this->load->model("trwoeinvoiceitemin_model");
		$this->load->model("trmemowoein_model");
		$this->load->model("mscurrencies_model");
		$this->load->model("glaccounts_model");
		$this->load->model("profitcostcenter_model");
		$this->load->model("msdepartments_model");
		$this->load->model("msprojects_model");
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "External WO Invoice";
		$this->list['list_name'] = "External WO Invoice List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/woe_inv/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/woe_inv/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_woein_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'Workorder', 'link' => null, 'icon' => '']			
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_woeinv_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_woeinv_no'],
			['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_woeinv_datetime'],
			['title' => 'WO', 'width' => '50px', 'data' => 'fst_wo_no'],
			['title' => 'Supplier', 'width' => '50px', 'data' => 'fst_supplier_name'],
			['title' => 'Item', 'width' => '50px', 'data' => 'fst_item_name'],
			['title' => 'Total', 'width' => '50px', 'data' => 'fdc_total','className' => 'text-right'],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/woe_inv/edit/' + row.fin_woeinv_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
			SELECT a.*,
			b.fst_wo_no,b.fst_unit,
			c.fst_item_code,c.fst_item_name,
			d.fst_relation_name as fst_supplier_name 
			FROM trwoeinvoice a 
			INNER JOIN trwo b on a.fin_wo_id = b.fin_wo_id
			INNER JOIN msitems c on b.fin_item_id = c.fin_item_id 
			INNER JOIN msrelations d on a.fin_supplier_id = d.fin_relation_id 
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
		
		$data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>1], true);
		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? lang("Add External Workorder IN") : lang("Update External Workorder IN");
		$data["fin_woeinv_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		
		$fstWOEInvNo = $this->trwoeinvoice_model->generateTransactionNo();
		$data["fst_woeinv_no"] = $fstWOEInvNo;	
		$page_content = $this->parser->parse('pages/tr/production/woe_inv/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}



	public function ajxGetWOList(){
		$term = $this->input->get("term");
		$term = "%$term%";

		$ssql ="SELECT a.fin_wo_id,a.fst_wo_no,a.fst_unit,a.fdc_external_cost_per_unit,a.fst_curr_code,
			b.fin_relation_id as fin_supplier_id,b.fst_relation_name AS fst_supplier_name,
			c.fin_item_id,c.fst_item_name,c.fst_item_code 
			FROM trwo a
			INNER JOIN msrelations b ON a.fin_supplier_id = b.fin_relation_id
			INNER JOIN msitems c ON a.fin_item_id = c.fin_item_id
			WHERE a.fst_wo_no LIKE ?  AND fst_wo_type = 'External' AND  a.fbl_closed = 0 AND a.fst_active = 'A' ";
			

		$qr = $this->db->query($ssql,[$term]);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}
	
	public function ajxGetWOInList(){
		$finWOId = $this->input->get("fin_wo_id");
		$ssql = "SELECT a.fin_woein_id,a.fst_woein_no,a.fdb_qty
			FROM trmemowoein a
			INNER JOIN trmemowoeout b on a.fin_woeout_id = b.fin_woeout_id 
			INNER JOIN trwo c on b.fin_wo_id = c.fin_wo_id
			WHERE c.fin_wo_id = ? and fbl_inv = 0 and a.fst_active = 'A' ";
		
		$qr = $this->db->query($ssql,[$finWOId]);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$rs
		]);
		
	}

	public function ajxGetSupplierList(){
		$this->load->model("msrelations_model");
		$rs = $this->msrelations_model->getSupplierList();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}
	
	public function ajxGetCostAccount(){
		$rs = $this->glaccounts_model->getBiayaList();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs,
		]);
	}


	public function ajxGetCustomer(){
		$this->load->model("msrelations_model");
		$rs = $this->msrelations_model->getCustomerList();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}

	public function ajx_add_save(){			
		parent::ajx_add_save();
		try{			
			$dataPrepared = $this->prepareData();
			$dataH = $dataPrepared["dataH"];
			$dataDWOEIn = $dataPrepared["dataDWOEIn"];
			$dataDCost = $dataPrepared["dataDCost"];
			unset($dataH["fin_woeinv_id"]);
			$dataH["fst_woeinv_no"] = $this->trwoeinvoice_model->generateTransactionNo();			
			$this->validateData($dataH,$dataDWOEIn,$dataDCost);

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}			

		try{
			$this->db->trans_start();
			$insertId = $this->trwoeinvoice_model->insert($dataH);

			foreach($dataDWOEIn as $fin_woein_id){
				$woein = $this->trmemowoein_model->getSimpleDataById($fin_woein_id);
				$dataDWOEIn =[
					"fin_woeinv_id"=>$insertId,
					"fin_woein_id"=>$fin_woein_id,
					"fdb_qty"=>$woein->fdb_qty,
					"fst_active"=>"A"
				];
				$this->trwoeinvoiceitemin_model->insert($dataDWOEIn);
			}

			foreach($dataDCost as $dCost){				

				$dataDWOECost =[
					"fin_woeinv_id"=>$insertId,
					"fst_glaccount_code"=>$dCost->fst_glaccount_code,
					"fin_pcc_id"=>$dCost->fin_pcc_id,
					"fin_pc_divisi_id"=>$dCost->fin_pc_divisi_id,
					"fin_pc_customer_id"=>$dCost->fin_pc_customer_id,
					"fin_pc_project_id"=>$dCost->fin_pc_project_id,	
					"fdc_total"=>$dCost->fdc_total,	
					"fst_active"=>"A"
				];

				$this->trwoeinvoiceitemcost_model->insert($dataDWOECost);
				
			}
			$this->trwoeinvoice_model->posting($insertId);
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
		$finWOEInvId = $this->input->post("fin_woeinv_id");
		try{
			$dataHOld = $this->trwoeinvoice_model->getSimpleDataById($finWOEInvId);
			if ($dataHOld == null){
				show_404();
			}			
			$this->trwoeinvoice_model->isEditable($finWOEInvId);			            
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			
			$dataPrepared = $this->prepareData();

			$dataH = $dataPrepared["dataH"];
			$dataDWOEIn = $dataPrepared["dataDWOEIn"];
			$dataDCost = $dataPrepared["dataDCost"];
			
			$this->db->trans_start();
			$this->trwoeinvoice_model->unposting($finWOEInvId);
			$this->validateData($dataH,$dataDWOEIn,$dataDCost);

			$ssql = "delete from trwoeinvoiceitemin where fin_woeinv_id = ?";
			$this->db->query($ssql,[$finWOEInvId]);

			$ssql = "delete from trwoeinvoiceitemcost where fin_woeinv_id = ?";
			$this->db->query($ssql,[$finWOEInvId]);
			

			$this->trwoeinvoice_model->update($dataH);
			foreach($dataDWOEIn as $fin_woein_id){
				$woein = $this->trmemowoein_model->getSimpleDataById($fin_woein_id);
				$dataDWOEIn =[
					"fin_woeinv_id"=>$finWOEInvId,
					"fin_woein_id"=>$fin_woein_id,
					"fdb_qty"=>$woein->fdb_qty,
					"fst_active"=>"A"
				];
				$this->trwoeinvoiceitemin_model->insert($dataDWOEIn);
			}

			foreach($dataDCost as $dCost){				
				$dataDWOECost =[
					"fin_woeinv_id"=>$finWOEInvId,
					"fst_glaccount_code"=>$dCost->fst_glaccount_code,
					"fin_pcc_id"=>$dCost->fin_pcc_id,
					"fin_pc_divisi_id"=>$dCost->fin_pc_divisi_id,
					"fin_pc_customer_id"=>$dCost->fin_pc_customer_id,
					"fin_pc_project_id"=>$dCost->fin_pc_project_id,	
					"fdc_total"=>$dCost->fdc_total,	
					"fst_active"=>"A"
				];

				$this->trwoeinvoiceitemcost_model->insert($dataDWOECost);
				
			}

			$this->trwoeinvoice_model->posting($finWOEInvId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finWOEInvId;
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
			"fin_woeinv_id"=>$this->input->post("fin_woeinv_id"),
			"fst_woeinv_no"=>$this->input->post("fst_woeinv_no"),
			"fdt_woeinv_datetime"=>dBDateTimeFormat($this->input->post("fdt_woeinv_datetime")),
			"fin_wo_id"=>$this->input->post("fin_wo_id"),	
			"fst_curr_code"=>$this->input->post("fst_curr_code"),	
			"fin_supplier_id"=>$this->input->post("fin_supplier_id"),		
			"fdc_exchange_rate_idr"=>$this->input->post("fdc_exchange_rate_idr"),
			"fdb_total_qty"=>0,
			"fdc_external_cost_per_unit"=>$this->input->post("fdc_external_cost_per_unit"),
			"fst_memo" => $this->input->post("fst_memo"),
			"fst_active"=>'A',
		];		

		$dataDWOEIn = $this->input->post("fin_woein_id_list");

		$ttlQty = 0;
		foreach($dataDWOEIn as $fin_woein_id){
			$woein = $this->trmemowoein_model->getSimpleDataById($fin_woein_id);
			$ttlQty += $woein->fdb_qty;
		}
		$dataH["fdb_total_qty"] = $ttlQty;
		$dataH["fdc_total"] = $ttlQty * $dataH["fdc_external_cost_per_unit"];

		$dataDCost = JSON_decode($this->input->post("detailsCost"));
		$total=0;
		foreach ($dataDCost as $dCost) {
			$total += $dCost->fdc_total;
		}

		if ($dataH["fdc_total"] != $total){
			throw new CustomException(lang("Total header dan detail tidak sesuai !"), 3003,"FAILED",[]);			
		};

		return[
			"dataH"=>$dataH,
			"dataDWOEIn"=>$dataDWOEIn,
			"dataDCost"=>$dataDCost
		];
		
	}
	
	private function validateData($dataH,$dataDWOEIn,$dataDCost){
		$this->form_validation->set_rules($this->trwoeinvoice_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		//Get Total cost harus sama dengan total cost inv dari inv in
		//$arrFinWOEInId = [];
		//var_dump($dataDWOEIn);
		//die();
		//foreach($dataDWOEIn as $woein){
		//	$arrFinWOEInId[] = $woein;
		//}
		//Get WO
		$ssql = "SELECT sum(a.fdb_qty * c.fdc_external_cost_per_unit) as ttl_woein_cost 		
			FROM trmemowoein a 
			INNER JOIN trmemowoeout b on a.fin_woeout_id = b.fin_woeout_id
			INNER JOIN trwo c on b.fin_wo_id = c.fin_wo_id 
			where a.fin_woein_id in ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$dataDWOEIn]);
		$rw = $qr->row();

		//Get Total Detail Cost
		$total = 0;
		foreach($dataDCost as $dCost){
			$total += $dCost->fdc_total; 
		}

		if ($total != $rw->ttl_woein_cost){
			throw new CustomException("Data total tidak sama",3003,"FAILED",[]);
		}

	}

	public function fetch_data($finId){
		$data = $this->trwoeinvoice_model->getDataById($finId);	
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
			$dataHOld = $this->trmemowoein_model->getSimpleDataById($finId);
			if ($dataHOld == null){
				show_404();
			}
			$this->trmemowoein_model->isEditable($finId);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();	
			$this->trmemowoein_model->unposting($finId);
			$resp = $this->trmemowoein_model->delete($finId,true,null);
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



	
	public function ajxGetDetailWOE(){
		
		$finWOEOutId = $this->input->get("fin_woeout_id");

		$ssql = "SELECT b.fin_item_id,b.fst_unit,a.fdb_qty,a.fdb_qty_in,a.fin_supplier_id,d.fst_relation_name,
			c.fst_item_name,c.fst_item_code 
			FROM trmemowoeout a			
			INNER JOIN trwo b on a.fin_wo_id = b.fin_wo_id
			INNER JOIN msitems c on b.fin_item_id = c.fin_item_id
			INNER JOIN msrelations d on a.fin_supplier_id = d.fin_relation_id 
			WHERE a.fin_woeout_id = ? and a.fst_active !='D'";
			
		$qr = $this->db->query($ssql,[$finWOEOutId]);
		$rw  = $qr->row();	

		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rw,
		]);
		
	}
	
}    
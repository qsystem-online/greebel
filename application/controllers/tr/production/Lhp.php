<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lhp extends MY_Controller{

	public function __construct(){
        parent::__construct();
		$this->load->model("trlhp_model");
		$this->load->model("trlhpactivities_model");
		$this->load->model("mswarehouse_model");
		$this->load->model("msmesin_model");
		$this->load->library('form_validation');
		$this->load->model('users_model');		
		$this->load->model('msactivityteams_model');	
		$this->load->model('msunits_model');	
		$this->load->model('msitems_model');	
			
	}

	public function index(){
		$this->lizt();
	}

	public function lizt(){
		$this->load->library('menus');
		$this->list['page_name'] = "Hasil Produksi";
		$this->list['list_name'] = "Hasil Produksi List";
		$this->list['addnew_ajax_url'] = site_url() . 'tr/production/lhp/add';
		$this->list['pKey'] = "fin_salesorder_id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/lhp/fetch_list_data';
		$this->list['edit_ajax_url'] = site_url() . 'tr/production/lhp/edit/';

		$this->list['arrSearch'] = [
			'fst_lhp_no' => 'LHP #',
			'fst_wo_no' => 'WO #'
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'LHP', 'link' => '#', 'icon' => ''],
			['title' => 'List', 'link' => NULL, 'icon' => ''],
		];

		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/lhp/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '10px', 'visible' => 'false', 'data' => 'fin_lhp_id'],
			['title' => 'LHP No.', 'width' => '120px', 'data' => 'fst_lhp_no'],
			['title' => 'LHP Date', 'width' => '80px', 'data' => 'fdt_lhp_datetime'],
			['title' => 'WO #', 'width' => '130px', 'data' => 'fst_wo_no'],
			['title' => 'Qty', 'width' => '130px', 'data' => 'fdb_qty'],
			['title' => 'Unit', 'width' => '130px', 'data' => 'fst_unit'],
			['title' => 'Action', 'width' => '60px', 'sortable' => false, 'className' => 'dt-body-center text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/lhp/edit/' + row.fin_lhp_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('template/listjs', [], true);		
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

	private function openForm($mode = "ADD", $id = 0){
		$this->load->library("menus");		
		if ($this->input->post("submit") != "") {
			$this->add_save();
		}

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlJurnal = $this->parser->parse('template/mdlJurnal.php', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);
		//$mdlConfirmAuthorize = $this->parser->parse('template/mdlConfirmAuthorize.php', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		
		

		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Sales Order" : "Update Sales Order";
		$data["mdlJurnal"] = $mdlJurnal;
		$data["mdlPrint"] = $mdlPrint;
		//$data["mdlConfirmAuthorize"] = $mdlConfirmAuthorize;
		$data["mdlEditForm"] = $edit_modal;
		
		if($mode == 'ADD'){
			$data["fin_lhp_id"] = 0;
			$data["fst_lhp_no"] = $this->trlhp_model->GenerateNo();
		}else{
			$data["fin_lhp_id"] = $id;
			$data["fst_lhp_no"] = "";			
		}
		
		$page_content = $this->parser->parse('pages/tr/production/lhp/form', $data, true);
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

	public function edit($finLHPId){
		$this->openForm("EDIT", $finLHPId);
	}

	public function view($finSalesOrderId){
		$this->openForm("VIEW", $finSalesOrderId);
	}
	
	public function ajx_add_save(){		
			

		try{
			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$details = $preparedData["details"];
			unset($dataH["fin_lhp_id"]);
			$this->validateData($dataH,$details);
			$resp = dateIsLock($dataH["fdt_lhp_datetime"]);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],[]);
			}
		}catch(CustomException $e){
			$this->json_output([
				"status"=>$e->getStatus(),
				"messages"=>$e->getMessage(),
				"data"=>$e->getStatus(),
				"status"=>$e->getData()				
			]);
		}

		try{
			$this->db->trans_start();
			$dataH["fst_serial_number_list"] =  json_encode($dataH["fst_serial_number_list"]);
			$insertId = $this->trlhp_model->insert($dataH);
			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_lhp_id"] = $insertId;
				$this->trlhpactivities_model->insert($dataD);
			}
			$this->trlhp_model->posting($insertId);
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
		$finLHPId = $this->input->post("fin_lhp_id");

		try{
            $dataHOld = $this->trlhp_model->getSimpleDataById($finLHPId);
            if ($dataHOld == null){
                show_404();
			}

			$resp = dateIsLock($dataHOld->fdt_lhp_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],[]);
			}

			$this->trlhp_model->isEditable($finLHPId);

			
			
			 
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{

			$this->db->trans_start();					
			
			$this->trlhp_model->unposting($finLHPId);

			$preparedData = $this->prepareData();
			$dataH = $preparedData["dataH"];
			$details = $preparedData["details"];
			$dataH["fin_lhp_id"] = $dataHOld->fin_lhp_id;
			$dataH["fst_lhp_no"] = $dataHOld->fst_lhp_no;
			$this->validateData($dataH,$details);			
			$resp = dateIsLock($dataH["fdt_lhp_datetime"]);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],[]);
			}



			$this->trlhp_model->deleteDetail($finLHPId);

			$dataH["fst_serial_number_list"] =  json_encode($dataH["fst_serial_number_list"]);
			$this->trlhp_model->update($dataH);

			foreach($details as $dataD){
				$dataD = (array)$dataD;
				$dataD["fin_lhp_id"] = $finLHPId;
				$this->trlhpactivities_model->insert($dataD);
			}

			$this->db->trans_complete();
			$this->trlhp_model->posting($finLHPId);

			$this->json_output([
				"status"=>"SUCCESS",
				"message"=>"Data Saved !",
				"data"=>[
					"insert_id"=>$finLHPId
				]
			]);			
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}
	}

	public function prepareData(){
		
		$fst_lhp_no = $this->trlhp_model->GenerateNo();			
		$fdt_lhp_datetime= dBDateTimeFormat($this->input->post("fdt_lhp_datetime"));
		$fdt_start_datetime= dBDateTimeFormat($this->input->post("fdt_start_datetime"));
		$fdt_end_datetime= dBDateTimeFormat($this->input->post("fdt_end_datetime"));
		


		$dataH = [
			'fin_lhp_id'=>0,
			'fst_lhp_no'=>$fst_lhp_no,
			'fdt_lhp_datetime'=>$fdt_lhp_datetime,
			'fin_wo_id'=>$this->input->post("fin_wo_id"),
			'fin_wobatchno_id'=>$this->input->post("fin_wobatchno_id"),
			'fin_warehouse_id'=>$this->input->post("fin_warehouse_id"),
			'fdb_gramasi'=>$this->input->post("fdb_gramasi"),
			'fin_mesin_id'=>$this->input->post("fin_mesin_id"),
			'fdt_start_datetime'=>$fdt_start_datetime,
			'fdt_end_datetime'=>$fdt_end_datetime,
			'fin_downtime_in_minutes'=>$this->input->post("fin_downtime_in_minutes"),
			'fin_checksheet_id'=>$this->input->post("fin_checksheet_id"),
			'fin_item_id'=>$this->input->post("fin_item_id"),
			'fdb_qty'=>$this->input->post("fdb_qty"),
			'fst_unit'=>$this->input->post("fst_unit"),
			'fdb_qty_baseonwo'=>$this->input->post("fdb_qty"),
			'fdb_qty_sisa'=>$this->input->post("fdb_qty_sisa"),
			'fst_wo_unit'=>$this->input->post("fst_unit"),
			'fst_notes'=>$this->input->post("fst_notes"),
			'fst_batch_number'=>NULL,
			'fst_serial_number_list'=>[],
			'fst_active'=>'A',
		];

		if ($dataH["fst_unit"] == "KILO"){
			$ssql = "SELECT * FROM trwo where fin_wo_id = ? and fst_active ='A'";
			$qr = $this->db->query($ssql,[$dataH["fin_wo_id"]]);
			$rw = $qr->row();
			if ($rw == null){
				throw new CustomException(lang("No Wo Tidak dikenal !"),3003,"FAILED".["fin_wo_id : $dataH[fin_wo_id]"]);				
			}

			$fstUnitWO = $rw->fst_unit;
			$dataH["fst_wo_unit"] = $fstUnitWO;
			$basicUnit = $this->msitems_model->getBasicUnit($dataH["fin_item_id"]);
			//$fdbQtyInBasic =$this->msitems_model->getQtyConvertToBasicUnit($dataH["fin_item_id"],$dataH["fdb_qty"],$fstUnitWO);
			$fdbQtyInBasic = $dataH["fdb_qty"] / $dataH["fdb_gramasi"];
			$fdbQtyInWOUnit = $this->msitems_model->getQtyConvertUnit($dataH["fin_item_id"],$fdbQtyInBasic,$basicUnit,$fstUnitWO);
			$dataH["fdb_qty_baseonwo"] = $fdbQtyInWOUnit;
			$dataH["fdb_qty_sisa"] = $rw->fdb_qty - $rw->fdb_qty_lhp - $dataH["fdb_qty_baseonwo"];			
		};

		//Generate Batch number and Serial Number
		$rwItem = $this->msitems_model->getSimpleDataById($dataH["fin_item_id"]);
		if ($rwItem->fbl_is_batch_number){
			$dataH["fst_batch_number"] = $dataH["fst_lhp_no"];
		}

		if ($rwItem->fbl_is_serial_number){
			$arrSerial=[];
			$prefix = str_replace("/","",$dataH["fst_lhp_no"]);
			for($i=1;$i<=$dataH["fdb_qty_baseonwo"];$i++){
				$arrSerial[] = $prefix ."-" .$i;
			}
			$dataH["fst_serial_number_list"] = $arrSerial;
		}



		$details = $this->input->post("details");
		$details = json_decode($details);
		$dataDetails=[];
		foreach($details as $dataD){
			$tmp = [
				"fin_activity_id"=>$dataD->fin_activity_id,
				"fin_team_id"=>$dataD->fin_team_id,
				"fin_user_id"=>$dataD->fin_user_id,
				"fdt_start_datetime"=>dBDateTimeFormat($dataD->fdt_start_datetime),
				"fdt_end_datetime"=>dBDateTimeFormat($dataD->fdt_end_datetime),
				"fdb_qty"=>$dataD->fdb_qty,
				"fst_unit"=>$dataD->fst_unit,
				"fst_active"=>"A"
			];
			$dataDetails[] = $tmp;
		}

		return [
			"dataH"=>$dataH,
			"details"=>$dataDetails
		];		
	}

	public function validateData($dataH,$details){
		$this->form_validation->set_rules($this->trlhp_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException(lang("Invalid data posted"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		//apakah  item tersebut harus batch number atau serial number

	}

	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("(
			select a.*,b.fst_wo_no from trlhp a 
			inner join trwo  b on a.fin_wo_id = b.fin_wo_id
		) a");

		$selectFields = "a.*";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();			
		//$datasources["data"] = $datasources;
		$this->json_output($datasources);
	}

	public function fetch_data($fin_lhp_id){
		$data = $this->trlhp_model->getDataById($fin_lhp_id);
		if ($data == null){
			$this->json_output([
				"status"=>"FAILED",
				"messages"=>lang("Data tidak di kenal !"),
				"data"=>[]
			]);
		}else{
			$this->json_output([
				"status"=>"SUCCESS",
				"messages"=>"",
				"data"=>$data
			]);
		}
	}

	public function delete($finSalesOrderId){
		$this->load->model("trsalesorder_model");
		$this->load->model('trsalesorder_model');
		$this->load->model('trvoucher_model');
		$this->load->model('trverification_model');
		
		
		$salesOrder = $this->trsalesorder_model->createObject($finSalesOrderId);

		//Is Editable ?		
		//CEK tgl lock dari transaksi yg di kirim	
		$resp = dateIsLock($salesOrder->getValue("fdt_salesorder_datetime"));
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}
		$resp = $this->trsalesorder_model->isEditable($finSalesOrderId);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();
			$this->trsalesorder_model->unposting($finSalesOrderId);
			$this->trsalesorder_model->delete($finSalesOrderId);
			$this->db->trans_complete();

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("Data dihapus !");
			$this->json_output();
		}catch(CustomException $e){
			$this->db->trans_rollback();
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
		}
        
	}	

	public function print_voucher($finSalesOrderId){
		$data = $this->trsalesorder_model->getDataVoucher($finSalesOrderId);

		$data["title"]= "Sales Order";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/sales_order/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		//echo $data;

			
		//$mpdf->SetHTMLHeaderByName('MyFooter');
		$mpdf->WriteHTML($data);
		$mpdf->Output();

    }
    
    public function ajxGetWOList(){
        $term = $this->input->get("term");
        $term = "%$term%";

        $ssql = "SELECT a.fin_wo_id,a.fst_wo_no,a.fin_item_id,b.fst_item_name,a.fst_unit,a.fdb_qty,a.fdb_qty_lhp,
			b.fbl_is_batch_number,b.fbl_is_serial_number,
            a.fin_warehouse_target,c.fdc_conv_to_basic_unit 
            FROM trwo a             
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
            INNER JOIN msitemunitdetails c on a.fin_item_id  = c.fin_item_id and a.fst_unit = c.fst_unit 
            where a.fst_active ='A' and a.fbl_closed = 0 and a.fst_wo_no like ?";
        $qr = $this->db->query($ssql,[$term]);

        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
	}
	
	public function ajxGetWOBatchNo(){
		$finWOId = $this->input->get("fin_wo_id");
		$ssql = "SELECT * FROM trwobatchno where fin_wo_id = ? and fbl_closed = 0 and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}

	public function ajxGetActivity(){
		$finWOId = $this->input->get("fin_wo_id");
		$ssql = "SELECT b.fin_activity_id,b.fst_name,b.fst_team,b.fst_type,b.fdc_cost_per_day FROM trwoactivitydetails a 
			INNER JOIN msactivity b on a.fin_activity_id = b.fin_activity_id
			WHERE a.fin_wo_id = ? and a.fst_active ='A' and b.fst_active = 'A'";
		$qr= $this->db->query($ssql,[$finWOId]);
		$rs = $qr->result();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>$rs
		]);
	}
}
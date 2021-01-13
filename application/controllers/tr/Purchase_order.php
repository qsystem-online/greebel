<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Purchase_order extends MY_Controller{
	public $menuName="purchase_order";

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('trpo_model');
		$this->load->model('trpodetails_model');		
		$this->load->model("msrelations_model");
		$this->load->model("mswarehouse_model");
		$this->load->model("msitemdiscounts_model");
		
		

	}
	public function index(){
		parent::index();
		$this->lizt();
	}
	public function lizt(){		
		$this->load->library('menus');
		parent::index();
        $this->list['page_name'] = "Purchase - Order";
        $this->list['list_name'] = "Order Pembelian List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/purchase_order/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase_order/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_po_no' => 'No Order Pembelian',
			'fst_supplier_name' => 'Supplier',
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Order', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. LPB Pembelian', 'width' => '10px','visible'=>'false', 'data' => 'fin_po_id'],
            ['title' => 'No. Order Pembelian', 'width' => '120px', 'data' => 'fst_po_no'],
            ['title' => 'Tanggal', 'width' => '80px', 'data' => 'fdt_po_datetime'],
            ['title' => 'Supplier', 'width' => '100px', 'data' => 'fst_supplier_name'],
			['title' => 'Memo', 'width' => '120px', 'data' => 'fst_memo'],
			['title' => 'Total', 'width' => '100px','className'=>'text-right',
				'render'=>"function(data,type,row){
					var total = parseFloat(row.fdc_subttl) - parseFloat(row.fdc_disc_amount) + parseFloat(row.fdc_ppn_amount);
					return row.fst_curr_code + ':' + App.money_format(total);
				}"
			],
			['title' => 'DP', 'width' => '80px', 'data' => 'fdc_downpayment','className' => 'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}"
			],
			['title' => 'DP paid', 'width' => '80px', 'data' => 'fdc_downpayment_paid','className' => 'text-right',
				'render'=>"function(data,type,row){
					return App.money_format(data);
				}"
			],
			['title' => 'Status', 'width' => '60px', 'data' => 'fst_active','className'=>'text-center',
				'render'=>"function(data,type,row){
					if(data == 'A'){
						return 'Active';
					}else if (data == 'S'){
						return 'Suspend';
					}else if (data == 'D'){
						return 'Deleted';
					}else if (data == 'R'){
						return 'Rejected';
					}
				}"
			],
			['title' => 'Closed', 'width' => '50px', 'data' => 'fbl_is_closed','className'=>'text-center',
				'render'=>"function(data,type,row){
					if(data == 1){
						return '<input class=\"isClosed\" type=\"checkbox\" value=\"1\" checked>';
					}else{
						return '<input class=\"isClosed\" type=\"checkbox\" value=\"0\" >';
					}					
				}"
			],
			
			['title' => 'Action', 'width' => '70px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/purchase_order/edit/' + row.fin_po_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<a class=\"btn-delete\" href=\"#\" data-id=\"\" data-toggle=\"confirmation\" ><i class=\"fa fa-trash\"></i></a>';
					action += '<div>';
					return action;
				}"
			]
		];

		$mdlPopupNotes = $this->parser->parse('template/mdlPopupNotes', [], true);
		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase_order/listjs', ["mdlPopupNotes"=>$mdlPopupNotes], true);

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
	private function openForm($mode = "ADD", $fin_po_id = 0,$fin_process_id = 0){
		$this->load->library("menus");		
		

		if ($this->input->post("submit") != "") {
			$this->add_save();
		}
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$mdlJurnal =$this->parser->parse('template/mdlJurnal.php', [], true);
		$mdlPrint =$this->parser->parse('template/mdlPrint.php', [], true);
		$data["mode"] = $mode;
		$data["title"] = $mode == "ADD" ? "Add Purchase Order" : "Update Purchase Order";
		$data["mdlJurnal"] = $mdlJurnal;
		$data["mdlPrint"] = $mdlPrint;
		if($mode == 'ADD'){
			$data["fin_po_id"] = 0;
			$data["fst_po_no"] = $this->trpo_model->GeneratePONo();			
			$data["fin_process_id"] = 0;
			$data["fin_supplier_id"] = 0;
		}else if($mode=="EDIT"){
			$data["fin_po_id"] = $fin_po_id;
			$data["fst_po_no"] = "";			
			$data["fin_process_id"] = 0;			
		}else if($mode == "VIEW"){
			$data["fin_po_id"] = $fin_po_id;
			$data["fst_po_no"] = "";			
			$data["fin_process_id"] = 0;
		}else if($mode == "GENERATE"){
			$data["fin_po_id"] = 0;
			$data["fst_po_no"] = $this->trpo_model->GeneratePONo();	
			$data["fin_process_id"] = $fin_process_id;
		}
		
		$page_content = $this->parser->parse('pages/tr/purchase_order/form', $data, true);
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
		parent::add();
		$this->openForm("ADD", 0);
	}
	public function edit($fin_po_id){
		parent::edit();
		$this->openForm("EDIT", $fin_po_id);
	}
	public function view($finPOId){
		$this->openForm("VIEW", $finPOId);
	}

	public function generate($fin_process_id){
		//echo $fin_process_id . ":" . $fin_supplier_id;
		$this->openForm("GENERATE",0,$fin_process_id);

	}

	public function ajx_add_save(){
		parent::ajx_add_save();

		try{

		
			//Prepare Data			
			$fdt_po_datetime = dBDateTimeFormat($this->input->post("fdt_po_datetime"));
			$resp = dateIsLock($fdt_po_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$dataPrepared = $this->prepareData();
			$fst_po_no = $this->trpo_model->GeneratePONo();

			$dataH = $dataPrepared["dataH"];
			$dataH["fst_po_no"]= $fst_po_no;

			$dataDetails =$dataPrepared["dataDetails"];

			//Validation
			$this->validateData($dataH,$dataDetails);

			//Save
			$this->db->trans_start();

			//Insert Data Header
			$insertId = $this->trpo_model->insert($dataH);
			
			//Insert Data Detail
			foreach ($dataDetails as $item) {
				$dataDetail = (array) $item;
				$dataDetail =[
					"fin_po_id"=>$insertId,
					"fin_item_id"=>$item->fin_item_id,
					"fst_custom_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"fdc_price"=>$item->fdc_price,
					"fst_disc_item"=>$item->fst_disc_item,
					"fdc_disc_amount"=>$item->fdc_disc_amount,
					"fdc_disc_amount_per_item"=> calculateDisc($item->fst_disc_item,$item->fdc_price),
					"fst_notes"=>$item->fst_notes,				
					"fst_active"=> 'A'
				];
				$this->trpodetails_model->insert($dataDetail);			
				$dbError  = $this->db->error();
				if ($dbError["code"] != 0){			
					$this->ajxResp["status"] = "DB_FAILED";
					$this->ajxResp["message"] = "Insert Failed";
					$this->ajxResp["data"] = $this->db->error();
					$this->json_output();
					$this->db->trans_rollback();
					return;
				}
			}

			//Posting
			$this->trpo_model->posting($insertId);
			
			$this->db->trans_complete();

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $insertId;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
		}
	}


	public function ajx_edit_save(){
		parent::ajx_edit_save();
		$this->load->model("trverification_model");
		
		try{
			$finPOId = $this->input->post("fin_po_id");
			//cek editable		
			//CEK tgl lock dari transaksi tersimpan
			$dataHOld = $this->trpo_model->getDataHeaderById($finPOId);
			$resp = dateIsLock($dataHOld->fdt_po_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"], null);
			}

			$fdt_po_datetime = dBDateTimeFormat($this->input->post("fdt_po_datetime"));		
			$resp = dateIsLock($fdt_po_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"], null);
			}

			$resp = $this->trpo_model->isEditable($dataHOld->fin_po_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"], null);
			}

			$this->db->trans_start();
			
			//unposting
			$this->trpo_model->unposting($dataHOld->fin_po_id);
			//$this->trverification_model->cancelAuthorize("PO",$finPOId);			
			$this->trpodetails_model->deleteByPOId($dataHOld->fin_po_id);

			$dataPrepared = $this->prepareData();

			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];

			$dataH["fin_po_id"]  = $dataHOld->fin_po_id;
			$dataH["fst_po_no"]  = $dataHOld->fst_po_no;
			$dataH["fst_active"] = $dataHOld->fst_active;


			$this->validateData($dataH,$dataDetails);

			$this->trpo_model->update($dataH);

			foreach ($dataDetails as $item) { //Insert Data Detail
				$dataDetail = (array) $item;
				$dataDetail =[
					"fin_po_detail_id"=>$item->fin_po_detail_id,
					"fin_po_id"=>$dataH["fin_po_id"],
					"fin_item_id"=>$item->fin_item_id,
					"fst_custom_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"fdb_qty_plb"=>$this->trpodetails_model->getQtyPLB($item->fin_po_detail_id),
					"fdc_price"=>$item->fdc_price,
					"fst_disc_item"=>$item->fst_disc_item,
					"fdc_disc_amount"=>$item->fdc_disc_amount,
					"fdc_disc_amount_per_item"=> calculateDisc($item->fst_disc_item,$item->fdc_price),
					"fst_notes"=>$item->fst_notes,				
					"fst_active"=> 'A'
				];
				$this->trpodetails_model->insert($dataDetail);
								
			}

			$this->trpo_model->posting($dataH["fin_po_id"]);
			$this->db->trans_complete();
			
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $dataH["fin_po_id"];
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();			
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
		}
		
	}


	public function prepareData(){
		
		$fdt_po_datetime = dBDateTimeFormat($this->input->post("fdt_po_datetime"));

		$fblDPIncPPN = $this->input->post("fbl_dp_inc_ppn");
		$fblDPIncPPN =  ($fblDPIncPPN == null) ? 0 : 1;
		$fstCurrCode =  $this->input->post("fst_curr_code");
		$fdcExchangeRateIdr = $this->input->post("fdc_exchange_rate_idr");

		if($fstCurrCode == null){
			$defaultCurr = getDefaultCurrency();
			$fstCurrCode = $defaultCurr["CurrCode"];
			$fdcExchangeRateIdr = 1;
		}		
		$fdcExchangeRateIdr = parseNumber($fdcExchangeRateIdr);

		$dataH = [
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fbl_is_import"=>$this->input->post("fbl_is_import"),
			"fst_po_no" => $this->input->post("fst_po_no"),
			"fdt_po_datetime" => $fdt_po_datetime,
			"fst_curr_code"=>$fstCurrCode,
			"fdc_exchange_rate_idr"=>$fdcExchangeRateIdr,
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
			"fin_term"=>$this->input->post("fin_term"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),	
			"fst_do_no" => $this->input->post("fst_do_no"),	
			"fst_contract_no" => $this->input->post("fst_contract_no"),	
			"fst_delivery_address" =>$this->input->post("fst_delivery_address"),			
			"fst_memo" =>$this->input->post("fst_memo"),
			"fdc_subttl"=>0,
			"fdc_disc_amount"=>0,
			"fdc_ppn_percent"=>$this->input->post("fdc_ppn_percent"),
			"fdc_ppn_amount"=>0,
			"fdc_downpayment"=>$this->input->post("fdc_downpayment"),
			"fdc_downpayment_paid"=>0,
			"fbl_dp_inc_ppn" => $fblDPIncPPN,
			"fbl_is_closed"=>0,
			"fin_pr_process_id"=>$this->input->post("fin_process_id"),
			"fst_active" => 'S' //semua purchase order harus di approve dulu
		];
		if ($dataH["fbl_is_import"]){
			$dataH["fdc_ppn_percent"] = 0;
			$dataH["fdc_ppn_amount"] = 0;
			$dataH["fbl_dp_inc_ppn"] = 0;			
		}

		$details = $this->input->post("detail");
		$details = json_decode($details);

		$total = 0;
		$discAmount= 0;
		$ppnAmount = 0;
		for($i = 0; $i < sizeof($details) ; $i++){
			$item = $details[$i];
			$tmpTtl = $item->fdb_qty * $item->fdc_price;
			$details[$i]->fdc_disc_amount = calculateDisc($item->fst_disc_item,$tmpTtl);			
			$total += $tmpTtl;
			$tmpDisc = calculateDisc($item->fst_disc_item,$tmpTtl);
			$discAmount += $tmpDisc;			
		}

		$dataH["fdc_subttl"] = $total - $discAmount;
		$dataH["fdc_disc_amount"] = $discAmount;
		$dataH["fdc_ppn_amount"] = $dataH["fdc_subttl"] * ($dataH["fdc_ppn_percent"] / 100);

		return [
			"dataH"=>$dataH,
			"dataDetails"=>$details
		];
	}
	
	public function validateData($dataH,$dataDetails){
		if($dataH["fdc_subttl"] <= 0){
			throw new CustomException("Total transaction is zero !",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array() );
		}

		$this->form_validation->set_rules($this->trpo_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Invalid data input !",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array() );
		}

		// Validate PO Details
		$this->form_validation->set_rules($this->trpodetails_model->getRules("ADD",0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		for($i = 0;$i < sizeof($dataDetails) ; $i++ ){
			$this->form_validation->set_data((array)$dataDetails[$i]);
			if ($this->form_validation->run() == FALSE){
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				throw new CustomException(lang("Error Validation Forms"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array(),$error);
			}
		}
	}


	public function fetch_list_data(){
		$this->load->library("datatables");
		$this->datatables->setTableName("(select a.*,b.fst_relation_name as fst_supplier_name from trpo a inner join msrelations b on a.fin_supplier_id = b.fin_relation_id ) a");
		$selectFields = "a.fin_po_id,a.fst_po_no,a.fdt_po_datetime,a.fst_memo,a.fst_supplier_name,a.fst_active,a.fdc_subttl,a.fdc_disc_amount,a.fdc_ppn_amount,a.fst_curr_code,a.fdc_downpayment,a.fdc_downpayment_paid,a.fbl_is_closed,'action' as action";
		$this->datatables->setSelectFields($selectFields);
		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";
		// Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDate = strtotime($data["fdt_po_datetime"]);						
			$data["fdt_po_datetime"] = date("d-M-Y",$insertDate);
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_po_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_po_id"] . "'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}


	public function fetch_data($fin_po_id){
		$data = $this->trpo_model->getDataById($fin_po_id);	
		$this->json_output($data);
	}
	public function delete($finPOId){
		parent::delete($finPOId);
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->db->trans_start();

		$result = $this->trpo_model->delete($finPOId);
		$this->db->trans_complete();
		if ($result["status"] ==  true){
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("PO Telah dihapus");		
		}else{
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = $result["message"];
		}
		$this->json_output();
	}

	public function get_msrelations(){
		$term = $this->input->get("term");
		$ssql = "select fin_relation_id, fst_relation_name,fin_sales_id,fin_warehouse_id,fin_terms_payment from msrelations where fin_branch_id = ? and fst_relation_name like ? and FIND_IN_SET(1,fst_relation_type)";
		$qr = $this->db->query($ssql,[$this->aauth->get_active_branch_id(),'%'.$term.'%']);
		//lastQuery();
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}
	public function get_mswarehouse(){
		$term = $this->input->get("term");
		$ssql = "select fin_warehouse_id, fst_warehouse_name from mswarehouse where fst_warehouse_name like ?";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}
	public function getValueFormInit($fin_salesorder_id){
		$salesDeptId = getDbConfig("sales_department_id");
		$activeBranchId = $this->aauth->get_active_branch_id();
		$ssql = "select fin_user_id, fst_username from users where  fin_branch_id =? and fin_department_id = $salesDeptId order by fst_username";
		$qr = $this->db->query($ssql,[$activeBranchId]);
		$rsSales = $qr->result();
		
		$ssql = "select fin_warehouse_id, fst_warehouse_name from mswarehouse where fin_branch_id =?";
		$qr = $this->db->query($ssql,[$activeBranchId]);
		$rsWarehouse = $qr->result();
	
		$rscurrencies = [];
		$rscurrencies[] = getDefaultCurrency();
		$ssql = "select fst_item_discount from msitemdiscounts where fst_active ='A'";
		$qr = $this->db->query($ssql,[]);
		$rsdiscount = $qr->result();
		$data = [
			"sales" => $rsSales,
			"warehouse" => $rsWarehouse,
			"currencies" => $rscurrencies,
			"discounts" => $rsdiscount,
			"min_date_time"=>getDbConfig("lock_transaction_date")
		];
		if ($fin_salesorder_id != 0){
			$this->load->model("trsalesorder_model");
			$tmp = $this->trsalesorder_model->getDataById($fin_salesorder_id);		
			$data["sales_order"] = $tmp["sales_order"];
			$data["so_details"] = $tmp["so_details"];
		}
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $data;
		$this->json_output();
	
	}
	public function get_sales(){
		$term = $this->input->get("term");
		$salesDeptId = getDbConfig("sales_department_id");
		$ssql = "select fin_user_id, fst_username from users where fst_username like ? and fin_department_id = $salesDeptId order by fst_username";
		$qr = $this->db->query($ssql,['%'.$term.'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}
	
	public function initVarForm($poId){
        $this->load->model("mswarehouse_model");
        $this->load->model("users_model");
        $this->load->library("select2");
        
        $branchId = $this->aauth->get_active_branch_id();
        
        //Get Data Supplier
		$arrSupplier = $this->select2->get_supplier($branchId);
		
		//Get Warehouse 
		$arrWarehouse = $this->select2->get_warehouse($branchId);
		
		//get List Disc
		$arrDisc = $this->select2->get_discountList();
		
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
			"arrSupplier"=>$arrSupplier,
			"arrWarehouse"=>$arrWarehouse,
			"arrDisc"=>$arrDisc
            //"arrSJ"=>$arrSJ,
        ];
        $this->json_output();
    }
	
	public function get_item(){
		$this->load->library("select2");

		$supplierId = $this->input->get("fin_supplier_id");
		$term = $this->input->get("term");		
		$arrItem = $this->select2->get_itemBySupplier($supplierId,$term);
		$this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
			"arrItem"=>$arrItem,
        ];
        $this->json_output();
	}
	public function get_item_unit($itemId){
		$this->load->library("select2");
		$arrUnit = $this->select2->get_buyItemUnit($itemId);
		$this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = [
			"arrUnit"=>$arrUnit,
        ];
        $this->json_output();
	}
	public function print_po(){
		$this->load->library("phpspreadsheet");
		//$spreadsheet = $this->phpspreadsheet->load(FCPATH . "assets/templates/template_sales_log.xlsx");
		//$spreadsheet = $this->phpspreadsheet->test();
		//die();
		$spreadsheet = $this->phpspreadsheet->load();
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		$sheet->getPageMargins()->setTop(1);
		$sheet->getPageMargins()->setRight(0.5);
		$sheet->getPageMargins()->setLeft(0.5);
		$sheet->getPageMargins()->setBottom(1);
		$sheet->setCellValue('A1', 'Hello World !'); 
		$filename = 'coba.xls';
		
		$this->phpspreadsheet->save($filename,$spreadsheet);
		
		/*
		var_dump($this->input->post("layoutColumn"));
		$arrLayoutCol = json_decode($this->input->post("layoutColumn"));
		var_dump($arrLayoutCol);
		//echo "PRINT......";
		*/
	}

	public function close_status_po($isClosed){
		$fstClosedNotes = $this->input->post("fst_closed_notes");
		$finPOId = $this->input->post("fin_po_id");
		try{
			
			$this->trpo_model->updateManualClosedStatus($isClosed,$finPOId,$fstClosedNotes);

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"] = [];
			$this->json_output();
			return;
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;			
		}
		
	}


	public function close_po_cost_list(){
		$this->menuName ="close_purchase_cost";
		parent::index();

		$this->load->library('menus');
        $this->list['page_name'] = "Purchase - Order";
        $this->list['list_name'] = "Order Pembelian List";
        $this->list['boxTools'] = [
			"<SELECT id='option-data'  style='margin-right:20px'><OPTION value='ALL'>ALL</OPTION><OPTION value='OPEN'>OPEN</OPTION><OPTION value='CLOSED'>CLOSED</OPTION></SELECT>"			
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase_order/fetch_close_cost_list_data';
        $this->list['arrSearch'] = [
			'fst_po_no' => 'No Order Pembelian',
			'fst_supplier_name' => 'Supplier',
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Order', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'ID. LPB Pembelian', 'width' => '10px','visible'=>'false', 'data' => 'fin_po_id'],
            ['title' => 'No. Order Pembelian', 'width' => '100px', 'data' => 'fst_po_no'],
            ['title' => 'Tanggal', 'width' => '80px', 'data' => 'fdt_po_datetime'],
            ['title' => 'Supplier', 'width' => '200px', 'data' => 'fst_supplier_name'],
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_memo'],
			['title' => 'Total', 'width' => '100px','className'=>'text-right',
				'render'=>"function(data,type,row){
					var total = parseFloat(row.fdc_subttl) - parseFloat(row.fdc_disc_amount) + parseFloat(row.fdc_ppn_amount);
					return row.fst_curr_code + ':' + App.money_format(total);
				}"
			],
		
			['title' => 'Cost Completed', 'width' => '50px', 'data' => 'fbl_cost_completed','className'=>'text-center',
				'render'=>"function(data,type,row){
					if(data == 1){
						return '<input class=\"isCostCompleted\" type=\"checkbox\" value=\"1\" checked>';
					}else{
						return '<input class=\"isCostCompleted\" type=\"checkbox\" value=\"0\" >';
					}					
				}"
			],
			['title' => 'Action', 'width' => '80px','className'=>'text-center',
				'render'=>"function(data,type,row){
					return '<a class=\'cost_detail\' href=\'#\'>Cost Detail</a>';
				}"
			],					
		];

		$mdlPopupNotes = $this->parser->parse('template/mdlPopupNotes', [], true);
		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase_order/list_close_cost_js', ["mdlPopupNotes"=>$mdlPopupNotes], true);

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

	public function fetch_close_cost_list_data(){
		$this->load->library("datatables");
		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$optionData = $this->input->get('optionData');
		
		$tableName = "";
		switch($optionData){
			case "ALL":
				$tableName = "(
					select a.*,b.fst_relation_name as fst_supplier_name from trpo a 
					inner join msrelations b on a.fin_supplier_id = b.fin_relation_id 
					where a.fbl_is_closed = true and a.fst_active = 'A'
				) a";
				break;				
			case "OPEN":
				$tableName = "(
					select a.*,b.fst_relation_name as fst_supplier_name from trpo a 
					inner join msrelations b on a.fin_supplier_id = b.fin_relation_id
					where a.fbl_is_closed = true and a.fst_active ='A' and a.fbl_cost_completed = false 
				) a";
				break;				
			case "CLOSED":
				$tableName = "(
					select a.*,b.fst_relation_name as fst_supplier_name from trpo a 
					inner join msrelations b on a.fin_supplier_id = b.fin_relation_id
					where a.fbl_is_closed = true and a.fst_active ='A' and a.fbl_cost_completed = true
				) a";
				break;

			default:
				$tableName = "(
					select a.*,b.fst_relation_name as fst_supplier_name from trpo a 
					inner join msrelations b on a.fin_supplier_id = b.fin_relation_id 
					where a.fbl_is_closed = true and a.fst_active ='A'
				) a";
		};

		$this->datatables->setTableName($tableName);
				
		$selectFields = "a.fin_po_id,a.fst_po_no,a.fdt_po_datetime,a.fst_memo,a.fst_supplier_name,a.fst_active,a.fdc_subttl,a.fdc_disc_amount,a.fdc_ppn_amount,a.fst_curr_code,a.fdc_downpayment,a.fdc_downpayment_paid,a.fbl_is_closed,a.fbl_cost_completed";
		$this->datatables->setSelectFields($selectFields);
		
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "fst_active !='D'";
		// Format Data	
		$datasources = $this->datatables->getData();
		
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDate = strtotime($data["fdt_po_datetime"]);						
			$data["fdt_po_datetime"] = date("d-M-Y",$insertDate);
			//action
			$data["action"]	= "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_po_id"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["fin_po_id"] . "'><i class='fa fa-trash'></i></a>
				</div>";
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function process_closing_cost($isCompleted){	
		$this->menuName ="close_purchase_cost";
		parent::ajx_add_save();
		 
		$finPOId = $this->input->post("fin_po_id");
		try{
			if($isCompleted){
				$this->db->trans_start();
				$this->trpo_model->completedCost($finPOId);
				$this->db->trans_complete();
			}else{
				$this->db->trans_start();
				$this->trpo_model->cancelCompletedCost($finPOId);
				$this->db->trans_complete();
			}

			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "";
			$this->ajxResp["data"]= [];
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"]= $e->getData();
			$this->json_output();
		}
	}

	public function get_detail_pr($finProcessId){
		$header = $this->trpo_model->getHeaderProcessPR($finProcessId);
		$detail = $this->trpo_model->getDetailPr($finProcessId);

		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>[
				"header"=>$header,
				"detail"=>$detail
			]
		]);
	}

	public function print_voucher($finPOId){
		$data = $this->trpo_model->getDataVoucher($finPOId);
		$data["title"] = "Purchase Order";	
		$this->data["title"]= $data["title"];	
		$page_content = $this->parser->parse('pages/tr/purchase_order/voucher', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;
		//$mpdf->simpleTables = true;
		$mpdf->WriteHTML($data);	
		//echo $data;
		$mpdf->Output();
	}

}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_request extends MY_Controller{
	public $menuName="purchase_request"; 
    public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('msdepartments_model');
		
		$this->load->model('trpurchaserequest_model');
		$this->load->model("trpurchaserequestitems_model");		
		$this->load->model('msitemdiscounts_model');
		$this->load->model("mslinebusiness_model");	

		
    }
	
	public function index(){
		parent::index();
		$this->load->library('menus');
        $this->list['page_name'] = "Purchase - Request";
        $this->list['list_name'] = "Purchase Request List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/purchase/purchase_request/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase/purchase_request/fetch_list_data';
        $this->list['arrSearch'] = [
			'fst_pr_no' => 'No Request',
			'fst_req_department_name' => 'Department'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Return', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'Id', 'width' => '10px','visible'=>'false', 'data' => 'fin_pr_id'],
            ['title' => 'Transaction No', 'width' => '120px', 'data' => 'fst_pr_no'],
            ['title' => 'Tanggal', 'width' => '120px', 'data' => 'fdt_pr_datetime'],
			['title' => 'Request By Department', 'width' => '200px', 'data' => 'fin_req_department_id',
				'render'=> "function(data,type,row){
					return row.fst_req_department_name;
				}"
			],
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_memo'],
			['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/purchase/purchase_request/edit/' + row.fin_pr_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];

		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase/request/listjs', [], true);

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



	public function add(){
		parent::add();
        $this->openForm("ADD", 0);
	}

	public function edit($finPRId){
		parent::edit($finPRId);
        $this->openForm("EDIT", $finPRId);
	}
	
	public function process(){
		$this->menuName = "process_pr";
		parent::index();
        $this->openForm("PROCESS", 0);
	}

	private function openForm($mode = "ADD", $finPRId = 0){
        $this->load->library("menus");			

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
		
		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Permintaan Pembelian") : lang("Update Permintaan Pembelian");
		$data["fin_pr_id"] = $finPRId;
		$data["mdlEditForm"] = $edit_modal;
		$mdlPrint =$this->parser->parse('template/mdlPrint.php', [], true);
		$data["mdlPrint"] = $mdlPrint;

		$data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();
		
		
		if($mode == 'ADD'){
			$data["fst_pr_no"]=$this->trpurchaserequest_model->generateTransactionNo(); 
			$data["mdlJurnal"] = "";
			$page_content = $this->parser->parse('pages/tr/purchase/request/form', $data, true);
		}else if($mode == 'EDIT'){
			$data["fst_pr_no"]="";	
			$data["mdlJurnal"] = $jurnal_modal;
			$data["mdlPrint"] = $mdlPrint;

			$page_content = $this->parser->parse('pages/tr/purchase/request/form', $data, true);
        } else if ($mode == 'PROCESS'){
			$stock_modal = $this->parser->parse('template/mdlStock', [], true);
			$data["title"] = $mode == "ADD" ? lang("Permintaan Pembelian") : lang("Proses Permintaan Pembelian");			
			$data["mdlStock"] = $stock_modal;

			$page_content = $this->parser->parse('pages/tr/purchase/request/frm_process', $data, true);
		}       
		
		
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
		$this->load->model("trpurchaserequestitems_model");		
		//CEK tgl lock dari transaksi yg di kirim
		try{
			$fdt_pr_datetime = dBDateTimeFormat($this->input->post("fdt_pr_datetime"));		
			$resp = dateIsLock($fdt_pr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$fst_pr_no = $this->trpurchaserequest_model->generateTransactionNo();

			$dataPrepared = $this->prepareData();			
			$dataH = $dataPrepared["dataH"];
			$dataH["fst_pr_no"] = $fst_pr_no;
			unset($dataH["fin_pr_id"]);

			$dataDetails = $dataPrepared["dataDetails"];
			
			
			$this->validateData($dataH,$dataDetails);

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			return;
		}
	
		try{
			//SAVE
			$this->db->trans_start(); 						
			$insertId = $this->trpurchaserequest_model->insert($dataH);
			
			foreach($dataDetails as $dataD){
				$dataD["fin_pr_id"] = $insertId;
				$this->trpurchaserequestitems_model->insert($dataD);
			}

			//POSTING
			$this->trpurchaserequest_model->posting($insertId);
			
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
			$this->db->trans_rollback();
			return;
		}
	}
	
	public function ajx_edit_save(){
		parent::ajx_edit_save();
		$finPRId = $this->input->post("fin_pr_id");
		try{
			
			//CEK if editable
			$dataHOld = $this->trpurchaserequest_model->getDataHeaderById($finPRId);
			if ($dataHOld == null){
				throw new CustomException(lang("ID Purchase Request tidak dikenal!",3003,"FAILED",["fin_purchasereturn_id"=>$finPRId]));
			}

			//CEK tgl lock dari transaksi tersimpan
			$resp = dateIsLock($dataHOld->fdt_pr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK tgl lock dari transaksi yg di kirim
			$fdt_pr_datetime = dBDateTimeFormat($this->input->post("fdt_pr_datetime"));		
			$fdt_publish_datetime = dBDateTimeFormat($this->input->post("fdt_publish_datetime"));		

			$resp = dateIsLock($fdt_pr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK iseditable 
			$resp = $this->trpurchaserequest_model->isEditable($dataHOld->fin_pr_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
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

			//UNPOSTING
			$this->trpurchaserequest_model->unposting($finPRId);

			//DELETE DETAIL DATA
			$this->trpurchaserequest_model->deleteDetail($finPRId);


			$dataPrepared = $this->prepareData();

			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];

			
			$dataH["fin_pr_id"] = $finPRId;
			$dataH["fst_pr_no"] = $dataHOld->fst_pr_no;

			//VALIDATION
			$this->validateData($dataH,$dataDetails);

			//SAVE
			$this->trpurchaserequest_model->update($dataH);
			foreach($dataDetails as $dataD){
				$dataD["fin_pr_id"] = $finPRId;
				$this->trpurchaserequestitems_model->insert($dataD);
			}

			//POSTING
			$this->trpurchaserequest_model->posting($finPRId);
						
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finPRId;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			$this->db->trans_rollback();
			return;
		}		
	}


	private function prepareData(){

		//PREPARE DATA
		
		$fdt_pr_datetime = dBDateTimeFormat($this->input->post("fdt_pr_datetime"));
		$fdt_publish_datetime = dBDateTimeFormat($this->input->post("fdt_publish_datetime"));
		
		
		
		$dataH =[
			"fin_pr_id"=>$this->input->post("fin_pr_id"),
			"fst_pr_no"=>$this->input->post("fst_pr_no"),
			"fdt_pr_datetime"=>$fdt_pr_datetime,
			"fdt_publish_datetime"=>$fdt_publish_datetime,
			"fst_memo"=>$this->input->post("fst_memo"), 
			"fin_req_department_id"=>$this->input->post("fin_req_department_id"),
			"fst_active"=>"A",
		];		

		$postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);
		$dataDetails = [];
		foreach($postDetails as $detail){
			$fdt_etd = dBDateFormat($detail->fdt_etd);
			$dataD = [
				"fin_rec_id"=>$detail->fin_rec_id,
				"fin_pr_id"=>$dataH["fin_pr_id"], 
				"fin_item_id"=>$detail->fin_item_id, 
				"fst_unit"=>$detail->fst_unit, 
				"fdb_qty_req"=>$detail->fdb_qty_req, 
				"fdt_etd"=>$fdt_etd, 
				"fst_memo"=>$detail->fst_memo, 
				"fst_active"=>"A"
			];
			$dataDetails[] = $dataD;			
		}
		return [
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails			
		];
	}

	private function validateData($dataH,$dataDetails){
		//validation header
		$this->form_validation->set_rules($this->trpurchaserequest_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		$this->form_validation->set_rules($this->trpurchaserequestitems_model->getRules("ADD", 0));		
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');		
		foreach ($dataDetails as $dataD){
			$this->form_validation->set_data($dataD);
			if ($this->form_validation->run() == FALSE) {
				$error = [
					"detail"=> $this->form_validation->error_string(),
				];
				throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",$error);
			}
		}


	}

	public function fetch_data($finPRId){
		$data = $this->trpurchaserequest_model->getDataById($finPRId);	
		if ($data["dataH"] == null){
			$resp = ["status"=>"FAILED","message"=>"DATA NOT FOUND !"];
		}else{
			$resp["status"] = "SUCCESS";
			$resp["message"] = "";
			$resp["data"] = $data;

		}		
		$this->json_output($resp);
	}

	public function delete($finPRId){
		parent::delete($finPRId);
		$dataHOld = $this->trpurchaserequest_model->getDataHeaderById($finPRId);
		//CEK tgl lock dari transaksi tersimpan
		$resp = dateIsLock($dataHOld->fdt_pr_datetime);
		if ($resp["status"] != "SUCCESS" ){
			$this->ajxResp["status"] = $resp["status"];
			$this->ajxResp["message"] = $resp["message"];
			$this->json_output();
			return;
		}

		$isEditable = $this->trpurchaserequest_model->isEditable($finPRId);
        if($isEditable["status"] != "SUCCESS"){
            return $isEditable;
		}
		
		try{

			$this->db->trans_start();
			$data =[];
			$this->trpurchaserequest_model->unposting($finPRId);               
			$this->trpurchaserequest_model->delete($finPRId,true,$data);
			//var_dump()
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

	
	public function fetch_list_data(){
		$this->load->library("datatables");
        $this->datatables->setTableName("(
			SELECT a.*,b.fst_department_name as fst_req_department_name 
			FROM trpurchaserequest a 
			INNER JOIN departments b on a.fin_req_department_id  = b.fin_department_id
			) a");

        $selectFields = "a.fin_pr_id,a.fst_pr_no,a.fdt_pr_datetime,a.fst_memo,a.fin_req_department_id,a.fst_req_department_name";
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

	public function fetch_history_list_data(){
		$finItemId = $this->input->get("fin_item_id");
		$fstUnit = $this->input->get("fst_unit");
		

		$this->db->select('a.fin_item_id,a.fst_unit,a.fdc_price,a.fdc_disc_amount_per_item,a.fdb_qty,b.fst_po_no,b.fdt_po_datetime,b.fin_supplier_id,b.fst_active,c.fst_relation_name as fst_supplier_name');
		$this->db->from('trpodetails a');
		$this->db->join('trpo b', 'a.fin_po_id = b.fin_po_id','INNER');
		$this->db->join('msrelations c', 'b.fin_supplier_id = c.fin_relation_id','INNER');
		$this->db->where(["a.fin_item_id" => $finItemId,"a.fst_unit" =>$fstUnit]);
		//$this->db->order_by('b.fdt_po_datetime', 'DESC');

		$ssql =  $this->db->get_compiled_select();
		//var_dump($ssql);
		//die();				
		$this->load->library("datatables");	

        $this->datatables->setTableName("($ssql) a");
        $selectFields = "fin_item_id,fst_unit,fdb_qty,fdc_price,fdc_disc_amount_per_item,fst_po_no,fdt_po_datetime,fin_supplier_id,fst_supplier_name";
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

	public function get_item_list(){
		$this->load->model("msitems_model");
		$term = $this->input->get("term");

		$rs =  $this->msitems_model->getAllList($term);
		$this->json_output([
			"itemList" => $rs
		]);
	}


	public function get_item_process_list(){
		$itemType = $this->input->get("fst_item_type");
		$lineBusinessId = $this->input->get("fst_linebusiness_id");
		//*stock|nonstock_umum|nonstock_pabrikasi
		$stockCostType = $this->input->get("fst_stock_cost_type");


		$rs = $this->trpurchaserequest_model->getItemProcessList($itemType,$lineBusinessId,$stockCostType);


		$result =[];
		foreach($rs as $rw){
			$key = $rw->fin_item_id ."_" .$rw->fst_unit;
			if (isset($result[$key])){
				$data = $result[$key];

				$data["fdb_qty_req"] +=  $rw->fdb_qty_req;
				$data["fst_detail_id"] .= "," . $rw->fin_rec_id;
				$details = $data["details"];
				array_push($details,[
					"fin_rec_id"=>$rw->fin_rec_id,
					"fin_pr_id"=>$rw->fin_pr_id,
					"fst_pr_no"=>$rw->fst_pr_no,
					"fdt_pr_datetime"=>$rw->fdt_pr_datetime,
					"fin_req_department_id"=>$rw->fin_req_department_id,
					"fst_req_department_name"=>$rw->fst_req_department_name,							
					"fin_item_id"=>$rw->fin_item_id,
					"fst_unit"=>$rw->fst_unit,
					"fdb_qty_req"=>$rw->fdb_qty_req,
					"fdb_qty_process"=>$rw->fdb_qty_process,
					"fdb_qty_distribute"=>$rw->fdb_qty_distribute,
					"fin_process_id"=>$rw->fin_process_id,
					"fdt_distribute_datetime"=>$rw->fdt_distribute_datetime,
					"fdt_etd"=>$rw->fdt_etd,
					"fst_memo"=>$rw->fst_memo
				]);

				$data["details"] = $details;
				$result[$key] = $data;
			}else{
				$result[$key] = [
					"fin_item_id"=> $rw->fin_item_id,
					"fst_item_code"=>  $rw->fst_item_code,
					"fst_item_name"=> $rw->fst_item_name,
					"fst_unit"=> $rw->fst_unit,
					"fdb_qty_req"=>$rw->fdb_qty_req,
					"fst_detail_id"=>$rw->fin_rec_id,
					"details"=>[
						[
							"fin_rec_id"=>$rw->fin_rec_id,
							"fin_pr_id"=>$rw->fin_pr_id,
							"fst_pr_no"=>$rw->fst_pr_no,
							"fdt_pr_datetime"=>$rw->fdt_pr_datetime,
							"fin_req_department_id"=>$rw->fin_req_department_id,
							"fst_req_department_name"=>$rw->fst_req_department_name,
							"fin_item_id"=>$rw->fin_item_id,
							"fst_unit"=>$rw->fst_unit,
							"fdb_qty_req"=>$rw->fdb_qty_req,
							"fdb_qty_process"=>$rw->fdb_qty_process,
							"fdb_qty_distribute"=>$rw->fdb_qty_distribute,
							"fin_process_id"=>$rw->fin_process_id,
							"fdt_distribute_datetime"=>$rw->fdt_distribute_datetime,
							"fdt_etd"=>$rw->fdt_etd,
							"fst_memo"=>$rw->fst_memo
						]
					]
						
				];
			}
		}
		

		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$result
		]);

	}
	
	public function ajx_process_pr(){
		$this->menuName = "process_pr";
		parent::ajx_add_save();

		$this->load->model("trpurchaserequestprocess_model");

		$fstItemType = $this->input->post("fst_item_type");
		$finLineBusinessId = $this->input->post("fin_linebusinness_id");
		$fstStockCostType = $this->input->post("fst_stock_cost_type");
		$finSupplierId = $this->input->post("fin_supplier_id");
		
		$dataH =[
			"fdt_process_datetime"=>date("Y-m-d H:i:s"),
			"fst_item_type"=>$fstItemType,
			"fin_linebusiness_id"=>$finLineBusinessId,
			"fst_stock_cost_type"=>$fstStockCostType,
			"fin_supplier_id"=>$finSupplierId,
			"fin_po_id"=>NULL,
			"fst_active"=> 'A'
		];
		
		try{		
			$this->db->trans_start(); 
			$processId = $this->trpurchaserequestprocess_model->insert($dataH);
			$list = $this->input->post("details");
			$list =  json_decode($list);
			$withPO = false;
			foreach($list as $listObj){
				$details = $listObj->details;			
				foreach($details as $dataD){				
					if ($dataD->fdb_qty_po > 0){
						$withPO = true;
					}
					$ssql ="update trpurchaserequestitems set fin_process_id = ?, fdb_qty_process = ?, fdb_qty_to_po = ?  where fin_rec_id = ?";
					$this->db->query($ssql,[$processId,$dataD->fdb_qty_process,$dataD->fdb_qty_po,$dataD->fin_rec_id]);
					$error = $this->db->error();
					if ($error["code"] != 0){
						throw new CustomException($error["message"],3003,"DB_FAILED",[]);
					}
				}			
			}
			$this->db->trans_complete();
			$this->json_output([
				"status"=>"SUCCESS",
				"message"=>"",
				"data"=>[
					"fin_process_id"=>$processId,
					"with_po"=>$withPO
				]			
			]);	
		}catch(CustomException $e){
			$this->db->trans_rollback();

			$this->json_output([
				"status"=>$e->getStatus(),
				"message"=>$e->getMessage()
			]);	
		}		
	}

	public function ajx_get_process_details($finProcessId){
		$this->load->model("trpurchaserequestprocess_model");
		$rs = $this->trpurchaserequestprocess_model->getDetailByProcessId($finProcessId);
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$rs			
		]);
	}

	public function ajx_cancel_process($finProcessId){
		$this->menuName = "process_pr";
		parent::delete($finProcessId);
		$this->load->model("trpurchaserequestprocess_model");
		
		$result=[
			"status"=>"SUCCESS",
			"message"=>""
		];

		try{
			$this->trpurchaserequestprocess_model->cancelProcess($finProcessId);			
		}catch(CustomException $e){
			$result["status"]=$e->getStatus();
			$result["message"]=$e->getMessage();
		}				
		$this->json_output($result);
	}

	public function fetch_list_processed_data(){
		$this->load->library("datatables");
		/*
		$this->datatables->setTableName("
			(
				SELECT a.fin_process_id,a.fin_po_id,b.fst_po_no,a.fdt_process_datetime,SUM(a.fdb_qty_process) AS fdb_qty_process,SUM(a.fdb_qty_to_po) AS fdb_qty_to_po,'A' AS fst_active 
				FROM trpurchaserequestitems a
				LEFT JOIN trpo b ON a.fin_po_id = b.fin_po_id 
				WHERE fin_process_id IS NOT NULL 
				GROUP BY a.fin_process_id,a.fin_po_id,b.fst_po_no,a.fdt_process_datetime ORDER BY  a.fdt_process_datetime  DESC
			) a");
		*/
		$this->datatables->setTableName("
			(
				SELECT 
					a.fin_process_id,
					a.fin_po_id,
					c.fst_po_no,
					a.fdt_process_datetime,
					a.fst_active,
					SUM(b.fdb_qty_process) AS fdb_qty_process,
					SUM(b.fdb_qty_to_po) AS fdb_qty_to_po 
				FROM trpurchaserequestprocess a
				INNER JOIN trpurchaserequestitems b on a.fin_process_id = b.fin_process_id
				LEFT JOIN trpo c on a.fin_po_id = c.fin_po_id
				GROUP BY a.fin_process_id ORDER BY  a.fdt_process_datetime  DESC
			) a");


        $selectFields = "a.*";
        $this->datatables->setSelectFields($selectFields);

        $Fields = '';
        $searchFields = [$Fields];
        $this->datatables->setSearchFields($searchFields);
        
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        //foreach ($arrData as $data) {        
        //    $arrDataFormated[] = $data;
        //}
		//$datasources["data"] = $arrDataFormated;
		$datasources["data"] = $arrData;
		$this->json_output($datasources);		
	}

	public function distribute(){
		$this->load->library('menus');
		$this->menuName = "pr_distibute";
		parent::index();

        $this->list['page_name'] = "Purchase Request Distribution";
        $this->list['list_name'] = "Distribution List";
        $this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/purchase/purchase_request/distribute_add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>",
		];
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/purchase/purchase_request/fetch_distribute_list_data';
        $this->list['arrSearch'] = [
			'fst_distributepr_no' => 'No Request',			
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Purchase', 'link' => '#', 'icon' => ''],
            ['title' => 'Return', 'link' => NULL, 'icon' => ''],
		];
		

        $this->list['columns'] = [
			['title' => 'Id', 'width' => '10px','visible'=>'false', 'data' => 'fin_distributepr_id'],
            ['title' => 'Transaction No', 'width' => '120px', 'data' => 'fst_distributepr_no'],
            ['title' => 'Tanggal', 'width' => '120px', 'data' => 'fdt_distributepr_datetime'],
			['title' => 'Memo', 'width' => '150px', 'data' => 'fst_distributepr_notes'],
			['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/purchase/purchase_request/distribute_edit/' + row.fin_distributepr_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
					action += '<div>';
					return action;
				}"
			]
		];
		$this->list['jsfile'] = $this->parser->parse('pages/tr/purchase/request/distribute_listjs', [], true);
		//$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		//$this->list['mdlEditForm'] = $edit_modal;
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

	public function fetch_distribute_list_data(){
		$this->load->library("datatables");
        $this->datatables->setTableName("(
			select * from trdistributepr 
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




	public function distribute_add(){
		$this->menuName = "pr_distibute";
		parent::add();
		
		$this->openDistributeForm("ADD");
	}

	public function distribute_edit($finDistributePRId){
		$this->menuName = "pr_distibute";
		parent::edit($finDistributePRId);
		
		$this->openDistributeForm("EDIT",$finDistributePRId);
	}

	private function openDistributeForm($mode = "ADD", $finDistributePRId = 0){
		$this->load->library("menus");		
		$this->load->model("trdistributepr_model")	;

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$edit_modal = $this->parser->parse('template/mdlEditForm', [], true);
		$jurnal_modal = $this->parser->parse('template/mdlJurnal', [], true);
		$mdlPrint = $this->parser->parse('template/mdlPrint', [], true);

		$data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? lang("Distrubusi Permohonan Pembelian") : lang("Update Distrubusi Permohonan Pembelian");
		$data["fin_distributepr_id"] = $finDistributePRId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;

		$data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();
		
		if($mode == 'ADD'){
			$data["fst_distributepr_no"]=$this->trdistributepr_model->generateTransactionNo(); 
			$data["mdlJurnal"] = "";
			$page_content = $this->parser->parse('pages/tr/purchase/request/distribution_form', $data, true);
		}else if($mode == 'EDIT'){
			$data["fst_distributepr_no"]= "";
			$data["mdlJurnal"] = $jurnal_modal;
			$page_content = $this->parser->parse('pages/tr/purchase/request/distribution_form', $data, true);
		}
		/*else if ($mode == 'PROCESS'){
			$stock_modal = $this->parser->parse('template/mdlStock', [], true);
			$data["title"] = $mode == "ADD" ? lang("Permintaan Pembelian") : lang("Proses Permintaan Pembelian");			
			$data["mdlStock"] = $stock_modal;

			$page_content = $this->parser->parse('pages/tr/purchase/request/frm_process', $data, true);
		}  
		*/     
		
		
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}
	
	public function ajx_get_need_to_distribute(){
		$this->load->model("trdistributepr_model");
		$rs = $this->trdistributepr_model->getNeedToDistribute();
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$rs
		]);
	}

	public function ajx_distribute_add_save(){		
		$this->menuName = "pr_distibute";
		parent::ajx_add_save();

		$this->load->model("trdistributepr_model");
		$this->load->model("trdistributepritems_model");			
		try{
			$fdt_distributepr_datetime = dBDateTimeFormat($this->input->post("fdt_distributepr_datetime"));		
			$resp = dateIsLock($fdt_distributepr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			$fst_distributepr_no =$this->trdistributepr_model->generateTransactionNo(); 
			$dataPrepared = $this->prepareDistributeData();	
			
			$dataH = $dataPrepared["dataH"];
			$dataH["fst_distributepr_no"] = $fst_distributepr_no;
			unset($dataH["fin_distributepr_id"]);
			$dataDetails = $dataPrepared["dataDetails"];						
			$this->validateDistributeData($dataH,$dataDetails);			
			//SAVE
			$this->db->trans_start(); 						
			$insertId = $this->trdistributepr_model->insert($dataH);
			
			foreach($dataDetails as $dataD){
				$dataD["fin_distributepr_id"] = $insertId;
				$dataD["fst_serial_number_list"] = json_encode($dataD["fst_serial_number_list"]);
				$this->trdistributepritems_model->insert($dataD);
			}

			//POSTING
			$this->trdistributepr_model->posting($insertId);
			
			$this->db->trans_complete();
			//$this->db->trans_rollback();
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

	public function ajx_distribute_edit_save(){
		$this->menuName = "pr_distibute";
		parent::ajx_edit_save();

		$this->load->model('trdistributepr_model');
		$this->load->model('trdistributepritems_model');

		$finDistributePRId = $this->input->post("fin_distributepr_id");

		try{
			
			//CEK if editable
			$dataHOld = $this->trdistributepr_model->getDataHeaderById($finDistributePRId);

			if ($dataHOld == null){
				throw new CustomException(lang("ID Distribute PR tidak dikenal!",3003,"FAILED",["fin_distributepr_id"=>$finDistributePRId]));
			}

			//CEK tgl lock dari transaksi tersimpan
			$resp = dateIsLock($dataHOld->fdt_distributepr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK tgl lock dari transaksi yg di kirim
			$fdt_distributepr_datetime = dBDateTimeFormat($this->input->post("fdt_distributepr_datetime"));					
			$resp = dateIsLock($fdt_distributepr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}

			//CEK iseditable 
			$resp = $this->trdistributepr_model->isEditable($dataHOld->fin_distributepr_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
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

			//UNPOSTING
			$this->trdistributepr_model->unposting($finDistributePRId);

			//DELETE DETAIL DATA
			$this->trdistributepr_model->deleteDetail($finDistributePRId);


			$dataPrepared = $this->prepareDistributeData();	
			$dataH = $dataPrepared["dataH"];
			$dataDetails = $dataPrepared["dataDetails"];
			
			$dataH["fin_distributepr_id"] = $finDistributePRId;
			$dataH["fst_distributepr_no"] = $dataHOld->fst_distributepr_no;

			//VALIDATION
			$this->validateDistributeData($dataH,$dataDetails);

			//SAVE
			$this->trdistributepr_model->update($dataH);

			foreach($dataDetails as $dataD){
				$dataD["fin_distributepr_id"] = $finDistributePRId;
				$dataD["fst_serial_number_list"] = json_encode($dataD["fst_serial_number_list"]);
				$this->trdistributepritems_model->insert($dataD);
			}

			//POSTING
			$this->trdistributepr_model->posting($finDistributePRId);
						
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finDistributePRId;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			$this->db->trans_rollback();
			return;
		}




	}

	public function ajx_delete_distribute($finDistributePRId){
		$this->menuName = "pr_distibute";
		parent::delete($finDistributePRId);


		$this->load->model('trdistributepr_model');
		$this->load->model('trdistributepritems_model');

		

		try{
			
			//CEK if editable
			$dataHOld = $this->trdistributepr_model->getDataHeaderById($finDistributePRId);

			if ($dataHOld == null){
				throw new CustomException(lang("ID Distribute PR tidak dikenal!",3003,"FAILED",["fin_distributepr_id"=>$finDistributePRId]));
			}

			//CEK tgl lock dari transaksi tersimpan
			$resp = dateIsLock($dataHOld->fdt_distributepr_datetime);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
			}
		
			//CEK iseditable 
			$resp = $this->trdistributepr_model->isEditable($dataHOld->fin_distributepr_id);
			if ($resp["status"] != "SUCCESS" ){
				throw new CustomException($resp["message"],3003,$resp["status"],null);
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

			//UNPOSTING
			$this->trdistributepr_model->unposting($finDistributePRId);

			//DELETE HEADER
			$this->trdistributepr_model->delete($finDistributePRId,true);

			//DELETE DETAIL DATA
			$this->trdistributepr_model->deleteDetail($finDistributePRId);

			
			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Deleted !";
			//$this->ajxResp["data"]= $finDistributePRId;
			$this->json_output();

		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();			
			$this->db->trans_rollback();
			return;
		}

	}


	public function ajx_fetch_distibution($finDistributionPRId){
		$this->load->model("trdistributepr_model");
		$data = $this->trdistributepr_model->getDataById($finDistributionPRId);
		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>$data
		]);

	}

	private function prepareDistributeData(){
		$fdt_distributepr_datetime = dBDateTimeFormat($this->input->post("fdt_distributepr_datetime"));
		$dataH = [
			"fin_distributepr_id"=>$this->input->post("fin_distributepr_id"),
			"fst_distributepr_no"=>$this->input->post("fst_distributepr_no"),
			"fdt_distributepr_datetime"=> $fdt_distributepr_datetime,
			"fst_distributepr_notes"=>$this->input->post("fst_distributepr_notes"),
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_active"=>"A",
		];

		$postDetails = $this->input->post("details");
		$postDetails = json_decode($postDetails);
		$dataDetails = [];
		foreach($postDetails as $detail){
			$dataD = [
				"fin_rec_id"=>$detail->fin_rec_id,
				"fin_distributepr_id"=>$dataH["fin_distributepr_id"], 
				"fin_pr_detail_id"=>$detail->fin_pr_detail_id, 
				"fdb_qty_distribute"=>$detail->fdb_qty_distribute, 
				"fin_source_warehouse_id"=>isset($detail->fin_source_warehouse_id) ? $detail->fin_source_warehouse_id : null, 
				"fst_batch_number"=>$detail->fst_batch_number,
				"fst_serial_number_list" =>$detail->fst_serial_number_list,
				"fst_notes"=>$detail->fst_notes, 
				"fst_active"=>"A"
			];
			$dataDetails[] = $dataD;			
		}
		return [
			"dataH"=>$dataH,
			"dataDetails"=>$dataDetails			
		];
	}

	private function validateDistributeData($dataH, $dataDetails){
		/**
		 * barang tipe logistic dan fbl_stock true harus menentukan source warehouse
		 */
		$this->load->model("msitems_model");
		$this->load->model("trinventory_model");

		foreach($dataDetails as $detail){			
			$ssql ="select a.*,b.fst_item_name,b.fin_item_type_id,b.fbl_stock,b.fbl_is_batch_number,b.fbl_is_serial_number from trpurchaserequestitems a 
				inner join msitems b on a.fin_item_id = b.fin_item_id
				where a.fin_rec_id  = ?";

			$qr =$this->db->query($ssql,[$detail["fin_pr_detail_id"]]);
			$item = $qr->row();
			
			
			 //Validation is valid batch number & serial number (qty, serial number exist)
			 //$item = $arrItem[$detail->fin_item_id];
			 if ($item->fbl_is_batch_number == 1){
				 if ($detail["fst_batch_number"] == null || $detail["fst_batch_number"] == ""){
					 throw new CustomException(sprintf(lang("%s harus memiliki batch number"),$item->fst_item_name),3009,"FAILED",null);
				 }
			 }
			 
			 if ($item->fbl_is_serial_number == 1){
				 if ($detail["fst_serial_number_list"] != null && $detail["fst_serial_number_list"] != ""){
					 if (is_array($detail["fst_serial_number_list"])){
						 $arrSerial = $detail["fst_serial_number_list"];
 
						 //Check Jumlah serial no
						 if (sizeof($arrSerial) != $this->msitems_model->getQtyConvertToBasicUnit($item->fin_item_id,$detail["fdb_qty_distribute"],$item->fst_unit) ){
							 throw new CustomException(sprintf(lang("total serial %s harus sesuai dengan total qty (%u)"),$item->fst_item_name,$detail["fdb_qty_distribute"]),3009,"FAILED",null);
						 
							}
						 //Check all serial is exist and ready;
						 $arrSerialStatus = $this->trinventory_model->getSummarySerialNo($detail["fin_source_warehouse_id"],$item->fin_item_id,$arrSerial);
						 foreach($arrSerial as $serial){
							 if (isset($arrSerialStatus[$serial]) ){
								 $serialStatus = $arrSerialStatus[$serial];
								 if ($serialStatus["fdb_qty_in"] <= $serialStatus["fdb_qty_out"] ){
									 throw new CustomException(sprintf(lang("No serial %s untuk item %s tidak tersedia"),$serial,$item->fst_item_name),3009,"FAILED",null);
								 }
							 }else{
								 throw new CustomException(sprintf(lang("No serial %s untuk item %s tidak tersedia"),$serial,$item->fst_item_name),3009,"FAILED",null);
							 }
						 }
					 }else{
						 throw new CustomException(sprintf(lang("%s harus memiliki serial number"),$detail->fst_custom_item_name),3009,"FAILED",null);
					 }
				 }
			 }

			 //Validation stock is available
			 if ($item->fbl_stock == 1){
                $basicUnit = $this->msitems_model->getBasicUnit($item->fin_item_id);
                $qtyStockBasicUnit = (float) $this->trinventory_model->getStock($item->fin_item_id,$basicUnit,$detail["fin_source_warehouse_id"]);
                $qtyReqInBasicUnit = $this->msitems_model->getQtyConvertUnit($item->fin_item_id,$detail["fdb_qty_distribute"],$item->fst_unit,$basicUnit);
                $qtyStockReqUnit =  $this->msitems_model->getQtyConvertUnit($item->fin_item_id,$qtyStockBasicUnit,$basicUnit,$item->fst_unit);
                if ($qtyReqInBasicUnit > $qtyStockBasicUnit ){
                    throw new CustomException(sprintf(lang("Stock %s tersisa : %d %s") ,$item->fst_item_name,$qtyStockReqUnit,$item->fst_unit),3009,"FAILED",null);
                }

            }
		
		}
	}

	public function print_voucher($finPRId){
		$data = $this->trpurchaserequest_model->getDataVoucher($finPRId);

		$data["title"]= "Purchase Request";
		$this->data["title"]= $data["title"];

		$page_content = $this->parser->parse('pages/tr/purchase/request/voucher_pr', $data, true);
		$this->data["PAGE_CONTENT"] = $page_content;
		$data = $this->parser->parse('template/voucher_pdf', $this->data, true);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		
		
		$mpdf->WriteHTML($data);	
		//$mpdf->SetHTMLHeaderByName('MyFooter');

		//echo $data;
		$mpdf->Output();

	}

	public function print_distribute_voucher($finDistributePRId){
		$this->load->model("trdistributepr_model");
		$this->data = $this->trdistributepr_model->getDataVoucher($finDistributePRId);
		//$data=[];
		$this->data["title"] = "Distribution Purchase Request";		
		$page_content = $this->parser->parse('pages/tr/purchase/request/voucher_distributepr', $this->data, true);
		$this->data["PAGE_CONTENT"] = $page_content;	

		
		$strHtml = $this->parser->parse('template/voucher_pdf', $this->data, true);

		//$this->parser->parse('template/voucher', $this->data);
		$mpdf = new \Mpdf\Mpdf(getMpdfSetting());		
		$mpdf->useSubstitutions = false;		
		//echo $strHtml;

		$mpdf->WriteHTML($strHtml);	
		$mpdf->Output();		
	}
}    
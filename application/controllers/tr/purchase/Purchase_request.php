<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_request extends MY_Controller{
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
        $this->openForm("ADD", 0);
	}

	public function edit($finPRId){
        $this->openForm("EDIT", $finPRId);
	}
	
	public function process(){
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

		$data["arrExchangeRate"] = $this->mscurrencies_model->getArrRate();
		
		
		if($mode == 'ADD'){
			$data["fst_pr_no"]=$this->trpurchaserequest_model->generateTransactionNo(); 
			$data["mdlJurnal"] = "";
			$page_content = $this->parser->parse('pages/tr/purchase/request/form', $data, true);
		}else if($mode == 'EDIT'){
			$data["fst_pr_no"]="";	
			$data["mdlJurnal"] = $jurnal_modal;
			$page_content = $this->parser->parse('pages/tr/purchase/request/form', $data, true);
        } else if ($mode == 'PROCESS'){
			$data["title"] = $mode == "ADD" ? lang("Permintaan Pembelian") : lang("Proses Permintaan Pembelian");
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
		
		
		$dataH =[
			"fin_pr_id"=>$this->input->post("fin_pr_id"),
			"fst_pr_no"=>$this->input->post("fst_pr_no"),
			"fdt_pr_datetime"=>$fdt_pr_datetime,
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
		$itemType = $this->input->get("fin_item_type_id");
		$lineBusinessId = $this->input->get("fst_linebusiness_id");

		$rs = $this->trpurchaserequest_model->getItemProcessList($itemType,$lineBusinessId);


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
					"fin_po_id"=>$rw->fin_po_id,
					"fdt_process_datetime"=>$rw->fdt_process_datetime,
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
							"fin_po_id"=>$rw->fin_po_id,
							"fdt_process_datetime"=>$rw->fdt_process_datetime,
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
		
		$processId = 0;
		$isExist = true;

		while ($isExist){
			$processId = rand(10000000,999999999);
			$ssql = "select * from trpurchaserequestitems where fin_process_id = ? and fin_po_id is null limit 1";
			$qr = $this->db->query($ssql,[$processId]);
			$rw = $qr->row();
			if ($rw == null){
				$isExist = false;

			}
		}

		$finSupplierId = $this->input->post("fin_supplier_id");
		$list = $this->input->post("details");
		$list =  json_decode($list);


		foreach($list as $listObj){
			$details = $listObj->details;			
			foreach($details as $dataD){
				$ssql ="update trpurchaserequestitems set fin_process_id = ?, fdb_qty_process = ? where fin_rec_id = ?";
				$this->db->query($ssql,[$processId,$dataD->fdb_qty_process,$dataD->fin_rec_id]);				
			}			
		}

		$this->json_output([
			"status"=>"SUCCESS",
			"message"=>"",
			"data"=>[
				"fin_process_id"=>$processId
			]			
		]);
	}
}    
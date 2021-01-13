<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Woe_out extends MY_Controller{
	public $menuName="woe_memo_out";
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		//$this->load->model("mscurrencies_model");
		//$this->load->model("msactivitygroups_model");
		$this->load->model("trmemowoeout_model");				
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "External Out";
		$this->list['list_name'] = "External Out List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/woe_out/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/woe_out/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_wo_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'Workorder', 'link' => null, 'icon' => '']			
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '30px', 'data' => 'fin_woeout_id'],
			['title' => 'No.', 'width' => '60px', 'data' => 'fst_woeout_no'],
			['title' => 'Tanggal', 'width' => '60px', 'data' => 'fdt_woeout_datetime'],
			['title' => 'WO', 'width' => '50px', 'data' => 'fst_wo_no'],
			['title' => 'Item', 'width' => '50px', 'data' => 'fst_item_name'],
			['title' => 'Qty WO', 'width' => '50px', 'data' => 'fdb_qty_wo'],
			['title' => 'Qty out', 'width' => '50px', 'data' => 'fdb_qty'],
			['title' => 'Action', 'width' => '50px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/woe_out/edit/' + row.fin_woeout_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
				SELECT a.*,b.fst_wo_no,b.fdb_qty as fdb_qty_wo,c.fst_item_code,c.fst_item_name FROM trmemowoeout a 
				INNER JOIN trwo b on a.fin_wo_id = b.fin_wo_id
				INNER JOIN msitems c on b.fin_item_id = c.fin_item_id 
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
		$data["title"] = $mode == "ADD" ? lang("Add Workorder") : lang("Update Workorder");
		$data["fin_woeout_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		$data["mdlJurnal"] = $jurnal_modal;			

		$fstWOEOutNo = $this->trmemowoeout_model->generateTransactionNo();
		$data["fst_woeout_no"] = $fstWOEOutNo;	
		$page_content = $this->parser->parse('pages/tr/production/woe_memo_out/form', $data, true);
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
			unset($dataH["fin_woeout_id"]);
			$dataH["fst_woeout_no"] = $this->trmemowoeout_model->generateTransactionNo();			
			$this->validateData($dataH);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();			
			$this->json_output();
			return;
		}		

		try{
			$this->db->trans_start();
			$insertId = $this->trmemowoeout_model->insert($dataH);
			//$this->trmemowoeout_model->posting($insertId);
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
        $finWOEOutId = $this->input->post("fin_woeout_id");
		try{
            $dataHOld = $this->trmemowoeout_model->getSimpleDataById($finWOEOutId);
            if ($dataHOld == null){
                show_404();
			}			
			$this->trmemowoeout_model->isEditable($finWOEOutId);			            
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
			$dataH["fin_woeout_id"] = $finWOEOutId;
			$dataH["fst_woeout_no"] = $dataHOld->fst_woeout_no;
			
			$this->db->trans_start();

			$ssql ="UPDATE trmemowoeout set fdb_qty = 0 where fin_woeout_id = ?";
			$this->db->query($ssql,[$finWOEOutId]);
			throwIfDBError();

			$this->validateData($dataH);

			$this->trmemowoeout_model->update($dataH);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finWOEOutId;
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
			"fin_woeout_id"=>$this->input->post("fin_woeout_id"),
			"fst_woeout_no"=>$this->input->post("fst_woeout_no"),
			"fdt_woeout_datetime"=>dBDateTimeFormat($this->input->post("fdt_woeout_datetime")),
			"fin_wo_id"=>$this->input->post("fin_wo_id"),			
			"fin_supplier_id"=>$this->input->post("fin_supplier_id"),
			"fdb_qty"=>$this->input->post("fdb_qty"),
			"fst_notes" => $this->input->post("fst_notes"),
			"fst_active"=>'A',
		];		

	
		return[
			"dataH"=>$dataH,
		];
		
	}
	
	private function validateData($dataH){
		$this->form_validation->set_rules($this->trmemowoeout_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}

		//CEK TOTAL WO VS TOTAL MEMO OUT
		$ssql = "SELECT a.fin_wo_id,a.fdb_qty,sum(b.fdb_qty) as ttl_out FROM trwo a
			INNER JOIN trmemowoeout b on a.fin_wo_id = b.fin_wo_id  
			where a.fin_wo_id = ? and b.fst_active = 'A' and a.fst_active ='A' ";
		$qr = $this->db->query($ssql,[$dataH["fin_wo_id"]]);
		$rw = $qr->row();
		if ($rw->fdb_qty < $rw->ttl_out + $dataH["fdb_qty"]){
			throw new CustomException("Qty keluar melebihi qty WO",3003,"VALIDATION_FORM_FAILED",["qty_wo"=> $rw->fdb_qty ,"qty_out"=>$rw->ttl_out]);
		}
	}

	public function fetch_data($finId){
		$data = $this->trmemowoeout_model->getDataById($finId);	
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
            $dataHOld = $this->trmemowoeout_model->getSimpleDataById($finId);
            if ($dataHOld == null){
                show_404();
			}
            $this->trmemowoeout_model->isEditable($finId);
		}catch(CustomException $e){
			$this->ajxResp["status"] = $e->getStatus();
			$this->ajxResp["message"] = $e->getMessage();
			$this->ajxResp["data"] = $e->getData();
			$this->json_output();
			return;
		}

		try{
			$this->db->trans_start();			
			$resp = $this->trmemowoeout_model->delete($finId,true,null);
			//$resp = $this->trwo_model->delete($finId,true,null);	
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



	public function ajxGetWOEList(){
        $term = $this->input->get("term");
        $term = "%$term%";
        $ssql ="SELECT a.fin_wo_id,a.fst_wo_no
			FROM trwo a
			where a.fst_wo_no like ? AND a.fst_wo_type = 'External' AND a.fbl_closed = 0 AND a.fst_active = 'A' ";

        $qr = $this->db->query($ssql,[$term]);
        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
	}
	
	public function ajxGetDetailWOE(){
		
        $finWOId = $this->input->get("fin_wo_id");

        $ssql = "SELECT a.fin_item_id,a.fst_unit,a.fdb_qty,a.fin_supplier_id,d.fst_relation_name,
			b.fst_item_name,b.fst_item_code,SUM(IFNULL(c.fdb_qty,0)) as fdb_ttl_out 
			FROM trwo a			
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			LEFT JOIN trmemowoeout c on a.fin_wo_id = c.fin_wo_id and c.fst_active  = 'A'
			INNER JOIN msrelations d on a.fin_supplier_id = d.fin_relation_id 
			WHERE a.fin_wo_id = ? and a.fst_active !='D' 
			GROUP BY a.fin_item_id,a.fst_unit,a.fdb_qty,a.fin_supplier_id,d.fst_relation_name,b.fst_item_name,b.fst_item_code";
		
		$qr = $this->db->query($ssql,[$finWOId]);      
		$dataH = $qr->row();

        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$dataH,
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

}    
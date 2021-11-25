<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Woe_in extends MY_Controller{
	public $menuName="woe_memo_in";
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("trmemowoein_model");				
	}

	public function index(){
		parent::index();
		$this->load->library('menus');
		$this->list['page_name'] = "External In";
		$this->list['list_name'] = "External In List";
		$this->list['boxTools'] = [
			"<a id='btnNew'  href='".site_url()."tr/production/woe_in/add' class='btn btn-primary btn-sm'><i class='fa fa-plus' aria-hidden='true'></i> New Record</a>"
		];
		$this->list['pKey'] = "id";
		$this->list['fetch_list_data_ajax_url'] = site_url() . 'tr/production/woe_in/fetch_list_data';
		$this->list['arrSearch'] = [
			'fst_woein_no' => 'No.',
		];

		$this->list['breadcrumbs'] = [
			['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
			['title' => 'Production', 'link' => '#', 'icon' => ''],
			['title' => 'Workorder', 'link' => null, 'icon' => '']			
		];
		

		$this->list['columns'] = [
			['title' => 'ID.', 'width' => '10px','visible' => 'false', 'data' => 'fin_woein_id'],
			['title' => 'No.', 'width' => '40px', 'data' => 'fst_woein_no'],
			['title' => 'Tanggal', 'width' => '80px', 'data' => 'fdt_woein_datetime'],
            //['title' => 'WO', 'width' => '50px', 'data' => 'fst_wo_no'],
            ['title' => 'Extrenal WO Out', 'width' => '40px', 'data' => 'fst_woeout_no'],
			['title' => 'Item', 'width' => '150px', 'data' => 'fst_item_name'],
            ['title' => 'Unit', 'width' => '30px', 'data' => 'fst_unit'],
            ['title' => 'Qty Balance', 'width' => '80px', 'data' => 'fdb_qty_woebalance'],
			['title' => 'Qty In', 'width' => '50px', 'data' => 'fdb_qty'],
			['title' => 'Action', 'width' => '80px', 'sortable' => false, 'className' => 'text-center',
				'render'=>"function(data,type,row){
					action = '<div style=\"font-size:16px\">';
					action += '<a class=\"btn-edit\" href=\"".site_url()."tr/production/woe_in/edit/' + row.fin_woein_id + '\" data-id=\"\"><i class=\"fa fa-pencil\"></i></a>&nbsp;';
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
                b.fst_woeout_no,b.fdb_qty - b.fdb_qty_in as fdb_qty_woebalance,
                c.fst_wo_no,c.fst_unit,
                d.fst_item_code,d.fst_item_name 
                FROM trmemowoein a 
                INNER JOIN trmemowoeout b on a.fin_woeout_id = b.fin_woeout_id
				INNER JOIN trwo c on b.fin_wo_id = c.fin_wo_id
				INNER JOIN msitems d on c.fin_item_id = d.fin_item_id 
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
		$data["fin_woein_id"] = $finId;
		$data["mdlEditForm"] = $edit_modal;
		$data["mdlPrint"] = $mdlPrint;				
		
		$fstWOEInNo = $this->trmemowoein_model->generateTransactionNo();
		$data["fst_woein_no"] = $fstWOEInNo;	
		$page_content = $this->parser->parse('pages/tr/production/woe_memo_in/form', $data, true);
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
			unset($dataH["fin_woein_id"]);
			$dataH["fst_woein_no"] = $this->trmemowoein_model->generateTransactionNo();			
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
            $insertId = $this->trmemowoein_model->insert($dataH);
            $this->trmemowoein_model->posting($insertId);
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
        $finWOEInId = $this->input->post("fin_woein_id");
		try{
            $dataHOld = $this->trmemowoein_model->getSimpleDataById($finWOEInId);
            if ($dataHOld == null){
                show_404();
			}			
			$this->trmemowoein_model->isEditable($finWOEInId);			            
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
			$dataH["fin_woein_id"] = $finWOEInId;
			$dataH["fst_woein_no"] = $dataHOld->fst_woein_no;
			
            $this->db->trans_start();
            $this->trmemowoein_model->unposting($finWOEInId);            
			$this->validateData($dataH);
            $this->trmemowoein_model->update($dataH);
            $this->trmemowoein_model->posting($finWOEInId);

			$this->db->trans_complete();
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = "Data Saved !";
			$this->ajxResp["data"]["insert_id"] = $finWOEInId;
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
			"fin_woein_id"=>$this->input->post("fin_woein_id"),
			"fst_woein_no"=>$this->input->post("fst_woein_no"),
			"fdt_woein_datetime"=>dBDateTimeFormat($this->input->post("fdt_woein_datetime")),
            "fin_woeout_id"=>$this->input->post("fin_woeout_id"),			
            "fin_wo_id"=>0,			
			"fdb_qty"=>$this->input->post("fdb_qty"),
			"fst_memo" => $this->input->post("fst_memo"),
			"fst_active"=>'A',
		];		

        $ssql = "SELECT * FROM trmemowoeout where fin_woeout_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$this->input->post("fin_woeout_id")]);
        $rw =  $qr->row();
        $dataH["fin_wo_id"] =  $rw->fin_wo_id;
		return[
			"dataH"=>$dataH,
		];
		
	}
	
	private function validateData($dataH){
		$this->form_validation->set_rules($this->trmemowoein_model->getRules("ADD", 0));
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		$this->form_validation->set_data($dataH);
		
		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			throw new CustomException("Error Validation Header",3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
		}
	}

	public function fetch_data($finId){
		$data = $this->trmemowoein_model->getDataById($finId);	
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



	public function ajxGetWOEList(){
        $term = $this->input->get("term");
        $term = "%$term%";

        $ssql ="SELECT a.fin_woeout_id,a.fst_woeout_no
			FROM trmemowoeout a
			where a.fst_woeout_no like ? AND a.fdb_qty_in < a.fdb_qty AND a.fbl_closed = 0 AND a.fst_active = 'A' ";

        $qr = $this->db->query($ssql,[$term]);
        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
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
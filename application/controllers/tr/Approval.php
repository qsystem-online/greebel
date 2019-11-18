<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval extends MY_Controller{

	public function __construct(){
		parent::__construct();
		
	}
	public function index(){
		$this->load->library("menus");		
        
        $main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);		
		$data["title"] = lang("Approval");
		
		$page_content = $this->parser->parse('pages/tr/approval', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
    }

    public function fetch_need_approval_list(){
		$this->load->library("datatables");
		
        $activeBranchId = $this->aauth->get_active_branch_id();
		$user = $this->aauth->user();

		$dateRange = parseDateRange($this->input->get('dateRange'));

        $this->datatables->setTableName(
			"(select a.*,b.fst_fullname as fst_user_name from trverification a 
			inner join users b on a.fin_insert_id = b.fin_user_id 
            where a.fst_verification_status = 'RV' 
            and a.fin_branch_id = ".$activeBranchId ." 
            and a.fin_department_id = ". $user->fin_department_id ." 
			and a.fin_user_group_id = ". $user->fin_group_id . "
			and a.fdt_insert_datetime >= '$dateRange[from]' and a.fdt_insert_datetime <= '$dateRange[to]') a "
        );

		$selectFields = "a.fin_rec_id,a.fst_controller,a.fin_transaction_id,a.fst_transaction_no,a.fst_message,a.fdt_insert_datetime,a.fst_user_name";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		//echo $this->db->last_query();
		//echo $this->db->last_query();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDate = strtotime($data["fdt_insert_datetime"]);						
			$data["fdt_insert_datetime"] = date("d-M-Y H:i:s",$insertDate);
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}
	
	public function fetch_hist_approval_list(){
		$this->load->library("datatables");
		
        $activeBranchId = $this->aauth->get_active_branch_id();
		$user = $this->aauth->user();

		$dateRange = parseDateRange($this->input->get('dateRange'));

        $this->datatables->setTableName(
			"(select a.*,b.fst_fullname as fst_user_name from trverification a 
			inner join users b on a.fin_insert_id = b.fin_user_id 
            where a.fst_verification_status in ('VF','RJ','VD')  
            and a.fin_branch_id = ".$activeBranchId ." 
            and a.fin_department_id = ". $user->fin_department_id ." 
			and a.fin_user_group_id = ". $user->fin_group_id . "
			and a.fdt_insert_datetime >= '$dateRange[from]' and a.fdt_insert_datetime <= '$dateRange[to]') a "
        );

		$selectFields = "a.fin_rec_id,a.fst_controller,a.fin_transaction_id,a.fst_transaction_no,a.fst_message,a.fdt_insert_datetime,a.fst_user_name";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";
		
		// Format Data
		$datasources = $this->datatables->getData();
		//echo $this->db->last_query();
		//echo $this->db->last_query();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			$insertDate = strtotime($data["fdt_insert_datetime"]);						
			$data["fdt_insert_datetime"] = date("d-M-Y H:i:s",$insertDate);
			$arrDataFormated[] = $data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
    }
    
    public function doApproval($finRecId){
        $this->load->model('trverification_model');
		$fstNotes = $this->input->post("fst_notes");
		$isApproved = $this->input->post("isApproved");

        $this->db->trans_start();
		$result = $this->trverification_model->approve($finRecId,$fstNotes,$isApproved);
		if( $result["status"] != "SUCCESS"){
			$this->ajxResp["status"] = $result["status"];
        	$this->ajxResp["message"] = $result["message"];
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}

        $this->db->trans_complete();		
		$this->ajxResp["status"] = $result["status"];
        $this->ajxResp["message"] = $result["message"];
        $this->json_output();
	}
	
	public function doReject($finRecId){
        $this->load->model('trverification_model');

        $this->db->trans_start();
        $result = $this->trverification_model->reject($finRecId);
        $this->db->trans_complete();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]=[];
        $this->json_output();
	}
	
	public function viewDetail($finRecId){
		$this->load->model('trverification_model');
		$this->trverification_model->showTransaction($finRecId);
	}


	
}
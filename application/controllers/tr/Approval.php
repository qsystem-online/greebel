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

        $this->datatables->setTableName(
            "(select * from trverification 
            where fst_verification_status = 'RV' 
            and fin_branch_id = ".$activeBranchId ." 
            and fin_department_id = ". $user->fin_department_id ." 
            and fin_user_group_id = ". $user->fin_group_id . ") a "
        );

		$selectFields = "a.fin_rec_id,a.fst_controller,a.fin_transaction_id,a.fst_transaction_no,a.fst_message,a.fdt_insert_datetime";
		$this->datatables->setSelectFields($selectFields);

		$searchFields =[];
		$searchFields[] = $this->input->get('optionSearch'); //["fin_salesorder_id","fst_salesorder_no"];
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";

		// Format Data
		$datasources = $this->datatables->getData();
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
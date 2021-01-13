<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Monitoring_sj extends MY_Controller{
	public $menuName="monitoring_sj"; 
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		parent::index();
		$this->load->library("menus");
	
        $main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);		
		$data["title"] = lang("Monitoring List");
		
		$page_content = $this->parser->parse('pages/adm_persediaan/monitoring_sj/monitoring_sj', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}
	
    public function fetch_monitoring_list(){
		$this->load->library("datatables");

		$useractive = $this->aauth->get_user_id();
		$user = $this->aauth->user();
		
		$this->datatables->setTableName("(SELECT a.*,
			ifnull(ifnull(b.fst_salesorder_no,c.fst_purchasereturn_no),d.fst_assembling_no) as fst_trans_no,
			ifnull(ifnull(b.fdt_salesorder_datetime,c.fdt_purchasereturn_datetime),d.fdt_assembling_datetime) as fdt_trans_datetime,
			e.fst_warehouse_name,f.fst_relation_name         
			FROM trsuratjalan a 
			LEFT JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id and a.fst_sj_type ='SO' 
			LEFT JOIN trpurchasereturn c on a.fin_trans_id = c.fin_purchasereturn_id and a.fst_sj_type ='PO_RETURN' 
			LEFT JOIN trassembling d on a.fin_trans_id = d.fin_assembling_id and a.fst_sj_type ='ASSEMBLING_OUT'
			LEFT JOIN mswarehouse e on a.fin_warehouse_id = e.fin_warehouse_id
			LEFT JOIN msrelations f on b.fin_relation_id = f.fin_relation_id
			WHERE a.fst_sj_return_resi_no is NULL ) a ");

		$selectFields = "a.fin_sj_id,a.fst_sj_no,a.fdt_sj_datetime,a.fst_warehouse_name,a.fst_trans_no,a.fdt_trans_datetime,
			a.fst_relation_name,a.fdt_sj_return_datetime,a.fst_sj_return_resi_no,a.fst_sj_return_memo,a.fin_sj_return_by_id,a.fbl_is_hold,a.fdt_unhold_datetime,a.fin_unhold_id";
		$this->datatables->setSelectFields($selectFields);

		$searchFields = [];
		$searchFields[] = $this->input->get('optionSearch');
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";

		//Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//$insertDate = strtotime($data["fdt_unhold_datetime"]);
			//$data["fdt_unhold_datetime"] = date("d-M-Y H:i:s",$insertDate);
			$arrDataFormated[] =$data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function doUnhold($sjId){
		$this->load->model('trsuratjalan_model');
        $this->db->trans_start();
        $this->trsuratjalan_model->unhold($sjId);
        $this->db->trans_complete();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Unhold Success !!!";
        $this->ajxResp["data"]=[];
        $this->json_output();
	}
	
	public function doUpdateResi(){
		$activeUser = $this->aauth->user();
		//print_r($activeUser);
		$fin_sj_id = $this->input->post("fin_sj_id");
		$this->load->model('trsuratjalan_model');
		$data = $this->trsuratjalan_model->getDataById($fin_sj_id);
		//$user = $data["user"];
		$data = [
			"fin_sj_id" => $fin_sj_id,
			"fin_sj_return_by_id" => $activeUser->fin_user_id,
            "fdt_sj_return_datetime" => dBDateTimeFormat($this->input->post("fdt_sj_return_datetime")),
            "fst_sj_return_resi_no" => $this->input->post("fst_sj_return_resi_no"),
            "fst_sj_return_memo" => $this->input->post("fst_sj_return_memo"),
		];
		$this->db->trans_start();
		$this->trsuratjalan_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}
		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Updated Resi !";
		$this->json_output();
	}

	//============================ HISTORIES ==========================\\

	public function fetch_histmonitoring_list(){
		$this->load->library("datatables");

		$useractive = $this->aauth->get_user_id();
		$user = $this->aauth->user();
		
		$this->datatables->setTableName("(SELECT a.*,
			ifnull(ifnull(b.fst_salesorder_no,c.fst_purchasereturn_no),d.fst_assembling_no) as fst_trans_no,
			ifnull(ifnull(b.fdt_salesorder_datetime,c.fdt_purchasereturn_datetime),d.fdt_assembling_datetime) as fdt_trans_datetime,
			e.fst_warehouse_name,f.fst_relation_name         
			from trsuratjalan a 
			LEFT JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id and a.fst_sj_type ='SO' 
			LEFT JOIN trpurchasereturn c on a.fin_trans_id = c.fin_purchasereturn_id and a.fst_sj_type ='PO_RETURN' 
			LEFT JOIN trassembling d on a.fin_trans_id = d.fin_assembling_id and a.fst_sj_type ='ASSEMBLING_OUT'
			LEFT JOIN mswarehouse e on a.fin_warehouse_id = e.fin_warehouse_id
			LEFT JOIN msrelations f on b.fin_relation_id = f.fin_relation_id
			where a.fst_sj_return_resi_no is NOT NULL ) a ");

			$selectFields = "a.fin_sj_id,a.fst_sj_no,a.fdt_sj_datetime,a.fst_warehouse_name,a.fst_trans_no,a.fdt_trans_datetime,
			a.fst_relation_name,a.fdt_sj_return_datetime,a.fst_sj_return_resi_no,a.fst_sj_return_memo,a.fin_sj_return_by_id,a.fbl_is_hold,a.fdt_unhold_datetime,a.fin_unhold_id";
		$this->datatables->setSelectFields($selectFields);

		$searchFields = [];
		$searchFields[] = $this->input->get('optionSearch2');
		$this->datatables->setSearchFields($searchFields);
		$this->datatables->activeCondition = "a.fst_active !='D'";

		//Format Data
		$datasources = $this->datatables->getData();
		$arrData = $datasources["data"];
		$arrDataFormated = [];
		foreach ($arrData as $data) {
			//$insertDate = strtotime($data["fdt_unhold_datetime"]);
			//$data["fdt_unhold_datetime"] = date("d-M-Y H:i:s",$insertDate);
			$arrDataFormated[] =$data;
		}
		$datasources["data"] = $arrDataFormated;
		$this->json_output($datasources);
	}

	public function doEditResi(){
		$activeUser = $this->aauth->user();
		//print_r($activeUser);
		$fin_sj_id = $this->input->post("fin_sj_id");
		$this->load->model('trsuratjalan_model');
		$data = $this->trsuratjalan_model->getDataById($fin_sj_id);
		//$user = $data["user"];
		$data = [
			"fin_sj_id" => $fin_sj_id,
			"fin_sj_return_by_id" => $activeUser->fin_user_id,
            "fdt_sj_return_datetime" => dBDateTimeFormat($this->input->post("fdt_sj_return_datetime")),
            "fst_sj_return_resi_no" => $this->input->post("fst_sj_return_resi_no"),
            "fst_sj_return_memo" => $this->input->post("fst_sj_return_memo"),
		];
		$this->db->trans_start();
		$this->trsuratjalan_model->update($data);
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0) {
			$this->ajxResp["status"] = "DB_FAILED";
			$this->ajxResp["message"] = "Insert Failed";
			$this->ajxResp["data"] = $this->db->error();
			$this->json_output();
			$this->db->trans_rollback();
			return;
		}
		$this->db->trans_complete();
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "Edit Resi !";
		$this->json_output();
	}

}
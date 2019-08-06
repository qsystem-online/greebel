<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_sj extends MY_Controller{

	public function __construct(){
		parent::__construct();
		
	}
	public function index(){
		$this->load->library("menus");
	
        $main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);		
		$data["title"] = lang("Monitoring Surat Jalan");
		
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

		//$this->datatables->setTableName("(select * from trsuratjalan where fbl_is_hold = '1' and fin_insert_id = $useractive) a ");
		$this->datatables->setTableName("(select a.*,b.fst_warehouse_name,c.fst_salesorder_no,c.fdt_salesorder_date,d.fst_relation_name from trsuratjalan a
			left join mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id
			left join trsalesorder c on a.fin_salesorder_id = c.fin_salesorder_id
			left join msrelations d on c.fin_relation_id = d.fin_relation_id
			where a.fbl_is_hold = '1' and a.fin_insert_id = $useractive ) a ");

		$selectFields = "a.fin_sj_id,a.fst_sj_no,a.fdt_sj_date,a.fst_warehouse_name,a.fst_salesorder_no,a.fdt_salesorder_date,
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
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]=[];
        $this->json_output();
	}

	public function doUpdate($sjId){
		$this->load->model('trsuratjalan_model');

        $this->db->trans_start();
        $this->trsuratjalan_model->update($sjId);
        $this->db->trans_complete();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]=[];
        $this->json_output();
	}
}
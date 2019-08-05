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
		$this->datatables->setTableName("(select a.*,b.fst_warehouse_name from trsuratjalan a
			left join mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id
			where a.fbl_is_hold = '1' and a.fin_insert_id = $useractive) a ");

		$selectFields = "a.fin_sj_id,a.fst_sj_no,a.fdt_sj_date,a.fst_warehouse_name,a.fst_sj_memo,a.fdt_unhold_datetime,a.fin_unhold_id";
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

	public function doUnhold($finSjId){
		$this->load->model('trsalesorder_model');

        $this->db->trans_start();
        $this->trsalesorder_model->unhold($finSjId);
        $this->db->trans_complete();
        
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]=[];
        $this->json_output();
    }
}
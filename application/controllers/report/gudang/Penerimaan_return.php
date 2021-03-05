<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_return extends MY_Controller
{

	public $menuName="report_penerimaan_return";
	public $layout_columns =[]; 
	public $spreadsheet;
	

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/trlpbsalesreturn_rpt_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('msrelations_model');
		$this->load->model('msitems_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'No.Penerimaan', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.Penerimaan', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Memo', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'GUD', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Kode Barang', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Nama Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Unit', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Invoice', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl Invoice', 'value'=>'9', 'selected'=>false,'sum_total'=>false],						

            ['layout' => 2, 'label'=>'No.Penerimaan', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.Penerimaan', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Memo', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'GUD', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Customer/Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Kode Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Nama Barang', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Qty', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Unit', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.Invoice', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl Invoice', 'value'=>'10', 'selected'=>false,'sum_total'=>false],


		];

	}

	public function index()
	{
		parent::index();
		$this->loadForm();
	}

	public function loadForm()
	{
		$this->load->library('menus');
						
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$fin_branch_id = 0;

		$this->data["fin_branch_id"] = $fin_branch_id;
		$this->data["mystatus"]="OK";
		$this->data["layout_columns"] = $this->layout_columns;
		$this->data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>0], true);        	
		$side_filter = $this->parser->parse('reports/penerimaan_return/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "PENERIMAAN BARANG RETURN PENJUALAN REPORT";
		$mode = "Report";
		// $this->data["mode"] = $mode;
		// $this->data["title"] = $mode == "ADD" ? "Add Branch" : "Update Branch";
		$report_filterbar = $this->parser->parse('inc/report_filterbar', $this->data, true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = null; // $this->parser->parse('template/standardList', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['REPORT_FILTERBAR'] = $report_filterbar;
		$this->data['REPORT_CONTENT'] = $page_content;
		$this->data['REPORT_FOOTER'] = $main_footer;
		$this->parser->parse('template/mainReport', $this->data);
	}

	//function ini untuk validasi form parameter report (jika ada parameter yg tidak boleh di kosongkan
	//sesuai di model)
	public function process()
	{
		// print_r('testing ajx-process');
		$this->load->model('trlpbsalesreturn_rpt_model');
		$this->form_validation->set_rules($this->trlpbsalesreturn_rpt_model->getRules());
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}


		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = "";
		$this->json_output();         
		
	}
	
	public function generateReport($isPreview = 1){		
		//var_dump($this->input->post());
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_customer_id" => $this->input->post("fin_customer_id"),
			"fin_item_id" => $this->input->post("fin_item_id"),
			"fdt_lpbsalesreturn_datetime"=>dbDateFormat($this->input->post("fdt_lpbsalesreturn_datetime")),
			"fdt_lpbsalesreturn_datetime2"=>dbDateFormat($this->input->post("fdt_lpbsalesreturn_datetime2")),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];		
		//$dataReport = $this->trlpbsalesreturn_rpt_model->queryComplete($data,$data['rpt_layout']);
		$dataReport = $this->trlpbsalesreturn_rpt_model->queryComplete($data,"a.fin_lpbsalesreturn_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/penerimaan_return/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/penerimaan_return/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/penerimaan_return/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
		}
		
		//echo "<div id='tstdiv'>Show Report</div>";//
	}

    public function get_customers(){
		$this->load->model("msrelations_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msrelations_model->getCustomerList();
		$this->json_output();
	}

    public function get_data_items(){
        /*$term = $this->input->get("term");
        $ssql = "select * from msitems where fst_item_name like ? order by fst_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

		$this->json_output($rs);*/
		
		$this->load->model("msitems_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msitems_model->getItemList_report();
		$this->json_output();

	}
	
	public function ajxListItem(){
		$this->load->model("msitems_model");
		$searchKey = $this->input->get("term");
		$result = $this->msitems_model->getAllList($searchKey,"fin_item_id,fst_item_code,fst_item_name");        
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=> $result
		]);
	}
}
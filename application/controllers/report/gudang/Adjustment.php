<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Adjustment extends MY_Controller
{

	public $menuName="report_adjustment";
	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/adjustment_rpt_model');
		$this->load->model('mswarehouse_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Adjustment', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tanggal', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Ref', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'GUD', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Kode Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nama Barang', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Unit', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'In/Out', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
		];

	}

	public function index()
	{
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
		

		$side_filter = $this->parser->parse('reports/adjustment_stock/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "ADJUSTMENT REPORT";
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
		$this->load->model('adjustment_rpt_model');
		$this->form_validation->set_rules($this->adjustment_rpt_model->getRules());
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
		//$activeBranchId = $this->session->userdata("active_branch_id");
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
            "fst_item_code" => $this->input->post("fst_item_code"),
            "fst_item_code2" => $this->input->post("fst_item_code2"),
			"fdt_adjustment_datetime" => $this->input->post("fdt_adjustment_datetime"),
			"fdt_adjustment_datetime2" => $this->input->post("fdt_adjustment_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->adjustment_rpt_model->queryComplete($data,"a.fst_adjustment_no",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		if ($data['rpt_layout'] == 1){
			$this->parser->parse('reports/adjustment_stock/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 2){
			$this->parser->parse('reports/adjustment_stock/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 3){
			$this->parser->parse('reports/adjustment_stock/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}
		
		//echo "<div id='tstdiv'>Show Report</div>";//
    }


}

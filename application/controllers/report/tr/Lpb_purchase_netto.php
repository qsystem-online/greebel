<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lpb_purchase_netto extends MY_Controller{

	public $menuName="report_purchase_netto";
	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/trlpbpurchase_netto_rpt_model');
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.LPB', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.LPB', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.J/T', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.P/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.P/O', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Supplier', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'M.U', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Rate', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nilai Faktur', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Klaim DP', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Total Retur', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Nilai Netto', 'value'=>'13', 'selected'=>false,'sum_total'=>true],


			['layout' => 2, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.LPB', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.LPB', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.J/T', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.P/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.P/O', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'M.U', 'value'=>'7', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Rate', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Nilai Faktur', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Klaim DP', 'value'=>'10', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Total Retur', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'Nilai Netto', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'No.Pembayaran', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Tgl.Bayar', 'value'=>'14', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Pembayaran', 'value'=>'15', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Outstanding', 'value'=>'16', 'selected'=>false,'sum_total'=>true],
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
		$this->data["default_currency"] = getDefaultCurrency();
		

		$side_filter = $this->parser->parse('reports/lpb_purchase_netto/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "LPB PURCHASE NETTO REPORT";
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
		$this->load->model('trlpbpurchase_netto_rpt_model');
		$this->form_validation->set_rules($this->trlpbpurchase_netto_rpt_model->getRules());
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
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
            "fst_curr_code" => $this->input->post("fst_curr_code"),
			"fdt_lpbpurchase_datetime" => $this->input->post("fdt_lpbpurchase_datetime"),
			"fdt_lpbpurchase_datetime2" => $this->input->post("fdt_lpbpurchase_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->trlpbpurchase_netto_rpt_model->queryComplete($data,"a.fin_lpbpurchase_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}
		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/lpb_purchase_netto/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/lpb_purchase_netto/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/lpb_purchase_netto/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
		}
		
		//echo "<div id='tstdiv'>Show Report</div>";//
	}

	public function get_suppliers(){
		$this->load->model("msrelations_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msrelations_model->getSupplierList();
		$this->json_output();
	}


}

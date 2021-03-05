<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ekspedisi extends MY_Controller
{

	public $menuName="report_ekspedisi";
	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/trsalesekspedisi_rpt_model');
		$this->load->model('msrelations_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Ekspedisi', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.Ekspedisi', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Ekspedisi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Customer/Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Ref', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty Kodi', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Harga/Kodi', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Ppn', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'No.Faktur Pajak', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 1, 'label'=>'Lain2', 'value'=>'10', 'selected'=>false,'sum_total'=>true],
            ['layout' => 1, 'label'=>'Total', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
            ['layout' => 1, 'label'=>'A/R', 'value'=>'12', 'selected'=>false,'sum_total'=>true],

			['layout' => 2, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.Ekspedisi', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.Ekspedisi', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Ekspedisi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Customer/Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.Ref', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Qty Kodi', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Harga/Kodi', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Ppn', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'No.Faktur Pajak', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'Lain2', 'value'=>'10', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'Total', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'A/R', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'S/J', 'value'=>'13', 'selected'=>false,'sum_total'=>true],

			['layout' => 3, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Ekspedisi', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl.Ekspedisi', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Ekspedisi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Customer/Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Ref', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Total', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'A/R', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Pembayaran', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 3, 'label'=>'Tgl.Pembayaran', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 3, 'label'=>'Pembayaran', 'value'=>'10', 'selected'=>false,'sum_total'=>true],
            ['layout' => 3, 'label'=>'Outstanding', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
            
			['layout' => 4, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.Ekspedisi', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Tgl.Ekspedisi', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Ekspedisi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Customer/Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.Ref', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Total', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'A/R', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.Pembayaran', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 4, 'label'=>'Tgl.Pembayaran', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 4, 'label'=>'Pembayaran', 'value'=>'10', 'selected'=>false,'sum_total'=>true],
            ['layout' => 4, 'label'=>'Outstanding', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
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
		

		$side_filter = $this->parser->parse('reports/ekspedisi/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "EKSPEDISI REPORT";
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
		$this->load->model('trsalesekspedisi_rpt_model');
		$this->form_validation->set_rules($this->trsalesekspedisi_rpt_model->getRules());
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
			"fin_customer_id" => $this->input->post("fin_customer_id"),
			"fin_ekspedisi_id" => $this->input->post("fin_ekspedisi_id"),
			"fdt_salesekspedisi_datetime" => $this->input->post("fdt_salesekspedisi_datetime"),
			"fdt_salesekspedisi_datetime2" => $this->input->post("fdt_salesekspedisi_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->trsalesekspedisi_rpt_model->queryComplete($data,"a.fin_salesekspedisi_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/ekspedisi/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/ekspedisi/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "3":
				$this->parser->parse('reports/ekspedisi/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "4":
				$this->parser->parse('reports/ekspedisi/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/ekspedisi/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
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
	public function get_ekspedisi(){
		$this->load->model("msrelations_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msrelations_model->getEkspedisiList();
		$this->json_output();
	}


}

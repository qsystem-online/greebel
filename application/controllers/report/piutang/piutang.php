<?php
defined('BASEPATH') or exit('No direct script access allowed');

class piutang extends MY_Controller
{

	public $menuName="report_piutang";
	public $layout_columns =[]; 
	public $spreadsheet;
	

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/piutang_rpt_model');
		$this->load->model('users_model');
		$this->load->model('kasbank_model');
		$this->load->model('mscurrencies_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tanggal', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'SRC', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Transaksi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Referensi', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Debit', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Credit', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Saldo', 'value'=>'7', 'selected'=>false,'sum_total'=>false],						

            ['layout' => 2, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'ID', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Customer/Pelanggan', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Saldo Awal', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Debit', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Credit', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Saldo Akhir', 'value'=>'6', 'selected'=>false,'sum_total'=>false],

			['layout' => 3, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl J/T', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Customer/Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Nilai Faktur', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
            ['layout' => 3, 'label'=>'Klaim DP', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Alokasi Retur', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Kasbank', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Pembayaran', 'value'=>'10', 'selected'=>false,'sum_total'=>false],

			['layout' => 4, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Tgl', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Customer/Pelanggan', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.Faktur', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Nilai Faktur', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
            ['layout' => 4, 'label'=>'Klaim DP', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Alokasi Retur', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.Kasbank', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Tgl', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Pembayaran', 'value'=>'9', 'selected'=>false,'sum_total'=>false],


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
		$this->data["default_currency"] = getDefaultCurrency();
		$this->data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>0], true);        	
		$side_filter = $this->parser->parse('reports/piutang/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "PIUTANG DAGANG REPORT";
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
		$this->load->model('piutang_rpt_model');
		$this->form_validation->set_rules($this->piutang_rpt_model->getRules());
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
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"kasbank_id" => $this->input->post("kasbank_id"),
            "fst_orgi_curr_code" => $this->input->post("fst_orgi_curr_code"),
			"fdt_trx_datetime"=>dbDateFormat($this->input->post("fdt_trx_datetime")),
			"fdt_trx_datetime2"=>dbDateFormat($this->input->post("fdt_trx_datetime2")),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];		
		$dataReport = $this->piutang_rpt_model->queryComplete($data,"b.fin_rec_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/piutang/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/piutang/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "3":
				$this->parser->parse('reports/piutang/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "4":
				$this->parser->parse('reports/piutang/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/piutang/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
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
}
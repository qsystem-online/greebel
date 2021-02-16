<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_outstanding extends MY_Controller
{

	public $menuName="report_invoice_outstanding";
	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/invoice_outstanding_rpt_model');
		$this->load->model('users_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');
		$this->load->model("msarea_model");

		$this->layout_columns = [
			['layout' => 1, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Sales', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'M.U', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nilai Faktur', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Total Retur', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Pembayaran', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Nilai Netto', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 1, 'label'=>'Outstanding', 'value'=>'10', 'selected'=>false,'sum_total'=>true],

			['layout' => 2, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Sales', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'M.U', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Outstanding', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Pembayaran', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Selisih', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'No.Pembayaran', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'Tgl.Pembayaran', 'value'=>'10', 'selected'=>false,'sum_total'=>true],

			['layout' => 3, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Pelanggan', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Sales', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'M.U', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Outstanding', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Pembayaran', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 3, 'label'=>'Selisih', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
            ['layout' => 3, 'label'=>'No.Pembayaran', 'value'=>'10', 'selected'=>false,'sum_total'=>true],
            ['layout' => 3, 'label'=>'Tgl.Pembayaran', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
            
			['layout' => 4, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.S/J', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.S/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'GUD', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Sales', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Pelanggan/Customer', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'M.U', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
			['layout' => 4, 'label'=>'Nilai Faktur', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Total Retur', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Pembayaran', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 4, 'label'=>'Nilai Netto', 'value'=>'13', 'selected'=>false,'sum_total'=>true],

			['layout' => 5, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.S/J', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.S/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'GUD', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Sales', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Pelanggan/Customer', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'M.U', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
			['layout' => 5, 'label'=>'Nilai Faktur', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Total Retur', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Pembayaran', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 5, 'label'=>'Nilai Netto', 'value'=>'13', 'selected'=>false,'sum_total'=>true],
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
		

		$side_filter = $this->parser->parse('reports/invoice_outstanding/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "FAKTUR PENJUALAN OUTSTANDING PEMBAYARAN REPORT";
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
		$this->load->model('invoice_outstanding_rpt_model');
		$this->form_validation->set_rules($this->invoice_outstanding_rpt_model->getRules());
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
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_item_id" => $this->input->post("fin_item_id"),
			"fst_curr_code" => $this->input->post("fst_curr_code"),
			"fdt_inv_datetime" => $this->input->post("fdt_inv_datetime"),
			"fdt_inv_datetime2" => $this->input->post("fdt_inv_datetime2"),
            "fdt_due_datetime" => $this->input->post("fdt_due_datetime"),
			"fbl_is_vat_include" => $this->input->post("fbl_is_vat_include"),
			"fst_area_code" => $this->input->post("fst_kode"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->invoice_outstanding_rpt_model->queryComplete($data,"b.fst_inv_no",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/invoice_outstanding/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/invoice_outstanding/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "3":
				$this->parser->parse('reports/invoice_outstanding/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "4":
				$this->parser->parse('reports/invoice_outstanding/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/invoice_outstanding/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
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

	public function get_provinces(){
		$term = $this->input->get("term");
		$term = "%".$term."%";
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 0 AND fst_nama like ? ";
		$qr = $this->db->query($ssql,[$term]);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_district($fst_kode){
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 1 and fst_kode like ? ";
		$qr = $this->db->query($ssql,[$fst_kode .'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_subdistrict($fst_kode){
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 2 and fst_kode like ? ";
		$qr = $this->db->query($ssql,[$fst_kode .'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}

	public function get_village($fst_kode){
		$ssql = "SELECT * FROM msarea WHERE LENGTH(fst_kode) - LENGTH(REPLACE(fst_kode, '.', '')) = 3 and fst_kode like ? ";
		$qr = $this->db->query($ssql,[$fst_kode .'%']);
		$rs = $qr->result();
		
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $rs;
		$this->json_output();
	}


}

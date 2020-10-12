<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends MY_Controller
{

	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/invoice_rpt_model');
		$this->load->model('users_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('msrelations_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'Pelanggan/Customer', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Jatuh Tempo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.S/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'GUD', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Sales', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Memo', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Kode Barang', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nama Barang', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Unit', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Disc%', 'value'=>'13', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Harga', 'value'=>'14', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Jumlah', 'value'=>'15', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.S/O', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'GUD', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Sales', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Pelanggan/Customer', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'M.U', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Subtotal', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Rate', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Total IDR', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 3, 'label'=>'Pelanggan/Customer', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Kode Barang', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Nama Barang', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Jumlah', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Sales', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Kode Barang', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Nama Barang', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Qty', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Unit', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Jumlah', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Sales', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Pelanggan/Customer', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Tgl.Faktur', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Jatuh Tempo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.S/J', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.S/O', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'GUD', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'M.U', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Nilai Faktur', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
			['layout' => 5, 'label'=>'Total Retur', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Pembayaran', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Nilai Netto', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 5, 'label'=>'Menunggak(hari)', 'value'=>'13', 'selected'=>false,'sum_total'=>true],
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
		

		$side_filter = $this->parser->parse('reports/invoice/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "FAKTUR PENJUALAN REPORT";
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
		$this->load->model('invoice_rpt_model');
		$this->form_validation->set_rules($this->invoice_rpt_model->getRules());
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
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fin_item_id" => $this->input->post("fin_item_id"),
			"fdt_inv_datetime" => $this->input->post("fdt_inv_datetime"),
			"fdt_inv_datetime2" => $this->input->post("fdt_inv_datetime2"),
			"fbl_is_vat_include" => $this->input->post("fbl_is_vat_include"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->invoice_rpt_model->queryComplete($data,"a.fst_inv_no",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		/*if ($data['rpt_layout'] == 1){
			$this->parser->parse('reports/invoice/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 2){
			$this->parser->parse('reports/invoice/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 3){
			$this->parser->parse('reports/invoice/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 4){
			$this->parser->parse('reports/invoice/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}*/
		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/invoice/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/invoice/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "3":
				$this->parser->parse('reports/invoice/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "4":
				$this->parser->parse('reports/invoice/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "5":
				$this->parser->parse('reports/invoice/layout5', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/invoice/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
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

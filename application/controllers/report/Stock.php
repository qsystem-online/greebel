<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends MY_Controller
{

	public $menuName="report_stock";
	public $layout_columns =[]; 
	public $spreadsheet;
	

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/stock_rpt_model');
		$this->load->model('mslinebusiness_model');
		$this->load->model('mswarehouse_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tanggal', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Keterangan', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No. Transaksi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Referensi', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Awal', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Masuk', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Keluar', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Sisa', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Unit', 'value'=>'9', 'selected'=>false,'sum_total'=>false],						

			['layout' => 2, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tanggal', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Keterangan', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No. Transaksi', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Referensi', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Q.Awal', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Q.Masuk', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Q.Keluar', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Harga', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Jumlah', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Q.Sisa', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Unit', 'value'=>'11', 'selected'=>false,'sum_total'=>false],

			['layout' => 3, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 3, 'label'=>'Item Code', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Item Name', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Q.Awal', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Q.Masuk', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Q.Keluar', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Q.Sisa', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'7', 'selected'=>false,'sum_total'=>false],

			['layout' => 4, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 4, 'label'=>'Item Code', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Item Name', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Q.Sisa', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Unit', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Harga Jual', 'value'=>'5', 'selected'=>false,'sum_total'=>false],

			['layout' => 5, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 5, 'label'=>'Item Code', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Item Name', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Q.Awal', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Q.Masuk', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Q.Keluar', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Q.Sisa', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Unit', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Nilai', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Jumlah', 'value'=>'9', 'selected'=>false,'sum_total'=>false],

			['layout' => 6, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 6, 'label'=>'Item Code', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'Item Name', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'Unit', 'value'=>'3', 'selected'=>false,'sum_total'=>false],

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
		$side_filter = $this->parser->parse('reports/stock/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "INVENTORY REPORT";
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
		//$this->load->model('items_rpt_model');
		$this->form_validation->set_rules($this->stock_rpt_model->getRules());
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
			"fin_item_group_id" => $this->input->post("fin_item_group_id"),
			"fin_item_type_id" => $this->input->post("fin_item_type_id"),
			"fin_warehouse_id"=> $this->input->post("fin_warehouse_id"),
			"fdt_from"=>dbDateFormat($this->input->post("fdt_from")),
			"fdt_to"=>dbDateFormat($this->input->post("fdt_to")),
			"fin_item_id" => $this->input->post("fin_item_id"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];		
		$dataReport = $this->stock_rpt_model->queryComplete($data,$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/stock/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/stock/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "3":
				$this->parser->parse('reports/stock/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "4":
				$this->parser->parse('reports/stock/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "5":
				$this->parser->parse('reports/stock/layout5', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "6":
				$this->parser->parse('reports/stock/layout6', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/stock/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
		}
		
		//echo "<div id='tstdiv'>Show Report</div>";//
	}



	private function buildKartuStock($dataReport){
		$groupItemId ="";
		$itemId = "";
		
		$sheet = $this->spreadsheet->getActiveSheet();

		$sheet->getColumnDimension("A")->setAutoSize(false);
		//$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
		$sheet->getColumnDimension("B")->setAutoSize(false);
		$sheet->getColumnDimension("C")->setAutoSize(false);
		$sheet->getColumnDimension("D")->setAutoSize(false);
		$sheet->getColumnDimension("E")->setAutoSize(false);
		$sheet->getColumnDimension("F")->setAutoSize(false);
		$sheet->getColumnDimension("G")->setAutoSize(false);
		$sheet->getColumnDimension("H")->setAutoSize(false);
		$sheet->getColumnDimension("I")->setAutoSize(false);
		$sheet->getColumnDimension("J")->setAutoSize(false);
		$sheet->getColumnDimension("K")->setAutoSize(false);
		$sheet->getColumnDimension("L")->setAutoSize(false);

		$sheet->getColumnDimension("A")->setWidth(50);
		$sheet->getColumnDimension("B")->setWidth(80);
		$sheet->getColumnDimension("C")->setWidth(70);
		$sheet->getColumnDimension("D")->setWidth(45);
		$sheet->getColumnDimension("E")->setWidth(100);
		$sheet->getColumnDimension("F")->setWidth(80);
		$sheet->getColumnDimension("G")->setWidth(100);
		$sheet->getColumnDimension("H")->setWidth(20);
		$sheet->getColumnDimension("I")->setWidth(20);
		$sheet->getColumnDimension("J")->setWidth(20);
		$sheet->getColumnDimension("K")->setWidth(20);
		$sheet->getColumnDimension("L")->setWidth(20);
		
		
		$sheet->setCellValue("A3","Gudang : Gudang");
		$sheet->setCellValue("A4","Tanggal : Tanggal  s/d Tanggal");		
		
		
		
		$line = 5;
		$nou = 1;
		
		$sheet->setCellValue("A$line","Group");		
		//$sheet->mergeCells("B$line:C$line");   
		$sheet->setCellValue("B$line","Item");
		$sheet->setCellValue("C$line","Tanggal");				
		$sheet->setCellValue("D$line","Trx Code");
		$sheet->setCellValue("E$line","No. Transaksi");
		$sheet->setCellValue("F$line","Referensi");
		$sheet->setCellValue("G$line","Q.Awal");
		$sheet->setCellValue("H$line","Q.Masuk");
		$sheet->setCellValue("I$line","Q.Keluar");
		$sheet->setCellValue("J$line","Q.sisa");
		$sheet->setCellValue("K$line","Basic Unit");

		$line++;

		foreach($dataReport as $row){
			//$sheet->mergeCells("B$line:C$line");
			if ($row->fin_item_group_id != $groupItemId ){
				$sheet->setCellValue("A$line",$row->fst_item_group_name);
				$groupItemId = $row->fin_item_group_id;
			}
			
			if ($row->fin_item_id != $itemId ){				   
				$sheet->setCellValue("B$line",$row->fst_item_name);
				$itemId = $row->fin_item_id;								
			}

			$sheet->setCellValue("C$line",$row->fdt_trx_datetime);				
			$sheet->setCellValue("D$line",$row->fst_trx_code);
			$sheet->setCellValue("E$line",$row->fst_trx_no);
			$sheet->setCellValue("F$line",$row->fst_referensi);
			$sheet->setCellValue("G$line",0);
			$sheet->setCellValue("H$line",$row->fdb_qty_in);
			$sheet->setCellValue("I$line",$row->fdb_qty_out);
			$sheet->setCellValue("J$line",$row->fdb_qty_balance_after);
			$sheet->setCellValue("K$line",$row->fst_basic_unit);
			$line++;
		}		
	}
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends MY_Controller
{

	public $menuName="report_gudang";
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
            ['layout' => 1, 'label'=>'Group Item.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Item', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tanggal', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Keterangan', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No. Transaksi', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Referensi', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Awal', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Masuk', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Keluar', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Q.Sisa', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Basic Unit', 'value'=>'10', 'selected'=>false,'sum_total'=>false],						
			['layout' => 2, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Group', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Type', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'LoB', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Item Code', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Item Name', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Vendor Item Name', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'POS Item Name', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Rack Info', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'SNI', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Max Disc', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'MinAvg', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'MaxAvg', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isBatchNumber', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isSerialNumber', 'value'=>'14', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isOnline', 'value'=>'15', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Memo', 'value'=>'16', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Unit', 'value'=>'17', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isBasic', 'value'=>'18', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Convertion', 'value'=>'19', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isSelling', 'value'=>'20', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isBuying', 'value'=>'21', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'isProduction', 'value'=>'22', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Price', 'value'=>'23', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'HET', 'value'=>'24', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 3, 'label'=>'Group', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Type', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'LoB', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Item Code', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Item Name', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Vendor Item Name', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'POS Item Name', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Rack Info', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'SNI', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Max Disc', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'MinAvg', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'MaxAvg', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'isBatchNumber', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'isSerialNumber', 'value'=>'14', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'isOnline', 'value'=>'15', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Memo', 'value'=>'16', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'BOM Scale', 'value'=>'17', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Item Code BOM', 'value'=>'18', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Item Name BOM', 'value'=>'19', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'20', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 4, 'label'=>'Group', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Type', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'LoB', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Item Code', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Item Name', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Vendor Item Name', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'POS Item Name', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Rack Info', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'SNI', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Max Disc', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'MinAvg', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'MaxAvg', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'isBatchNumber', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'isSerialNumber', 'value'=>'14', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'isOnline', 'value'=>'15', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Memo', 'value'=>'16', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Pricing Group', 'value'=>'17', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Unit', 'value'=>'18', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Selling Price', 'value'=>'19', 'selected'=>false,'sum_total'=>false],
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
		$this->data['TITLE'] = "ITEM LIST REPORT";
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

	public function generateExcel($isPreview = 1) {
		$this->load->library("phpspreadsheet");
		// print_r("Hallo");print_r($data);die();
		// $dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no");
		// print_r($dataReport);die();
		$data = [
			"fin_item_group_id" => $this->input->post("fin_item_group_id"),
			"fin_item_type_id" => $this->input->post("fin_item_type_id"),
			"fin_warehouse_id"=> $this->input->post("fin_warehouse_id"),
			"fdt_from"=>dbDateFormat($this->input->post("fdt_from")),
			"fdt_to"=>dbDateFormat($this->input->post("fdt_to")),
			"fst_item_code" => $this->input->post("fst_item_code"),
			"fst_item_code2" => $this->input->post("fst_item_code2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];		
		$dataReport = $this->stock_rpt_model->queryComplete($data,$data['rpt_layout']);

	
		$arrMerged = [];  //row,ttlColType(full,sum)
		if (isset($dataReport)) {
			if ($dataReport==[]) {
				print_r("Data Not Found!");
			}else {
				$repTitle = "";
		
				$this->spreadsheet = $this->phpspreadsheet->load();
				$sheet = $this->spreadsheet->getActiveSheet();								
				$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
				$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
				switch ($data['rpt_layout']){
					case "1":
						$repTitle = "LAPORAN STOCK BARANG";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 11;
						break;
					case "2":
						$repTitle = "LAPORAN DAFTAR BARANG DETAIL UNIT SATUAN";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						$fullColumn = 25;
						break;
					case "3":
						$repTitle = "LAPORAN DAFTAR BARANG DETAIL BOM";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						$fullColumn = 21;
						break;
					case "4":
						$repTitle = "LAPORAN DAFTAR BARANG DETAIL SPECIAL PRICING";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						$fullColumn = 20;
						break;
					default:
						$repTitle = "LAPORAN DAFTAR BARANG";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 17;
						break;
				}	

				$this->spreadsheet->getProperties()->setCreator('QSystem - Indonesia')
				->setLastModifiedBy('Developer team')
				->setTitle($repTitle)
				->setSubject($repTitle)
				->setDescription($repTitle)
				->setKeywords('office 2007 openxml php')
				->setCategory('report file');
		
				$this->spreadsheet->getActiveSheet()->getPageSetup()
					->setOrientation($repOrientation);
				$this->spreadsheet->getActiveSheet()->getPageSetup()
					->setPaperSize($repPaperSize);
							
				// $spreadsheet->getActiveSheet()->getHeaderFooter()
				// ->setOddHeader('&C&HPlease treat this document as confidential!');
				
				$this->spreadsheet->getActiveSheet()->getHeaderFooter()
				->setOddFooter('&L&B' . $this->spreadsheet->getProperties()->getTitle() .date('d-m-Y H') . '-' . '&RPage &P of &N');
				$this->spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));		
				$sheet->getPageSetup()->setFitToWidth(0);
				$sheet->getPageSetup()->setFitToHeight(0);
				$sheet->getPageMargins()->setTop(0.5);
				$sheet->getPageMargins()->setRight(0.5);
				$sheet->getPageMargins()->setLeft(0.5);
				$sheet->getPageMargins()->setBottom(0.5);		
				$this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$this->spreadsheet->getDefaultStyle()->getFont()->setSize(24);
				$sheet->setCellValue("A1", $repTitle);								
				$arrMerged[] = [1,"FULL"];
				$arrMerged[] = [3,"FULL"];
				$arrMerged[] = [4,"FULL"];

				$this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$this->spreadsheet->getDefaultStyle()->getFont()->setSize(10);
				
				//ini contoh report layout 1 az yang sudah dibuat
				if  ($data['rpt_layout'] ==  1){
					$this->buildKartuStock($dataReport);		
					$ttlSelectedCol = sizeof($data['selected_columns'][0]);
					$sumCol = $this->phpspreadsheet->getSumColPosition($this->layout_columns,$data['rpt_layout'],$data['selected_columns'][0]);
					$this->phpspreadsheet->mergedData($sheet,$arrMerged,$ttlSelectedCol,$sumCol);	
					$this->phpspreadsheet->cleanColumns($sheet,$fullColumn,$data['selected_columns'][0]);		
				} //end if layout 1

				if  ($data['rpt_layout'] ==  2){
                    $sheet->setCellValue("A3","Nou.");
                    $sheet->setCellValue("B3","Group");
                    $sheet->setCellValue("C3","Type");
                    $sheet->setCellValue("D3","LoB");
                    $sheet->setCellValue("E3","Item Code");
                    $sheet->setCellValue("F3","Item Name");
                    $sheet->setCellValue("G3","Vendor Item Name");
                    $sheet->setCellValue("H3","POS Item Name");
                    $sheet->setCellValue("I3","Rack Info");
                    $sheet->setCellValue("J3","SNI");
                    $sheet->setCellValue("K3","Max Disc");
					$sheet->setCellValue("L3","MinAvg");
					$sheet->setCellValue("M3","MaxAvg");
					$sheet->setCellValue("N3","isBatchNumber");
					$sheet->setCellValue("O3","isSerial Number");
					$sheet->setCellValue("P3","isOnline");
					$sheet->setCellValue("Q3","Memo");
					$sheet->setCellValue("R3","Unit");
                    $sheet->setCellValue("S3","isBasic");
					$sheet->setCellValue("T3","convertion");
					$sheet->setCellValue("U3","isSelling");
					$sheet->setCellValue("V3","isBuying");
					$sheet->setCellValue("W3","isProduction");
					$sheet->setCellValue("X3","Price");
					$sheet->setCellValue("Y3","HET");
                    $sheet->getColumnDimension("A")->setAutoSize(false);
                    $sheet->getColumnDimension("B")->setAutoSize(true);
                    $sheet->getColumnDimension("C")->setAutoSize(true);
                    $sheet->getColumnDimension("D")->setAutoSize(true);
                    $sheet->getColumnDimension("E")->setAutoSize(true);
                    $sheet->getColumnDimension("F")->setAutoSize(true);
                    $sheet->getColumnDimension("G")->setAutoSize(true);
                    $sheet->getColumnDimension("H")->setAutoSize(true);
                    $sheet->getColumnDimension("I")->setAutoSize(true);
                    $sheet->getColumnDimension("J")->setAutoSize(true);
                    $sheet->getColumnDimension("K")->setAutoSize(true);
					$sheet->getColumnDimension("L")->setAutoSize(true);
					$sheet->getColumnDimension("M")->setAutoSize(true);
                    $sheet->getColumnDimension("N")->setAutoSize(true);
                    $sheet->getColumnDimension("O")->setAutoSize(true);
                    $sheet->getColumnDimension("P")->setAutoSize(true);
					$sheet->getColumnDimension("Q")->setAutoSize(true);
					$sheet->getColumnDimension("R")->setAutoSize(true);
                    $sheet->getColumnDimension("S")->setAutoSize(true);
					$sheet->getColumnDimension("T")->setAutoSize(true);
					$sheet->getColumnDimension("U")->setAutoSize(true);
                    $sheet->getColumnDimension("V")->setAutoSize(true);
                    $sheet->getColumnDimension("W")->setAutoSize(true);
                    $sheet->getColumnDimension("X")->setAutoSize(true);
                    $sheet->getColumnDimension("Y")->setAutoSize(true);
					$nou = 0;
					$itemCode = "";
					$cellRow = 4;
					$numOfRecs = count($dataReport);
					$idx = 0;
					
					foreach($dataReport as $row){
						$idx++;
						if ($itemCode != $row->fst_item_code){
							$type = $row->fin_item_type_id;
							switch($type){
								case 1:
									$type = "Raw Material";
									break;
								case 2:
									$type = "Semi Finished Material";
									break;
								case 3:
									$type = "Supporting Material";
									break;
								case 4:
									$type = "Ready Product";
									break;
								case 5:
									$type = "Logistic";
									break;
								case 'ALL':
									$type = "ALL";
									break;
							}
							$itemCode = $row->fst_item_code;
							$nou++;
							$sheet->setCellValue("A".$cellRow,$nou);
							$sheet->setCellValue("B".$cellRow,$row->itemGroup);
							$sheet->setCellValue("C".$cellRow,$type);
							$sheet->setCellValue("D".$cellRow,$row->fst_linebusiness_id);
							$sheet->setCellValue("E".$cellRow,$row->fst_item_code);
							$sheet->setCellValue("F".$cellRow,$row->fst_item_name);
							$sheet->setCellValue("G".$cellRow,$row->fst_name_on_pos);
							$sheet->setCellValue("H".$cellRow,$row->fst_vendor_item_name);
							$sheet->setCellValue("I".$cellRow,$row->fst_storage_rack_info);
							$sheet->setCellValue("J".$cellRow,$row->fst_sni_no);
							$sheet->setCellValue("K".$cellRow,$row->fst_max_item_discount);
							$sheet->setCellValue("L".$cellRow,$row->fdc_min_basic_unit_avg_cost);
							$sheet->setCellValue("M".$cellRow,$row->fdc_max_basic_unit_avg_cost);
							if ($row->fbl_is_batch_number == 0){
								$row->fbl_is_batch_number ="NO";
							}else{
								$row->fbl_is_batch_number ="YES";
							}
							if ($row->fbl_is_serial_number == 0){
								$row->fbl_is_serial_number ="NO";
							}else{
								$row->fbl_is_serial_number ="YES";
							}
							if ($row->fbl_is_online == 0){
								$row->fbl_is_online ="NO";
							}else{
								$row->fbl_is_online ="YES";
							}
							$sheet->setCellValue("N".$cellRow,$row->fbl_is_batch_number);
							$sheet->setCellValue("O".$cellRow,$row->fbl_is_serial_number); 
							$sheet->setCellValue("P".$cellRow,$row->fbl_is_online);
							$sheet->setCellValue("Q".$cellRow,$row->fst_memo);                                
							//$cellRow++;

						}
						if ($row->fbl_is_basic_unit == 0){
							$row->fbl_is_basic_unit ="NO";
						}else{
							$row->fbl_is_basic_unit ="YES";
						}
						if ($row->fbl_is_selling == 0){
							$row->fbl_is_selling ="NO";
						}else{
							$row->fbl_is_selling ="YES";
						}
						if ($row->fbl_is_buying == 0){
							$row->fbl_is_buying ="NO";
						}else{
							$row->fbl_is_buying ="YES";
						}
						if ($row->fbl_is_production_output == 0 ){
							$row->fbl_is_production_output ="NO";
						}else{
							$row->fbl_is_production_output ="YES";
						}
						$sheet->setCellValue("R".$cellRow,$row->fst_unit);
						$sheet->setCellValue("S".$cellRow,$row->fbl_is_basic_unit); 
						$sheet->setCellValue("T".$cellRow,$row->fdc_conv_to_basic_unit);
						$sheet->setCellValue("U".$cellRow,$row->fbl_is_selling);
						$sheet->setCellValue("V".$cellRow,$row->fbl_is_buying);
						$sheet->setCellValue("W".$cellRow,$row->fbl_is_production_output); 
						$sheet->setCellValue("X".$cellRow,$row->fdc_price_list);
						$sheet->setCellValue("Y".$cellRow,$row->fdc_het);
						$cellRow++; 
					}
					

					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
							],
						],
					];
					//$sheet->getStyle('A1:L'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('A1:IV65536'.$cellRow)->applyFromArray($styleArray);
					$sheet->setShowGridlines(false);
					//BORDER
					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
							],
						],
					];
					$sheet->getStyle('A3:Y'.$cellRow)->applyFromArray($styleArray);
		
					//FONT BOLD & Center
					$styleArray = [
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						]
					];
					// $sheet->getStyle('A2')->applyFromArray($styleArray);
					$sheet->getStyle('A3:Y3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					$sheet->getStyle('L4:L'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('L4:M'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('X4:X'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('X4:Y'.$cellRow)->applyFromArray($styleArray);

					//$styleArray = [
					//	'numberFormat'=> [
					//		'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
					//	]
					//];
					//$sheet->getStyle('H4:H'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('J4:L'.$cellRow)->applyFromArray($styleArray);
					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY
						]
					];
					$sheet->getStyle('F4:F'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'font' => [
							'bold' => true,
							'size' => 24,
						],
					
					];
					$sheet->getStyle('A1')->applyFromArray($styleArray);

					$ttlSelectedCol = sizeof($data['selected_columns'][0]);
					$sumCol = $this->phpspreadsheet->getSumColPosition($this->layout_columns,$data['rpt_layout'],$data['selected_columns'][0]);
					$this->phpspreadsheet->cleanColumns($sheet,$fullColumn,$data['selected_columns'][0]);
					$this->phpspreadsheet->mergedData($sheet,$arrMerged,$ttlSelectedCol,$sumCol);

				} //End Of Layout 3
				if  ($data['rpt_layout'] ==  3){
                    $sheet->setCellValue("A3","Nou.");
                    $sheet->setCellValue("B3","Group");
                    $sheet->setCellValue("C3","Type");
                    $sheet->setCellValue("D3","LoB");
                    $sheet->setCellValue("E3","Item Code");
                    $sheet->setCellValue("F3","Item Name");
                    $sheet->setCellValue("G3","Vendor Item Name");
                    $sheet->setCellValue("H3","POS Item Name");
                    $sheet->setCellValue("I3","Rack Info");
                    $sheet->setCellValue("J3","SNI");
                    $sheet->setCellValue("K3","Max Disc");
					$sheet->setCellValue("L3","MinAvg");
					$sheet->setCellValue("M3","MaxAvg");
					$sheet->setCellValue("N3","isBatchNumber");
					$sheet->setCellValue("O3","isSerial Number");
					$sheet->setCellValue("P3","isOnline");
					$sheet->setCellValue("Q3","Memo");
					$sheet->setCellValue("R3","BOM Scale");
                    $sheet->setCellValue("S3","Item Code BOM");
					$sheet->setCellValue("T3","Item Name BOM");
					$sheet->setCellValue("U3","Unit BOM");
                    $sheet->getColumnDimension("A")->setAutoSize(false);
                    $sheet->getColumnDimension("B")->setAutoSize(true);
                    $sheet->getColumnDimension("C")->setAutoSize(true);
                    $sheet->getColumnDimension("D")->setAutoSize(true);
                    $sheet->getColumnDimension("E")->setAutoSize(true);
                    $sheet->getColumnDimension("F")->setAutoSize(true);
                    $sheet->getColumnDimension("G")->setAutoSize(true);
                    $sheet->getColumnDimension("H")->setAutoSize(true);
                    $sheet->getColumnDimension("I")->setAutoSize(true);
                    $sheet->getColumnDimension("J")->setAutoSize(true);
                    $sheet->getColumnDimension("K")->setAutoSize(true);
					$sheet->getColumnDimension("L")->setAutoSize(true);
					$sheet->getColumnDimension("M")->setAutoSize(true);
                    $sheet->getColumnDimension("N")->setAutoSize(true);
                    $sheet->getColumnDimension("O")->setAutoSize(true);
                    $sheet->getColumnDimension("P")->setAutoSize(true);
					$sheet->getColumnDimension("Q")->setAutoSize(true);
					$sheet->getColumnDimension("R")->setAutoSize(true);
                    $sheet->getColumnDimension("S")->setAutoSize(true);
					$sheet->getColumnDimension("T")->setAutoSize(true);
					$sheet->getColumnDimension("U")->setAutoSize(true);
					$nou = 0;
					$itemCode = "";
					$cellRow = 4;
					$numOfRecs = count($dataReport);
					$idx = 0;
					
					foreach($dataReport as $row){
						$idx++;
						if ($itemCode != $row->fst_item_code){
							$type = $row->fin_item_type_id;
							switch($type){
								case 1:
									$type = "Raw Material";
									break;
								case 2:
									$type = "Semi Finished Material";
									break;
								case 3:
									$type = "Supporting Material";
									break;
								case 4:
									$type = "Ready Product";
									break;
								case 5:
									$type = "Logistic";
									break;
								case 'ALL':
									$type = "ALL";
									break;
							}
							$itemCode = $row->fst_item_code;
							$nou++;
							$sheet->setCellValue("A".$cellRow,$nou);
							$sheet->setCellValue("B".$cellRow,$row->itemGroup);
							$sheet->setCellValue("C".$cellRow,$type);
							$sheet->setCellValue("D".$cellRow,$row->fst_linebusiness_id);
							$sheet->setCellValue("E".$cellRow,$row->fst_item_code);
							$sheet->setCellValue("F".$cellRow,$row->fst_item_name);
							$sheet->setCellValue("G".$cellRow,$row->fst_name_on_pos);
							$sheet->setCellValue("H".$cellRow,$row->fst_vendor_item_name);
							$sheet->setCellValue("I".$cellRow,$row->fst_storage_rack_info);
							$sheet->setCellValue("J".$cellRow,$row->fst_sni_no);
							$sheet->setCellValue("K".$cellRow,$row->fst_max_item_discount);
							$sheet->setCellValue("L".$cellRow,$row->fdc_min_basic_unit_avg_cost);
							$sheet->setCellValue("M".$cellRow,$row->fdc_max_basic_unit_avg_cost);
							if ($row->fbl_is_batch_number == 0){
								$row->fbl_is_batch_number ="NO";
							}else{
								$row->fbl_is_batch_number ="YES";
							}
							if ($row->fbl_is_serial_number == 0){
								$row->fbl_is_serial_number ="NO";
							}else{
								$row->fbl_is_serial_number ="YES";
							}
							if ($row->fbl_is_online == 0){
								$row->fbl_is_online ="NO";
							}else{
								$row->fbl_is_online ="YES";
							}
							$sheet->setCellValue("N".$cellRow,$row->fbl_is_batch_number);
							$sheet->setCellValue("O".$cellRow,$row->fbl_is_serial_number); 
							$sheet->setCellValue("P".$cellRow,$row->fbl_is_online);
							$sheet->setCellValue("Q".$cellRow,$row->fst_memo);
							$sheet->setCellValue("R".$cellRow,$row->fdc_scale_for_bom);                                
							//$cellRow++;

						}
						$sheet->setCellValue("S".$cellRow,$row->itemCodeBOM); 
						$sheet->setCellValue("T".$cellRow,$row->itemNameBOM);
						$sheet->setCellValue("U".$cellRow,$row->unitBOM);
						$cellRow++; 
					}
					

					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
							],
						],
					];
					//$sheet->getStyle('A1:L'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('A1:IV65536'.$cellRow)->applyFromArray($styleArray);
					$sheet->setShowGridlines(false);
					//BORDER
					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
							],
						],
					];
					$sheet->getStyle('A3:U'.$cellRow)->applyFromArray($styleArray);
		
					//FONT BOLD & Center
					$styleArray = [
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						]
					];
					// $sheet->getStyle('A2')->applyFromArray($styleArray);
					$sheet->getStyle('A3:U3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					$sheet->getStyle('L4:L'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('L4:M'.$cellRow)->applyFromArray($styleArray);

					//$styleArray = [
					//	'numberFormat'=> [
					//		'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
					//	]
					//];
					//$sheet->getStyle('H4:H'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('J4:L'.$cellRow)->applyFromArray($styleArray);
					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY
						]
					];
					$sheet->getStyle('F4:F'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'font' => [
							'bold' => true,
							'size' => 24,
						],
					
					];
					$sheet->getStyle('A1')->applyFromArray($styleArray);

					$ttlSelectedCol = sizeof($data['selected_columns'][0]);
					$sumCol = $this->phpspreadsheet->getSumColPosition($this->layout_columns,$data['rpt_layout'],$data['selected_columns'][0]);
					$this->phpspreadsheet->cleanColumns($sheet,$fullColumn,$data['selected_columns'][0]);
					$this->phpspreadsheet->mergedData($sheet,$arrMerged,$ttlSelectedCol,$sumCol);

				} //End Of Layout 4
				if  ($data['rpt_layout'] ==  4){
                    $sheet->setCellValue("A3","Nou.");
                    $sheet->setCellValue("B3","Group");
                    $sheet->setCellValue("C3","Type");
                    $sheet->setCellValue("D3","LoB");
                    $sheet->setCellValue("E3","Item Code");
                    $sheet->setCellValue("F3","Item Name");
                    $sheet->setCellValue("G3","Vendor Item Name");
                    $sheet->setCellValue("H3","POS Item Name");
                    $sheet->setCellValue("I3","Rack Info");
                    $sheet->setCellValue("J3","SNI");
                    $sheet->setCellValue("K3","Max Disc");
					$sheet->setCellValue("L3","MinAvg");
					$sheet->setCellValue("M3","MaxAvg");
					$sheet->setCellValue("N3","isBatchNumber");
					$sheet->setCellValue("O3","isSerial Number");
					$sheet->setCellValue("P3","isOnline");
					$sheet->setCellValue("Q3","Memo");
					$sheet->setCellValue("R3","Pricing Group");
                    $sheet->setCellValue("S3","Unit");
					$sheet->setCellValue("T3","Selling Price");
                    $sheet->getColumnDimension("A")->setAutoSize(false);
                    $sheet->getColumnDimension("B")->setAutoSize(true);
                    $sheet->getColumnDimension("C")->setAutoSize(true);
                    $sheet->getColumnDimension("D")->setAutoSize(true);
                    $sheet->getColumnDimension("E")->setAutoSize(true);
                    $sheet->getColumnDimension("F")->setAutoSize(true);
                    $sheet->getColumnDimension("G")->setAutoSize(true);
                    $sheet->getColumnDimension("H")->setAutoSize(true);
                    $sheet->getColumnDimension("I")->setAutoSize(true);
                    $sheet->getColumnDimension("J")->setAutoSize(true);
                    $sheet->getColumnDimension("K")->setAutoSize(true);
					$sheet->getColumnDimension("L")->setAutoSize(true);
					$sheet->getColumnDimension("M")->setAutoSize(true);
                    $sheet->getColumnDimension("N")->setAutoSize(true);
                    $sheet->getColumnDimension("O")->setAutoSize(true);
                    $sheet->getColumnDimension("P")->setAutoSize(true);
					$sheet->getColumnDimension("Q")->setAutoSize(true);
					$sheet->getColumnDimension("R")->setAutoSize(true);
                    $sheet->getColumnDimension("S")->setAutoSize(true);
					$sheet->getColumnDimension("T")->setAutoSize(true);
					$nou = 0;
					$itemCode = "";
					$cellRow = 4;
					$numOfRecs = count($dataReport);
					$idx = 0;
					
					foreach($dataReport as $row){
						$idx++;
						if ($itemCode != $row->fst_item_code){
							$type = $row->fin_item_type_id;
							switch($type){
								case 1:
									$type = "Raw Material";
									break;
								case 2:
									$type = "Semi Finished Material";
									break;
								case 3:
									$type = "Supporting Material";
									break;
								case 4:
									$type = "Ready Product";
									break;
								case 5:
									$type = "Logistic";
									break;
								case 'ALL':
									$type = "ALL";
									break;
							}
							$itemCode = $row->fst_item_code;
							$nou++;
							$sheet->setCellValue("A".$cellRow,$nou);
							$sheet->setCellValue("B".$cellRow,$row->itemGroup);
							$sheet->setCellValue("C".$cellRow,$type);
							$sheet->setCellValue("D".$cellRow,$row->fst_linebusiness_id);
							$sheet->setCellValue("E".$cellRow,$row->fst_item_code);
							$sheet->setCellValue("F".$cellRow,$row->fst_item_name);
							$sheet->setCellValue("G".$cellRow,$row->fst_name_on_pos);
							$sheet->setCellValue("H".$cellRow,$row->fst_vendor_item_name);
							$sheet->setCellValue("I".$cellRow,$row->fst_storage_rack_info);
							$sheet->setCellValue("J".$cellRow,$row->fst_sni_no);
							$sheet->setCellValue("K".$cellRow,$row->fst_max_item_discount);
							$sheet->setCellValue("L".$cellRow,$row->fdc_min_basic_unit_avg_cost);
							$sheet->setCellValue("M".$cellRow,$row->fdc_max_basic_unit_avg_cost);
							if ($row->fbl_is_batch_number == 0){
								$row->fbl_is_batch_number ="NO";
							}else{
								$row->fbl_is_batch_number ="YES";
							}
							if ($row->fbl_is_serial_number == 0){
								$row->fbl_is_serial_number ="NO";
							}else{
								$row->fbl_is_serial_number ="YES";
							}
							if ($row->fbl_is_online == 0){
								$row->fbl_is_online ="NO";
							}else{
								$row->fbl_is_online ="YES";
							}
							$sheet->setCellValue("N".$cellRow,$row->fbl_is_batch_number);
							$sheet->setCellValue("O".$cellRow,$row->fbl_is_serial_number); 
							$sheet->setCellValue("P".$cellRow,$row->fbl_is_online);
							$sheet->setCellValue("Q".$cellRow,$row->fst_memo);                                
							//$cellRow++;

						}
						$sheet->setCellValue("R".$cellRow,$row->fst_cust_pricing_group_name);
						$sheet->setCellValue("S".$cellRow,$row->fst_unit); 
						$sheet->setCellValue("T".$cellRow,$row->fdc_selling_price);
						$cellRow++; 
					}
					

					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
							],
						],
					];
					//$sheet->getStyle('A1:L'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('A1:IV65536'.$cellRow)->applyFromArray($styleArray);
					$sheet->setShowGridlines(false);
					//BORDER
					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
							],
						],
					];
					$sheet->getStyle('A3:T'.$cellRow)->applyFromArray($styleArray);
		
					//FONT BOLD & Center
					$styleArray = [
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						]
					];
					// $sheet->getStyle('A2')->applyFromArray($styleArray);
					$sheet->getStyle('A3:T3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					//$sheet->getStyle('L4:L'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('L4:M'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('T4:T'.$cellRow)->applyFromArray($styleArray);

					//$styleArray = [
					//	'numberFormat'=> [
					//		'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
					//	]
					//];
					//$sheet->getStyle('H4:H'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('J4:L'.$cellRow)->applyFromArray($styleArray);
					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY
						]
					];
					$sheet->getStyle('F4:F'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'font' => [
							'bold' => true,
							'size' => 24,
						],
					
					];
					$sheet->getStyle('A1')->applyFromArray($styleArray);

					$ttlSelectedCol = sizeof($data['selected_columns'][0]);
					$sumCol = $this->phpspreadsheet->getSumColPosition($this->layout_columns,$data['rpt_layout'],$data['selected_columns'][0]);
					$this->phpspreadsheet->cleanColumns($sheet,$fullColumn,$data['selected_columns'][0]);
					$this->phpspreadsheet->mergedData($sheet,$arrMerged,$ttlSelectedCol,$sumCol);

				} //End Of Layout 4

				if ($isPreview != 1) {
					$this->phpspreadsheet->save("Master Items.xls" ,$this->spreadsheet);
					// $this->phpspreadsheet->savePDF();
				}else {
					//$this->phpspreadsheet->savePDF();
					$this->phpspreadsheet->saveHTMLvia($this->spreadsheet);    
				}
			}
		}else {
			print_r("Data Not Found !");
		}
	}
	
	public function generateReport($isPreview = 1){		
		//var_dump($this->input->post());
		$data = [
			"fin_item_group_id" => $this->input->post("fin_item_group_id"),
			"fin_item_type_id" => $this->input->post("fin_item_type_id"),
			"fin_warehouse_id"=> $this->input->post("fin_warehouse_id"),
			"fdt_from"=>dbDateFormat($this->input->post("fdt_from")),
			"fdt_to"=>dbDateFormat($this->input->post("fdt_to")),
			"fst_item_code" => $this->input->post("fst_item_code"),
			"fst_item_code2" => $this->input->post("fst_item_code2"),
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

		if ($data['rpt_layout'] == 1){
			$this->parser->parse('reports/stock/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
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
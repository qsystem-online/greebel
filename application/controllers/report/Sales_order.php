<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends MY_Controller
{

	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/sales_order_rpt_model');
		$this->load->model('users_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('msrelations_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'Pelanggan/Customer', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.SO', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.SO', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'GUD', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Sales', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Kode Barang', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nama Barang', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Unit', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Harga', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Diskon', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Jumlah', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.SO', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.SO', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'GUD', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Sales', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Customer', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Sub Total', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Diskon', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Dpp', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Ppn', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Total', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'DP', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 3, 'label'=>'GUD', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Customer', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Sales', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.S/O', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl S/O', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Nama Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty S/O', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.S/J', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl S/J', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty S/J', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty O/S', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
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
		

		$side_filter = $this->parser->parse('reports/sales_order/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "SALES ORDER REPORT";
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
		$this->load->model('sales_order_rpt_model');
		$this->form_validation->set_rules($this->sales_order_rpt_model->getRules());
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

	public function generateExcel_hide($isPreview = 1) {
		$this->load->library("phpspreadsheet");
		// print_r("Hallo");print_r($data);die();
		// $dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no");
		// print_r($dataReport);die();
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fdt_salesorder_datetime" => $this->input->post("fdt_salesorder_datetime"),
			"fdt_salesorder_datetime2" => $this->input->post("fdt_salesorder_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no");
		
		if (isset($dataReport)) {
			if ($dataReport==[]) {
				print_r("Data Not Found!");
			}
			else {
				$repTitle = "";
		
				$spreadsheet = $this->phpspreadsheet->load();
				$sheet = $spreadsheet->getActiveSheet();
				
				
				$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
				$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
				switch ($data['rpt_layout']){
					case "1":
						$repTitle = "LAPORAN SALES ORDER DETAIL";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						break;
					case "2":
						$repTitle = "LAPORAN SALES ORDER RINGKAS";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
						break;
					case "3":
						$repTitle = "LAPORAN SALES ORDER OUTSTANDING";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						break;
					default:
						$repTitle = "LAPORAN SALES ORDER DETAIL";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						break;
				}	
				$spreadsheet->getProperties()->setCreator('QSystem - Indonesia')
				->setLastModifiedBy('Developer team')
				->setTitle($repTitle)
				->setSubject($repTitle)
				->setDescription($repTitle)
				->setKeywords('office 2007 openxml php')
				->setCategory('report file');
		
				$spreadsheet->getActiveSheet()->getPageSetup()
					->setOrientation($repOrientation);
				$spreadsheet->getActiveSheet()->getPageSetup()
					->setPaperSize($repPaperSize);
							
				// $spreadsheet->getActiveSheet()->getHeaderFooter()
				// ->setOddHeader('&C&HPlease treat this document as confidential!');
				
				$spreadsheet->getActiveSheet()->getHeaderFooter()
				->setOddFooter('&L&B' . $spreadsheet->getProperties()->getTitle() .date('d-m-Y H') . '-' . '&RPage &P of &N');
				$spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));
		
				$sheet->getPageSetup()->setFitToWidth(0);
				$sheet->getPageSetup()->setFitToHeight(0);
				//$sheet->getPageSetup()->setScale(80);
				//$sheet->getPageSetup()->setFitToPage(false);
				$sheet->getPageMargins()->setTop(0.5);
				$sheet->getPageMargins()->setRight(0.5);
				$sheet->getPageMargins()->setLeft(0.5);
				$sheet->getPageMargins()->setBottom(0.5);
		
				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(24);
				$sheet->setCellValue("A1", $repTitle);
				
				$sheet->mergeCells('A1:L1');                

				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
				
				//ini contoh report layout 1 az yang sudah dibuat
				$sheet->setCellValue("A3","Nou.");
				$sheet->setCellValue("B3","No.S/O");
				$sheet->setCellValue("C3","Tanggal");
				$sheet->setCellValue("D3","Nama Pelanggan");
				$sheet->setCellValue("E3","Nama Sales");
				$sheet->setCellValue("F3","Kode Barang");
				$sheet->setCellValue("G3","Nama Barang");
				$sheet->setCellValue("H3","Qty");
				$sheet->setCellValue("I3","Unit");
				$sheet->setCellValue("J3","Harga");
				$sheet->setCellValue("K3","Diskon");
				$sheet->setCellValue("L3","Jumlah");
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
				// $sheet->getColumnDimension("D")->setWidth(200);
				// $sheet->getColumnDimension("D")->setWidth(200);
		
		//        if (isset($dataReport)) {
					// while ($row = $dataReport->fetch())
					// {
					//     $total += $row['total'];
					// }            
					$nou = 0;
					$noSO = "";
					$cellRow = 4;
					$subQty = 0;
					$subAmount = 0;
					$subDiscount = 0;
					$totalQty = 0;
					$totalDiscount = 0;
					$totalAmount = 0;
					$numOfRecs = count($dataReport);
					$idx = 0;
					foreach($dataReport as $row){
						$idx++;
						// break by no_SO
						if ($noSO != $row->No_SO) {
							// jika record pertama, tidak perlu create subtotal
							if ($noSO != "") {
								//akumulasi total keseluruhan                            
								$totalQty += $subQty;
								$totalDiscount += $subDiscount;
								$totalAmount += $subAmount;                                
								//tulis subtotal per-group
								$styleArray = [
									'font' => [
										'bold' => true,
									]
								];
								$sheet->getStyle('A'.$cellRow.':L'.$cellRow)->applyFromArray($styleArray);
								
								$sheet->setCellValue("G".$cellRow,"SUBTOTAL");
								$sheet->setCellValue("H".$cellRow,$subQty);
								$sheet->setCellValue("K".$cellRow,$subDiscount);
								$sheet->setCellValue("L".$cellRow,$subAmount);
								// $sheet->mergeCells('A'.$cellRow.':G'.$cellRow);
								$cellRow++;
								//reset subtotal variable (break group)
								$subQty = 0;
								$subAmount = 0;

								$cellRow ++;
							}

							$noSO = $row->No_SO;
							$nou++;
							$sheet->setCellValue("A".$cellRow,$nou);
							$sheet->setCellValue("B".$cellRow,$row->No_SO);
							$sheet->setCellValue("C".$cellRow,$row->SO_Date);
							$sheet->setCellValue("D".$cellRow,$row->Relation_Name);
							$sheet->setCellValue("E".$cellRow,$row->Sales_Name);                            
						}
						$sheet->setCellValue("F".$cellRow,$row->Item_Code);
						$sheet->setCellValue("G".$cellRow,$row->Item_Name);
						$sheet->setCellValue("H".$cellRow,$row->Qty);
						$sheet->setCellValue("I".$cellRow,$row->Unit);
						$sheet->setCellValue("J".$cellRow,$row->Price);
						$sheet->setCellValue("K".$cellRow,$row->Disc_Amount);
						$sheet->setCellValue("L".$cellRow,$row->Amount);
						$subQty += $row->Qty;
						$subDiscount += $row->Disc_Amount;
						$subAmount += $row->Amount;
						$cellRow++;
					}
					$totalQty += $subQty;
					$totalDiscount += $subDiscount;
					$totalAmount += $subAmount;                                
					//tulis subtotal per-group
					$styleArray = [
						'font' => [
							'bold' => true,
						]
					];
					 
					// $sheet->mergeCells('A'.$cellRow.':G'.$cellRow);
					$sheet->setCellValue("G".$cellRow,"SUBTOTAL");
					$sheet->setCellValue("H".$cellRow,$subQty);
					$sheet->setCellValue("K".$cellRow,$subDiscount);
					$sheet->setCellValue("L".$cellRow,$subAmount);
					$sheet->getStyle('G'.$cellRow.':L'.$cellRow)->applyFromArray($styleArray);

					$cellRow++;
					$cellRow++;

				   $sheet->getStyle('G'.$cellRow.':L'.$cellRow)->applyFromArray($styleArray);

					// $sheet->mergeCells('A'.$cellRow.':G'.$cellRow);
					$sheet->setCellValue("G".$cellRow,"TOTAL KESELURUHAN");
					$sheet->setCellValue("H".$cellRow,$totalQty);
					$sheet->setCellValue("K".$cellRow,$totalDiscount);
					$sheet->setCellValue("L".$cellRow,$totalAmount);

					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
							],
						],
					];
					$sheet->getStyle('A1:L'.$cellRow)->applyFromArray($styleArray);

					//BORDER
					$styleArray = [
						'borders' => [
							'allBorders' => [
								//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
							],
						],
					];
					$sheet->getStyle('A3:L'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:L3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					$sheet->getStyle('H4:H'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('J4:L'.$cellRow)->applyFromArray($styleArray);
					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY
						]
					];
					$sheet->getStyle('C4:C'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'font' => [
							'bold' => true,
							'size' => 24,
						],
					
					];
					$sheet->getStyle('A1')->applyFromArray($styleArray);
					//hide unwanted columns
					// foreach(range('A','L') as $v){
					//     if (in_array($v, $data['selected_columns'][0])) {
					//         $spreadsheet->getActiveSheet()->getColumnDimension($v)->setVisible(true);                                
					//     } else {
					//        // $spreadsheet->getActiveSheet()->getColumnDimension($v)->setWidth(0);
					//         $spreadsheet->getActiveSheet()->getColumnDimension($v)->setVisible(false);
					//     }    
					// }
					foreach(range('A','L') as $v){
						$spreadsheet->getActiveSheet()->getColumnDimension($v)->setAutoSize(true);
						if (in_array($v, $data['selected_columns'][0])) {
							$spreadsheet->getActiveSheet()->getColumnDimension($v)->setVisible(true);
							//$spreadsheet->getActiveSheet()->getColumnDimension($v)->setWidth(true);
							// print_r('column '.$v.'-> SHOW');
						} else {
							//print_r('column '.$v.'-> HIDE');
						   $spreadsheet->getActiveSheet()->getColumnDimension($v)->setVisible(false);
						   $spreadsheet->getActiveSheet()->getColumnDimension($v)->setWidth(0);
						}    
					}


					if ($isPreview != 1) {
						$this->phpspreadsheet->save("hasil.xls" ,$spreadsheet);
						// $this->phpspreadsheet->savePDF();
					}
					else {
						//$this->phpspreadsheet->savePDF();
						$this->phpspreadsheet->saveHTMLvia($spreadsheet);    
					}
			}
		}
		else {
			print_r("Data Not Found !");
		}
	}

	public function generateExcel($isPreview = 1) {
		$this->load->library("phpspreadsheet");
		// print_r("Hallo");print_r($data);die();
		// $dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no");
		// print_r($dataReport);die();
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fdt_salesorder_datetime" => $this->input->post("fdt_salesorder_datetime"),
			"fdt_salesorder_datetime2" => $this->input->post("fdt_salesorder_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no",$data['rpt_layout']);

		$arrMerged = [];  //row,ttlColType(full,sum)
		if (isset($dataReport)) {
			if ($dataReport==[]) {
				print_r("Data Not Found!");
			}else {
				$repTitle = "";
		
				$spreadsheet = $this->phpspreadsheet->load();
				$sheet = $spreadsheet->getActiveSheet();								
				$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
				$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
				switch ($data['rpt_layout']){
					case "1":
						$repTitle = "LAPORAN SALES ORDER DETAIL";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						$fullColumn = 12;
						break;
					case "2":
						$repTitle = "LAPORAN SALES ORDER RINGKAS";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
						$fullColumn = 6;
						break;
					case "3":
						$repTitle = "LAPORAN SALES ORDER OUTSTANDING";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						break;
					default:
						$repTitle = "LAPORAN SALES ORDER DETAIL";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						break;
				}	

				$spreadsheet->getProperties()->setCreator('QSystem - Indonesia')
				->setLastModifiedBy('Developer team')
				->setTitle($repTitle)
				->setSubject($repTitle)
				->setDescription($repTitle)
				->setKeywords('office 2007 openxml php')
				->setCategory('report file');
		
				$spreadsheet->getActiveSheet()->getPageSetup()
					->setOrientation($repOrientation);
				$spreadsheet->getActiveSheet()->getPageSetup()
					->setPaperSize($repPaperSize);
							
				// $spreadsheet->getActiveSheet()->getHeaderFooter()
				// ->setOddHeader('&C&HPlease treat this document as confidential!');
				
				$spreadsheet->getActiveSheet()->getHeaderFooter()
				->setOddFooter('&L&B' . $spreadsheet->getProperties()->getTitle() .date('d-m-Y H') . '-' . '&RPage &P of &N');
				$spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));
		
				$sheet->getPageSetup()->setFitToWidth(0);
				$sheet->getPageSetup()->setFitToHeight(0);
				$sheet->getPageMargins()->setTop(0.5);
				$sheet->getPageMargins()->setRight(0.5);
				$sheet->getPageMargins()->setLeft(0.5);
				$sheet->getPageMargins()->setBottom(0.5);
		
				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(24);
				$sheet->setCellValue("A1", $repTitle);
				
				//$sheet->mergeCells('A1:L1');                
				$arrMerged[] = [1,"FULL"];

				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
				
				//ini contoh report layout 1 az yang sudah dibuat
				if  ($data['rpt_layout'] ==  1){
					$sheet->setCellValue("A3","Nou.");
					$sheet->setCellValue("B3","No.S/O");
					$sheet->setCellValue("C3","Tanggal");
					$sheet->setCellValue("D3","Nama Pelanggan");
					$sheet->setCellValue("E3","Nama Sales");
					$sheet->setCellValue("F3","Kode Barang");
					$sheet->setCellValue("G3","Nama Barang");
					$sheet->setCellValue("H3","Qty");
					$sheet->setCellValue("I3","Unit");
					$sheet->setCellValue("J3","Harga");
					$sheet->setCellValue("K3","Diskon");
					$sheet->setCellValue("L3","Jumlah");
					$sheet->getColumnDimension("A")->setAutoSize(true);
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
					$nou = 0;
					$noSO = "";
					$cellRow = 4;
					$subQty = 0;
					$subAmount = 0;
					$subDiscount = 0;
					$totalQty = 0;
					$totalDiscount = 0;
					$totalAmount = 0;
					$numOfRecs = count($dataReport);
					$idx = 0;
					
					foreach($dataReport as $row){
						$idx++;
						// break by no_SO
						if ($noSO != $row->No_SO) {
							// jika record pertama, tidak perlu create subtotal
							if ($noSO != "") {
								//akumulasi total keseluruhan                            
								$totalQty += $subQty;
								$totalDiscount += $subDiscount;
								$totalAmount += $subAmount;                                
								//tulis subtotal per-group
								$styleArray = [
									'font' => [
										'bold' => true,
									]
								];
								$sheet->getStyle('A'.$cellRow.':L'.$cellRow)->applyFromArray($styleArray);                            
								$sheet->setCellValue("A".$cellRow,"SUBTOTAL");
								$sheet->setCellValue("H".$cellRow,$subQty);
								$sheet->setCellValue("K".$cellRow,$subDiscount);
								$sheet->setCellValue("L".$cellRow,$subAmount);
								
								$arrMerged[]=[$cellRow,"SUM"];

								$cellRow++;
								//reset subtotal variable (break group)
								$subQty = 0;
								$subAmount = 0;

								$cellRow ++;
							}

							$noSO = $row->No_SO;
							$nou++;
							$sheet->setCellValue("A".$cellRow,$nou);
							$sheet->setCellValue("B".$cellRow,$row->No_SO);
							$sheet->setCellValue("C".$cellRow,$row->SO_Date);
							$sheet->setCellValue("D".$cellRow,$row->Relation_Name);
							$sheet->setCellValue("E".$cellRow,$row->Sales_Name);                            
						}
						$sheet->setCellValue("F".$cellRow,$row->Item_Code);
						$sheet->setCellValue("G".$cellRow,$row->Item_Name);
						$sheet->setCellValue("H".$cellRow,$row->Qty);
						$sheet->setCellValue("I".$cellRow,$row->Unit);
						$sheet->setCellValue("J".$cellRow,$row->Price);
						$sheet->setCellValue("K".$cellRow,$row->Disc_Amount);
						$sheet->setCellValue("L".$cellRow,$row->Amount);
						$subQty += $row->Qty;
						$subDiscount += $row->Disc_Amount;
						$subAmount += $row->Amount;
						$cellRow++;
					}

					$totalQty += $subQty;
					$totalDiscount += $subDiscount;
					$totalAmount += $subAmount;                                
					//tulis subtotal per-group
					$styleArray = [
						'font' => [
							'bold' => true,
						]
					];
						
					// $sheet->mergeCells('A'.$cellRow.':G'.$cellRow);
					$sheet->setCellValue("A".$cellRow,"SUBTOTAL");
					$sheet->setCellValue("H".$cellRow,$subQty);
					$sheet->setCellValue("K".$cellRow,$subDiscount);
					$sheet->setCellValue("L".$cellRow,$subAmount);
					$sheet->getStyle('G'.$cellRow.':L'.$cellRow)->applyFromArray($styleArray);
					
					$arrMerged[]=[$cellRow,"SUM"];
					
					$cellRow++;
					$cellRow++;

					$sheet->getStyle('G'.$cellRow.':L'.$cellRow)->applyFromArray($styleArray);

					// $sheet->mergeCells('A'.$cellRow.':G'.$cellRow);
					$sheet->setCellValue("A".$cellRow,"TOTAL KESELURUHAN");
					$sheet->setCellValue("H".$cellRow,$totalQty);
					$sheet->setCellValue("K".$cellRow,$totalDiscount);
					$sheet->setCellValue("L".$cellRow,$totalAmount);
					
					$arrMerged[]=[$cellRow,"SUM"];
					

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
					$sheet->getStyle('A3:L'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:L3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					$sheet->getStyle('H4:H'.$cellRow)->applyFromArray($styleArray);
					$sheet->getStyle('J4:L'.$cellRow)->applyFromArray($styleArray);
					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY
						]
					];
					$sheet->getStyle('C4:C'.$cellRow)->applyFromArray($styleArray);

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

				} //end if layout 1

				if ($isPreview != 1) {
					$this->phpspreadsheet->save("hasil.xls" ,$spreadsheet);
					// $this->phpspreadsheet->savePDF();
				}else {
					//$this->phpspreadsheet->savePDF();
					$this->phpspreadsheet->saveHTMLvia($spreadsheet);    
				}
			}
		}else {
			print_r("Data Not Found !");
		}
	}


	public function generateExcel_test2($isPreview = 1) {
		$this->load->library("phpspreadsheet");
		// print_r("Hallo");print_r($data);die();
		// $dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no");
		// print_r($dataReport);die();
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fdt_salesorder_datetime" => $this->input->post("fdt_salesorder_datetime"),
			"fdt_salesorder_datetime2" => $this->input->post("fdt_salesorder_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fst_salesorder_no");
		$arrMerged = [];  //row,ttlColType(full,sum)
		if (isset($dataReport)) {
			if ($dataReport==[]) {
				print_r("Data Not Found!");
			}else {
				$repTitle = "";
		
				$spreadsheet = $this->phpspreadsheet->load();
				$sheet = $spreadsheet->getActiveSheet();	

				$repPaperSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
				$repOrientation= \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
				$repTitle = "LAPORAN SALES ORDER DETAIL";

				
			
		
				//$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				//$spreadsheet->getDefaultStyle()->getFont()->setSize(24);
				//$sheet->setCellValue("A1", $repTitle);
				
				//$sheet->mergeCells('A1:L1');                
				$arrMerged[] = [1,"FULL"];

				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
				
				$sheet->setCellValue("A3","Col A");
				$sheet->setCellValue("B3","Col B");
				$sheet->setCellValue("C3","Col C");
				$sheet->setCellValue("D3","Col D");
				$sheet->setCellValue("E3","Col E");
				$sheet->setCellValue("F3","Col F");
				$sheet->getColumnDimension("A")->setAutoSize(true);
				$sheet->getColumnDimension("B")->setAutoSize(true);
				$sheet->getColumnDimension("C")->setAutoSize(true);
				$sheet->getColumnDimension("D")->setAutoSize(true);
				$sheet->getColumnDimension("E")->setAutoSize(true);
				$sheet->getColumnDimension("F")->setAutoSize(true);
				
				$ttlCol = 6;
				$sumCol = 3;
				$this->phpspreadsheet->cleanColumns($sheet,6,$data['selected_columns'][0]);
				$this->phpspreadsheet->mergedData($sheet,$arrMerged,$ttlCol,$sumCol);


				if ($isPreview != 1) {
					$this->phpspreadsheet->save("hasil.xls" ,$spreadsheet);
					// $this->phpspreadsheet->savePDF();
				}else {
					//$this->phpspreadsheet->savePDF();
					$this->phpspreadsheet->saveHTMLvia($spreadsheet);    
				}
			}
		}else {
			print_r("Data Not Found !");
		}
	}

	public function generateExcel_test($isPreview = 1) {
		$this->load->library("phpspreadsheet");
	   
		$repTitle = "";
		$spreadsheet = $this->phpspreadsheet->load("d:\\hasil.xls","XLS");  
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension("A")->setAutoSize(false);
		//$sheet->getColumnDimension("H")->setAutoSize(true);
		//$sheet->getColumnDimension("H")->setWidth(0.1);        
		$sheet->removeColumn('H');
		$sheet->mergeCells('A8:G8');   


		
		//$this->phpspreadsheet->save("hasil2.xls" ,$spreadsheet); 
		//$this->phpspreadsheet->savePDF();
		$this->phpspreadsheet->saveHTMLvia($spreadsheet);
	}

	

	public function report_branch()
	{
		$this->load->library('pdf');
		//$customPaper = array(0,0,381.89,595.28);
		//$this->pdf->setPaper($customPaper, 'landscape');
		$this->pdf->setPaper('A4', 'portrait');
		//$this->pdf->setPaper('A4', 'landscape');

		$this->load->model("msbranches_model");
		$listBranch = $this->msbranches_model->get_Branch();
		$data = [
			"datas" => $listBranch
		];

		$this->pdf->load_view('report/branch_pdf', $data);
		$this->Cell(30, 10, 'Percobaan Header Dan Footer With Page Number', 0, 0, 'C');
		$this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'R');
	}

	public function generateReport($isPreview = 1){		
		//var_dump($this->input->post());
		//$activeBranchId = $this->session->userdata("active_branch_id");
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_sales_id" => $this->input->post("fin_sales_id"),
			"fdt_salesorder_datetime" => $this->input->post("fdt_salesorder_datetime"),
			"fdt_salesorder_datetime2" => $this->input->post("fdt_salesorder_datetime2"),
			"fbl_is_vat_include" => $this->input->post("fbl_is_vat_include"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->sales_order_rpt_model->queryComplete($data,"a.fin_salesorder_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		if ($data['rpt_layout'] == 1){
			$this->parser->parse('reports/sales_order/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 2){
			$this->parser->parse('reports/sales_order/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 3){
			$this->parser->parse('reports/sales_order/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}
		
		//echo "<div id='tstdiv'>Show Report</div>";//
	}

}

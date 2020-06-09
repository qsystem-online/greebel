<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Glaccounts extends MY_Controller
{

	public $layout_columns =[]; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/glaccounts_rpt_model');
		$this->load->model('glaccounts_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Group', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Parent', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Level', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Account Code', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Account Name', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Default Post', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Min Level Access', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Currency', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Seq Number', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Allow In Cashbank Module', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Analisa Divisi', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Analisa Customer', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Analisa Project', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
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
		$this->data["mainGL2Report"] = $this->glaccounts_model->get_MainGL();
		

		$side_filter = $this->parser->parse('reports/glaccounts/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "GL ACCOUNT LIST REPORT";
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
		$this->load->model('glaccounts_rpt_model');
		$this->form_validation->set_rules($this->glaccounts_rpt_model->getRules());
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
			"fin_glaccount_maingroup_id" => $this->input->post("fin_glaccount_maingroup_id"),
			"fin_glaccount_maingroup_id2" => $this->input->post("fin_glaccount_maingroup_id2"),
			"fbl_is_allow_in_cash_bank_module" => $this->input->post("fbl_is_allow_in_cash_bank_module"),
			"fbl_pc_divisi" => $this->input->post("fbl_pc_divisi"),
			"fbl_pc_customer" => $this->input->post("fbl_pc_customer"),
			"fbl_pc_project" => $this->input->post("fbl_pc_project"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->glaccounts_rpt_model->queryComplete($data,"a.fst_glaccount_code",$data['rpt_layout']);

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
						$repTitle = "LAPORAN DAFTAR GL ACCOUNT";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 12;
						break;
					default:
						$repTitle = "LAPORAN DAFTAR GL ACCOUNT";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 12;
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
                    $sheet->setCellValue("B3","Group");
                    $sheet->setCellValue("C3","Parent");
                    $sheet->setCellValue("D3","Level");
                    $sheet->setCellValue("E3","Account Code");
                    $sheet->setCellValue("F3","Account Name");
                    $sheet->setCellValue("G3","Default Post");
                    $sheet->setCellValue("H3","Min Level Access");
                    $sheet->setCellValue("I3","Currency");
                    $sheet->setCellValue("J3","Seq Number");
                    $sheet->setCellValue("K3","Allow In Cashbank Module");
					$sheet->setCellValue("L3","Analisa Divisi");
					$sheet->setCellValue("M3","Analisa Customer");
					$sheet->setCellValue("N3","Analisa Project");
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
					$nou = 0;
					//$noSO = "";
					$cellRow = 4;
					//$subQty = 0;
					//$subAmount = 0;
					//$subDiscount = 0;
					//$totalQty = 0;
					//$totalDiscount = 0;
					//$totalAmount = 0;
					$numOfRecs = count($dataReport);
					//$idx = 0;
					
					foreach($dataReport as $row){
						//$idx++;
                        $nou++;
                        $sheet->setCellValue("A".$cellRow,$nou);
                        $sheet->setCellValue("B".$cellRow,$row->fst_glaccount_maingroup_name);
						$sheet->setCellValue("C".$cellRow,$row->GLParentName);
						//Pilihan HD(Header). DT(Detail), DK(Detail Kas) , DB (Detail Bank)
						$level_name = $row->fst_glaccount_level;
						switch($level_name){
							case "HD":
								$level_name = "Header";
								break;
							case "DT":
								$level_name = "Detail";
								break;
							case "DK":
								$level_name = "Detail Kas";
								break;
							case "DB":
								$level_name = "Detail Bank";
								break;
						}
						$default_post = $row->fst_default_post;
						switch($default_post){
							case "D":
								$default_post = "DEBIT";
								break;
							case "C":
								$default_post = "CREDIT";
								break;
						}
						$fbl_is_allow_in_cash_bank_module = $row->fbl_is_allow_in_cash_bank_module;
						switch($fbl_is_allow_in_cash_bank_module){
							case "0":
								$fbl_is_allow_in_cash_bank_module = "NO";
								break;
							case "1":
								$fbl_is_allow_in_cash_bank_module = "YES";
								break;
						}
						$fbl_pc_divisi = $row->fbl_pc_divisi;
						switch($fbl_pc_divisi){
							case "0":
								$fbl_pc_divisi = "NO";
								break;
							case "1":
								$fbl_pc_divisi = "YES";
								break;
						}
						$fbl_pc_customer = $row->fbl_pc_customer;
						switch($fbl_pc_customer){
							case "0":
								$fbl_pc_customer = "NO";
								break;
							case "1":
								$fbl_pc_customer = "YES";
								break;
						}
						$fbl_pc_project = $row->fbl_pc_project;
						switch($fbl_pc_project){
							case "0":
								$fbl_pc_project = "NO";
								break;
							case "1":
								$fbl_pc_project = "YES";
								break;
						}
                        $sheet->setCellValue("D".$cellRow,$level_name);
                        $sheet->setCellValue("E".$cellRow,$row->fst_glaccount_code);
                        $sheet->setCellValue("F".$cellRow,$row->fst_glaccount_name);
                        $sheet->setCellValue("G".$cellRow,$default_post);
                        $sheet->setCellValue("H".$cellRow,$row->fin_min_user_level_access);
                        $sheet->setCellValue("I".$cellRow,$row->fst_curr_name);
                        $sheet->setCellValue("J".$cellRow,$row->fin_seq_no);
                        $sheet->setCellValue("K".$cellRow,$fbl_is_allow_in_cash_bank_module);
						$sheet->setCellValue("L".$cellRow,$fbl_pc_divisi); 
						$sheet->setCellValue("M".$cellRow,$fbl_pc_customer);
                        $sheet->setCellValue("N".$cellRow,$fbl_pc_project);                             
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
					$sheet->getStyle('A3:N'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:N3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

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

				} //end if layout 1

				if ($isPreview != 1) {
					$this->phpspreadsheet->save("GLAccounts.xls" ,$spreadsheet);
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
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relations extends MY_Controller
{

	public $layout_columns =[]; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/relations_rpt_model');
		$this->load->model('msarea_model');
        $this->load->model('msbranches_model');
		$this->load->model('mslinebusiness_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'Relation Type', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'LoB', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Business Type', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Branch', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Group', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Relation Induk', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Relation Name', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'NPWP', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Phone', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Fax', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Address', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Postal Code', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Province', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'District', 'value'=>'14', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'SubDistrict', 'value'=>'15', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Village', 'value'=>'16', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Pricing group', 'value'=>'17', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Sales Name', 'value'=>'18', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Credit Limit', 'value'=>'19', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP', 'value'=>'20', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP Commision', 'value'=>'21', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP + Commision', 'value'=>'22', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Relation Type', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'LoB', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Business Type', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Branch', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Group', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Relation Induk', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Relation Name', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'NPWP', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Phone', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Fax', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Address', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Postal Code', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Province', 'value'=>'13', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'District', 'value'=>'14', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'SubDistrict', 'value'=>'15', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Village', 'value'=>'16', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Pricing group', 'value'=>'17', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Sales Name', 'value'=>'18', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Credit Limit', 'value'=>'19', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'TOP', 'value'=>'20', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'TOP Commision', 'value'=>'21', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'TOP + Commision', 'value'=>'22', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Name', 'value'=>'23', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Area Kode', 'value'=>'24', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Shipping Address', 'value'=>'25', 'selected'=>false,'sum_total'=>false],
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

        $this->data["linebusinessList"] =$this->mslinebusiness_model->get_data_linebusiness();
		

		$side_filter = $this->parser->parse('reports/relations/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "RELATION LIST REPORT";
		$mode = "Report";
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
		$this->load->model('relations_rpt_model');
		$this->form_validation->set_rules($this->relations_rpt_model->getRules());
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
			"fin_country_id" => $this->input->post("fin_country_id"),
			"fst_area_code" => $this->input->post("fst_kode"),
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_relation_group_id" => $this->input->post("fin_relation_group_id"),
			//"fst_relation_type" => implode(",",$this->input->post("fst_relation_type")),
			//"fst_linebusiness_id" => $this->input->post("fst_linebusiness_id"),
			"fst_business_type" => $this->input->post("fst_business_type"),
			"fin_parent_id" => $this->input->post("fin_parent_id"),
			"fin_relation_id" => $this->input->post("fin_relation_id"),
			"fin_relation_id2" => $this->input->post("fin_relation_id2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		$fst_relation_type = $this->input->post("fst_relation_type");
		$fst_linebusiness_id = $this->input->post("fst_linebusiness_id");
        if ($fst_relation_type != "" ){
            $data["fst_relation_type"] = implode(",",$this->input->post("fst_relation_type"));
        }else{
			$data["fst_relation_type"] = $this->input->post("fst_relation_type");
		}

        if ($fst_linebusiness_id != "" ){
            $data["fst_linebusiness_id"] = implode(",",$this->input->post("fst_linebusiness_id"));
        }else{
			$data["fst_linebusiness_id"] = $this->input->post("fst_linebusiness_id");
		}
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->relations_rpt_model->queryComplete($data,"a.fst_relation_name",$data['rpt_layout']);

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
						$repTitle = "LAPORAN DAFTAR RELASI";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 23;
						break;
					case "2":
						$repTitle = "LAPORAN DAFTAR RELASI DETAIL ALAMAT PENGIRIMAN";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
						$repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
						$fullColumn = 26;
						break;
					default:
						$repTitle = "LAPORAN DAFTAR RELASI";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 23;
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
                    $sheet->setCellValue("B3","Relation Type");
                    $sheet->setCellValue("C3","LoB");
                    $sheet->setCellValue("D3","Business Type");
                    $sheet->setCellValue("E3","Branch");
                    $sheet->setCellValue("F3","Group");
                    $sheet->setCellValue("G3","Relation Induk");
                    $sheet->setCellValue("H3","Relation Name");
                    $sheet->setCellValue("I3","NPWP");
                    $sheet->setCellValue("J3","Phone");
                    $sheet->setCellValue("K3","Fax");
					$sheet->setCellValue("L3","Address");
					$sheet->setCellValue("M3","Postal Code");
					$sheet->setCellValue("N3","Province");
					$sheet->setCellValue("O3","District");
					$sheet->setCellValue("P3","SubDistrict");
					$sheet->setCellValue("Q3","Village");
					$sheet->setCellValue("R3","Pricing Group");
					$sheet->setCellValue("S3","Sales Name");
					$sheet->setCellValue("T3","Credit Limit");
					$sheet->setCellValue("U3","TOP");
					$sheet->setCellValue("V3","TOP Commision");
					$sheet->setCellValue("W3","TOP + Commision");
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
					$nou = 0;
					$cellRow = 4;
					$numOfRecs = count($dataReport);
					
					foreach($dataReport as $row){
						if($row->fst_business_type == 'P'){
							$row->fst_business_type = 'Personal';
						}else{
							$row->fst_business_type = 'Corporate';
						}
                        $nou++;
                        $sheet->setCellValue("A".$cellRow,$nou);
                        $sheet->setCellValue("B".$cellRow,$row->fst_relation_type);
                        $sheet->setCellValue("C".$cellRow,$row->fst_linebusiness_id);
                        $sheet->setCellValue("D".$cellRow,$row->fst_business_type);
                        $sheet->setCellValue("E".$cellRow,$row->fst_branch_name);
                        $sheet->setCellValue("F".$cellRow,$row->fst_relation_group_name);
                        $sheet->setCellValue("G".$cellRow,$row->ParentName);
                        $sheet->setCellValue("H".$cellRow,$row->fst_relation_name);
                        $sheet->setCellValue("I".$cellRow,$row->fst_npwp);
                        $sheet->setCellValue("J".$cellRow,$row->fst_phone);
                        $sheet->setCellValue("K".$cellRow,$row->fst_fax);
						$sheet->setCellValue("L".$cellRow,$row->fst_address);
						$sheet->setCellValue("M".$cellRow,$row->fst_postal_code);
						$sheet->setCellValue("N".$cellRow,$row->fst_province_name);
						$sheet->setCellValue("O".$cellRow,$row->fst_district_name); 
						$sheet->setCellValue("P".$cellRow,$row->fst_subdistrict_name);
						$sheet->setCellValue("Q".$cellRow,$row->fst_village_name);
						$sheet->setCellValue("R".$cellRow,$row->fst_cust_pricing_group_name);
						$sheet->setCellValue("S".$cellRow,$row->SalesName);
						$sheet->setCellValue("T".$cellRow,$row->fdc_credit_limit);
						$sheet->setCellValue("U".$cellRow,$row->fin_terms_payment); 
						$sheet->setCellValue("V".$cellRow,$row->fin_top_komisi);
						$sheet->setCellValue("W".$cellRow,$row->fin_top_plus_komisi);                                 
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
					$sheet->getStyle('A3:W'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:W3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					$sheet->getStyle('T4:T'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('L4:M'.$cellRow)->applyFromArray($styleArray);

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
				if  ($data['rpt_layout'] ==  2){
                    $sheet->setCellValue("A3","Nou.");
                    $sheet->setCellValue("B3","Relation Type");
                    $sheet->setCellValue("C3","LoB");
                    $sheet->setCellValue("D3","Business Type");
                    $sheet->setCellValue("E3","Branch");
                    $sheet->setCellValue("F3","Group");
                    $sheet->setCellValue("G3","Relation Induk");
                    $sheet->setCellValue("H3","Relation Name");
                    $sheet->setCellValue("I3","NPWP");
                    $sheet->setCellValue("J3","Phone");
                    $sheet->setCellValue("K3","Fax");
					$sheet->setCellValue("L3","Address");
					$sheet->setCellValue("M3","Postal Code");
					$sheet->setCellValue("N3","Province");
					$sheet->setCellValue("O3","District");
					$sheet->setCellValue("P3","SubDistrict");
					$sheet->setCellValue("Q3","Village");
					$sheet->setCellValue("R3","Pricing Group");
					$sheet->setCellValue("S3","Sales Name");
					$sheet->setCellValue("T3","Credit Limit");
					$sheet->setCellValue("U3","TOP");
					$sheet->setCellValue("V3","TOP Commision");
					$sheet->setCellValue("W3","TOP + Commision");
					$sheet->setCellValue("X3","Name");
					$sheet->setCellValue("Y3","Area Kode");
					$sheet->setCellValue("Z3","Shipping Address");
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
                    $sheet->getColumnDimension("Z")->setAutoSize(true);
					$nou = 0;
					$relation_id = "";
					$cellRow = 4;
					$numOfRecs = count($dataReport);
					$idx = 0;
					
					foreach($dataReport as $row){
						$idx++;
						if ($relation_id != $row->fin_relation_id){

							$relation_id = $row->fin_relation_id;
							if($row->fst_business_type == 'P'){
								$row->fst_business_type = 'Personal';
							}else{
								$row->fst_business_type = 'Corporate';
							}
							$nou++;
							$sheet->setCellValue("A".$cellRow,$nou);
							$sheet->setCellValue("B".$cellRow,$row->fst_relation_type);
							$sheet->setCellValue("C".$cellRow,$row->fst_linebusiness_id);
							$sheet->setCellValue("D".$cellRow,$row->fst_business_type);
							$sheet->setCellValue("E".$cellRow,$row->fst_branch_name);
							$sheet->setCellValue("F".$cellRow,$row->fst_relation_group_name);
							$sheet->setCellValue("G".$cellRow,$row->ParentName);
							$sheet->setCellValue("H".$cellRow,$row->fst_relation_name);
							$sheet->setCellValue("I".$cellRow,$row->fst_npwp);
							$sheet->setCellValue("J".$cellRow,$row->fst_phone);
							$sheet->setCellValue("K".$cellRow,$row->fst_fax);
							$sheet->setCellValue("L".$cellRow,$row->fst_address);
							$sheet->setCellValue("M".$cellRow,$row->fst_postal_code);
							$sheet->setCellValue("N".$cellRow,$row->fst_province_name);
							$sheet->setCellValue("O".$cellRow,$row->fst_district_name); 
							$sheet->setCellValue("P".$cellRow,$row->fst_subdistrict_name);
							$sheet->setCellValue("Q".$cellRow,$row->fst_village_name);
							$sheet->setCellValue("R".$cellRow,$row->fst_cust_pricing_group_name);
							$sheet->setCellValue("S".$cellRow,$row->SalesName);
							$sheet->setCellValue("T".$cellRow,$row->fdc_credit_limit);
							$sheet->setCellValue("U".$cellRow,$row->fin_terms_payment); 
							$sheet->setCellValue("V".$cellRow,$row->fin_top_komisi);
							$sheet->setCellValue("W".$cellRow,$row->fin_top_plus_komisi);                                
							//$cellRow++;

						}
						$sheet->setCellValue("X".$cellRow,$row->fst_name);
						$sheet->setCellValue("Y".$cellRow,$row->fst_village_nameShipp);
						$sheet->setCellValue("Z".$cellRow,$row->fst_shipping_address);
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
					$sheet->getStyle('A3:Z'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:Z3')->applyFromArray($styleArray);
					$sheet->getStyle('A3:A'.$cellRow)->applyFromArray($styleArray);

					$styleArray = [
						'numberFormat'=> [
							'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
						]
					];
					$sheet->getStyle('T4:T'.$cellRow)->applyFromArray($styleArray);
					//$sheet->getStyle('L4:M'.$cellRow)->applyFromArray($styleArray);

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

				} //End Of Layout 2

				if ($isPreview != 1) {
					$this->phpspreadsheet->save("Master Relations.xls" ,$spreadsheet);
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
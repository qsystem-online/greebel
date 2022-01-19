<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

	public $layout_columns =[]; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/users_rpt_model');
		$this->load->model('usersgroup_model');
        $this->load->model('msbranches_model');
        $this->load->model('msdepartments_model');

		$this->layout_columns = [
            ['layout' => 1, 'label'=>'Nou.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
            ['layout' => 1, 'label'=>'ID', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'User', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nama Lengkap', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Gender', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl Lahir', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Alamat', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Telephone', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Email', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Cabang', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Departemen', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Group', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Special Access', 'value'=>'12', 'selected'=>false,'sum_total'=>false],
		];

	}

	public function index()
	{
		$this->loadForm();
	}

	public function loadForm()
	{
		$this->load->library('menus');
		// $this->list['page_name'] = "Branch";
		// $this->list['list_name'] = "Branch List";
		// $this->list['addnew_ajax_url'] = site_url() . 'master/branch/add';
		// $this->list['pKey'] = "id";
		// $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/branch/fetch_list_data';
		// $this->list['delete_ajax_url'] = site_url() . 'master/branch/delete/';
		// $this->list['edit_ajax_url'] = site_url() . 'master/branch/edit/';
		// $this->list['arrSearch'] = [
		//     'fin_branch_id' => 'Branch ID',
		//     'fst_branch_name' => 'Branch Name'
		// ];

		// $this->list['breadcrumbs'] = [
		//     ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
		//     ['title' => 'Branch', 'link' => '#', 'icon' => ''],
		//     ['title' => 'List', 'link' => NULL, 'icon' => ''],
		// ];
		// $this->list['columns'] = [
		//     ['title' => 'Branch ID', 'width' => '5%', 'data' => 'fin_branch_id'],
		//     ['title' => 'Branch Name', 'width' => '15%', 'data' => 'fst_branch_name'],
		//     ['title' => 'Phone', 'width' => '10%', 'data' => 'fst_branch_phone'],
		//     ['title' => 'Notes', 'width' => '15%', 'data' => 'fst_notes'],
		//     ['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-center']
		// ];
		


		// $layout_columns = [['layout' => 1, 'label'=>'Nou.', 'title'=>'Nou.','value'=>1, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'No.SO', 'title'=>'No.SO','value'=>2, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'Tgl.SO', 'title'=>'Tgl.SO','value'=>3, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'Pelanggan', 'title'=>'Pelanggan','value'=>4, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'Sales', 'title'=>'Sales','value'=>5, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'Kode Barang', 'title'=>'Kode Barang','value'=>6, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'Nama Barang', 'title'=>'Nama Barang','value'=>7, 'selected'=>true],
		//                 ['layout' => 1, 'label'=>'Qty', 'title'=>'Qty','value'=>8, 'selected'=>true],
		//                 ['layout' => 2, 'label'=>'Nou.', 'title'=>'Nomor','value'=>1, 'selected'=>true],
		//                 ['layout' => 2, 'label'=>'No.SO', 'title'=>'No.SO','value'=>2, 'selected'=>true],
		//                 ['layout' => 2, 'label'=>'Tgl.SO', 'title'=>'Tgl.SO','value'=>3, 'selected'=>true],
		//                 ['layout' => 2, 'label'=>'Pelanggan', 'title'=>'Pelanggan','value'=>4, 'selected'=>true],
		//                 ['layout' => 2, 'label'=>'Sales', 'title'=>'Sales','value'=>5, 'selected'=>true],
		//                 ['layout' => 2, 'label'=>'Nilai SO', 'title'=>'Nilai SO','value'=>6, 'selected'=>true]
		//                 ];
						
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$fin_branch_id = 0;

		$this->data["fin_branch_id"] = $fin_branch_id;
		$this->data["mystatus"]="OK";
		$this->data["layout_columns"] = $this->layout_columns;
		

		$side_filter = $this->parser->parse('reports/users/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "USER LIST REPORT";
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
		$this->load->model('users_rpt_model');
		$this->form_validation->set_rules($this->users_rpt_model->getRules());
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
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_group_id" => $this->input->post("fin_group_id"),
			"fin_department_id" => $this->input->post("fin_department_id"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];
		
		// print_r($data['selected_columns'][0]);die;
		

		$dataReport = $this->users_rpt_model->queryComplete($data,"a.fin_user_id",$data['rpt_layout']);

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
						$repTitle = "LAPORAN DAFTAR USER";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 13;
						break;
					default:
						$repTitle = "LAPORAN DAFTAR USER";
						$repPaperSize=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL;
                        $repOrientation=\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                        $fullColumn = 13;
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
                    $sheet->setCellValue("B3","ID");
                    $sheet->setCellValue("C3","User");
                    $sheet->setCellValue("D3","Nama Lengkap");
                    $sheet->setCellValue("E3","Gender");
                    $sheet->setCellValue("F3","Tgl Lahir");
                    $sheet->setCellValue("G3","Alamat");
                    $sheet->setCellValue("H3","Telephone");
                    $sheet->setCellValue("I3","Email");
                    $sheet->setCellValue("J3","Cabang");
                    $sheet->setCellValue("K3","Department");
                    $sheet->setCellValue("L3","Group");
					$sheet->setCellValue("M3","Special Access");
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
						if($row->fst_gender =="F"){
							$row->fst_gender ="Female";
						}else{
							$row->fst_gender ="Male";
						}
						if($row->fbl_admin =="1"){
							$row->fbl_admin ="YES";
						}else{
							$row->fbl_admin ="NO";
						}
                        $nou++;
                        $sheet->setCellValue("A".$cellRow,$nou);
                        $sheet->setCellValue("B".$cellRow,$row->fin_user_id);
                        $sheet->setCellValue("C".$cellRow,$row->fst_username);
                        $sheet->setCellValue("D".$cellRow,$row->fst_fullname);
                        $sheet->setCellValue("E".$cellRow,$row->fst_gender);
                        $sheet->setCellValue("F".$cellRow,$row->fdt_birthdate);
                        $sheet->setCellValue("G".$cellRow,$row->fst_address);
                        $sheet->setCellValue("H".$cellRow,$row->fst_phone);
                        $sheet->setCellValue("I".$cellRow,$row->fst_email);
                        $sheet->setCellValue("J".$cellRow,$row->fst_branch_name);
                        $sheet->setCellValue("K".$cellRow,$row->fst_department_name);
                        $sheet->setCellValue("L".$cellRow,$row->fst_group_name);
						$sheet->setCellValue("M".$cellRow,$row->fbl_admin);                             
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
					$sheet->getStyle('A3:M'.$cellRow)->applyFromArray($styleArray);
		
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
					$sheet->getStyle('A3:M3')->applyFromArray($styleArray);
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
					$this->phpspreadsheet->save("User_List.xls" ,$spreadsheet);
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class Test extends CI_Controller {

	
	public function test1($var){
		echo "uri string : ". uri_string();
		echo "<br>";
		echo "site_url : ". site_url();
		echo "<br>";
		echo "site_url (uri_string) : ". site_url(uri_string());
		echo "<br>";
		
	}


	public function index(){
		$this->my_model->throwIfDBError();
		
	}
	public function testException(){
		$this->load->model("trinvoice_model");	
		
		$this->db->trans_commit();

		$this->trinvoice_model->test_exception();

		/*
		try{
			echo "befor TEST";
			$this->trinvoice_model->test_exception();
			echo "after TEST";
		}catch(CustomException $e){
			//var_dump($e);
			echo "KENA EXCEPTION ! :";
			echo $e->getMessage();
			var_dump($e->getData());
		}
		*/
	}


	public function test_page(){
		$this->parser->parse('pages/sample/test',[]);
	}


	public function test_ajax(){
		//echo "AJAX REQUEST :" .$this->input->is_ajax_request();
		$this->ajxResp["status"] = AJAX_STATUS_SESSION_EXPIRED;
		$this->json_output(403);
	}

	public function get_file(){
		$this->load->helper('download');
		$this->load->helper('file');
		
		$ssql = "select * from permission_token where fst_token = '123456' and fbl_active = true";
		$qr = $this->db->query($ssql,[]);
		$rw = $qr->row();
		if ($rw){
			$data = ["fbl_active"=>false];
			$this->db->where("fst_token","123456");
			//$this->db->update("permission_token",$data);

			$fileLoc ='d:\\test.pdf';
			$string = read_file($fileLoc);
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=download.pdf");
			echo $string;
		}else{
			die();
		}
		//die();		
		//force_download($fileLoc, NULL,true);
		//echo file_get_contents('http://some.secret.location.com/secretfolder/the_file.tar.gz');
	}

	public function testdb(){
		/*
		echo "Test DB";
		for($i = 0;$i < 50;$i++){
			$qr = $this->db->query("select * from users",[]);
		}

		
		print_r($qr->result());
		*/
		$this->load->library("menus");
		$sstr = $this->menus->build_menu();
		var_dump($sstr);

	}

	public function info(){
		phpinfo();
	}
	public function testdebug(){
		$i =0 ;
		if ($i == 0){
			echo "TRUE";
		}else{
			echo "FALSE";
		}
	}


	public function testExcel(){

		$this->load->library("phpspreadsheet");
		$spreadsheet = $this->phpspreadsheet->load(FCPATH . "assets/templates/coba.xlsm");
		/*
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue("A1", "No.");

		for( $i = 0; $i < 10 ; $i++){
			//
			$baris = $i + 2;
			$sheet->setCellValue("A". $baris, $i);
		}
		*/
		//FILE NAME WITH DATE
		$this->phpspreadsheet->save("tesss",$spreadsheet);
        //$this->phpspreadsheet->save("item_report_" . date("Ymd") . ".xlsm" ,$spreadsheet);
	}

	public function testimg(){
		/*
		//$newImage="D:\\xampp\\htdocs\\comextra\\uploads\\cm_ereservation\\catalog\\26\\thumb\\2019-10-27cm_eres_catalog200440.jpg";
		$src="D:\\xampp\\htdocs\\comextra\\uploads\\cm_ereservation\\catalog\\26\\2019-10-27cm_eres_catalog200440.jpg";
		list($srcWidth, $srcHeight) = getimagesize($src);
		$width = 150;
		$height = 80;

		$thumb = imagecreatetruecolor($width, $height);

		$oldImage = imagecreatefromstring(file_get_contents($src));


		imagecopyresized($thumb, $oldImage, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
		// Content type
		header('Content-Type: image/jpeg');
		imagejpeg($thumb);
		*/

		$this->load->library("image_lib");
		$config['image_library'] = 'gd2';
		$config['source_image'] = "D:\\xampp\\htdocs\\comextra\\uploads\\cm_ereservation\\catalog\\26\\2019-10-27cm_eres_catalog200440.jpg";
		$config['new_image'] = "D:\\xampp\\htdocs\\comextra\\uploads\\cm_ereservation\\catalog\\26\\thumb\\2019-10-27cm_eres_catalog200440.jpg";		
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']         = 75;
		$config['height']       = 50;

		//$this->load->library('image_lib', $config);
		$this->image_lib->initialize($config);
		
		$this->image_lib->resize();
	}
	
	public function test_background(){
		$this->load->helper('array');
		$this->load->helper('string');

		ignore_user_abort(true);
		set_time_limit(0);
		ini_set('max_execution_time', 0);

		$arrKeyInt =[1,2,3,4,5,6,7,8,9,10];
		$arrKeyChar25 = ["AAAAAAAAAA","BBBBBBBBBB","CCCCCCCCCC","DDDDDDDDDD","EEEEEEEEEE"];
		$arrKeyChar50 = ["AAAAAAAAAA","BBBBBBBBBB","CCCCCCCCCC","DDDDDDDDDD","EEEEEEEEEE"];
		$arrKeyDate = ["2019-11-02","2019-11-03","2019-09-03","2019-01-02","2019-11-20"];
		$arrKeyDatetime = ["2019-11-02 12:03:22","2019-11-03 14:20:32","2019-09-03 09:32:11","2019-01-02 02:23:43","2019-11-20 14:11:49"];
		$datas = [];

		$db2 = $this->load->database('default_local', TRUE);

		for($y = 0 ; $y < 5000000 ; $y++){
			$data["key_int"] = random_element($arrKeyInt);
			$data["key_char_25"] = random_element($arrKeyChar25);
			$data["key_char_50"] = random_element($arrKeyChar50);
			$data["key_date"] = random_element($arrKeyDate);
			$data["key_datetime"]= random_element($arrKeyDatetime);
			$data["key_text"] = random_element($arrKeyChar50);
			for($i = 1;$i <=20 ;$i++){
				$data["val_col_$i"] = random_string('alnum', 100);
			}
			$data["val_int"] = rand(10000,9999999);
			$data["val_dec"] = $data["val_int"] / rand(2,9);
			$data["val_double"] = (double) $data["val_int"] / rand(2,9);

			if($y % 1000 == 0){
				$db2->insert_batch("tbl_test",$datas);
				$dbError  = $db2->error();
				if ($dbError["code"] != 0){	
					var_dump($dbError);				
				}
				$db2->close();
				$db2 = $this->load->database('default_local', TRUE);
				$datas=[];
			}else{
				$datas[] = $data;
			}
			echo "Progress i :$y \n";
    		flush();			
		}		
	}
	
	public function test_report($type = "pdf"){		
		switch ($type){
			case "pdf":
				$this->load->library("pdf");
				$this->pdf->load_view('test/test_report', []);
				break;
			case "excel":
				$this->load->library("phpspreadsheet");
				$page_content = $this->parser->parse('test/test_report', [], true);
				$spreadsheet = $this->phpspreadsheet->loadFromHTMLString($page_content);
				$this->phpspreadsheet->save("sample.xls",$spreadsheet);
				break;
			case "simple_excel":
				header("Content-type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename=hasil.xls");
				$page_content = $this->parser->parse('test/test_report', [], true);
				echo $page_content;
				break;
			case "advance_excel":
				$this->generateTestReport();
				break;
			default:
				break;
		}		
	}
	private function generateTestReport(){				
		$this->load->library("phpspreadsheet");
		$spreadsheet = $this->phpspreadsheet->load();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		$sheet->getPageMargins()->setTop(1);
		$sheet->getPageMargins()->setRight(0.5);
		$sheet->getPageMargins()->setLeft(0.5);
		$sheet->getPageMargins()->setBottom(1);
		
		//AUTO SIZE COLUMN
        $sheet->getColumnDimension("A")->setAutoSize(true);
        $sheet->getColumnDimension("B")->setAutoSize(true);
        $sheet->getColumnDimension("C")->setAutoSize(true);
        $sheet->getColumnDimension("D")->setAutoSize(true);
        $sheet->getColumnDimension("E")->setAutoSize(true);
        $sheet->getColumnDimension("F")->setAutoSize(true);
		$sheet->getColumnDimension("G")->setAutoSize(true);
		
		$sheet->setCellValue("A1", "TESTING DIV");
		$sheet->mergeCells('A2:F2');
		$sheet->setCellValue("A2", "Laporan Testing");

		$sheet->setCellValue("A3","Name");
		$sheet->setCellValue("B3","Position");
		$sheet->setCellValue("C3","Office");
		$sheet->setCellValue("D3","Age");
		$sheet->setCellValue("E3","Start date");
		$sheet->setCellValue("F3","Salary");


		//Detail
		$i = 4;
		$sheet->setCellValue("A$i","Tiger Nixon");
		$sheet->setCellValue("B$i","System Architect");
		$sheet->setCellValue("C$i","Edinburgh");
		$sheet->setCellValue("D$i","61");
		$sheet->setCellValue("E$i","2011/04/25");
		$sheet->setCellValue("F$i","$320,800");

		$i++;
		$sheet->setCellValue("A$i","Garrett Winters");
		$sheet->setCellValue("B$i","Accountant");
		$sheet->setCellValue("C$i","Tokyo");
		$sheet->setCellValue("D$i","63");
		$sheet->setCellValue("E$i","2011/07/25");
		$sheet->setCellValue("F$i","$170,750");
		
		

		$i++;
		$sheet->setCellValue("A$i","Ashton Cox");
		$sheet->setCellValue("B$i","Junior Technical Author");
		$sheet->setCellValue("C$i","San Francisco");
		$sheet->setCellValue("D$i","66");
		$sheet->setCellValue("E$i","2009/01/12");
		$sheet->setCellValue("F$i","$86,000");

		$i++;
		$sheet->setCellValue("A$i","Cedric Kelly");
		$sheet->setCellValue("B$i","Senior Javascript Developer");
		$sheet->setCellValue("C$i","Edinburgh");
		$sheet->setCellValue("D$i","22");
		$sheet->setCellValue("E$i","2012/03/29");
		$sheet->setCellValue("F$i","$433,060");
	

		$i++;
		$sheet->setCellValue("A$i","Airi Satou");
		$sheet->setCellValue("B$i","Accountant");
		$sheet->setCellValue("C$i","Tokyo");
		$sheet->setCellValue("D$i","33");
		$sheet->setCellValue("E$i","2008/11/28");
		$sheet->setCellValue("F$i","$162,700");


		$i++;
		$sheet->setCellValue("A$i","Brielle Williamson");
		$sheet->setCellValue("B$i","Integration Specialist");
		$sheet->setCellValue("C$i","New York");
		$sheet->setCellValue("D$i","61");
		$sheet->setCellValue("E$i","2012/12/02");
		$sheet->setCellValue("F$i","$372,000");

		$arrData =[
			["Herrod Chandler","Sales Assistant","San Francisco","59","2012/08/06","$137,500"],
			["Rhona Davidson","Integration Specialist","Tokyo","55","2010/10/14","$327,900"],
			["Colleen Hurst","Javascript Developer","San Francisco","39","2009/09/15","$205,500"],
			["Sonya Frost","Software Engineer","Edinburgh","23","2008/12/13","$103,600"],
			["Jena Gaines","Office Manager","London","30","2008/12/19","$90,560"],
			["Quinn Flynn","Support Lead","Edinburgh","22","2013/03/03","$342,000"]
		];

		for($y=0;$y<sizeof($arrData);$y++){
			$data = $arrData[$y];
			$posRow = $y + $i;

			$sheet->setCellValue("A$posRow",$data[0]);
			$sheet->setCellValue("B$posRow",$data[1]);
			$sheet->setCellValue("C$posRow",$data[2]);
			$sheet->setCellValue("D$posRow",$data[3]);
			$sheet->setCellValue("E$posRow",$data[4]);
			$sheet->setCellValue("F$posRow",$data[5]);
		};

		/**
		 * Styling
		 * https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style.html
		 */
		//BORDER
        $styleArray = [
            'borders' => [
                'allBorders' => [
					//https://phpoffice.github.io/PhpSpreadsheet/1.1.0/PhpOffice/PhpSpreadsheet/Style/Border.html
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];
		$sheet->getStyle('A2:F14')->applyFromArray($styleArray);

		//FONT BOLD & Center
		$styleArray = [
            'font' => [
                'bold' => true,
			],
			'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
		];
		$sheet->getStyle('A2')->applyFromArray($styleArray);
		$sheet->getStyle('A3:F3')->applyFromArray($styleArray);
		
		$styleArray = [
            'font' => [
				'bold' => true,
				'size' => 24,
			],
		
		];
		$sheet->getStyle('A1')->applyFromArray($styleArray);


		$this->phpspreadsheet->save("hasil.xls" ,$spreadsheet);

		
	}
	
	public function loadTest(){
		$this->parser->parse('pages/sample/test',[]);
	}

	public function loadDataTest(){
		$divisi = $this->input->get("divisi");

		$arr = [1,2,3,4,5,6,7,8,9,"aaaa" . $divisi,"bbbb sdcsdfsdf sdfsdfsdf sdfsdfsdfsdfsdf sdfsdfsdfsdfsdfsdf bb"];
		echo json_encode($arr);

	}

	public function mpdf(){
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<h1>Hello world!</h1>');
		$mpdf->Output();
	}


	public function test3(){
		
		$writer = WriterEntityFactory::createXLSXWriter();
		$fileName = "test3.xlsx";
		
		//$writer->openToFile($filePath); // write data to a file or to a PHP stream
		$writer->openToBrowser($fileName); // stream data directly to the browser

		$cells = [
			WriterEntityFactory::createCell('Carl'),
			WriterEntityFactory::createCell('is'),
			WriterEntityFactory::createCell('great!'),
		];

		/** add a row at a time */
		$singleRow = WriterEntityFactory::createRow($cells);
		$writer->addRow($singleRow);

		/** add multiple rows at a time */
		$multipleRows = [
			WriterEntityFactory::createRow($cells),
			WriterEntityFactory::createRow($cells),
		];
		$writer->addRows($multipleRows); 

		/** Shortcut: add a row from an array of values */
		$values = ['Carl', 'is', 'great!'];
		$rowFromValues = WriterEntityFactory::createRowFromArray($values);
		$writer->addRow($rowFromValues);

		$writer->close();



	}



	public function testExcel2(){
		$this->load->library("phpspreadsheet");
		$spreadsheet = $this->phpspreadsheet->load();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		$sheet->getPageMargins()->setTop(1);
		$sheet->getPageMargins()->setRight(0.5);
		$sheet->getPageMargins()->setLeft(0.5);
		$sheet->getPageMargins()->setBottom(1);

		$dataArr = [];
		for($i=0;$i<3000;$i++){
			$arr = [];
			for($j=0;$j<20;$j++){
				$col = $this->phpspreadsheet->getNameFromNumber($j);
				$sheet->setCellValue("$col$i","Ini row $i col $col " . date("Ymd His"));
				//$arr[] = "Ini row $i col $col " . date("Ymd His");
			}			
			//$dataArr[] = $arr;
		}

		//$sheet->fromArray($dataArr,null,"A1");		
		$this->phpspreadsheet->save("hasil.xls" ,$spreadsheet);
	}

	public function testExcel3(){
	
		$this->parser->parse('test/excel3',[]);
	
	}



	public function testSize(){
		//var_dump(PHP_OS);
		//var_dump(FCPATH );
		//die();
		$path = FCPATH . ".." ."/eticketing/assets/app/tickets/image" ;
		$path = str_replace("/",DIRECTORY_SEPARATOR,$path);
		var_dump($path);		
		//die();
		$total_size = foldersize($path);//$this->dirSize($path);
		var_dump(format_size($total_size,"MB"));		
		//var_dump($total_size);		
	}
	
	


}

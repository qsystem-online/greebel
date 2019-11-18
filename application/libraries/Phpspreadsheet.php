<?php defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Phpspreadsheet extends Spreadsheet {
	
	public $spreadsheet;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function save($filename,$spreadsheet = null,$fileType="xls"){
		
		$spreadsheet =  $spreadsheet == null ? $this->spreadsheet : $spreadsheet;
		$fileEXT = "";

		switch (strtoupper($fileType)){
			case "XLS":
				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xls");
				$fileEXT = "xls";
				break;
			case "XLSX":
				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
				$fileEXT = "xlsx";
				break;
			case "XLSM":
				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
				$fileEXT = "xlsm";
				break;
			default:
				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xls");
				$fileEXT = "xls";
		}		
		
		$filename = $filename; //. "." . $fileEXT;

		//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");		
		header('Content-Type: application/vnd.ms-excel'); // generate excel file
		header('Content-Disposition: attachment;filename="'. $filename .'"'); 
		header('Cache-Control: max-age=0');        
		$writer->save('php://output');	// download file 
		$spreadsheet->disconnectWorksheets();
		unset($spreadsheet);		
	}

	public function saveHTML($filename){
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($this->spreadsheet);		
		//$writer->save("05featuredemo.htm");
		$writer->save('php://output');
	}

	public function savePDF(){
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Dompdf');
		header("Content-type:application/pdf");
		//header("Content-Disposition:attachment;filename='downloaded.pdf'");
		$writer->save('php://output');

	}

	public function load($filename = null,$fileType="xlsx"){	
		
		if($filename == null){
			$this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		}else{
			switch (strtoupper($fileType)){
				case "XLS":
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
					$fileEXT = "xls";
					break;
				case "XLSX":
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
					break;
				case "XLSM":
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
					$fileEXT = "xlsm";
					break;
				default:
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
					$fileEXT = "xls";
			}			
			$this->spreadsheet = $reader->load($filename);
		}
		
		
		//$spreadsheet = IOFactory::load($filename);		
		return $this->spreadsheet;


	}
	
	public function protectSheet($sheet,$password){
		$sheet->getProtection()->setPassword($password);
		$sheet->getProtection()->setSheet(true);
		$sheet->getProtection()->setSort(true);
		$sheet->getProtection()->setInsertRows(true);
		$sheet->getProtection()->setFormatCells(true);
	
	}

	public function getNameFromNumber($num) {
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
			return getNameFromNumber($num2 - 1) . $letter;
		} else {
			return $letter;
		}
	}
	
	public function testing(){

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$filename ="coba";
		$writer->save($filename.'.xlsm');

		/*macro OK
		//$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(FCPATH . "assets\\templates\\template_macro.xlsm");
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		//$spreadsheet = $reader->load(FCPATH . "assets\\templates\\template_sales_log.xlsx");
		$spreadsheet = $reader->load(FCPATH . "assets\\templates\\template_macro.xlsm");
		//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$filename ="coba";
		$writer->save($filename.'.xlsm');
		*/

	}
	
}
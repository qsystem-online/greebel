<?php defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Phpspreadsheet extends Spreadsheet {
	
	
	public function __construct(){
		parent::__construct();
	}
	
	public function save($filename,$spreadsheet = null){
		
		$spreadsheet =  $spreadsheet == null ? $this : $spreadsheet;
		//$writer = new Xlsx($spreadsheet);
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

		header('Content-Type: application/vnd.ms-excel'); // generate excel file
		header('Content-Disposition: attachment;filename="'. $filename . '"'); 
        header('Cache-Control: max-age=0');        
        $writer->save('php://output');	// download file 		
	}

	public function load($filename = null){
		//$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template.xlsx');
		//$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
		if($filename == null){
			return new Spreadsheet();
		}
		$spreadsheet = IOFactory::load($filename);
		return $spreadsheet;
		
		/*
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template.xlsx');
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet->getCell('A1')->setValue('John');
		$worksheet->getCell('A2')->setValue('Smith');
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
		$writer->save('write.xls');
		*/


	}
	
	public function test(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
		//$writer = new Xlsx($spreadsheet);
		//$writer->save('hello world.xlsx');
		header('Content-Type: application/vnd.ms-excel'); // generate excel file
		header('Content-Disposition: attachment;filename="hello_world.xls"'); 
		header('Cache-Control: max-age=0');        
		$writer->save('php://output');	// download file 		
	}

	
}
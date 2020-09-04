<?php defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;

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
		
		$writer->setPreCalculateFormulas(false);
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

		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setFitToPage(false);
		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setScale(50);
		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		//$this->spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);

		$this->spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.2);
		$this->spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.2);

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Dompdf');
		//$writer->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
		//$writer->setPaperSize(PageSetup::ORIENTATION_PORTRAIT);
		//$writer->setHorizontalCentered(true);
		
		//$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Mpdf');
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

	public function loadFromHTMLString($htmlString){

		//$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
		//$this->spreadsheet = $reader->loadFromString($htmlString);
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();	
		$spreadsheet = $reader->loadFromString($htmlString);


		return $spreadsheet;
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

	public function saveHTMLvia($spreadsheet){
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);		
		//$writer->save("05featuredemo.htm");
		$writer->save('php://output');
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
	

	public function cleanColumns(&$sheet,$ttlCol,$selectedCol){
		$sheet->insertNewRowBefore(1, 1);		
		
		//$sheet->getCell("A1")->setValue("HAPUS");
		//$sheet->getCell("B1")->setValue("JANGAN");
		//$sheet->getCell("C1")->setValue("JANGAN");
		//$sheet->getCell("D1")->setValue("JANGAN");
		//$sheet->getCell("E1")->setValue("JANGAN");

		
		for($i=0;$i<$ttlCol;$i++){
			$colName = $this->getNameFromNumber($i);
			if (!in_array($i, $selectedCol)){								
				$sheet->getCell("$colName"."1")->setValue("HAPUS");
			}else{
				$sheet->getCell("$colName"."1")->setValue("VIEW");
			}			
		}
				

		//$doDelete = true;
		$colIndex = 0;
		for($i=0;$i<$ttlCol;$i++){
			$colName = $this->getNameFromNumber($colIndex);
			//$colName = $this->getNameFromNumber($i);
			$colFlag =$colName ."1";
			$val=null;
			$val = $sheet->getCell($colFlag)->getvalue();
			$val2 = $sheet->getCell("$colName"."4")->getvalue();
			if ($val == "HAPUS"){
				$sheet->removeColumn($colName);
				//$sheet->removeColumnByIndex($colIndex+1);
				//$i=-1;
			}else{
				$colIndex++;
			}			
			//$colIndex++;
		}		
		$sheet->removeRow(1);							
	}

	public function getSumColPosition($layoutColumn,$layoutNo,$selectedCol){
		$colIndex = 0;
		foreach($layoutColumn as $lay){			
			if($lay["layout"] == $layoutNo){
				if (in_array($lay["value"], $selectedCol)){								
					if ($lay["sum_total"] == true){
						break;
					}else{
						$colIndex++;
					}
				}
			}			
		}
		
		return $colIndex;
	}

	public function mergedData(&$sheet,$arrMerged,$ttlCol,$sumCol){				
		$ttlCol = $this->getNameFromNumber($ttlCol-1);
		$sumCol = $this->getNameFromNumber($sumCol-1);
		foreach($arrMerged as $merged){
			if ($merged[1] == "FULL"){
				$sheet->mergeCells("A$merged[0]:$ttlCol".$merged[0]);   
			}else{
				$sheet->mergeCells("A$merged[0]:$sumCol".$merged[0]);   
			}
		}
	
	}
	
	public function writeCell(&$sheet, $row, $col, $cellValue) {
        $sheet->setCellValue($col.$row,$cellValue);
    }
}
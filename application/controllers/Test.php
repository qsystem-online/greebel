<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

		for($y = 0 ; $y < 5000000 ; $y++){
			$data["key_int"] = random_element($arrKeyInt);
			$data["key_char_25"] = random_element($arrKeyChar25);
			$data["key_char_50"] = random_element($arrKeyChar50) . $y;
			$data["key_date"] = random_element($arrKeyDate);
			$data["key_datetime"]= random_element($arrKeyDatetime);
			$data["key_text"] = random_element($arrKeyChar50);
			for($i = 1;$i <=20 ;$i++){
				$data["val_col_$i"] = random_string('alnum', 100);
			}
			$data["val_int"] = rand(10000,9999999);
			$data["val_dec"] = $data["val_int"] / rand(2,9);
			$data["val_double"] = (double) $data["val_int"] / rand(2,9);
			$this->db->insert("tbl_test",$data);
		}		
	}
	
	
	
	
}

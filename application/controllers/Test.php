<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {

	
	public function test1($var){
		echo "uri string : ". uri_string();
		echo "<br>";
		echo "site_url : ". site_url();
		echo "<br>";
		echo "site_url (uri_string) : ". site_url(uri_string());
		echo "<br>";
		
	}


	public function index(){
		$this->load->library('unit_test');
		$test = dBDateFormat("20-04-2019");
		$expected_result = "2019-04-20";
		$test_name = 'Test dBdateFormat';
		$this->unit->run($test, $expected_result, $test_name,$test);
		

		$test = parseNumber("200.000.000,15",",");
		$expected_result = (float) 200000000.15;
		$test_name = 'Test parseNumber';
		$this->unit->run($test, $expected_result, $test_name,$test ." vs " .$expected_result);
		
		echo $this->unit->report();

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

}

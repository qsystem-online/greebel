<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BackgroundProcess{
	public $ci;
	public function __construct(){
		$this->ci =& get_instance();
	}

	public function echo_test(){
		echo "BackgroundProcess : Echo Test";
	}
	public function do_in_background($url, $params=[]){
		//csr
		//__comextracom=rjqrgm304emv826adalb69utep6roc98
		$csrf_cookie_name =  $this->ci->config->item("csrf_cookie_name");
		$csrf_cookie_value = $this->ci->input->cookie($csrf_cookie_name);
		$params[$this->ci->security->get_csrf_token_name()] = $this->ci->security->get_csrf_hash();
		$params["nama"] = "Devi Bastian";

		$post_string = http_build_query($params);
		$parts = parse_url($url);
		$errno = 0;
		$errstr = "";
		//echo "=================cookies================<br>";
		//print_r($this->ci->input->cookie());
		//echo "<br>=============end cookies================<br>";
		//echo "=================Data POST================<br>";
		//print_r($post_string);
		//echo "<br>=============End Data POST================<br>";
		
		//Use SSL & port 443 for secure servers
		//Use otherwise for localhost and non-secure servers
		//For secure server

		/*
		$fp = fsockopen('ssl://' . $parts['host'], 
			isset($parts['port']) ? $parts['port'] : 443, 
			$errno, $errstr, 30);
		*/

		//For localhost and un-secure server
		//echo "Background start " . date("Y-m-d H:i:s") ."<br> " ;
		//print_r($parts);
		//echo "<br>";
		$fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
		if(!$fp){
			echo "Some thing Problem";    
		}

		//Array ( [scheme] => http [host] => cmdemo.bastian.com [path] => /task/sent_email ) 
		//POST /task/sent_email HTTP/1.1 Host: cmdemo.bastian.com Content-Type: application/x-www-form-urlencoded Content-Length: 0 Connection: Close 

		$out = "POST ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Cookie:$csrf_cookie_name=".$csrf_cookie_value."\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: close\r\n\r\n";
		if (isset($post_string)){
			$out.= $post_string ."\r\n\r\n";	
		} 

		//echo $out;
		//echo "<br>";
		fwrite($fp, $out);

		//header('Content-type: text/plain');
		/*
		echo "======================================================================<br>";
		header('Content-type: text/html');		
		while (!feof($fp)) {
    		echo fgets($fp, 1024);
		}
		echo "<br>======================================================================<br>";
		*/		
		fclose($fp);
		//echo "Background End " . date("Y-m-d H:i:s") ."<br> " ;
	}
}
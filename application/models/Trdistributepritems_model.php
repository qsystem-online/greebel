<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trdistributepritems_model extends MY_Model {
	public $tableName = "trdistributepritems";
	public $pkey = "fin_rec_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];				
		return $rules;
	}
}



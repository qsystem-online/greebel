<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlpbsalesreturnitems_model extends MY_Model {
	public $tableName = "trlpbsalesreturnitems";
	public $pkey = "fin_rec_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fin_lpbsalesreturn_id',
			'label' => 'ID Penerimaan Retur',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];      
		return $rules;
	}
}



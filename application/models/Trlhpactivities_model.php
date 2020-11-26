<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlhpactivities_model extends MY_Model {
	public $tableName = "trlhpactivities";
	public $pkey = "fin_rec_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];
		$rules[] = [
			'field' => 'fst_lhp_id',
			'label' => 'LHP ID',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];		
		return $rules;
	}
}
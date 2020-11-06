<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trrmoutreturnitems_model extends MY_Model{
	public $tableName = "trrmoutreturnitems";
	public $pkey = "fin_rec_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_rmout_return_id',
			'label' => 'Nomor RM-OUT Return',
			'rules' => array(
				'required',
			),
			'errors' => array(
				'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
		];      
		
		return $rules;
	}
}
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwoeinvoiceitemcost_model extends MY_Model{
	public $tableName = "trwoeinvoiceitemcost";
	public $pkey = "fin_rec_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fin_woeinv_id',
			'label' => 'Nomor Invoice Workorder External',
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
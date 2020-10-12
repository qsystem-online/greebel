<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trrmoutitems_model extends MY_Model{
	public $tableName = "trrmoutitems";
	public $pkey = "fin_rec_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fin_rmmout_id',
			'label' => 'RM-OUT Id',
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
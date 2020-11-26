<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msunits_model extends MY_Model
{
	public $tableName = "msunits";
	public $pkey = "fin_rec_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getDataById($id)
	{
		return null;
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_fa_profile_code',
			'label' => 'Fixed asset code',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong'
			)
		];

		$rules[] = [
			'field' => 'fin_productiontype_id',
			'label' => 'Type produksi',
			'rules' => 'required',
			'errors' => array(
				'is_unique' => '%s tidak boleh kosong'
			)
		];

		return $rules;
	}	

	
}

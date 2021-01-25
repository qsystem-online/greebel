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
		$ssql = "SELECT * FROM msunits where fin_rec_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$id]);
		$rw = $qr->row();
		return $rw;
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_unit',
			'label' => 'Unit',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong'
			)
		];
		return $rules;
	}	

	
}

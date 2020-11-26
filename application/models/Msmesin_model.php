<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msmesin_model extends MY_Model
{
	public $tableName = "msmesin";
	public $pkey = "fin_mesin_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getDataById($fin_mesin_id)
	{
		$ssql = "SELECT a.*,b.fst_fa_profile_name FROM msmesin a 
			INNER JOIN trfaprofilesitems b on a.fst_fa_profile_code = b.fst_fa_profile_code
			where a.fin_mesin_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$fin_mesin_id]);
		$data = $qr->row();
		return $data;
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

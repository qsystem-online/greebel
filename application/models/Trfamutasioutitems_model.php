<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trfamutasioutitems_model extends MY_Model{
    public $tableName = "trfamutasioutitems";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_fa_profile_code',
            'label' => 'Profile Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.fst_glaccount_code.fst_glaccount_code.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];        
        return $rules;
    }
    
}
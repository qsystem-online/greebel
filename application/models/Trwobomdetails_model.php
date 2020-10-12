<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwobomdetails_model extends MY_Model{
    public $tableName = "trwobomdetails";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_wo_id',
            'label' => 'Workorder ID',
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
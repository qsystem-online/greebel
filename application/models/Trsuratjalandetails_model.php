<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsuratjalandetails_model extends MY_Model {
    public $tableName = "trsuratjalandetails";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fin_salesorder_detail_id',
            'label' => 'Sales Order Detail ID',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        return $rules;
    }
    
}

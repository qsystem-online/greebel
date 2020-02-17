<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchaserequestitems_model extends MY_Model {
    public $tableName = "trpurchaserequestitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        $rules[] = [
            'field' => 'fdt_etd',
            'label' => 'ETD',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];  
        return $rules;
    }
}



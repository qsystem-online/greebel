<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlpbpurchaseitems_model extends MY_Model {
    public $tableName = "trlpbpurchaseitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }
}



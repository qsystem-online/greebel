<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsalesekspedisiitems_model extends MY_Model {
    public $tableName = "trsalesekspedisiitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    
}



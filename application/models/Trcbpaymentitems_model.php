<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trcbpaymentitems_model extends MY_Model {
    public $tableName = "trcbpaymentitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($finCBPayemntId,$softDelete = true){
        if ($softDelete == true){            
            $ssql ="update trcbpaymentitems set fst_active ='D' where fin_cbpayment_id = ?";
        }else{
            $ssql ="delete from trcbpaymentitems where fin_cbpayment_id = ?";
        }
        $this->db->query($ssql,$finCBPayemntId); 
    }

  
}



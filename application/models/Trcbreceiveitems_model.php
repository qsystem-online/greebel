<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trcbreceiveitems_model extends MY_Model {
    public $tableName = "trcbreceiveitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        return $rules;
    }

    public function deleteByHeaderId($finCBReceiveId,$softDelete = true){
        if ($softDelete == true){            
            $ssql ="update trcbreceiveitems set fst_active ='D' where fin_cbreceive_id = ?";
        }else{
            $ssql ="delete from trcbreceiveitems where fin_cbreceive_id = ?";
        }
        $this->db->query($ssql,$finCBReceiveId);
        throwIfDBError();
    }

  
}



<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trcbreceiveitemstype_model extends MY_Model {
    public $tableName = "trcbreceiveitemstype";
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
            $ssql ="update trcbreceiveitemstype set fst_active ='D' where fin_cbreceive_id = ?";
        }else{
            $ssql ="delete from trcbreceiveitemstype where fin_cbreceive_id = ?";
        }
        $this->db->query($ssql,$finCBReceiveId);
        throwIfDBError();
    }
}



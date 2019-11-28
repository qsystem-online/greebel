<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trchequeflow_model extends MY_Model {
    public $tableName = "trchequeflow";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_salesorder_no',
            'label' => 'Sales Order No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }

    public function getPendingChequeList($finRelationId,$type="IN"){
        $ssql ="select * from trchequeflow where fst_type = ? and fin_relation_id = ? and fst_cheque_status ='OPEN' and fst_active ='A'";
        $qr = $this->db->query($ssql,[$type,$finRelationId]);
        return $qr->result();
    }
    
}



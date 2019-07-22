<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msverification_model extends MY_Model {
    public $tableName = "msverification";
    public $pkey = "fin_rec_id";

    public function __construct() {
        parent::__construct();
    }

    public function getData($controller,$verificationType){
        $ssql ="select * from " . $this->tableName . " where fst_controller = ? and fst_verification_type = ? order by fin_seqno";
        $qr = $this->db->query($ssql,[$controller,$verificationType]);
        return $qr->result();
        
    }
    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];       
        return $rules;
    }
}
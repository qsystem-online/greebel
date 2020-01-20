<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kasbanknumbering_model extends MY_Model{
    public $tableName = "mskasbanknumbering";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getKasBankNo($prefix){

        $ssql = "SELECT MAX(fst_kasbank_no) as max_id FROM mskasbanknumbering where fst_kasbank_no like '$prefix'";
        $qr =$this->db->query($ssql,[]);
        $row = $qr->row();
        $max_id = $row->max_id;
        if ($max_id == null){
            $max_id1 = 0;
        }else{
            $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        }                
        $fst_tr_no = $max_id1 + 1;
        return $fst_tr_no;
    }

    public function log($no){
        $ssql = "INSERT mskasbanknumbering (fst_kasbank_no,fdt_insert_datetime) values(?,now())";
        $this->db->query($ssql,[$no]);
        throwIfDBError();
    }
    public function unlog($no){
        $ssql = "delete from mskasbanknumbering where fst_kasbank_no = ?";
        $this->db->query($ssql,[$no]);
        throwIfDBError();
    }
}

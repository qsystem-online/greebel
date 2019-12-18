<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trinvoiceitems_model extends MY_Model {
    public $tableName = "trinvoiceitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];            
        return $rules;
    }    

    public function getDataById($finInvDetailId){
        $ssql ="SELECT  a.*,b.fdc_ppn_percent,b.fbl_is_vat_include,b.fdc_total,b.fdc_total_paid,b.fdc_total_return,b.fst_inv_no FROM trinvoiceitems a inner join trinvoice b on a.fin_inv_id = b.fin_inv_id where a.fin_rec_id = ? ";
        $qr = $this->db->query($ssql,[$finInvDetailId]);
        return $qr->row();

    }

}

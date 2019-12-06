<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trvoucher_model extends MY_Model
{
    public $tableName = "trvoucher";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById()
    {
       
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];
        return $rules;
    }

    public function createVoucher($data){
        /*
        `fin_rec_id` bigint (20),
        `fst_transaction_type` char (30),
        `fin_transaction_id` int (11),
        `fin_promo_id` int (11),
        `fin_branch_id` int (11),
        `fin_relation_id` int (11),
        `fst_voucher_code` varchar (300),
        `fdc_disc_percent` Decimal (14),
        `fdc_value` Decimal (14),
        `fbl_is_used` tinyint (1),
        `fdt_used_datetime` datetime ,
        `fin_used_transaction_id` int (11),
        `fst_active` char (3),
        `fin_insert_id` int (11),
        `fdt_insert_datetime` datetime ,
        `fin_update_id` int (11),
        `fdt_update_datetime` datetime 
        */
        parent::insert($data);
    }

    public function deleteVoucher($transactionType , $transactionId){
        //Make sure voucher not used;
        $ssql ="select * from " . $this->tableName . " where fst_transaction_type = ? and fin_transaction_id = ? and fbl_is_used = true limit 1";
        $qr = $this->db->query($ssql,[$transactionType,$transactionId]);
        $rw = $qr->row();
        if($rw != null){
            throw new CustomException(lang("Voucher sudah terpakai !"),3003,"FAILED",[]);
        }
        $ssql = "delete from " . $this->tableName . " where fst_transaction_type = ? and fin_transaction_id = ?";
        $this->db->query($ssql,[$transactionType,(int)$transactionId]);
        throwIfDBError();        
    }
}

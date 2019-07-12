<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trinventory_model extends MY_Model
{
    public $tableName = "trinventory";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fst_glaccount_code)
    {
       
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];
        return $rules;
    }


    /*
        stock:
        marketing stock:
    */
    public function getStock($fin_item_id,$fst_unit,$fin_warehouse_id){
        $ssql = "select sum(fin_qty_in) as ttl_qty_in,sum(fin_qty_out) as ttl_qty_out from ". $this->tableName . " where fin_warehouse_id = ? and fin_item_id = ? and fst_unit = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql,[$fin_warehouse_id,$fin_item_id,$fst_unit]);
        $rw = $qr->row();
        return (int) $rw->ttl_qty_in - (int) $rw->ttl_qty_out;
    }

    public function getMarketingStock($fin_item_id,$fst_unit,$fin_warehouse_id){
        $qtyStock = $this->getStock($fin_item_id,$fst_unit,$fin_warehouse_id);
        //Get Qty SO yang masih belum terpenuhi
        $qtyUnprocessSO =1;
        //Get Qty PO yang belum diterima
        $qtyUnprocessPO =4;
        return $qtyStock - $qtyUnprocessSO + $qtyUnprocessPO;


    }
}

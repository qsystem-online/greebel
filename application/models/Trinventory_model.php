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
        //$ssql = "select sum(fdb_qty_in) as ttl_qty_in,sum(fdb_qty_out) as ttl_qty_out from ". $this->tableName . " where fin_warehouse_id = ? and fin_item_id = ? and fst_unit = ? and fst_active = 'A'";
        $ssql = "select * from msitems where fin_item_id = ?";
        $qr = $this->db->query($ssql,[$fin_item_id]);
        $rw = $qr->row();
        if(!$rw){
            return 0;
        }
        if ($rw->fbl_stock == false){
            return 999999999;
        }

        $ssql ="select * from trinventory a 
            where fin_warehouse_id =? 
            and fin_item_id = ?
            and fst_basic_unit = ? 
            order by fdt_trx_datetime desc , fin_rec_id desc limit 1";
        $qr = $this->db->query($ssql,[$fin_warehouse_id,$fin_item_id,$fst_unit]);
        $rw = $qr->row();
        if (!$rw){
            return 0;
        }else{
            //return (int) $rw->ttl_qty_in - (int) $rw->ttl_qty_out;
            return (float) $rw->fdb_qty_balance_after;
        }
        
    }

    public function getMarketingStock($fin_item_id,$fst_unit,$fin_warehouse_id){
        $qtyStock = $this->getStock($fin_item_id,$fst_unit,$fin_warehouse_id);
        //Get Qty SO yang masih belum terpenuhi
        $qtyUnprocessSO =1;
        //Get Qty PO yang belum diterima
        $qtyUnprocessPO =4;
        return $qtyStock - $qtyUnprocessSO + $qtyUnprocessPO;


    }

    public function insert($data){

        $convToBasicUnit = 1;
        $basicUnit = "";
        $pricePerBasicUnit = 0.00;
        $qtyInBasicUnit = 0.00;
        $qtyOutBasicUnit = 0.00;
        

        
        $ssql ="select a.*,b.fdc_conv_to_basic_unit from msitems a 
            inner join msitemunitdetails b on (a.fin_item_id = b.fin_item_id and b.fst_unit = ?)
            where a.fin_item_id = ?";

        $qr = $this->db->query($ssql,[$data["fst_unit"],$data["fin_item_id"]]);
        $rw = $qr->row();
        if($rw == null){
            return ["status"=>"FAILED","message"=>lang("Item dan unit tidak ditemukan !")];
        }else{
            if ($rw->fbl_stock == false){
                return ["status"=>"SUCCESS","message"=>""];
            }
        }        
        $convToBasicUnit = floatval($rw->fdc_conv_to_basic_unit);
    

        $ssql ="select * from msitemunitdetails where fin_item_id = ? and fbl_is_basic_unit = 1";
        $qr = $this->db->query($ssql,[$data["fin_item_id"]]);
        $rw  = $qr->row();
        if($rw == null){
            return ["status"=>"FAILED","message"=>lang("Basic unit item tidak ditemukan !")];
        }
        $basicUnit = $rw->fst_unit;        
        $pricePerBasicUnit = floatval($data["fdc_price_in"]) / $convToBasicUnit;
        $qtyInBasicUnit = (float) $data["fdb_qty_in"] * $convToBasicUnit;
        $qtyOutBasicUnit = (float) $data["fdb_qty_out"] * $convToBasicUnit;


        //get last record
        $ssql ="select * from trinventory 
        where fin_warehouse_id = ?
        and fin_item_id = ?
        and fst_basic_unit = ?        
        and fdt_trx_datetime <= ? order by fdt_trx_datetime desc , fin_rec_id desc limit 1";

        $qr = $this->db->query($ssql,[$data["fin_warehouse_id"],$data["fin_item_id"],$basicUnit,$data["fdt_trx_datetime"]]);
        $rw = $qr->row();

        $qtyBalanceBefore = 0;
        $avgCostBefore = 0;
        if ($rw){
            $qtyBalanceBefore = (float) $rw->fdb_qty_balance_after;
            $avgCostBefore = (float) $rw->fdc_avg_cost;
        }

        $data["fdb_qty_balance_after"] = $qtyBalanceBefore  + $qtyInBasicUnit - $qtyOutBasicUnit;

        if ($qtyInBasicUnit > 0){
            $newAvgCost = ($qtyBalanceBefore * $avgCostBefore) + ($qtyInBasicUnit * $pricePerBasicUnit);
            $newAvgCost = $newAvgCost / ($qtyBalanceBefore + $qtyInBasicUnit);
            $data["fdc_avg_cost"] =  $newAvgCost;
        }else{
            $data["fdc_avg_cost"] = $avgCostBefore;
        }

        $data["fst_basic_unit"] = $basicUnit;
        $data["fdb_qty_in"]=$qtyInBasicUnit; 
        $data["fdb_qty_out"]=$qtyOutBasicUnit;
        $data["fdc_price_in"]=$pricePerBasicUnit; 

        parent::insert($data);

        $datetime = $data["fdt_trx_datetime"];

        $ssql ="select * from trinventory 
        where fin_warehouse_id = ?
        and fin_item_id = ?
        and fst_basic_unit = ?        
        and fdt_trx_datetime > ? order by fdt_trx_datetime ,fin_rec_id";

        $qr = $this->db->query($ssql,[$data["fin_warehouse_id"],$data["fin_item_id"],$basicUnit,$datetime]);
        $rs = $qr->result();
        $currentData = $data;
        foreach($rs as $rw){
            $rw = (array) $rw;
            $rw["fdb_qty_balance_after"] = $currentData["fdb_qty_balance_after"] + $rw["fdb_qty_in"] -  $rw["fdb_qty_out"];
            
            if($rw["fdb_qty_in"] > 0){
                $newAvgCost = ($currentData["fdb_qty_balance_after"] * $currentData["fdc_avg_cost"]) + ((float) $rw["fdb_qty_in"] * (float) $rw["fdc_price_in"]);
                $newAvgCost = $newAvgCost / ((float) $currentData["fdb_qty_balance_after"] + (float) $rw["fdb_qty_in"]);
                $rw["fdc_avg_cost"] =  $newAvgCost;
            }else{
                $rw["fdc_avg_cost"] = $currentData["fdc_avg_cost"];
            }
            unset($rw["fdt_update_datetime"]);
            unset($rw["fin_update_id"]);

            parent::update($rw);
            $currentData = $rw;
        }



    }

    public function deleteByCodeId($trxCode,$trxId){
        $ssql = "select *  from trinventory where fst_trx_code =? and fin_trx_id =? order by fdt_trx_datetime,fin_rec_id";
        $qr = $this->db->query($ssql,[$trxCode,$trxId]);
        $rs = $qr->result();
        foreach($rs as $rw){
            //delete row
            $ssql ="delete from trinventory where fin_rec_id = ?";
            $this->db->query($ssql,[$rw->fin_rec_id]);


            //Get Last record
            $ssql ="select * from trinventory
                where fin_warehouse_id = ?
                and fin_item_id = ?
                and fst_basic_unit = ?
                and fdt_trx_datetime <= ? order by fdt_trx_datetime desc , fin_rec_id desc limit 1";
            $qr = $this->db->query($ssql,[$rw->fin_warehouse_id,$rw->fin_item_id,$rw->fst_basic_unit,$rw->fdt_trx_datetime]);
            $currentData = $qr->row();

            if ($currentData){
                $currentData = (array) $currentData;
            }else{
                $currentData = [
                    "fdb_qty_balance_after"=>0,
                    "fdc_avg_cost"=>0
                ];
            }

            //Process maju
            $ssql ="select * from trinventory
                where fin_warehouse_id = ?
                and fin_item_id = ?
                and fst_basic_unit = ?
                and fdt_trx_datetime >= ? order by fdt_trx_datetime,fin_rec_id";
            $qr = $this->db->query($ssql,[$rw->fin_warehouse_id,$rw->fin_item_id,$rw->fst_basic_unit,$rw->fdt_trx_datetime]);
            $rs = $qr->result();    
            foreach($rs as $rw2){
                $rw2->fdb_qty_balance_after = $currentData["fdb_qty_balance_after"] + (float) $rw2->fdb_qty_in -  (float) $rw2->fdb_qty_out;
                if ($rw2->fdb_qty_in > 0){
                    $newAvg = ((float) $currentData["fdb_qty_balance_after"] * (float) $currentData["fdc_avg_cost"]) + ($rw2->fdb_qty_in * $rw2->fdc_price_in);
                    $newAvg = $newAvg / $rw2->fdb_qty_balance_after;
                    $rw2->fdc_avg_cost = $newAvg;
                }else{
                    $rw2->fdc_avg_cost = (float) $currentData["fdc_avg_cost"];
                }
                unset($rw2->fin_update_id);
                unset($rw2->fdt_update_datetime);

                parent::update((array) $rw2);
                $currentData = (array) $rw2;
            }


        } 

    }
}

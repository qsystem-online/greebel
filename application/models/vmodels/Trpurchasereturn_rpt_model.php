<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trpurchasereturn_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No Retur', 'Tanggal Retur'];

    public function queryComplete($data, $sorder_by="a.fin_purchasereturn_id", $rptLayout="1") {
        
        $branch_id = "";
        $relation_id = "";
        $curr_code = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_supplier_id'])) { $relation_id = $data['fin_supplier_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fdt_purchasereturn_datetime'])) { $start_date = $data['fdt_purchasereturn_datetime'];}
        if (isset($data['fdt_purchasereturn_datetime2'])) { $end_date = $data['fdt_purchasereturn_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($relation_id > "0") {
            $swhere .= " AND a.fin_supplier_id = " . $this->db->escape($relation_id);
        }
        if ($curr_code != "0") {
            $swhere .= " AND a.fst_curr_code = " . $this->db->escape($curr_code);
        }
        if (isset($start_date)) {
            $swhere .= " AND a.fdt_purchasereturn_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_purchasereturn_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_purchasereturn_id,a.fst_purchasereturn_no as No_Retur, a.fdt_purchasereturn_datetime as Retur_Date,
                a.fst_memo as Retur_Memo,a.fdc_total_claimed as Total_Claimed,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,(a.fdc_subttl * a.fdc_exchange_rate_idr) as fdc_subttl_Idr,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,
                a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,(((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) * a.fdc_exchange_rate_idr) as fdc_total_Idr,
                a.fin_supplier_id as fin_supplier_id,c.fst_relation_name as Relation_Name,e.fst_lpbpurchase_no as No_LPB,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_notes as Notes_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount,((b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) * a.fdc_exchange_rate_idr) as Amount_Idr   
                FROM (SELECT * FROM trpurchasereturn a WHERE a.fst_active !='D') a LEFT OUTER JOIN trpurchasereturnitems b 
                ON a.fin_purchasereturn_id = b.fin_purchasereturn_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trlpbpurchase e
                ON a.fin_lpbpurchase_id = e.fin_lpbpurchase_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_purchasereturn_id,a.fst_purchasereturn_no as No_Retur, a.fdt_purchasereturn_datetime as Retur_Date,
                a.fst_memo as Retur_Memo,a.fdc_total_claimed as Total_Claimed,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,(a.fdc_subttl * a.fdc_exchange_rate_idr) as fdc_subttl_Idr,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,
                a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,(((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) * a.fdc_exchange_rate_idr) as fdc_total_Idr,
                a.fin_supplier_id as fin_supplier_id,b.fst_relation_name as Relation_Name,c.fst_lpbpurchase_no as No_LPB
                FROM (SELECT * FROM trpurchasereturn WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b
                ON a.fin_supplier_id = b.fin_relation_id  LEFT OUTER JOIN trlpbpurchase c
                ON a.fin_lpbpurchase_id = c.fin_lpbpurchase_id " . $swhere . $sorderby;
                break;
            default:
                break;
        }
        
        $query = $this->db->query($ssql);
        //echo $this->db->last_query();
        //die();
        return $query->result();
    }

    public function getRules()
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_branch_id',
            'label' => 'Branch',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        $rules[] = [
            'field' => 'fin_supplier_id',
            'label' => 'Supplier',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_purchasereturn_datetime',
            'label' => 'Tgl Retur',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_purchasereturn_datetime2',
            'label' => 'Tgl Retur',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
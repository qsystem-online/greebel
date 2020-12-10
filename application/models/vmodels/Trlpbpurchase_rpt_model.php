<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trlpbpurchase_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No LPB', 'Tanggal LPB'];

    public function queryComplete($data, $sorder_by="a.fin_lpbpurchase_id", $rptLayout="1") {
        
        $branch_id = "";
        $item_id = "";
        $relation_id = "";
        $curr_code = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
        if (isset($data['fin_supplier_id'])) { $relation_id = $data['fin_supplier_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fdt_lpbpurchase_datetime'])) { $start_date = $data['fdt_lpbpurchase_datetime'];}
        if (isset($data['fdt_lpbpurchase_datetime2'])) { $end_date = $data['fdt_lpbpurchase_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($rptLayout == "2"){
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
                $swhere .= " AND a.fdt_lpbpurchase_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_lpbpurchase_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
        }else{
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($item_id > "0") {
                $swhere .= " AND b.fin_item_id = " . $this->db->escape($item_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND a.fin_supplier_id = " . $this->db->escape($relation_id);
            }
            if ($curr_code != "0") {
                $swhere .= " AND a.fst_curr_code = " . $this->db->escape($curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " AND a.fdt_lpbpurchase_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_lpbpurchase_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_lpbpurchase_id,a.fst_lpbpurchase_no as No_LPB, a.fdt_lpbpurchase_datetime as LPB_Date, a.fin_term as TOP,CAST(DATE_ADD(a.fdt_lpbpurchase_datetime, INTERVAL a.fin_term DAY) as DATE) as Jt_Date,
                a.fst_memo as LPB_Memo,a.fdc_downpayment_claim as Dpp_Claimed,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,(a.fdc_subttl * a.fdc_exchange_rate_idr) as fdc_subttl_Idr,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,
                a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,(((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) * a.fdc_exchange_rate_idr) as fdc_total_Idr,
                a.fin_supplier_id as fin_supplier_id,c.fst_relation_name as Relation_Name,e.fst_po_no as No_PO,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_memo_item as Nota_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount,((b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) * a.fdc_exchange_rate_idr) as Amount_Idr   
                FROM (SELECT * FROM trlpbpurchase a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbpurchaseitems b 
                ON a.fin_lpbpurchase_id = b.fin_lpbpurchase_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trpo e
                ON a.fin_po_id = e.fin_po_id $swhere ORDER BY a.fin_supplier_id";
                break;
            case "2":
                $ssql = "SELECT a.fin_lpbpurchase_id,a.fst_lpbpurchase_no as No_LPB, a.fdt_lpbpurchase_datetime as LPB_Date, a.fin_term as TOP,CAST(DATE_ADD(a.fdt_lpbpurchase_datetime, INTERVAL a.fin_term DAY) as DATE) as Jt_Date,
                a.fin_supplier_id as fin_supplier_id,b.fst_relation_name as Relation_Name,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,a.fdc_subttl as fdc_subttl,
                ((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,(((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) * a.fdc_exchange_rate_idr)  as fdc_total_Idr,
                a.fdc_downpayment_claim as Dp_Claimed,c.fst_po_no as No_PO,c.fdt_po_datetime as PO_Date
                FROM (SELECT * FROM trlpbpurchase WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b
                ON a.fin_supplier_id = b.fin_relation_id  LEFT OUTER JOIN trpo c
                ON a.fin_po_id = c.fin_po_id " . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.fdt_lpbpurchase_datetime as LPB_Date,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,a.fin_supplier_id as fin_supplier_id,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, c.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_memo_item as Nota_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount,((b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) * a.fdc_exchange_rate_idr) as Amount_Idr   
                FROM (SELECT * FROM trlpbpurchase a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbpurchaseitems b 
                ON a.fin_lpbpurchase_id = b.fin_lpbpurchase_id LEFT OUTER JOIN msitems c
                ON b.fin_item_id = c.fin_item_id $swhere GROUP BY b.fin_item_id,c.fst_item_name,a.fin_supplier_id,a.fdc_exchange_rate_idr,b.fst_unit";
                break;
            case "4":
                $ssql = "SELECT a.fdt_lpbpurchase_datetime as LPB_Date,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_supplier_id as fin_supplier_id,c.fst_relation_name as Relation_Name,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_memo_item as Nota_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount,((b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) * a.fdc_exchange_rate_idr) as Amount_Idr   
                FROM (SELECT * FROM trlpbpurchase a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbpurchaseitems b 
                ON a.fin_lpbpurchase_id = b.fin_lpbpurchase_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                ON b.fin_item_id = d.fin_item_id $swhere GROUP BY a.fin_supplier_id,c.fst_relation_name,b.fin_item_id,d.fst_item_name,a.fdc_exchange_rate_idr,b.fst_unit";
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
            'field' => 'fdt_lpbpurchase_datetime',
            'label' => 'Tgl LPB',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_lpbpurchase_datetime2',
            'label' => 'Tgl LPB',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
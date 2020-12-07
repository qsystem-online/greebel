<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trpo_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No PO', 'Tanggal PO'];

    public function queryComplete($data, $sorder_by="a.fin_po_id", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $relation_id = "";
        $curr_code = "";
        $bl_import = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_supplier_id'])) { $relation_id = $data['fin_supplier_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fbl_is_import'])) { $bl_import = $data['fbl_is_import'];}
        if (isset($data['fdt_po_datetime'])) { $start_date = $data['fdt_po_datetime'];}
        if (isset($data['fdt_po_datetime2'])) { $end_date = $data['fdt_po_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($warehouse_id > "0" ) {
            $swhere .= " AND a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
        }
        if ($relation_id > "0") {
            $swhere .= " AND a.fin_relation_id = " . $this->db->escape($relation_id);
        }
        if ($curr_code != "") {
            $swhere .= " AND a.fst_curr_code = " . $this->db->escape($curr_code);
        }
        if ($bl_import != "ALL") {
            $swhere .= " AND a.fbl_is_import = " . $this->db->escape($bl_import);
        }
        if (isset($start_date)) {
            $swhere .= " AND a.fdt_po_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_po_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($rptLayout == "3"){
            if ($swhere != "") {
                $swhere = " AND " . substr($swhere, 5);
            }
        }else{
            if ($swhere != "") {
                $swhere = " WHERE " . substr($swhere, 5);
            }
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_po_id,a.fst_po_no as No_PO, a.fdt_po_datetime as PO_Date, a.fin_term as TOP,CAST(DATE_ADD(a.fdt_po_datetime, INTERVAL a.fin_term DAY) as DATE) as Jt_Date,
                a.fst_memo as PO_Memo,a.fdc_downpayment as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,
                a.fst_do_no as No_DO,a.fst_contract_no as No_Kontrak,
                a.fdc_exchange_rate_idr as Rate_Idr,a.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fin_supplier_id as fin_supplier_id,c.fst_relation_name as Relation_Name,
                b.fin_po_detail_id as Rec_Id, b.fin_item_id as Item_Id, e.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_notes as Nota_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount  
                FROM (SELECT * FROM trpo a WHERE a.fst_active !='D') a LEFT OUTER JOIN trpodetails b 
                ON a.fin_po_id = b.fin_po_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN mswarehouse d
                ON a.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN msitems e
                ON b.fin_item_id = e.fin_item_id $swhere ORDER BY a.fin_supplier_id";
                break;
            case "2":
                $ssql = "SELECT a.fst_po_no as No_PO, a.fdt_po_datetime as PO_Date,a.fin_term as TOP,a.fst_do_no as No_DO,a.fst_contract_no as No_Kontrak,c.fst_warehouse_name as Warehouse,
                a.fin_supplier_id as fin_supplier_id,b.fst_relation_name as Relation_Name,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,a.fdc_subttl as fdc_subttl,
                ((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,(((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) * a.fdc_exchange_rate_idr)  as fdc_total_Idr
                FROM (SELECT * FROM trpo WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b
                ON a.fin_supplier_id = b.fin_relation_id  LEFT OUTER JOIN mswarehouse c
                ON a.fin_warehouse_id = c.fin_warehouse_id " . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.fin_po_id,a.fst_po_no as No_PO, a.fdt_po_datetime as PO_Date, a.fin_term as TOP,CAST(DATE_ADD(a.fdt_po_datetime, INTERVAL a.fin_term DAY) as DATE) as Jt_Date,
                a.fst_memo as PO_Memo,a.fdc_downpayment as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,
                a.fst_do_no as No_DO,a.fst_contract_no as No_Kontrak,
                a.fdc_exchange_rate_idr as Rate_Idr,a.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fin_supplier_id as fin_supplier_id,c.fst_relation_name as Relation_Name,
                b.fin_po_detail_id as Rec_Id, b.fin_item_id as Item_Id, e.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_notes as Nota_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit
                FROM (SELECT * FROM trpo a WHERE a.fst_active !='D') a LEFT OUTER JOIN trpodetails b 
                ON a.fin_po_id = b.fin_po_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN mswarehouse d
                ON a.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN msitems e
                ON b.fin_item_id = e.fin_item_id LEFT OUTER JOIN 
                (SELECT a.fin_trans_id,a.fst_trans_no,b.fin_trans_detail_id,b.fin_item_id,b.fdb_qty 
                FROM trlpbgudang a LEFT OUTER JOIN trlpbgudangitems b ON a.fin_sj_id = b.fin_sj_id) f LEFT OUTER JOIN
	            trlpbgudang g ON d.fin_sj_id = e.fin_sj_id RIGHT OUTER JOIN $swhere ORDER BY a.fin_supplier_id";
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
            'field' => 'fin_warehouse_id',
            'label' => 'Warehouse',
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
            'field' => 'fdt_po_datetime',
            'label' => 'Tgl PO',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_po_datetime2',
            'label' => 'Tgl PO',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
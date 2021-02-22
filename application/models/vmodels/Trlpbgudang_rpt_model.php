<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trlpbgudang_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No.Penerimaan', 'Tanggal Penerimaan'];

    public function queryComplete($data, $sorder_by="b.fin_lpbgudang_id", $rptLayout="1") {
        
        $branch_id = "";
        $warehouse_id = "";
        $supplier_id = "";
        $item_id = "";
        $lpb_type = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_supplier_id'])) { $relation_id = $data['fin_supplier_id'];}
        if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
        if (isset($data['fst_lpb_type'])) { $lpb_type = $data['fst_lpb_type'];}
        if (isset($data['fdt_lpbgudang_datetime'])) { $start_date = $data['fdt_lpbgudang_datetime'];}
        if (isset($data['fdt_lpbgudang_datetime2'])) { $end_date = $data['fdt_lpbgudang_datetime2'];}

        $swhere = "";
        $sorderby = "";
        //if ($rptLayout == "1"){
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0") {
                $swhere .= " AND a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($item_id > "0") {
                $swhere .= " AND b.fin_item_id = " . $this->db->escape($item_id);
            }
            if ($lpb_type =="PO"){
                if ($supplier_id > "0") {
                    $swhere .= " AND a.fin_relation_id = " . $this->db->escape($supplier_id);
                }
            }
            if ($lpb_type != "") {
                $swhere .= " AND a.fst_lpb_type = " . $this->db->escape($lpb_type);
            }
            if (isset($start_date)) {
                $swhere .= " AND a.fdt_lpbgudang_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_lpbgudang_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
        //}
        //}

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                if ($lpb_type =="PO"){
                    $ssql = "SELECT a.fin_lpbgudang_id as Id_LpbGudang,a.fst_lpbgudang_no as No_Penerimaan, a.fdt_lpbgudang_datetime as Penerimaan_Date,a.fst_memo as LpbGudang_Memo,
                    a.fin_relation_id as fin_supplier_id,c.fst_relation_name as Supplier_Name,e.fst_po_no as No_PO,e.fdt_po_datetime as PO_Date,a.fst_lpb_type as Lpb_Type,
                    b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fdb_qty as Qty, b.fst_unit as Unit,
                    f.fst_lpbpurchase_no as No_LPB,f.fdt_lpbpurchase_datetime as LPB_Date,g.fst_warehouse_name as Warehouse
                    FROM (SELECT * FROM trlpbgudang a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbgudangitems b 
                    ON a.fin_lpbgudang_id = b.fin_lpbgudang_id LEFT OUTER JOIN msrelations c
                    ON a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                    ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trpo e
                    ON a.fin_trans_id = e.fin_po_id LEFT OUTER JOIN trlpbpurchase f
                    ON a.fin_lpbpurchase_id = f.fin_lpbpurchase_id LEFT OUTER JOIN mswarehouse g 
                    ON a.fin_warehouse_id = g.fin_warehouse_id $swhere ORDER BY a.fin_relation_id,a.fin_lpbgudang_id";
                }else{
                    $ssql = "SELECT a.fin_lpbgudang_id as Id_LpbGudang,a.fst_lpbgudang_no as No_Penerimaan, a.fdt_lpbgudang_datetime as Penerimaan_Date,a.fst_memo as LpbGudang_Memo,
                    a.fin_relation_id as fin_supplier_id,IFNULL(c.fst_relation_name,'ASSEMBLING IN') as Supplier_Name,e.fst_assembling_no as No_PO,e.fdt_assembling_datetime as PO_Date,
                    b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fdb_qty as Qty, b.fst_unit as Unit,
                    f.fst_warehouse_name as Warehouse,a.fst_lpb_type as Lpb_Type
                    FROM (SELECT * FROM trlpbgudang a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbgudangitems b 
                    ON a.fin_lpbgudang_id = b.fin_lpbgudang_id LEFT OUTER JOIN msrelations c
                    ON a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                    ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trassembling e
                    ON a.fin_trans_id = e.fin_assembling_id LEFT OUTER JOIN mswarehouse f
                    ON a.fin_warehouse_id = f.fin_warehouse_id $swhere ORDER BY a.fin_relation_id,a.fin_lpbgudang_id";
                }
                break;
            case "2":
                if ($lpb_type =="PO"){
                    $ssql = "SELECT a.fin_lpbgudang_id as Id_LpbGudang,a.fst_lpbgudang_no as No_Penerimaan, a.fdt_lpbgudang_datetime as Penerimaan_Date,a.fst_memo as LpbGudang_Memo,
                    a.fin_relation_id as fin_supplier_id,c.fst_relation_name as Supplier_Name,e.fst_po_no as No_PO,e.fdt_po_datetime as PO_Date,a.fst_lpb_type as Lpb_Type,
                    b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fdb_qty as Qty, b.fst_unit as Unit,
                    f.fst_lpbpurchase_no as No_LPB,f.fdt_lpbpurchase_datetime as LPB_Date,g.fst_warehouse_name as Warehouse
                    FROM (SELECT * FROM trlpbgudang a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbgudangitems b 
                    ON a.fin_lpbgudang_id = b.fin_lpbgudang_id LEFT OUTER JOIN msrelations c
                    ON a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                    ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trpo e
                    ON a.fin_trans_id = e.fin_po_id LEFT OUTER JOIN trlpbpurchase f
                    ON a.fin_lpbpurchase_id = f.fin_lpbpurchase_id LEFT OUTER JOIN mswarehouse g 
                    ON a.fin_warehouse_id = g.fin_warehouse_id $swhere ORDER BY a.fin_lpbgudang_id";
                }else{
                    $ssql = "SELECT a.fin_lpbgudang_id as Id_LpbGudang,a.fst_lpbgudang_no as No_Penerimaan, a.fdt_lpbgudang_datetime as Penerimaan_Date,a.fst_memo as LpbGudang_Memo,
                    a.fin_relation_id as fin_supplier_id,IFNULL(c.fst_relation_name,'ASSEMBLING IN') as Supplier_Name,e.fst_assembling_no as No_PO,e.fdt_assembling_datetime as PO_Date,
                    b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fdb_qty as Qty, b.fst_unit as Unit,
                    f.fst_warehouse_name as Warehouse,a.fst_lpb_type as Lpb_Type
                    FROM (SELECT * FROM trlpbgudang a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbgudangitems b 
                    ON a.fin_lpbgudang_id = b.fin_lpbgudang_id LEFT OUTER JOIN msrelations c
                    ON a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                    ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trassembling e
                    ON a.fin_trans_id = e.fin_assembling_id LEFT OUTER JOIN mswarehouse f
                    ON a.fin_warehouse_id = f.fin_warehouse_id $swhere ORDER BY a.fin_lpbgudang_id";
                }
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
            'field' => 'fdt_lpbgudang_datetime',
            'label' => 'Tgl Penerimaan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_lpbgudang_datetime2',
            'label' => 'Tgl Penerimaan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
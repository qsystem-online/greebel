<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trlpbsalesreturn_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No.Penerimaan', 'Tanggal Penerimaan'];

    public function queryComplete($data, $sorder_by="a.fin_lpbsalesreturn_id", $rptLayout="1") {
        
        $branch_id = "";
        $warehouse_id = "";
        $customer_id = "";
        $item_id = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_customer_id'])) { $customer_id = $data['fin_customer_id'];}
        if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
        if (isset($data['fdt_lpbsalesreturn_datetime'])) { $start_date = $data['fdt_lpbsalesreturn_datetime'];}
        if (isset($data['fdt_lpbsalesreturn_datetime2'])) { $end_date = $data['fdt_lpbsalesreturn_datetime2'];}

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
  
            if ($customer_id > "0") {
                $swhere .= " AND a.fin_customer_id = " . $this->db->escape($customer_id);
            }

            if (isset($start_date)) {
                $swhere .= " AND a.fdt_lpbsalesreturn_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_lpbsalesreturn_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
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
                $ssql = "SELECT a.fin_lpbsalesreturn_id as Id_LpbReturn,a.fst_lpbsalesreturn_no as No_Penerimaan, a.fdt_lpbsalesreturn_datetime as Penerimaan_Date,a.fst_memo as LpbReturn_Memo,
                a.fin_customer_id as fin_customer_id,c.fst_relation_name as Customer_Name,e.fst_inv_no as No_Inv,e.fdt_inv_datetime as Inv_Date,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id,d.fst_item_code as Item_Code,d.fst_item_name as Item_Name,b.fdb_qty as Qty,b.fst_unit as Unit,
                f.fst_warehouse_name as Warehouse
                FROM (SELECT * FROM trlpbsalesreturn a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbsalesreturnitems b 
                ON a.fin_lpbsalesreturn_id = b.fin_lpbsalesreturn_id LEFT OUTER JOIN msrelations c
                ON a.fin_customer_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trinvoice e
                ON b.fin_inv_id = e.fin_inv_id LEFT OUTER JOIN mswarehouse f
                ON a.fin_warehouse_id = f.fin_warehouse_id $swhere ORDER BY a.fin_customer_id,a.fin_lpbsalesreturn_id";
                break;
            case "2":
                $ssql = "SELECT a.fin_lpbsalesreturn_id as Id_LpbReturn,a.fst_lpbsalesreturn_no as No_Penerimaan, a.fdt_lpbsalesreturn_datetime as Penerimaan_Date,a.fst_memo as LpbReturn_Memo,
                a.fin_customer_id as fin_customer_id,c.fst_relation_name as Customer_Name,e.fst_inv_no as No_Inv,e.fdt_inv_datetime as Inv_Date,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id,d.fst_item_code as Item_Code,d.fst_item_name as Item_Name,b.fdb_qty as Qty,b.fst_unit as Unit,
                f.fst_warehouse_name as Warehouse
                FROM (SELECT * FROM trlpbsalesreturn a WHERE a.fst_active !='D') a LEFT OUTER JOIN trlpbsalesreturnitems b 
                ON a.fin_lpbsalesreturn_id = b.fin_lpbsalesreturn_id LEFT OUTER JOIN msrelations c
                ON a.fin_customer_id = c.fin_relation_id LEFT OUTER JOIN msitems d
                ON b.fin_item_id = d.fin_item_id LEFT OUTER JOIN trinvoice e
                ON b.fin_inv_id = e.fin_inv_id LEFT OUTER JOIN mswarehouse f
                ON a.fin_warehouse_id = f.fin_warehouse_id $swhere ORDER BY a.fin_lpbsalesreturn_id";
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
            'field' => 'fin_customer_id',
            'label' => 'Customer',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_lpbsalesreturn_datetime',
            'label' => 'Tgl Penerimaan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_lpbsalesreturn_datetime2',
            'label' => 'Tgl Penerimaan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
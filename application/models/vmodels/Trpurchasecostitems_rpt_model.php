<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trpurchasecostitems_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No.Memo Biaya', 'Tanggal'];

    public function queryComplete($data, $sorder_by="a.fin_purchasecost_id", $rptLayout="1") {
        
        $branch_id = "";
        $relation_id = "";
        $curr_code = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_supplier_id'])) { $relation_id = $data['fin_supplier_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fdt_purchasecost_datetime'])) { $start_date = $data['fdt_purchasecost_datetime'];}
        if (isset($data['fdt_purchasecost_datetime2'])) { $end_date = $data['fdt_purchasecost_datetime2'];}

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
            $swhere .= " AND a.fdt_purchasecost_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_purchasecost_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_purchasecost_id,a.fst_purchasecost_no as No_Cost, a.fdt_purchasecost_datetime as Cost_Date,
                a.fst_memo as Cost_Memo,a.fdc_total as fdc_total,a.fbl_is_import as fbl_is_import,
                a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,(a.fdc_total * a.fdc_exchange_rate_idr) as fdc_total_Idr,
                a.fin_supplier_id as fin_supplier_id,c.fst_relation_name as Relation_Name,e.fst_po_no as No_PO,
                b.fin_rec_id as Rec_Id, b.fst_glaccount_code as Account_Code, d.fst_glaccount_name as Account_Name,b.fst_notes as Notes_Detail,
                b.fdc_debet as fdc_debet,b.fdc_credit as fdc_credit,f.fst_pcc_name as ProfitCost_Center,g.fst_department_name as Analisa_Department,
                h.fst_relation_name as Analisa_Customer,i.fst_project_name as Analisa_Project 
                FROM (SELECT * FROM trpurchasecost a WHERE a.fst_active !='D') a LEFT OUTER JOIN trpurchasecostitems b 
                ON a.fin_purchasecost_id = b.fin_purchasecost_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN glaccounts d
                ON b.fst_glaccount_code = d.fst_glaccount_code LEFT OUTER JOIN trpo e
                ON a.fin_po_id = e.fin_po_id LEFT OUTER JOIN msprofitcostcenter f
                ON b.fin_pcc_id = f.fin_pcc_id LEFT OUTER JOIN departments g
                ON b.fin_pc_divisi_id = g.fin_department_id LEFT OUTER JOIN msrelations h
                ON b.fin_pc_customer_id = h.fin_relation_id LEFT OUTER JOIN msprojects i
                ON b.fin_pc_project_id = i.fin_project_id
                 " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_po_id as Id_PO,a.fin_purchasecost_id,a.fst_purchasecost_no as No_Cost, a.fdt_purchasecost_datetime as Cost_Date,
                a.fst_memo as Cost_Memo,a.fdc_total as fdc_total,a.fbl_is_import as fbl_is_import,
                a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,(a.fdc_total * a.fdc_exchange_rate_idr) as fdc_total_Idr,
                a.fin_supplier_id as fin_supplier_id,b.fst_relation_name as Relation_Name,c.fst_po_no as No_PO  
                FROM (SELECT * FROM trpurchasecost a WHERE a.fst_active !='D') a LEFT OUTER JOIN msrelations b
                ON a.fin_supplier_id = b.fin_relation_id LEFT OUTER JOIN trpo c
                ON a.fin_po_id = c.fin_po_id $swhere ORDER BY a.fin_po_id,a.fin_purchasecost_id";
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
            'field' => 'fdt_purchasecost_datetime',
            'label' => 'Tgl Memo biaya',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_purchasecost_datetime2',
            'label' => 'Tgl Memo biaya',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
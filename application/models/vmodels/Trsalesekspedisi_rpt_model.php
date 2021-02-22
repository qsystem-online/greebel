<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trsalesekspedisi_rpt_model extends CI_Model {

    public $layout1Columns = ['Ekspedisi', 'No.Ekspedisi', 'Tgl.Ekspedisi'];

    public function queryComplete($data, $sorder_by="a.fin_salesekspedisi_id", $rptLayout="1") {
        
        $branch_id = "";
        $customer_id = "";
        $ekspedisi_id = "";
        $start_date = "";
        $end_date = "";

        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_customer_id'])) { $customer_id = $data['fin_customer_id'];}
        if (isset($data['fin_ekspedisi_id'])) { $ekspedisi_id = $data['fin_ekspedisi_id'];}
        if (isset($data['fdt_salesekspedisi_datetime'])) { $start_date = $data['fdt_salesekspedisi_datetime'];}
        if (isset($data['fdt_salesekspedisi_datetime2'])) { $end_date = $data['fdt_salesekspedisi_datetime2'];}

        $swhere = "";
        $sorderby = "";

        //if ($rptLayout == "1" || $rptLayout == "2" || $rptLayout == "3"){
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($customer_id > "0") {
                $swhere .= " AND a.fin_customer_id = " . $this->db->escape($customer_id);
            }
            if ($ekspedisi_id > "0") {
                $swhere .= " AND a.fin_supplier_id = " . $this->db->escape($ekspedisi_id);
            }
            if (isset($start_date)) {
                $swhere .= " AND a.fdt_salesekspedisi_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_salesekspedisi_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
        //}

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }

        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_salesekspedisi_id, a.fst_salesekspedisi_no as No_Ekspedisi,a.fdt_salesekspedisi_datetime as Ekspedisi_Date,a.fst_no_referensi as No_Ref,
                a.fbl_reclaimable as Reclaimable,a.fdb_qty as qty_kodi,a.fdc_price as price_kodi,a.fdc_ppn_amount as Ppn,a.fst_no_faktur_pajak as No_Pajak,a.fdc_other_cost as fdc_other_cost,a.fdc_total as fdc_total,
                b.fst_relation_name as Customer_Name,c.fst_relation_name as Ekspedisi_Name
                FROM (SELECT * FROM trsalesekspedisi WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b 
                on a.fin_customer_id = b.fin_relation_id LEFT OUTER JOIN msrelations c
                on a.fin_supplier_id = c.fin_relation_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_salesekspedisi_id as Id_Ekspedisi, a.fst_salesekspedisi_no as No_Ekspedisi,a.fdt_salesekspedisi_datetime as Ekspedisi_Date,a.fst_no_referensi as No_Ref,
                a.fbl_reclaimable as Reclaimable,a.fdb_qty as qty_kodi,a.fdc_price as price_kodi,a.fdc_ppn_amount as Ppn,a.fst_no_faktur_pajak as No_Pajak,a.fdc_other_cost as fdc_other_cost,a.fdc_total as fdc_total,
                b.fst_relation_name as Customer_Name,c.fst_relation_name as Ekspedisi_Name,d.fst_sj_no as No_SJ
                FROM (SELECT a.*,b.fin_sj_id FROM trsalesekspedisi a LEFT OUTER JOIN trsalesekspedisiitems b ON a.fin_salesekspedisi_id = b.fin_salesekspedisi_id WHERE a.fst_active !='D') a LEFT OUTER JOIN msrelations b 
                ON a.fin_customer_id = b.fin_relation_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN trsuratjalan d 
                ON a.fin_sj_id = d.fin_sj_id " . $swhere . $sorderby;
                break;
            case "3":
                // kurang AND b.fst_trans_type ='EKSPEDISI'
                $ssql = "SELECT a.fin_salesekspedisi_id as Id_Ekspedisi, a.fst_salesekspedisi_no as No_Ekspedisi,a.fdt_salesekspedisi_datetime as Ekspedisi_Date,a.fst_no_referensi as No_Ref,
                a.fbl_reclaimable as Reclaimable,a.fdb_qty as qty_kodi,a.fdc_price as price_kodi,a.fdc_ppn_amount as Ppn,a.fst_no_faktur_pajak as No_Pajak,a.fdc_other_cost as fdc_other_cost,a.fdc_total as fdc_total,
                b.fst_relation_name as Customer_Name,c.fst_relation_name as Ekspedisi_Name,d.fst_cbpayment_no as No_Payment,d.fdt_cbpayment_datetime as Payment_Date,d.fdc_payment as Payment_Amount
                FROM (SELECT a.*,b.fin_sj_id FROM trsalesekspedisi a LEFT OUTER JOIN trsalesekspedisiitems b ON a.fin_salesekspedisi_id = b.fin_salesekspedisi_id WHERE a.fst_active !='D') a 
                LEFT OUTER JOIN msrelations b 
                ON a.fin_customer_id = b.fin_relation_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN (SELECT a.fst_cbpayment_no,a.fdt_cbpayment_datetime,b.fst_trans_type,b.fin_trans_id,b.fdc_payment FROM trcbpayment a 
                RIGHT OUTER JOIN trcbpaymentitems b ON a.fin_cbpayment_id = b.fin_cbpayment_id WHERE a.fst_active !='D') d
                ON a.fin_salesekspedisi_id = d.fin_trans_id" . $swhere . $sorderby;
                break;
            case "4":
                // kurang AND b.fst_trans_type ='KLAIM EKSPEDISI'
                $ssql = "SELECT a.fin_salesekspedisi_id as Id_Ekspedisi, a.fst_salesekspedisi_no as No_Ekspedisi,a.fdt_salesekspedisi_datetime as Ekspedisi_Date,a.fst_no_referensi as No_Ref,
                a.fbl_reclaimable as Reclaimable,a.fdb_qty as qty_kodi,a.fdc_price as price_kodi,a.fdc_ppn_amount as Ppn,a.fst_no_faktur_pajak as No_Pajak,a.fdc_other_cost as fdc_other_cost,a.fdc_total as fdc_total,
                b.fst_relation_name as Customer_Name,c.fst_relation_name as Ekspedisi_Name,d.fst_cbreceive_no as No_Payment,d.fdt_cbreceive_datetime as Payment_Date,d.fdc_receive_amount as Payment_Amount
                FROM (SELECT a.*,b.fin_sj_id FROM trsalesekspedisi a LEFT OUTER JOIN trsalesekspedisiitems b ON a.fin_salesekspedisi_id = b.fin_salesekspedisi_id WHERE a.fst_active !='D') a 
                LEFT OUTER JOIN msrelations b 
                ON a.fin_customer_id = b.fin_relation_id LEFT OUTER JOIN msrelations c
                ON a.fin_supplier_id = c.fin_relation_id LEFT OUTER JOIN 
                (SELECT a.fst_cbreceive_no,a.fdt_cbreceive_datetime,b.fst_trans_type,b.fin_trans_id,b.fdc_receive_amount FROM trcbreceive a 
                LEFT OUTER JOIN trcbreceiveitems b ON a.fin_cbreceive_id = b.fin_cbreceive_id WHERE a.fst_active !='D') d
                ON a.fin_salesekspedisi_id = d.fin_trans_id" . $swhere . $sorderby;
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
            'label' => 'Cabang',
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
            'field' => 'fin_ekspedisi_id',
            'label' => 'Ekspedisi',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        $rules[] = [
            'field' => 'fdt_salesekspedisi_datetime',
            'label' => 'Tgl Ekspedisi',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_salesekspedisi_datetime2',
            'label' => 'Tgl Ekspedisi',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    }   


}
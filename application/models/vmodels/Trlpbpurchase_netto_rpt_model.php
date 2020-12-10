<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trlpbpurchase_netto_rpt_model extends CI_Model {

    public $layout1Columns = ['Supplier/Vendor', 'No LPB', 'Tanggal LPB'];

    public function queryComplete($data, $sorder_by="a.fin_lpbpurchase_id", $rptLayout="1") {
        
        $branch_id = "";
        $relation_id = "";
        $curr_code = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_supplier_id'])) { $relation_id = $data['fin_supplier_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fdt_lpbpurchase_datetime'])) { $start_date = $data['fdt_lpbpurchase_datetime'];}
        if (isset($data['fdt_lpbpurchase_datetime2'])) { $end_date = $data['fdt_lpbpurchase_datetime2'];}

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
            $swhere .= " AND a.fdt_lpbpurchase_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_lpbpurchase_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }
 

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                //TIDAK TERMASUK RETUR NON FAKTUR
                $ssql = "SELECT a.fin_lpbpurchase_id,a.fst_lpbpurchase_no as No_LPB, a.fdt_lpbpurchase_datetime as LPB_Date, a.fin_term as TOP,CAST(DATE_ADD(a.fdt_lpbpurchase_datetime, INTERVAL a.fin_term DAY) as DATE) as Jt_Date,
                a.fin_supplier_id as fin_supplier_id,b.fst_relation_name as Relation_Name,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_nilai_faktur,a.fdc_downpayment_claim as fdc_downpayment_claim,
                c.fst_po_no as No_PO,c.fdt_po_datetime as PO_Date,a.fdc_total as fdc_total,COALESCE(d.fdc_total,0) as fdc_total_retur,(a.fdc_total - (COALESCE(d.fdc_total,0))) as fdc_total_netto
                FROM (SELECT * FROM trlpbpurchase WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b
                ON a.fin_supplier_id = b.fin_relation_id  LEFT OUTER JOIN trpo c
                ON a.fin_po_id = c.fin_po_id LEFT OUTER JOIN (SELECT a.fin_lpbpurchase_id,SUM(a.fdc_total) as fdc_total FROM trpurchasereturn a WHERE fst_active !='D' GROUP BY a.fin_lpbpurchase_id) d
                ON a.fin_lpbpurchase_id = d.fin_lpbpurchase_id $swhere ORDER BY a.fin_lpbpurchase_id,a.fin_supplier_id";
                break;
            case "2":
                //vLPBPurchaseNettoValuePayment
                $ssql = "SELECT a.fin_lpbpurchase_id,a.fst_lpbpurchase_no as No_LPB, a.fdt_lpbpurchase_datetime as LPB_Date, a.fin_term as TOP,CAST(DATE_ADD(a.fdt_lpbpurchase_datetime, INTERVAL a.fin_term DAY) as DATE) as Jt_Date,
                a.fin_supplier_id as fin_supplier_id,b.fst_relation_name as Relation_Name,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,c.fst_po_no as No_PO,c.fdt_po_datetime as PO_Date,e.fst_cbpayment_no as No_Payment,
                e.fdt_cbpayment_datetime as Payment_Date,((a.fdc_subttl - a.fdc_disc_amount) + a.fdc_ppn_amount) as fdc_nilai_faktur,a.fdc_downpayment_claim as fdc_downpayment_claim,
                a.fdc_total as fdc_total,COALESCE(d.fdc_total,0) as fdc_total_retur,(a.fdc_total - (COALESCE(d.fdc_total,0))) as fdc_total_netto,e.fst_cbpayment_no,COALESCE(e.fdc_payment,0) as fdc_payment
                FROM (SELECT * FROM trlpbpurchase WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b
                ON a.fin_supplier_id = b.fin_relation_id  LEFT OUTER JOIN trpo c
                ON a.fin_po_id = c.fin_po_id LEFT OUTER JOIN (SELECT a.fin_lpbpurchase_id,SUM(a.fdc_total) as fdc_total FROM trpurchasereturn a WHERE a.fst_active !='D' GROUP BY a.fin_lpbpurchase_id) d
                ON a.fin_lpbpurchase_id = d.fin_lpbpurchase_id LEFT OUTER JOIN (SELECT a.fst_cbpayment_no,a.fdt_cbpayment_datetime,b.fin_trans_id,b.fdc_payment FROM trcbpayment a LEFT OUTER JOIN trcbpaymentitems b ON  a.fin_cbpayment_id = b.fin_cbpayment_id WHERE a.fst_active !='D' AND b.fst_trans_type='LPB_PO') e
                ON a.fin_lpbpurchase_id = e.fin_trans_id $swhere ORDER BY a.fin_supplier_id,a.fin_lpbpurchase_id";
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
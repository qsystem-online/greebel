<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Piutang_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Account Code', 'Account Name'];

    public function queryComplete($data, $sorder_by="a.fst_glaccount_code", $rptLayout="1") {
        
        $branch_id = "";
        $relation_id = "";
        $sales_id = "";
        $kasbank_id = "";
        $fst_orgi_curr_code = "";
        $start_date = "";
        $end_date = "";
        $piutang_gl_code = getGLConfig("AR_DAGANG_LOKAL");
        $piutangExpedisi_gl_code = getGLConfig("PIUTANG_EKSPEDISI_PENJUALAN");
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fin_sales_id'])) { $sales_id = $data['fin_sales_id'];}
        if (isset($data['kasbank_id'])) { $kasbank_id = $data['kasbank_id'];}
        if (isset($data['fst_orgi_curr_code'])) { $fst_orgi_curr_code = $data['fst_orgi_curr_code'];}
        if (isset($data['fdt_trx_datetime'])) { $start_date = $data['fdt_trx_datetime'];}
        if (isset($data['fdt_trx_datetime2'])) { $end_date = $data['fdt_trx_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($rptLayout == "1") {
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($sales_id > "0") {
                $swhere .= " AND a.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND a.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($fst_orgi_curr_code != "") {
                $swhere .= " AND b.fst_orgi_curr_code = " . $this->db->escape($fst_orgi_curr_code);
            }
        }

        if ($rptLayout == "2") {
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($sales_id > "0") {
                $swhere .= " AND a.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND a.fin_relation_id = " . $this->db->escape($relation_id);
            }
        }

        if ($rptLayout == "3" || $rptLayout == "4") {
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($sales_id > "0") {
                $swhere .= " AND a.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND a.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($kasbank_id > "0") {
                $swhere .= " AND c.fin_kasbank_id = " . $this->db->escape($kasbank_id);
            }
            if ($fst_orgi_curr_code != "") {
                $swhere .= " AND a.fst_curr_code = " . $this->db->escape($fst_orgi_curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " AND a.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
        }


        //if (isset($start_date)) {
        //    $swhere .= " AND b.fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        //}
        //if (isset($end_date)) {
        //    $swhere .= " AND b.fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        //}
        /*if ($rptLayout == "1"){
            if ($swhere != "") {
                //$swhere = " WHERE (b.fst_account_code = '".$piutang_gl_code."' OR b.fst_account_code = '".$piutangExpedisi_gl_code."') AND " . substr($swhere, 5);
                $swhere = " WHERE " . substr($swhere, 5);
            }
        }
        if ($rptLayout == "2"){
            if ($swhere != "") {
                $swhere = " WHERE " . substr($swhere, 5);
            }
        }*/
        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }

        //jika terjadi perubahan branchID dimaster relasi, kemungkinan terjadi report tidak sesuai karena record GLLedger juga simpan branchID dari transaksi
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_sales_id,a.fin_relation_id,a.fst_relation_name,IFNULL(b.fin_rec_id,0) AS fin_rec_id,IFNULL(b.fst_trx_sourcecode,'OB') AS fst_trx_sourcecode,b.fin_trx_id,b.fst_trx_no,
                b.fin_branch_id,b.fdt_trx_datetime,b.fst_account_code,b.fst_reference,IFNULL(b.fdc_debit,0) AS fdc_debit,IFNULL(b.fdc_credit,0) AS fdc_credit,c.fst_username AS Sales_Name
                FROM (SELECT * FROM msrelations WHERE fst_active !='D' AND FIND_IN_SET('1',fst_relation_type)) a 
                LEFT OUTER JOIN (SELECT * FROM glledger WHERE fst_active !='D' AND fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "' AND fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "' AND
                (fst_account_code = '".$piutang_gl_code."' OR fst_account_code = '".$piutangExpedisi_gl_code."') AND fst_orgi_curr_code = '".$fst_orgi_curr_code."') b 
                ON a.fin_relation_id = b.fin_relation_id LEFT OUTER JOIN users c
                ON a.fin_sales_id = c.fin_user_id ORDER BY a.fin_sales_id,a.fin_relation_id,b.fdt_trx_datetime,b.fin_rec_id";
                break;
            case "2":
                $ssql = "SELECT a.fin_sales_id,d.fst_username AS Sales_Name,a.fin_relation_id,a.fst_relation_name,b.fst_orgi_curr_code, b.fin_branch_id,IFNULL(c.fdc_debit,0) AS Total_Debit,
                IFNULL(c.fdc_credit,0) AS Total_Credit,IFNULL(b.SA_Debit,0) AS SA_Debit,IFNULL(b.SA_Credit,0) AS SA_Credit 
                FROM (SELECT * FROM msrelations WHERE fst_active !='D' AND FIND_IN_SET('1',fst_relation_type)) a 
                LEFT OUTER JOIN (SELECT fin_relation_id,fst_orgi_curr_code,fin_branch_id,SUM(fdc_debit) AS SA_Debit, SUM(fdc_credit) AS SA_Credit FROM glledger 
                WHERE fst_active !='D' AND fdt_trx_datetime < '" . date('Y-m-d', strtotime($start_date)) . "' AND (fst_account_code = '".$piutang_gl_code."' OR fst_account_code = '".$piutangExpedisi_gl_code."') AND fst_orgi_curr_code = '".$fst_orgi_curr_code."' GROUP BY fin_relation_id) b ON a.fin_relation_id = b.fin_relation_id 
                LEFT OUTER JOIN (SELECT fin_relation_id,SUM(fdc_debit) AS fdc_debit, SUM(fdc_credit) AS fdc_credit FROM glledger WHERE fst_active !='D' AND fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "' AND fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "' AND (fst_account_code = '".$piutang_gl_code."' OR fst_account_code = '".$piutangExpedisi_gl_code."') AND fst_orgi_curr_code = '".$fst_orgi_curr_code."' GROUP BY fin_relation_id) c 
                ON a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN users d ON a.fin_sales_id = d.fin_user_id
                $swhere GROUP BY a.fin_relation_id ORDER BY a.fin_sales_id,a.fin_relation_id";
                break;
            case "3":
                $ssql = "SELECT a.fin_branch_id,a.fin_inv_id as Id_Inv,a.fst_inv_no as No_Inv, a.fdt_inv_datetime as Inv_Date,a.fdc_downpayment_claim as fdc_downpayment_claim,a.fdc_total as fdc_total,a.fst_curr_code as Mata_Uang, a.fdc_total_return as fdc_total_return, a.fdc_total_paid as fdc_total_paid,(a.fdc_total - a.fdc_total_return) as Total_Netto,
                b.fst_relation_name as Relation_Name,c.fin_kasbank_id as Type_Kasbank,c.fst_cbreceive_no as Receive_No,c.fdt_cbreceive_datetime as Receive_Date,c.fdc_receive_amount as Receive_Amount
                FROM (SELECT * FROM trinvoice WHERE fst_active !='D') a LEFT OUTER JOIN
                msrelations b ON a.fin_relation_id = b.fin_relation_id
                LEFT OUTER JOIN (SELECT a.fin_kasbank_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,b.fst_trans_type,b.fin_trans_id,b.fdc_receive_amount FROM trcbreceive a 
                LEFT OUTER JOIN trcbreceiveitems b ON a.fin_cbreceive_id = b.fin_cbreceive_id WHERE a.fst_active !='D' AND b.fst_trans_type ='INV_SO') c ON a.fin_inv_id = c.fin_trans_id
                $swhere ORDER BY a.fst_inv_no";
                break;
            case "4":
                $ssql = "SELECT a.fin_branch_id,a.fin_inv_id as Id_Inv,a.fst_inv_no as No_Inv, a.fdt_inv_datetime as Inv_Date,CONCAT_WS('-',YEAR(a.fdt_inv_datetime),MONTH(a.fdt_inv_datetime)) AS periode,a.fdc_downpayment_claim as fdc_downpayment_claim,a.fdc_total as fdc_total,a.fst_curr_code as Mata_Uang, a.fdc_total_return as fdc_total_return, a.fdc_total_paid as fdc_total_paid,(a.fdc_total - a.fdc_total_return) as Total_Netto,
                b.fst_relation_name as Relation_Name,c.fin_kasbank_id as Type_Kasbank,c.fst_cbreceive_no as Receive_No,c.fdt_cbreceive_datetime as Receive_Date,c.fdc_receive_amount as Receive_Amount
                FROM (SELECT * FROM trinvoice WHERE fst_active !='D') a LEFT OUTER JOIN
                msrelations b ON a.fin_relation_id = b.fin_relation_id
                LEFT OUTER JOIN (SELECT a.fin_kasbank_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,b.fst_trans_type,b.fin_trans_id,b.fdc_receive_amount FROM trcbreceive a 
                LEFT OUTER JOIN trcbreceiveitems b ON a.fin_cbreceive_id = b.fin_cbreceive_id WHERE a.fst_active !='D' AND b.fst_trans_type ='INV_SO') c ON a.fin_inv_id = c.fin_trans_id
                $swhere ORDER BY a.fdt_inv_datetime,a.fin_relation_id";
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
            'field' => 'fin_relation_id',
            'label' => 'Customer',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_trx_datetime',
            'label' => 'Tanggal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_trx_datetime2',
            'label' => 'Tanggal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
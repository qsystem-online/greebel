<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bukubesar_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Account Code', 'Account Name'];

    public function queryComplete($data, $sorder_by="a.fst_glaccount_code", $rptLayout="1") {
        
        $branch_id = "";
        $start_glaccount = "";
        $end_glaccount = "";
        $start_date = "";
        $end_date = "";
        $fst_keyword = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fst_account_code'])) { $start_glaccount = $data['fst_account_code'];}
        if (isset($data['fst_account_code2'])) { $end_glaccount = $data['fst_account_code2'];}
        if (isset($data['fdt_trx_datetime'])) { $start_date = $data['fdt_trx_datetime'];}
        if (isset($data['fdt_trx_datetime2'])) { $end_date = $data['fdt_trx_datetime2'];}
        if (isset($data['fst_keyword'])) { $fst_keyword = $data['fst_keyword'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND b.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($start_glaccount != "") {
            $swhere .= " AND a.fst_glaccount_code >= " . $this->db->escape($start_glaccount);
        }
        if ($end_glaccount != "") {
            $swhere .= " AND a.fst_glaccount_code <= " . $this->db->escape($end_glaccount);
        }
        if ($fst_keyword != "") {
            $swhere .= " AND LEFT(b.fst_reference,50) LIKE ". $this->db->escape('%'.$fst_keyword.'%');
        }
        //if (isset($start_date)) {
        //    $swhere .= " AND b.fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        //}
        //if (isset($end_date)) {
        //    $swhere .= " AND b.fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        //}

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fst_glaccount_code,a.fst_glaccount_name,a.fst_glaccount_level,b.fin_rec_id,b.fst_trx_sourcecode,b.fin_trx_id,b.fst_trx_no,b.fin_branch_id,b.fdt_trx_datetime,b.fst_account_code,b.fst_reference,
                IFNULL(b.fdc_debit,0) AS fdc_debit,IFNULL(b.fdc_credit,0) AS fdc_credit
                FROM (SELECT * FROM glaccounts WHERE fst_active !='D') a 
                LEFT OUTER JOIN (SELECT * FROM glledger WHERE fst_active !='D' AND fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "' AND fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "') b ON a.fst_glaccount_code = b.fst_account_code $swhere ORDER BY a.fst_glaccount_code,b.fdt_trx_datetime,b.fin_rec_id,b.fdc_debit DESC";
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
            'field' => 'fst_account_code',
            'label' => 'GL Account',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_account_code2',
            'label' => 'GL Account',
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
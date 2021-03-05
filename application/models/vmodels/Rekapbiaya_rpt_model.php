<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekapbiaya_rpt_model extends CI_Model {

    public $layout1Columns = ['Account Code', 'Account Name', 'Jumlah'];

    public function queryComplete($data, $sorder_by="a.fst_account_code", $rptLayout="1") {
        
        $branch_id = "";
        $pcc_id = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_pcc_id'])) { $pcc_id = $data['fin_pcc_id'];}
        if (isset($data['fdt_trx_datetime'])) { $start_date = $data['fdt_trx_datetime'];}
        if (isset($data['fdt_trx_datetime2'])) { $end_date = $data['fdt_trx_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($pcc_id >"0") {
            $swhere .= " AND a.fin_pcc_id = " . $this->db->escape($pcc_id);
        }
        if (isset($start_date)) {
            $swhere .= " AND a.fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE b.fst_glaccount_level = 'DT' AND (b.fin_glaccount_maingroup_id = '7' OR b.fin_glaccount_maingroup_id = '9') AND " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT b.fst_glaccount_name,b.fst_glaccount_level,b.fin_glaccount_maingroup_id,a.fst_account_code,
                SUM(IFNULL(a.fdc_debit,0) - IFNULL(a.fdc_credit,0)) AS fdcJumlah
                FROM (SELECT * FROM glledger WHERE fst_active !='D') a 
                LEFT OUTER JOIN (SELECT * FROM glaccounts WHERE fst_active !='D') b ON a.fst_account_code = b.fst_glaccount_code $swhere GROUP BY a.fst_account_code ORDER BY a.fst_account_code";
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
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_kasbank_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Account Code', 'Account Name'];

    public function queryComplete($data, $sorder_by="a.fst_trx_no", $rptLayout="1") {
        
        $branch_id = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fdt_trx_datetime'])) { $start_date = $data['fdt_trx_datetime'];}
        if (isset($data['fdt_trx_datetime2'])) { $end_date = $data['fdt_trx_datetime2'];}
        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if (isset($start_date)) {
            $swhere .= " AND a.fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        switch($rptLayout){
            case "1":
                if ($swhere != "") {
                    $swhere = " WHERE a.fst_trx_sourcecode ='CBIN' AND " . substr($swhere, 5);
                }
                break;
            case "2":
                if ($swhere != "") {
                    $swhere = " WHERE a.fst_trx_sourcecode ='CBOUT' AND " . substr($swhere, 5);
                }
                break;
            case "3":
                if ($swhere != "") {
                    $swhere = " WHERE a.fst_trx_sourcecode ='CBPO' AND " . substr($swhere, 5);
                }
                break;
            case "4":
                if ($swhere != "") {
                    $swhere = " WHERE a.fst_trx_sourcecode ='CBRO' AND " . substr($swhere, 5);
                }
                break;
            case "5":
                if ($swhere != "") {
                    $swhere = " WHERE (a.fst_trx_sourcecode ='CBIN' OR a.fst_trx_sourcecode ='CBOUT' OR a.fst_trx_sourcecode ='CBPO' OR a.fst_trx_sourcecode ='CBRO')  AND " . substr($swhere, 5);
                }
                break;
            case "6":
                if ($swhere != "") {
                    $swhere = " WHERE a.fst_trx_sourcecode!='CBIN' AND a.fst_trx_sourcecode !='CBOUT' AND a.fst_trx_sourcecode !='CBPO' AND a.fst_trx_sourcecode !='CBRO'  AND " . substr($swhere, 5);
                }
                break;
    

        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        $ssql = "SELECT a.fin_rec_id AS Rec_Id,a.fin_branch_id,a.fst_trx_sourcecode AS Trx_Sourcecode,a.fin_trx_id AS Trx_Id,a.fst_trx_no AS Trx_No,a.fdt_trx_datetime AS Trx_Date,a.fst_reference AS Keterangan,a.fst_account_code AS Glaccount_Code,a.fst_account_name AS Glaccount_Name,
        IFNULL(a.fdc_debit,0) AS fdc_debit,IFNULL(a.fdc_credit,0) AS fdc_credit,a.fst_info AS Trx_Info
        FROM (SELECT * FROM glledger WHERE fst_active !='D') a 
        $swhere ORDER BY a.fst_trx_sourcecode,a.fdt_trx_datetime,a.fst_trx_no";
        
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
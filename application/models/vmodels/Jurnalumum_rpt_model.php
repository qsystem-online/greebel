<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jurnalumum_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Account Code', 'Account Name'];

    public function queryComplete($data, $sorder_by="a.fst_glaccount_code", $rptLayout="1") {
        
        $branch_id = "";
        $start_jurnal_type = "";
        $end_jurnal_type = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fst_journal_type'])) { $start_jurnal_type = $data['fst_journal_type'];}
        if (isset($data['fst_journal_type2'])) { $end_jurnal_type = $data['fst_journal_type2'];}
        if (isset($data['fdt_trx_datetime'])) { $start_date = $data['fdt_trx_datetime'];}
        if (isset($data['fdt_trx_datetime2'])) { $end_date = $data['fdt_trx_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($start_jurnal_type != "") {
            $swhere .= " AND a.fst_journal_type >= " . $this->db->escape($start_jurnal_type);
        }
        if ($end_jurnal_type != "") {
            $swhere .= " AND a.fst_journal_type <= " . $this->db->escape($end_jurnal_type);
        }
        if (isset($start_date)) {
            $swhere .= " AND a.fdt_journal_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_journal_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_journal_id AS Jurnal_Id,a.fst_journal_type AS Jurnal_Type,a.fst_journal_no AS Jurnal_No,a.fdt_journal_datetime AS Jurnal_Date,a.fst_desc AS Keterangan,a.fin_rec_id AS Rec_Id,a.fst_glaccount_code AS Glaccount_Code,b.fst_glaccount_name AS Glaccount_Name,
                a.fst_memo AS Memo_Detail,IFNULL(a.fdc_debit,0) AS fdc_debit,IFNULL(a.fdc_credit,0) AS fdc_credit,a.fin_insert_id,c.fst_username AS Entry_By
                FROM (SELECT a.*,b.fin_rec_id,b.fst_glaccount_code,b.fst_memo,b.fdc_debit,b.fdc_credit FROM gltrjournal a LEFT JOIN gltrjournalitems b ON a.fin_journal_id=b.fin_journal_id WHERE a.fst_active !='D') a 
                LEFT OUTER JOIN glaccounts b ON a.fst_glaccount_code = b.fst_glaccount_code
                LEFT OUTER JOIN users c ON a.fin_insert_id = c.fin_user_id $swhere ORDER BY a.fst_journal_type,a.fdt_journal_datetime,a.fst_journal_no";
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
            'field' => 'fst_journal_type',
            'label' => 'Jenis Jurnal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_journal_type2',
            'label' => 'Jenis Jurnal',
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
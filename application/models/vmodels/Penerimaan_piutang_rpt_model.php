<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_piutang_rpt_model extends CI_Model {

    public $layout1Columns = ['Account Code', 'Account Name', 'Jumlah'];

    public function queryComplete($data, $sorder_by="a.fin_cbreceive_id", $rptLayout="1") {
        
        $branch_id = "";
        $pcc_id = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fdt_cbreceive_datetime'])) { $start_date = $data['fdt_cbreceive_datetime'];}
        if (isset($data['fdt_cbreceive_datetime2'])) { $end_date = $data['fdt_cbreceive_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if (isset($start_date)) {
            $swhere .= " AND a.fdt_cbreceive_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " AND a.fdt_cbreceive_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_cbreceive_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,a.fin_customer_id,a.fst_glaccount_code,a.fdc_amount,
                b.fst_glaccount_name,c.fst_relation_name
                FROM (SELECT a.fin_branch_id,a.fin_cbreceive_id,a.fst_cbreceive_no,a.fdt_cbreceive_datetime,a.fin_customer_id,b.fst_cbreceive_type,b.fst_glaccount_code,b.fdc_amount FROM trcbreceive a LEFT OUTER JOIN trcbreceiveitemstype b ON a.fin_cbreceive_id = b.fin_cbreceive_id WHERE a.fst_active !='D' AND b.fst_cbreceive_type='GLACCOUNT' ) a 
                LEFT OUTER JOIN (SELECT * FROM glaccounts WHERE fst_active !='D') b ON a.fst_glaccount_code = b.fst_glaccount_code 
                LEFT OUTER JOIN msrelations c ON a.fin_customer_id = c.fin_relation_id $swhere ORDER BY a.fst_glaccount_code";
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
            'field' => 'fdt_cbreceive_datetime',
            'label' => 'Tanggal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_cbreceive_datetime2',
            'label' => 'Tanggal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
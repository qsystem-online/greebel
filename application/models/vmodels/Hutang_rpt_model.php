<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hutang_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Account Code', 'Account Name'];

    public function queryComplete($data, $sorder_by="a.fst_glaccount_code", $rptLayout="1") {
        
        $branch_id = "";
        $relation_id = "";
        $fst_orgi_curr_code = "";
        $start_date = "";
        $end_date = "";
        $hutangLokal_gl_code = getGLConfig("AP_DAGANG_LOKAL");
        $hutangImport_gl_code = getGLConfig("AP_DAGANG_IMPORT");
        $hutangExpedisi_gl_code = getGLConfig("HUTANG_EKSPEDISI_PENJUALAN");
        $biayaLokal_gl_code = getGLConfig("AP_BIAYA_PEMBELIAN_LOKAL");
        $biayaImport_gl_code = getGLConfig("AP_BIAYA_PEMBELIAN_IMPORT");
        $hutangWo_gl_code = getGLConfig("HUTANG_WORKORDER_EKSTERNAL");
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fst_orgi_curr_code'])) { $fst_orgi_curr_code = $data['fst_orgi_curr_code'];}
        if (isset($data['fdt_trx_datetime'])) { $start_date = $data['fdt_trx_datetime'];}
        if (isset($data['fdt_trx_datetime2'])) { $end_date = $data['fdt_trx_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($rptLayout == "1") {
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
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
            if ($relation_id > "0") {
                $swhere .= " AND a.fin_relation_id = " . $this->db->escape($relation_id);
            }
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }

        //jika terjadi perubahan branchID dimaster relasi, kemungkinan terjadi report tidak sesuai karena record GLLedger juga simpan branchID dari transaksi
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_relation_id,a.fst_relation_name,IFNULL(b.fin_rec_id,0) AS fin_rec_id,IFNULL(b.fst_trx_sourcecode,'OB') AS fst_trx_sourcecode,b.fin_trx_id,b.fst_trx_no,
                b.fin_branch_id,b.fdt_trx_datetime,b.fst_account_code,b.fst_reference,IFNULL(b.fdc_debit,0) AS fdc_debit,IFNULL(b.fdc_credit,0) AS fdc_credit
                FROM (SELECT * FROM msrelations WHERE fst_active !='D' AND (FIND_IN_SET('2',fst_relation_type) OR FIND_IN_SET('3',fst_relation_type))) a 
                LEFT OUTER JOIN (SELECT * FROM glledger WHERE fst_active !='D' AND fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "' AND fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "' AND
                (fst_account_code = '".$hutangLokal_gl_code."' 
                OR fst_account_code = '".$hutangImport_gl_code."' 
                OR fst_account_code = '".$hutangExpedisi_gl_code."'
                OR fst_account_code = '".$biayaLokal_gl_code."'
                OR fst_account_code = '".$biayaImport_gl_code."'
                OR fst_account_code = '".$hutangWo_gl_code."'
                ) AND fst_orgi_curr_code = '".$fst_orgi_curr_code."') b 
                ON a.fin_relation_id = b.fin_relation_id ORDER BY a.fin_relation_id,b.fdt_trx_datetime,b.fin_rec_id";
                break;
            case "2":
                $ssql = "SELECT a.fin_relation_id,a.fst_relation_name,b.fst_orgi_curr_code, b.fin_branch_id,IFNULL(c.fdc_debit,0) AS Total_Debit,
                IFNULL(c.fdc_credit,0) AS Total_Credit,IFNULL(b.SA_Debit,0) AS SA_Debit,IFNULL(b.SA_Credit,0) AS SA_Credit 
                FROM (SELECT * FROM msrelations WHERE fst_active !='D' AND (FIND_IN_SET('2',fst_relation_type) OR FIND_IN_SET('3',fst_relation_type))) a 
                LEFT OUTER JOIN (SELECT fin_relation_id,fst_orgi_curr_code,fin_branch_id,SUM(fdc_debit) AS SA_Debit, SUM(fdc_credit) AS SA_Credit FROM glledger 
                WHERE fst_active !='D' AND fdt_trx_datetime < '" . date('Y-m-d', strtotime($start_date)) . "' AND 
                (fst_account_code = '".$hutangLokal_gl_code."' 
                OR fst_account_code = '".$hutangImport_gl_code."' 
                OR fst_account_code = '".$hutangExpedisi_gl_code."'
                OR fst_account_code = '".$biayaLokal_gl_code."'
                OR fst_account_code = '".$biayaImport_gl_code."'
                OR fst_account_code = '".$hutangWo_gl_code."'
                ) AND fst_orgi_curr_code = '".$fst_orgi_curr_code."' GROUP BY fin_relation_id) b ON a.fin_relation_id = b.fin_relation_id 
                LEFT OUTER JOIN (SELECT fin_relation_id,SUM(fdc_debit) AS fdc_debit, SUM(fdc_credit) AS fdc_credit FROM glledger WHERE fst_active !='D' AND fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "' AND fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "' 
                AND 
                (fst_account_code = '".$hutangLokal_gl_code."' 
                OR fst_account_code = '".$hutangImport_gl_code."' 
                OR fst_account_code = '".$hutangExpedisi_gl_code."'
                OR fst_account_code = '".$biayaLokal_gl_code."'
                OR fst_account_code = '".$biayaImport_gl_code."'
                OR fst_account_code = '".$hutangWo_gl_code."'
                ) AND fst_orgi_curr_code = '".$fst_orgi_curr_code."' GROUP BY fin_relation_id) c 
                ON a.fin_relation_id = c.fin_relation_id $swhere GROUP BY a.fin_relation_id ORDER BY a.fin_relation_id";
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
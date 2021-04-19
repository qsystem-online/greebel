<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fixed_asset_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Kode F/A', 'Nama F/A'];

    public function queryComplete($data, $sorder_by="a.fst_fa_profile_no", $rptLayout="1") {
        
        $branch_id = "";
        $fa_group_id = "";
        $period_code = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_fa_group_id'])) { $fa_group_id = $data['fin_fa_group_id'];}
        if (isset($data['fst_period'])) { $period_code = $data['fst_period'];}
        if (isset($data['fdt_aquisition_date'])) { $start_date = $data['fdt_aquisition_date'];}
        if (isset($data['fdt_aquisition_date2'])) { $end_date = $data['fdt_aquisition_date2'];}

        $swhere = "";
        $sorderby = "";
        if ($rptLayout == "1") {
            if ($branch_id > "0") {
                $swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($fa_group_id > "0") {
                $swhere .= " AND a.fin_fa_group_id = " . $this->db->escape($fa_group_id);
            }
            if (isset($start_date)) {
                $swhere .= " AND a.fdt_aquisition_date >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND a.fdt_aquisition_date <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            //if ($period_code != "") {
            //    $swhere .= " AND b.fst_period = " . $this->db->escape($period_code);
            //}
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                //vFAProfileList
                $ssql = "SELECT a.fin_branch_id,a.fst_fa_profile_no AS FA_No,a.fst_type,a.fdt_aquisition_date AS Aquisition_Date,a.fin_lpbpurchase_detail_id,a.fdc_aquisition_price AS Nilai_Perolehan,a.fdc_residu_value AS Nilai_Residu,
                a.fin_life_time_period AS Umur_FA,a.fst_depre_period AS fst_depre_period,a.fst_fa_profile_code AS FA_Code,a.fst_fa_profile_name AS FA_Name,f.fst_pcc_name,g.fst_branch_name,
                a.fin_fa_group_id,b.fst_fa_group_name,c.fst_period,c.fdc_depre_amount,d.fst_fa_disposal_no AS Mutasi_No,e.fst_lpbpurchase_no AS Lpb_No,CAST(e.fdt_lpbpurchase_datetime AS DATE) AS Lpb_Date
                FROM (SELECT a.fin_branch_id,a.fin_pcc_id,a.fin_fa_profile_id,a.fst_fa_profile_no,a.fst_type,a.fdt_aquisition_date,a.fin_lpbpurchase_detail_id,a.fin_fa_disposal_detail_id,a.fdc_aquisition_price,a.fdc_residu_value,a.fdb_qty,a.fin_life_time_period,a.fst_depre_period,a.fin_fa_group_id,b.fst_fa_profile_code,b.fst_fa_profile_name 
                FROM trfaprofiles a LEFT JOIN trfaprofilesitems b ON a.fin_fa_profile_id = b.fin_fa_profile_id WHERE a.fst_active !='D') a
                LEFT OUTER JOIN msfagroups b ON a.fin_fa_group_id = b.fin_fa_group_id 
                LEFT OUTER JOIN (SELECT fst_fa_profile_code,MAX(IFNULL(fst_period,'')) AS fst_period,SUM(IFNULL(fdc_depre_amount,0)) AS fdc_depre_amount FROM trfadeprecard WHERE fst_active !='D' AND fst_period <= '".$period_code."'  GROUP BY fst_fa_profile_code) c ON a.fst_fa_profile_code = c.fst_fa_profile_code
                LEFT OUTER JOIN ( SELECT a.fin_fa_disposal_id,a.fst_fa_disposal_no,a.fdt_fa_disposal_datetime,b.fin_fa_profile_detail_id FROM trfadisposal a LEFT JOIN trfadisposalitems b ON a.fin_fa_disposal_id = b.fin_fa_disposal_id WHERE a.fst_disposal_type ='MUTASI' AND a.fst_active !='D') d ON a.fin_fa_disposal_detail_id = d.fin_fa_disposal_id
                LEFT OUTER JOIN trlpbpurchase e ON a.fin_lpbpurchase_detail_id = e.fin_lpbpurchase_id 
                LEFT OUTER JOIN msprofitcostcenter f ON a.fin_pcc_id = f.fin_pcc_id 
                LEFT OUTER JOIN msbranches g ON a.fin_branch_id = g.fin_branch_id " . $swhere . $sorderby;
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
            'field' => 'fin_fa_group_id',
            'label' => 'Group F/A',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_period',
            'label' => 'Periode',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_aquisition_date',
            'label' => 'Tgl Perolehan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_aquisition_date2',
            'label' => 'Tgl Perolehan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

}
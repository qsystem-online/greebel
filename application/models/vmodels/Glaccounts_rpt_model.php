<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Glaccounts_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Account Code', 'Account Name'];

    public function queryComplete($data, $sorder_by="a.fst_glaccount_code", $rptLayout="1") {
        
        $start_group_id = "";
        $end_group_id = "";
        $fbl_is_allow_in_cash_bank_module = "";
        $fbl_pc_divisi = "";
        $fbl_pc_customer = "";
        $fbl_pc_project = "";
        if (isset($data['fin_glaccount_maingroup_id'])) { $start_group_id = $data['fin_glaccount_maingroup_id'];}
        if (isset($data['fin_glaccount_maingroup_id2'])) { $end_group_id = $data['fin_glaccount_maingroup_id2'];}
        if (isset($data['fbl_is_allow_in_cash_bank_module'])) { $fbl_is_allow_in_cash_bank_module = $data['fbl_is_allow_in_cash_bank_module'];}
        if (isset($data['fbl_pc_divisi'])) { $fbl_pc_divisi = $data['fbl_pc_divisi'];}
        if (isset($data['fbl_pc_customer'])) { $fbl_pc_customer = $data['fbl_pc_customer'];}
        if (isset($data['fbl_pc_project'])) { $fbl_pc_project = $data['fbl_pc_project'];}

        $swhere = "";
        $sorderby = "";
        if ($start_group_id > "0") {
            $swhere .= " and a.fin_glaccount_maingroup_id >= '" . $start_group_id . "'";            
        }
        if ($end_group_id > "0") {
            $swhere .= " and a.fin_glaccount_maingroup_id <= '". $end_group_id . "'";
        }
        if ($fbl_is_allow_in_cash_bank_module == 1) {
            $swhere .= " and a.fbl_is_allow_in_cash_bank_module = " . $fbl_is_allow_in_cash_bank_module;
        }
        if ($fbl_pc_divisi == 1) {
            $swhere .= " and a.fbl_pc_divisi = " . $fbl_pc_divisi;
        }
        if ($fbl_pc_customer == 1) {
            $swhere .= " and a.fbl_pc_customer = " . $fbl_pc_customer;
        }
        if ($fbl_pc_project == 1) {
            $swhere .= " and a.fbl_pc_project = " . $fbl_pc_project;
        }
        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.*,b.fst_curr_name, c.fst_glaccount_name AS GLParentName, d.fst_glaccount_maingroup_name, d.fst_glaccount_main_prefix 
                FROM glaccounts a 
                LEFT JOIN mscurrencies b ON a.fst_curr_code = b.fst_curr_code 
                LEFT JOIN glaccounts c ON a.fst_parent_glaccount_code = c.fst_glaccount_code
                LEFT JOIN glaccountmaingroups d ON a.fin_glaccount_maingroup_id = d.fin_glaccount_maingroup_id" . $swhere . $sorderby;
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
            'field' => 'fin_glaccount_maingroup_id',
            'label' => 'Cabang',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    }   

    public function processReport($data) {
        // var_dump($data);die();
        //$data['fin_warehouse_id'], $data["fin_sales_order_datetime"], $data["fin_sales_order_datetime2"], $data["fin_relation_id"], $data['fin_sales_id']
        $dataReport = $this->queryComplete($data,"","1");
        // var_dump($recordset);
        // print_r($dataReturn["fields"]);die();
        
        // if (isset($this->$data['rows'])) {
        //     $reportData = $this->parser->parse('reports/sales_order/rpt',$this->$data["rows"], true);
        // } else {
        //     $reportData = $this->parser->parse('reports/sales_order/rpt',[], true);
        // }
        $reportData = $this->parser->parse('reports/glaccounts/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}
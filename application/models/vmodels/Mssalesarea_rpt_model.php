<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mssalesarea_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'ID', 'Nama Area'];

    public function queryComplete($data, $sorder_by="a.fin_sales_area_id", $rptLayout="1") {
        
        $start_regional_id = "";
        $end_regional_id = "";
        if (isset($data['fin_sales_regional_id'])) { $start_regional_id = $data['fin_sales_regional_id'];}
        if (isset($data['fin_sales_regional_id2'])) { $end_regional_id = $data['fin_sales_regional_id2'];}
        
        $swhere = "";
        $sorderby = "";
        if ($start_regional_id > "0") {
            $swhere .= " and a.fin_sales_regional_id >= '" . $start_regional_id . "'";            
        }
        if ($end_regional_id > "0") {
            $swhere .= " and a.fin_sales_regional_id <= '". $end_regional_id . "'";
        }
        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.*,b.fst_name AS RegionalName,c.fst_username AS SalesName FROM mssalesarea a 
                LEFT JOIN mssalesregional b ON a.fin_sales_regional_id = b.fin_sales_regional_id
                LEFT JOIN users c ON a.fin_sales_id = c.fin_user_id " . $swhere . $sorderby;
                break;
            default:
                break;
        }

        $query = $this->db->query($ssql);
        // $dataReturn["rows"]=$query->result();
        // $fields = $query->list_fields();
        // $dataReturn["fields"]=$fields;
        return $query->result();
    }

    public function getRules()
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_sales_regional_id',
            'label' => 'Regional',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

    public function processReport($data) {
        // var_dump($data);die();
        $dataReport = $this->queryComplete($data,"","1");
        // var_dump($recordset);
        // print_r($dataReturn["fields"]);die();
        
        $reportData = $this->parser->parse('reports/sales_area/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}
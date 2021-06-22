<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwo_realisasibahan_rpt_model extends CI_Model {

    public $layout1Columns = ['No W/O','Tanggal W/O'];

    public function queryComplete($data, $sorder_by="a.fin_wo_id", $rptLayout="1") {
        
        $wo_id = "";
        if (isset($data['fin_wo_id'])) { $wo_id = $data['fin_wo_id'];}

        $swhere = "";
        $sorderby = "";
        if ($wo_id > "0") {
            $swhere .= " and a.fin_wo_id = " . $this->db->escape($wo_id);
        }
        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout){
            case "1":
                $ssql = "SELECT a.fin_wo_id AS Wo_Id,a.fst_wo_no AS Wo_No,a.fdt_wo_datetime AS Wo_Date,a.fst_wo_type AS Wo_Type,a.fdt_wo_target_date AS Target_Date,a.fin_item_id AS Id_Product,
                a.fdb_qty AS Qty_Product,a.fst_unit AS Unit_Product,a.fdb_qty AS Qty_Wo,a.fdb_qty_lhp AS Qty_Lhp,b.fst_item_name AS Name_Product,b.fst_item_code AS Code_Product,c.fst_item_group_name AS Group_Product,
                d.fin_item_id AS Id_Bom,e.fst_item_name AS Name_Bom,e.fst_item_code AS Code_Bom,d.fdb_qty AS Qty_Bom,d.fst_unit AS Unit_Bom
                FROM (SELECT * FROM trwo WHERE fst_active !='D') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN 
                msgroupitems c ON b.fin_item_group_id = c.fin_item_group_id  LEFT OUTER JOIN  
                trwobomdetails d ON a.fin_wo_id = d.fin_wo_id LEFT OUTER JOIN
                msitems e on d.fin_item_id = e.fin_item_id ". $swhere . $sorderby;
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
            'field' => 'fin_wo_id',
            'label' => 'No. WO',
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
        $reportData = $this->parser->parse('reports/sales_order/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}
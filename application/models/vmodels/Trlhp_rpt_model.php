<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trlhp_rpt_model extends CI_Model {

    public $layout1Columns = ['No W/O','Tanggal W/O'];

    public function queryComplete($data, $sorder_by="a.fin_wo_id", $rptLayout="1") {
        
        $wo_id = "";
        $wo_id2 = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_wo_id'])) { $wo_id = $data['fin_wo_id'];}
        if (isset($data['fin_wo_id2'])) { $wo_id2 = $data['fin_wo_id2'];}
        if (isset($data['fdt_datetime'])) { $start_date = $data['fdt_datetime'];}
        if (isset($data['fdt_datetime2'])) { $end_date = $data['fdt_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($wo_id > "0") {
            $swhere .= " and a.fin_wo_id >= " . $this->db->escape($wo_id);
        }
        if ($wo_id2 > "0") {
            $swhere .= " and a.fin_wo_id <= " . $this->db->escape($wo_id2);
        }
        switch($rptLayout){
            case "1":
                if (isset($start_date)) {
                    $swhere .= " and a.fdt_wo_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
                }
                if (isset($end_date)) {
                    $swhere .= " and a.fdt_wo_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
                }
                break;
            case "2":
                if (isset($start_date)) {
                    $swhere .= " and a.fdt_lhp_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
                }
                if (isset($end_date)) {
                    $swhere .= " and a.fdt_lhp_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
                }
                break;
            case "3":
                if (isset($start_date)) {
                    $swhere .= " and a.fdt_insert_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
                }
                if (isset($end_date)) {
                    $swhere .= " and a.fdt_insert_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
                }
                break;
            case "4":
                if (isset($start_date)) {
                    $swhere .= " and a.fdt_update_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
                }
                if (isset($end_date)) {
                    $swhere .= " and a.fdt_update_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
                }
                break;
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
                a.fst_unit AS Unit_Product,a.fdb_qty AS Qty_Wo,b.fst_item_name AS Name_Product,b.fst_item_code AS Code_Product,a.fst_notes
                FROM (SELECT * FROM trwo WHERE fst_active !='D') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id ". $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_lhp_id AS Lhp_Id,a.fst_lhp_no AS Lhp_No,c.fst_wo_no AS Wo_No,a.fdt_lhp_datetime AS Lhp_Date,a.fin_item_id AS Id_Product,a.fdb_qty_sisa,a.fst_wo_unit,
                a.fst_unit AS Unit_Product,a.fdb_qty AS Qty_Lhp,b.fst_item_name AS Name_Product,b.fst_item_code AS Code_Product,a.fst_notes
                FROM (SELECT * FROM trlhp WHERE fst_active !='D') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.fin_lhp_id AS Lhp_Id,a.fst_lhp_no AS Lhp_No,c.fst_wo_no AS Wo_No,a.fdt_lhp_datetime AS Lhp_Date,a.fdt_insert_datetime,a.fin_item_id AS Id_Product,a.fdb_qty_sisa,a.fst_wo_unit,
                a.fst_unit AS Unit_Product,a.fdb_qty AS Qty_Lhp,b.fst_item_name AS Name_Product,b.fst_item_code AS Code_Product,a.fst_notes
                FROM (SELECT * FROM trlhp WHERE fst_active !='D') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
                break;
            case "4":
                $ssql = "SELECT a.fin_lhp_id AS Lhp_Id,a.fst_lhp_no AS Lhp_No,c.fst_wo_no AS Wo_No,a.fdt_lhp_datetime AS Lhp_Date,a.fdt_update_datetime,a.fin_item_id AS Id_Product,a.fdb_qty_sisa,a.fst_wo_unit,
                a.fst_unit AS Unit_Product,a.fdb_qty AS Qty_Lhp,b.fst_item_name AS Name_Product,b.fst_item_code AS Code_Product,a.fst_notes
                FROM (SELECT * FROM trlhp WHERE fst_active !='D') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
                break;
            default:
                $ssql = "SELECT a.fin_wo_id AS Wo_Id,a.fst_wo_no AS Wo_No,a.fdt_wo_datetime AS Wo_Date,a.fst_wo_type AS Wo_Type,a.fdt_wo_target_date AS Target_Date,a.fin_item_id AS Id_Product,
                a.fst_unit AS Unit_Product,a.fdb_qty AS Qty_Wo,b.fst_item_name AS Name_Product,b.fst_item_code AS Code_Product,a.fst_notes
                FROM (SELECT * FROM trwo WHERE fst_active !='D') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id ". $swhere . $sorderby;
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

        $rules[] = [
            'field' => 'fin_wo_id2',
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
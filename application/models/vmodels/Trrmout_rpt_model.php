<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trrmout_rpt_model extends CI_Model {

    public $layout1Columns = ['No RMOUT','Tanggal RMOUT'];

    public function queryComplete($data, $sorder_by="a.fin_rmout_id", $rptLayout="1") {
        
        $start_date = "";
        $end_date = "";

        if (isset($data['fdt_datetime'])) { $start_date = $data['fdt_datetime'];}
        if (isset($data['fdt_datetime2'])) { $end_date = $data['fdt_datetime2'];}

        $swhere = "";
        $sorderby = "";
        switch($rptLayout){
            case "1":
                if (isset($start_date)) {
                    $swhere .= " and a.fdt_rmout_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
                }
                if (isset($end_date)) {
                    $swhere .= " and a.fdt_rmout_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
                }
                break;
            case "2":
                if (isset($start_date)) {
                    $swhere .= " and a.fdt_insert_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
                }
                if (isset($end_date)) {
                    $swhere .= " and a.fdt_insert_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
                }
                break;
            case "3":
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
                $ssql = "SELECT a.fin_rmout_id AS Rmout_Id,a.fst_rmout_no AS Rmout_No,c.fst_wo_no AS Wo_No,a.fdt_rmout_datetime AS Rmout_Date,a.fin_item_id AS Id_Product,
                a.fst_unit AS Unit_Bahan,a.fdb_qty AS Qty_Bahan,b.fst_item_name AS Name_Bahan,b.fst_item_code AS Code_Bahan
                FROM (SELECT a.fin_rmout_id,a.fst_rmout_no,a.fin_wo_id,a.fdt_rmout_datetime,b.fin_item_id,b.fst_unit,b.fdb_qty FROM trrmout a INNER JOIN trrmoutitems b ON a.fin_rmout_id=b.fin_rmout_id WHERE a.fst_active !='D' AND a.fin_wo_id !='null') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_rmout_id AS Rmout_Id,a.fst_rmout_no AS Rmout_No,c.fst_wo_no AS Wo_No,a.fdt_rmout_datetime AS Rmout_Date,a.fdt_insert_datetime,a.fin_item_id AS Id_Product,
                a.fst_unit AS Unit_Bahan,a.fdb_qty AS Qty_Bahan,b.fst_item_name AS Name_Bahan,b.fst_item_code AS Code_Bahan
                FROM (SELECT a.fin_rmout_id,a.fst_rmout_no,a.fin_wo_id,a.fdt_rmout_datetime,a.fdt_insert_datetime,b.fin_item_id,b.fst_unit,b.fdb_qty FROM trrmout a INNER JOIN trrmoutitems b ON a.fin_rmout_id=b.fin_rmout_id WHERE a.fst_active !='D' AND a.fin_wo_id !='null') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.fin_rmout_id AS Rmout_Id,a.fst_rmout_no AS Rmout_No,c.fst_wo_no AS Wo_No,a.fdt_rmout_datetime AS Rmout_Date,a.fdt_update_datetime,a.fin_item_id AS Id_Product,
                a.fst_unit AS Unit_Bahan,a.fdb_qty AS Qty_Bahan,b.fst_item_name AS Name_Bahan,b.fst_item_code AS Code_Bahan
                FROM (SELECT a.fin_rmout_id,a.fst_rmout_no,a.fin_wo_id,a.fdt_rmout_datetime,a.fdt_update_datetime,b.fin_item_id,b.fst_unit,b.fdb_qty FROM trrmout a INNER JOIN trrmoutitems b ON a.fin_rmout_id=b.fin_rmout_id WHERE a.fst_active !='D' AND a.fin_wo_id !='null') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
                break;
            default:
                $ssql = "SELECT a.fin_rmout_id AS Rmout_Id,a.fst_rmout_no AS Rmout_No,c.fst_wo_no AS Wo_No,a.fdt_rmout_datetime AS Rmout_Date,a.fin_item_id AS Id_Product,
                a.fst_unit AS Unit_Bahan,a.fdb_qty AS Qty_Bahan,b.fst_item_name AS Name_Bahan,b.fst_item_code AS Code_Bahan
                FROM (SELECT a.fin_rmout_id,a.fst_rmout_no,a.fin_wo_id,a.fdt_rmout_datetime,b.fin_item_id,b.fst_unit,b.fdb_qty FROM trrmout a INNER JOIN trrmoutitems b ON a.fin_rmout_id=b.fin_rmout_id WHERE a.fst_active !='D' AND a.fin_wo_id !='null') a INNER JOIN
                msitems b ON a.fin_item_id = b.fin_item_id INNER JOIN
                trwo c ON a.fin_wo_id = c.fin_wo_id ". $swhere . $sorderby;
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
            'field' => 'fdt_datetime',
            'label' => 'Tanggal RMOUT',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_datetime2',
            'label' => 'Tanggal RMOUT',
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
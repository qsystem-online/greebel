<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Adjustment_rpt_model extends CI_Model {

    public $layout1Columns = ['No.', 'No.Adjustment', 'Tanggal'];

    public function queryComplete($data, $sorder_by="a.fst_adjustment_no", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $start_item = "";
        $end_item = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fst_item_code'])) { $start_item = $data['fst_item_code'];}
        if (isset($data['fst_item_code2'])) { $end_item = $data['fst_item_code2'];}
        if (isset($data['fdt_adjustment_datetime'])) { $start_date = $data['fdt_adjustment_datetime'];}
        if (isset($data['fdt_adjustment_datetime2'])) { $end_date = $data['fdt_adjustment_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " and a.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($warehouse_id > "0" ) {
            $swhere .= " and a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
        }
        if ($start_item > "0") {
            $swhere .= " and d.fst_item_code >= " . $this->db->escape($start_item);
        }
        if ($end_item > "0") {
            $swhere .= " and d.fst_item_code <= " . $this->db->escape($end_item);
        }
        if (isset($start_date)) {
            $swhere .= " and a.fdt_adjustment_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " and a.fdt_adjustment_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout){
            case "1":
                $ssql = "SELECT a.fin_adjustment_id AS Adj_Id,a.fst_adjustment_no as Adj_No,a.fdt_adjustment_datetime as Adj_Date,a.fst_reff as Reff_No,a.fst_notes AS Memo,
                a.fin_warehouse_id as Warehouse_Id, c.fst_warehouse_name as Warehouse,b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, d.fst_item_name as Item_Name,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fst_in_out as In_out
                FROM (SELECT * FROM tradjustment WHERE fst_active !='D') a INNER JOIN
                tradjustmentitems b on a.fin_adjustment_id = b.fin_adjustment_id LEFT OUTER JOIN  
                mswarehouse c on a.fin_warehouse_id = c.fin_warehouse_id LEFT OUTER JOIN
                msitems d on b.fin_item_id = d.fin_item_id ". $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_adjustment_id AS Adj_Id,a.fst_adjustment_no as Adj_No,a.fdt_adjustment_datetime as Adj_Date,a.fst_reff as Reff_No,a.fst_notes AS Memo,
                a.fin_warehouse_id as Warehouse_Id, c.fst_warehouse_name as Warehouse,b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, d.fst_item_name as Item_Name,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fst_in_out as In_out
                FROM (SELECT * FROM tradjustment WHERE fst_active !='D') a INNER JOIN
                tradjustmentitems b on a.fin_adjustment_id = b.fin_adjustment_id LEFT OUTER JOIN  
                mswarehouse c on a.fin_warehouse_id = c.fin_warehouse_id LEFT OUTER JOIN
                msitems d on b.fin_item_id = d.fin_item_id ". $swhere . $sorderby;
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
            'field' => 'fdt_adjustment_datetime',
            'label' => 'Tanggal Adjustment',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_adjustment_datetime2',
            'label' => 'Tanggal Adjustment',
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
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trpurchaserequest_rpt_model extends CI_Model {

    public $layout1Columns = ['Department', 'No.Request', 'Tanggal Request'];

    public function queryComplete($data, $sorder_by="a.fin_pr_id", $rptLayout="1") {
        
        $req_department_id = "";
        $item_id = "";
        $start_req_date = "";
        $end_req_date = "";
        if (isset($data['fin_req_department_id'])) { $req_department_id = $data['fin_req_department_id'];}
        if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
        if (isset($data['fdt_pr_datetime'])) { $start_req_date = $data['fdt_pr_datetime'];}
        if (isset($data['fdt_pr_datetime2'])) { $end_req_date = $data['fdt_pr_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($rptLayout == "1" || $rptLayout == "3"){
            if ($req_department_id > "0") {
                $swhere .= " AND a.fin_req_department_id = " . $this->db->escape($req_department_id);
            }
            if ($item_id > "0") {
                $swhere .= " AND d.fin_item_id = " . $this->db->escape($item_id);
            }
            if (isset($start_req_date)) {
                $swhere .= " AND a.fdt_pr_datetime >= '" . date('Y-m-d', strtotime($start_req_date)) . "'";            
            }
            if (isset($end_req_date)) {
                $swhere .= " AND a.fdt_pr_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_req_date)). "'";
            }
        }else{
            if ($req_department_id > "0") {
                $swhere .= " AND a.fin_req_department_id = " . $this->db->escape($req_department_id);
            }
            if (isset($start_req_date)) {
                $swhere .= " AND a.fdt_pr_datetime >= '" . date('Y-m-d', strtotime($start_req_date)) . "'";            
            }
            if (isset($end_req_date)) {
                $swhere .= " AND a.fdt_pr_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_req_date)). "'";
            }
        }


        if ($rptLayout == "3"){
            if ($swhere != "") {
                $swhere = " AND " . substr($swhere, 5);
            }
        }else{
            if ($swhere != "") {
                $swhere = " WHERE " . substr($swhere, 5);
            }
        }
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fst_pr_no as No_PR, a.fdt_pr_datetime as PR_Date,a.fdt_publish_datetime as Publish_Date,a.fdt_use_datetime as Use_Date, a.fst_memo as PR_Memo,
                a.fin_req_department_id, a.fbl_rejected,a.fst_rejected_note, c.fst_department_name as Request_By,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, d.fst_item_name as Item_Name,
                b.fdb_qty_req as Qty_Req, b.fst_unit as Unit, b.fdb_qty_process as Qty_Process, b.fdb_qty_to_po as Qty_Po, b.fdb_qty_distribute as Qty_Distribute,
                b.fdt_distribute_datetime,b.fbl_closed_distribute,b.fdt_etd,b.fst_memo as Detail_Memo 
                FROM (SELECT * FROM trpurchaserequest WHERE fst_active !='D') a left join trpurchaserequestitems b 
                on a.fin_pr_id = b.fin_pr_id left join departments c
                on a.fin_req_department_id = c.fin_department_id left join msitems d
                on b.fin_item_id = d.fin_item_id $swhere ORDER BY c.fin_department_id";
                break;
            case "2":
                $ssql = "SELECT a.fst_pr_no as No_PR, a.fdt_pr_datetime as PR_Date,a.fdt_publish_datetime as Publish_Date,a.fdt_use_datetime as Use_Date, a.fst_memo as PR_Memo,
                a.fin_req_department_id, a.fbl_rejected,a.fst_rejected_note, b.fst_department_name as Request_By
                FROM (SELECT * FROM trpurchaserequest WHERE fst_active !='D') a left join departments b
                on a.fin_req_department_id = b.fin_department_id " . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.fst_pr_no as No_PR, a.fdt_pr_datetime as PR_Date,a.fdt_publish_datetime as Publish_Date,a.fdt_use_datetime as Use_Date, a.fst_memo as PR_Memo,
                a.fin_req_department_id, a.fbl_rejected,a.fst_rejected_note, c.fst_department_name as Request_By,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, d.fst_item_code as Item_Code, d.fst_item_name as Item_Name,
                b.fdb_qty_req as Qty_Req, b.fst_unit as Unit, b.fdb_qty_process as Qty_Process, b.fdb_qty_to_po as Qty_Po, b.fdb_qty_distribute as Qty_Distribute,
                b.fdt_distribute_datetime,b.fbl_closed_distribute,b.fdt_etd,b.fst_memo as Detail_Memo 
                FROM (SELECT * FROM trpurchaserequest WHERE fst_active !='D') a right join trpurchaserequestitems b 
                on a.fin_pr_id = b.fin_pr_id left join departments c
                on a.fin_req_department_id = c.fin_department_id left join msitems d
                on b.fin_item_id = d.fin_item_id 
                WHERE (b.fdb_qty_req - b.fdb_qty_to_po) >0 $swhere ORDER BY c.fin_department_id";
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
            'field' => 'fin_req_department_id',
            'label' => 'Department',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_pr_datetime',
            'label' => 'Tgl Request',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_pr_datetime2',
            'label' => 'Tgl Request',
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
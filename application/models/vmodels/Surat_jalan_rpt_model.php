<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_jalan_rpt_model extends CI_Model {

    public $layout1Columns = ['Pelanggan/Customer', 'No S/J', 'Tanggal S/J'];

    public function queryComplete($data, $sorder_by="a.fst_relation_name", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $relation_id = "";
        $sj_type = "";
        $start_date = "";
        $end_date = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fst_sj_type'])) { $sj_type = $data['fst_sj_type'];}
        if (isset($data['fdt_sj_datetime'])) { $start_date = $data['fdt_sj_datetime'];}
        if (isset($data['fdt_sj_datetime2'])) { $end_date = $data['fdt_sj_datetime2'];}

        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " and b.fin_branch_id = " . $this->db->escape($branch_id);
        }
        if ($warehouse_id > "0" ) {
            $swhere .= " and b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
        }
        if ($sj_type =="SO" || $sj_type =="PO_RETURN"){
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
        }
        if ($sj_type != "") {
            $swhere .= " and b.fst_sj_type = " . $this->db->escape($sj_type);
        }
        if (isset($start_date)) {
            $swhere .= " and b.fdt_sj_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " and b.fdt_sj_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout){
            case "1":
                if ($sj_type == "SO") {
                    $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,
                    b.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fst_relation_name as Relation_Name,
                    c.fin_rec_id as Rec_Id, c.fin_item_id as Item_Id, e.fst_item_code as Item_Code, e.fst_item_name as Item_Name,
                    c.fdb_qty as Qty, c.fst_unit as Unit, b.fst_salesorder_no as No_Ref
                    FROM msrelations a RIGHT OUTER JOIN
                    (SELECT a.*,b.fin_relation_id as fin_relation_id,b.fst_salesorder_no FROM trsuratjalan a
                    LEFT JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id WHERE a.fst_sj_type ='SO' AND a.fst_active !='D') b ON b.fin_relation_id = a.fin_relation_id LEFT OUTER JOIN 
                    trsuratjalandetails c on b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN  
                    mswarehouse d on b.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN
                    msitems e on c.fin_item_id = e.fin_item_id ". $swhere . $sorderby;
                }else if ($sj_type == "PO_RETURN"){
                    $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,
                    b.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fst_relation_name as Relation_Name,
                    c.fin_rec_id as Rec_Id, c.fin_item_id as Item_Id, e.fst_item_code as Item_Code, e.fst_item_name as Item_Name,
                    c.fdb_qty as Qty, c.fst_unit as Unit, b.fst_purchasereturn_no as No_Ref
                    FROM msrelations a RIGHT OUTER JOIN
                    (SELECT a.*,b.fin_supplier_id as fin_relation_id,b.fst_purchasereturn_no FROM trsuratjalan a
                    LEFT JOIN trpurchasereturn b on a.fin_trans_id = b.fin_purchasereturn_id WHERE a.fst_sj_type ='PO_RETURN' AND a.fst_active !='D') b ON b.fin_relation_id = a.fin_relation_id LEFT OUTER JOIN 
                    trsuratjalandetails c on b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN  
                    mswarehouse d on b.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN
                    msitems e on c.fin_item_id = e.fin_item_id ". $swhere . $sorderby;
                }else if ($sj_type == "ASSEMBLING_OUT"){
                    $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,
                    b.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,
                    c.fin_rec_id as Rec_Id, c.fin_item_id as Item_Id, e.fst_item_code as Item_Code, e.fst_item_name as Item_Name,
                    c.fdb_qty as Qty, c.fst_unit as Unit, a.fst_assembling_no as No_Ref
                    FROM trassembling a RIGHT OUTER JOIN
                    (SELECT * FROM trsuratjalan WHERE fst_sj_type ='ASSEMBLING_OUT' AND fst_active !='D') b on b.fin_trans_id = a.fin_assembling_id LEFT OUTER JOIN 
                    trsuratjalandetails c on b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN  
                    mswarehouse d on b.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN
                    msitems e on c.fin_item_id = e.fin_item_id $swhere ORDER BY b.fst_sj_no";
                }
                break;
            case "2":
                $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,
                b.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fst_relation_name as Relation_Name,
                c.fst_inv_no as No_Inv,CAST(c.fdt_inv_datetime as DATE) as Inv_Date, CAST(c.fdt_payment_due_date as DATE) as JT_Date, b.fst_salesorder_no as No_SO, CAST(b.fdt_salesorder_datetime as DATE) as SO_Date
                FROM msrelations a RIGHT OUTER JOIN
                (SELECT a.*,b.fin_relation_id as fin_relation_id,b.fst_salesorder_no,b.fdt_salesorder_datetime FROM trsuratjalan a
                LEFT OUTER JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id WHERE a.fst_sj_type ='SO' AND a.fst_active !='D') b ON b.fin_relation_id = a.fin_relation_id LEFT OUTER JOIN
                (SELECT a.fst_inv_no,a.fdt_inv_datetime,DATE_ADD(a.fdt_inv_datetime, INTERVAL a.fin_terms_payment DAY) as fdt_payment_due_date,b.fin_sj_id FROM trinvoice a
                LEFT OUTER JOIN trinvoicedetails b ON a.fin_inv_id = b.fin_inv_id) c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                mswarehouse d ON b.fin_warehouse_id = d.fin_warehouse_id $swhere ORDER BY a.fst_relation_name, b.fst_sj_no";
                break;
            case "3":
                if ($sj_type == "SO"){
                    $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,b.fin_warehouse_id as Warehouse_Id, e.fst_warehouse_name as Warehouse,a.fst_relation_name as Relation_Name,
                    b.fst_salesorder_no as No_Ref, CAST(b.fdt_salesorder_datetime as DATE) as Ref_Date,b.fin_print_no as Print_No,c.fst_username as Entry_BY, d.fst_username as Update_BY
                    FROM msrelations a RIGHT OUTER JOIN
                    (SELECT a.*,b.fin_relation_id as fin_relation_id,b.fst_salesorder_no,b.fdt_salesorder_datetime FROM trsuratjalan a
                    LEFT OUTER JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id WHERE a.fst_sj_type ='SO' AND a.fst_active !='D') b 
                    ON b.fin_relation_id = a.fin_relation_id LEFT OUTER JOIN 
                    users c ON b.fin_insert_id = c.fin_user_id LEFT OUTER JOIN
                    users d ON b.fin_update_id = d.fin_user_id LEFT OUTER JOIN
                    mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id $swhere ORDER BY a.fst_relation_name, b.fst_sj_no";
                }else if ($sj_type == "PO_RETURN"){
                    $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,b.fin_warehouse_id as Warehouse_Id, e.fst_warehouse_name as Warehouse,a.fst_relation_name as Relation_Name,
                    b.fst_purchasereturn_no as No_Ref, CAST(b.fdt_purchasereturn_datetime as DATE) as Ref_Date,b.fin_print_no as Print_No,c.fst_username as Entry_BY, d.fst_username as Update_BY
                    FROM msrelations a RIGHT OUTER JOIN
                    (SELECT a.*,b.fin_supplier_id as fin_relation_id,b.fst_purchasereturn_no,b.fdt_purchasereturn_datetime FROM trsuratjalan a
                    LEFT OUTER JOIN trpurchasereturn b on a.fin_trans_id = b.fin_purchasereturn_id WHERE a.fst_sj_type ='PO_RETURN' AND a.fst_active !='D') b 
                    ON b.fin_relation_id = a.fin_relation_id LEFT OUTER JOIN 
                    users c ON b.fin_insert_id = c.fin_user_id LEFT OUTER JOIN
                    users d ON b.fin_update_id = d.fin_user_id LEFT OUTER JOIN
                    mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id $swhere ORDER BY a.fst_relation_name, b.fst_sj_no";
                }else if ($sj_type == "ASSEMBLING_OUT"){
                    $ssql = "SELECT b.fst_sj_no as No_SJ, b.fdt_sj_datetime as SJ_Date, b.fst_sj_type as Type,b.fin_warehouse_id as Warehouse_Id, e.fst_warehouse_name as Warehouse,
                    a.fst_assembling_no as No_Ref, CAST(a.fdt_assembling_datetime as DATE) as Ref_Date,b.fin_print_no as Print_No,c.fst_username as Entry_BY, d.fst_username as Update_BY
                    FROM trassembling a RIGHT OUTER JOIN
                    (SELECT * FROM trsuratjalan WHERE fst_sj_type ='ASSEMBLING_OUT' AND fst_active !='D') b on b.fin_trans_id = a.fin_assembling_id LEFT OUTER JOIN
                    users c ON b.fin_insert_id = c.fin_user_id LEFT OUTER JOIN
                    users d ON b.fin_update_id = d.fin_user_id LEFT OUTER JOIN
                    mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id $swhere ORDER BY b.fst_sj_no";
                }
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
            'field' => 'fin_branch_id',
            'label' => 'Cabang',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fin_warehouse_id',
            'label' => 'Gudang',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fin_relation_id',
            'label' => 'Customer',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_sj_datetime',
            'label' => 'Tgl S/J',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_sj_datetime2',
            'label' => 'Tgl S/J',
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
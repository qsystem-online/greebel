<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_order_rpt_model extends CI_Model {

    public $layout1Columns = ['Pelanggan/Customer', 'Sales Order No', 'Tanggal S/O'];

    public function queryComplete($data, $sorder_by="a.fin_salesorder_id", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $relation_id = "";
        $sales_id = "";
        $start_date = "";
        $end_date = "";
        $fbl_is_vat_include = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fin_sales_id'])) { $sales_id = $data['fin_sales_id'];}
        if (isset($data['fdt_salesorder_datetime'])) { $start_date = $data['fdt_salesorder_datetime'];}
        if (isset($data['fdt_salesorder_datetime2'])) { $end_date = $data['fdt_salesorder_datetime2'];}
        if (isset($data['fbl_is_vat_include'])) { $fbl_is_vat_include = $data['fbl_is_vat_include'];}

        $swhere = "";
        $sorderby = "";
        // if ($area_code > 0) {
        //     $swhere += " and a.fin_sales_area_id = " . $sales_area_id;
        // }
        if ($rptLayout == "1"){
            if ($branch_id > "0") {
                $swhere .= " and a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0" ) {
                $swhere .= " and a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and a.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and a.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if (isset($start_date)) {
                $swhere .= " and a.fdt_salesorder_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and a.fdt_salesorder_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and a.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "2"){
            if ($branch_id > "0") {
                $swhere .= " and a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0" ) {
                $swhere .= " and a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and a.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and a.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if (isset($start_date)) {
                $swhere .= " and a.fdt_salesorder_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and a.fdt_salesorder_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and a.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "3"){
            if ($branch_id > "0") {
                $swhere .= " and b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0" ) {
                $swhere .= " and b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if (isset($start_date)) {
                $swhere .= " and b.fdt_salesorder_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and b.fdt_salesorder_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }

        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "select a.fst_salesorder_no as No_SO, a.fdt_salesorder_datetime as SO_Date, a.fin_terms_payment as TOP,
                a.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,c.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, e.fst_username as Sales_Name,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, f.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount  
                from trsalesorder a left join trsalesorderdetails b 
                on a.fin_salesorder_id = b.fin_salesorder_id left join msrelations c
                on a.fin_relation_id = c.fin_relation_id left join mswarehouse d
                on a.fin_warehouse_id = d.fin_warehouse_id left join users e
                on a.fin_sales_id = e.fin_user_id left join msitems f
                on b.fin_item_id = f.fin_item_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "select a.fst_salesorder_no as No_SO, a.fdt_salesorder_datetime as SO_Date, a.fin_terms_payment as TOP, a.fdc_subttl as fdc_subttl, a.fdc_disc_amount as fdc_disc_amount, a.fdc_total as fdc_total, a.fdc_downpayment as fdc_downpayment,
                a.fdc_downpayment_paid as fdc_downpayment_paid, a.fdc_downpayment_claimed as fdc_downpayment_claimed, a.fdc_dpp_amount as fdc_dpp_amount, a.fdc_vat_amount as fdc_vat_amount,
                a.fin_warehouse_id as Warehouse_Id, c.fst_warehouse_name as Warehouse,b.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, d.fst_username as Sales_Name
                from trsalesorder a left join msrelations b
                on a.fin_relation_id = b.fin_relation_id left join mswarehouse c
                on a.fin_warehouse_id = c.fin_warehouse_id left join users d
                on a.fin_sales_id = d.fin_user_id " . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT b.fin_salesorder_id as Id_SO,b.fst_salesorder_no as No_SO, b.fdt_salesorder_datetime as SO_Date, b.fin_terms_payment as TOP,b.fin_warehouse_id as Warehouse_Id,b.fin_sales_id as Sales_Id,
                a.fst_username as Sales_Name,c.fst_relation_name as Relation_Name,f.fin_rec_id as ID_DetailSO, f.fin_item_id as Item_Id,g.fst_item_code as Item_Code,f.fst_custom_item_name as Item_Name,
                f.fdb_qty as Qty, f.fst_unit as Unit, e.fst_sj_no as fst_sj_no, e.fdt_sj_datetime as fdt_sj_datetime,d.fdb_qty as qty_sj,h.fst_warehouse_name as Warehouse
                FROM users a RIGHT OUTER JOIN 
                trsalesorder b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN 
                msrelations c ON b.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN 
                (SELECT a.fin_trans_id,a.fin_sj_id,b.fin_trans_detail_id,b.fin_item_id,b.fdb_qty FROM trsuratjalan a LEFT OUTER JOIN trsuratjalandetails b ON a.fin_sj_id = b.fin_sj_id) d LEFT OUTER JOIN
                trsuratjalan e ON d.fin_sj_id = e.fin_sj_id RIGHT OUTER JOIN 
                trsalesorderdetails f ON d.fin_trans_id = f.fin_salesorder_id AND d.fin_trans_detail_id = f.fin_rec_id AND d.fin_item_id = f.fin_item_id LEFT OUTER JOIN 
                msitems g ON f.fin_item_id = g.fin_item_id ON b.fin_salesorder_id = f.fin_salesorder_id LEFT OUTER JOIN
                mswarehouse h ON b.fin_warehouse_id = h.fin_warehouse_id $swhere ORDER BY b.fin_salesorder_id";
                break;
            case "4":
                $ssql = "select a.fin_salesorder_id as Id_SO,a.fst_salesorder_no as No_SO, a.fdt_salesorder_datetime as SO_Date, a.fin_terms_payment as TOP,
                a.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,c.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, e.fst_username as Sales_Name,
                b.fin_rec_id as ID_DetailSO, b.fin_item_id as Item_Id, f.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,
                b.fdb_qty as Qty, b.fst_unit as Unit, g.fst_sj_no as fst_sj_no, g.fdt_sj_datetime as fdt_sj_datetime,h.fdb_qty as qty_sj
                from trsalesorder a left join trsalesorderdetails b 
                on a.fin_salesorder_id = b.fin_salesorder_id left join msrelations c
                on a.fin_relation_id = c.fin_relation_id left join mswarehouse d
                on a.fin_warehouse_id = d.fin_warehouse_id left join users e
                on a.fin_sales_id = e.fin_user_id left join msitems f
                on b.fin_item_id = f.fin_item_id left join trsuratjalan g
                on a.fin_salesorder_id = g.fin_trans_id left join trsuratjalandetails h
                on g.fin_sj_id = h.fin_sj_id left join trsalesorderdetails i
                on h.fin_trans_detail_id = i.fin_rec_id " . $swhere . $sorderby;
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
            'field' => 'fin_sales_id',
            'label' => 'Sales',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_salesorder_datetime',
            'label' => 'Tgl SO',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_salesorder_datetime2',
            'label' => 'Tgl SO',
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
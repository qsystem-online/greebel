<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_rpt_model extends CI_Model {

    public $layout1Columns = ['Pelanggan/Customer', 'No.Faktur', 'Tanggal Faktur'];

    public function queryComplete($data, $sorder_by="a.fst_inv_no", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $relation_id = "";
        $sales_id = "";
        $item_id = "";
        $start_date = "";
        $end_date = "";
        $fbl_is_vat_include = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fin_sales_id'])) { $sales_id = $data['fin_sales_id'];}
        if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
        if (isset($data['fdt_inv_datetime'])) { $start_date = $data['fdt_inv_datetime'];}
        if (isset($data['fdt_inv_datetime2'])) { $end_date = $data['fdt_inv_datetime2'];}
        if (isset($data['fbl_is_vat_include'])) { $fbl_is_vat_include = $data['fbl_is_vat_include'];}

        $swhere = "";
        $sorderby = "";
        // if ($area_code > 0) {
        //     $swhere += " and a.fin_sales_area_id = " . $sales_area_id;
        // }
        if ($rptLayout == "1" || $rptLayout == "8"){
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
            if ($item_id > "0") {
                $swhere .= " and b.fin_item_id = " . $this->db->escape($item_id);
            }
            if (isset($start_date)) {
                $swhere .= " and a.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and a.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and a.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "2" || $rptLayout == "6"){
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
                $swhere .= " and a.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and a.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and a.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "3"){
            if ($branch_id > "0") {
                $swhere .= " and b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($item_id > "0") {
                $swhere .= " and c.fin_item_id = " . $this->db->escape($item_id);
            }
            if (isset($start_date)) {
                $swhere .= " and b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "4"){
            if ($branch_id > "0") {
                $swhere .= " and b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($item_id > "0") {
                $swhere .= " and c.fin_item_id = " . $this->db->escape($item_id);
            }
            if (isset($start_date)) {
                $swhere .= " and b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }

        if ($rptLayout == "5"){
            if ($branch_id > "0") {
                $swhere .= " and b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if (isset($start_date)) {
                $swhere .= " and b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }

        if ($rptLayout == "7"){
            if ($branch_id > "0") {
                $swhere .= " and b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if (isset($start_date)) {
                $swhere .= " and b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
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
                $ssql = "SELECT a.fin_inv_id,a.fst_inv_no as No_Inv, a.fst_salesorder_no as No_SO, a.fdt_inv_datetime as Inv_Date, a.fin_terms_payment as TOP,CAST(DATE_ADD(a.fdt_inv_datetime, INTERVAL a.fin_terms_payment DAY) as DATE) as Jt_Date,
                a.fst_inv_memo as Inv_Memo,a.fdc_dpp_amount as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fin_relation_id as fin_relation_id,c.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, e.fst_username as Sales_Name,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, f.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_memo_item as Memo_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount  
                FROM (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') a LEFT OUTER JOIN trinvoiceitems b 
                on a.fin_inv_id = b.fin_inv_id LEFT OUTER JOIN msrelations c
                on a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN mswarehouse d
                on a.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN users e
                on a.fin_sales_id = e.fin_user_id LEFT OUTER JOIN msitems f
                on b.fin_item_id = f.fin_item_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_inv_id,a.fst_inv_no as No_Inv,a.fst_salesorder_no as No_SO, a.fdt_inv_datetime as Inv_Date, a.fin_terms_payment as TOP,CAST(DATE_ADD(a.fdt_inv_datetime, INTERVAL a.fin_terms_payment DAY) as DATE) as Jt_Date,
                a.fst_inv_memo as Inv_Memo,a.fdc_dpp_amount as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_warehouse_id as Warehouse_Id, c.fst_warehouse_name as Warehouse,a.fin_relation_id as fin_relation_id,b.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, d.fst_username as Sales_Name
                FROM (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') a LEFT OUTER JOIN  msrelations b
                on a.fin_relation_id = b.fin_relation_id left join mswarehouse c
                on a.fin_warehouse_id = c.fin_warehouse_id left join users d
                on a.fin_sales_id = d.fin_user_id " . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT b.fin_relation_id as Relation_Id,a.fst_relation_name as Relation_Name,c.fin_item_id as Item_Id,d.fst_item_code as Item_Code,c.fst_custom_item_name as Item_Name,
                c.fst_unit as Unit,SUM(c.fdb_qty) as Ttl_Qty,SUM((c.fdb_qty * (c.fdc_price - c.fdc_disc_amount_per_item))) as fdc_jumlah 
                FROM msrelations a RIGHT OUTER JOIN 
                (SELECT * FROM trinvoice WHERE fst_active !='D') b ON a.fin_relation_id = b.fin_relation_id LEFT OUTER JOIN
                trinvoiceitems c ON b.fin_inv_id = c.fin_inv_id LEFT OUTER JOIN
                msitems d ON c.fin_item_id = d.fin_item_id  $swhere GROUP BY b.fin_relation_id,a.fst_relation_name,c.fin_item_id,d.fst_item_code,c.fst_custom_item_name,c.fst_unit";
                break;
            case "4":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,c.fin_item_id as Item_Id,d.fst_item_code as Item_Code,c.fst_custom_item_name as Item_Name,
                c.fst_unit as Unit,SUM(c.fdb_qty) as Ttl_Qty,SUM((c.fdb_qty * (c.fdc_price - c.fdc_disc_amount_per_item))) as fdc_jumlah 
                FROM users a RIGHT OUTER JOIN 
                (SELECT * FROM trinvoice WHERE fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trinvoiceitems c ON b.fin_inv_id = c.fin_inv_id LEFT OUTER JOIN
                msitems d ON c.fin_item_id = d.fin_item_id  $swhere GROUP BY b.fin_sales_id,a.fst_username,c.fin_item_id,d.fst_item_code,c.fst_custom_item_name,c.fst_unit";
                break;
            case "5":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,b.fin_relation_id as Relation_Id,d.fst_relation_name as Relation_Name,b.fin_inv_id as Id_Inv,b.fst_inv_no as No_Inv,b.fst_salesorder_no as No_SO,f.fst_sj_no as No_SJ, b.fdt_inv_datetime as Inv_Date, b.fin_terms_payment as TOP,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang, b.fdc_total_return as fdc_total_return, b.fdc_total_paid as fdc_total_paid,
                ((b.fdc_total - b.fdc_total_return) - b.fdc_total_paid) as Saldo_Piutang,datediff(current_date(),CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE)) as Menunggak_Hari,e.fst_warehouse_name as Warehouse
                FROM users a RIGHT OUTER JOIN 
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trsuratjalan c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN 
                (SELECT b.fin_inv_id, GROUP_CONCAT(a.fst_sj_no) AS fst_sj_no FROM trsuratjalan a LEFT OUTER JOIN trinvoicedetails b ON a.fin_sj_id = b.fin_sj_id WHERE b.fst_active !='D' GROUP BY b.fin_inv_id) f ON b.fin_inv_id = f.fin_inv_id $swhere ORDER BY a.fst_username,d.fst_relation_name,b.fst_inv_no";
                break;
            case "6":
                $ssql = "SELECT a.fin_inv_id,a.fst_inv_no as No_Inv,a.fst_salesorder_no as No_SO, a.fdt_inv_datetime as Inv_Date, a.fin_terms_payment as TOP,CAST(DATE_ADD(a.fdt_inv_datetime, INTERVAL a.fin_terms_payment DAY) as DATE) as Jt_Date,
                a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,
                a.fin_warehouse_id as Warehouse_Id, c.fst_warehouse_name as Warehouse,a.fin_relation_id as fin_relation_id,b.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, d.fst_username as Sales_Name
                FROM (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') a LEFT OUTER JOIN  msrelations b
                on a.fin_relation_id = b.fin_relation_id LEFT OUTER JOIN  mswarehouse c
                on a.fin_warehouse_id = c.fin_warehouse_id LEFT OUTER JOIN  users d
                on a.fin_sales_id = d.fin_user_id $swhere ORDER BY d.fst_username,a.fst_inv_no";
                break;
            case "7":
                $ssql = "SELECT b.fst_inv_no as No_Inv, b.fdt_inv_datetime as Inv_Date, b.fst_salesorder_no as No_SO,b.fin_warehouse_id as Warehouse_Id, e.fst_warehouse_name as Warehouse,a.fst_relation_name as Relation_Name,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,c.fst_username as Entry_BY, d.fst_username as Update_BY,f.fst_username as Sales_Name,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang
                FROM msrelations a RIGHT OUTER JOIN
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b 
                ON b.fin_relation_id = a.fin_relation_id LEFT OUTER JOIN 
                users c ON b.fin_insert_id = c.fin_user_id LEFT OUTER JOIN
                users d ON b.fin_update_id = d.fin_user_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN
                users f ON b.fin_sales_id = f.fin_user_id $swhere ORDER BY b.fst_inv_no";
                break;
            case "8":
                $ssql = "SELECT a.fin_inv_id,a.fst_inv_no as No_Inv, a.fdt_inv_datetime as Inv_Date,g.fst_salesorder_no as No_SO, a.fin_terms_payment as TOP,CAST(DATE_ADD(a.fdt_inv_datetime, INTERVAL a.fin_terms_payment DAY) as DATE) as Jt_Date,
                a.fst_inv_memo as Inv_Memo,a.fdc_dpp_amount as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_warehouse_id as Warehouse_Id, d.fst_warehouse_name as Warehouse,a.fin_relation_id as fin_relation_id,c.fst_relation_name as Relation_Name, a.fin_sales_id as Sales_Id, e.fst_username as Sales_Name,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, f.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_memo_item as Memo_Item,
                b.fdb_qty as Qty, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount  
                FROM (SELECT a.*,b.fin_promo_id FROM trinvoice a LEFT OUTER JOIN trinvoiceitems b ON a.fin_inv_id = b.fin_inv_id WHERE a.fst_active !='D' and b.fin_promo_id > 0) a LEFT OUTER JOIN 
                trinvoiceitems b on a.fin_inv_id = b.fin_inv_id LEFT OUTER JOIN 
                msrelations c on a.fin_relation_id = c.fin_relation_id LEFT OUTER JOIN 
                mswarehouse d on a.fin_warehouse_id = d.fin_warehouse_id LEFT OUTER JOIN 
                users e on a.fin_sales_id = e.fin_user_id LEFT OUTER JOIN 
                msitems f on b.fin_item_id = f.fin_item_id LEFT OUTER JOIN 
                trsalesorder g on a.fin_salesorder_id = g.fin_salesorder_id $swhere ORDER BY a.fst_inv_no";
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
            'field' => 'fdt_inv_datetime',
            'label' => 'Tgl Faktur',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_inv_datetime2',
            'label' => 'Tgl Faktur',
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
        $reportData = $this->parser->parse('reports/invoice/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}
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
            if ($item_id > "0") {
                $swhere .= " and a.fin_item_id = " . $this->db->escape($item_id);
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
            if ($warehouse_id > "0" ) {
                $swhere .= " and b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($item_id > "0") {
                $swhere .= " and b.fin_item_id = " . $this->db->escape($item_id);
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
            if ($warehouse_id > "0" ) {
                $swhere .= " and b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($item_id > "0") {
                $swhere .= " and b.fin_item_id = " . $this->db->escape($item_id);
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
            if ($swhere != "") {
                $swhere = " and " . substr($swhere, 5);
            }
        }else{
            if ($swhere != "") {
                $swhere = " where " . substr($swhere, 5);
            }
        }
        //if ($swhere != "") {
        //    $swhere = " where " . substr($swhere, 5);
        //}
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
                $ssql = "SELECT b.fin_inv_id,b.fst_inv_no as No_Inv,b.fin_salesorder_id as Id_SO,b.fst_salesorder_no as No_SO, b.fdt_salesorder_datetime as SO_Date, b.fin_terms_payment as TOP,b.fin_warehouse_id as Warehouse_Id,b.fin_sales_id as Sales_Id,
                a.fst_username as Sales_Name,c.fst_relation_name as Relation_Name,f.fin_rec_id as ID_DetailSO, f.fin_item_id as Item_Id,g.fst_item_code as Item_Code,f.fst_custom_item_name as Item_Name,
                f.fdb_qty as Qty, f.fst_unit as Unit, e.fst_sj_no as fst_sj_no, e.fdt_sj_datetime as fdt_sj_datetime,d.fdb_qty as qty_sj,h.fst_warehouse_name as Warehouse
                FROM msrelations a RIGHT OUTER JOIN 
                (SELECT * FROM trinvoice WHERE fst_active !='D') b ON a.fin_relation_id = b.fin_relation_id LEFT OUTER JOIN
                trinvoiceitems c ON b.fin_inv_id = c.fin_inv_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN 
                msitems e ON c.fin_item_id = e.fin_item_id  $swhere ORDER BY b.fin_salesorder_id";
                //GROUP BY fstTitle,fstCustCode,fstCustName,fstItemCode,fstItemName,fstSatuan,fmnRate
                break;
            case "4":
                $ssql = "SELECT b.fin_salesorder_id as Id_SO,b.fst_salesorder_no as No_SO, b.fdt_salesorder_datetime as SO_Date, b.fin_terms_payment as TOP,b.fin_warehouse_id as Warehouse_Id,b.fin_sales_id as Sales_Id,
                c.fst_username as Sales_Name,a.fst_relation_name as Relation_Name,e.fin_rec_id as ID_DetailSO, e.fin_item_id as Item_Id,d.fst_item_code as Item_Code,e.fst_custom_item_name as Item_Name,
                (e.fdb_qty - e.fdb_qty_out) as Qty_OS, e.fst_unit as Unit,(e.fdc_price - e.fdc_disc_amount_per_item) as Harga_Netto, ((e.fdb_qty - e.fdb_qty_out) * (e.fdc_price - e.fdc_disc_amount_per_item)) as Amount,f.fst_warehouse_name as Warehouse
                FROM msrelations a RIGHT OUTER JOIN 
                (SELECT * FROM trsalesorder WHERE fst_active !='D') b LEFT OUTER JOIN 
                users c ON b.fin_sales_id = c.fin_user_id ON a.fin_relation_id = b.fin_relation_id LEFT OUTER JOIN
                msitems d RIGHT OUTER JOIN 
                trsalesorderdetails e ON d.fin_item_id = e.fin_item_id ON b.fin_salesorder_id = e.fin_salesorder_id LEFT OUTER JOIN
                mswarehouse f ON b.fin_warehouse_id = f.fin_warehouse_id
                WHERE (e.fdb_qty - e.fdb_qty_out) >0 $swhere ORDER BY b.fin_salesorder_id";
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
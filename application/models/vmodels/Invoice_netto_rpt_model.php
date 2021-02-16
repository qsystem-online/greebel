<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_netto_rpt_model extends CI_Model {

    public $layout1Columns = ['Pelanggan/Customer', 'No.Faktur', 'Tanggal Faktur'];

    public function queryComplete($data, $sorder_by="b.fst_inv_no", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $relation_id = "";
        $sales_id = "";
        $item_id = "";
        $curr_code = "";
        $start_date = "";
        $end_date = "";
        $due_date = "";
        $fbl_is_vat_include = "";
        $area_code = "";

        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fin_sales_id'])) { $sales_id = $data['fin_sales_id'];}
        if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fdt_inv_datetime'])) { $start_date = $data['fdt_inv_datetime'];}
        if (isset($data['fdt_inv_datetime2'])) { $end_date = $data['fdt_inv_datetime2'];}
        if (isset($data['fdt_due_datetime'])) { $due_date = $data['fdt_due_datetime'];}
        if (isset($data['fbl_is_vat_include'])) { $fbl_is_vat_include = $data['fbl_is_vat_include'];}
        if (isset($data['fst_area_code'])) { $area_code = $data['fst_area_code'];}

        $swhere = "";
        $sorderby = "";
        // if ($area_code > 0) {
        //     $swhere += " and a.fin_sales_area_id = " . $sales_area_id;
        // }
        if ($rptLayout == "1" || $rptLayout == "2"){
            if ($branch_id > "0") {
                $swhere .= " AND b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0") {
                $swhere .= " AND b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " AND b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($curr_code != "") {
                $swhere .= " AND b.fst_curr_code = " . $this->db->escape($curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " AND b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if (isset($due_date)) {
                $swhere .= " AND CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) AS DATE) <='". date('Y-m-d 23:59:59', strtotime($due_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " AND b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "3" || $rptLayout == "4"){
            if ($branch_id > "0") {
                $swhere .= " AND b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0") {
                $swhere .= " AND b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " AND b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($curr_code != "") {
                $swhere .= " AND b.fst_curr_code = " . $this->db->escape($curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " AND b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if (isset($due_date)) {
                $swhere .= " AND CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) AS DATE) <= '". date('Y-m-d 23:59:59', strtotime($due_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " AND b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "5"){
            if ($branch_id > "0") {
                $swhere .= " AND b.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($warehouse_id > "0") {
                $swhere .= " AND b.fin_warehouse_id = " . $this->db->escape($warehouse_id);
            }
            if ($relation_id > "0") {
                $swhere .= " AND b.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " AND b.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($curr_code != "") {
                $swhere .= " AND b.fst_curr_code = " . $this->db->escape($curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " AND b.fdt_inv_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " AND b.fdt_inv_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if (isset($due_date)) {
                $swhere .= " AND CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) AS DATE) <= '". date('Y-m-d 23:59:59', strtotime($due_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " AND b.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
            if ($area_code > "0") {
                $swhere .= " AND d.fst_area_code LIKE '" . $area_code ."%'";
            }
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }

        if ($sorder_by != "") {
            if ($rptLayout != "5")
                $sorderby = " ORDER BY " .$sorder_by;
            else{
                switch (strlen($area_code)){
                    case "2":
                        $sorderby = " ORDER BY h.fst_nama";
                        break;
                    case "5":
                        $sorderby = " ORDER BY i.fst_nama";
                        break;
                    case "8":
                        $sorderby = " ORDER BY j.fst_nama";
                        break;
                    case "13":
                        $sorderby = " ORDER BY " .$sorder_by;
                        break;
                }
            }
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,b.fin_relation_id as Relation_Id,d.fst_relation_name as Relation_Name,b.fin_inv_id as Id_Inv,b.fst_inv_no as No_Inv,b.fst_salesorder_no as No_SO,f.fst_sj_no as No_SJ, b.fdt_inv_datetime as Inv_Date, b.fin_terms_payment as TOP,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang, b.fdc_total_return as fdc_total_return, b.fdc_total_paid as fdc_total_paid,
                (b.fdc_total - b.fdc_total_return) as Total_Netto,e.fst_warehouse_name as Warehouse
                FROM users a RIGHT OUTER JOIN 
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trsuratjalan c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN 
                (SELECT b.fin_inv_id, GROUP_CONCAT(a.fst_sj_no) AS fst_sj_no FROM trsuratjalan a LEFT OUTER JOIN trinvoicedetails b ON a.fin_sj_id = b.fin_sj_id WHERE b.fst_active !='D' GROUP BY b.fin_inv_id) f ON b.fin_inv_id = f.fin_inv_id $swhere ORDER BY b.fst_inv_no";
                break;
            case "2":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,b.fin_relation_id as Relation_Id,d.fst_relation_name as Relation_Name,b.fin_inv_id as Id_Inv,b.fst_inv_no as No_Inv,b.fst_salesorder_no as No_SO,f.fst_sj_no as No_SJ, b.fdt_inv_datetime as Inv_Date, b.fin_terms_payment as TOP,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang, b.fdc_total_return as fdc_total_return, b.fdc_total_paid as fdc_total_paid,
                (b.fdc_total - b.fdc_total_return) as Total_Netto,b.fin_warehouse_id,e.fst_warehouse_name as Warehouse
                FROM users a RIGHT OUTER JOIN 
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trsuratjalan c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN 
                (SELECT b.fin_inv_id, GROUP_CONCAT(a.fst_sj_no) AS fst_sj_no FROM trsuratjalan a LEFT OUTER JOIN trinvoicedetails b ON a.fin_sj_id = b.fin_sj_id WHERE b.fst_active !='D' GROUP BY b.fin_inv_id) f ON b.fin_inv_id = f.fin_inv_id $swhere ORDER BY b.fin_warehouse_id,b.fst_inv_no";
                break;
            case "3":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,b.fin_relation_id as Relation_Id,d.fst_relation_name as Relation_Name,b.fin_inv_id as Id_Inv,b.fst_inv_no as No_Inv,b.fst_salesorder_no as No_SO,f.fst_sj_no as No_SJ, b.fdt_inv_datetime as Inv_Date, b.fin_terms_payment as TOP,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang, b.fdc_total_return as fdc_total_return, b.fdc_total_paid as fdc_total_paid,
                (b.fdc_total - b.fdc_total_return) as Total_Netto,b.fin_warehouse_id,e.fst_warehouse_name as Warehouse
                FROM users a RIGHT OUTER JOIN 
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trsuratjalan c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN 
                (SELECT b.fin_inv_id, GROUP_CONCAT(a.fst_sj_no) AS fst_sj_no FROM trsuratjalan a LEFT OUTER JOIN trinvoicedetails b ON a.fin_sj_id = b.fin_sj_id WHERE b.fst_active !='D' GROUP BY b.fin_inv_id) f ON b.fin_inv_id = f.fin_inv_id $swhere ORDER BY b.fin_sales_id,b.fst_inv_no";
                break;
            case "4":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,b.fin_relation_id as Relation_Id,d.fst_relation_name as Relation_Name,b.fin_inv_id as Id_Inv,b.fst_inv_no as No_Inv,b.fst_salesorder_no as No_SO,f.fst_sj_no as No_SJ, b.fdt_inv_datetime as Inv_Date, b.fin_terms_payment as TOP,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang, b.fdc_total_return as fdc_total_return, b.fdc_total_paid as fdc_total_paid,
                (b.fdc_total - b.fdc_total_return) as Total_Netto,b.fin_warehouse_id,e.fst_warehouse_name as Warehouse
                FROM users a RIGHT OUTER JOIN 
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trsuratjalan c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN 
                (SELECT b.fin_inv_id, GROUP_CONCAT(a.fst_sj_no) AS fst_sj_no FROM trsuratjalan a LEFT OUTER JOIN trinvoicedetails b ON a.fin_sj_id = b.fin_sj_id WHERE b.fst_active !='D' GROUP BY b.fin_inv_id) f ON b.fin_inv_id = f.fin_inv_id $swhere ORDER BY b.fin_relation_id,b.fst_inv_no";
                break;
            case "5":
                $ssql = "SELECT b.fin_sales_id as Sales_Id,a.fst_username as Sales_Name,b.fin_relation_id as Relation_Id,d.fst_relation_name as Relation_Name,b.fin_inv_id as Id_Inv,b.fst_inv_no as No_Inv,b.fst_salesorder_no as No_SO,f.fst_sj_no as No_SJ, b.fdt_inv_datetime as Inv_Date, b.fin_terms_payment as TOP,
                CAST(DATE_ADD(b.fdt_inv_datetime, INTERVAL b.fin_terms_payment DAY) as DATE) as Jt_Date,b.fdc_total as fdc_total,b.fst_curr_code as Mata_Uang, b.fdc_total_return as fdc_total_return, b.fdc_total_paid as fdc_total_paid,
                (b.fdc_total - b.fdc_total_return) as Total_Netto,b.fin_warehouse_id,e.fst_warehouse_name as Warehouse,
                IFNULL(MID(d.fst_area_code, 1, 2),'') AS provinces,IFNULL(MID(d.fst_area_code, 1, 5),'') AS district,IFNULL(MID(d.fst_area_code, 1, 8),'') AS subdistrict,IFNULL(MID(d.fst_area_code, 1, 13),'') AS village,
                g.fst_nama AS fst_province_name,h.fst_nama AS fst_district_name,i.fst_nama AS fst_subdistrict_name,j.fst_nama AS fst_village_name
                FROM users a RIGHT OUTER JOIN 
                (SELECT a.*,b.fst_salesorder_no FROM trinvoice a LEFT OUTER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id WHERE a.fst_active !='D') b ON a.fin_user_id = b.fin_sales_id LEFT OUTER JOIN
                trsuratjalan c ON b.fin_sj_id = c.fin_sj_id LEFT OUTER JOIN
                msrelations d ON b.fin_relation_id = d.fin_relation_id LEFT OUTER JOIN
                mswarehouse e ON b.fin_warehouse_id = e.fin_warehouse_id LEFT OUTER JOIN 
                (SELECT b.fin_inv_id, GROUP_CONCAT(a.fst_sj_no) AS fst_sj_no FROM trsuratjalan a 
                LEFT OUTER JOIN trinvoicedetails b ON a.fin_sj_id = b.fin_sj_id WHERE b.fst_active !='D' GROUP BY b.fin_inv_id) f ON b.fin_inv_id = f.fin_inv_id
                LEFT OUTER JOIN msarea g ON MID(d.fst_area_code, 1, 2) = g.fst_kode
                LEFT OUTER JOIN msarea h ON MID(d.fst_area_code, 1, 5) = h.fst_kode
                LEFT OUTER JOIN msarea i ON MID(d.fst_area_code, 1, 8) = i.fst_kode
                LEFT OUTER JOIN msarea j ON MID(d.fst_area_code, 1, 13) = j.fst_kode "
                . $swhere . $sorderby;
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
            'field' => 'fst_curr_code',
            'label' => 'Mata Uang',
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
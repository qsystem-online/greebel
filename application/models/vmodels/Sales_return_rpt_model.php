<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_return_rpt_model extends CI_Model {

    public $layout1Columns = ['Pelanggan/Customer', 'No.Retur', 'Tgl Retur'];

    public function queryComplete($data, $sorder_by="a.fst_salesreturn_no", $rptLayout="1") {
        
        $warehouse_id = "";
        $branch_id = "";
        $relation_id = "";
        $sales_id = "";
        $curr_code = "";
        $start_date = "";
        $end_date = "";
        $fbl_is_vat_include = "";

        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        if (isset($data['fin_sales_id'])) { $sales_id = $data['fin_sales_id'];}
        if (isset($data['fst_curr_code'])) { $curr_code = $data['fst_curr_code'];}
        if (isset($data['fdt_salesreturn_datetime'])) { $start_date = $data['fdt_salesreturn_datetime'];}
        if (isset($data['fdt_salesreturn_datetime2'])) { $end_date = $data['fdt_salesreturn_datetime2'];}
        if (isset($data['fbl_is_vat_include'])) { $fbl_is_vat_include = $data['fbl_is_vat_include'];}

        $swhere = "";
        $sorderby = "";

        if ($rptLayout == "1"){
            if ($branch_id > "0") {
                $swhere .= " and a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and a.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and d.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($curr_code != "") {
                $swhere .= " and a.fst_curr_code = " . $this->db->escape($curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " and a.fdt_salesreturn_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and a.fdt_salesreturn_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and a.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }
        if ($rptLayout == "2"){
            if ($branch_id > "0") {
                $swhere .= " and a.fin_branch_id = " . $this->db->escape($branch_id);
            }
            if ($relation_id > "0") {
                $swhere .= " and a.fin_relation_id = " . $this->db->escape($relation_id);
            }
            if ($sales_id > "0") {
                $swhere .= " and d.fin_sales_id = " . $this->db->escape($sales_id);
            }
            if ($curr_code != "") {
                $swhere .= " and a.fst_curr_code = " . $this->db->escape($curr_code);
            }
            if (isset($start_date)) {
                $swhere .= " and a.fdt_salesreturn_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
            }
            if (isset($end_date)) {
                $swhere .= " and a.fdt_salesreturn_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
            }
            if ($fbl_is_vat_include == 1) {
                $swhere .= " and a.fbl_is_vat_include = " . $this->db->escape($fbl_is_vat_include);
            }
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }

        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.fin_salesreturn_id, a.fst_salesreturn_no as No_Retur,d.fst_inv_no as No_Inv, a.fdt_salesreturn_datetime as Retur_Date,d.fdt_inv_datetime as Inv_Date,
                a.fst_memo as Retur_Memo,a.fdc_dpp_amount as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_customer_id as fin_customer_id,c.fst_relation_name as Relation_Name, d.fin_sales_id as Sales_Id, e.fst_username as Sales_Name,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, f.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_unit as Unit,
                b.fdb_qty as Qty,b.fdb_qty_lpb as Qty_Terima, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount  
                FROM (SELECT * FROM trsalesreturn WHERE fst_active !='D') a LEFT OUTER JOIN trsalesreturnitems b 
                on a.fin_salesreturn_id = b.fin_salesreturn_id LEFT OUTER JOIN msrelations c
                on a.fin_customer_id = c.fin_relation_id LEFT OUTER JOIN trinvoice d
                on b.fin_inv_id = d.fin_inv_id LEFT OUTER JOIN users e
                on d.fin_sales_id = e.fin_user_id LEFT OUTER JOIN msitems f
                on b.fin_item_id = f.fin_item_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.fin_salesreturn_id,a.fst_salesreturn_no as No_Retur, a.fdt_salesreturn_datetime as Retur_Date,
                a.fst_memo as Retur_Memo,a.fdc_dpp_amount as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_customer_id as fin_customer_id,b.fst_relation_name as Relation_Name 
                FROM (SELECT * FROM trsalesreturn WHERE fst_active !='D') a LEFT OUTER JOIN msrelations b
                on a.fin_customer_id = b.fin_relation_id" . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.fin_salesreturn_id,d.fst_inv_no as No_Inv, a.fst_salesreturn_no as No_Retur, a.fdt_salesreturn_datetime as Retur_Date,
                a.fst_memo as Retur_Memo,a.fdc_dpp_amount as Dpp,a.fdc_ppn_amount as Ppn,a.fdc_subttl as fdc_subttl,a.fdc_total as fdc_total,a.fdc_disc_amount as Disc_Total,a.fst_curr_code as Mata_Uang,a.fdc_exchange_rate_idr as Rate_Idr,
                a.fin_customer_id as fin_customer_id,c.fst_relation_name as Relation_Name, d.fin_sales_id as Sales_Id, e.fst_username as Sales_Name,
                b.fin_rec_id as Rec_Id, b.fin_item_id as Item_Id, f.fst_item_code as Item_Code, b.fst_custom_item_name as Item_Name,b.fst_unit as Unit,
                b.fdb_qty as Qty,b.fdb_qty_lpb as Qty_Terima, b.fst_unit as Unit, b.fdc_price as Price,(b.fdc_price - b.fdc_disc_amount_per_item) as Price_Netto, b.fst_disc_item as Disc_Item, b.fdc_disc_amount_per_item as Disc_Amount,
                (b.fdb_qty * (b.fdc_price - b.fdc_disc_amount_per_item)) as Amount  
                FROM (SELECT * FROM trsalesreturn WHERE fst_active !='D') a LEFT OUTER JOIN trsalesreturnitems b 
                on a.fin_salesreturn_id = b.fin_salesreturn_id LEFT OUTER JOIN msrelations c
                on a.fin_customer_id = c.fin_relation_id LEFT OUTER JOIN trinvoice d
                on b.fin_inv_id = d.fin_inv_id LEFT OUTER JOIN users e
                on d.fin_sales_id = e.fin_user_id LEFT OUTER JOIN msitems f
                on b.fin_item_id = f.fin_item_id " . $swhere . $sorderby;
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
            'field' => 'fdt_salesreturn_datetime',
            'label' => 'Tgl Retur',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fdt_salesreturn_datetime2',
            'label' => 'Tgl Retur',
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
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Items_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'Kode Item', 'Nama Item'];

    public function queryComplete($data, $sorder_by="a.fst_item_code", $rptLayout="1") {
        
        $group_id = "";
        $type_id = "";
        $lob_id = "";
        $sales_id = "";
        $start_itemCode = "";
        $end_itemCode = "";
        if (isset($data['fin_item_group_id'])) { $group_id = $data['fin_item_group_id'];}
        if (isset($data['fin_item_type_id'])) { $type_id = $data['fin_item_type_id'];}
        if (isset($data['fst_linebusiness_id'])) { $lob_id = $data['fst_linebusiness_id'];}
        if (isset($data['fst_item_code'])) { $start_itemCode = $data['fst_item_code'];}
        if (isset($data['fst_item_code2'])) { $end_itemCode = $data['fst_item_code2'];}

        $swhere = "";
        $sorderby = "";
        if ($group_id != "") {
            $swhere .= " and a.fin_item_group_id = " . $group_id;
        }
        if ($type_id > "0" ) {
            $swhere .= " and a.fin_item_type_id = " . $type_id;
        }
        if ($lob_id > "0") {
            $swhere .= " and a.fst_linebusiness_id in (" . $lob_id .")";
        }
        if ($start_itemCode > "0") {
            $swhere .= " and a.fst_item_code >= '" . $start_itemCode . "'";            
        }
        if ($end_itemCode > "0") {
            $swhere .= " and a.fst_item_code <= '". $end_itemCode . "'";
        }
        //if (isset($start_itemCode)) {
        //    $swhere .= " and a.fst_item_code >= '" . $start_itemCode;            
        //}
        //if (isset($end_itemCode)) {
        //    $swhere .= " and a.fst_item_code <= '". $end_itemCode;
        //}
        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.*,b.fst_item_group_name as itemGroup,CONCAT(a.fst_linebusiness_id,'  -  ',c.fst_linebusiness_name)
                FROM msitems a LEFT JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
                LEFT JOIN mslinebusiness c ON a.fst_linebusiness_id = c.fin_linebusiness_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.*,b.fst_item_group_name as itemGroup,CONCAT(a.fst_linebusiness_id,'  -  ',c.fst_linebusiness_name),
                d.fst_unit,d.fbl_is_basic_unit,d.fdc_conv_to_basic_unit,d.fbl_is_selling,d.fbl_is_buying,d.fbl_is_production_output,d.fdc_price_list,d.fdc_het
                FROM msitems a 
                LEFT JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
                LEFT JOIN mslinebusiness c ON a.fst_linebusiness_id = c.fin_linebusiness_id
                LEFT JOIN msitemunitdetails d ON a.fin_item_id = d.fin_item_id " . $swhere . $sorderby;
                break;
            case "3":
                $ssql = "SELECT a.*,b.fst_item_group_name as itemGroup,CONCAT(a.fst_linebusiness_id,'  -  ',c.fst_linebusiness_name),
                d.fst_unit as unitBOM,e.fst_item_code as itemCodeBOM,e.fst_item_name as itemNameBOM
                FROM msitems a 
                LEFT JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
                LEFT JOIN mslinebusiness c ON a.fst_linebusiness_id = c.fin_linebusiness_id
                LEFT JOIN msitembomdetails d ON a.fin_item_id = d.fin_item_id 
                LEFT JOIN msitems e ON d.fin_item_id_bom = e.fin_item_id " . $swhere . $sorderby;
                break;
            case "4":
                $ssql = "SELECT a.*,b.fst_item_group_name as itemGroup,CONCAT(a.fst_linebusiness_id,'  -  ',c.fst_linebusiness_name),
                d.fst_unit,d.fdc_selling_price,e.fst_cust_pricing_group_name
                FROM msitems a 
                LEFT JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
                LEFT JOIN mslinebusiness c ON a.fst_linebusiness_id = c.fin_linebusiness_id
                LEFT JOIN msitemspecialpricinggroupdetails d ON a.fin_item_id = d.fin_item_id 
                LEFT JOIN mscustpricinggroups e ON d.fin_cust_pricing_group_id = e.fin_cust_pricing_group_id " . $swhere . $sorderby;
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
            'field' => 'fin_item_type_id',
            'label' => 'Item Type',
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
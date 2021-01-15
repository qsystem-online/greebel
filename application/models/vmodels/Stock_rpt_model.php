<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_rpt_model extends CI_Model {

	public $layout1Columns = ['No', 'Kode Item', 'Nama Item'];

	public function queryComplete($data, $rptLayout="1") {

		$group_id = "";
		$type_id = "";
		$warehouse_id = "";
		$start_itemCode = "";
		$end_itemCode = "";
		$start_date = "";
        $end_date = "";
		//if (isset($data['fin_item_group_id'])) { $group_id = $data['fin_item_group_id'];}
		//if (isset($data['fin_item_type_id'])) { $type_id = $data['fin_item_type_id'];}
		//if (isset($data['fst_linebusiness_id'])) { $lob_id = $data['fst_linebusiness_id'];}
		//if (isset($data['fst_item_code'])) { $start_itemCode = $data['fst_item_code'];}
		//if (isset($data['fst_item_code2'])) { $end_itemCode = $data['fst_item_code2'];}

        if (isset($data['fin_item_group_id'])) { $group_id = $data['fin_item_group_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_item_type_id'])) { $type_id = $data['fin_item_type_id'];}
		if (isset($data['fst_item_code'])) { $start_itemCode = $data['fst_item_code'];}
		if (isset($data['fst_item_code2'])) { $end_itemCode = $data['fst_item_code2'];}
		$start_date= $data["fdt_from"] == "" ? "2000-01-01" : $data["fdt_from"];
        $end_date= $data["fdt_to"] == "" ? "3000-01-01" : $data["fdt_to"];

        $swhere = "";
        $sorderby = "";
        if ($warehouse_id > "0" ) {
            $swhere .= " AND a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
        }
        if ($group_id != "") {
            $swhere .= " AND b.fin_item_group_id = " . $this->db->escape($group_id);
        }
        if (isset($start_date)) {
            $swhere .= " AND CAST(a.fdt_trx_datetime AS DATE) BETWEEN '"  . $start_date."'";            
		}
        if (isset($end_date)) {
            $swhere .= " AND '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
		
		
		switch($rptLayout) {
			case "1":
				$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name FROM trinventory a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
				$swhere ORDER BY b.fin_item_group_id,b.fin_item_id,a.fdt_trx_datetime";
				break;
			case "2":
				$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name FROM trinventory a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
				$swhere ORDER BY b.fin_item_group_id,b.fin_item_id,a.fdt_trx_datetime";
				break;
			case "3":
				$ssql = "SELECT a.fin_item_id,SUM(a.fdb_qty_in) AS fdb_qty_in,SUM(a.fdb_qty_out) AS fdb_qty_out,a.fst_basic_unit,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name 
				FROM trinventory a INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id 
				INNER JOIN msgroupitems c 
				ON b.fin_item_group_id = c.fin_item_group_id $swhere GROUP BY a.fin_item_id ORDER BY c.fst_item_group_name,b.fst_item_code";
				break;
			case "4":
				$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name FROM trinventory a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
				$swhere ORDER BY b.fin_item_group_id,b.fin_item_id,a.fdt_trx_datetime";
				break;
			case "5":
				$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name FROM trinventory a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
				$swhere ORDER BY b.fin_item_group_id,b.fin_item_id,a.fdt_trx_datetime";
				break;
			case "6":
				$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name FROM trinventory a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
				$swhere ORDER BY b.fin_item_group_id,b.fin_item_id,a.fdt_trx_datetime";
				break;
			default:
				break;
		}
		//return;		
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

	public function getLayoutKartuStock($data){
		//Item Group, item
		//filter by line bussiness, material 
		$fdtStart= $data["fdt_from"] == "" ? "2000-01-01" : $data["fdt_from"] ;
		$fdtEnd= $data["fdt_to"] == "" ? "3000-01-01" : $data["fdt_to"] ;
		$finWarehouseId = $data["fin_warehouse_id"];


		
		$ssql ="SELECT a.*,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name FROM trinventory a 
		INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
		INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
		WHERE CAST(a.fdt_trx_datetime AS DATE) BETWEEN ? AND ?
		AND fin_warehouse_id = ? 
		ORDER BY b.fin_item_group_id,b.fin_item_id,a.fdt_trx_datetime";

		$qr = $this->db->query($ssql,[$fdtStart,$fdtEnd,$finWarehouseId]);
		//echo $this->db->last_query();
        //die();
		return $qr->result();

	}

	public function getLayoutMutasiStockX($data){
		$group_id = "";
		$type_id = "";
		$warehouse_id = "";
		$start_itemCode = "";
		$end_itemCode = "";
		$start_date = "";
        $end_date = "";

        if (isset($data['fin_item_group_id'])) { $group_id = $data['fin_item_group_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_item_type_id'])) { $type_id = $data['fin_item_type_id'];}
		if (isset($data['fst_item_code'])) { $start_itemCode = $data['fst_item_code'];}
		if (isset($data['fst_item_code2'])) { $end_itemCode = $data['fst_item_code2'];}
		if (isset($data['fdt_from'])) { $start_date = $data['fdt_from'];}
        if (isset($data['fdt_to'])) { $end_date = $data['fdt_to'];}

        $swhere = "";
        $sorderby = "";
        if ($warehouse_id > "0" ) {
            $swhere .= " and a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
        }
        if ($group_id != "") {
            $swhere .= " and b.fin_item_group_id = " . $this->db->escape($group_id);
        }
        if (isset($start_date)) {
            $swhere .= " and a.fdt_trx_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'";            
        }
        if (isset($end_date)) {
            $swhere .= " and a.fdt_trx_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
        }

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }

		$ssql = "SELECT a.fin_item_id,SUM(a.fdb_qty_in) AS fdb_qty_in,SUM(a.fdb_qty_out) AS fdb_qty_out,a.fst_basic_unit,b.fst_item_code,b.fst_item_name,b.fin_item_group_id,c.fst_item_group_name 
		FROM trinventory a INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id 
		INNER JOIN msgroupitems c 
		ON b.fin_item_group_id = c.fin_item_group_id $swhere GROUP BY a.fin_item_id ORDER BY c.fst_item_group_name,b.fst_item_code";
		$query = $this->db->query($ssql);
        //echo $this->db->last_query();
        //die();
        return $query->result();

	}
}
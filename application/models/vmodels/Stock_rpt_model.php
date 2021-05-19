<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_rpt_model extends CI_Model {

	public $layout1Columns = ['No', 'Kode Item', 'Nama Item'];

	public function queryComplete($data, $rptLayout="1") {

		$group_id = "";
		$type_id = "";
		$warehouse_id = "";
		$item_id = "";
		$start_date = "";
        $end_date = "";
		$end_date6 = "";
		//if (isset($data['fin_item_group_id'])) { $group_id = $data['fin_item_group_id'];}
		//if (isset($data['fin_item_type_id'])) { $type_id = $data['fin_item_type_id'];}
		//if (isset($data['fst_linebusiness_id'])) { $lob_id = $data['fst_linebusiness_id'];}
		//if (isset($data['fst_item_code'])) { $start_itemCode = $data['fst_item_code'];}
		//if (isset($data['fst_item_code2'])) { $end_itemCode = $data['fst_item_code2'];}

        if (isset($data['fin_item_group_id'])) { $group_id = $data['fin_item_group_id'];}
        if (isset($data['fin_warehouse_id'])) { $warehouse_id = $data['fin_warehouse_id'];}
        if (isset($data['fin_item_type_id'])) { $type_id = $data['fin_item_type_id'];}
		if (isset($data['fin_item_id'])) { $item_id = $data['fin_item_id'];}
		$start_date= $data["fdt_from"] == "" ? "1900-01-01" : $data["fdt_from"];
		$end_date= $data["fdt_to"] == "" ? "3000-01-01" : $data["fdt_to"];
        $end_date6= $data["fdt_to"] == "" ? "3000-01-01" : date('Y-m-d 23:59:59', strtotime($data["fdt_to"]));

        $swhere = "";
		$sorderby = "";
		if ($rptLayout == "1"){
			if ($warehouse_id > "0" ) {
				$swhere .= " AND a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
			}
			if ($group_id != "") {
				$swhere .= " AND b.fin_item_group_id = " . $this->db->escape($group_id);
			}
			if ($type_id > "0" ) {
				$swhere .= " AND b.fin_item_type_id = " . $this->db->escape($type_id);
			}
			if ($item_id > "0" ) {
				$swhere .= " AND a.fin_item_id = " . $this->db->escape($item_id);
			}
			if (isset($start_date)) {
				$swhere .= " AND CAST(a.fdt_trx_datetime AS DATE) BETWEEN '"  . $start_date."'";            
			}
			if (isset($end_date)) {
				$swhere .= " AND '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
			}
		}
		if ($rptLayout == "2"){
			if ($warehouse_id > "0" ) {
				$swhere .= " AND a.fin_warehouse_id = " . $this->db->escape($warehouse_id);
			}
			if ($group_id != "") {
				$swhere .= " AND b.fin_item_group_id = " . $this->db->escape($group_id);
			}
			if ($type_id > "0" ) {
				$swhere .= " AND b.fin_item_type_id = " . $this->db->escape($type_id);
			}
			if ($item_id > "0" ) {
				$swhere .= " AND a.fin_item_id = " . $this->db->escape($item_id);
			}
			if (isset($start_date)) {
				$swhere .= " AND CAST(a.fdt_trx_datetime AS DATE) BETWEEN '"  . $start_date."'";            
			}
			if (isset($end_date)) {
				$swhere .= " AND '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
			}
		}
		if ($rptLayout == "3" || $rptLayout == "4"){
			if ($group_id != "") {
				$swhere .= " AND a.fin_item_group_id = " . $this->db->escape($group_id);
			}
			if ($type_id > "0" ) {
				$swhere .= " AND a.fin_item_type_id = " . $this->db->escape($type_id);
			}
			if ($item_id > "0" ) {
				$swhere .= " AND a.fin_item_id = " . $this->db->escape($item_id);
			}
		}

		if ($rptLayout =="5"){
			if ($group_id != "") {
				$swhere .= " AND a.fin_item_group_id = " . $this->db->escape($group_id);
			}
			if ($type_id > "0" ) {
				$swhere .= " AND a.fin_item_type_id = " . $this->db->escape($type_id);
			}
			if ($item_id > "0" ) {
				$swhere .= " AND a.fin_item_id = " . $this->db->escape($item_id);
			}
		}

		if ($rptLayout =="6"){
			if ($group_id != "") {
				$swhere .= " AND a.fin_item_group_id = " . $this->db->escape($group_id);
			}
			if ($type_id > "0" ) {
				$swhere .= " AND a.fin_item_type_id = " . $this->db->escape($type_id);
			}
			if ($item_id > "0" ) {
				$swhere .= " AND a.fin_item_id = " . $this->db->escape($item_id);
			}
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
				$ssql = "SELECT a.fin_item_id,IFNULL(b.fdb_qty_in,0) AS fdb_qty_in,IFNULL(b.fdb_qty_out,0) AS fdb_qty_out,a.fst_unit AS fst_basic_unit,a.fdc_price_list,
				a.fst_item_code,a.fst_item_name,IFNULL(c.start_balance,0) AS start_balance,a.fin_item_group_id,d.fst_item_group_name
				FROM (SELECT a.*,b.fst_unit,b.fdc_price_list FROM msitems a INNER JOIN msitemunitdetails b ON a.fin_item_id = b.fin_item_id WHERE a.fst_active !='D' AND a.fbl_stock = 1 AND b.fbl_is_basic_unit =1) a 
				LEFT OUTER JOIN (
					SELECT a.fin_item_id,SUM(a.fdb_qty_in) AS fdb_qty_in,SUM(a.fdb_qty_out) AS fdb_qty_out,a.fin_warehouse_id,a.fdt_trx_datetime FROM trinventory a
					WHERE a.fin_warehouse_id = '".$warehouse_id."' AND a.fdt_trx_datetime >= '".$start_date."' AND a.fdt_trx_datetime <= '".$end_date6."'
					GROUP BY a.fin_item_id
				) b ON a.fin_item_id = b.fin_item_id 
				LEFT OUTER JOIN (
					SELECT a.fin_item_id,b.fdb_qty_balance_after AS start_balance  FROM (
						SELECT fin_item_id,MAX(fin_rec_id) AS max_rec_id FROM trinventory 
						WHERE fin_warehouse_id = '".$warehouse_id."' AND fdt_trx_datetime < '".$start_date."' GROUP BY fin_item_id
					) a
					INNER JOIN trinventory b ON a.max_rec_id = b.fin_rec_id 
				) c ON a.fin_item_id = c.fin_item_id 
				INNER JOIN msgroupitems d ON a.fin_item_group_id = d.fin_item_group_id 
				$swhere GROUP BY a.fin_item_id ORDER BY d.fst_item_group_name,a.fst_item_code";
				break;
			case "4":
				$ssql = "SELECT a.fin_item_id,IFNULL(b.fdb_qty_in,0) AS fdb_qty_in,IFNULL(b.fdb_qty_out,0) AS fdb_qty_out,a.fst_unit AS fst_basic_unit,a.fdc_price_list,
				a.fst_item_code,a.fst_item_name,IFNULL(c.start_balance,0) AS start_balance,a.fin_item_group_id,d.fst_item_group_name
				FROM (SELECT a.*,b.fst_unit,b.fdc_price_list FROM msitems a INNER JOIN msitemunitdetails b ON a.fin_item_id = b.fin_item_id WHERE a.fst_active !='D' AND a.fbl_stock = 1 AND b.fbl_is_basic_unit =1) a 
				LEFT OUTER JOIN (
					SELECT a.fin_item_id,SUM(a.fdb_qty_in) AS fdb_qty_in,SUM(a.fdb_qty_out) AS fdb_qty_out,a.fin_warehouse_id,a.fdt_trx_datetime FROM trinventory a
					WHERE a.fin_warehouse_id = '".$warehouse_id."' AND a.fdt_trx_datetime >= '".$start_date."' AND a.fdt_trx_datetime <= '".$end_date6."'
					GROUP BY a.fin_item_id
				) b ON a.fin_item_id = b.fin_item_id 
				LEFT OUTER JOIN (
					SELECT a.fin_item_id,b.fdb_qty_balance_after AS start_balance  FROM (
						SELECT fin_item_id,MAX(fin_rec_id) AS max_rec_id FROM trinventory 
						WHERE fin_warehouse_id = '".$warehouse_id."' AND fdt_trx_datetime < '".$start_date."' GROUP BY fin_item_id
					) a
					INNER JOIN trinventory b ON a.max_rec_id = b.fin_rec_id 
				) c ON a.fin_item_id = c.fin_item_id 
				INNER JOIN msgroupitems d ON a.fin_item_group_id = d.fin_item_group_id 
				$swhere GROUP BY a.fin_item_id ORDER BY a.fst_item_code";
				break;
			case "5":
				$ssql = "SELECT a.fin_item_id,IFNULL(b.fdb_qty_in,0) AS fdb_qty_in,IFNULL(b.fdb_qty_out,0) AS fdb_qty_out,b.fdc_avg_cost_start,c.fdc_avg_cost_last ,a.fst_unit AS fst_basic_unit,
				a.fst_item_code,a.fst_item_name,IFNULL(c.start_balance,0) AS start_balance,a.fin_item_group_id,d.fst_item_group_name
				FROM (SELECT a.*,b.fst_unit FROM msitems a INNER JOIN msitemunitdetails b ON a.fin_item_id = b.fin_item_id WHERE a.fst_active !='D' AND a.fbl_stock = 1 AND b.fbl_is_basic_unit =1) a 
				LEFT JOIN (
					SELECT a.fin_item_id,SUM(a.fdb_qty_in) AS fdb_qty_in,SUM(a.fdb_qty_out) AS fdb_qty_out,a.fin_warehouse_id,a.fdc_avg_cost AS fdc_avg_cost_start,a.fdt_trx_datetime FROM trinventory a
					WHERE a.fin_warehouse_id = '".$warehouse_id."' AND a.fdt_trx_datetime >= '".$start_date."' AND a.fdt_trx_datetime <= '".$end_date6."'
					GROUP BY a.fin_item_id
				) b ON a.fin_item_id = b.fin_item_id 
				LEFT JOIN (
					SELECT a.fin_item_id,b.fdb_qty_balance_after AS start_balance,b.fdc_avg_cost AS fdc_avg_cost_last  FROM (
						SELECT fin_item_id,MAX(fin_rec_id) AS max_rec_id FROM trinventory 
						WHERE fin_warehouse_id = '".$warehouse_id."' AND fdt_trx_datetime < '".$start_date."' GROUP BY fin_item_id
					) a
					INNER JOIN trinventory b ON a.max_rec_id = b.fin_rec_id 
				) c ON a.fin_item_id = c.fin_item_id 
				INNER JOIN msgroupitems d ON a.fin_item_group_id = d.fin_item_group_id 
				$swhere GROUP BY a.fin_item_id ORDER BY a.fst_item_code";
				break;
			case "6":
				$ssql = "SELECT a.fin_item_id,a.fst_unit AS fst_basic_unit,
				a.fst_item_code,a.fst_item_name,b.fin_warehouse_id,IFNULL(b.end_balance,0) AS end_balance
				FROM (SELECT a.*,b.fst_unit FROM msitems a INNER JOIN msitemunitdetails b ON a.fin_item_id = b.fin_item_id WHERE a.fst_active !='D' AND a.fbl_stock = 1 AND b.fbl_is_basic_unit =1) a 
				LEFT JOIN (
					SELECT a.fin_item_id,a.fin_warehouse_id,b.fdb_qty_balance_after AS end_balance  FROM (
						SELECT fin_item_id,fin_warehouse_id,MAX(fin_rec_id) AS max_rec_id FROM trinventory 
						WHERE fdt_trx_datetime <= '".$end_date6."' GROUP BY fin_item_id,fin_warehouse_id
					) a
					INNER JOIN trinventory b ON a.max_rec_id = b.fin_rec_id 
				) b ON a.fin_item_id = b.fin_item_id 
				$swhere  ORDER BY a.fst_item_code,b.fin_warehouse_id";
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
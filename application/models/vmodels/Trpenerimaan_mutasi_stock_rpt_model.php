<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trpenerimaan_mutasi_stock_rpt_model extends CI_Model {

	public $layout1Columns = ['No', 'No PAG', 'Gudang Asal', 'Gudang Tujuan'];

	public function queryComplete($data, $sorder_by="f.fin_mag_confirm_id", $rptLayout="1") {

		$fbl_date_option = "";
		$branch_id = "";
		$type_id = "";
		$warehouse_from = "";
		$warehouse_to = "";
		$start_date = "";
        $end_date = "";

		if (isset($data['fbl_date_option'])) { $fbl_date_option = $data['fbl_date_option'];}
		if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
		if (isset($data['fin_from_warehouse_id'])) { $warehouse_from = $data['fin_from_warehouse_id'];}
		if (isset($data['fin_to_warehouse_id'])) { $warehouse_to = $data['fin_to_warehouse_id'];}
        if (isset($data['fin_type_id'])) { $type_id = $data['fin_type_id'];}
        if (isset($data['fdt_datetime'])) { $start_date = $data['fdt_datetime'];}
        if (isset($data['fdt_datetime2'])) { $end_date = $data['fdt_datetime2'];}

        $swhere = "";
		$sorderby = "";
		if ($rptLayout == "1"){
			if ($branch_id > "0" ) {
				$swhere .= " AND f.fin_branch_id = " . $this->db->escape($branch_id);
			}
			if ($warehouse_from > "0" ) {
				$swhere .= " AND c.fin_from_warehouse_id = " . $this->db->escape($warehouse_from);
			}
			if ($warehouse_to > "0" ) {
				$swhere .= " AND c.fin_to_warehouse_id = " . $this->db->escape($warehouse_to);
			}
			if ($type_id > "0" ) {
				switch($type_id){
					case "1":
						$swhere .= " AND c.fbl_mag_production =1 AND c.fin_wo_id IS NOT NULL ";
						break;
					case "2":
						$swhere .= " AND c.fbl_mag_production =1 AND c.fin_wo_id IS NULL ";
						break;
					case "3":
						$swhere .= " AND c.fbl_mag_production =0";
						break;

				}
			}
			if (isset($start_date)) {
				if($fbl_date_option == "0"){
					$swhere .= " AND f.fdt_mag_confirm_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'"; 
				}else{
					$swhere .= " AND f.fdt_insert_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'"; 
				}
                           
            }
            if (isset($end_date)) {
				if($fbl_date_option == "0"){
					$swhere .= " AND f.fdt_mag_confirm_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
				}else{
					$swhere .= " AND f.fdt_insert_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
				}
                
            }
            $swhere .= " AND IFNULL(b.fdb_qty_confirm,0) = b.fdb_qty";
		}
		if ($rptLayout == "2"){
			if ($branch_id > "0" ) {
				$swhere .= " AND a.fin_branch_id = " . $this->db->escape($branch_id);
			}
			if ($warehouse_from > "0" ) {
				$swhere .= " AND b.fin_from_warehouse_id = " . $this->db->escape($warehouse_from);
			}
			if ($warehouse_to > "0" ) {
				$swhere .= " AND b.fin_to_warehouse_id = " . $this->db->escape($warehouse_to);
			}
			if ($type_id > "0" ) {
				switch($type_id){
					case "1":
						$swhere .= " AND b.fbl_mag_production =1 AND b.fin_wo_id IS NOT NULL ";
						break;
					case "2":
						$swhere .= " AND b.fbl_mag_production =1 AND b.fin_wo_id IS NULL ";
						break;
					case "3":
						$swhere .= " AND b.fbl_mag_production =0";
						break;

				}
			}
			if (isset($start_date)) {
				if($fbl_date_option == "0"){
					$swhere .= " AND a.fdt_mag_confirm_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'"; 
				}else{
					$swhere .= " AND a.fdt_insert_datetime >= '" . date('Y-m-d', strtotime($start_date)) . "'"; 
				}
                           
            }
            if (isset($end_date)) {
				if($fbl_date_option == "0"){
					$swhere .= " AND a.fdt_mag_confirm_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
				}else{
					$swhere .= " AND a.fdt_insert_datetime <= '". date('Y-m-d 23:59:59', strtotime($end_date)). "'";
				}
                
            }
		}

        if ($swhere != "") {
            $swhere = " WHERE " . substr($swhere, 5);
        }
		
		switch($rptLayout) {
			case "1":
				$ssql = "SELECT a.fin_item_id,IFNULL(b.fdb_qty,0) AS Qty_MAG,IFNULL(b.fdb_qty_confirm,0) AS Qty_confirm,b.fst_unit AS Unit,b.fst_batch_number,b.fst_serial_number_list,f.fst_mag_confirm_no AS No_PAG,f.fdt_mag_confirm_datetime AS PAG_Date,f.fst_memo AS Memo_PAG,
				a.fst_item_code AS Item_Code,a.fst_item_name AS Item_Name,f.fin_mag_confirm_id,f.fin_branch_id AS Branch_PAG,c.fin_mag_id,c.fst_mag_no AS No_MAG,c.fdt_mag_datetime AS MAG_Date,c.fin_wo_id,d.fst_warehouse_name AS warehouse_from,e.fst_warehouse_name AS warehouse_to
				FROM msitems  a 
				RIGHT OUTER JOIN trmagitems b ON a.fin_item_id = b.fin_item_id
				RIGHT OUTER JOIN trmag c ON b.fin_mag_id = c.fin_mag_id 
				LEFT OUTER JOIN mswarehouse d ON c.fin_from_warehouse_id = d.fin_warehouse_id 
				LEFT OUTER JOIN mswarehouse e ON c.fin_to_warehouse_id = e.fin_warehouse_id
                LEFT OUTER JOIN trmagconfirm f ON c.fin_mag_id = f.fin_mag_id " . $swhere . $sorderby;
				break;
			case "2":
				$ssql = "SELECT a.fin_mag_confirm_id,a.fin_branch_id,a.fst_mag_confirm_no AS No_PAG,a.fdt_mag_confirm_datetime AS PAG_Date,b.fst_mag_no AS No_MAG,b.fdt_mag_datetime AS MAG_Date,a.fst_memo AS Memo_PAG,c.fst_warehouse_name AS warehouse_from,d.fst_warehouse_name AS warehouse_to
				FROM trmagconfirm a
                RIGHT OUTER JOIN trmag b ON a.fin_mag_id = b.fin_mag_id 
				LEFT OUTER JOIN mswarehouse c ON b.fin_from_warehouse_id = c.fin_warehouse_id 
				LEFT OUTER JOIN mswarehouse d ON b.fin_to_warehouse_id = d.fin_warehouse_id $swhere";
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
			'field' => 'fdt_datetime',
			'label' => 'Start Date',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong'
			)
		];

		$rules[] = [
			'field' => 'fdt_datetime2',
			'label' => 'End Date',
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
<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Datatables {

	public $tableName = "";
	public $groupBy = "";
	public $countTableName = "";

	public $selectFields = "";
	public $searchFields = [];
	public $activeCondition = "fst_active != 'D'";
	private $CI;
	private $db;


	

	public function __construct() {
		$this->CI = & get_instance();
		$this->db = $this->CI->load->database('default', TRUE);

	}


	public function setTableName($tableName){
		$this->tableName = $tableName;
	}

	public function setGroupBy($groupBy){
		$this->groupBy = $groupBy;
	}

	public function setCountTableName($tableName){
		$this->countTableName = $tableName;
	}
	public function setSelectFields($selectFields){
		$this->selectFields = $selectFields;
	}

	public function setSearchFields($searchFields){
		$this->searchFields = $searchFields;
	}

	public function setDatabase($db){
		$this->db = $this->CI->load->database($db, TRUE);
	}



	public function getData(){	
		$offset = (int) $this->CI->input->get_post("start");
		$limit = (int) $this->CI->input->get_post("length");

		$orders = $this->CI->input->get_post("order");
		$columns = $this->CI->input->get_post("columns");


		$this->countTableName = $this->countTableName == "" ? $this->tableName : $this->countTableName;

		//Get Total Row 
		$ssql = "select count(*) as ttl_records from " . $this->countTableName . " where " . $this->activeCondition ;
		$qr = $this->db->query($ssql,[]);
		//echo $this->db->last_query();
		//die();
		
		$rw = $qr->row();
		
		$totalRows = $rw->ttl_records;

		

		$search = $this->CI->input->get_post("search");
		$strSearch = $search["value"];
		$strWhere = " where " . $this->activeCondition;
		$params = [];
		$totalFiltered = $rw->ttl_records;

		if($strSearch != "" and  $strSearch != NULL) {
			//Get Total Row filter
			$ssql = "select count(*) as ttl_filtered from " . $this->tableName ;
			$strWhere .= " AND ";
			foreach ($this->searchFields as $searchField) {				
				$strWhere .= " $searchField like ? OR ";
				$params[] = "%" .$strSearch ."%";
			}

			$strWhere = rtrim(trim($strWhere),"OR");
			$qr = $this->db->query($ssql .' '. $strWhere,$params);
			//echo $this->db->last_query();
			//die();
		
			$rw = $qr->row();
			$totalFiltered = $rw->ttl_filtered;
		}


		//Get Data
		// Prepare Order String		
		$strOrder = "";
		
		foreach ($orders as $order) {
			$field = $columns[$order["column"]]["data"]; // data / name

			$strOrder .= $field . ' ' . $order["dir"] . ', ' ;	
		}
		if ($strOrder != ""){
			$strOrder = " order by " .$strOrder;
		}
		$strOrder = rtrim(trim($strOrder),",");

		$params[] = $limit;
		$params[] = $offset;

		// group by
		$strGroupBy = "";
		if ($this->groupBy != ""){
			$strGroupBy = " group by " . $this->groupBy ;
		}
		
		$ssql = "select " . $this->selectFields . " from " . $this->tableName . " ". $strWhere ." ". $strGroupBy ." " . $strOrder . " limit ? offset ?" ;

		$qr = $this->db->query($ssql,$params);
		//echo $this->db->last_query();
		//die();
		
		$rs = $qr->result_array();


		//print_r($this->CI->input->get_post("draw"));
		$datasource = [
			"draw"=>  $this->CI->input->get_post("draw"),
			"recordsTotal"=> $totalRows,
			"recordsFiltered"=> $totalFiltered,
			"data"=>array_values($rs),
  		];
		return $datasource;
	}
}
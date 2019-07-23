<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public $rules;
	public $tableName = "tableName";
	public $pkey = "fin_id";


	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data)
	{

		if (!isset($data["fdt_insert_datetime"])) {
			$data["fdt_insert_datetime"] = date("Y-m-d H:i:s");
			$data["fin_insert_id"] = $this->aauth->get_user_id();
		}

		$data = $this->cleanupData($data);		
		$this->db->insert($this->tableName, $data);
		$insertId = $this->db->insert_id();
		$error = $this->db->error();
		if ($error["code"] != 0) {
			throw new Exception("Database Error !!!", EXCEPTION_DB);
			//echo "TEST throw, never call statement";
		}

		return $insertId;
	}

	public function update($data)
	{
		//if (!isset($data["fdt_update_datetime"])) {
		$data["fdt_update_datetime"] = date("Y-m-d H:i:s");
		$data["fin_update_id"] = $this->aauth->get_user_id();
		//}
		$data = $this->cleanupData($data);
		$this->db->where($this->pkey, $data[$this->pkey]);
		$this->db->update($this->tableName, $data);
	}

	public function delete($key, $softdelete = TRUE)
	{
		if ($softdelete) {
			$this->db->where($this->pkey, $key);
			$this->db->update($this->tableName, ["fst_active" => "D"]);
		} else {
			$this->db->where($this->pkey, $key);
			$this->db->delete($this->tableName);
		}
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function getRules()
	{
		return $this->rules;
	}

	private function cleanupData($data){
		//Hanya data yang terdapat di column yang akan dihasilkan
		$arrColumns = $this->getColums();
		$arrResult =[];
		foreach($data as $k => $v){
			if (array_search($k,$arrColumns) !== false){
				$arrResult[$k]=$v;
			}
		}
		return $arrResult;
	}

	public function getColums(){
		$ssql = "SHOW COLUMNS FROM " .$this->tableName;
		$qr = $this->db->query($ssql,[]);
		$rs = $qr->result();
		$columns = [];

		foreach($rs as $rw){
			$columns[] = $rw->Field;
		}
		return $columns;
	}
}

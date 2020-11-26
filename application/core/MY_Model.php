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
		$this->throwIfDBError();
		return $insertId;
	}

	public function update($data){
		//if (!isset($data["fdt_update_datetime"])) {
		$data["fdt_update_datetime"] = date("Y-m-d H:i:s");
		$data["fin_update_id"] = $this->aauth->get_user_id();
		//}
		/*
		if ( isset($data["fin_user_id_request_by"]) ){
			$finUserIdReqEdit = $data["fin_user_id_request_by"];
			$fstNotesEdit =  isset($data["fst_edit_notes"]) ? $data["fst_edit_notes"] : null;
			$dataLog = [
				"fst_mode"=>"EDIT",
				"fst_module"=>$this->router->fetch_class(),
				"fst_table_name"=>$this->tableName,
				"fst_trans_id"=> $data[$this->pkey],
				"fst_notes"=>$fstNotesEdit, 
				"fin_request_by_id"=>$finUserIdReqEdit, 
				"fin_insert_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(),
				"fdt_insert_datetime"=>date("Y-m-d H:i:s"), 
				"fin_update_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(), 
				"fdt_update_datetime"=>date("Y-m-d H:i:s")
			];
			$this->db->insert("log_table_transaction",$dataLog);
		}
		*/
		if($this->input->post("fin_user_id_request_by") != null){
			$finUserIdReqEdit = $this->input->post("fin_user_id_request_by");
			$fstNotesEdit =  $this->input->post("fst_edit_notes");
			$dataLog = [
				"fst_mode"=>"EDIT",
				"fst_module"=>$this->router->fetch_class(),
				"fst_table_name"=>$this->tableName,
				"fst_trans_id"=> $data[$this->pkey],
				"fst_notes"=>$fstNotesEdit, 
				"fin_request_by_id"=>$finUserIdReqEdit, 
				"fin_insert_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(),
				"fdt_insert_datetime"=>date("Y-m-d H:i:s"), 
				"fin_update_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(), 
				"fdt_update_datetime"=>date("Y-m-d H:i:s")
			];
			$this->db->insert("log_table_transaction",$dataLog);
		}
		
		$data = $this->cleanupData($data);
		$this->db->where($this->pkey, $data[$this->pkey]);
		$this->db->update($this->tableName, $data);
		$this->throwIfDBError();
	}

	public function delete($key, $softdelete = TRUE,$data=null){
		/*
		if ($data !=null){

			$finUserIdReqEdit = $data["fin_user_id_request_by"];
			$fstNotesEdit =  isset($data["fst_edit_notes"]) ? $data["fst_edit_notes"] : null;
			$dataLog = [
				"fst_mode"=>"DELETE",
				"fst_module"=>$this->router->fetch_class(),
				"fst_table_name"=>$this->tableName,
				"fst_trans_id"=> $key,
				"fst_notes"=>$fstNotesEdit, 
				"fin_request_by_id"=>$finUserIdReqEdit, 
				"fin_insert_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(),
				"fdt_insert_datetime"=>date("Y-m-d H:i:s"), 
				"fin_update_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(), 
				"fdt_update_datetime"=>date("Y-m-d H:i:s")
			];
			$this->db->insert("log_table_transaction",$dataLog);
		}
		*/

		if($this->input->post("fin_user_id_request_by") != null){
			$finUserIdReqEdit = $this->input->post("fin_user_id_request_by");
			$fstNotesEdit =  $this->input->post("fst_edit_notes");
			$dataLog = [
				"fst_mode"=>"DELETE",
				"fst_module"=>$this->router->fetch_class(),
				"fst_table_name"=>$this->tableName,
				"fst_trans_id"=> $key,
				"fst_notes"=>$fstNotesEdit, 
				"fin_request_by_id"=>$finUserIdReqEdit, 
				"fin_insert_id"=>$this->aauth->get_user_id(),
				"fdt_insert_datetime"=>date("Y-m-d H:i:s"), 
				"fin_update_id"=>$this->aauth->get_user_id(), 
				"fdt_update_datetime"=>date("Y-m-d H:i:s")
			];
			$this->db->insert("log_table_transaction",$dataLog);
		}

		if ($softdelete) {
			$this->db->where($this->pkey, $key);
			$this->db->update($this->tableName, ["fst_active" => "D"]);
		} else {
			$this->db->where($this->pkey, $key);
			$this->db->delete($this->tableName);
		}
		$this->throwIfDBError();
	}

	public function getSimpleDataById($id,$activeStatus ='A'){
		$ssql = "SELECT * FROM $this->tableName where $this->pkey = ? and fst_active = '$activeStatus'";
		$qr = $this->db->query($ssql,[$id]);
		return $qr->row();
	}

	public function getList($active='A'){
		if ($active == 'ALL'){
			$ssql = "SELECT * FROM $this->tableName";
		}else{
			$ssql = "SELECT * FROM $this->tableName where fst_active = '$active'";
		}		
		$qr = $this->db->query($ssql,[]);
		return $qr->result();
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

	public function getDBErrors(){
		$dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			return [
				"status"=>"DB_FAILED",
				"message"=> $dbError["code"] . " - " . $dbError["message"]
			];
		}
		return [
			"status"=>"SUCCESS",
			"message"=> ""
		];
	}

	public function getValue($id,$column,$where=""){
		if ($where == ""){
			$where = " WHERE $this->pkey = ?";
		}else{
			$where = " WHERE $where";
		}
		$ssql = "SELECT $column FROM $this->tableName $where";
		$qr = $this->db->query($ssql,$id);
		$rw = $qr->row();
		return $rw->$column;

	}

	public function throwIfDBError($db = null){
		if ($db==null){
			$dbError  = $this->db->error();
		}else{
			$dbError  = $db->error();
		}

		if ($dbError["code"] != 0){	

			$data =[];
			if (ENVIRONMENT !== 'production'){
				$data["last_query"] = $this->db->last_query();
			}			
			throw new CustomException($dbError["message"],$dbError["code"],"DB_FAILED",$data);	
		}		
	}	
}

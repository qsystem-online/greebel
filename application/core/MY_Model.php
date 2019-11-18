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
		
		$data = $this->cleanupData($data);
		$this->db->where($this->pkey, $data[$this->pkey]);
		$this->db->update($this->tableName, $data);
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
				"fin_insert_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(),
				"fdt_insert_datetime"=>date("Y-m-d H:i:s"), 
				"fin_update_id"=>$data["fin_insert_id"] = $this->aauth->get_user_id(), 
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
		$error = $this->db->error();
		if ($error["code"] != 0) {
			throw new Exception("Delete Database Error !!!", EXCEPTION_DB);
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

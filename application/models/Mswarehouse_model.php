<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mswarehouse_model extends MY_Model
{
	public $tableName = "mswarehouse";
	public $pkey = "fin_warehouse_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getDataById($fin_warehouse_id)
	{
		$ssql = "select a.*,b.fst_branch_name from " . $this->tableName . " a 
		left join msbranches b on a.fin_branch_id = b.fin_branch_id 
		where fin_warehouse_id = ?";
		$qr = $this->db->query($ssql, [$fin_warehouse_id]);
		$rwWarehouse = $qr->row();
		$data = [
			"warehouse" => $rwWarehouse
		];
		return $data;
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_warehouse_name',
			'label' => 'Warehouse Name',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong'
			)
		];

		$rules[] = [
			'field' => 'fbl_is_main',
			'label' => 'Main warehouse',
			'rules' => 'is_unique[mswarehouse.fin_warehouse_id.fbl_is_main.' . $id . ']',
			'errors' => array(
				'is_unique' => '%s is more one'
			)
		];

		return $rules;
	}

	// Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
	/*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
	}*/

	public function getAllList()
	{
		$ssql = "select fin_warehouse_id,fst_warehouse_name from " . $this->tableName . " where fbl_is_buffer = 0 and fst_active = 'A'";
		$qr = $this->db->query($ssql, []);
		var_dump($this->db->error());
		$rs = $qr->result();
		return $rs;
	}

	public function get_Branch()
	{
		$query = $this->db->get('msbranches');
		return $query->result_array();
	}


	public function getSelect2(){

		$branchId = $this->aauth->get_active_branch_id();
		$ssql = "select fin_warehouse_id as id,fst_warehouse_name as text from " . $this->tableName . " where fbl_is_buffer = 0 and  fst_active = 'A' and fin_branch_id = ?";
		$qr = $this->db->query($ssql, [$branchId]);
		$rs = $qr->result();
		return $rs;
	}

	
	public function getWarehouseList($branchId = null){
		if ($branchId == null){
			$branchId = $this->aauth->get_active_branch_id();
		}
		
		if ($branchId == "ALL"){
			$ssql = "select * from " . $this->tableName . " where fbl_is_buffer = 0 and fst_active = 'A'";
			$qr = $this->db->query($ssql,[]);
		}else{
			$ssql = "select * from " . $this->tableName . " where fbl_is_buffer = 0 and fst_active = 'A' and fin_branch_id = ?";
			$qr = $this->db->query($ssql, [$branchId]);
		}		
		$rs = $qr->result();
		return $rs;
	}

	public function getNonLogisticWarehouseList(){
		$branchId = $this->aauth->get_active_branch_id();
		$ssql = "select * from " . $this->tableName . " where fbl_is_buffer = 0 and fbl_logistic = false and fst_active = 'A' and fin_branch_id = ? ";
		$qr = $this->db->query($ssql, [$branchId]);
		$rs = $qr->result();
		return $rs;
	}

	public function getLogisticWarehouseList(){
		//Gudang ini digunakan untuk setiap pembelian barang melalui proses PR
		$branchId = $this->aauth->get_active_branch_id();
		$ssql = "select * from " . $this->tableName . " where fbl_is_buffer = 0 and fbl_logistic = true and fst_active = 'A' and fin_branch_id = ? ";
		$qr = $this->db->query($ssql, [$branchId]);
		$rs = $qr->result();
		return $rs;
	}

	public function getBufferWarehouseId($finBranchId=""){
		if ($finBranchId == ""){
			$finBranchId = $this->aauth->get_active_branch_id();
		}
		$ssql = "SELECT * FROM mswarehouse where fin_branch_id = ? and fbl_is_buffer = true";
		$qr = $this->db->query($ssql,[$finBranchId]);
		$rw =  $qr->row();
		if ($rw != null){
			return $rw->fin_warehouse_id;
		}else{
			//Create buffer stock for this branch
			$data = [
				"fin_branch_id"=>$finBranchId,
				"fst_warehouse_name"=>"Buffer Warehouse",
				"fbl_is_external"=>0,
				"fbl_is_main"=>0,
				"fbl_logistic"=>0,
				"fbl_is_buffer"=>1,
				"fst_active"=>"A",            
			];
			$insertId = $this->mswarehouse_model->insert($data);
			return $insertId;
		}
	}

	
}

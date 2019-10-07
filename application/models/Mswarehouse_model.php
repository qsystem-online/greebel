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
        $ssql = "select fin_warehouse_id,fin_warehouse_name from " . $this->tableName . " where fst_active = 'A'";
        $qr = $this->db->query($ssql, []);
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
        $ssql = "select fin_warehouse_id as id,fst_warehouse_name as text from " . $this->tableName . " where fst_active = 'A' and fin_branch_id = ?";
        $qr = $this->db->query($ssql, [$branchId]);
        $rs = $qr->result();
        return $rs;
    }

    
    public function getWarehouseList(){

        $branchId = $this->aauth->get_active_branch_id();
        $ssql = "select * from " . $this->tableName . " where fst_active = 'A' and fin_branch_id = ?";
        $qr = $this->db->query($ssql, [$branchId]);
        $rs = $qr->result();
        return $rs;
    }
}

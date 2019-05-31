<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSWarehouse_model extends MY_Model
{
    public $tableName = "mswarehouse";
    public $pkey = "fin_warehouse_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_warehouse_id)
    {
        //$ssql = "select * from " . $this->tableName . " where fin_branch_id = ?";
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
}

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSBranches_model extends MY_Model
{
    public $tableName = "branches";
    public $pkey = "fin_branch_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_branch_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_branch_id = ?";
        $qr = $this->db->query($ssql, [$fin_branch_id]);
        $rwBranch = $qr->row();
        $data = [
            "branches" => $rwBranch
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_branch_name',
            'label' => 'Branch Name',
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
        $ssql = "select fin_branch_id,fst_branch_name from " . $this->tableName . " where fst_active = 'A'";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function get_Branch()
    {
        $query = $this->db->get('branches');
        return $query->result_array();
    }
}

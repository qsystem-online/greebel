<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Usersgroup_model extends MY_Model
{
    public $tableName = "usersgroup";
    public $pkey = "fin_group_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_group_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_group_id = ?";
        $qr = $this->db->query($ssql, [$fin_group_id]);
        $rwUsersgroup = $qr->row();
        $data = [
            "usersgroup" => $rwUsersgroup
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_group_name',
            'label' => 'Group Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
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
        $ssql = "select fin_group_id,fst_group_name from " . $this->tableName . " where fst_active = 'A'";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }
}

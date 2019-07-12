<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Glaccountmaingroups_model extends MY_Model
{
    public $tableName = "glaccountmaingroups";
    public $pkey = "fin_glaccount_maingroup_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_glaccount_maingroup_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_glaccount_maingroup_id = ? and fst_active = 'A'";
        //$ssql = "SELECT fst_glaccount_maingroup_name, fin_glaccount_maingroup_id from " . $this->tableName . " where fst_glaccount_maingroup_name = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_glaccount_maingroup_id]);
        $rw = $qr->row();

        $data = [
            "glAccountMainGroups" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_glaccount_maingroup_name',
            'label' => 'GL Main Group Name',
            'rules' => array(
                'required',
                'is_unique[glaccountmaingroups.fst_glaccount_maingroup_name.fin_glaccount_maingroup_id.' . $id . ']',
                'min_length[3]'
            ),
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s harus unik',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            )
        ];

        return $rules;
    }
}

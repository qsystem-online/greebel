<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msmaingroupitems_model extends MY_Model
{
    public $tableName = "msmaingroupitems";
    public $pkey = "fin_item_maingroup_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_item_maingroup_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_item_maingroup_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_item_maingroup_id]);
        $rw = $qr->row();

        $data = [
            "maingroupitems" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_item_maingroup_name',
            'label' => 'Main Group Name',
            'rules' => 'required|min_length[2]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 2 character'
            )
        ];

        return $rules;
    }
}

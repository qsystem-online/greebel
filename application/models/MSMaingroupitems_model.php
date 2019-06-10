<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSMaingroupitems_model extends MY_Model
{
    public $tableName = "msmaingroupitems";
    public $pkey = "ItemMainGroupId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($ItemMainGroupId)
    {
        $ssql = "select * from " . $this->tableName . " where ItemMainGroupId = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$ItemMainGroupId]);
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
            'field' => 'ItemMainGroupName',
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

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSGroupitems_model extends MY_Model
{
    public $tableName = "msgroupitems";
    public $pkey = "ItemGroupId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($ItemGroupId)
    {
        $ssql = "select * from " . $this->tableName . " where ItemGroupId = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$ItemGroupId]);
        $rw = $qr->row();

        $data = [
            "groupitems" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'ItemGroupName',
            'label' => 'Group Name',
            'rules' => 'required|min_length[2]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 2 character'
            )
        ];

        return $rules;
    }
}

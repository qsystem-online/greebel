<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mssubgroupitems_model extends MY_Model
{
    public $tableName = "mssubgroupitems";
    public $pkey = "fin_item_subgroup_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_item_subgroup_id)
    {
        $ssql = "select a. *,b.fst_item_group_name from " . $this->tableName . " a left join msgroupitems b on a.fin_item_group_id = b.fin_item_group_id  where a.fin_item_subgroup_id = ? and a.fst_active = 'A'";

        $qr = $this->db->query($ssql, [$fin_item_subgroup_id]);
        $rw = $qr->row();

        $data = [
            "subgroupitems" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_item_subgroup_name',
            'label' => 'Subgroup Name',
            'rules' => 'required|min_length[2]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 2 character'
            )
        ];

        return $rules;
    }
}

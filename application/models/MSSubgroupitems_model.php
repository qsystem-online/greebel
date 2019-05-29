<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSSubgroupitems_model extends MY_Model
{
    public $tableName = "mssubgroupitems";
    public $pkey = "ItemSubGroupId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($ItemSubGroupId)
    {
        $ssql = "select a. *,b.ItemGroupName from " . $this->tableName . " a left join msgroupitems b on a.ItemGroupId = b.ItemGroupId  where a.ItemSubGroupId = ? and a.fst_active = 'A'";

        $qr = $this->db->query($ssql, [$ItemSubGroupId]);
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
            'field' => 'ItemSubGroupName',
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

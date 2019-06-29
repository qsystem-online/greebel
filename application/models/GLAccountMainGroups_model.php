<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class GLAccountMainGroups_model extends MY_Model
{
    public $tableName = "glaccountmaingroups";
    public $pkey = "GLAccountMainGroupId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($GLAccountMainGroupId)
    {
        $ssql = "select * from " . $this->tableName . " where GLAccountMainGroupId = ? and fst_active = 'A'";
        //$ssql = "SELECT GLAccountMainGroupName, GLAccountMainGroupId from " . $this->tableName . " where GLAccountMainGroupName = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$GLAccountMainGroupId]);
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
            'field' => 'GLAccountMainGroupName',
            'label' => 'GL Main Group Name',
            'rules' => array(
                'required',
                'is_unique[glaccountmaingroups.GLAccountMainGroupName.GLAccountMainGroupId.' . $id . ']',
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

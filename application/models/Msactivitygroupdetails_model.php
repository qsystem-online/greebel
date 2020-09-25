<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msactivitygroupdetails_model extends MY_Model
{
    public $tableName = "msactivitygroupdetails";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_rec_id)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " where fin_rec_id = ? ";
        $qr = $this->db->query($ssql, [$fin_rec_id]);
        $rw = $qr->row();

        $data = [
            "" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_activity_id',
            'label' => 'Activity ID',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
    public function deleteByHeaderId($fin_activity_group_id)
    {
        $ssql = "DELETE FROM " . $this->tableName . " where fin_activity_group_id = $fin_activity_group_id";
        $this->db->query($ssql);
    }
}

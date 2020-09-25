<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msactivitygroups_model extends MY_Model
{
    public $tableName = "msactivitygroups";
    public $pkey = "fin_activity_group_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_activity_group_id)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fin_activity_group_id = ? AND fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_activity_group_id]);
        $rwActivitygroups = $qr->row_array();

        $ssql = "SELECT a.*,b.fst_name FROM msactivitygroupdetails a LEFT JOIN msactivity b ON a.fin_activity_id = b.fin_activity_id WHERE a.fin_activity_group_id = ?";
        $qr = $this->db->query($ssql, [$fin_activity_group_id]);
        $rwGroupdetail = $qr->result_array();

        $data = [
            "msactivitygroups" => $rwActivitygroups,
            "groupdetails" => $rwGroupdetail,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_activity_group_name',
            'label' => 'ActivityGroups Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            )
        ];
        

        return $rules;
    }

    public function getAllList()
    {
        $ssql = "select fin_activity_group_id,fst_activity_group_name from " . $this->tableName . " where fst_active = 'A' order by fst_activity_group_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}

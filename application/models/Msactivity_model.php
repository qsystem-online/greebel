<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msactivity_model extends MY_Model
{
    public $tableName = "msactivity";
    public $pkey = "fin_activity_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_activity_id)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " WHERE fin_activity_id = ? AND fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_activity_id]);
        $rwActivity = $qr->row_array();

        $ssql = "SELECT * FROM msactivityborongandetails WHERE fin_activity_id = ?";
        $qr = $this->db->query($ssql, [$fin_activity_id]);
        $rwBorongan = $qr->result_array();

        $data = [
            "msactivity" => $rwActivity,
            "borongandetail" => $rwBorongan,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_name',
            'label' => 'Activity Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_team',
            'label' => 'Team',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];
        $rules[] = [
            'field' => 'fst_type',
            'label' => 'Type',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];
        

        return $rules;
    }

    public function getAllList()
    {
        $ssql = "select fin_activity_id,fst_name from " . $this->tableName . " where fst_active = 'A' order by fst_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}

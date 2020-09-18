<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msactivityteamdetails_model extends MY_Model
{
    public $tableName = "msactivityteamdetails";
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
            'field' => 'fin_team_id',
            'label' => 'Team ID',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
    public function deleteByHeaderId($fin_team_id)
    {
        $ssql = "DELETE FROM " . $this->tableName . " where fin_team_id = $fin_team_id";
        $this->db->query($ssql);
    }
}

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msactivityteams_model extends MY_Model
{
    public $tableName = "msactivityteams";
    public $pkey = "fin_team_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_team_id)
    {
        $ssql = "SELECT a.*,b.fst_username as headteam FROM " . $this->tableName . " a LEFT JOIN users b ON a.fin_headteam_id = b.fin_user_id WHERE a.fin_team_id = ? AND a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_team_id]);
        $rwActivityteams = $qr->row_array();

        $ssql = "SELECT a.*,b.fst_username as personel FROM msactivityteamdetails a LEFT JOIN users b ON a.fin_user_id = b.fin_user_id WHERE a.fin_team_id = ?";
        $qr = $this->db->query($ssql, [$fin_team_id]);
        $rwTeamdetail = $qr->result_array();

        $data = [
            "msactivityteams" => $rwActivityteams,
            "teamdetails" => $rwTeamdetail,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_team_name',
            'label' => 'Team Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            )
        ];

        $rules[] = [
            'field' => 'fin_headteam_id',
            'label' => 'Head team',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        

        return $rules;
    }

    public function getAllList()
    {
        $ssql = "select fin_team_id,fst_team_name from " . $this->tableName . " where fst_active = 'A' order by fst_team_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

}

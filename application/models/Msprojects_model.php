<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msprojects_model extends MY_Model{
    public $tableName ="msprojects";
    public $pkey ="fin_project_id";

    public function __construct(){
        parent::__construct();
    }

    public function getDataById($fin_project_id){
        $ssql = "SELECT * FROM msprojects where fin_project_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_project_id]);
        $rwProjects = $qr->row();

        $data = [
            "ms_projects" => $rwProjects
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_project_name',
            'label' => 'Project Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }

    public function getAllList(){
        $ssql = "select * from msprojects where fst_active = 'A' and fin_branch_id = ?  order by fst_project_name";
        $qr = $this->db->query($ssql,[$this->aauth->get_active_branch_id()]);
        $rs = $qr->result();
        return $rs;
    }

    



}
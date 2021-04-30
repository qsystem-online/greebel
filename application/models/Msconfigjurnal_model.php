<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msconfigjurnal_model extends MY_Model{
    public $tableName ="msconfigjurnal";
    public $pkey ="fin_rec_id";

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
            'field' => 'fin_item_group_id',
            'label' => 'Item Group',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        return $rules;
    }
    
}
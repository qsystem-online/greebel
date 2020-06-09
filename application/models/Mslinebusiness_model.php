<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mslinebusiness_model extends MY_Model {
    public $tableName = "mslinebusiness";
    public $pkey = "fin_linebusiness_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_linebusiness_id ){
        $ssql = "select fin_linebusiness_id, fst_linebusiness_name from " . $this->tableName ." where fin_linebusiness_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_linebusiness_id]);
        $rwMSLinebusiness = $qr->row();
        
		$data = [
            "mslinebusiness" => $rwMSLinebusiness
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_linebusiness_name',
            'label' => 'Line Business Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    public function get_data_linebusiness(){
        $term = $this->input->get("term");
        $ssql = "select * from " . $this->tableName ." where fst_active ='A' order by fst_linebusiness_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function getSetName($businessSetId , $separator = ","){
        $arrLineBussiness =  explode(",",$businessSetId);        
        $ssql = "SELECT * FROM mslinebusiness where fin_linebusiness_id IN ?";
        $qr = $this->db->query($ssql,[$arrLineBussiness]);
        $rs = $qr->result();
        $setName = "";
        foreach($rs as $rw ){
            $setName .= $rw->fst_linebusiness_name . $separator;
        }

        $setName = rtrim($setName,$separator);

        return $setName;
    }
}
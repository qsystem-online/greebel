<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msarea_model extends MY_Model {
    public $tableName = "msarea";
    public $pkey = "kode";

    public function __construct(){
        parent:: __construct();
    }
    
    public function getDataById($fst_kode){
        $ssql = "SELECT fst_kode,fst_nama FROM " . $this->tableName . " WHERE fst_kode = ?"; 
        $qr = $this->db->query($ssql, [$fst_kode]);
        $rwArea = $qr->row();

        $data = [
            "ms_area" => $rwArea
        ];

        return $data;
    }
    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'kode',
            'label' => 'kode',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    }
}
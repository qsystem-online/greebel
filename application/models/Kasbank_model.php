<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kasbank_model extends MY_Model{
    public $tableName = "mskasbank";
    public $pkey = "fin_kasbank_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_kasbank_id){
        $ssql = "SELECT fin_kasbank_id,fst_kasbank_name FROM mskasbank where fin_kasbank_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_kasbank_id]);
        $rwKasbank = $qr->row();

        $data = [
            "ms_Kasbank" => $rwKasbank
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_curr_name',
            'label' => 'Currencies Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_prefix_pengeluaran',
            'label' => 'Prefix Pengeluaran',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_prefix_pemasukan',
            'label' => 'Prefix Pemasukan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_type',
            'label' => 'Type',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_gl_account_code',
            'label' => 'GL Account Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
    
}

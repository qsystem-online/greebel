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
        $ssql = "SELECT a.*,b.fst_glaccount_code FROM mskasbank a LEFT JOIN glaccounts b ON a.fst_gl_account_code = b.fst_glaccount_code
                where a.fin_kasbank_id = ? AND a.fst_active = 'A' ";
        $qr = $this->db->query($ssql, [$fin_kasbank_id]);
        $rwKasbank = $qr->row();

        $data = [
            "ms_kasbank" => $rwKasbank
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_kasbank_name',
            'label' => 'Cash/Bank Name',
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

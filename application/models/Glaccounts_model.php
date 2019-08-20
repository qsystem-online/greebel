<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class GL_account {
    private $CI;
    private $rw;
    private $db;

    public function __construct($CI,$id){
        $this->CI = $CI;
        $this->db = $CI->db;

        $ssql = "select * from glaccounts where fst_glaccount_code = ?";
        $qr = $this->CI->db->query($ssql,[$id]);
        $this->rw = $qr->row();
        if ($this->rw == false){
            throw new Exception("Invalid ID");
        }
    }
    public function __debugInfo() {
        //support on php 5.6
        return [
            'rw' => $this->rw
        ];
    }
    public function __get($name){
        if (property_exists($this->rw,$name)){
            return $this->rw->$name;
        }else{
            throw new Exception("Invalid Property Name !");
        }
    }
}

class Glaccounts_model extends MY_Model
{
    public $tableName = "glaccounts";
    public $pkey = "fst_glaccount_code";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fst_glaccount_code)
    {
        $ssql = "select a.*,b.fst_curr_name, c.fst_glaccount_name as GLParentName, d.fst_glaccount_maingroup_name, d.fst_glaccount_main_prefix 
        from " . $this->tableName . " a 
        left join mscurrencies b on a.fst_curr_code = b.fst_curr_code 
        left join " . $this->tableName . " c on a.fst_parent_glaccount_code = c.fst_glaccount_code
        left join glaccountmaingroups d on a.fin_glaccount_maingroup_id = d.fin_glaccount_maingroup_id
        where a.fst_glaccount_code = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fst_glaccount_code]);
        $rwGLAccounts = $qr->row();

        $ssql = "select * from glaccounts where fst_parent_glaccount_code = ?";
        $qr = $this->db->query($ssql, [$fst_glaccount_code]);
        $rwParent = $qr->row();

        $data = [
            "gl_Account" => $rwGLAccounts,
            "parents" => $rwParent,
            "isUsed" => $this->isUsed($fst_glaccount_code)
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_glaccount_code',
            'label' => 'GL Account Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.fst_glaccount_code.fst_glaccount_code.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];

        $rules[] = [
            'field' => 'fst_glaccount_name',
            'label' => 'GL Account Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            ),
        ];

        $rules[] = [
            'field' => 'fst_curr_code',
            'label' => 'Current Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            ),
        ];

        return $rules;
    }

    public function isUsed($fst_glaccount_code){
        return true;
    }
}

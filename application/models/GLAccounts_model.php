<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class GLAccounts_model extends MY_Model {
    public $tableName = "glaccounts";
    public $pkey = "GLAccountCode";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($GLAccountCode ){
        $ssql = "select * from " . $this->tableName ." where GLAccountCode = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$GLAccountCode]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'GLAccountCode',
            'label' => 'GL Account Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'GLAccountName',
            'label' => 'GL Account Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'ParentGLAccountCode',
            'label' => 'Parent GL Account Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'CurrCode',
            'label' => 'Current Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
}
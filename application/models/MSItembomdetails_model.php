<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSItembomdetails_model extends MY_Model {
    public $tableName = "msitembomdetails";
    public $pkey = "recid";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($recid ){
        $ssql = "select * from " . $this->tableName ." where recid = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$recid]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'ItemCode',
            'label' => 'Item Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'ItemCodeBOM',
            'label' => 'Item Code BOM',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'unit',
            'label' => 'Unit',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}
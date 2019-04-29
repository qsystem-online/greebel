<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSMemberShips_model extends MY_Model {
    public $tableName = "msmemberships";
    public $pkey = "RecId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RecId ){
        $ssql = "select * from " . $this->tableName ." where RecId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$RecId]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'MemberNo',
            'label' => 'Member No',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'NameOnCard',
            'label' => 'Name On Card',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'ExpiryDate',
            'label' => 'Expiry Date',
            'rules' => array(
				'required'				
			),
            'errors' => array(
				'required' => '%s tidak boleh kosong',
			),
        ];
        
        $rules[] =[
			'field' => 'MemberDiscount',
			'label' => 'Member Discount',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka'
			)
        ];

        return $rules;
    }
}
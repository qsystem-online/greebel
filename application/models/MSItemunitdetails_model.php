<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSItemunitdetails_model extends MY_Model {
    public $tableName = "msitemunitdetails";
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
            'field' => 'ItemCode',
            'label' => 'Item Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'Unit',
            'label' => 'Unit',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'Conv2BasicUnit',
            'label' => 'Conv2 Basic Unit',
            'rules' => 'numeric',
            'errors' => array(
                'numeric' => '%s harus berupa angka'
            )
        ];
        
        $rules[] =[
			'field' => 'PriceList',
			'label' => 'Price List',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka'
			)
        ];

        $rules[] =[
			'field' => 'HET',
			'label' => 'HET',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka'
			)
        ];

        $rules[] =[
			'field' => 'LastBuyingPrice',
			'label' => 'Last Buying Price',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka'
			)
        ];

        return $rules;
    }
}
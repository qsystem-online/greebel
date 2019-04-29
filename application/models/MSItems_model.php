<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSItems_model extends MY_Model {
    public $tableName = "msitems";
    public $pkey = "ItemId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($ItemId ){
        $ssql = "select * from " . $this->tableName ." where ItemId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$ItemId]);
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
            'field' => 'ItemName',
            'label' => 'Item Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'VendorItemName',
            'label' => 'Vendor Item Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];
        
        $rules[] =[
			'field' => 'MinBasicUnitAvgCost',
			'label' => 'Min Basic Unit Avg Cost',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka',
			)
        ];
        
        $rules[] =[
			'field' => 'MaxBasicUnitAvgCost',
			'label' => 'Max Basic Unit Avg Cost',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka',
			)
		];

        return $rules;
    }
}
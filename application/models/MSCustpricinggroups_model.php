<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSCustpricinggroups_model extends MY_Model {
    public $tableName = "mscustpricinggroups";
    public $pkey = "CustPricingGroupId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($CustPricingGroupId ){
        $ssql = "select CustPricingGroupId,CustPricingGroupName,PercentOfPriceList,DifferenceInAmount from " . $this->tableName ." where CustPricingGroupId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$CustPricingGroupId]);
        $rwCustPricingGroupId = $qr->row();
        
		$data = [
            "mscustpricinggroups" => $rwCustPricingGroupId
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'CustPricingGroupName',
            'label' => 'Cust Pricing Group Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            )
        ];

        $rules[] =[
			'field' => 'PercentOfPriceList',
			'label' => 'Percent',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka',
			)
        ];
        
        $rules[] =[
			'field' => 'DifferenceInAmount',
			'label' => 'Amount',
			'rules' => 'required|numeric',
			'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka'
			)
		];

        return $rules;
    }
}
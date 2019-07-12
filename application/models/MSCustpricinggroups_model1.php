<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mscustpricinggroups_model extends MY_Model {
    public $tableName = "mscustpricinggroups";
    public $pkey = "fin_cust_pricing_group_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_cust_pricing_group_id ){
        $ssql = "select fin_cust_pricing_group_id,fst_cust_pricing_group_name,fdc_percent_of_price_list,fdc_difference_in_amount from " . $this->tableName ." where fin_cust_pricing_group_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_cust_pricing_group_id]);
        $rwMSCustpricinggroups = $qr->row();
        
		$data = [
            "mscustpricinggroups" => $rwMSCustpricinggroups
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_cust_pricing_group_name',
            'label' => 'Cust Pricing Group Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    public function get_CustPricingGroups(){
        $query = $this->db->get('mscustpricinggroups');
		return $query->result_array();
    }
}
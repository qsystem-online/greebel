<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msprofitcostcenter_model extends MY_Model {
    public $tableName = "msprofitcostcenter";
    public $pkey = "fin_pcc_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_pcc_id){
		$ssql = "select * from msprofitcostcenter where fin_pcc_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_pcc_id]);		
		$rwProfitCostCenter = $qr->row();
        $data = [
            "profit_cost_center" => $rwProfitCostCenter
        ];
        return $data;
    }
    
    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_pcc_name',
            'label' => 'Profit & Cost Center Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
    
    public function getAllList(){
        $ssql = "select fin_pcc_id,fst_pcc_name from msprofitcostcenter where fst_pcc_name";
        $qr = $this->db->query($ssql,[]);		
        $rs = $qr->result();		
		return $rs;
    }

    public function get_profitcostcenter(){
        $query = $this->db->get('msprofitcostcenter');
		return $query->result_array();
    }
}
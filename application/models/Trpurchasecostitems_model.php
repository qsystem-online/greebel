<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchasecostitems_model extends MY_Model {
    public $tableName = "trpurchasecostitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fin_pcc_id',
            'label' => lang('Profit & Cost Center'),
            'rules' => 'required',
            'errors' => array(
                'required' => lang('%s tidak boleh kosong'),
            )
        ];                
        return $rules;
    }


    public function deleteById($finPurchaseCostId){
        $ssql ="delete from trpurchasecostitems where fin_purchasecost_id = ?";
        $this->db->query($ssql,[$finPurchaseCostId]);
        
        $dbError  = $this->db->error();
		if ($dbError["code"] != 0){	
            $result["status"]= "FAILED";
            $result["message"]= $dbError["message"];            
			return $result;
        }

        return[
            "status"=>"SUCCESS",
            "message"=>""
        ];

    }

}



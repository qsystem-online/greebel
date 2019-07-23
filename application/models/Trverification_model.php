<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trverification_model extends MY_Model {
    public $tableName = "trverification";
    public $pkey = "fin_rec_id";

    public function __construct() {
        parent::__construct();
    }
    
    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];
        return $rules;
    }

    public function voidAuthorize($branchId,$controller,$transactionId){
        //'VD:VOID'
        $ssql ="update trverification set fst_verification_status ='VD' where fin_branch_id = ? and fst_controller = ? and fin_transaction_id = ?";
        $this->db->query($ssql,[$branchId,$controller,$transactionId]);

    }
    public function createAuthorize($controller,$module,$transactionId,$message,$notes = null){
        $this->load->model("msverification_model");
        $arrVerify = $this->msverification_model->getData("SO","QtyOutStock");
        foreach($arrVerify as $verify){
            $dataVerify =[
                "fin_branch_id"=>$verify->fin_branch_id,
                "fst_controller"=>$controller,
                "fin_transaction_id"=>$transactionId,
                "fin_seqno"=>$verify->fin_seqno,
                "fst_message"=>$message,
                "fin_department_id"=>$verify->fin_department_id,
                "fin_user_group_id"=>$verify->fin_user_group_id,
                "fst_verification_status"=>"NV",
                "fst_notes"=>$notes,
                "fst_active"=>"A",
            ];				
            parent::insert($dataVerify);
        }

    }
}
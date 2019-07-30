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
        //$ssql ="update trverification set fst_verification_status ='VD', where fin_branch_id = ? and fst_controller = ? and fin_transaction_id = ?";
        $ssql ="update trverification set fst_active ='D' where fin_branch_id = ? and fst_controller = ? and fin_transaction_id = ?";
        $this->db->query($ssql,[$branchId,$controller,$transactionId]);

    }
    public function createAuthorize($controller,$module,$transactionId,$message,$notes = null){
        $this->load->model("msverification_model");
        $arrVerify = $this->msverification_model->getData($controller,$module);
        foreach($arrVerify as $verify){
            $dataVerify =[
                "fin_branch_id"=>$verify->fin_branch_id,
                "fst_controller"=>$controller,
                "fst_verification_type"=>$verify->fst_verification_type,
                "fin_transaction_id"=>$transactionId,
                "fin_seqno"=>$verify->fin_seqno,
                "fst_message"=>$message,
                "fin_department_id"=>$verify->fin_department_id,
                "fin_user_group_id"=>$verify->fin_user_group_id,
                "fst_verification_status"=>"NV",
                "fst_notes"=>$notes,
                "fst_model"=>$verify->fst_model,
                "fst_method"=>$verify->fst_method,
                "fst_active"=>"A",
            ];				
            parent::insert($dataVerify);
        }

    }

    public function approve($finRecId){
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$finRecId]);
        $rw = $qr->row();

        $activeUser = $this->aauth->user();
        //di approved oleh orang dr departemen dan group sesuai ketentuan
        if ($rw->fin_department_id == $activeUser->fin_department_id && $rw->fin_user_group_id == $activeUser->fin_group_id){
            $data=[
                "fin_rec_id"=>$finRecId,
                "fst_verification_status"=>"VF" //Verified
            ];
            parent::update($data);

            
            //Cek if all row in same seqno allready approved
            $ssql = "select * from " . $this->tableName . " where fst_controller = ? 
                and fst_verification_type = ?
                and fin_transaction_id =? 
                and fin_branch_id =? 
                and fin_seqno = ?
                and fst_verification_status != 'VF' 
                and fst_active = 'A' 
                limit 1";
            
            $qr = $this->db->query($ssql,[
                $rw->fst_controller,
                $rw->fst_verification_type,
                $rw->fin_transaction_id,
                $rw->fin_branch_id,
                $rw->fin_seqno
            ]);
            $rwCek = $qr->row();
            
            if ($rwCek == false){
                //Semua pada seqno ini telah mengverifikasi
                //Update next seq No
                $ssql = "select fin_seqno from " . $this->tableName . " where fst_controller = ? 
                and fst_verification_type = ?
                and fin_transaction_id =? 
                and fin_branch_id =? 
                and fin_seqno > ? 
                and fst_active = 'A' 
                order by fin_seqno limit 1";
                
                $qr = $this->db->query($ssql,[
                    $rw->fst_controller,
                    $rw->fst_verification_type,
                    $rw->fin_transaction_id,
                    $rw->fin_branch_id,
                    $rw->fin_seqno
                ]);
                
                $rwCek = $qr->row();
                if ($rwCek == false){
                    //Proses Verifikasi selesai
                    $this->load->model($rw->fst_model,'model');
                    $action = $rw->fst_method;

                    if(is_callable(array($this->model, $action))){
                        $this->model->$action($rw->fin_transaction_id);
                    }

                }else{
                    $nextSeqno  = $rw->fin_seqno;
                    $ssql = "update " . $this->tableName . " set fst_verification_status = 'RV' where 
                    fst_controller = ? 
                    and fst_verification_type = ? 
                    and fin_transaction_id =? 
                    and fin_branch_id =? 
                    and fin_seqno = ? 
                    and fst_active = 'A' ";

                    $qr = $this->db->query($ssql,[
                        $rw->fst_controller,
                        $rw->fst_verification_type,
                        $rw->fin_transaction_id,
                        $rw->fin_branch_id,
                        $nextSeqno
                    ]);
                    
                }

            }
        }else{
            return false;
        }
        

    }
}
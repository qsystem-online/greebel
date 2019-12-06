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
    
    public function createAuthorize($controller,$module,$transactionId,$message,$notes = null,$transactionNo = null){
        $this->load->model("msverification_model");
        $arrVerify = $this->msverification_model->getData($controller,$module);
        foreach($arrVerify as $verify){
            $status = ($verify->fin_seqno == 0 ? "RV" : "NV");
            $dataVerify =[
                "fin_branch_id"=>$this->aauth->get_active_branch_id(),
                "fst_controller"=>$controller,
                "fst_verification_type"=>$verify->fst_verification_type,
                "fin_transaction_id"=>$transactionId,
                "fst_transaction_no"=>$transactionNo,
                "fin_seqno"=>$verify->fin_seqno,
                "fst_message"=>$message,
                "fin_department_id"=>$verify->fin_department_id,
                "fin_user_group_id"=>$verify->fin_user_group_id,
                "fst_verification_status"=>$status,
                "fst_notes"=>$notes,
                "fst_model"=>$verify->fst_model,
                "fst_method"=>$verify->fst_method,
                "fst_show_record_method"=>$verify->fst_show_record_method,
                "fst_active"=>"A",
            ];			
            parent::insert($dataVerify);
        }

    }

    public function cancelAuthorize($controller,$transactionId){
        $ssql = "delete from trverification where fst_controller = ? and fin_transaction_id = ? ";
        $this->db->query($ssql,[$controller,$transactionId]);
        $this->load->model("msverification_model");       
    }


    public function approve($finRecId,$fstNotes,$isApproved){
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$finRecId]);
        $rw = $qr->row();

        $activeUser = $this->aauth->user();
        //di approved oleh orang dr departemen dan group sesuai ketentuan
        if ($rw->fin_department_id == $activeUser->fin_department_id && $rw->fin_user_group_id == $activeUser->fin_group_id){
            if ($isApproved){
                $data=[
                    "fin_rec_id"=>$finRecId,
                    "fst_verification_status"=>"VF", //Verified
                    "fst_notes"=>$fstNotes
                ];
            }else{
                $data = [
                    "fin_rec_id"=>$finRecId,
                    "fst_verification_status"=>"RJ", //REJECT
                    "fst_notes"=>$fstNotes
                ];
            }
            parent::update($data);

            if ($isApproved){
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
                
                if ($rwCek == null){
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
                    if ($rwCek == null){
                        //Proses Verifikasi selesai
                        $this->load->model($rw->fst_model,'model');
                        $action = $rw->fst_method;

                        if(is_callable(array($this->model, $action))){
                            $result = $this->model->$action($rw->fin_transaction_id,true);
                            return $result;
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
                //Rubah semua seq_no (yang sama dan belum VF) dan seq_no diatasnya menjadi rejected
                $ssql ="update " . $this->tableName . " set fst_verification_status = 'RJ' 
                    where fst_controller = ? 
                    and fst_verification_type = ? 
                    and fin_transaction_id = ?
                    and fin_seqno >= ? 
                    and fst_verification_status != 'VF'";

                $this->db->query($ssql,array($rw->fst_controller,$rw->fst_verification_type,$rw->fin_transaction_id,$rw->fin_seqno));
                $this->load->model($rw->fst_model,'model');
                $action = $rw->fst_method;

                if(is_callable(array($this->model, $action))){
                    $result = $this->model->$action($rw->fin_transaction_id,false);
                    return $result;
                }

            }
            $result =[
                "status"=>"SUCCESS",
                "message"=>"",
            ];
            return $result;

        }else{

            $result =[
                "status"=>"FAILED",
                "message"=>lang("Anda tidak memiliki autorisasi untuk melakukan approval !")
            ];
            return $result;
        }
        

    }

    public function cancelApprove($finRecId){
        /**
         * Rules :
         * 1. Hanya user yang sama atau user dr departement sama dan group yang sama
         * 2. Hanya bisa di cancel bila tingkatan approval diatasnya belum melakukan approval ataupun rejec
         * 
         */          
        $activeUser = $this->aauth->user();
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$finRecId]);        
        $rw = $qr->row();
        if($rw==null){
            throw new CustomException(lang("Id transaksi tidak ditemukan !"),3003,"FAILED",[]);
        }

        if ($rw->fin_department_id == $activeUser->fin_department_id && $rw->fin_user_group_id == $activeUser->fin_group_id){
            //Cek status diatasnya
            //NV = Need Verification, RV = Ready to verification, VF=Verified, RJ= Rejected, VD= Void
            $ssql ="SELECT * FROM trverification 
                WHERE fin_branch_id = ? AND fst_controller = ? AND fst_verification_type = ? AND fin_transaction_id = ? 
                AND fin_seqno > ? AND fst_verification_status IN ?";

            $qr = $this->db->query($ssql,[
                $rw->fin_branch_id,
                $rw->fst_controller,
                $rw->fst_verification_type,
                $rw->fin_transaction_id,                
                $rw->fin_seqno,
                ['VF','RJ','VD']
            ]);
            $rs = $qr->result();
            if (sizeof($rs) > 0){
                throw new CustomException(lang("Transaksi ini telah diapprove oleh level yang lebih tinggi !"),3003,"FAILED",[]);  
            }

            $this->load->model($rw->fst_model,'model');
            $action = "cancelApproval";
            if(is_callable(array($this->model, $action))){
                $result = $this->model->$action($rw->fin_transaction_id);
                if ($result["status"] == "SUCCESS" || $result === null){
                    //Update status diatasnya menjadi NV dan Status ini menjadi RV
                    $ssql = "UPDATE trverification SET fst_verification_status = 'NV' 
                        WHERE fin_branch_id = ? AND fst_controller = ? AND fst_verification_type = ? AND fin_transaction_id = ? 
                        AND fin_seqno > ?";

                    $qr = $this->db->query($ssql,[
                        $rw->fin_branch_id,
                        $rw->fst_controller,
                        $rw->fst_verification_type,
                        $rw->fin_transaction_id,                
                        $rw->fin_seqno
                    ]);

                    $ssql = "UPDATE trverification SET fst_verification_status = 'RV' WHERE fin_rec_id = ?";
                    $qr = $this->db->query($ssql,[$finRecId]);
                }else{
                    throw new CustomException($result["message"],3003,$result["status"],[]);
                }
                
            }else{
                throw new CustomException(lang("Cancel Approval Method Not Found in Model"),3003,"FAILED",[]);     
            }

        }else{
            return["status"=>"FAILED","message"=>lang("Anda tidak memiliki autorisasi untuk membatalkan status approval !")];
        }




    }

    public function reject($finRecId){
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$finRecId]);
        $rw = $qr->row();

        $activeUser = $this->aauth->user();
        //di approved oleh orang dr departemen dan group sesuai ketentuan
        if ($rw->fin_department_id == $activeUser->fin_department_id && $rw->fin_user_group_id == $activeUser->fin_group_id){
            $data=[
                "fin_rec_id"=>$finRecId,
                "fst_verification_status"=>"RJ" //Verified
            ];
            parent::update($data);
            //Rubah semua seq_no (yang sama dan belum VF) dan seq_no diatasnya menjadi rejected
            $ssql ="update " . $this->tableName . " set fst_verification_status = 'RJ' 
                where fst_controller = ? 
                and fst_verification_type = ? 
                and fin_transaction_id = ?
                and fin_seqno >= ? 
                and fst_verification_status != 'VF'";

            $this->db->query($ssql,array($rw->fst_controller,$rw->fst_verification_type,$rw->fin_transaction_id,$rw->fin_seqno));
            $this->load->model($rw->fst_model,'model');
            $action = $rw->fst_method;

            if(is_callable(array($this->model, $action))){
                $this->model->$action($rw->fin_transaction_id,false);
            }


        }else{
            return false;
        }
    }

    public function showTransaction($finRecId){
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$finRecId]);
        $rw = $qr->row();

        if($rw){
            $this->load->model($rw->fst_model,'model');
            $action = $rw->fst_show_record_method;

            if(is_callable(array($this->model, $action))){
                $this->model->$action($rw->fin_transaction_id);
            }
            
        }
    
    }

    public function haveAprrovalRecord($controller,$verificationType,$transactionId){
        $ssql = "select  * from ". $this->tableName ." WHERE fst_controller = ? and fst_verification_type =? and fin_transaction_id = ? and fst_verification_status in ('VF','RJ','VD')";
        $qr = $this->db->query($ssql,[$controller,$verificationType,$transactionId]);
        $rw = $qr->row();
        if(!$rw){
            return false;
        }else{
            return true;
        }
    }

    public function deleteApproval($controller,$finTransId){
        $ssql ="select * from trverification where fst_controller = ? and fin_transaction_id = ? and fst_verification_status in ('VF','RJ','VD') and fst_active ='A' ";
        $qr = $this->db->query($ssql,[$controller,$finTransId]);
        $rw = $qr->row();
        if ($rw != null){
            throw new CustomException(sprintf(lang("Transaksi %s sudah dilakukan proses approval"),$rw->fst_transaction_no),3003,"FAILED",null);
        }
        $ssql = "delete from trverification where fst_controller = ? and fin_transaction_id = ?";
        $this->db->query($ssql,[$controller,$finTransId]);
        throwIfDBError();
    }

}
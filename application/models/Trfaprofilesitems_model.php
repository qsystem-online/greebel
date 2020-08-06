<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class trfaprofilesitems_model extends MY_Model{
    public $tableName = "trfaprofilesitems";
    public $pkey = "fin_fa_profile_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        
        $rules[] = [
            'field' => 'fst_fa_profile_code',
            'label' => 'Profile Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.fst_glaccount_code.fst_glaccount_code.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];
                
        return $rules;
    }

    public function generateCode($finFAGroupId){
        $ssql = "SELECT * FROM msfagroups where fin_fa_group_id = ?";
        $qr = $this->db->query($ssql,[$finFAGroupId]);
        $rw = $qr->row();
        if($rw== null){
            throw new CustomException(lang("invalid fixed asset group id"),404,"FAILED",["fin_fa_group_id"=>$finFAGroupId]);	
        }

        //21420070001

        $prefix = $rw->fst_fa_group_code . date("Ym");
        $ssql ="select * from trfaprofilesitems where fst_fa_profile_code like '$prefix%' order by fst_fa_profile_code desc";
        $qr = $this->db->query($ssql,[]);
        $rw = $qr->row();
        if ($rw ==null){
            $lastNo = 0;
        }else{            
            $lastNo = substr($rw->fst_fa_profile_code,strlen($prefix));
            $lastNo = (int) $lastNo;
        }

        $newNo = $lastNo + 1;
        $newNo = "0000" . $newNo;
        $newNo = substr($newNo,strlen($newNo)-4);
        return $prefix.$newNo;
    }  
    

    public function getInfoById($finFAProfileDetailId){
        $ssql = "SELECT a.fst_fa_profile_code,b.fst_account_code,b.fst_accum_account_code,b.fdc_aquisition_price,sum(c.fdc_depre_amount) as fdc_depre_amount FROM trfaprofilesitems a 
            INNER JOIN trfaprofiles b on a.fin_fa_profile_id = b.fin_fa_profile_id  
            INNER JOIN trfadeprecard c on a.fst_fa_profile_code = c.fst_fa_profile_code
            WHERE a.fin_rec_id = ? group by a.fst_fa_profile_code,b.fst_account_code,b.fst_accum_account_code,b.fdc_aquisition_price";
        $qr =$this->db->query($ssql,[$finFAProfileDetailId]);
        return $qr->row();
    }
    public function disposal($data){
        if ($data["method"] =="MUTASI"){
            //Close JURNAL
            
        }else if($data["method"] =="MUTASI"){
            //Harga JUAL
        }
    }
}
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Glaccounts_model extends MY_Model
{
    public $tableName = "glaccounts";
    public $pkey = "fst_glaccount_code";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fst_glaccount_code)
    {
        $ssql = "select a.*,b.fst_curr_name, c.fst_glaccount_name as GLParentName, d.fst_glaccount_maingroup_name, d.fst_glaccount_main_prefix 
        from " . $this->tableName . " a 
        left join mscurrencies b on a.fst_curr_code = b.fst_curr_code 
        left join " . $this->tableName . " c on a.fst_parent_glaccount_code = c.fst_glaccount_code
        left join glaccountmaingroups d on a.fin_glaccount_maingroup_id = d.fin_glaccount_maingroup_id
        where a.fst_glaccount_code = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fst_glaccount_code]);
        $rwGLAccounts = $qr->row();

        $ssql = "select * from glaccounts where fst_parent_glaccount_code = ?";
        $qr = $this->db->query($ssql, [$fst_glaccount_code]);
        $rwParent = $qr->row();

        $data = [
            "gl_Account" => $rwGLAccounts,
            "parents" => $rwParent,
            "isUsed" => $this->isUsed($fst_glaccount_code)
        ];

        return $data;
    }

    public function getSimpleDataHeader($fstGlaccountCode){
        $ssql ="select a.*,b.fst_glaccount_type from glaccounts a 
            inner join glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id 
            where a.fst_glaccount_code = ?";

        $qr = $this->db->query($ssql,[$fstGlaccountCode]);
        return $qr->row();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_glaccount_code',
            'label' => 'GL Account Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.fst_glaccount_code.fst_glaccount_code.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];

        $rules[] = [
            'field' => 'fst_glaccount_name',
            'label' => 'GL Account Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            ),
        ];

        $rules[] = [
            'field' => 'fst_curr_code',
            'label' => 'Current Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            ),
        ];

        return $rules;
    }

    public function isUsed($fst_glaccount_code){
        return true;
    }

    public function delete($fst_glaccount_code,$softDelete=true,$data=null){
        //cek jika sudah ada di LEDGER
        $ssql  = "select * from glledger where fst_account_code = ?";
        $qr = $this->db->query($ssql,[$fst_glaccount_code]);
        if ($qr->row()){
            return [
                "status"=>false,
                "message"=>lang("AKUN tidak dapat dihapus, sudah ada record Ledger !"),
            ];
        }
        parent::delete($fst_glaccount_code,$softDelete);
        if(!$softDelete){
            $this->db->delete("glledger",array("fst_account_code"=>$fst_glaccount_code));
        }
        

        return [
            "status"=>true,
            "message"=>"",
        ];
    }

    public function getPrintGLAccount($mainGroupGL_start,$mainGroupGL_end){
        if ($mainGroupGL_start == 'null'){
            $mainGroupGL_start ="";
        }
        if ($mainGroupGL_end == 'null'){
            $mainGroupGL_end ="";
        }
        $ssql = "SELECT a.*,b.fst_glaccount_maingroup_name FROM glaccounts a
        left join glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id
        WHERE a.fin_glaccount_maingroup_id >= '$mainGroupGL_start' AND a.fin_glaccount_maingroup_id <= '$mainGroupGL_end' 
        ORDER BY a.fst_glaccount_code ";
        $query = $this->db->query($ssql,[]);
        //echo $this->db->last_query();
        //die();
        $rs = $query->result();

        return $rs;
    }


    public function getAccountRabaRugi(){
        $ssql = "select a.* from glaccounts a
            inner join glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id 
            where b.fst_glaccount_type = 'PROFIT_LOST' and a.fst_glaccount_level in ('DT','DK','DB')  
            and a.fst_active ='A'";

        $qr = $this->db->query($ssql,[]);		
        $rs = $qr->result();	
        return $rs;
		
    }
    public function getBiayaList(){
        $prefixBiaya = "6";
        $ssql = "select fst_glaccount_code,fst_glaccount_name from glaccounts 
            where fst_glaccount_code like ? and fst_glaccount_level in ('DT','DK','DB')  
            and fst_active ='A'";
        $qr = $this->db->query($ssql,[$prefixBiaya.'%']);		
        $rs = $qr->result();		
		return $rs;
    }

    public function get_MainGL()
    {
        $term = $this->input->get("term");
        $ssql = "SELECT fin_glaccount_maingroup_id, fst_glaccount_maingroup_name from glaccountmaingroups where fst_glaccount_maingroup_name like ?";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        return $rs;
    }

    public function getAccountList(){
        $ssql = "select a.*,if(b.fst_glaccount_type = 'PROFIT_LOST',true,false) as fbl_pcc from glaccounts a 
        inner join glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id
        where a.fst_glaccount_level != 'HD' and a.fst_active ='A'";
        $qr =$this->db->query($ssql,[]);
        $rs = $qr->result();
        return $rs;
    }

    public function getAccountListByGroup($group){

        $groupId = $group;
        if (strtolower($group) == "asset"){
            $groupId ="1";
        }else if (strtolower($group) == "biaya"){
            $groupId ="6";
        }
        $ssql = "SELECT * FROM glaccounts where fin_glaccount_maingroup_id = ? and fst_glaccount_level = 'DT' and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$groupId]);
        return $qr->result();
    }

    public function getKasbankList(){
        $ssql = "SELECT * FROM glaccounts WHERE (fst_glaccount_level = 'DK' OR fst_glaccount_level = 'DB') AND fst_active ='A'";
        $qr =$this->db->query($ssql,[]);
        $rs = $qr->result();
        return $rs;
    }
}

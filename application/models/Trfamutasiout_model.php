<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trfamutasiout_model extends MY_Model{
    public $tableName = "trfamutasiout";
    public $pkey = "fin_fa_mutasiout_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_fa_mutasiout_no',
            'label' => 'Fixed Asset Mutasi Out No',
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

    public function generateTransactionNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("fa_mutasiout_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_fa_mutasiout_no) as max_id FROM trfamutasiout where fst_fa_mutasiout_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
    }

    public function posting($finFAMutasioutId){
        //Disposal mutasi asset
        $this->load->model("trfaprofilesitems_model");
        $ssql ="SELECT * FROM trfamutasioutitems where fin_fa_mutasiout_id = ?";
        $qr = $this->db->query($ssql,[$finFAMutasioutId]);
        $rs = $qr->result();
        foreach($rs as $rw){
            $trfaprofilesitems_model->disposal($rw->fst_fa_profile_code,"MUTASI");
            
        }
    }
    
}
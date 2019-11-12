<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchasecost_model extends MY_Model {
    public $tableName = "trpurchasecost";
    public $pkey = "fin_purchasecost_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_purchasereturn_no',
            'label' => 'Purchase Return No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_supplier_id',
            'label' => 'Supplier',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_warehouse_id',
            'label' => 'Gudang',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];

        return $rules;
    }

    public function GeneratePurchaseCostNo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("purchase_cost_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_purchasecost_no) as max_id FROM trpurchasecost where fst_purchasecost_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }
    
    public function getListPO($isImport){
        $isImport = (int) $isImport;
        $ssql = "select * from trpo where fbl_is_import = ? and fbl_cost_completed =  false";
        $qr = $this->db->query($ssql,[$isImport]);
        $rs = $qr->result();
        return $rs;        
    }

    
    
    public function isEditable($finPurchaseCostId){
       
        /**
         * FALSE CONDITION
         * 1. 
         */
        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
    }
    
}



<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trassembling_model extends MY_Model{
	public $tableName = "trassembling";
	public $pkey = "fin_assembling_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_assembling_no',
			'label' => 'Assembling / Disassembling No',
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
		$prefix = getDbConfig("assembling_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_assembling_no) as max_id FROM trassembling where fst_assembling_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
	}

	public function getDataById($finAssemblingId){
		$ssql ="SELECT a.*,b.fst_item_name,b.fst_item_code 
			FROM trassembling a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id where fin_assembling_id = ?";

		$qr = $this->db->query($ssql,[$finAssemblingId]);		
		$dataH = $qr->row();
		if ($dataH == null){
			return [
				"header"=>null,
				"details"=>null
			];
		}

		$ssql = "SELECT a.*,b.fst_item_name,b.fst_item_code 
			FROM trassemblingitems a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
			WHERE a.fin_assembling_id = ?";
		
		$qr = $this->db->query($ssql,[$finAssemblingId]);
		
		$details = $qr->result();
		return [
			"header"=>$dataH,
			"details"=>$details
		];
	}

	public function getDataHeader($finAssemblingId){		
		$ssql ="SELECT * FROM trassembling where fin_assembling_id = ? ";
		$qr = $this->db->query($ssql,[$finAssemblingId]);
		return $qr->row();
	}
	

	public function isEditable($finAssemblingId){
		//Data belum dilakukan gudang out

	}

	public function deleteDetail($finAssemblingId){
		$ssql ="DELETE FROM trassemblingitems where fin_assembling_id = ?";
		$this->db->query($ssql,[$finAssemblingId]);		
	}

	public function delete($finAssemblingId,$softdelete = TRUE,$data=null){		
		parent::delete($finAssemblingId,$softdelete,$data);
		if ($softdelete){
			$ssql ="UPDATE trassemblingitems set fst_active ='D' where fin_assembling_id = ?";
		}else{
			$ssql ="DELETE from trassemblingitems where fin_assembling_id = ?";
		}		
		$this->db->query($ssql,[$finAssemblingId]);		
	}

}
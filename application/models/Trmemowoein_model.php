<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trmemowoein_model extends MY_Model{
	public $tableName = "trmemowoein";
	public $pkey = "fin_woein_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_woein_no',
			'label' => 'Nomor Memo In',
			'rules' => array(
				'required',
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
		$prefix = getDbConfig("woe_memo_in_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_woein_no) as max_id FROM trmemowoein where fst_woein_no like '".$prefix.$tahun."%'");
		
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
	}
	
	public function getDataById($finWOEInId){                
		
		$ssql = "SELECT a.*,
			b.fin_woeout_id,b.fst_woeout_no,(b.fdb_qty - b.fdb_qty_in) as fdb_qty_balance,
			c.fst_unit,
			d.fst_relation_name AS fst_supplier_name,
			e.fst_item_name,e.fst_item_code  
			FROM trmemowoein a 
			INNER JOIN trmemowoeout b ON a.fin_woeout_id = b.fin_woeout_id
			INNER JOIN trwo c ON b.fin_wo_id = c.fin_wo_id
			INNER JOIN msrelations d ON b.fin_supplier_id = d.fin_relation_id
			INNER JOIN msitems e  ON c.fin_item_id = e.fin_item_id            
			WHERE a.fin_woein_id = ?";
		$qr = $this->db->query($ssql,[$finWOEInId]);
		$data = $qr->row();
		if ($data == null){
			return null;
		}
		
		return [
			"data"=>$data,
		];
	}


	public function posting($finWOEInId){	
		$dataH = $this->getSimpleDataById($finWOEInId);
		$ssql = "UPDATE trmemowoeout set fdb_qty_in =  fdb_qty_in + ? where fin_woeout_id = ? and fst_active ='A'";
		$this->db->query($ssql,[$dataH->fdb_qty,$dataH->fin_woeout_id]);
		
		$ssql = "SELECT * FROM trmemowoeout where fin_woeout_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$dataH->fin_woeout_id]);
		$rw = $qr->row();
		if ($rw->fdb_qty < $rw->fdb_qty_in){
			throw new CustomException(lang("Total Qty In Lebih Besar dari Qty Out"),3003,"FAILED",[]);
		}
	}
	public function unposting($finWOEInId){	
		$dataH = $this->getSimpleDataById($finWOEInId);
		
		$ssql = "UPDATE trmemowoeout set fdb_qty_in =  fdb_qty_in - ? where fin_woeout_id = ? and fst_active ='A'";
		$this->db->query($ssql,[$dataH->fdb_qty,$dataH->fin_woeout_id]);
		
		$ssql = "SELECT * FROM trmemowoeout where fin_woeout_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$dataH->fin_woeout_id]);
		$rw = $qr->row();
		if ($rw->fdb_qty < $rw->fdb_qty_in){
			throw new CustomException(lang("Total Qty In Lebih Besar dari Qty Out"),3003,"FAILED",[]);
		}
	}
	public function isEditable(){
		

	}

	public function delete($finId,$softDelete=true,$data=null){
		parent::delete($finId,$softDelete);
		if(!$softDelete){
			//$this->db->delete("trmpsitems",array("fin_mts_id"=>$finId));
			//DELETE BOM WO
			$this->db->delete("trwobomdetails",array("fin_wo_id"=>$finId));			
			//DELETE ACTIVITY
			$this->db->delete("trwoactivitydetails",array("fin_wo_id"=>$finId));
		}else{
			$this->db->query("update trwobomdetails set fst_active ='D' where fin_wo_id = ?",[$finId]);
			$this->db->query("update trwoactivitydetails set fst_active ='D' where fin_wo_id = ?",[$finId]);
		}        		
		return [
			"status"=>true,
			"message"=>"",
		];
	}
	
}
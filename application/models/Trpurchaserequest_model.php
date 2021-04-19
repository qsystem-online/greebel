<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchaserequest_model extends MY_Model {
	public $tableName = "trpurchaserequest";
	public $pkey = "fin_pr_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_pr_no',
			'label' => 'Purchase Request No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fin_req_department_id',
			'label' => 'Departemen',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
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
		$prefix = getDbConfig("purchase_request_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_pr_no) as max_id FROM trpurchaserequest where fst_pr_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}
	public function posting($finPurchaseRequestId){
	}

	public function unposting($finPurchaseRequestId){
	}

	public function getDataById($finPRId){
		$ssql = "SELECT a.*,b.fst_department_name as fst_req_department_name FROM " .$this->tableName. " a  
			LEFT JOIN departments b ON a.fin_req_department_id = b.fin_department_id 
			WHERE a.fin_pr_id = ? AND a.fst_active != 'D'";

		$qr = $this->db->query($ssql, [$finPRId]);
		$dataH = $qr->row();

		$ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name from trpurchaserequestitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
			WHERE a.fin_pr_id = ?";

		$qr = $this->db->query($ssql,[$finPRId]);        
		$dataDetails = $qr->result();

		$data = [
			"dataH" => $dataH,
			"dataDetails" => $dataDetails
		];

		return $data;
	}

	public function getDataHeaderById($finPRId){
		$ssql = "SELECT * FROM " .$this->tableName. "
			WHERE fin_pr_id = ?";
		$qr = $this->db->query($ssql, [$finPRId]);
		$dataH = $qr->row();
		return $dataH;
	}
	public function isEditable($finPRId){       
		/**
		 * FALSE CONDITION
		 * 1. kalau sudah publish tidak bisa diedit
		 * 
		 */
		$dataH = $this->getDataHeaderById($finPRId);
		if ($dataH->fdt_publish_datetime != null){
			return ["status"=>"FAILED","message"=>"Purchase request yang sudah di publish tidak bisa di rubah.."];
		}
		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	}

	public function deleteDetail($finPRId){
		$ssql ="delete from trpurchaserequestitems where fin_pr_id = ?";
		$this->db->query($ssql,[$finPRId]);
		throwIfDBError();        
	}
	public function update($data){
		//Delete Field yang tidak boleh berubah
		parent::update($data);        
	}

	public function delete($finPRId,$softDelete = true,$data=null){
		if ($softDelete){
			$ssql ="update trpurchaserequestitems set fst_active ='D' where fin_pr_id = ?";
			$this->db->query($ssql,[$finPRId]);
		}else{
			$ssql ="delete from trpurchaserequestitems where fin_pr_id = ?";
			$this->db->query($ssql,[$finPRId]);            
		}
		parent::delete($finPRId,$softDelete,$data);

		return ["status" => "SUCCESS","message"=>""];
	}


	public function getItemProcessList($itemType,$lineBusinessId,$stockCostType){
		//5 logistic ; other merchandise
		//stockCostType: stock|nonstock_umum|nonstock_pabrikasi
		$stockCostType = strtoupper($stockCostType);

		$ssql ="SELECT a.*,
				b.fst_pr_no,b.fin_req_department_id,d.fst_department_name as fst_req_department_name,
				b.fdt_pr_datetime,c.fst_item_code,c.fst_item_name 
			FROM trpurchaserequestitems a 
			INNER JOIN trpurchaserequest b on a.fin_pr_id = b.fin_pr_id        
			INNER JOIN msitems c on a.fin_item_id = c.fin_item_id       
			INNER JOIN departments d on b.fin_req_department_id = d.fin_department_id     
			WHERE b.fdt_publish_datetime <= now() AND
			a.fin_process_id IS null AND
			FIND_IN_SET(?,c.fst_linebusiness_id) AND
			b.fst_active != 'D'";

		if ($itemType == "LOGISTIC"){
			$ssql .= " AND c.fin_item_type_id = 5";
			if ($stockCostType == "STOCK"){
				$ssql .= " AND c.fbl_stock = true";
			}else if ($stockCostType == "NONSTOCK_UMUM"){
				$ssql .= " AND c.fbl_stock = false and d.fst_department_type ='Umum'";
			}else if ($stockCostType == "NONSTOCK_PABRIKASI"){
				$ssql .= " AND c.fbl_stock = false and d.fst_department_type ='Pabrikasi'";
			}
		}else{
			$ssql .= " AND c.fin_item_type_id != 5";
		}

		$qr = $this->db->query($ssql,[$lineBusinessId]);

		//echo $this->db->last_query();

		//echo "ITEM TYPE : $itemType";
		//echo $this->db->last_query();
		$rs = $qr->result();
		return $rs;
						
	}

	public function getDataVoucher($finPRId){
		$ssql ="SELECT a.*,b.fst_department_name as fst_req_department_name FROM trpurchaserequest a 
			INNER JOIN departments b on a.fin_req_department_id = b.fin_department_id 
			where a.fin_pr_id = ?";
		$qr = $this->db->query($ssql,[$finPRId]);
		$header = $qr->row_array();

		$ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name FROM trpurchaserequestitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			WHERE fin_pr_id = ?";

		$qr = $this->db->query($ssql,[$finPRId]);


		$details = $qr->result_array();

		return [
			"header"=>$header,
			"details"=>$details
		];
	}
	
	
	










	

	

	
	
	
	

	

	public function updateClosedStatus($finPurchaseReturnId){

		$ssql = "select * from trpurchasereturnitems where fin_purchasereturn_id = ? and fdb_qty > fdb_qty_out";
		$qr = $this->db->query($ssql,$finPurchaseReturnId);
		if ($qr->row() == null){
			//Transaksi Return Completed
			$ssql = "update trpurchasereturn set fdt_closed_datetime = now() , fbl_is_closed = 1, fst_closed_notes = 'AUTO - ".date("Y-m-d H:i:s") ."' where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}else{
			$ssql = "update trpurchasereturn set fdt_closed_datetime = null , fbl_is_closed = 0, fst_closed_notes = null where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}
	}
}



<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwoeinvoice_model extends MY_Model{
	public $tableName = "trwoeinvoice";
	public $pkey = "fin_woeinv_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_woeinv_no',
			'label' => 'Nomor Invoice',
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
		$prefix = getDbConfig("woe_inv_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_woeinv_no) as max_id FROM trwoeinvoice where fst_woeinv_no like '".$prefix.$tahun."%'");
		
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
	}
	
	public function getDataById($finWOEInvId){    
		
		$ssql ="SELECT a.*,
			b.fin_item_id,b.fst_unit,b.fst_wo_no,b.fdc_external_cost_per_unit,
			c.fst_item_code,c.fst_item_name,
			d.fst_relation_name as fst_supplier_name 
			FROM trwoeinvoice a 
			INNER JOIN trwo b ON a.fin_wo_id = b.fin_wo_id 
			INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id
			INNER JOIN msrelations d ON a.fin_supplier_id = d.fin_relation_id
			WHERE a.fin_woeinv_id = ?
		";

		$qr = $this->db->query($ssql,[$finWOEInvId]);
		$dataH = $qr->row();
		if ($dataH == null){
			return null;
		}
		
		$ssql ="SELECT a.*,b.fst_woein_no 
			FROM trwoeinvoiceitemin a 
			INNER JOIN trmemowoein b on a.fin_woein_id = b.fin_woein_id
			where a.fin_woeinv_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOEInvId]);
		$dataItemIn = $qr->result();


		$ssql ="SELECT a.*,
			b.fst_glaccount_name,c.fst_pcc_name,d.fst_department_name,
			e.fst_relation_name AS fst_customer_name,f.fst_project_name 
			FROM trwoeinvoiceitemcost a 
			INNER JOIN glaccounts b ON a.fst_glaccount_code = b.fst_glaccount_code
			LEFT JOIN msprofitcostcenter c ON a.fin_pcc_id = c.fin_pcc_id
			LEFT JOIN departments d ON a.fin_pc_divisi_id = d.fin_department_id
			LEFT JOIN msrelations e ON a.fin_pc_customer_id = e.fin_relation_id
			LEFT JOIN msprojects f ON a.fin_pc_project_id = f.fin_project_id
			WHERE a.fin_woeinv_id = ? AND a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOEInvId]);
		$dataItemCost = $qr->result();


		return [
			"dataH"=>$dataH,
			"dataItemIn"=>$dataItemIn,
			"dataItemCost"=>$dataItemCost,
		];
	}


	public function posting($finWOEInvId){	
		$this->load->model("glledger_model");
		$dataH = $this->getSimpleDataById($finWOEInvId);


		$ssql = "SELECT * FROM trwoeinvoiceitemin where fin_woeinv_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOEInvId]);
		$rs = $qr->result();
		//Flag woein fbl_inv  =1
		foreach ($rs as $rw) {
			$ssql = "Update trmemowoein set fbl_inv = 1 where fin_woein_id = ? ";
			$this->db->query($ssql,[$rw->fin_woein_id]);
		}

		//Total Hutang
		$totalHutang = $dataH->fdc_total;

		//Jurnal Biaya (Update kartu Hutang)
		// Biaya-Biaya
			//Hutang (AP)
			


		$dataJurnal = [];
		

		
		$ssql = "SELECT * FROM trwoeinvoiceitemcost where fin_woeinv_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOEInvId]);
		$rs = $qr->result();
		foreach ($rs as $rw) {
			$dataJurnal[] =[
				"fin_branch_id"=>$this->aauth->get_active_branch_id(),
				"fst_account_code"=>$rw->fst_glaccount_code,
				"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
				"fst_trx_sourcecode"=>"WOEI", //Work Order Eksternal Invoice
				"fin_trx_id"=>$dataH->fin_woeinv_id,
				"fst_reference"=>null,
				"fdc_debit"=> $rw->fdc_total * $dataH->fdc_exchange_rate_idr,
				"fdc_origin_debit"=>$rw->fdc_total,
				"fdc_credit"=> 0,
				"fdc_origin_credit"=> 0, 
				"fst_orgi_curr_code"=>$dataH->fst_curr_code,
				"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>$rw->fin_pcc_id,
				"fin_pc_divisi"=>$rw->fin_pc_divisi_id,
				"fin_pc_customer_id"=>$rw->fin_pc_customer_id,
				"fin_pc_project_id"=>$rw->fin_pc_project_id,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>""
			];

		}

		$accAPWOEksternal = getGLConfig("HUTANG_WORKORDER_EKSTERNAL");
		$dataJurnal[] =[
			"fin_branch_id"=>$this->aauth->get_active_branch_id(),
			"fst_account_code"=>$accAPWOEksternal,
			"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
			"fst_trx_sourcecode"=>"WOEI", //Work Order Eksternal Invoice
			"fin_trx_id"=>$dataH->fin_woeinv_id,
			"fst_reference"=>null,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $totalHutang * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=> $totalHutang, 
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_supplier_id,
			"fst_active"=>"A",
			"fst_info"=>""
		];


		$this->glledger_model->createJurnal($dataJurnal);         
	}

	public function isEditable(){	
	}

	public function unposting($finWOEInvId){	
		$this->load->model("glledger_model");
		$dataH = $this->getSimpleDataById($finWOEInvId);
		if ($dataH != null){
			$this->glledger_model->cancelJurnal("WOEI",$finWOEInvId);			
			$ssql = "SELECT * FROM trwoeinvoiceitemin where fin_woeinv_id = ? and fst_active ='A'";
			$qr = $this->db->query($ssql,[$finWOEInvId]);
			$rs = $qr->result();			
			foreach ($rs as $rw) {
				$ssql = "Update trmemowoein set fbl_inv = 0 where fin_woein_id = ? ";
				$this->db->query($ssql,[$rw->fin_woein_id]);
			}
		}		
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
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
			'field' => 'fst_purchasecost_no',
			'label' => lang('No Memo Biaya'),
			'rules' => 'required',
			'errors' => array(
				'required' => lang('%s tidak boleh kosong'),
			)
		];
		$rules[] = [
			'field' => 'fin_supplier_id',
			'label' => lang('Supplier'),
			'rules' => 'required',
			'errors' => array(
				'required' => lang('%s tidak boleh kosong'),
			)
		];
		$rules[] = [
			'field' => 'fin_po_id',
			'label' => lang('Purchase Order'),
			'rules' => 'required',
			'errors' => array(
				'required' => lang('%s tidak boleh kosong'),
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
		$ssql = "select a.*,b.fst_relation_name as fst_supplier_name from trpo a
			inner join msrelations b on a.fin_supplier_id = b.fin_relation_id
			where a.fbl_is_import = ? and a.fbl_cost_completed =  false and a.fst_active = 'A'";

		$qr = $this->db->query($ssql,[$isImport]);
		$rs = $qr->result();
		return $rs;        
	}

	public function posting($finPurchaseCostId){
		$this->load->model("glledger_model");

		$ssql ="select * from trpurchasecost where fin_purchasecost_id = ?";
		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		$dataH = $qr->row();

		if ($dataH == null){
			return [
				"status"=>"FAILED",
				"message"=>lang("ID Memo Biaya Pembelian tidak valid !")
			];
		}

		//GET Account Hutang biaya pembelian (Lokal / Import)
		$isImport = $dataH->fbl_is_import;        
		if($isImport){
			$accHutangBiayaPembelian = getGLConfig("AP_BIAYA_PEMBELIAN_IMPORT");
		}else{
			$accHutangBiayaPembelian = getGLConfig("AP_BIAYA_PEMBELIAN_LOKAL");
		}

		$ssql = "select * from trpurchasecostitems where fin_purchasecost_id = ?";

		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		$detailList = $qr->result();
		$arrJurnal = [];
		foreach($detailList as $dataD){
			$arrJurnal[] = [ //Biaya
				"fin_branch_id"=>$dataH->fin_branch_id,
				"fst_account_code"=>$dataD->fst_glaccount_code,
				"fdt_trx_datetime"=>$dataH->fdt_purchasecost_datetime,
				"fst_trx_sourcecode"=>"PCS",
				"fin_trx_id"=>$dataH->fin_purchasecost_id,
				"fst_trx_no"=>$dataH->fst_purchasecost_no,
				"fst_reference"=>null,
				"fdc_debit"=> $dataD->fdc_debet * $dataH->fdc_exchange_rate_idr,
				"fdc_origin_debit"=>$dataD->fdc_debet,
				"fdc_credit"=>$dataD->fdc_credit * $dataH->fdc_exchange_rate_idr,
				"fdc_origin_credit"=>$dataD->fdc_credit,
				"fst_orgi_curr_code"=>$dataH->fst_curr_code,
				"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>$dataD->fin_pcc_id,
				"fin_pc_divisi_id"=>$dataD->fin_pc_divisi_id,
				"fin_pc_customer_id"=>$dataD->fin_pc_customer_id,
				"fin_pc_project_id"=>$dataD->fin_pc_project_id,
				"fin_relation_id"=>null,
				"fst_active"=>"A"
			];
		}

		$fdcCredit = $dataH->fdc_total > 0 ? $dataH->fdc_total : 0;
		$fdcDebet = $dataH->fdc_total < 0 ? abs($dataH->fdc_total) : 0;
		

		$arrJurnal[] = [ //Hutang Biaya Pembelian 
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accHutangBiayaPembelian,
			"fdt_trx_datetime"=>$dataH->fdt_purchasecost_datetime,
			"fst_trx_sourcecode"=>"PCS",
			"fin_trx_id"=>$dataH->fin_purchasecost_id,
			"fst_trx_no"=>$dataH->fst_purchasecost_no,
			"fst_reference"=>null,
			"fdc_debit"=> $fdcDebet * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_debit"=>$fdcDebet,
			"fdc_credit"=>$fdcCredit * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=>$fdcCredit,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_pc_divisi_id"=>null,
			"fin_pc_customer_id"=>null,
			"fin_pc_project_id"=>null,
			"fin_relation_id"=>$dataH->fin_supplier_id,
			"fst_active"=>"A"
		];    
		
		$result = $this->glledger_model->createJurnal($arrJurnal);
		return $result;
	}

	public function unposting($finPurchaseCostId){
		$this->load->model("glledger_model");
		$unpostResult = $this->glledger_model->cancelJurnal("PCS",$finPurchaseCostId);                        
		return $unpostResult;
	}

	public function getDataById($finPurchaseCostId){
		$ssql = "SELECT a.*,b.fst_po_no FROM trpurchasecost a 
			INNER JOIN trpo b ON a.fin_po_id = b.fin_po_id  
			WHERE a.fin_purchasecost_id = ? and a.fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		$dataH = $qr->row();

		if ($dataH == null){
			return null;
		}

		$ssql ="SELECT a.*,b.fst_glaccount_name,c.fst_pcc_name FROM trpurchasecostitems a 
			INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code 
			INNER JOIN msprofitcostcenter c on a.fin_pcc_id = c.fin_pcc_id 
			WHERE fin_purchasecost_id = ?";

		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		$detailList = $qr->result();

		return[
			"dataH"=>$dataH,
			"detailList"=>$detailList,
		];
	}
	
	public function getDataHeaderById($finPurchaseCostId){
		$ssql = "select * from " .$this->tableName. " WHERE fin_purchasecost_id = ?";
		$qr = $this->db->query($ssql, [$finPurchaseCostId]);
		return $qr->row();

	}

	public function isEditable($finPurchaseCostId){       
		/**
		 * FALSE CONDITION
		 * 1. Status fbl_cost_completed sudah true, sudah dilakukan perhitungan harga pokok barang 
		 */
		$ssql ="SELECT b.fst_po_no,b.fbl_cost_completed FROM trpurchasecost a 
			INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
			WHERE a.fin_purchasecost_id = ? ";
		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		$data = $qr->row();
		if ($data == null){
			$resp =["status"=>"FAILED","message"=>"PO NOT FOUND !"];    
			return $resp;
		}
		if ($data->fbl_cost_completed){
			$resp =["status"=>"FAILED","message"=>sprintf(lang("Cost Completed for PO: %s"),$data->fst_po_no)];    
			return $resp;
		}


		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	}

	public function delete($finPurchaseCostId,$softDelete = true,$data=null){
		if ($softDelete){
			$ssql ="update trpurchasecostitems set fst_active ='D' where fin_purchasecost_id = ?";
			$this->db->query($ssql,[$finPurchaseCostId]);
		}else{
			$ssql ="delete from trpurchasecostitems where fin_purchasecost_id = ?";
			$this->db->query($ssql,[$finPurchaseCostId]);            
		}
		parent::delete($finPurchaseCostId,$softDelete,$data);
		return ["status" => "SUCCESS","message"=>""];
	}

	public function getDataVoucher($finPurchaseCostId){
		$ssql ="SELECT a.*,b.fst_relation_name AS fst_supplier_name,c.fst_curr_name,
			d.fst_po_no,d.fdt_po_datetime 
			FROM trpurchasecost a
			INNER JOIN msrelations b ON a.fin_supplier_id = b.fin_relation_id
			INNER JOIN mscurrencies c ON a.fst_curr_code = c.fst_curr_code 
			LEFT JOIN trpo d ON a.fin_po_id = d.fin_po_id
			WHERE fin_purchasecost_id = ?";
			
		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		$header = $qr->row_array();
		
		$ssql = "SELECT a.*,b.fst_glaccount_name FROM trpurchasecostitems a
			INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code
			WHERE fin_purchasecost_id = ?";

		$qr = $this->db->query($ssql,[$finPurchaseCostId]);
		var_dump($this->db->error());

		$details = $qr->result_array();

		return [
			"header"=>$header,
			"details"=>$details
		];
	}

	
}



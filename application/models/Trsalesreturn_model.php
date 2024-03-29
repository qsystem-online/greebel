<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsalesreturn_model extends MY_Model {
	public $tableName = "trsalesreturn";
	public $pkey = "fin_salesreturn_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_salesreturn_no',
			'label' => 'Sales Return No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fin_customer_id',
			'label' => 'Customer',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];        

		return $rules;
	}

	public function getDataById($finSalesReturnId){
		$ssql = "SELECT a.*,b.fst_relation_name 
			FROM trsalesreturn a 
			INNER JOIN msrelations b on a.fin_customer_id = b.fin_relation_id 
			WHERE a.fin_salesreturn_id = ? AND a.fst_active != 'D'";

		$qr = $this->db->query($ssql, [$finSalesReturnId]);
		$dataH = $qr->row();
		if ($dataH == null){
			return [
				"salesreturn"=>null,
				"salesreturn_details"=>[]
			];            
		}

		$ssql ="SELECT a.fin_lpbsalesreturn_id,a.fst_lpbsalesreturn_no FROM trlpbsalesreturn a where a.fin_lpbsalesreturn_id in ?";
		$qr = $this->db->query($ssql,[json_decode($dataH->fst_lpbsalesreturn_id_list)]);
		$rs = $qr->result();
		$dataH->fst_lpbsalesreturn_id_list = $rs;


		$ssql = "SELECT a.*,
			b.fst_inv_no,b.fdt_inv_datetime,
			c.fst_item_code,c.fst_item_name  
			FROM trsalesreturnitems a 
			LEFT JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id
			INNER JOIN msitems c on a.fin_item_id = c.fin_item_id 
			WHERE a.fin_salesreturn_id = ?";

		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$dataDetails = $qr->result();
		$data = [
			"salesreturn" => $dataH,
			"salesreturn_details" => $dataDetails
		];

		return $data;
	}

	public function getDataHeaderById($finSalesReturnId){
		$ssql = "SELECT * FROM " .$this->tableName. "
			WHERE fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql, [$finSalesReturnId]);
		$dataH = $qr->row();
		return $dataH;
	}
	

	public function GenerateSalesReturnNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("sales_return_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_salesreturn_no) as max_id FROM trsalesreturn where fst_salesreturn_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}
	
	public function getListSalesFaktur($isPaidFaktur,$fin_customer_id,$fstCurrCode,$search){        
		if ($isPaidFaktur) { //Faktur2 yang sudah pernah dilakukan pembayaran atau sudah pernah ada transaksi retur
			$ssql = "SELECT DISTINCT b.fin_inv_id,b.fst_inv_no,b.fdt_inv_datetime,b.fbl_is_vat_include,b.fdc_ppn_percent from trinvoiceitems a 
				INNER JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id 
				WHERE a.fdb_qty > fdb_qty_return
				AND b.fdc_total_paid > 0 
				AND b.fin_relation_id = ? AND b.fst_curr_code = ? AND b.fst_inv_no like ? AND b.fst_active != 'D' ";

		}else{ //Faktur2 yang belumh pernah dilakukan pembayaran dan belum pernah ada transaksi retur
			$ssql = "SELECT DISTINCT b.fin_inv_id,b.fst_inv_no,fdt_inv_datetime,b.fbl_is_vat_include,b.fdc_ppn_percent FROM trinvoiceitems a
				INNER JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id
				WHERE a.fdb_qty > fdb_qty_return
				AND b.fdc_total_paid = 0 
				AND b.fin_relation_id = ? AND b.fst_curr_code = ? AND b.fst_inv_no like ? AND b.fst_active != 'D' ";
		}

		$qr =$this->db->query($ssql,[(int) $fin_customer_id,$fstCurrCode,$search]);        
		$rs= $qr->result();
		return $rs;

		/*
		//Faktur2 yang pembayarannya belum lunas
		$ssql = "SELECT a.fin_inv_id,a.fst_inv_no FROM trinvoice a
			WHERE a.fdc_total > a.fdc_total_paid + a.fdc_total_return
			AND a.fin_relation_id = ? AND a.fst_active != 'D' ";
			
		$qr =$this->db->query($ssql,[(int) $fin_customer_id]);
		//echo $this->db->last_query();
		$rs= $qr->result();
		return $rs;
		*/
	}

	public function getSalesInvoice($finInvId){
		$ssql = "select a.* from trinvoice a where a.fin_inv_id = ?";
		$qr = $this->db->query($ssql,[$finInvId]);
		$rw = $qr->row();
		if($rw == null){
			return [
				"invoice"=>null,
				"invoiceDetails"=>[]            
			];
		}

		$ssql = "SELECT c.fin_item_id,c.fst_custom_item_name,e.fst_item_code,e.fst_item_name,c.fst_unit,c.fdc_price,c.fst_disc_item,c.fdc_disc_amount_per_item,b.fdb_ttl_qty_out,ifnull(d.fdb_ttl_qty_return,0) as fdb_ttl_qty_return  
			FROM trinvoice a 
			INNER JOIN (
				SELECT a.fin_inv_id,b.fin_salesorder_detail_id,sum(b.fdb_qty) as fdb_ttl_qty_out 
				FROM trsuratjalan a
				INNER JOIN trsuratjalandetails b on a.fin_sj_id = b.fin_sj_id                
				group by a.fin_inv_id,b.fin_salesorder_detail_id)  b on a.fin_inv_id = b.fin_inv_id            
			INNER JOIN trsalesorderdetails c on b.fin_salesorder_detail_id = c.fin_rec_id
			LEFT JOIN (
				SELECT b.fin_inv_id, a.fin_salesorder_detail_id,sum(a.fdb_qty) as fdb_ttl_qty_return  FROM trsalesreturnitems a 
				INNER JOIN trsalesreturn b on a.fin_salesreturn_id = b.fin_salesreturn_id
				GROUP BY b.fin_inv_id,a.fin_salesorder_detail_id
			) d on a.fin_inv_id = d.fin_inv_id and c.fin_rec_id = d.fin_salesorder_detail_id            
			INNER JOIN msitems e on c.fin_item_id = e.fin_item_id
			WHERE a.fin_inv_id = ? 
			AND b.fdb_ttl_qty_out > ifnull(d.fdb_ttl_qty_return,0)";

		$qr = $this->db->query($ssql,[$finInvId]); 
			   
		$rs = $qr->result();
		return [
			"invoice"=>$rw,
			"invoiceDetails"=>$rs            
		];

	}


	public function getSummaryReturnByInvoice($finInvId){

		$ssql = "SELECT b.fin_salesorder_detail_id,b.fdb_ttl_qty_out,ifnull(c.fdb_ttl_qty_return,0) as fdb_ttl_qty_return  FROM trinvoice a
			INNER JOIN ( 
				select a.fin_inv_id,b.fin_salesorder_detail_id,sum(b.fdb_qty) as fdb_ttl_qty_out from trsuratjalan a 
				inner join trsuratjalandetails b on a.fin_sj_id = b.fin_sj_id
				group by a.fin_inv_id,b.fin_salesorder_detail_id
			) b on a.fin_inv_id = b.fin_inv_id
			LEFT JOIN (
				select a.fin_inv_id,b.fin_salesorder_detail_id,sum(b.fdb_qty) as fdb_ttl_qty_return from trsalesreturn a 
				inner join trsalesreturnitems b on a.fin_salesreturn_id = b.fin_salesreturn_id
				group by a.fin_inv_id,b.fin_salesorder_detail_id
			) c on a.fin_inv_id = c.fin_inv_id
			WHERE a.fin_inv_id = ?";        

		$qr = $this->db->query($ssql,[(int)$finInvId]);
		$rs = $qr->result();
		$result =[];
		foreach($rs as $rw){
			$result[$rw->fin_salesorder_detail_id] = $rw;
		}
		return $result;
	}

	public function posting($finSalesReturnId){
		$this->load->model("glledger_model");
		//$this->load->model("trinventory_model");

		$ssql ="select * from trsalesreturn where fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$dataH = $qr->row();

		$ssql ="select * from trsalesreturnitems where fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$dataDetails = $qr->result();

		/**		
			Jurnal Return
			--------------------------------------------
			Misalkan retur 10% dari invoice
			Retur Penjualan		Rp.   100.000,-
			PPN			Rp.     9.000,-
				Discount				Rp.   10.000,-
				Potongan				Rp.   10.000,-	
				Retur Belum Realisasi	Rp.   89.000,-
		 */

		//JURNAL RETURN
		$accReturnPenjualan = getGLConfig("RETUR_PENJUALAN");
		$accPPN = getGLConfig("PPN_KELUARAN");
		$accDisc = getGLConfig("INV_DISC");
		$accPiutang = getGLConfig("AR_DAGANG_LOKAL");
		$accRetunBelumRealisasi = getGLConfig("RETUR_BELUM_REALISASI");
		$accPotonganReturn = getGLConfig("POTONGAN_RETURN_PENJUALAN");
		

		$ttlReturnPenjualan = 0;
		$ttlPpn = 0;
		$ttlDisc = 0;
		$ttlPiutang = 0;   
		$ttlPotongan =0;     
		
		foreach($dataDetails as $dataD){			
			//$ttlReturnPenjualan += $dataD->fdc_total;
			$ttlReturnPenjualan += $dataD->fdc_dpp_amount;
			$ttlPpn += $dataD->fdc_ppn_amount;
			$discAmount = $dataD->fdb_qty * $dataD->fdc_disc_amount_per_item;
			$ttlDisc += $discAmount;
			//$ttlPiutang += $dataD->fdc_total + $dataD->fdc_ppn_amount - $discAmount;
			$ttlPiutang += $dataD->fdc_dpp_amount + $dataD->fdc_ppn_amount - $discAmount -$dataD->fdc_potongan;
			$ttlPotongan += $dataD->fdc_potongan;
		}

		

		$dataJurnal = [];
		$dataJurnal[] =[ //Retur Penjualan
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accReturnPenjualan,
			"fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
			"fst_trx_sourcecode"=>"SRT",
			"fin_trx_id"=>$dataH->fin_salesreturn_id,
			"fst_trx_no"=>$dataH->fst_salesreturn_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> $ttlReturnPenjualan * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_debit"=>$ttlReturnPenjualan,
			"fdc_credit"=>0,
			"fdc_origin_credit"=>0,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"RETURN PENJUALAN"
		];

		$dataJurnal[] =[ //PPN
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accPPN,
			"fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
			"fst_trx_sourcecode"=>"SRT",
			"fin_trx_id"=>$dataH->fin_salesreturn_id,
			"fst_trx_no"=>$dataH->fst_salesreturn_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> $ttlPpn * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_debit"=>$ttlPpn,
			"fdc_credit"=>0,
			"fdc_origin_credit"=>0,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"PPN"
		];

		$dataJurnal[] =[ //Disc
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accDisc,
			"fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
			"fst_trx_sourcecode"=>"SRT",
			"fin_trx_id"=>$dataH->fin_salesreturn_id,
			"fst_trx_no"=>$dataH->fst_salesreturn_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$ttlDisc * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=>$ttlDisc,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"DISC"
		];

		$dataJurnal[] =[ //Potongan
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accPotonganReturn,
			"fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
			"fst_trx_sourcecode"=>"SRT",
			"fin_trx_id"=>$dataH->fin_salesreturn_id,
			"fst_trx_no"=>$dataH->fst_salesreturn_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$ttlPotongan * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=>$ttlPotongan,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"POTONGAN RETURN"
		];

		$dataTmp =[ //Piutang / Retur belum realisasi
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accRetunBelumRealisasi,
			"fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
			"fst_trx_sourcecode"=>"SRT",
			"fin_trx_id"=>$dataH->fin_salesreturn_id,
			"fst_trx_no"=>$dataH->fst_salesreturn_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$ttlPiutang * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=>$ttlPiutang,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"RETURN BELUM REALISASI"
		];
		
		$dataJurnal[] = $dataTmp;
		$result = $this->glledger_model->createJurnal($dataJurnal);        
	}

	public function unposting($finSalesReturnId){
		$this->load->model("glledger_model");
		
		
		$ssql ="select * from trsalesreturn where fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$dataH = $qr->row();

		if($dataH == null){
			throw new CustomException(lang("invalid sales return ID"),3003,"FAILED",$finSalesReturnId);
		}

		$this->glledger_model->cancelJurnal("SRT",$finSalesReturnId);			
	}

	public function getItemListByInv($finInvId,$search="%"){
		if ($finInvId != ""){
			$ssql ="SELECT a.fin_rec_id as fin_inv_detail_id,a.fin_item_id,b.fst_item_code,b.fst_item_name,a.fst_custom_item_name,a.fst_unit,(a.fdb_qty - a.fdb_qty_return) as fdb_qty_max_return,a.fdc_price,a.fst_disc_item,fdc_disc_amount_per_item 
				FROM trinvoiceitems  a    
				INNER JOIN msitems b on a.fin_item_id =b.fin_item_id 
				WHERE a.fin_inv_id = ? AND a.fdb_qty > a.fdb_qty_return
				AND a.fst_custom_item_name like ?";
			
			$qr = $this->db->query($ssql,[$finInvId,$search]);
			$rs = $qr->result();            
			return $rs;
		}else{
			$ssql = "SELECT a.fin_item_id,a.fst_item_code,a.fst_item_name,a.fst_item_name as fst_custom_item_name from msitems a 
			where (a.fst_item_code like ? or a.fst_item_name like ?) 
			and a.fst_active ='A'";

			$qr = $this->db->query($ssql,[$search,$search]);
			$rs = $qr->result();            
			return $rs;
		}
	}

	public function isEditable($finSalesReturnId){
	   
		/**
		 * Tidak bisa di hapus kalau sudah ada penerimaan barang
		 * Tidak bisa di hapus jika invoice sudah di bayar
		 * tidak bisa di hapus bila voucer retur telah di gunakan
		 *          
		 */
		$ssql = "select * from trsalesreturnitems where fdb_qty_lpb > 0 and fin_salesreturn_id = ? and fst_active =! 'D'";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$rw = $qr->row();
		if($rw != null){
			throw new CustomException(lang("Transaksi tidak bisa dirubah karena sudah ada penerimaan barang"),3003,"FAILED",null);
		}
		
		/*
		$ssql = "SELECT a.*,b.fst_inv_no from trsalesreturnitems a 
			inner join trinvoice b on a.fin_inv_id = b.fin_inv_id 
			where b.fdc_total_paid > 0
			and a.fin_salesreturn_id = ? 
			and a.fst_active != 'D'";            
		$rw = $qr->row();
		if($rw != null){
			throw new CustomException(sprintf(lang("Transaksi tidak bisa dirubah karena invoice %s sudah ada pembayaran"),$rw->fst_inv_no),3003,"FAILED",null);
		}
		*/

		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	}

	public function deleteDetail($finSalesReturnId){
		$ssql ="DELETE FROM trsalesreturnitems WHERE fin_salesreturn_id = ?";
		$this->db->query($ssql,[$finSalesReturnId]);
		throwIfDBError();
	}

	public function delete($finSalesReturnId,$softDelete = true,$data=null){
		if ($softDelete){
			$ssql ="update trsalesreturnitems set fst_active ='D' where fin_salesreturn_id = ?";
			$this->db->query($ssql,[$finSalesReturnId]);
		}else{
			$ssql ="delete from trsalesreturnitems where fin_salesreturn_id = ?";
			$this->db->query($ssql,[$finSalesReturnId]);            
		}
		parent::delete($finSalesReturnId,$softDelete,$data);

		return ["status" => "SUCCESS","message"=>""];
	}

	public function updateClosedStatus($finSoReturnId){
		$ssql = "select * from trsalesreturnitems where fin_salesreturn_id = ? and fdb_qty > fdb_qty_lpb";
		$qr = $this->db->query($ssql,$finSoReturnId);
		
		if ($qr->row() == null){
			//Penerimaan lengkap close sales return
			$ssql = "update trsalesreturn set fdt_closed_datetime = now() , fbl_is_closed = 1, fst_closed_notes = 'AUTO - ".date("Y-m-d H:i:s") ."' where fin_salesreturn_id = ?";
			$this->db->query($ssql,[$finSoReturnId]);
		}else{
			$ssql = "update trsalesreturn set fdt_closed_datetime = null , fbl_is_closed = 0, fst_closed_notes = null where fin_salesreturn_id = ?";
			$this->db->query($ssql,[$finSoReturnId]);
		}
	}

	public function getSalesReturnNonFakturList($finCustId,$fstCurrCode){
		$ssql = "SELECT a.* from trsalesreturn a 
			where a.fdc_total > a.fdc_total_claimed
			AND fin_customer_id = ?
			AND fst_curr_code = ?";
		$qr = $this->db->query($ssql,[$finCustId,$fstCurrCode]);
		return $qr->result();
		
	}


	public function getDataVoucher($finSalesReturnId){
		//$data = $this->getDataById($finSalesReturnId);
		
		$ssql ="SELECT a.*,b.fst_relation_name as fst_cust_name,c.fst_curr_name FROM trsalesreturn a
			INNER JOIN msrelations b on a.fin_customer_id = b.fin_relation_id  
			INNER JOIN mscurrencies c on a.fst_curr_code = c.fst_curr_code 
			where a.fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$header = $qr->row_array();

		$ssql ="SELECT a.*,b.fst_item_code,fst_inv_no FROM trsalesreturnitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id   
			LEFT JOIN trinvoice c on a.fin_inv_id = c.fin_inv_id  
			where a.fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$details = $qr->result_array();

		
		return [
			"header"=>$header,
			"details"=>$details,
		];        
	}
}



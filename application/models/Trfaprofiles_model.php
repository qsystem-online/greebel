<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class trfaprofiles_model extends MY_Model{
	public $tableName = "trfaprofiles";
	public $pkey = "fin_fa_profile_id";

	public function __construct(){
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0){
		$rules = [];

		
		$rules[] = [
			'field' => 'fst_fa_profile_name',
			'label' => 'Profile Name',
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
   
	public function generateNo($trDate = null) {      
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("fa_profile_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_fa_profile_no) as max_id FROM trfaprofiles where fst_fa_profile_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

	public function getDataHeader($finFAProfileId){
		$ssql = "SELECT * FROM trfaprofiles where fin_fa_profile_id = ?";
		$qr = $this->db->query($ssql,[$finFAProfileId]);
		$rw = $qr->row();
		return $rw;
	}

	public function getDataById($finFAProfileId){
		$ssql = "SELECT a.* 
			FROM trfaprofiles a
			where fin_fa_profile_id = ?";

		$qr = $this->db->query($ssql, [$finFAProfileId]);
		$rw = $qr->row();

		$rw->fin_lpbpurchase_id = NULL;
		$rw->fst_lpbpurchase_no = NULL;
		$rw->fin_fa_disposal_id = NULL;
		$rw->fst_fa_disposal_no = NULL;
		$rw->fst_fa_disposal_code = NULL;
		$rw->fst_fa_disposal_name = NULL;
		$rw->fst_lpbpurchase_item_name = NULL;
		
		
		if ($rw->fst_type == "PURCHASE"){
			$ssql = "SELECT b.fin_lpbpurchase_id,b.fst_lpbpurchase_no,a.fst_custom_item_name FROM trlpbpurchaseitems a 
				INNER JOIN trlpbpurchase b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id 
				WHERE fin_rec_id = ?";

			$qr = $this->db->query($ssql,[$rw->fin_lpbpurchase_detail_id]);
			$lpbPurchase = $qr->row();
			if ($lpbPurchase != null){
				$rw->fin_lpbpurchase_id = $lpbPurchase->fin_lpbpurchase_id;
				$rw->fst_lpbpurchase_no = $lpbPurchase->fst_lpbpurchase_no;
				$rw->fst_lpbpurchase_item_name = $lpbPurchase->fst_custom_item_name;
			}
		}else if ($rw->fst_type == "MUTASI"){
			$ssql = "SELECT b.fin_fa_disposal_id,b.fst_fa_disposal_no,c.fst_fa_profile_code,c.fst_fa_profile_name FROM trfadisposalitems a
				INNER JOIN trfadisposal b on a.fin_fa_disposal_id = b.fin_fa_disposal_id
				INNER JOIN trfaprofilesitems c on a.fin_fa_profile_detail_id = c.fin_rec_id
				WHERE a.fin_rec_id = ?";
			$qr = $this->db->query($ssql,[$rw->fin_fa_disposal_detail_id]);
			$disposal = $qr->row();
			if ($disposal != null){
				$rw->fin_fa_disposal_id = $disposal->fin_fa_disposal_id;
				$rw->fst_fa_disposal_no = $disposal->fst_fa_disposal_no;
				$rw->fst_fa_disposal_code = $disposal->fst_fa_profile_code;
				$rw->fst_fa_disposal_name = $disposal->fst_fa_profile_name;
		
			}
		}

		$ssql = "SELECT * FROM trfaprofilesitems where fin_fa_profile_id = ?";
		$qr = $this->db->query($ssql, [$finFAProfileId]);
		$rs = $qr->result();
		return [
			"header"=>$rw,
			"details"=>$rs
		];
	}

	public function isEditable($finFAProfileId){
		//Throw customexception if not editable
		//Tidak bisa di edit bila ada yang sudah di jurnal pada trfadeprecard

		$ssql = "SELECT * FROM trfadeprecard where fin_fa_profile_id = ? and fbl_jurnal = 1 order by fst_period";
		$qr=$this->db->query($ssql,[$finFAProfileId]);
		$rw = $qr->row();
		if ($rw != null){
			throw new CustomException("Profile sudah dilakukan proses jurnal sejak $rw->fst_period",3003,"FAILED",[]);            
		}


		$ssql = "SELECT * FROM trfaprofilesitems where fin_fa_profile_id = ? and fbl_disposal = 1";
		$qr=$this->db->query($ssql,[$finFAProfileId]);
		$rw = $qr->row();
		if ($rw != null){
			throw new CustomException("Profile sudah didisposal",3003,"FAILED",[]);            
		}
		return true;            
	}

	public function posting_bac($finFAProfileId){

		//Create Card only if no reference from purchase invoice

		$this->load->model("trfadeprecard_model");

		$ssql = "SELECT * FROM trfaprofiles where fin_fa_profile_id = ?";
		$qr = $this->db->query($ssql,[$finFAProfileId]);
		$dataH=$qr->row();
		if ($dataH == null){
			throw new CustomException("Invalid FA profile ID",404,"INVALID ID",[]);
		}

		if ($dataH->fin_lpbpurchase_detail_id != null){
			return;
		}
		$fdtStartSystem = getDbConfig("start_program");
		
		//Create FA Card from aquisisi to startsystem
		$aquisitionPeriod = getPeriod($dataH->fdt_aquisition_date);
		$startSystemPeriod = getPeriod($fdtStartSystem);
		

		$ssql ="SELECT * FROM trfaprofilesitems where fin_fa_profile_id = ?";
		$qr = $this->db->query($ssql,[$finFAProfileId]);
		

		$details = $qr->result();
		
		$i=0;
		$processPeriod = $aquisitionPeriod;
		while ($processPeriod != $startSystemPeriod ){
			$i++;
			if ($i > $dataH->fin_life_time_month){
				break;
			}
			

			//cek tahunan atau bulanan
			$depreAmountPerMonth = ($dataH->fdc_aquisition_price - $dataH->fdc_residu_value) /$dataH->fin_life_time_month;
			if ($dataH->fst_depre_period == "monthly"){
				foreach($details as $detail){
					$data =[
						"fin_fa_profile_id"=>$dataH->fin_fa_profile_id,
						"fst_fa_profile_code"=>$detail->fst_fa_profile_code,
						"fst_period"=>$processPeriod,
						"fdc_depre_amount"=>$depreAmountPerMonth,
						"fst_active"=>"A",
					];
					$this->trfadeprecard_model->insert($data);
				}
			}else{ //year
				//$currentPeriod = getPeriod();
				if (periodIsEndOfYear($processPeriod)){
					$diffPeriod = diffPeriod($startSystemPeriod,$processPeriod);
					if ( $diffPeriod > 12 ){
						$diffPeriod = 12;
					}
				}
				$depreAmountPerMonth = $depreAmountPerMonth * $diffPeriod;
				foreach($details as $detail){
					$data =[
						"fin_fa_profile_id"=>$dataH->fin_fa_profile_id,
						"fst_fa_profile_code"=>$detail->fst_fa_profile_code,
						"fst_period"=>$processPeriod,
						"fdc_depre_amount"=>$depreAmountPerMonth,
						"fst_active"=>"A",
					];
					$this->trfadeprecard_model->insert($data);
				}
			}            
			$processPeriod = nextPeriod($processPeriod);
		}
		
	}


	public function posting($finFAProfileId){

		/**
		 * create record card jika periode akuisisi lebih kecil dari tgl start system
		 * 
		 */
		$this->load->model("trfadeprecard_model");
		$ssql = "SELECT * FROM trfaprofiles where fin_fa_profile_id = ?";
		$qr = $this->db->query($ssql,[$finFAProfileId]);
		$dataH=$qr->row();
		if ($dataH == null){
			throw new CustomException("Invalid FA profile ID",404,"INVALID ID",[]);
		}
		
		if ($dataH->fst_type == "PURCHASE"){
			$ssql = "UPDATE trlpbpurchaseitems set fbl_fa_profiles = 1 where fin_rec_id = ?";
			$this->db->query($ssql,[$dataH->fin_lpbpurchase_detail_id]);
		}else if ($dataH->fst_type == "MUTASI"){
			
			$ssql = "UPDATE trfadisposalitems set fbl_fa_profiles = 1 where fin_rec_id = ?";
			$this->db->query($ssql,[$dataH->fin_fa_disposal_detail_id]);

			$ssql = "SELECT c.fst_account_code,c.fin_branch_id FROM trfadisposalitems a  
				INNER JOIN trfaprofilesitems b on a.fin_fa_profile_detail_id = b.fin_rec_id
				INNER JOIN trfaprofiles c on b.fin_fa_profile_id = c.fin_fa_profile_id				
				where a.fin_rec_id = ?";
			$qr =$this->db->query($ssql,[$dataH->fin_fa_disposal_detail_id]);
			$rwProfile = $qr->row();

			//Create Jurnal
			$this->load->model("glledger_model");
			$dataJurnal = [];
			/**
			 * Asset di cabang Tujuan (Nilai Buku)
			 * 		Asset di cabang assal
			 * */
			$dataJurnal[] =[ 
				"fin_branch_id"=>$dataH->fin_branch_id,
				"fst_account_code"=>$dataH->fst_account_code,
				"fdt_trx_datetime"=>$dataH->fdt_aquisition_date,
				"fst_trx_sourcecode"=>"PFA", //Profiling Fixed Asset
				"fin_trx_id"=>$dataH->fin_fa_profile_id,
				"fst_trx_no"=>$dataH->fst_fa_profile_no,
				"fst_reference"=>$dataH->fst_notes,
				"fdc_debit"=> $dataH->fdc_aquisition_price,
				"fdc_origin_debit"=>$dataH->fdc_aquisition_price,
				"fdc_credit"=>0,
				"fdc_origin_credit"=>0,
				"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>"ACC Asset tujuan",
			];
			$dataJurnal[] =[ 
				"fin_branch_id"=>$rwProfile->fin_branch_id,
				"fst_account_code"=>$rwProfile->fst_account_code,
				"fdt_trx_datetime"=>$dataH->fdt_aquisition_date,
				"fst_trx_sourcecode"=>"PFA", //Profiling Fixed Asset
				"fin_trx_id"=>$dataH->fin_fa_profile_id,
				"fst_trx_no"=>$dataH->fst_fa_profile_no,
				"fst_reference"=>$dataH->fst_notes,
				"fdc_debit"=> 0,
				"fdc_origin_debit"=>0,
				"fdc_credit"=>$dataH->fdc_aquisition_price,
				"fdc_origin_credit"=>$dataH->fdc_aquisition_price,
				"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>"ACC Asset Asal",
			];
			$this->glledger_model->createJurnal($dataJurnal);
		}else{
			$fdtStartSystem = getDbConfig("start_program");    
			//Create FA Card from aquisisi to startsystem
			$aquisitionPeriod = getPeriod($dataH->fdt_aquisition_date,$dataH->fst_depre_period);
			$startSystemPeriod = getPeriod($fdtStartSystem,$dataH->fst_depre_period);                
			$lastDeprecatePeriod = addPeriod($aquisitionPeriod,$dataH->fin_life_time_period,$dataH->fst_depre_period);
			
			$processPeriod = $aquisitionPeriod;
			//$processPeriod < $startSystemPeriod, karena start system tgl belum di closing periodnya
			//while ($processPeriod < $startSystemPeriod  && $processPeriod <= $lastDeprecatePeriod){
			while ($processPeriod <= $lastDeprecatePeriod){
				if ($processPeriod >= $startSystemPeriod){                
					$resp  = dateIsLock(getPeriodDate($processPeriod));
					if($resp["status"] != "SUCCESS"){
						$lockDate = $resp["data"]["lock_date"];
						throw new CustomException("Process Period $processPeriod sudah dilock $lockDate",3003,"FAILED",[]);
					}                
				}
				if ($processPeriod < $startSystemPeriod){       
					$this->trfadeprecard_model->deprecateAsset($finFAProfileId,$processPeriod);                               
				}
				$processPeriod = nextPeriod($processPeriod,$dataH->fst_depre_period);            
			}
		}


	}


	public function unposting($finFAProfileId){		
		$ssql = "DELETE FROM trfadeprecard where fin_fa_profile_id =?";
		$qr=$this->db->query($ssql,[$finFAProfileId]);
		throwIfDBError();

		$ssql = "SELECT * FROM trfaprofiles where fin_fa_profile_id = ?";
		$qr = $this->db->query($ssql,[$finFAProfileId]);
		$dataH=$qr->row();
		if ($dataH == null){
			throw new CustomException("Invalid FA profile ID",404,"INVALID ID",[]);
		}

		if ($dataH->fst_type == "PURCHASE"){
			$ssql = "UPDATE trlpbpurchaseitems set fbl_fa_profiles = 0 where fin_rec_id = ?";
			$this->db->query($ssql,[$dataH->fin_lpbpurchase_detail_id]);
			throwIfDBError();
		}else if ($dataH->fst_type == "MUTASI"){
			$this->load->model("glledger_model");
			$this->glledger_model->cancelJurnal("PFA",$finFAProfileId);
			
			$ssql = "UPDATE trfadisposalitems set fbl_fa_profiles = 0 where fin_rec_id = ?";
			$this->db->query($ssql,[$dataH->fin_fa_disposal_detail_id]);
			throwIfDBError();
		}
	}

	public function deleteDetails($finFAProfileId){
		$ssql = "delete from trfaprofilesitems where fin_fa_profile_id = ?";
		$this->db->query($ssql,[$finFAProfileId]);
		throwIfDBError();

		$ssql = "delete from trfadeprecard where fin_fa_profile_id = ?";
		$this->db->query($ssql,[$finFAProfileId]);
		throwIfDBError();
	}

	public function delete($finFAProfileId,$softDelete=true,$data=null){
		if ($softDelete == true){
			$ssql = "UPDATE FROM trfaprofilesitems set fst_active ='D' where fin_fa_profile_id = ?";
			$this->db->query($ssql,[$finFAProfileId]);
		}else{
			$ssql = "DELETE FROM trfaprofilesitems where fin_fa_profile_id = ?";
			$this->db->query($ssql,[$finFAProfileId]);
		}

	}
	
}
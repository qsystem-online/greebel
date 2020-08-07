<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trfadisposal_model extends MY_Model{
	public $tableName = "trfadisposal";
	public $pkey = "fin_fa_disposal_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_fa_disposal_no',
			'label' => 'Fixed Asset Disposal No',
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

	public function getDataById($finFADisposalId){
		$ssql ="SELECT a.*,b.fst_relation_name as fst_customer_name,c.fst_curr_name as fst_sell_curr_name  FROM trfadisposal a 
			LEFT JOIN msrelations b on a.fin_customer_id = b.fin_relation_id 
			LEFT JOIN mscurrencies c on a.fst_sell_curr_code = c.fst_curr_code 
			WHERE a.fin_fa_disposal_id = ?";
		$qr = $this->db->query($ssql,[$finFADisposalId]);		
		$dataH = $qr->row();
		if ($dataH == null){
			return [
				"header"=>null,
				"details"=>null
			];
		}

		$ssql = "SELECT a.*,b.fst_fa_profile_code,b.fst_fa_profile_name FROM trfadisposalitems a
			INNER JOIN trfaprofilesitems b on a.fin_fa_profile_detail_id = b.fin_rec_id 			
			WHERE a.fin_fa_disposal_id = ?";
		
		$qr = $this->db->query($ssql,[$finFADisposalId]);
		
		$details = $qr->result();
		return [
			"header"=>$dataH,
			"details"=>$details
		];
	}

	public function getDataHeader($finFADisposalId){
		
		$ssql ="SELECT * FROM trfadisposal where fin_fa_disposal_id = ? ";
		$qr = $this->db->query($ssql,[$finFADisposalId]);
		return $qr->row();
	}

	public function generateTransactionNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("fa_disposal_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_fa_disposal_no) as max_id FROM trfadisposal where fst_fa_disposal_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
	}

	public function posting($finFADisposalId){
		$this ->load->model("trfaprofilesitems_model");
		$this->load->model("glledger_model");

		$ssql ="SELECT * FROM trfadisposal where fin_fa_disposal_id = ? and fst_active='A'";
		$qr = $this->db->query($ssql,[$finFADisposalId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException("Invalid FA Disposal ID",404,"FAILED",[]);            
		}

		$ssql = "SELECT * FROM trfadisposalitems where fin_fa_disposal_id = ?";
		$qr = $this->db->query($ssql,[$finFADisposalId]);
		$details = $qr->result();

		//DESTROY,JUAL,MUTASI
		//$dataH->fst_disposal_type;
		/** JURNAL 
		 * DESTROY
		 * =================
		 * ACC PENYUSUTAN
		 * RUGI / RABA
		 *      NILAI AKUISISI ASSET (BANGUNAN / KENDARAAN)
		 *       
		 * JUAL
		 * =================
		 * ACC PENYUSUTAN
		 * PIUTANG PENJUALAN ASSET
		 * Rugi (bila penjualan di bawan nilai buku)
		 *      NILAI AKUISISI ASSET (BANGUNAN / KENDARAAN)
		 *      Raba (bila Penjualan diatas nilai buku)
		 *      PPN
		 *      
		 * MUTASI OUT
		 * ACC PENYUSUTAN
		 *      ACC ASSET (BANGUNAN / KENDARAAN) (branch asal - nilai susut)
		 * 
		 * 
		 * MUTASI IN
		 * ACC ASSET (BANGUNAN / KENDARAAN) (branch tujuan - nilai buku)
		 *      ACC ASSET (BANGUNAN / KENDARAAN) (branch asal - nilai buku)
		 **/

		
		$accFixedAsset = "";
		$accDeprecated ="";
		$dataJurnal = [];
		$accRugiDestroyFixedAsset = getGLConfig("RUGI_DESTROY_FIXED_ASSET");
		$accRugiSellFixedAsset = getGLConfig("RUGI_PENJUALAN_FIXED_ASSET");
		$accRabaSellFixedAsset = getGLConfig("RABA_PENJUALAN_FIXED_ASSET");
		$accPpnFixedAsset = getGLConfig("PPN_PENJUALAN_FIXED_ASSET");
		$accPiutangSellFixedAsset = getGLConfig("PIUTANG_PENJUALAN_FIXED_ASSET");
		
		$dataJurnal = [];

		foreach($details as $dataD){
			$ssql = "UPDATE trfaprofilesitems set fbl_disposal = 1 WHERE fin_rec_id = ?";
			$this->db->query($ssql,[$dataD->fin_fa_profile_detail_id]);

			$profileInfo = $this->trfaprofilesitems_model->getInfoById($dataD->fin_fa_profile_detail_id);
			if ($profileInfo == null){
				throw new CustomException("Invalid FA Profile Detail id",404,"FAILED",[]);
			}
			$accFixedAsset = $profileInfo->fst_account_code;
			$accDeprecated = $profileInfo->fst_accum_account_code;
		   
			if ($dataH->fst_disposal_type == "DESTROY"){
				$rugiDestroyAmount = $dataD->fdc_aquisition_price - $dataD->fdc_deprecated_amount;
				//Penyusutan
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accDeprecated,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> $dataD->fdc_deprecated_amount,
					"fdc_origin_debit"=>$dataD->fdc_deprecated_amount,
					"fdc_credit"=>0,
					"fdc_origin_credit"=>0,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Penyusutan",
				];
				//Kerugian FA Destroy
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accRugiDestroyFixedAsset,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> $rugiDestroyAmount,
					"fdc_origin_debit"=>$rugiDestroyAmount,
					"fdc_credit"=>0,
					"fdc_origin_credit"=>0,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Rugi Destroy FA",
				];
				//Akuisisi asset
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accFixedAsset,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> 0,
					"fdc_origin_debit"=>0,
					"fdc_credit"=>$dataD->fdc_aquisition_price,
					"fdc_origin_credit"=>$dataD->fdc_aquisition_price,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Fixed Asset Acc",
				];				
			}else if ($dataH->fst_disposal_type == "JUAL"){

				$kurs = $dataH->fdc_sell_exchange_rate_idr;

				$hargaJual = $dataD->fdc_sell_price;
				$hargaJual = $hargaJual*$kurs;

				$ppnJual = ($dataH->fdc_ppn_percent /100) * $hargaJual;

				$hargaJualPpn = $hargaJual + $ppnJual;
				$rabaRugiJual = $hargaJual - ($dataD->fdc_aquisition_price - $dataD->fdc_deprecated_amount );

				//Penyusutan
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accDeprecated,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> $dataD->fdc_deprecated_amount,
					"fdc_origin_debit"=>$dataD->fdc_deprecated_amount,
					"fdc_credit"=>0,
					"fdc_origin_credit"=>0,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Penyusutan",
				];

				//Acc PIutang			
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accPiutangSellFixedAsset,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> $hargaJualPpn,
					"fdc_origin_debit"=>$hargaJualPpn,
					"fdc_credit"=>0,
					"fdc_origin_credit"=>0,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Acc Piutang",
				];

				//Akuisisi asset
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accFixedAsset,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> 0,
					"fdc_origin_debit"=>0,
					"fdc_credit"=>$dataD->fdc_aquisition_price,
					"fdc_origin_credit"=>$dataD->fdc_aquisition_price,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Fixed Asset Acc",
				];

				//PPN
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accPpnFixedAsset,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> 0,
					"fdc_origin_debit"=>0,
					"fdc_credit"=>$ppnJual,
					"fdc_origin_credit"=>$ppnJual,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Fixed Asset Acc",
				];

				//Raba  Rugi Penjualan FA
				$rabaRugi =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>null,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> 0,
					"fdc_origin_debit"=>0,
					"fdc_credit"=>$rabaRugiJual,
					"fdc_origin_credit"=>$rabaRugiJual,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Fixed Asset Acc",
				];

				if (  $rabaRugiJual > 0){
					//Raba Penjualan Asset
					$rabaRugi["fst_account_code"]= $accRabaSellFixedAsset;
					$dataJurnal[] = $rabaRugi;
				}else if($rabaRugiJual < 0) {
					//Rugi Penjualan Asset
					$rabaRugi["fst_account_code"]= $accRugiSellFixedAsset;
					$dataJurnal[] = $rabaRugi;
				}
			}else if($dataH->fst_disposal_type == "MUTASI"){

				//Penyusutan
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accDeprecated,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> $dataD->fdc_deprecated_amount,
					"fdc_origin_debit"=>$dataD->fdc_deprecated_amount,
					"fdc_credit"=>0,
					"fdc_origin_credit"=>0,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Penyusutan",
				];

				//Akuisisi asset
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accFixedAsset,
					"fdt_trx_datetime"=>$dataH->fdt_fa_disposal_datetime,
					"fst_trx_sourcecode"=>"DFA", //Disposal Fixed Asset
					"fin_trx_id"=>$dataH->fin_fa_disposal_id,
					"fst_trx_no"=>$dataH->fst_fa_disposal_no,
					"fst_reference"=>$dataD->fst_notes,
					"fdc_debit"=> 0,
					"fdc_origin_debit"=>0,
					"fdc_credit"=>$dataD->fdc_deprecated_amount,
					"fdc_origin_credit"=>$dataD->fdc_deprecated_amount,
					"fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>"Fixed Asset Acc",
				];

			}else{
				throw new CustomException("Invalid disposal type " .$dataH->fst_disposal_type,404,"FAILED",[]);				
			}
		}  	
			
		$this->glledger_model->createJurnal($dataJurnal);

	}
	
	public function unposting($finFADisposalId){
		$this->load->model("glledger_model");
		$this->glledger_model->cancelJurnal("DFA",$finFADisposalId);
		$ssql ="SELECT * FROM trfadisposalitems where fin_fa_disposal_id = ?";
		$qr = $this->db->query($ssql,[$finFADisposalId]);
		$rs = $qr->result();
		foreach($rs as $rw){
			$ssql = "UPDATE trfaprofilesitems set fbl_disposal = 0 where fin_rec_id = ?";
			$this->db->query($ssql,[$rw->fin_fa_profile_detail_id]);
			throwIfDBError();
		}
	}
	public function deleteDetail($finFADisposalId){
		$ssql ="DELETE FROM trfadisposalitems where fin_fa_disposal_id = ?";
		$this->db->query($ssql,[$finFADisposalId]);		
	}

	public function delete($finFADisposalId,$softdelete = TRUE,$data=null){		
		parent::delete($finFADisposalId,$softdelete,$data);
		if ($softdelete){
			$ssql ="UPDATE trfadisposalitems set fst_active ='D' where fin_fa_disposal_id = ?";
		}else{
			$ssql ="DELETE from trfadisposalitems where fin_fa_disposal_id = ?";
		}		
		$this->db->query($ssql,[$finFADisposalId]);		
	}


	public function isEditable($finFADisposalId){
	}


	public function getSellFixedAssetList($finCustId,$fstCurrCode){
		$ssql = "SELECT * from trfadisposal a 
			where fst_disposal_type ='JUAL' and fdc_sell_total > fdc_sell_total_paid 
			AND fin_customer_id = ? AND fst_sell_curr_code = ?  AND fst_active ='A' ";
		$qr = $this->db->query($ssql,[$finCustId,$fstCurrCode]);
		$rs = $qr->result();
		return $rs;

	}


}
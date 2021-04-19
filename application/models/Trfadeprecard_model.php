<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trfadeprecard_model extends MY_Model{
    public $tableName = "trfadeprecard";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_fa_profile_id',
            'label' => 'Profile ID',
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



    public function deprecateAsset($finFAProfileId,$processPeriod){
        //periode sebelum start system tidak dilakukan jurnal

        $ssql = "SELECT * FROM trfaprofiles where fin_fa_profile_id = ?";
        $qr = $this->db->query($ssql,[$finFAProfileId]);
        $dataH=$qr->row();
        if ($dataH == null){
            throw new CustomException("Invalid FA profile ID",404,"INVALID ID",[]);
        }


        //Bila period melebihi masa susut batalkan proses
        $fdtStartSystem = getDbConfig("start_program");    
        $startSystemPeriod = getPeriod($fdtStartSystem,$dataH->fst_depre_period);          
        $akuisisiPeriod = getPeriod($dataH->fdt_aquisition_date,$dataH->fst_depre_period);
        $lifePeriodMonth =$dataH->fin_life_time_period;
        $lastDeprecatePeriod = addPeriod($akuisisiPeriod,$lifePeriodMonth,$dataH->fst_depre_period);        
        if ($processPeriod > $lastDeprecatePeriod){
            return;
        }


        $ssql ="SELECT * FROM trfaprofilesitems where fin_fa_profile_id = ?";
        $qr = $this->db->query($ssql,[$finFAProfileId]);        
        $details = $qr->result();
        
        //cek tahunan atau bulanan
        if ($dataH->fst_method == "Non-Depreciable"){
            return;
        }else if ($dataH->fst_method == "Straight Line"){
            //$depreAmount = ($dataH->fdc_aquisition_price - $dataH->fdc_residu_value) / $dataH->fin_life_time_period;
            //Depresiasi per tahun
            $depreAmountYear = $dataH->fdc_aquisition_price * ($dataH->fdc_pct_rate_year /100);

        }else if ($dataH->fst_method == "Double Declining Balance"){     
            //Ngak di pakai DULU cara juga berubah pakai rate bukan pakai nilai residu lagi       
            $diffPeriod =  diffPeriod($akuisisiPeriod,$processPeriod,$dataH->fst_depre_period);
            $nilaiBuku = $dataH->fdc_aquisition_price;
            $nilaiSusut = 0;
            $prcSusut = (1/$dataH->fin_life_time_period) * 2;
            for($i =0 ;$i < $diffPeriod + 1;$i++){
                $nilaiSusut = $prcSusut * $nilaiBuku;
                $nilaiBuku -= $nilaiSusut;

                if ($i == ($dataH->fin_life_time_period -1 )){
                    //Susut terakhir 
                    if ($nilaiBuku > $dataH->fdc_residu_value){
                        $nilaiSusut  += ($nilaiBuku - $dataH->fdc_residu_value);
                    }else{
                        $nilaiSusut  -= ($dataH->fdc_residu_value - $nilaiBuku);
                    }
                }
            }
            $depreAmount = $nilaiSusut;
        }

        $fblJurnal =0;
        if ($processPeriod >= $startSystemPeriod ){
            $fblJurnal = 1;
        }

        

        if ($dataH->fst_depre_period == "monthly"){            
            $depreAmount = $depreAmountYear /12;

            foreach($details as $detail){
                $data =[
                    "fin_fa_profile_id"=>$dataH->fin_fa_profile_id,
                    "fst_fa_profile_code"=>$detail->fst_fa_profile_code,
                    "fst_period"=>$processPeriod,
                    "fdc_depre_amount"=>$depreAmount,
                    "fbl_jurnal"=>$fblJurnal,
                    "fst_active"=>"A",
                ];
                $insertId = $this->trfadeprecard_model->insert($data);
                if ($fblJurnal){
                    $this->jurnalDeprecateAsset($insertId);
                }
            }
        }else{ //year
            //$currentPeriod = getPeriod();
            $depreAmount = $depreAmountYear;

            if (periodIsEndOfYear($processPeriod)){            
                foreach($details as $detail){
                    $data =[
                        "fin_fa_profile_id"=>$dataH->fin_fa_profile_id,
                        "fst_fa_profile_code"=>$detail->fst_fa_profile_code,
                        "fst_period"=>$processPeriod,
                        "fdc_depre_amount"=>$depreAmount,
                        "fbl_jurnal"=>$fblJurnal,
                        "fst_active"=>"A",
                    ];
                    $insertId = $this->trfadeprecard_model->insert($data);
                    if ($fblJurnal){
                        $this->jurnalDeprecateAsset($insertId);
                    }
                }
            }
        }
    }

    public function jurnalDeprecateAsset($finfaDeprecardId){
        $this->load->model("glledger_model");

        //belum di buat jurnalnya
        /**
         * Biaya
         *      Akumulasi Penyusutan
         */

        $ssql = "SELECT a.*,b.fin_branch_id,b.fst_accum_account_code,b.fst_deprecost_account_code FROM trfadeprecard a 
            INNER JOIN trfaprofiles b on a.fin_fa_profile_id = b.fin_fa_profile_id
            where a.fin_rec_id = ? and a.fst_active ='A'";

        
        $qr = $this->db->query($ssql,[$finfaDeprecardId]);        
        $rw = $qr->row();
        if ($rw == null){
            throw new CustomException("Invalid Deprecard ID",404,"FAILED",[]);
        }

        $accBiaya = $rw->fst_deprecost_account_code;
        $accAkumalisPenyusutan = $rw->fst_accum_account_code;
        
        $dataJurnal =[];

        $dataJurnal[] =[ 
            "fin_branch_id"=>$rw->fin_branch_id,
            "fst_account_code"=>$accBiaya,
            "fdt_trx_datetime"=>$rw->fdt_insert_datetime,
            "fst_trx_sourcecode"=>"DCFA", //Deprecard Asset
            "fin_trx_id"=>$rw->fin_rec_id,
            "fst_trx_no"=>$rw->fst_fa_profile_code . "/" .$rw->fst_period,
            "fst_reference"=>"",
            "fdc_debit"=> $rw->fdc_depre_amount,
            "fdc_origin_debit"=>$rw->fdc_depre_amount,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
            "fdc_orgi_rate"=>1,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A",
            "fst_info"=>"Biaya Penyusutan",
        ];

        $dataJurnal[] =[ 
            "fin_branch_id"=>$rw->fin_branch_id,
            "fst_account_code"=>$accAkumalisPenyusutan,
            "fdt_trx_datetime"=>$rw->fdt_insert_datetime,
            "fst_trx_sourcecode"=>"DCFA", //Deprecard Asset
            "fin_trx_id"=>$rw->fin_rec_id,
            "fst_trx_no"=>$rw->fst_fa_profile_code . "/" .$rw->fst_period,
            "fst_reference"=>"",
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=>$rw->fdc_depre_amount,
            "fdc_origin_credit"=>$rw->fdc_depre_amount,
            "fst_orgi_curr_code"=>getDefaultCurrency()["CurrCode"],
            "fdc_orgi_rate"=>1,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A",
            "fst_info"=>"Akumulasi Penyusutan",
        ];        

        $this->glledger_model->createJurnal($dataJurnal);
        
    }

    
}
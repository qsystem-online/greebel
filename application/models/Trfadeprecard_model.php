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
            $depreAmount = ($dataH->fdc_aquisition_price - $dataH->fdc_residu_value) / $dataH->fin_life_time_period;
        }else if ($dataH->fst_method == "Double Declining Balance"){            
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

    public function jurnalDeprecateAsset(){
        //belum di buat jurnalnya
    }

    
}
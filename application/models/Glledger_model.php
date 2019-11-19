<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Glledger_model extends MY_Model{
    public $tableName = "glledger";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_glaccount_code',
            'label' => 'GL Account Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.fst_glaccount_code.fst_glaccount_code.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];

        $rules[] = [
            'field' => 'fst_glaccount_name',
            'label' => 'GL Account Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            ),
        ];

        $rules[] = [
            'field' => 'fst_curr_code',
            'label' => 'Current Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            ),
        ];

        return $rules;
    }  
    
    public function createJurnal($datas){
        $this->load->model("glaccounts_model");
        
        //Cek Balance
        $ttlDebet = 0;
        $ttlCredit = 0;
        $epsilon = 0.00001;        
        foreach($datas as $data){
            $ttlDebet += (float) $data["fdc_debit"];
            $ttlCredit += (float) $data["fdc_credit"];
        }
        if(abs($ttlDebet - $ttlCredit) > $epsilon ){
            return [
                "status"=>"FAILED",
                "message"=>"Debet and Credit not balance! ($ttlDebet vs $ttlCredit)"
            ];          
        }
        $ids=[];
        foreach($datas as $data){
            $account = $this->glaccounts_model->getSimpleDataHeader($data["fst_account_code"]);
            $data["fst_account_name"] = $account->fst_glaccount_name;

            
            if ($data["fst_orgi_curr_code"]  == null){
                $defaultCurr = getDefaultCurrency();
                $data["fst_orgi_curr_code"] =$defaultCurr["CurrCode"];
                $data["fdc_orgi_rate"] = 1;
            }
            
            
            if ($data["fdc_debit"] == 0 && $data["fdc_credit"] == 0){
                continue;
            }
            //var_dump($data);
            $ids[]= parent::insert($data);
        }

        $error = $this->db->error();
        if($error["code"] != 0){
            return [
                "status"=>"FAILED",
                "message"=>$error["message"]
            ];    
        }

        return [
            "status"=>"SUCCESS",
            "message"=>"",
            "ids"=>$ids
        ];  
        
    }

    public function cancelJurnal($trxSourcecode,$trxId,$newTrxDate=""){
        $ssql ="select * from glledger where fst_trx_sourcecode =? and fin_trx_id = ?";
        $qr = $this->db->query($ssql,[$trxSourcecode,$trxId]);
        $rs = $qr->result();

        if ((boolean) getDbConfig("delete_jurnal")){
            //Delete Transaction;
            foreach($rs as $rw){
                parent::delete($rw->fin_rec_id,false);
            }
        }else{

            //Balik Jurnal
            for($i=0;$i<sizeof($rs);$i++){
                $rw = $rs[$i];
                parent::delete($rw->fin_rec_id,true);
                /*
                $debet =  $rs[$i]->fdc_debit;
                $credit = $rs[$i]->fdc_credit;

                $rs[$i]->fdc_debit = $credit;
                $rs[$i]->fdc_credit = $debet;
                $rs[$i]->fbl_is_flip = 1;            
                unset($rs[$i]->fin_rec_id);
                if ($newTrxDate != ""){
                    $rs[$i]->fdt_trx_datetime = $newTrxDate;
                }
                parent::insert((array) $rs[$i]);
                */
            }
        }

        $dbError  = $this->db->error();
		if ($dbError["code"] != 0){	
            $result["status"]= "FAILED";
            $result["message"]= $dbError["message"];            
			return $result;
        }

        return[
            "status"=>"SUCCESS",
            "message"=>""
        ];

    }

    public function getJurnal($trxSourcecode,$trxId){
        $ssql ="select a.fst_account_code,a.fst_account_name as fst_glaccount_name, a.fdc_debit, a.fdc_credit,a.fin_pcc_id,b.fst_pcc_name from glledger a 
            LEFT JOIN msprofitcostcenter b on a.fin_pcc_id = b.fin_pcc_id 
            where fst_trx_sourcecode = ? and fin_trx_id = ? and a.fbl_is_flip = 0 and a.fst_active = 'A' order by (fdc_debit > 0) desc ,fin_rec_id";

        $qr= $this->db->query($ssql,[$trxSourcecode,(int) $trxId]);
        $rs = $qr->result();
        return $rs;
    }

    
}

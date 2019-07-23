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
        //Cek Balance
        $ttlDebet = 0;
        $ttlCredit = 0;
        foreach($datas as $data){            
            $ttlDebet += (float) $data["fdc_debit"];
            $ttlCredit += (float) $data["fdc_credit"];
        }
        if($ttlDebet != $ttlCredit){
            return false;          
        }
        $ids=[];
        foreach($datas as $data){
            $ids[]= parent::insert($data);
        }
        return $ids;
    }
    public function cancelJurnal($trxSourcecode,$trxId,$newTrxDate=""){
        $ssql ="select * from glledger where fst_trx_sourcecode =? and fin_trx_id = ?";
        $qr = $this->db->query($ssql,[$trxSourcecode,$trxId]);
        $rs = $qr->result();

        if ((boolean) getDbConfig("delete_jurnal")){
            //Delete Transaction;
            foreach($rs as $rw){
                parent::delete($rw->fin_rec_id);
            }
        }else{
            //Balik Jurnal
            for($i=0;$i<sizeof($rs);$i++){
                $debet =  $rs[$i]->fdc_debet;
                $credit = $rs[$i]->fdc_credit;

                $rs[$i]->fdc_debet = $credit;
                $rs[$i]->fdc_credit = $debet;
                if ($newTrxDate != ""){
                    $rs[$i]->fdt_trx_datetime = $newTrxDate;
                }
                parent::insert($rs[$i]);
            }
        }
    }
}

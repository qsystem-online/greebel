<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsalesekspedisi_model extends MY_Model {
    public $tableName = "trsalesekspedisi";
    public $pkey = "fin_salesekspedisi_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_salesekspedisi_no',
            'label' => 'No Transaksi Ekspedisi',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }

    public function getDataById($finSalesEkspedisiId){
        
        $ssql = "SELECT a.*,d.fst_name as fst_shipping_address_name,d.fst_shipping_address FROM trsalesekspedisi a         
        INNER JOIN msshippingaddress d on a.fin_shipping_address_id = d.fin_shipping_address_id       
        where a.fin_salesekspedisi_id = ? and a.fst_active != 'D'";

        $dataH = $this->db->query($ssql,[$finSalesEkspedisiId])->row();
                
        $ssql = "SELECT a.*,b.fst_sj_no FROM trsalesekspedisiitems a
            INNER JOIN trsuratjalan b on a.fin_sj_id = b.fin_sj_id
            where a.fin_salesekspedisi_id = ? and a.fst_active != 'D'";
        $dataDetails = $this->db->query($ssql,[$finSalesEkspedisiId])->result();

        return[
            "salesEkspedisi"=>$dataH,
            "salesEkspedisiItems"=>$dataDetails
        ];        
    }
    
    public function generateTransactionNo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }

        $prefix = getDbConfig("sales_ekspedisi_prefix");


        //$query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM $table where $field like '".$prefix.$tahun."%'");
        $query = $this->db->query("SELECT MAX(fst_salesekspedisi_no) as max_id FROM trsalesekspedisi where fst_salesekspedisi_no like '".$prefix."/%/".$tahun."%'");

        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix."/". $branchCode .'/' .$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }
    
    public function unposting($finSalesEkspedisiId,$unpostingDateTime =""){
        $this->load->model("glledger_model");
        $ssql ="UPDATE trsuratjalan set fin_salesekspedisi_id = null  where fin_salesekspedisi_id = ?";
        $this->db->query($ssql,[$finSalesEkspedisiId]);
        throwIfDBError();

        $this->glledger_model->cancelJurnal("EXP",$finSalesEkspedisiId,$unpostingDateTime);        
    }

    public function posting($finSalesEkspedisiId){

        $this->load->model("glledger_model");
        $ssql="SELECT * FROM trsalesekspedisi where fin_salesekspedisi_id = ?";
        $qr =$this->db->query($ssql,[$finSalesEkspedisiId]);
        $dataH = $qr->row();
        if($dataH == null){
            throw new CustomException(lang("ID ekspedisi penjualan tidak dikenal !",9009,"FAILED",["fin_salesekspedisi_id"=>$finSalesEkspedisiId]));
        }
        
        $ssql = "UPDATE trsuratjalan a 
            INNER JOIN trsalesekspedisiitems b ON a.fin_sj_id = b.fin_sj_id
            set a.fin_salesekspedisi_id = ? 
            WHERE b.fin_salesekspedisi_id = ?";

        $this->db->query($ssql,[$finSalesEkspedisiId,$finSalesEkspedisiId]);
        throwIfDBError();
        
        
        $dataJurnal = [];
        if(!$dataH->fbl_reclaimable){
            //Piutang
            //      Hutang                        
            $accPiutangEkspedisi = getGLConfig("PIUTANG_EKSPEDISI_PENJUALAN");        
            $glAccountInfo = "Piutang Ekspedisi";                    
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$accPiutangEkspedisi,
                "fdt_trx_datetime"=>$dataH->fdt_salesekspedisi_datetime,
                "fst_trx_sourcecode"=>"EXP",
                "fin_trx_id"=>$dataH->fin_salesekspedisi_id,
                "fst_trx_no"=>$dataH->fst_salesekspedisi_no,
                "fst_reference"=>$dataH->fst_memo,
                "fdc_debit"=> $dataH->fdc_total * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_debit"=>$dataH->fdc_total,
                "fdc_credit"=> 0,
                "fdc_origin_credit"=> 0,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_relation_id"=>$dataH->fin_customer_id,
                "fst_active"=>"A",
                "fst_info"=>$glAccountInfo,
            ];            
        }else{
            //Biaya
            //PPN
            //      Hutang
            $accBiayaEkspedisi = getGLConfig("BIAYA_EKSPEDISI_PENJUALAN");        
            $glAccountInfo = "Biaya Ekspedisi";            
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$accBiayaEkspedisi,
                "fdt_trx_datetime"=>$dataH->fdt_salesekspedisi_datetime,
                "fst_trx_sourcecode"=>"EXP",
                "fin_trx_id"=>$dataH->fin_salesekspedisi_id,
                "fst_trx_no"=>$dataH->fst_salesekspedisi_no,
                "fst_reference"=>$dataH->fst_memo,
                "fdc_debit"=> ($dataH->fdc_total - $dataH->fdc_ppn_amount) * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_debit"=>($dataH->fdc_total - $dataH->fdc_ppn_amount),
                "fdc_credit"=> 0,
                "fdc_origin_credit"=> 0,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A",
                "fst_info"=>$glAccountInfo,
            ];            
            $accPpnEkspedisi = getGLConfig("PPN_EKSPEDISI_PENJUALAN");        
            $glAccountInfo = "PPN Ekspedisi";            
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$accPpnEkspedisi,
                "fdt_trx_datetime"=>$dataH->fdt_salesekspedisi_datetime,
                "fst_trx_sourcecode"=>"EXP",
                "fin_trx_id"=>$dataH->fin_salesekspedisi_id,
                "fst_trx_no"=>$dataH->fst_salesekspedisi_no,
                "fst_reference"=>$dataH->fst_memo,
                "fdc_debit"=> $dataH->fdc_ppn_amount * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_debit"=>$dataH->fdc_ppn_amount,
                "fdc_credit"=> 0,
                "fdc_origin_credit"=> 0,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A",
                "fst_info"=>$glAccountInfo,
            ];
        }
        
        $accHutangEkspedisi = getGLConfig("HUTANG_EKSPEDISI_PENJUALAN");        
        $glAccountInfo = "Hutang Ekspedisi";            
        $dataJurnal[] = [
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accHutangEkspedisi,
            "fdt_trx_datetime"=>$dataH->fdt_salesekspedisi_datetime,
            "fst_trx_sourcecode"=>"EXP",
            "fin_trx_id"=>$dataH->fin_salesekspedisi_id,
            "fst_trx_no"=>$dataH->fst_salesekspedisi_no,
            "fst_reference"=>$dataH->fst_memo,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=> $dataH->fdc_total * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_credit"=> $dataH->fdc_total,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
            "fin_relation_id"=>$dataH->fin_supplier_id,
            "fst_active"=>"A",
            "fst_info"=>$glAccountInfo,
        ];            
        
        $this->glledger_model->createJurnal($dataJurnal);
    }
    
   public function delete($finSalesEkspedisiId,$softDelete = true,$data=null){
       
        //Delete detail transaksi
        if ($softDelete){
            $ssql ="update trsalesekspedisi set fst_active ='D' where fin_salesekspedisi_id = ?";
            $this->db->query($ssql,[$finSalesEkspedisiId]);
            throwIfDBError();
        }else{
            $ssql ="delete from trsalesekspedisi where fin_salesekspedisi_id = ?";
            $this->db->query($ssql,[$finSalesEkspedisiId]);
            throwIfDBError();            
        }   
        parent::delete($finSalesEkspedisiId,$softDelete,$data);    
   }

   public function deleteDetail($finSalesEkspedisiId){
       $ssql = "DELETE from trsalesekspedisiitems where fin_salesekspedisi_id = ?";
       $this->db->query($ssql,[$finSalesEkspedisiId]);
       throwIfDBError();
   }

   public function isEditable($dataH){
       /**
        * FAILED CONDITION
        * + Belum dibayar
        */
        if ($dataH->fdc_total_paid > 0){
            throw new CustomException(lang("Biaya ekspedisi telah dilakukan pembayaran !"),3003,"FAILED",null);
        }        
        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
   }

   public function getSJList($finCustomerId){

        $term = $this->input->get("term");
        $term =  "%$term%";

        $ssql  = "SELECT a.fin_sj_id,a.fst_sj_no,a.fdt_sj_datetime,b.fst_salesorder_no,b.fdt_salesorder_datetime from trsuratjalan a 
            INNER JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id
            WHERE a.fst_sj_type ='SO' and a.fin_salesekspedisi_id IS NULL 
            AND b.fin_relation_id = ?
            and a.fst_sj_no like ?";
        $qr = $this->db->query($ssql,[$finCustomerId,$term]);
        
        return $qr->result();

   }

}



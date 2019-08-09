<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class SalesOrder{
    private $rw;
    private $CI;
    public function __construct($CI,$fin_salesorder_id){
        $this->CI = $CI;
        $ssql = "Select * from trsalesorder where fin_salesorder_id = ?";
        $qr = $this->CI->db->query($ssql,[$fin_salesorder_id]);
        $this->rw  = $qr->row();

    }
    public function isClosed(){
        return $this->rw->fbl_is_closed;
    }    
    public function getValue($key){
        return $this->rw->$key;
    }

    public function getDetails(){
        $ssql = "Select * from trsalesorderdetails where fin_salesorder_id = ?";
        $qr = $this->CI->db->query($ssql,[$this->rw->fin_salesorder_id]);       
        $rs = $qr->result();
        return $rs;
    }
    public function getData(){
        return $this->rw;
    }

    public function isPromoWithSJ(){
        //transaksi ini memiliki promo item dan sudah ada surat jalan atas transaksi ini
        // Free Item, Free Custom Item, Free Cashback, Disc Per Item

        return false;
    }
    
    public function __get($name){
        if (property_exists($this->rw,$name)){
            return $this->rw->$name;
        }else{
            throw new Exception("Invalid Property Name !");
        }
    }

    public function getDPClaimed($salesOrderId,$excludeInvId = 0){
        $ssql = "select sum(fdc_downpayment_claimed) as fdc_downpayment_claimed from trinvoice where fin_salesorder_id = ? and fst_active ='A' and fin_inv_id != ?";
        $qr = $this->CI->db->query($ssql,[$salesOrderId,$excludeInvId]);
        $rw =$qr->row();
        $totalClaimed = $rw->fdc_downpayment_claimed;
        return $totalClaimed;
    }
    public function getDPAvailableToClaimed($salesOrderId,$excludeInvId = 0){        
        $totalClaimed =  $this->getDPClaimed($salesOrderId,$excludeInvId);
        return (float) $this->fdc_downpayment_paid - (float) $totalClaimed;
    }
}

class Trsalesorder_model extends MY_Model {
    public $tableName = "trsalesorder";
    public $pkey = "fin_salesorder_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_salesorder_no',
            'label' => 'Sales Order No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }

    public function createObject($fin_salesorder_id){
        $CI = &get_instance();
        $salesOrder = new SalesOrder($this,$fin_salesorder_id);
        return $salesOrder;
    }

    public function getDataById($fin_salesorder_id){
        $ssql = "select a.*,
            b.fst_relation_name,b.fin_sales_id,a.fin_shipping_address_id,b.fin_cust_pricing_group_id,
            c.fst_fullname as fst_sales_name 
            from trsalesorder a
            inner join msrelations b on a.fin_relation_id  = b.fin_relation_id 
            inner join users c on a.fin_sales_id  = c.fin_user_id             
            where a.fin_salesorder_id = ?";
        $qr = $this->db->query($ssql, [$fin_salesorder_id]);
        $rwSalesOrder = $qr->row();

        $ssql = "select a.*,b.fst_item_name,b.fst_item_code,b.fst_max_item_discount from trsalesorderdetails a 
        left join msitems b on a.fin_item_id = b.fin_item_id
        where a.fin_salesorder_id = ?";
		$qr = $this->db->query($ssql,[$fin_salesorder_id]);
		$rsSODetails = $qr->result();

		$data = [
            "sales_order" => $rwSalesOrder,
            "so_details" => $rsSODetails
		];

		return $data;
    }

    public function GenerateSONo($soDate = null) {
        $soDate = ($soDate == null) ? date ("Y-m-d"): $soDate;
        $tahun = date("ym", strtotime ($soDate));
        $prefix = getDbConfig("salesorder_prefix");
        $query = $this->db->query("SELECT MAX(fst_salesorder_no) as max_id FROM trsalesorder where fst_salesorder_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();
        $max_id = $row['max_id']; 
        $max_id1 =(int) substr($max_id,8,5);
        $fst_salesorder_no = $max_id1 +1;
        $max_salesorder_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_salesorder_no);
        return $max_salesorder_no;
    }

    public function getDataPromo($fin_customer_id,$details,$trxDate=null){

        $this->load->model("msitemunitdetails_model");
        $trxDate = ($trxDate == null) ? date("Y-m-d") : $trxDate;
        $arrPromo = [];

        $ssql = "select a.*,b.fin_member_group_id from msrelations a 
            left join (select * from msmemberships where fst_active = 'A' and fdt_expiry_date > ?) b on a.fin_relation_id = b.fin_relation_id 
            where a.fin_relation_id = ?";
        $qr = $this->db->query($ssql,[$trxDate,$fin_customer_id]);
        $rwCek = $qr->row();
        if(!$rwCek){
            return false;
        }
        $fin_relation_group_id = $rwCek->fin_relation_group_id;
        $fin_member_group_id = $rwCek->fin_member_group_id;

        //Select ALL Promo in date range
        $ssql = "select * from mspromo where ? between fdt_start and fdt_end 
            and fst_promo_type in ('OFFICE','ALL') 
            and fbl_disc_per_item = false 
            and fst_active = 'A'";
        $qr = $this->db->query($ssql,[$trxDate]);                
        $rs = $qr->result();

        //** No participant - ALL CUSTOMER IS PARTICIPANT */
        for($i = 0 ; $i < sizeof($rs); $i++){  
            $fin_promo_id = $rs[$i]->fin_promo_id;  
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer where fin_promo_id = ?";
            $qr = $this->db->query($ssql,[$fin_promo_id]);
            $rwParticipant = $qr->row();
            if($rwParticipant->ttlParticipant == 0){
                $rs[$i]->isParticipant = true;
            }else{
                $rs[$i]->isParticipant = false;
            }       
        }
        
       

        //** RELATION GROUP PARTICIPANT */
        for($i = 0 ; $i < sizeof($rs); $i++){              
            if ($rs[$i]->isParticipant == true){
                continue;
            }
            $fin_promo_id = $rs[$i]->fin_promo_id;  
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer 
                where fin_promo_id = ? 
                and fst_participant_type ='RELATION GROUP' 
                and fin_customer_id = ?";                
            $qr = $this->db->query($ssql,[$fin_promo_id,$fin_relation_group_id]);            
            $rwParticipant = $qr->row();
            if($rwParticipant->ttlParticipant > 0){
                $rs[$i]->isParticipant = true;
            }            
        }

        //** MEMBER GROUP PARTICIPANT */
        if ($fin_member_group_id != null){
            for($i = 0 ; $i < sizeof($rs); $i++){              
                if ($rs[$i]->isParticipant == true){
                    continue;
                }
                $fin_promo_id = $rs[$i]->fin_promo_id;  
                $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer 
                    where fin_promo_id = ? 
                    and fst_participant_type ='MEMBER GROUP' 
                    and fin_customer_id = ?";                
                $qr = $this->db->query($ssql,[$fin_promo_id,$fin_member_group_id]);
                $rwParticipant = $qr->row();
                if($rwParticipant->ttlParticipant > 0){
                    $rs[$i]->isParticipant = true;
                }            
            }
        }
       

        //** RELATION ID PARTICIPANT */
        for($i = 0 ; $i < sizeof($rs); $i++){              
            if ($rs[$i]->isParticipant == true){
                continue;
            }
            $fin_promo_id = $rs[$i]->fin_promo_id;  
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer 
                where fin_promo_id = ? 
                and fst_participant_type ='RELATION' 
                and fin_customer_id = ?";                
            $qr = $this->db->query($ssql,[$fin_promo_id,$fin_customer_id]);
            $rwParticipant = $qr->row();
            if($rwParticipant->ttlParticipant > 0){
                $rs[$i]->isParticipant = true;
            }            
        }

        
        //**Remove all promo when user is not participant */
        $ttlRow = sizeof($rs);
        for($i = 0 ; $i < $ttlRow; $i++){             
            if ($rs[$i]->isParticipant != true){                
                unset($rs[$i]);
            }
        }        
        $rs = array_values($rs); //reindex array       


        //** Cek if transaction valid for list promotion */
        $ttlRow = sizeof($rs);
        for($i = 0 ; $i < $ttlRow; $i++){             
            $rwPromo = $rs[$i];
            $minTotalPurchase = (float) $rwPromo->fdc_min_total_purchase;
            
            //Syarat dipilih salah satu antara berdasarkan total belanja atau syarat qty
            if($minTotalPurchase > 0){ //Proses Berdasarkan Total Belanja                
                $totalPurchase = 0;
                foreach($details as $item){
                    $amount = $item->fdb_qty * $item->fdc_price;
                    $ttlDisc = calculateDisc($item->fst_disc_item , $amount);
                    $totalPurchase += ($item->fdb_qty * $item->fdc_price) - $ttlDisc;                        
                }
                if ($totalPurchase < $minTotalPurchase){
                    //syarat promo tidak terpenuhi hapus dari array
                    unset($rs[$i]);
                }                
            }else { //Proses Berdasarkan Syarat Qty - Unit         
                $ssql ="select * from mspromoitems where fin_promo_id =?";
                $qr= $this->db->query($ssql,[$rwPromo->fin_promo_id]);
                $itemRules = $qr->result();

                //Get Item SubGroup ID
                
                for($y=0;$y<sizeof($details);$y++){
                    $ssql = "select * from msitems where fin_item_id = ?";
                    $qr = $this->db->query($ssql,[$details[$y]->fin_item_id]);
                    $rwItem = $qr->row();
                    if(!$rwItem){
                        $details[$y]->fin_item_subgroup_id = null; 
                    }else{
                        $details[$y]->fin_item_subgroup_id = $rwItem->fin_item_subgroup_id;                    
                    }
                }

                if ($rwPromo->fbl_qty_gabungan){ //Proses Qty Gabungan                    
                    $qtyGabungan =$rwPromo->fdb_qty_gabungan;
                    $satuanGabungan = $rwPromo->fst_unit_gabungan;
                    $totalQtyGabungan =0;
                    foreach($details as $item){
                        $isOnRules = false;
                        if(sizeof($itemRules) == 0){
                            $isOnRules = true;
                        }else{
                            $isOnRules = $this->isOnPromoItemRules($itemRules,$item->fin_item_id,$item->fin_item_subgroup_id);
                        }
                        if($isOnRules){    
                            $qtyUnit = $this->msitemunitdetails_model->getConversionUnit($item->fin_item_id,$item->fdb_qty, $item->fst_unit, $satuanGabungan);
                            $totalQtyGabungan += $qtyUnit;
                        }
                    }
                    if ($totalQtyGabungan < $qtyGabungan){
                        //syarat promo tidak terpenuhi hapus dari array
                        unset($rs[$i]);
                    }
                }else{ //Qty Tidak Gabungan
                    //$isAchived = false;
                    foreach($itemRules as $rule){
                        if($rule->fst_item_type == "SUB GROUP"){
                            $totalQtySubGroup = 0; 
                            foreach($details as $item){
                                if ($item->fin_item_subgroup_id == $rule->fin_item_id && $item->fst_unit == $rule->fst_unit){
                                    $totalQtySubGroup += (float) $item->fdb_qty;
                                }
                            }
                            if ($totalQtySubGroup < (float) $rule->fdb_qty){
                                //syarat promo tidak terpenuhi hapus dari array
                                unset($rs[$i]);
                            }
                        }else if($rule->fst_item_type == "ITEM"){
                            $totalQtyItem = 0; 
                            foreach($details as $item){
                                if ($item->fin_item_id == $rule->fin_item_id && $item->fst_unit == $rule->fst_unit){
                                    $totalQtyItem += (float) $item->fdb_qty;
                                }
                            }
                            if ($totalQtyItem < (float) $rule->fdb_qty){
                                //syarat promo tidak terpenuhi hapus dari array
                                unset($rs[$i]);
                            }
                        }
                    }
                }
            }
        }
        $rs = array_values($rs); //reindex array       
        

        if (sizeof($rs) == 0){ //No Promo 
            return false;
        }

        //** Filter promo gabungan */
        $arrfin_promo_id =[];
        foreach($rs as $promo){
            $arrfin_promo_id[] = $promo->fin_promo_id;
        }


        $ssql = "select *,b.fst_item_name as fst_item_name,b.fst_item_code as fst_item_code from mspromo a 
            left join msitems b on a.fin_promo_item_id = b.fin_item_id 
            where a.fin_promo_id in ? order by a.fdt_start desc ,a.fdt_end desc,a.fin_promo_id desc";
        $qr = $this->db->query($ssql,[$arrfin_promo_id]);        
        $rs = $qr->result();

        $arrPromo =[];
        foreach($rs as $promo){
            if ($promo->fbl_promo_gabungan){
                $arrPromo[] = $promo;
            }else{                
                if (sizeof($arrPromo) == 0){
                    $arrPromo[] = $promo;
                    break;
                }                
            }            
        }

        //Dapatkan Promo Item
        $arrPromoItem = [];
        foreach($arrPromo as $promo){

            if ($promo->fin_promo_item_id != null ){
                $arrPromoItem[] = [
                    "fin_promo_id"=>$promo->fin_promo_id,
                    "modelPromotion" =>"ITEM",
                    "fin_item_id"=>$promo->fin_promo_item_id,
                    "fst_item_code"=>$promo->fst_item_code,
                    "fst_item_name"=>$promo->fst_item_name,
                    "fst_custom_item_name"=>$promo->fst_item_name,
                    "fdb_qty"=>$promo->fdb_promo_qty,
                    "fst_unit"=>$promo->fst_promo_unit,
                    "fdc_cashback"=>0,
                ];
            }

            if ($promo->fst_other_prize != null && $promo->fst_other_prize != ""){                
                $arrPromoItem[] = [
                    "fin_promo_id"=>$promo->fin_promo_id,
                    "modelPromotion" => "OTHER ITEM",
                    "fin_item_id"=>0,
                    "fst_item_code"=>"PRO",
                    "fst_item_name"=>$promo->fst_other_prize,
                    "fst_custom_item_name"=>$promo->fst_other_prize,
                    "fdb_qty"=>1,
                    "fst_unit"=>$promo->fst_promo_unit,
                    "fdc_cashback"=>0,
                ];
            }

            if ($promo->fdc_cashback != null && $promo->fdc_cashback > 0){
                $arrPromoItem[] = [
                    "fin_promo_id"=>$promo->fin_promo_id,
                    "modelPromotion" => "CASHBACK",
                    "fin_item_id"=>0,
                    "fst_item_code"=>"PRO",
                    "fst_item_name"=>null,
                    "fst_custom_item_name"=>"Voucher Cashback Rp." . formatNumber($promo->fdc_cashback),
                    "fdb_qty"=>1,
                    "fst_unit"=>"PCS",
                    "fdc_cashback"=> $promo->fdc_cashback,
                ];
            }
        }
            
        if (sizeof($arrPromoItem) > 0){
            return $arrPromoItem;
        }else{
            return false;
        }

        //return false;
    }

    public function getDataOutstanding($fin_customer_id,$fdc_credit_limit,$current_so_id = 0){
        
        $fdc_credit_limit = (float) $fdc_credit_limit;
        $piutangOutstanding =0;
        $soOutstanding = 10000000;
        $billyetOutstanding = 0;
        $totalOutstanding = ($piutangOutstanding + $soOutstanding + $billyetOutstanding);        
        return [
            "maxCreditLimit"=>$fdc_credit_limit,
            "piutangOutstanding"=> $piutangOutstanding,
            "soOutstanding"=> $soOutstanding,
            "billyetOutstanding"=>$billyetOutstanding,
            "totalOutstanding"=> $totalOutstanding,
            "sisaPlafon"=> $fdc_credit_limit - $totalOutstanding,
            "dataFakturOutstanding"=>[],
        ];
    }
    
    private function isOnPromoItemRules($rules,$fin_item_id,$fin_item_subgroup_id){
        foreach($rules as $rule){
            if($rule->fst_item_type == 'SUB GROUP'){
                if ($fin_item_subgroup_id == $fin_item_id){
                    return true;
                }
            }else if ($rule->fst_item_type == 'ITEM') {
                if ($rule->fin_item_id == $fin_item_id){
                    return true;
                }
            }
        }
        return false;
    }

    
    public function posting($fin_salesorder_id){
        //Bila terdapat DP jurnal DP tersebut
        

        $ssql ="select * from trsalesorder where fin_salesorder_id = ?";
        $qr = $this->db->query($ssql,[$fin_salesorder_id]);
        $dataH = $qr->row_array();

        if ($dataH["fdc_downpayment"] > 0 && $dataH["fst_active"] == "A"){
            $this->load->model("glledger_model");
            $dataJurnal = [
                [
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("SO_PIUTANG"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_date"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_reference"=>null,
                    "fdc_debit"=>$dataH["fdc_downpayment"],
                    "fdc_credit"=>0,
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fst_profit_cost_center_code"=>null,
                    "fin_relation_id"=>$dataH["fin_relation_id"],
                    "fst_active"=>"A"
                ],
                [
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("SO_DP"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_date"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_reference"=>null,
                    "fdc_debit"=>0,
                    "fdc_credit"=>$dataH["fdc_downpayment"],
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fst_profit_cost_center_code"=>null,
                    "fin_relation_id"=>$dataH["fin_relation_id"],
                    "fst_active"=>"A"
                ],
            ];
            
            if($this->glledger_model->createJurnal($dataJurnal) === false){
                throw new Exception("Error Create Jurnal !", EXCEPTION_JURNAL);
            }
        }


    }

    public function update($data){
        //Delete Field yang tidak boleh berubah
        unset($data["fin_relation_id"]);
        unset($data["fst_salesorder_no"]);
        parent::update($data);        
    }

    public function approved($finSalesOrderId){

        $data = [
            "fin_salesorder_id"=>$finSalesOrderId,
            "fst_active"=>"A"
        ];
        parent::update($data);
        

        //Cek kalau semua proses verification sudah selesai
        $ssql = "select * from trverification 
        where fst_controller ='SO' 
        and fin_transaction_id = ? 
        and fst_verification_status != 'VF' 
        and fst_active='A'" ;

        $qr = $this->db->query($ssql,[$finSalesOrderId]);

        $rw = $qr->row();
        if ($rw == false){
            $this->posting($finSalesOrderId);
        }
    }


    //==== UNHOLD ===============================\\
    public function unhold($finSalesOrderId){
        
        $activeUser = $this->aauth->user();
        //print_r($activeUser);
    
        $data = [
            "fin_salesorder_id" => $finSalesOrderId,
            "fbl_is_hold" => "0", //Unhold Success
            "fin_unhold_id" => $activeUser->fin_user_id,
            //"fdt_unhold_datetime" => dBDateFormat("fdt_unhold_datetime")
            "fdt_unhold_datetime" => date("Y-m-d H:i:s")
        ];

        parent::update($data);
       
    }
}



<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

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

    public function getDataById($fin_salesorder_id){
        $ssql = "select * from trsalesorder where fin_salesorder_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_salesorder_id]);
        $rwSalesOrder = $qr->row();

        $ssql = "select a.*,b.fst_item_name,c.fdc_selling_price,d.fst_item_discount from trsalesorderdetails a left join msitems b on a.fin_item_id = b.fin_item_id
        left join msitemspecialpricinggroupdetails c on a.fdc_price = c.fdc_selling_price
        left join msitemdiscounts d on a.fst_disc_item = d.fst_item_discount 
        where a.fin_salesorder_id = ? order by fin_salesorder_id";
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
        $query = $this->db->query("SELECT MAX(fst_salesorder_no) as max_id FROM trsalesorder where fst_salesorder_no like '$tahun%'"); 
        $row = $query->row_array();
        $max_id = $row['max_id']; 
        $max_id1 =(int) substr($max_id,8,5);
        $fst_salesorder_no = $max_id1 +1;
        $maxfst_salesorder_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_salesorder_no);
        return $maxfst_salesorder_no;
    }

    public function getDataPromo($fin_customer_id,$details,$trxDate=null){

        $this->load->model("MSItemunitdetails_model");
        $trxDate = ($trxDate == null) ? date("Y-m-d") : $trxDate;
        $arrPromo = [];

        $ssql = "select a.*,b.fin_member_group_id from msrelations a 
            left join (select * from msmemberships where fst_active = 'A' and ExpiryDate > ?) b on a.fin_relation_id = b.fin_relation_id 
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
            $promoId = $rs[$i]->fin_promo_id;  
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer where fin_promo_id = ?";
            $qr = $this->db->query($ssql,[$promoId]);
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
            $promoId = $rs[$i]->fin_promo_id;  
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer 
                where fin_promo_id = ? 
                and fst_participant_type ='RELATION GROUP' 
                and fin_customer_id = ?";                
            $qr = $this->db->query($ssql,[$promoId,$fin_relation_group_id]);            
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
                $promoId = $rs[$i]->fin_promo_id;  
                $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer 
                    where fin_promo_id = ? 
                    and fst_participant_type ='MEMBER GROUP' 
                    and fin_customer_id = ?";                
                $qr = $this->db->query($ssql,[$promoId,$fin_member_group_id]);
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
            $promoId = $rs[$i]->fin_promo_id;  
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer 
                where fin_promo_id = ? 
                and fst_participant_type ='RELATION' 
                and fin_customer_id = ?";                
            $qr = $this->db->query($ssql,[$promoId,$fin_customer_id]);
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
                    $amount = $item->fdc_qty * $item->fdc_price;
                    $ttlDisc = calculateDisc($item->fst_disc_item , $amount);
                    $totalPurchase += ($item->fdc_qty * $item->fdc_price) - $ttlDisc;                        
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
                    $ssql = "select * from msitems where ItemId = ?";
                    $qr = $this->db->query($ssql,[$details[$y]->fin_item_id]);
                    $rwItem = $qr->row();
                    if(!$rwItem){
                        $details[$y]->fin_item_subgroup_id = null; 
                    }else{
                        $details[$y]->fin_item_subgroup_id = $rwItem->fin_item_subgroup_id;                    
                    }
                }

                if ($rwPromo->fbl_qty_gabungan){ //Proses Qty Gabungan                    
                    $qtyGabungan =$rwPromo->fin_qty_gabungan;
                    $satuanGabungan = $rwPromo->fst_satuan_gabungan;
                    $totalQtyGabungan =0;
                    foreach($details as $item){
                        $isOnRules = false;
                        if(sizeof($itemRules) == 0){
                            $isOnRules = true;
                        }else{
                            $isOnRules = $this->isOnPromoItemRules($itemRules,$item->fin_item_id,$item->fin_item_subgroup_id);
                        }
                        if($isOnRules){    
                            $qtyUnit = $this->MSItemunitdetails_model->getConversionUnit($item->fin_item_id,$item->fdc_qty, $item->fst_unit, $satuanGabungan);
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
                                    $totalQtySubGroup += (float) $item->fdc_qty;
                                }
                            }
                            if ($totalQtySubGroup < (float) $rule->fin_qty){
                                //syarat promo tidak terpenuhi hapus dari array
                                unset($rs[$i]);
                            }
                        }else if($rule->fst_item_type == "ITEM"){
                            $totalQtyItem = 0; 
                            foreach($details as $item){
                                if ($item->fin_item_id == $rule->fin_item_id && $item->fst_unit == $rule->fst_unit){
                                    $totalQtyItem += (float) $item->fdc_qty;
                                }
                            }
                            if ($totalQtyItem < (float) $rule->fin_qty){
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
        $arrPromoId =[];
        foreach($rs as $promo){
            $arrPromoId[] = $promo->fin_promo_id;
        }


        $ssql = "select *,b.fst_item_name as fst_item_name,b.fst_item_code as fst_item_code from mspromo a 
            left join msitems b on a.fin_promo_item_id = b.fin_item_id 
            where a.fin_promo_id in ? order by a.fdt_start desc ,a.fdt_end desc,a.fin_promo_id desc";
        $qr = $this->db->query($ssql,[$arrPromoId]);        
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
                    "fdc_qty"=>$promo->fin_promo_qty,
                    "fst_unit"=>$promo->fin_promo_unit,
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
                    "fdc_qty"=>1,
                    "fst_unit"=>$promo->fin_promo_unit,
                    "fdc_cashback"=>0,
                ];
            }

            if ($promo->fin_cashback != null && $promo->fin_cashback > 0){
                $arrPromoItem[] = [
                    "fin_promo_id"=>$promo->fin_promo_id,
                    "modelPromotion" => "CASHBACK",
                    "fin_item_id"=>0,
                    "fst_item_code"=>"PRO",
                    "fst_item_name"=>null,
                    "fst_custom_item_name"=>"Voucher Cashback Rp." . formatNumber($promo->fin_cashback),
                    "fdc_qty"=>1,
                    "fst_unit"=>"PCS",
                    "fdc_cashback"=> $promo->fin_cashback,
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

    public function getDataOutstanding($fin_customer_id,$current_so_id = 0){
        return [
            "dataOutstanding"=>[],
            "totalOutstanding"=>0
        ]
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

}
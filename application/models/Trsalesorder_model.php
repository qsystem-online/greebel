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

        $rules[] = [
            'field' => 'fin_shipping_address_id',
            'label' => lang('Shipping Address'),
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];

        $rules[] = [
            'field' => 'fin_warehouse_id',
            'label' => lang('Kolom Warehouse'),
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
        $this->load->model("trinventory_model");
        $ssql = "select a.*,
            b.fst_relation_name,a.fin_shipping_address_id,b.fin_cust_pricing_group_id,
            c.fst_fullname as fst_sales_name,d.fst_name as fst_address_name,d.fst_shipping_address 
            from trsalesorder a
            inner join msrelations b on a.fin_relation_id  = b.fin_relation_id 
            inner join users c on a.fin_sales_id  = c.fin_user_id   
            LEFT join msshippingaddress d on a.fin_shipping_address_id = d.fin_shipping_address_id         
            where a.fin_salesorder_id = ?";
        $qr = $this->db->query($ssql, [$fin_salesorder_id]);
        $rwSalesOrder = $qr->row();

        //echo($this->db->last_query());
        //die();

        $ssql = "select a.*,b.fst_item_name,b.fst_item_code,b.fst_max_item_discount from trsalesorderdetails a 
        left join msitems b on a.fin_item_id = b.fin_item_id
        where a.fin_salesorder_id = ?";
		$qr = $this->db->query($ssql,[$fin_salesorder_id]);
        $rsSODetails = $qr->result();        
        for($i = 0; $i < sizeof($rsSODetails) ; $i++ ){            
            $dataD = $rsSODetails[$i];
            $dataD->real_stock = $this->trinventory_model->getStock($dataD->fin_item_id,$dataD->fst_unit,$rwSalesOrder->fin_warehouse_id);
            $dataD->marketing_stock = $this->trinventory_model->getMarketingStock($dataD->fin_item_id,$dataD->fst_unit,$rwSalesOrder->fin_warehouse_id);
            $dataD->fst_basic_unit = $this->msitems_model->getBasicUnit($dataD->fin_item_id);
            $dataD->fdc_conv_to_basic_unit = $this->msitems_model->getConversionUnit($dataD->fin_item_id,$dataD->fst_unit,$dataD->fst_basic_unit);
            $rsSODetails[$i] = $dataD;          
        }

        //voucher from promo
        $ssql ="SELECT * FROM trvoucher where fst_transaction_type ='SALESORDER' and fin_transaction_id = ? and fst_active != 'D' ";
        $qr = $this->db->query($ssql,[$fin_salesorder_id]);
        $rsVoucher = $qr->result();
        foreach($rsVoucher as $voucher){
            $dataD = [
                "fin_rec_id"=>0,
                "fbl_is_promo_disc"=>0,
                "fdb_qty"=> 1,
                "fdc_conv_to_basic_unit"=>1,
                "fdc_disc_amount_per_item"=>1,
                "fdc_price"=>1,
                "fin_item_id"=>1,
                "fin_promo_id"=>$voucher->fin_promo_id,                
                "fin_salesorder_id"=>$fin_salesorder_id,
                "fst_basic_unit"=>"",
                "fst_custom_item_name"=> lang("Voucher cashback promotion") . "(" .  formatNumber($voucher->fdc_value) . ")",
                "fst_disc_item"=>100,
                "fst_item_code"=>"VCR",
                "fst_item_name"=>lang("Voucher cashback promotion") . "(" .  formatNumber($voucher->fdc_value) . ")",
                "fst_max_item_discount"=>100,
                "fst_memo_item"=>"",
                "fst_unit"=>"LBR",
                "marketing_stock"=>0
            ];
            $rsSODetails[] = $dataD;
        }


		$data = [
            "sales_order" => $rwSalesOrder,
            "so_details" => $rsSODetails
		];

		return $data;
    }

    public function getDataHeaderById($finSalesOrderId){
        $ssql = "select * from trsalesorder where fin_salesorder_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        return $qr->row();
        
    }

    public function GenerateSONo($trDate = null) {
        /*
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
        */

        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("salesorder_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_salesorder_no) as max_id FROM trsalesorder where fst_salesorder_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;


    }

    public function DELETE_getDataPromo($fin_customer_id,$details,$trxDate=null){
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


    /**
     * PROMOTION RULES
     * + Yang berhak mendapat promo tersebut hanya customer yang terdaftar didalam participan dimana terdapat 4 aturan:
     *  1. berdasarkan id customer
     *  2. berdasarkan group customer
     *  3. berdasarkan group member (bila customer memiliki kartu member)
     *  4. bila daftar participan ini kosong maka promo tersebut berlaku untuk semua customer
     * + Min Nilai Transaksi & Min Qty Transaksi:
     *  1. Nilai transaksi yang di hitung adalah nilai setelah disc dan sebelum pengenaan pajak
     *  2. Nilai min qty di convert menjadi satuan sesuai dengan ketentuan promo, bila ternyata item yg diorder tidak memiliki satuan tersebut 
     *     maka qty ordernya dianggap 0
     *  3. Bila terdapat daftar isi pada tabel promo term, maka nilai transaksi & qty berlaku hanya bila item tersebut masuk dalam daftar isi
     * + Allow other promo (fbl_promo_gabungan)
     *  1. Bila tidak disetting, Promo di proses berdasarkan prioritas, bila pada daftar promo yang di peroleh belum ada maka tambahkan promo tsb, 
     *     namun bila sudah ada promo yang diterima maka promo allow other promo tidak berlaku
     * + Multiple Prize
     * 
     * 
     */
    public function getDataPromo($fin_customer_id,$ppnPercent,$isIncludePPN,$details,$trxDate=null){
        $this->load->model("msitemunitdetails_model");
        $this->load->model("msitems_model");
        $this->load->model("msgroupitems_model");

        $trxDate = ($trxDate == null) ? date("Y-m-d") : $trxDate;
        $arrPromo = [];

        //Select ALL Promo in date range (tidak termasuk promo disc peritem)
        $ssql = "select a.*,b.fst_item_code,b.fst_item_name from mspromo a
            LEFT JOIN msitems b on a.fin_promo_item_id = b.fin_item_id
            where ? between a.fdt_start and a.fdt_end 
            and a.fst_promo_type in ('OFFICE','ALL') 
            and a.fbl_disc_per_item = false 
            and a.fst_active = 'A'
            and FIND_IN_SET(?,a.fst_list_branch_id) order by a.fin_priority";

        $qr = $this->db->query($ssql,[$trxDate,$this->aauth->get_active_branch_id()]);                
        $rsPromo = $qr->result();
        //$ppnPercent = $dataH["fdc_vat_percent"];
        //$isIncludePPN = $dataH["fbl_is_vat_include"];     

        foreach($rsPromo as $promo){
            $isParticipant = false;
            //Cek apakah user ini termasuk participant
            $ssql = "select a.*,b.fin_member_group_id from msrelations a 
            left join (select * from msmemberships where fst_active = 'A' and fdt_expiry_date > ?) b on a.fin_relation_id = b.fin_relation_id 
            where a.fin_relation_id = ?";
            $qr = $this->db->query($ssql,[$trxDate,$fin_customer_id]);
            $rwCustomer = $qr->row();
            if($rwCustomer == null){
                throw new CustomException(lang("Customer tidak dikenal"),3003,"FAILED",["fin_customer_id"=>$fin_customer_id]);
            }

            $fin_relation_group_id = $rwCustomer->fin_relation_group_id;
            $fin_member_group_id = $rwCustomer->fin_member_group_id;

            //** No participant - ALL CUSTOMER IS PARTICIPANT */
            $ssql = "select count(*) as ttlParticipant from mspromoitemscustomer where fin_promo_id = ?";
            $qr = $this->db->query($ssql,[$promo->fin_promo_id]);            
            $rwParticipant = $qr->row();
            if($rwParticipant->ttlParticipant == 0){
                $isParticipant = true;
            }

            //** RELATION GROUP PARTICIPANT */
            if ($isParticipant == false){
                $ssql = "select * from mspromoitemscustomer 
                    where fin_promo_id = ? 
                    and fst_participant_type ='RELATION GROUP' 
                    and fin_customer_id = ?";                
                $qr = $this->db->query($ssql,[$promo->fin_promo_id ,$fin_relation_group_id]);            
                $rwParticipant = $qr->row();
                if($rwParticipant != null){
                    $isParticipant = true;
                }                        
            }

            //** MEMBER GROUP PARTICIPANT */
            if ($isParticipant == false){
                if ($rwCustomer->fin_member_group_id != null){
                    $ssql = "select * from mspromoitemscustomer 
                        where fin_promo_id = ? 
                        and fst_participant_type ='MEMBER GROUP' 
                        and fin_customer_id = ?";

                    $qr = $this->db->query($ssql,[$promo->fin_promo_id,$rwCustomer->fin_member_group_id]);
                    $rwParticipant = $qr->row();
                    if($rwParticipant != null){
                        $isParticipant = true;
                    }                            
                }
            }

            //** RELATION ID PARTICIPANT */
            if ($isParticipant == false){
                $ssql = "select * from mspromoitemscustomer 
                    where fin_promo_id = ? 
                    and fst_participant_type ='RELATION' 
                    and fin_customer_id = ?";                
                
                $qr = $this->db->query($ssql,[$promo->fin_promo_id,$rwCustomer->fin_relation_id]);
                $rwParticipant = $qr->row();
                if($rwParticipant != null){
                    $isParticipant = true;
                }                        
            }

            if ($isParticipant){
                //VALIDATION PROMO RULE
                //Min Purchase Amount DPP -> bila terdaftar barang di dlm term nilai yang diakui hanya nilai yg barangnya terdaftar
                $ssql = "select * from mspromoitems where fin_promo_id = ? and fst_active ='A'";
                $qr  = $this->db->query($ssql,$promo->fin_promo_id);                
                $itemTermList = $qr->result();
                
                $ttlDPP = 0;
                $ttlQtyPromoUnit = 0;
                if(sizeof($itemTermList) == 0){
                    //Semua barang bisa di gunakan untuk mencapai batas ketentuan amount dan qty
                    foreach($details as $dataD){
                        //Dipastikan semua data valid, data akan divalidasi pada saat simpan data
                        $total = $dataD->fdb_qty * $dataD->fdc_price;
                        $discAmount = calculateDisc($dataD->fst_disc_item,$subTotal);
                        $subTotal = $total - $discAmount;
                        if ($isIncludePPN){
                            $subTotalDPP = $subTotal / (1 + ($ppnPercent /100));
                        }else{
                            $subTotalDPP = $subTotal;
                        }
                        $ttlDPP += $subTotalDPP;
                        $ttlQtyPromoUnit += $this->msitems_model->getQtyConvertUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit,$promo->fst_promo_unit);
                    }
                }else{
                    //Hanya barang yang terdaftar dalam term item yang di perhitungkan nilai amount & qtynya
                    //Tandai semua detail order termasuk dalam list                    
                    for ($i = 0 ; $i < sizeof($details) ; $i++){                        
                        $dataD = $details[$i];
                        $dataD->isTermPromoItem = false;
                        foreach($itemTermList as $itemTerm){
                            //CEK ITEM
                            if ($itemTerm->fst_item_type == "ITEM"){
                                if ($itemTerm->fin_item_id == $dataD->fin_item_id){
                                    $dataD->isTermPromoItem = true;
                                }
                            }else if($itemTerm->fst_item_type == "SUB GROUP"){
                                //CEK SUB GROUP
                                if ($this->msgroupitems_model->isInGroup($dataD->fin_item_id,$itemTerm->fin_item_id)){
                                    $dataD->isTermPromoItem = true;
                                }
                            }                            
                        }
                        $details[$i] = $dataD;
                    }
                    //Process ALL detail isTermPromoItem true
                    foreach($details as $dataD){
                        if ($dataD->isTermPromoItem == true){
                            $total = $dataD->fdb_qty * $dataD->fdc_price;
                            $discAmount = calculateDisc($dataD->fst_disc_item,$total);
                            $subTotal = $total - $discAmount;
                            if ($isIncludePPN){
                                $subTotalDPP = $subTotal / (1 + ($ppnPercent /100));
                            }else{
                                $subTotalDPP = $subTotal;
                            }
                            $ttlDPP += $subTotalDPP;
                            //$ttlQtyPromoUnit += $this->msitems_model->getQtyConvertUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit,$promo->fst_promo_unit);
                            $ttlQtyPromoUnit += $this->msitems_model->getQtyConvertUnit($dataD->fin_item_id,$dataD->fdb_qty,$dataD->fst_unit,$promo->fst_unit_gabungan);
                        }                        
                    }
                }

                if ($ttlDPP >= $promo->fdc_min_total_purchase && $ttlQtyPromoUnit >= $promo->fdb_qty_gabungan ){
                    //Transaksi sesuai ketentuan Promo
                    //Cek promo boleh digabung tidak
                    if ($promo->fbl_promo_gabungan == 1){
                        //$arrPromo[] = $promo;
                        $promo->tec_notes = "OK";
                    }else{
                        if (sizeof($arrPromo) == 0 ){
                            //$arrPromo[] = $promo;
                            $promo->tec_notes = "OK";
                            $arrPromo[] = $promo;            
                        }
                        break; //exit from foreach
                    }     
                    
                    $arrPromo[] = $promo;
                }else{
                    $promo->tec_notes = "total amount : $ttlDPP  || total qty : $ttlQtyPromoUnit";
                }

                
                
            }else{
                $promo->tec_notes = "Is Not Participant";
                $arrPromo[] = $promo;
            }
        } //End foreach
        return $arrPromo;
    }


    public function getPendingSOList($finCustomerId){
        $ssql ="select a.fin_salesorder_id,a.fst_salesorder_no,a.fdc_total,a.fdc_downpayment_claimed, b.ttl_inv  from trsalesorder a 
        inner join (
            select fin_salesorder_id, ifnull(sum(fdc_total),0) as ttl_inv from trinvoice 
            where fst_active ='A' group by fin_salesorder_id
        ) b on a.fin_salesorder_id = b.fin_salesorder_id
        WHERE a.fin_relation_id = ? and a.fbl_is_closed = 0 and a.fst_active ='A' ";

        $qr = $this->db->query($ssql,[$finCustomerId]);
        return  $qr->result();
    }

    public function getDataOutstanding($fin_customer_id,$fdc_credit_limit,$current_so_id = 0){
        $this->load->model("trchequeflow_model");
        $this->load->model("trinvoice_model");
        $this->load->model("glledger_model");

        $fdc_credit_limit = (float) $fdc_credit_limit;
        $piutangOutstanding = $this->glledger_model->getTotalPiutang($fin_customer_id);  //Total hutang, cek di jurnal;
        $soPendinglist = $this->getPendingSOList($fin_customer_id);

        $totalSOPending = 0;
        foreach($soPendinglist as $soPending){
            $totalSOPending += $soPending->fdc_total - $soPending->fdc_downpayment_claimed -$soPending->ttl_inv;
        }


        $chequePendingList  = $this->trchequeflow_model->getPendingChequeList($fin_customer_id,"IN");
        $totalChequePending = 0;
        foreach($chequePendingList as $chequePending){
            $totalChequePending += $chequePending->fdc_amount;
        }

        $totalOutstanding = ($piutangOutstanding + $totalSOPending + $totalChequePending);

        return [
            "maxCreditLimit"=>$fdc_credit_limit,
            "piutangOutstanding"=> $piutangOutstanding,
            "totalSOPending"=> $totalSOPending,
            "totalChequePending"=>$totalChequePending,
            "totalOutstanding"=> $totalOutstanding,
            "sisaPlafon"=> $fdc_credit_limit - $totalOutstanding,
            //"invoiceJatuhTempoMelebihBatas"=>$arrInvoiceJatuhTempoMelebihBatas,
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

        //Aktifkan bila terdapat voucher cashback
        $ssql= "update trvoucher set fst_active ='A' where fst_transaction_type ='SALESORDER' and fin_transaction_id = ?"; 
        $this->db->query($ssql,[$fin_salesorder_id]);
        $this->my_model->throwIfDBError();

        //Bila terdapat DP jurnal DP tersebut
        $ssql ="select * from trsalesorder where fin_salesorder_id = ?";
        $qr = $this->db->query($ssql,[$fin_salesorder_id]);
        $dataH = $qr->row_array();
        if ($dataH["fdc_downpayment"] > 0 && $dataH["fst_active"] == "A"){
            $this->load->model("glledger_model");

            $dataJurnal = [];

            //Piutang Dagang
            $dataJurnal[] = [
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("AR_DAGANG_LOKAL"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_datetime"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_trx_no"=>$dataH["fst_salesorder_no"],
                    "fst_reference"=>$dataH["fst_memo"],
                    "fdc_debit"=>$dataH["fdc_downpayment"] * $dataH["fdc_exchange_rate_idr"],
                    "fdc_origin_debit"=>$dataH["fdc_downpayment"],
                    "fdc_credit"=>0,
                    "fdc_origin_credit"=>0,
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fin_pcc_id"=>null,
                    "fin_pc_divisi_id"=>null,
                    "fin_pc_customer_id"=>null,
                    "fin_pc_project_id"=>null,
                    "fin_relation_id"=>$dataH["fin_relation_id"],
                    "fst_active"=>"A"
            ];

            if ($dataH["fbl_dp_inc_ppn"] == 1){
                $dp = $dataH["fdc_downpayment"] / (1 + ($dataH["fdc_vat_percent"]/100));
                $ppn = $dp * ($dataH["fdc_vat_percent"]/100);
                //Uang Muka
                $dataJurnal[] =[
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("DP_IN_LOKAL"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_datetime"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_trx_no"=>$dataH["fst_salesorder_no"],
                    "fst_reference"=>$dataH["fst_memo"],
                    "fdc_debit"=>0,
                    "fdc_origin_debit"=>0,
                    "fdc_credit"=>$dp * $dataH["fdc_exchange_rate_idr"],
                    "fdc_origin_credit"=>$dp,
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fin_pcc_id"=>null,
                    "fin_pc_divisi_id"=>null,
                    "fin_pc_customer_id"=>null,
                    "fin_pc_project_id"=>null,
                    "fin_relation_id"=>null,
                    "fst_active"=>"A"
                ];

                //PPN
                $dataJurnal[] =[
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("PPN_KELUARAN"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_datetime"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_trx_no"=>$dataH["fst_salesorder_no"],
                    "fst_reference"=>$dataH["fst_memo"],
                    "fdc_debit"=>0,
                    "fdc_origin_debit"=>0,
                    "fdc_credit"=>$ppn * $dataH["fdc_exchange_rate_idr"],
                    "fdc_origin_credit"=>$ppn,
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fin_pcc_id"=>null,
                    "fin_pc_divisi_id"=>null,
                    "fin_pc_customer_id"=>null,
                    "fin_pc_project_id"=>null,
                    "fin_relation_id"=>null,
                    "fst_active"=>"A"
                ];
            }else{
                //Uang Muka
                $dataJurnal[] =[
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("DP_IN_LOKAL"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_datetime"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_trx_no"=>$dataH["fst_salesorder_no"],
                    "fst_reference"=>$dataH["fst_memo"],
                    "fdc_debit"=>0,
                    "fdc_origin_debit"=>0,
                    "fdc_credit"=>$dataH["fdc_downpayment"] * $dataH["fdc_exchange_rate_idr"],
                    "fdc_origin_credit"=>$dataH["fdc_downpayment"],
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fin_pcc_id"=>null,
                    "fin_pc_divisi_id"=>null,
                    "fin_pc_customer_id"=>null,
                    "fin_pc_project_id"=>null,
                    "fin_relation_id"=>null,
                    "fst_active"=>"A"
                ];

            }
           
            $result = $this->glledger_model->createJurnal($dataJurnal);
            if($result["status"] != "SUCCESS"){
                throw new CustomException("Error Create Jurnal !", 3001,"FAILED",$dataJurnal);
            }
        }


    }

    public function unposting($fin_salesorder_id){

        //Cancel Jurnal
        $this->load->model("glledger_model");
        $result = $this->glledger_model->cancelJurnal(JURNAL_TRX_SC_SO,$fin_salesorder_id);
        
        //Suspend Voucher
        $ssql = "UPDATE trvoucher SET fst_active ='S' where fst_transaction_type ='SALESORDER' and fin_transaction_id = ?";
        $this->db->query($ssql,[$fin_salesorder_id]);
        $this->my_model->throwIfDBError();

        $ssql = "UPDATE trsalesorder SET fst_active ='S' where fin_salesorder_id = ?";
        $this->db->query($ssql,[$fin_salesorder_id]);
        $this->my_model->throwIfDBError();      
        
        //Cancel Aproval Requirment
        $ssql = "DELETE from trverification where fst_controller ='SO' and fin_transaction_id = ?";
        $this->db->query($ssql,[$fin_salesorder_id]);
        $this->my_model->throwIfDBError();      
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
    public function cancelApproval($finSalesOrderId){
        /**
         * tidak dapat dibatalkan bila sudah ada pembayaran DP
         * Approval hanya bisa dicancel bila belum ada pengiriman barang 
         * Cek bila ada voucher cashback yang digunakan
         * 
         */ 
        $ssql = "select * from trsalesorder where fin_salesorder_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        $rw =$qr->row();
        if($rw == null){
            throw new CustomException(lang("ID SO tidak dikenal !"),3003,"FAILED",null);            
            //$resp =["status"=>"FAILED","message"=>lang("ID SO tidak dikenal !")];
            //return $resp;
        }        

        //cek bila sudah ada pem bayaran DP
        if ($rw->fdc_downpayment_paid > 0 ){
            throw new CustomException(lang("Status approval SO tidak dapat dirubah karena sudah terjadi pembayaran DP !"),3003,"FAILED",null);
            //$resp =["status"=>"FAILED","message"=>lang("Status approval SO tidak dapat dirubah karena sudah terjadi pembayaran DP !")];
            //return $resp;
        }

        //cek bila sudah ada pengiriman barang
        $ssql = "select * from trsalesorderdetails where fin_salesorder_id = ? and fdb_qty_out > 0";
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        $rw = $qr->row();
        if ($rw != null){
            throw new CustomException(lang("Status approval SO tidak dapat dirubah karena sudah terjadi pengiriman barang !"),3003,"FAILED",null);
            //$resp =["status"=>"FAILED","message"=>lang("Status approval SO tidak dapat dirubah karena sudah terjadi pengiriman barang !")];
            //return $resp;
        }

        //cek bila  ada voucher cashback yang sudah digunakan
        $ssql = "select * from trvoucher where fst_transaction_type ='SALESORDER' and fin_transaction_id = ? and fbl_is_used = 1";        
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        $rw = $qr->row();
        if ($rw != null){
            throw new CustomException(lang("Status approval SO tidak dapat dirubah karena sudah ada voucher cashback yang digunakan !"),3003,"FAILED",null);
            //$resp =["status"=>"FAILED","message"=>lang("Status approval SO tidak dapat dirubah karena sudah ada voucher cashback yang digunakan !")];
            //return $resp;
        }
        $this->unposting($finSalesOrderId);
    }

    public function show_transaction($finSalesOrderId){
        redirect(site_url()."tr/sales_order/view/$finSalesOrderId", 'refresh');
    }

    public function getAuthorizationList($dataH,$details){
        $this->load->model("trinventory_model");
        
        $needAuthorize = false;
        $needAuthorizeList = [
            "default"=>[],
            "out_of_stock"=>[],
            "over_max_disc"=>[],
            "over_credit_limit"=>null,
            "over_tolerance_invoice"=>[],
        ];

        //Authorize Default

        //Authorize Stock kurang (Yang dijadikan acuan adalah marketing stock)
		$arrOutofStock =[];
		$authorizeOutofStock = false;
		foreach ($details as $item){
            $basicUnit = $this->msitems_model->getBasicUnit($item->fin_item_id);
            $stockInBasicUnit = $this->trinventory_model->getMarketingStock($item->fin_item_id,$basicUnit,$dataH["fin_warehouse_id"]);                       
            $stock = $this->msitems_model->getQtyConvertUnit($item->fin_item_id,$stockInBasicUnit,$basicUnit,$item->fst_unit);
            
            //$conversionUnit= $this->msitems_model->getBasicUnit($item->fin_item_id);
            $this->msitems_model->getSimpleDataById($item->fin_item_id);

			if($item->fdb_qty > $stockInBasicUnit){
				$authorizeOutofStock = true;
				$needAuthorize = true;
				$arrOutofStock[] = [
					"fin_item_id"=>$item->fin_item_id,
					"fst_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"stock"=>$stock
				];
			}
        }
        if ($authorizeOutofStock){
            $needAuthorizeList["out_of_stock"] = $arrOutofStock;
        }        


        //Authorize if disc more than max disc per item
		$authorizeMaxDisc = false;
		$arrMaxDisc=[];
		foreach ($details as $item){
            //$amount = $item->fdb_qty * $item->fdc_price;
			$maxDiscAllowed = calculateDisc($item->fst_max_item_discount,$item->fdc_price);			
			if($maxDiscAllowed < $item->fdc_disc_amount_per_item){
				$authorizeMaxDisc = true;
				$needAuthorize = true;
				$arrMaxDisc[] = [
					"fin_item_id"=>$item->fin_item_id,
					"fst_item_name"=>$item->fst_custom_item_name,
					"fst_unit"=>$item->fst_unit,
					"fdb_qty"=>$item->fdb_qty,
					"fdc_price"=>$item->fdc_price,
					"fst_disc_item"=>$item->fst_disc_item,
					"fdc_disc_amount_per_item"=>$item->fdc_disc_amount_per_item,
					"fdc_max_disc_amount"=>$maxDiscAllowed,
				];
			}
        }
        if ($authorizeMaxDisc){
            $needAuthorizeList["over_max_disc"] = $arrMaxDisc;
        }     


        //Authorize Order over Credit Limit
        //Cek Credit Limit, need authorization if credit limit is over
        $subTtl = 0;
        $ttlDiscAmount = 0;
        foreach($details as $dataD){
            $ttl = $dataD->fdb_qty * $dataD->fdc_price;
            $subTtl += $ttl;
            //$ttlDiscAmount += calculateDisc($dataD->fst_disc_item,$ttl);
            $ttlDiscAmount += $dataD->fdc_disc_amount_per_item;
        }
        $dataH["fdc_vat_amount"] = parseNumber($dataH["fdc_vat_amount"]);
        $dataH["fdc_downpayment"] = parseNumber($dataH["fdc_downpayment"]);        
		$grandTotal = $subTtl - $ttlDiscAmount + $dataH["fdc_vat_amount"] - $dataH["fdc_downpayment"];
		$maxCreditLimit = $this->msrelations_model->getCreditLimit($dataH["fin_relation_id"]);				
        $arrOutstanding = $this->trsalesorder_model->getDataOutstanding($dataH["fin_relation_id"],$maxCreditLimit);        
		$totalOutstanding = $arrOutstanding["totalOutstanding"];
        $authorizeCreditLimit = false;
        
		if ($totalOutstanding + $grandTotal > $maxCreditLimit){
            $arrOutstanding["maxCreditLimit"] = $maxCreditLimit;
            $arrOutstanding["currentOrder"] = $grandTotal;
			$authorizeCreditLimit = true;
			$needAuthorize = true;
		}

        if($authorizeCreditLimit){
            $needAuthorizeList["over_credit_limit"] = $arrOutstanding;
        }

        //Authorize Invoce jatuh tempo yang telah melewati batas toleransi dan belum dibayarkan
        $batasToleranInvoiceJatuhTempo =  getDbConfig("invoice_duedate_tolerance_day"); //hari
        $arrInvoiceJatuhTempoMelebihBatas = $this->trinvoice_model->getPastDueInvoiceOverToleranceList($batasToleranInvoiceJatuhTempo,$dataH["fin_relation_id"]);
        if (sizeof($arrInvoiceJatuhTempoMelebihBatas) > 0){
            $needAuthorize = true;
        }        
        $needAuthorizeList["over_tolerance_invoice"] = $arrInvoiceJatuhTempoMelebihBatas;

        return [
            "need_authorize" => $needAuthorize,
            "authorize_list" => $needAuthorizeList
        ];
    }

    public function generateApprovalData($needAuthorizeList,$insertId,$fstSalesOrderNo){
        if ($needAuthorizeList["need_authorize"] == true){
            //$authorizeOutofStock
            //Get Master
            $this->load->model("trverification_model");

            $authorizeList = $needAuthorizeList["authorize_list"];
            //OutOfStock
            $strMessage ="";
            foreach($authorizeList["out_of_stock"] as $item){
                $strMessage .= "Out Of Stock : "  . $item["fst_item_name"] . " Ready :" . formatNumber($item["stock"]) . " request :" . $item["fdb_qty"] . " " . $item["fst_unit"] . "<br>";
            };
            if ($strMessage != ""){
                //createAuthorize($controller,$module,$transactionId,$message,$notes = null,$transactionNo = null)
                $this->trverification_model->createAuthorize("SO","QtyOutStock",$insertId,$strMessage,null,$fstSalesOrderNo);
            }

            //Over max Disc
            $strMessage ="";
            foreach($authorizeList["over_max_disc"] as $item){			
                $strMessage .= "Over Max Disc : " . $item["fst_item_name"] . " " . $item["fdb_qty"] . " " . $item["fst_unit"]  . " | Max Disc :" . formatNumber($item["fdc_max_disc_amount"]) . " Current Disc :" . formatNumber($item["fdc_disc_amount_per_item"]) . "<br>";
            };
            if ($strMessage != ""){
                $this->trverification_model->createAuthorize("SO","MaxDisc",$insertId,$strMessage,null,$fstSalesOrderNo);
            }

            //over_credit_limit
            $strMessage ="";
            if ($authorizeList["over_credit_limit"] != null){
                $limit = $authorizeList["over_credit_limit"];
                $strMessage .= "Total Credit Limit : ". formatNumber($limit["maxCreditLimit"]) ."<br>";
                $strMessage .= " | Invoice Outstanding :" . formatNumber($limit["piutangOutstanding"]) ."<br>";
                $strMessage .= " | Check / Giro Pending :" . formatNumber($limit["totalChequePending"]) ."<br>";
                $strMessage .= " | Sales Order Pending :" . formatNumber($limit["totalSOPending"]) ."<br>";			
                $strMessage .= " | Current Plafon : " . formatNumber($limit["sisaPlafon"]) ."<br>";
                $strMessage .= " | Current Order :" . formatNumber($limit["currentOrder"]) ."<br>";
            }
            if ($strMessage != ""){
                $this->trverification_model->createAuthorize("SO","CreditLimit",$insertId,$strMessage,null,$fstSalesOrderNo);
            }

            //over_tolerance_invoice
            $strMessage ="";
            foreach($authorizeList["over_tolerance_invoice"] as $inv){			
                //messageList[] = "Over Max Disc : " + item.fst_item_name + " " + item.fdb_qty + " " + item.fst_unit  + " | Max Disc :" + App.money_format(item.fdc_max_disc_amount) + " Current Disc :" + App.money_format(item.fdc_disc_amount);
                $dueDate = date_create($inv->fdt_payment_due_date);
                $dateNow = date_create(date("Y-m-d"));
                $diff = date_diff($dueDate,$dateNow);
                $overDays = $diff->format("%R%a days");
                $strMessage .= "Invoice " . $inv->fst_inv_no . " | Total Outstanding : " .formatNumber($inv->fdc_total - $inv->fdc_total_paid - $inv->fdc_total_return) . " | Due Date : " . dateFormat($inv->fdt_payment_due_date,"Y-m-d",$outputFormat = 'd-m-Y') . " | Over : " . $overDays ."<br>";
            };
            if ($strMessage != ""){
                $this->trverification_model->createAuthorize("SO","OverDueDateTolerance",$insertId,$strMessage,null,$fstSalesOrderNo);
            }
            
        }

    }

    public function isEditable($finSalesOrderId){
        /**
         * + False: kalau transaksi sudah ada yang melakukan approval
         * + False: kalau sudah di tarik menjadi SJ
         * + False: Sudah ada pembayaran DP
         */
        $ssql = "SELECT * from trverification  
            where fst_verification_type = 'SO' 
            AND fin_transaction_id = ?
            AND fst_verification_status in ('VF','RJ','VD') ";
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        $rw = $qr->row();
        if($rw != null){
            return [
                "status"=>"FAILED",
                "message"=> sprintf(lang("%s telah dilakukan proses approval"),$rw->fst_salesorder_no)
            ];
        }

        $ssql ="SELECT * FROM trsuratjalan where fst_sj_type ='SO' and fin_trans_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        $rw = $qr->row();
        if ($rw != null){
            return [
                "status"=>"FAILED",
                "message"=> sprintf(lang("telah terbi surat jalan %s "),$rw->fst_sj_no)
            ];
        }

        $so = $this->getSimpleDataById($finSalesOrderId);
        if ($so != null){
            if ($so->fdc_downpayment_paid > 0){
                return [
                    "status"=>"FAILED",
                    "message"=> lang("Telah diterima pembayaran DP")
                ];  
            }
        }   

        return [
            "status"=>"SUCCESS",
            "message"=>""
        ];

    }

    public function delete($finSalesOrderId, $softdelete = TRUE,$data=null){
        //Delete Detail
        if ($softdelete){
            $ssql = "update trsalesorderdetails set fst_active ='D' where fin_salesorder_id = ?";
        }else{
            $ssql = "delete from trsalesorderdetails where fin_salesorder_id = ?";
        }
        $this->db->query($ssql,[$finSalesOrderId]);
        throwIfDBError();

        //Delete voucher
        if ($softdelete){
            $ssql = "update trvoucher set fst_active ='D' where fst_transaction_type = 'SALESORDER' and fin_transaction_id = ?";
        }else{
            $ssql = "delete from trsalesorderdetails where fst_transaction_type = 'SALESORDER' and fin_transaction_id = ?";
        }
        $this->db->query($ssql,[$finSalesOrderId]);
        throwIfDBError();

        parent::delete($finSalesOrderId,$softdelete,$data);        
    }

    public function getUnpaidDPList($finCustomerId = "",$fstCurrCode=""){
        if ($finCustomerId == "" ){
            $ssql ="select fin_salesorder_id,fst_salesorder_no,fdc_downpayment,fdc_downpayment_paid,fdc_vat_percent from trsalesorder 
                where fdc_downpayment > fdc_downpayment_paid and fst_active ='A'";
            $qr = $this->db->query($ssql,[]);
        }else{
            $ssql ="select fin_salesorder_id,fst_salesorder_no,fdc_downpayment,fdc_downpayment_paid,fdc_vat_percent from trsalesorder 
                where   fdc_downpayment  > fdc_downpayment_paid and fst_active ='A' 
                and fin_relation_id = ? and fst_curr_code = ?";
            $qr = $this->db->query($ssql,[$finCustomerId,$fstCurrCode]);
        }
        return $qr->result();
    }

    public function updateClosedStatus($finSOId){
        $ssql = "select * from trsalesorderdetails where fin_salesorder_id = ? and fdb_qty > fdb_qty_out";
        $qr = $this->db->query($ssql,$finSOId);
        if ($qr->row() == null){
            //Penerimaan lengkap close SO
            $ssql = "update trsalesorder set fdt_closed_datetime = now() , fbl_is_closed = 1, fst_closed_notes = 'AUTO - ".date("Y-m-d H:i:s") ."' where fin_salesorder_id = ?";
            $this->db->query($ssql,[$finSOId]);
        }else{
            $ssql = "update trsalesorder set fdt_closed_datetime = null , fbl_is_closed = 0, fst_closed_notes = null where fin_salesorder_id = ?";
            $this->db->query($ssql,[$finSOId]);
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

    public function getDataVoucher($finSalesOrderId){
		$ssql ="SELECT a.*,
            b.fst_relation_name as fst_cust_name,
            c.fst_curr_name,
            d.fst_shipping_address 
			FROM trsalesorder a
            INNER JOIN msrelations b on a.fin_relation_id = b.fin_relation_id 
            INNER JOIN mscurrencies c on a.fst_curr_code = c.fst_curr_code
            INNER JOIN msshippingaddress d on a.fin_shipping_address_id = d.fin_shipping_address_id 
			WHERE a.fin_salesorder_id = ? and a.fst_active = 'A' ";
			
        $qr = $this->db->query($ssql,[$finSalesOrderId]);
        $header = $qr->row_array();
        $details =[];
		if ($header != null){      
            $ssql = "SELECT a.*,
                b.fst_item_code,b.fst_item_name
                FROM trsalesorderdetails a
                INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id
                WHERE fin_salesorder_id = ?";

            $qr = $this->db->query($ssql,[$finSalesOrderId]);

            $details = $qr->result_array();
        }
		return [
			"header"=>$header,
			"details"=>$details
		];
    }
    
    public function closeManual($finSalesOrderId,$fstClosedNote,$isClosed){
        $ssql = "UPDATE trsalesorder set fbl_is_closed = ?, fdt_closed_datetime = now(),fst_closed_notes = ? where fin_salesorder_id = ?";
        $this->db->query($ssql,[$isClosed,$fstClosedNote,$finSalesOrderId]);
    }


}



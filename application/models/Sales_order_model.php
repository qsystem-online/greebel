<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_order_model extends MY_Model {
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

        $ssql = "select a.*,b.ItemName,c.SellingPrice,d.ItemDiscount from trsalesorderdetails a left join msitems b on a.fin_item_id = b.ItemId
        left join msitemspecialpricinggroupdetails c on a.fdc_price = c.SellingPrice
        left join msitemdiscounts d on a.fst_disc_item = d.ItemDiscount 
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

    public function getDataPromo($fin_customer_id,$details){
        //echo "Details Ordered  :<br>";
        //var_dump($details);

        $this->load->model("MSItemunitdetails_model");
        //get active promo for this customer;
        $ssql = "select b.*,c.ItemName as fst_promo_item_name  from mspromoitemscustomer a 
            inner join mspromo b on a.fin_promo_id = b.fin_promo_id
            left join msitems c on b.fin_promo_item_id = c.ItemId 
            where a.fin_customer_id = ? and 
            DATE(NOW()) between b.fdt_start and b.fdt_end and
            b.fst_active = 'A'
        ";
        $qr = $this->db->query($ssql,[$fin_customer_id]);
        $rsPromo = $qr->result();
        //promoItem =["fin_item_id"=>1,"fdc_qty"=>10,"fdc_cashback"=>1000,"Other Item"=>"indomie jumbo 10 bungkus"]
        $arrPromoItem =[];
        //echo "<br><br><br><br><br>GET Master Promo :<br>";
        //print_r($rsPromo);
        foreach($rsPromo as $rwPromo){
            //cek kalau pembelian memenuhi syarat promo
            $ssql ="select * from mspromoitems where fin_promo_id = " . $rwPromo->fin_promo_id ." and fst_active ='A'";
            $qr = $this->db->query($ssql,[]);
            $rsRule = $qr->result();
            $isValid = true;

            $isQtyGabungan = $rwPromo->fbl_qty_gabungan;
            $qtyGabungan =$rwPromo->fin_qty_gabungan;
            $satuanGabungan = $rwPromo->fst_satuan_gabungan;
            //bila isQtyGabuangan true, qty detail di abaikan              
            //echo "<br><br><br><br><br>GET Promo Detail :<br>";
            //var_dump($rsRule);
            foreach($rsRule as $rule){
                if ($isQtyGabungan){
                    $arrItem[$rule->fin_item_id] = [
                        "ttl_qty"=>0,
                        "target_qty" =>0,
                        "target_unit" =>$satuanGabungan
                    ];
                }else{
                    $arrItem[$rule->fin_item_id] = [
                        "ttl_qty"=>0,
                        "target_qty" =>(int) $rule->fin_qty,
                        "target_unit" =>$rule->fst_unit
                    ];
                }
                
               

                foreach($details as $item){
                    //skip kalau item adalah barang promo bila harga 0
                    
                    if ($item->fin_item_id == $rule->fin_item_id){
                        $targetUnit = $arrItem[$rule->fin_item_id]["target_unit"];
                        $tmpArr = $arrItem[$rule->fin_item_id];
                        $qty = $this->MSItemunitdetails_model->getConversionUnit($rule->fin_item_id,$item->fdc_qty, $item->fst_unit, $targetUnit);
                        $tmpArr["ttl_qty"] += $qty;
                        $arrItem[$rule->fin_item_id] = $tmpArr;
                    }
                }
            }
            //echo "<br><br><br><br><br>GET  arr Item  :<br>";
            //var_dump($arrItem);

            if ($isQtyGabungan){
                $total = 0;
                foreach($arrItem as $item){
                    $total += $item["ttl_qty"];                        
                }
                $dapatPromo =false;
                if ($total >= $qtyGabungan){
                    $dapatPromo =true;
                }
            }else{
                $dapatPromo =true;
                foreach($arrItem as $item){
                    if ($item["ttl_qty"] < $item["target_qty"]){
                        $dapatPromo = false;
                    }                      
                }
            }

            if ($dapatPromo){
                $arrPromoItem[] = [
                    "fin_promo_id"=>$rwPromo->fin_promo_id,
                    "fin_item_id"=>$rwPromo->fin_promo_item_id,
                    "fst_item_name"=>$rwPromo->fst_promo_item_name,
                    "fdc_qty"=>$rwPromo->fin_promo_qty,
                    "fst_unit"=>$rwPromo->fin_promo_unit,
                    "fdc_cashback"=>$rwPromo->fin_cashback,
                    "Other Item"=>$rwPromo->fst_other_prize
                ];
            }

            if (sizeof($arrPromoItem) > 0){
                return $arrPromoItem;
            }else{
                return false;
            }
        }
        return false;
    }

    /*public function getSales_order() {
        $query = $this->db->get('trsalesorder');
        return $query->result_array();
    }*/
}
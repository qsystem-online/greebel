<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msitems_model extends MY_Model
{
    public $tableName = "msitems";
    public $pkey = "fin_item_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_item_id)
    {
        //$ssql = "select * from " . $this->tableName . " where fin_item_id = ? and fst_active = 'A'";
        $ssql = "select a.*,b.fst_item_maingroup_name,c.fst_item_group_name,e.fst_linebusiness_name,ifnull(f.ttl_record,0) as fin_ttl_invnetory from " . $this->tableName . " a 
        left join msmaingroupitems b on a.fin_item_maingroup_id = b.fin_item_maingroup_id 
        left join msgroupitems c on a.fin_item_group_id = c.fin_item_group_id  
        left join mslinebusiness e on a.fst_linebusiness_id = e.fin_linebusiness_id
        left join (select fin_item_id,count(*) ttl_record from trinventory group by fin_item_id) f on a.fin_item_id = f.fin_item_id  
        where a.fin_item_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rwItem = $qr->row();

		if ($rwItem) {
			if (file_exists(FCPATH . 'assets/app/items/image/'.$rwItem->fst_item_code . '.jpg')) {
				$imageURL = site_url() . 'assets/app/items/image/' . $rwItem->fst_item_code . '.jpg';
			} else {

				$imageURL = site_url() . 'assets/app/items/image/default.jpg';
			}
			$rwItem->imageURL = $imageURL;
		}

        $ssql = "select * from msitemunitdetails where fin_item_id = ?";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rsUnitDetail = $qr->result();

        $ssql = "select a.*,b.fst_item_name from msitembomdetails a left join " . $this->tableName . " b on a.fin_item_id_bom = b.fin_item_id  where a.fin_item_id = ?";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rsBomDetail = $qr->result();

        $ssql = "select a.*,b.fst_cust_pricing_group_name from msitemspecialpricinggroupdetails a left join mscustpricinggroups b on a.fin_cust_pricing_group_id = b.fin_cust_pricing_group_id  where a.fin_item_id = ?";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rsSpecialPricing = $qr->result();

        $data = [
            "ms_items" => $rwItem,
            "unit_Detail" => $rsUnitDetail,
            "bom_Detail" => $rsBomDetail,
            "special_Pricing" => $rsSpecialPricing,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_item_code',
            'label' => 'Item Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_item_name',
            'label' => 'Item Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_vendor_item_name',
            'label' => 'Vendor Item Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    public function getAllList($filter=null,$selectCol = "fin_item_id,fst_item_code,fst_item_name"){        
        if ($filter ==null){
            $ssql = "select $selectCol  from " . $this->tableName . " where fst_active = 'A' order by fst_item_name";
            $qr = $this->db->query($ssql, []);
        }else{
            $ssql = "select $selectCol from " . $this->tableName . " where fst_active = 'A' and (fst_item_code like ? or fst_item_name like ?) order by fst_item_name";
            $qr = $this->db->query($ssql, ["%$filter%","%$filter%"]);
        }
        $rs = $qr->result();
        return $rs;
    }

    public function getSellingPrice($fin_item_id,$fst_unit,$fin_customer_id) {
        //$this->load->model("MSRelations_model");
        //$this->MSRelations_model->
        $ssql ="select * from msrelations where fin_relation_id = ?";
        $qr = $this->db->query($ssql,[$fin_customer_id]);
        $rw = $qr->row();
        if($rw){            
            $fin_cust_pricing_group_id = $rw->fin_cust_pricing_group_id;
            // cek Special item
            $ssql = "select * from msitemspecialpricinggroupdetails where fin_item_id = ? and fst_unit = ? and fin_cust_pricing_group_id = ? and fst_active = 'A'";
            $qr = $this->db->query($ssql,[$fin_item_id,$fst_unit,$fin_cust_pricing_group_id]);
            $rwPrice = $qr->row();
            if($rwPrice){
                return $rwPrice->fdc_selling_price;
            }else{
                //item fst_unit details
                $ssql = "select * from msitemunitdetails where fin_item_id = ? and fst_unit = ? and fst_active = 'A'";
                $qr = $this->db->query($ssql,[$fin_item_id,$fst_unit]);
                $rwPrice = $qr->row();
                if($rwPrice){
                    $sellingPrice = $rwPrice->fdc_price_list;
                    //Cek Group Price List
                    $ssql = "select * from mscustpricinggroups where fin_cust_pricing_group_id = ?";
                    $qr = $this->db->query($ssql,[$fin_cust_pricing_group_id]);
                    $rwGroupPrice = $qr->row();
                    if($rwGroupPrice){
                        if ($rwGroupPrice->fdc_percent_of_price_list == 0){
                            return $sellingPrice - $rwGroupPrice->fdc_difference_in_amount;
                        }else{
                            return $sellingPrice * ($rwGroupPrice->fdc_percent_of_price_list /100);
                        }
                    }else{
                        return $sellingPrice;
                    }
                }else{
                    return 0;
                }
            }

        }else{
            return 0 ;
        }

        return 0;
    }

    public function getSellingPriceByPricingGroup($fin_item_id,$fst_unit,$fin_cust_pricing_group_id) {                 
        //$fin_cust_pricing_group_id = $pricingGroupId;
        // cek Special item
        $ssql = "select * from msitemspecialpricinggroupdetails where fin_item_id = ? and fst_unit = ? and fin_cust_pricing_group_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql,[$fin_item_id,$fst_unit,$fin_cust_pricing_group_id]);
        $rwPrice = $qr->row();
        if($rwPrice){
            return $rwPrice->fdc_selling_price;
        }else{
            //item fst_unit details
            $ssql = "select * from msitemunitdetails where fin_item_id = ? and fst_unit = ? and fst_active = 'A'";
            $qr = $this->db->query($ssql,[$fin_item_id,$fst_unit]);
            $rwPrice = $qr->row();
            if($rwPrice){
                $sellingPrice = $rwPrice->fdc_price_list;
                //Cek Group Price List
                $ssql = "select * from mscustpricinggroups where fin_cust_pricing_group_id = ? ";
                $qr = $this->db->query($ssql,[$fin_cust_pricing_group_id]);
                $rwGroupPrice = $qr->row();
                if($rwGroupPrice){
                    if ($rwGroupPrice->fdc_percent_of_price_list == 0){
                        return $sellingPrice - $rwGroupPrice->fdc_difference_in_amount;
                    }else{
                        return $sellingPrice * ($rwGroupPrice->fdc_percent_of_price_list /100);
                    }
                }else{
                    return $sellingPrice;
                }
            }else{
                return 0;
            }
        }
        return 0;
    }

    public function getDetailbyArray($arrItemId){
        $ssql = "select * from msitems where fin_item_id in ?";
        $qr = $this->db->query($ssql,[$arrItemId]);
        $rs = $qr->result();
        $result = [];
        foreach($rs as $rw){
            $result[$rw->fin_item_id] = $rw;
        }
        return $result;

    }

    public function getPrintItem($lineBussines,$vendorName,$groupName,$itemCode_awal,$itemCode_akhir){
        if ($lineBussines == 'null'){
            $lineBussines ="";
        }
        if ($vendorName == 'null'){
            $vendorName ="";
        }
        if ($groupName == 'null'){
            $groupName ="";
        }
        $ssql = "SELECT a.fin_item_id,a.fst_item_code,a.fst_item_name,
                CONCAT(a.fst_linebusiness_id,'  -  ',d.fst_linebusiness_name) as vendorName1,
                CONCAT(a.fin_item_group_id,'  -  ',b.fst_item_group_name) as itemGroup,
                c.fdc_price_list,c.fst_unit 
                FROM msitems a LEFT JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
                LEFT JOIN msitemunitdetails c on a.fin_item_id = c.fin_item_id
                LEFT JOIN mslinebusiness d ON REPLACE(a.fst_linebusiness_id,',','|') REGEXP  REPLACE(d.fin_linebusiness_id,',','|')
                WHERE a.fin_standard_vendor_id like ? AND a.fin_item_group_id like ?
                AND a.fst_item_code >= '$itemCode_awal' AND a.fst_item_code <= '$itemCode_akhir' ORDER BY a.fst_item_name ";
        $query = $this->db->query($ssql,['%'.$lineBussines.'%','%'.$vendorName.'%','%'.$groupName.'%']);
        //echo $this->db->last_query();
        //die();
        $rs = $query->result();

        return $rs;
    }

    public function geSimpletDataById($fin_item_id){
        $ssql ="select * from msitems where fin_item_id = ?";
        $qr=$this->db->query($ssql,[$fin_item_id]);
        return $qr->row();
    }    

    public function getQtyConvertUnit($itemId,$qtyToConvert,$fromUnit,$toUnit){
        $conversion = $this->getConversionUnit($itemId,$fromUnit,$toUnit);        
        return (float) $qtyToConvert * $conversion;
    }

    public function getQtyConvertToBasicUnit($itemId,$qtyToConvert,$unitToConvert){
        $basicUnit = $this->getBasicUnit($itemId);
        $conversion = $this->getConversionUnit($itemId,$unitToConvert,$basicUnit);        
        return (float) $qtyToConvert * $conversion;
    }

    public function getBasicUnit($finItemId){
        $ssql = "select * from msitemunitdetails where fin_item_id = ? and fbl_is_basic_unit = 1";
        $qr = $this->db->query($ssql,[$finItemId]);
        $rw  = $qr->row();
        if($rw == null){
            return null;
        }else{
            return $rw->fst_unit;
        }
    }
    public function getConversionUnit($itemId,$fromUnit,$toUnit){
        $ssql = "select * from msitemunitdetails where fin_item_id = ? and fst_unit = ?";
        $qr = $this->db->query($ssql,[$itemId,$fromUnit]);
        $rw = $qr->row();
        if($rw == null){
            return 0; //throw new Exception("Conversion Unit error : unit $fromUnit not defined !");                        
        }
        $fromConversion = (float) $rw->fdc_conv_to_basic_unit;
        $ssql = "select * from msitemunitdetails where fin_item_id = ? and fst_unit = ?";
        $qr = $this->db->query($ssql,[$itemId,$toUnit]);
        $rw = $qr->row();
        if($rw == null){
            return 0; //throw new Exception("Conversion Unit error : unit $fromUnit not defined !");                        
        }

        $toConversion = (float) $rw->fdc_conv_to_basic_unit;
        
        return $fromConversion/$toConversion;

    }

    public function delete($fin_item_id,$softDelete=true,$data=null){
        //cek jika sudah ada di inventory
        $ssql  = "select * from trinventory where fin_item_id = ?";
        $qr = $this->db->query($ssql,[$fin_item_id]);
        if ($qr->row()){
            return [
                "status"=>false,
                "message"=>lang("ITEM tidak dapat dihapus, sudah ada record inventory !"),
            ];
        }
        parent::delete($fin_item_id,$softDelete);
        if(!$softDelete){
            $this->db->delete("trinventory",array("fin_item_id"=>$fin_item_id));
        }
        

        return [
            "status"=>true,
            "message"=>"",
        ];
    }


    public function getItemList($filter){
        $ssql = "SELECT fin_item_id,fst_item_code,fst_item_name FROM msitems where fst_item_code like ? or fst_item_name like ? and fst_active = 'A' ";
        $filter = "%$filter%";
        $qr = $this->db->query($ssql,[$filter,$filter]);

        return $qr->result();
    }

    public function getItemList_report(){
        $term = $this->input->get("term");
        $term = "%".$term."%";
        $ssql = "SELECT CONCAT_WS('->',fst_item_code,fst_item_name) AS fst_item_name,fst_item_code,fin_item_id FROM msitems WHERE fst_item_name LIKE ?";

        $query = $this->db->query($ssql, [$term]);
        $rs = $query->result();
        return $rs;

    }
}

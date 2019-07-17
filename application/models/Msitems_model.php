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
        $ssql = "select a.*,b.fst_item_maingroup_name,c.fst_item_group_name,d.fst_item_subgroup_name from " . $this->tableName . " a 
        left join msmaingroupitems b on a.fin_item_maingroup_id = b.fin_item_maingroup_id 
        left join msgroupitems c on a.fin_item_group_id = c.fin_item_group_id  
        left join mssubgroupitems d on a.fin_item_subgroup_id = d.fin_item_subgroup_id 
        where a.fin_item_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rw = $qr->row();

		if ($rw) {
			if (file_exists(FCPATH . 'assets/app/items/image/'.$rw->fin_item_code . '.jpg')) {
				$imageURL = site_url() . 'assets/app/items/image/' . $rw->fin_item_code . '.jpg';
			} else {

				$imageURL = site_url() . 'assets/app/items/image/default.jpg';
			}
			$rw->imageURL = $imageURL;
		}

        $ssql = "select * from msitemfst_unitdetails where fin_item_id = ?";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rsUnitDetail = $qr->result();

        $ssql = "select a.*,b.fst_item_name from msitembomdetails a left join " . $this->tableName . " b on a.fin_item_id_bom = b.fin_item_id  where a.fin_item_id = ?";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rsBomDetail = $qr->result();

        $ssql = "select a.*,b.fst_cust_pricing_group_name from msitemspecialpricinggroupdetails a left join mscustpricinggroups b on a.fin_cust_pricing_group_id = b.fin_cust_pricing_group_id  where a.fin_item_id = ?";
        $qr = $this->db->query($ssql, [$fin_item_id]);
        $rsSpecialPricing = $qr->result();

        $data = [
            "msitems" => $rw,
            "fst_unitDetail" => $rsUnitDetail,
            "bomDetail" => $rsBomDetail,
            "specialpricing" => $rsSpecialPricing,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_item_code',
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

    public function getAllList()
    {
        $ssql = "select fin_item_id,fst_item_name from " . $this->tableName . " where fst_active = 'A' order by fst_item_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function getSellingPrice($fin_item_id,$fst_unit,$custId) {
        //$this->load->model("MSRelations_model");
        //$this->MSRelations_model->
        $ssql ="select * from msrelations where fin_relation_id = ?";
        $qr = $this->db->query($ssql,[$custId]);
        $rw = $qr->row();
        if($rw){
            

            $priceGroupId = $rw->fin_cust_pricing_group_id;
            // cek Special item
            $ssql = "select * from msitemspecialpricinggroupdetails where fin_item_id = ? and fst_unit = ? and fin_cust_pricing_group_id = ? and fst_active = 'A'";
            $qr = $this->db->query($ssql,[$fin_item_id,$fst_unit,$priceGroupId]);
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
                    $qr = $this->db->query($ssql,[$priceGroupId]);
                    $rwGroupPrice = $qr->row();
                    if($rwGroupPrice){
                        if ($rwGroupPrice->fdc_percent_of_price_list == 100){
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
}

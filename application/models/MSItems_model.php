<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSItems_model extends MY_Model
{
    public $tableName = "msitems";
    public $pkey = "ItemId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($ItemId)
    {
        //$ssql = "select * from " . $this->tableName . " where ItemId = ? and fst_active = 'A'";
        $ssql = "select a.*,b.ItemMainGroupName,c.ItemGroupName,d.ItemSubGroupName from " . $this->tableName . " a 
        left join msmaingroupitems b on a.ItemMainGroupId = b.ItemMainGroupId 
        left join msgroupitems c on a.ItemGroupId = c.ItemGroupId  
        left join mssubgroupitems d on a.ItemSubGroupId = d.ItemSubGroupId 
        where a.ItemId = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$ItemId]);
        $rw = $qr->row();

        $ssql = "select * from msitemunitdetails where ItemId = ?";
        $qr = $this->db->query($ssql, [$ItemId]);
        $rsUnitDetail = $qr->result();

        $ssql = "select a.*,b.ItemName from msitembomdetails a left join " . $this->tableName . " b on a.ItemIdBom = b.ItemId  where a.ItemId = ?";
        $qr = $this->db->query($ssql, [$ItemId]);
        $rsBomDetail = $qr->result();

        $ssql = "select a.*,b.CustPricingGroupName from msitemspecialpricinggroupdetails a left join mscustpricinggroups b on a.PricingGroupId = b.CustPricingGroupId  where a.ItemId = ?";
        $qr = $this->db->query($ssql, [$ItemId]);
        $rsSpecialPricing = $qr->result();

        $data = [
            "msitems" => $rw,
            "unitDetail" => $rsUnitDetail,
            "bomDetail" => $rsBomDetail,
            "specialpricing" => $rsSpecialPricing,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'ItemCode',
            'label' => 'Item Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'ItemName',
            'label' => 'Item Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'VendorItemName',
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
        $ssql = "select ItemId,ItemName from " . $this->tableName . " where fst_active = 'A' order by ItemName";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function getSellingPrice($itemId,$unit,$custId) {
        //$this->load->model("MSRelations_model");
        //$this->MSRelations_model->
        $ssql ="select * from msrelations where RelationId = ?";
        $qr = $this->db->query($ssql,[$custId]);
        $rw = $qr->row();
        if($rw){
            

            $priceGroupId = $rw->CustPricingGroupid;
            // cek Special item
            $ssql = "select * from msitemspecialpricinggroupdetails where ItemId = ? and Unit = ? and PricingGroupId = ? and fst_active = 'A'";
            $qr = $this->db->query($ssql,[$itemId,$unit,$priceGroupId]);
            $rwPrice = $qr->row();
            if($rwPrice){
                return $rwPrice->SellingPrice;
            }else{
                //item unit details
                $ssql = "select * from msitemunitdetails where ItemId = ? and Unit = ? and fst_active = 'A'";
                $qr = $this->db->query($ssql,[$itemId,$unit]);
                $rwPrice = $qr->row();
                if($rwPrice){
                    $sellingPrice = $rwPrice->PriceList;
                    //Cek Group Price List
                    $ssql = "select * from mscustpricinggroups where CustPricingGroupId = ?";
                    $qr = $this->db->query($ssql,[$priceGroupId]);
                    $rwGroupPrice = $qr->row();
                    if($rwGroupPrice){
                        if ($rwGroupPrice->PercentOfPriceList == 100){
                            return $sellingPrice - $rwGroupPrice->DifferenceInAmount;
                        }else{
                            return $sellingPrice * ($rwGroupPrice->PercentOfPriceList /100);
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
        $ssql = "select * from msitems where ItemId in ?";
        $qr = $this->db->query($ssql,$arrItemId);
        $rs = $qr->result();
        $result = [];
        foreach($rs as $w){
            $result[$rw->ItemId] = $rw;
        }
        return $result;

    }
}

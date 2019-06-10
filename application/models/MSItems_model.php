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

        $ssql = "select * from msitemunitdetails where ItemCode = ?";
        $qr = $this->db->query($ssql, [$ItemId]);
        $rsUnitDetail = $qr->result();

        $ssql = "select a.*,b.ItemName from msitembomdetails a left join " . $this->tableName . " b on a.ItemCodeBom = b.ItemId  where a.ItemCode = ?";
        $qr = $this->db->query($ssql, [$ItemId]);
        $rsBomDetail = $qr->result();

        $ssql = "select a.*,b.CustPricingGroupName from msitemspecialpricinggroupdetails a left join mscustpricinggroups b on a.PricingGroupId = b.CustPricingGroupId  where a.ItemCode = ?";
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
}

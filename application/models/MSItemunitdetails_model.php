<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSItemunitdetails_model extends MY_Model
{
    public $tableName = "msitemunitdetails";
    public $pkey = "RecId";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($RecId)
    {
        $ssql = "select * from " . $this->tableName . " where RecId = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$RecId]);
        $rw = $qr->row();

        $data = [
            "" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'ItemId',
            'label' => 'Item ID',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'Unit',
            'label' => 'Unit',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'Conv2BasicUnit',
            'label' => 'Conv2 Basic Unit',
            'rules' => 'numeric',
            'errors' => array(
                'numeric' => '%s harus berupa angka'
            )
        ];

        $rules[] = [
            'field' => 'PriceList',
            'label' => 'Price List',
            'rules' => 'numeric',
            'errors' => array(
                'numeric' => '%s harus berupa angka'
            )
        ];

        $rules[] = [
            'field' => 'HET',
            'label' => 'HET',
            'rules' => 'numeric',
            'errors' => array(
                'numeric' => '%s harus berupa angka'
            )
        ];

        $rules[] = [
            'field' => 'LastBuyingPrice',
            'label' => 'Last Buying Price',
            'rules' => 'numeric',
            'errors' => array(
                'numeric' => '%s harus berupa angka'
            )
        ];

        return $rules;
    }
    public function deleteByHeaderId($ItemId)
    {
        $ssql = "delete from " . $this->tableName . " where ItemId = $ItemId";
        $this->db->query($ssql);
    }


    public function getSellingListUnit($itemId){
        $ssql ="select * from " . $this->tableName . " where ItemId = ? and isSelling = 1 and fst_active = 'A' order by RecId ";
        $qr = $this->db->query($ssql,[$itemId]);
        $rw = $qr->result();
        return $rw;
    }


    public function getConversionUnit($fin_item_id,$qty , $unitFrom,$unitTo){
        $ssql = "Select * from msitemunitdetails where ItemId = ? and Unit = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$fin_item_id,$unitFrom]);
        $rwFrom = $qr->row();
        if($rwFrom){
            $qtyBasicUnit = $qty * $rwFrom->Conv2BasicUnit;
            $qr = $this->db->query($ssql,[$fin_item_id,$unitTo]);
            $rwTo = $qr->row();
            if($rwTo){
                return $qtyBasicUnit / $rwTo->Conv2BasicUnit;
            }else{
                return 0;
            }
        }else{
            return 0;
        }



    }
}

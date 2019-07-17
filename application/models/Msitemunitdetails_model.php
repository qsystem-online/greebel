<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msitemunitdetails_model extends MY_Model
{
    public $tableName = "msitemunitdetails";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_rec_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_rec_id]);
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
            'field' => 'fin_item_id',
            'label' => 'Item ID',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_unit',
            'label' => 'Unit',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fdc_conv_to_basic_unit',
            'label' => 'Conv2 Basic Unit',
            'rules' => 'numeric',
            'errors' => array(
                'numeric' => '%s harus berupa angka'
            )
        ];

        $rules[] = [
            'field' => 'fdc_price_list',
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

        return $rules;
    }
    public function deleteByHeaderId($fin_item_id)
    {
        $ssql = "delete from " . $this->tableName . " where fin_item_id = $fin_item_id";
        $this->db->query($ssql);
    }


    public function getSellingListUnit($itemId){
        $ssql ="select * from " . $this->tableName . " where fin_item_id = ? and fbl_is_selling = 1 and fst_active = 'A' order by fin_rec_id ";
        $qr = $this->db->query($ssql,[$itemId]);
        $rw = $qr->result();
        return $rw;
    }


    public function getConversionUnit($fin_item_id,$qty , $unitFrom,$unitTo){
        $ssql = "Select * from msitemunitdetails where fin_item_id = ? and Unit = ? and fst_active ='A'";
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

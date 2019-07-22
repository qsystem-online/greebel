<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msshippingaddress_model extends MY_Model {
    public $tableName = "msshippingaddress";
    public $pkey = "fin_shipping_address_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_shipping_address_id){
        $ssql = "select a.*,MID(a.fst_area_code, 1, 2) AS province,MID(a.fst_area_code, 1, 5) AS district,MID(a.fst_area_code, 1, 8) AS subdistrict,MID(a.fst_area_code, 1, 13) AS village,
        b.fst_nama as fst_province_name,c.fst_nama as fst_district_name,d.fst_nama as fst_subdistrict_name,e.fst_nama as fst_village_name,f.fst_relation_name from " . $this->tableName . " a  
        left join msarea b on MID(a.fst_area_code, 1, 2) = b.fst_kode
        left join msarea c on MID(a.fst_area_code, 1, 5) = c.fst_kode
        left join msarea d on MID(a.fst_area_code, 1, 8) = d.fst_kode
        left join msarea e on MID(a.fst_area_code, 1, 13) = e.fst_kode
        left join msrelations f on a.fin_relation_id = f.fin_relation_id
        where a.fin_shipping_address_id = ? order by fin_shipping_address_id ";
        $qr = $this->db->query($ssql, [$fin_shipping_address_id]);
        $rwShipping = $qr->row();

        $arrTmp = explode(".",$rwShipping->district);
        if (sizeof($arrTmp) == 2 ){
            $arrTmp = explode(".",$rwShipping->subdistrict);
            if (sizeof($arrTmp) == 3){
                $arrTmp = explode(".",$rwShipping->village);
                if (sizeof($arrTmp) != 4){
                    $rwShipping->village = null;
                    $rwShipping->fst_village_name = null;
                }
            }else{
                $rwShipping->subdistrict = null;
                $rwShipping->fst_subdistrict_name = null;
                $rwShipping->village = null;
                $rwShipping->fst_village_name = null;
            }
        }else{
            $rwShipping->district = null;
            $rwShipping->fst_district_name = null;
            $rwShipping->subdistrict = null;
            $rwShipping->fst_subdistrict_name = null;
            $rwShipping->village = null;
            $rwShipping->fst_village_name = null;
        }
        
        $data = [
            "ms_shipping" => $rwShipping
		];

		return $data;
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_name',
            'label' => 'Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    public function deleteByHeaderId($fin_relation_id)
    {
        $ssql = "delete from " . $this->tableName . " where fin_relation_id = $fin_relation_id";
        $this->db->query($ssql);
    }
}
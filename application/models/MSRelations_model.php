<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msrelations_model extends MY_Model {
    public $tableName = "msrelations";
    public $pkey = "fin_relation_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_relation_id){
        $ssql = "select a.*,MID(a.AreaCode, 1, 2) AS province,MID(a.AreaCode, 1, 5) AS district,MID(a.AreaCode, 1, 8) AS subdistrict,MID(a.AreaCode, 1, 13) AS village,b.fst_country_name,
        c.nama as namaprovince,d.nama as namadistrict,e.nama as namasubdistrict,f.nama as namavillage,g.fst_relation_group_name,h.fst_cust_pricing_group_name,i.fst_notes,j.fst_username as SalesName,
        k.fst_warehouse_name,l.fst_name,m.fst_relation_name as ParentName from " . $this->tableName . " a 
        left join mscountries b on a.fin_country_id = b.fin_country_id 
        left join msarea c on MID(a.AreaCode, 1, 2) = c.kode
        left join msarea d on MID(a.AreaCode, 1, 5) = d.kode
        left join msarea e on MID(a.AreaCode, 1, 8) = e.kode
        left join msarea f on MID(a.AreaCode, 1, 13) = f.kode
        left join msrelationgroups g on a.fin_relation_group_id = g.fin_relation_group_id
        left join mscustpricinggroups h on a.fin_cust_pricing_group_id = h.fin_cust_pricing_group_id
        left join msrelationprintoutnotes i on a.fst_relation_notes = i.fin_note_id
        left join users j on a.fin_sales_id = j.fin_user_id
        left join mswarehouse k on a.fin_warehouse_id = k.fin_warehouse_id
        left join mssalesarea l on a.fin_sales_area_id = l.fin_sales_area_id
        left join " . $this->tableName . " m on a.fin_parent_id = m.fin_relation_id
        where a.fin_relation_id = ? order by fin_relation_id ";
		$qr = $this->db->query($ssql, [$fin_relation_id]);
        $rwMSRelations = $qr->row();

        $arrTmp = explode(".",$rwMSRelations->district);
        if (sizeof($arrTmp) == 2 ){
            $arrTmp = explode(".",$rwMSRelations->subdistrict);
            if (sizeof($arrTmp) == 3){
                $arrTmp = explode(".",$rwMSRelations->village);
                if (sizeof($arrTmp) != 4){
                    $rwMSRelations->village = null;
                    $rwMSRelations->namavillage = null;
                }
            }else{
                $rwMSRelations->subdistrict = null;
                $rwMSRelations->namasubdistrict = null;
                $rwMSRelations->village = null;
                $rwMSRelations->namavillage = null;
            }
        }else{
            $rwMSRelations->district = null;
            $rwMSRelations->namadistrict = null;
            $rwMSRelations->subdistrict = null;
            $rwMSRelations->namasubdistrict = null;
            $rwMSRelations->village = null;
            $rwMSRelations->namavillage = null;
        }

		$data = [
            "ms_relations" => $rwMSRelations
		];

		return $data;
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_relation_name',
            'label' => 'Relation Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_postal_code',
            'label' => 'Postal Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_npwp',
            'label' => 'NPWP',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fin_top_komisi',
            'label' => 'Top Commission',
            'rules' => 'required|numeric',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka'
            )
        ];

        $rules[] = [
            'field' => 'fin_top_plus_komisi',
            'label' => 'Top Plus Commission',
            'rules' => 'required|numeric',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka'
            )
        ];

        return $rules;
    }

    public function get_Relations(){
        $query = $this->db->get('msrelations');
		return $query->result_array();
    }

    public function getCreditLimit($relationId){
        $ssql = "select fin_credit_limit from msrelations where fin_relation_id = ?";
        $qr = $this->query($ssql,[$relationId]);
        $rw = $qr->row();
        if(!$rw){
            return 0;
        }else{
            return (float) $rw->fin_credit_limit;
        }
    }
}
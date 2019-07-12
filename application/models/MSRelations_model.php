<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSRelations_model extends MY_Model {
    public $tableName = "msrelations";
    public $pkey = "RelationId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RelationId){
        $ssql = "select a.*,MID(a.AreaCode, 1, 2) AS province,MID(a.AreaCode, 1, 5) AS district,MID(a.AreaCode, 1, 8) AS subdistrict,MID(a.AreaCode, 1, 13) AS village,b.CountryName,
        c.nama as namaprovince,d.nama as namadistrict,e.nama as namasubdistrict,f.nama as namavillage,g.RelationGroupName,h.CustPricingGroupName,i.Notes,j.fst_username as SalesName,
        k.fst_warehouse_name,l.fst_name,m.RelationName as ParentName from " . $this->tableName . " a 
        left join mscountries b on a.CountryId = b.CountryId 
        left join msarea c on MID(a.AreaCode, 1, 2) = c.kode
        left join msarea d on MID(a.AreaCode, 1, 5) = d.kode
        left join msarea e on MID(a.AreaCode, 1, 8) = e.kode
        left join msarea f on MID(a.AreaCode, 1, 13) = f.kode
        left join msrelationgroups g on a.RelationGroupId = g.RelationGroupId
        left join mscustpricinggroups h on a.CustPricingGroupid = h.CustPricingGroupid
        left join msrelationprintoutnotes i on a.RelationNotes = i.NoteId
        left join users j on a.fin_sales_id = j.fin_user_id
        left join mswarehouse k on a.fin_warehouse_id = k.fin_warehouse_id
        left join mssalesarea l on a.fin_sales_area_id = l.fin_sales_area_id
        left join " . $this->tableName . " m on a.fin_parent_id = m.RelationId
        where a.RelationId = ? order by RelationId ";
		$qr = $this->db->query($ssql, [$RelationId]);
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
            'field' => 'RelationName',
            'label' => 'Relation Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'PostalCode',
            'label' => 'Postal Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'NPWP',
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
        $ssql = "select fin_credit_limit from msrelations where RelationId = ?";
        $qr = $this->query($ssql,[$relationId]);
        $rw = $qr->row();
        if(!$rw){
            return 0;
        }else{
            return (float) $rw->fin_credit_limit;
        }
    }
}
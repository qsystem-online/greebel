<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSRelations_model extends MY_Model {
    public $tableName = "msrelations";
    public $pkey = "RelationId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RelationId){
        $ssql = "select a.*,MID(a.AreaCode, 1, 2) AS province,MID(a.AreaCode, 1, 5) AS district,MID(a.AreaCode, 1, 8) AS subdistrict,MID(a.AreaCode, 1, 13) AS village,b.CountryName,c.nama as namaprovince,d.RelationGroupName,e.CustPricingGroupName,f.Notes,g.nama as namadistrict,h.nama as namasubdistrict,i.nama as namavillage from " . $this->tableName . " a left join 
        mscountries b on a.CountryId = b.CountryId 
        left join msarea c on MID(a.AreaCode, 1, 2) = c.kode
        left join msrelationgroups d on a.RelationGroupId = d.RelationGroupId
        left join mscustpricinggroups e on a.CustPricingGroupId = e.CustPricingGroupId
        left join msrelationprintoutnotes f on a.RelationNotes = f.NoteId
        left join msarea g on MID(a.AreaCode, 1, 5) = g.kode
        left join msarea h on MID(a.AreaCode, 1, 8) = h.kode
        left join msarea i on MID(a.AreaCode, 1, 13) = i.kode
        where a.RelationId = ? order by RelationId ";
		$qr = $this->db->query($ssql, [$RelationId]);
        $rwMSRelations = $qr->row();

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

        return $rules;
    }

    public function get_Relations(){
        $query = $this->db->get('msrelations');
		return $query->result_array();
    }
}
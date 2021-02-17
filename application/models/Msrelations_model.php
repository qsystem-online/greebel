<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

clASs Msrelations_model extends MY_Model {
    public $tableName = "msrelations";
    public $pkey = "fin_relation_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_relation_id){
        $ssql = "SELECT a.*,MID(a.fst_area_code, 1, 2) AS provinces,MID(a.fst_area_code, 1, 5) AS district,MID(a.fst_area_code, 1, 8) AS subdistrict,MID(a.fst_area_code, 1, 13) AS village,
        b.fst_country_name,c.fst_nama AS fst_province_name,d.fst_nama AS fst_district_name,e.fst_nama AS fst_subdistrict_name,f.fst_nama AS fst_village_name,g.fst_relation_group_name,
        h.fst_cust_pricing_group_name,i.fst_notes,j.fst_username AS SalesName,k.fst_warehouse_name,l.fst_name,m.fst_branch_name,n.fst_relation_name AS ParentName,o.fst_linebusiness_name 
        FROM msrelations a 
        LEFT JOIN mscountries b ON a.fin_country_id = b.fin_country_id 
        LEFT JOIN msarea c ON MID(a.fst_area_code, 1, 2) = c.fst_kode
        LEFT JOIN msarea d ON MID(a.fst_area_code, 1, 5) = d.fst_kode
        LEFT JOIN msarea e ON MID(a.fst_area_code, 1, 8) = e.fst_kode
        LEFT JOIN msarea f ON MID(a.fst_area_code, 1, 13) = f.fst_kode
        LEFT JOIN msrelationgroups g ON a.fin_relation_group_id = g.fin_relation_group_id
        LEFT JOIN mscustpricinggroups h ON a.fin_cust_pricing_group_id = h.fin_cust_pricing_group_id
        LEFT JOIN msrelationprintoutnotes i ON a.fst_relation_notes = i.fin_note_id
        LEFT JOIN users j ON a.fin_sales_id = j.fin_user_id
        LEFT JOIN mswarehouse k ON a.fin_warehouse_id = k.fin_warehouse_id
        LEFT JOIN mssalesarea l ON a.fin_sales_area_id = l.fin_sales_area_id
        LEFT JOIN msbranches m ON a.fin_branch_id = m.fin_branch_id
        LEFT JOIN msrelations n ON a.fin_parent_id = n.fin_relation_id
        LEFT JOIN mslinebusiness o ON a.fst_linebusiness_id = o.fin_linebusiness_id
        WHERE a.fin_relation_id = ? order by fin_relation_id ";
		$qr = $this->db->query($ssql, [$fin_relation_id]);
        $rwRelation = $qr->row();

        $arrTmp = explode(".",$rwRelation->district);
        if (sizeof($arrTmp) == 2 ){
            $arrTmp = explode(".",$rwRelation->subdistrict);
            if (sizeof($arrTmp) == 3){
                $arrTmp = explode(".",$rwRelation->village);
                if (sizeof($arrTmp) != 4){
                    $rwRelation->village = null;
                    $rwRelation->fst_village_name = null;
                }
            }else{
                $rwRelation->subdistrict = null;
                $rwRelation->fst_subdistrict_name = null;
                $rwRelation->village = null;
                $rwRelation->fst_village_name = null;
            }
        }else{
            $rwRelation->district = null;
            $rwRelation->fst_district_name = null;
            $rwRelation->subdistrict = null;
            $rwRelation->fst_subdistrict_name = null;
            $rwRelation->village = null;
            $rwRelation->fst_village_name = null;
        }

        //$ssql = "SELECT * FROM msshippingaddress where fin_relation_id = ? and fst_active = 'A' ";
        $ssql = "SELECT a.*,MID(a.fst_area_code, 1, 2) AS provinceShipp,MID(a.fst_area_code, 1, 5) AS districtShipp,MID(a.fst_area_code, 1, 8) AS subdistrictShipp,MID(a.fst_area_code, 1, 13) AS villageShipp,
        b.fst_nama AS fst_province_name,c.fst_nama AS fst_district_name,d.fst_nama AS fst_subdistrict_name,e.fst_nama AS fst_village_name,f.fst_relation_name AS fst_name FROM msshippingaddress a
        LEFT JOIN msarea b ON MID(a.fst_area_code, 1, 2) = b.fst_kode
        LEFT JOIN msarea c ON MID(a.fst_area_code, 1, 5) = c.fst_kode
        LEFT JOIN msarea d ON MID(a.fst_area_code, 1, 8) = d.fst_kode
        LEFT JOIN msarea e ON MID(a.fst_area_code, 1, 13) = e.fst_kode
        LEFT JOIN msrelations f ON a.fin_relation_id = f.fin_relation_id
        WHERE a.fin_relation_id = ? and a.fst_active = 'A' ";
        $qr = $this->db->query($ssql, [$fin_relation_id]);
        $rsShipping = $qr->result();

		$data = [
            "ms_relations" => $rwRelation,
            "ms_shipping" => $rsShipping
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

    public function getAllList(){
        $ssql = "select fin_relation_id,fst_relation_name from " . $this->tableName . " where fst_active = 'A' order by fst_relation_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function get_Relations(){
        $query = $this->db->get('msrelations');
		return $query->result_array();
    }

    public function getCreditLimit($relationId){
        $ssql = "select fdc_credit_limit from msrelations where fin_relation_id = ?";
        $qr = $this->db->query($ssql,[$relationId]);
        $rw = $qr->row();
        if(!$rw){
            return 0;
        }else{
            return (float) $rw->fdc_credit_limit;
        }
    }

    public function getSupplierList(){
        $ssql = "select * from msrelations where find_in_set('2',fst_relation_type) and fin_branch_id = ?";
        $query = $this->db->query($ssql, [$this->aauth->get_active_branch_id()]);
        $rs = $query->result();
        return $rs;

    }

    public function getCustomerList(){
        $term = $this->input->get("term");
        $term = "%".$term."%";
        $ssql = "select * from msrelations where find_in_set('1',fst_relation_type) and fin_branch_id = ? and fst_relation_name like ?";
        //$ssql = "select fin_relation_id,fst_relation_name from msrelations where fin_branch_id = ? and fst_relation_name like ?";
        $query = $this->db->query($ssql, [$this->aauth->get_active_branch_id(),$term]);

        $rs = $query->result();
        return $rs;

    }

    public function getEkspedisiList(){
        $term = $this->input->get("term");
        $term = "%".$term."%";
        $ssql = "select * from msrelations where find_in_set('3',fst_relation_type) and fin_branch_id = ? and fst_relation_name like ?";
        //$ssql = "select fin_relation_id,fst_relation_name from msrelations where fin_branch_id = ? and fst_relation_name like ?";
        $query = $this->db->query($ssql, [$this->aauth->get_active_branch_id(),$term]);

        $rs = $query->result();
        return $rs;

    }

    public function getPrintRelation($type,$relationId_start,$relationId_end){
        if ($type == 'ALL'){
            $type ="";
        }
        if ($relationId_start == 'null'){
            $relationId_start ="";
        }
        if ($relationId_end == 'null'){
            $relationId_end ="";
        }
        $ssql = "SELECT * FROM msrelations WHERE fin_branch_id = ? AND fst_relation_type like ?
                AND fin_relation_id >= '$relationId_start' AND fin_relation_id <= '$relationId_end' ORDER BY fst_relation_name ";
        $query = $this->db->query($ssql,[$this->aauth->get_active_branch_id(),'%'.$type.'%']);
        //echo $this->db->last_query();
        //die();
        $rs = $query->result();

        return $rs;
    }

    public function getCustomerListByBranch($finBranchId=null){
        $finBranchId = $finBranchId ==  null ? $this->aauth->get_active_branch_id() : $finBranchId;
        $ssql = "select * from msrelations where find_in_set('1',fst_relation_type) and fst_active ='A' and fin_branch_id = ?";
        $qr = $this->db->query($ssql,[$finBranchId]);
        return $qr->result();
    }

    public function getRelationListByBranch($finBranchId = null){
        $finBranchId = $finBranchId ==  null ? $this->aauth->get_active_branch_id() : $finBranchId;
        $ssql = "select * from msrelations where fst_active ='A' and fin_branch_id = ?";
        $qr = $this->db->query($ssql,[$finBranchId]);
        return $qr->result();
    }

    public function getSupplierByLineBusinessAndActiveBranch($finLineBussiness){
        $finBranchId = $this->aauth->get_active_branch_id() ;
        $ssql = "select * from msrelations where fst_active ='A' and fin_branch_id = ? and find_in_set('2',fst_relation_type) and find_in_set(?,fst_linebusiness_id) ";
        $qr = $this->db->query($ssql,[$finBranchId,$finLineBussiness]);
        return $qr->result();
    }
}
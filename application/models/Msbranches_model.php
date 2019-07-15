<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msbranches_model extends MY_Model
{
    public $tableName = "msbranches";
    public $pkey = "fin_branch_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_branch_id)
    {
        $ssql = "select a.*,MID(a.fin_area_code, 1, 2) AS province,MID(a.fin_area_code, 1, 5) AS district,MID(a.fin_area_code, 1, 8) AS subdistrict,b.fst_country_name,
        c.fst_nama AS fst_province_name,d.fst_nama AS fst_district_name,e.fst_nama AS fst_subdistrict_name from " . $this->tableName . " a 
        left join mscountries b on a.fin_country_id = b.fin_country_id 
        left join msarea c on MID(a.fin_area_code, 1, 2) = c.fst_kode
        left join msarea d on MID(a.fin_area_code, 1, 5) = d.fst_kode
        left join msarea e on MID(a.fin_area_code, 1, 8) = e.fst_kode
        where fin_branch_id = ?";
        $qr = $this->db->query($ssql, [$fin_branch_id]);
        $rwBranch = $qr->row();

        $arrTmp = explode(".",$rwBranch->district);
        if (sizeof($arrTmp) == 2 ){
            $arrTmp = explode(".",$rwBranch->subdistrict);
            if (sizeof($arrTmp) != 3){
                $rwBranch->subdistrict = null;
                $rwBranch->fst_subdistrict_name = null;
            }
        }else{
            $rwBranch->district = null;
            $rwBranch->fst_district_name = null;
            $rwBranch->subdistrict = null;
            $rwBranch->fst_subdistrict_name = null;
        }

        $data = [
            "branches" => $rwBranch
        ];
        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_branch_name',
            'label' => 'Branch Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fbl_is_hq',
            'label' => 'HQ',
            'rules' => 'is_unique[msbranches.fin_branch_id.fbl_is_hq.' . $id . ']',
            'errors' => array(
                'is_unique' => '%s is more one'
            )
        ];


        return $rules;
    }

    // Untuk mematikan fungsi otomatis softdelete dari MY_MODEL
    /*public function delete($key, $softdelete = false){
		parent::delete($key,$softdelete);
    }*/

    public function getAllList()
    {
        $ssql = "select fin_branch_id,fst_branch_name from " . $this->tableName . " where fst_active = 'A'";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function get_Branch()
    {
        $query = $this->db->get('branches');
        return $query->result_array();
    }

}

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSBranches_model extends MY_Model
{
    public $tableName = "msbranches";
    public $pkey = "fin_branch_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_branch_id)
    {
        $ssql = "select a.*,MID(a.AreaCode, 1, 2) AS province,MID(a.AreaCode, 1, 5) AS district,MID(a.AreaCode, 1, 8) AS subdistrict,b.CountryName,
        c.nama AS ProvinceName,d.nama AS DistrictName,e.nama AS SubDistrictName from " . $this->tableName . " a 
        left join mscountries b on a.CountryId = b.CountryId 
        left join msarea c on MID(a.AreaCode, 1, 2) = c.kode
        left join msarea d on MID(a.AreaCode, 1, 5) = d.kode
        left join msarea e on MID(a.AreaCode, 1, 8) = e.kode
        where fin_branch_id = ?";
        $qr = $this->db->query($ssql, [$fin_branch_id]);
        $rwBranch = $qr->row();

        $arrTmp = explode(".",$rwBranch->district);
        if (sizeof($arrTmp) == 2 ){
            $arrTmp = explode(".",$rwBranch->subdistrict);
            if (sizeof($arrTmp) != 3){
                $rwBranch->subdistrict = null;
                $rwBranch->SubDistrictName = null;
            }
        }else{
            $rwBranch->district = null;
            $rwBranch->DistrictName = null;
            $rwBranch->subdistrict = null;
            $rwBranch->SubDistrictName = null;
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

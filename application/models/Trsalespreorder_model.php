<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trsalespreorder_model extends MY_Model{
    public $tableName = "preorder";
    public $pkey = "fin_preorder_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_preorder_id)
    {
        $ssql = "select a.*,b.fst_item_maingroup_name,c.fst_item_group_name,d.fst_item_subgroup_name,e.fst_curr_name from " . $this->tableName . " a 
        left join msmaingroupitems b on a.fin_item_maingroup_id = b.fin_item_maingroup_id 
        left join msgroupitems c on a.fin_item_group_id = c.fin_item_group_id  
        left join mssubgroupitems d on a.fin_item_subgroup_id = d.fin_item_subgroup_id
        left join mscurrencies e on a.fst_curr_code = e.fst_curr_code
        where a.fin_preorder_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_preorder_id]);
        $rwPreorder = $qr->row();

        $ssql = "select a.*,b.fst_branch_name from preorderbranchdetails a left join msbranches b on a.fin_branch_id = b.fin_branch_id  where a.fin_preorder_id = ?";
        $qr = $this->db->query($ssql, [$fin_preorder_id]);
        $rspreorderDetail = $qr->result();

        $data = [
            "preOrder" => $rwPreorder,
            "preorderDetail" => $rspreorderDetail, 
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_preorder_name',
            'label' => 'Pre-order Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        /*$rules[] = [
            'field' => 'fst_satuan_gabungan',
            'label' => 'Unit',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];*/

        return $rules;
    }

    public function getAllList()
    {
        $ssql = "select fin_preorder_id,fst_promo_name from " . $this->tableName . " where fst_active = 'A' order by fst_promo_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }
}

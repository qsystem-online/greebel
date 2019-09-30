<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msgroupitems_model extends MY_Model
{
    public $tableName = "msgroupitems";
    public $pkey = "fin_item_group_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_item_group_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_item_group_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_item_group_id]);
        $rw = $qr->row();

        $data = [
            "groupitems" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_item_group_name',
            'label' => 'Group Name',
            'rules' => 'required|min_length[2]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 2 character'
            )
        ];

        return $rules;
    }

    public function insert($data){
        $id = parent::insert($data);
        $parent = $this->getDataById($data["fin_parent_item_group_id"]);
        $parent = $parent["groupitems"];        
        if($parent == false){
            $parent_tree_id = null;
        }else{
            $parent_tree_id = $parent->fst_tree_id;
        }

        $ssql ="update msitems set fin_item_group_id = ? where fin_item_group_id = ?";
        $qr = $this->db->query($ssql,[$id,$data["fin_parent_item_group_id"]]);

        $fstTreeId = ($parent_tree_id == null || $parent_tree_id = "") ? $id : $parent_tree_id . "." .$id;
        $data = [
            "fin_item_group_id"=> $id,
            "fst_tree_id"=> $fstTreeId,
        ];
        parent::update($data);        
        return $id;
    }

    public function delete($id, $softdelete = true){
        //Cek if data in used
        $ssql = "select * from msitems where fin_item_group_id = ? and fst_active ='A' limit 1";
        $qr = $this->db->query($ssql,[$id]);
        $rw = $qr->row();
        if ($rw == false){
            parent::delete($id,$softdelete);
            return true;
        }else{
            throw new Exception("Delete Database Error !!!", EXCEPTION_DATA_USED);
        }
    }

    public function isUsed($id){
        $ssql = "select * from msitems where fin_item_group_id= ? limit 1";
        $qr = $this->db->query($ssql,[$id]);
        $rw = $qr->row(); 
        if ($rw == false){
            return false;
        }else{
            return true;
        }
    }
}

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msitembomdetails_model extends MY_Model
{
    public $tableName = "msitembomdetails";
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
            'label' => 'Item Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fin_item_id_bom',
            'label' => 'Item Id BOM',
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

        return $rules;
    }

    public function deleteByHeaderId($fin_item_id)
    {
        $ssql = "delete from " . $this->tableName . " where fin_item_id = $fin_item_id";
        $this->db->query($ssql);
    }


    //bom untuk mengasilkan item dalam 1 unit basic
    public function getBOMList($fin_item_id){
        //scale BOM
        //list bom yang ada untuk kapasitas scale unit basic
        $ssql = "SELECT * FROM msitems where fin_item_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$fin_item_id]);
        $item = $qr->row();

        $scale = (double) $item->fdc_scale_for_bom;

        $ssql= "SELECT a.fin_item_id_bom as fin_item_id,b.fst_item_code,b.fst_item_name,a.fst_unit,a.fdb_qty FROM msitembomdetails a 
            INNER JOIN msitems b on a.fin_item_id_bom = b.fin_item_id 
            where a.fin_item_id = ? and a.fst_active ='A'";
        $qr = $this->db->query($ssql,[$fin_item_id]);
        $rs = $qr->result();

        for($i=0;$i<sizeof($rs);$i++){
            $rw = $rs[$i];
            $rw->fdb_qty_per_unit = ((double) $rw->fdb_qty / $scale);
            $rs[$i] = $rw;
        }
        return $rs;
    }
}

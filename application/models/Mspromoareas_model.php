<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mspromoareas_model extends MY_Model
{
    public $tableName = "mspromoareas";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_rec_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_rec_id = ? ";
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
            'field' => 'fst_kode_area',
            'label' => 'Kode Area',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
    public function deleteByHeaderId($fin_promo_id)
    {
        $ssql = "delete from " . $this->tableName . " where fin_promo_id = $fin_promo_id";
        $this->db->query($ssql);
    }
}

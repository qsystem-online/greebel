<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MSPromo_model extends MY_Model
{
    public $tableName = "mspromo";
    public $pkey = "fin_promo_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_promo_id)
    {
        $ssql = "select a.*,b.ItemName from " . $this->tableName . " a left join msitems b on a.fin_promo_item_id = b.ItemId where a.fin_promo_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rw = $qr->row();

        $ssql = "select a.*,b.ItemName as ItemTerms from mspromoitems a left join msitems b on a.fin_item_id = b.ItemId  where a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoTerms = $qr->result();

        $ssql = "select a.*,b.RelationName from mspromoitemscustomer a left join msrelations b on a.fin_customer_id = b.RelationId  where a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoParticipants = $qr->result();

        $data = [
            "mspromo" => $rw,
            "promoTerms" => $rsPromoTerms,
            "promoParticipants" => $rsPromoParticipants,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_promo_name',
            'label' => 'Promo Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_satuan_gabungan',
            'label' => 'Unit',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];

        return $rules;
    }

    public function getAllList()
    {
        $ssql = "select fin_promo_id,fst_promo_name from " . $this->tableName . " where fst_active = 'A' order by fst_promo_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }
}

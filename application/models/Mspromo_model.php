<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mspromo_model extends MY_Model
{
    public $tableName = "mspromo";
    public $pkey = "fin_promo_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_promo_id)
    {
        $ssql = "select a.*,b.fst_item_name,c.fst_branch_name from " . $this->tableName . " a 
        left join msitems b on a.fin_promo_item_id = b.fin_item_id
        left join msbranches c on a.fst_list_branch_id = c.fin_branch_id  
        where a.fin_promo_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rwPromo = $qr->row_array();

        $ssql = "SELECT a.*,IF (a.fst_item_type ='ITEM',b.fst_item_name,c.fst_item_group_name) AS ItemTerms FROM mspromoitems a 
        LEFT JOIN msitems b ON a.fin_item_id = b.fin_item_id
        LEFT JOIN msgroupitems c ON a.fin_item_id = c.fin_item_group_id 
        WHERE a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoTerms = $qr->result_array();

        $ssql = "SELECT a.*, IF (a.fst_participant_type ='RELATION',b.fst_relation_name, IF(a.fst_participant_type ='RELATION GROUP',c.fst_relation_group_name,d.fst_member_group_name)) AS ParticipantName FROM mspromoitemscustomer a 
        LEFT JOIN msrelations b ON a.fin_customer_id = b.fin_relation_id
        LEFT JOIN msrelationgroups c ON a.fin_customer_id = c.fin_relation_group_id
        LEFT JOIN msmembergroups d ON a.fin_customer_id = d.fin_member_group_id  
        WHERE a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoParticipants = $qr->result_array();

        $ssql = "select a.*,b.fst_item_name from mspromodiscperitems a left join msitems b on a.fin_item_id = b.fin_item_id where a.fin_promo_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rwPromodiscItems = $qr->result_array();

        $data = [
            "mspromo" => $rwPromo,
            "promoTerms" => $rsPromoTerms,
            "promoParticipants" => $rsPromoParticipants,
            "promodiscItems" => $rwPromodiscItems,
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
            'field' => 'fst_unit_gabungan',
            'label' => 'Unit',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];
        $rules[] = [
            'field' => 'fst_list_branch_id',
            'label' => 'Branch',
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

    public function getDiscItem($finCustomerId,$finItemId,$fstUnit,$fdbQty,$trxDate = null){
        if ($trxDate == null){
            $trxDate = date("Y-m-d 23:59:59");
        }else{
            $trxDate = dBDateTimeFormat($trxDate);
        }

        $ssql = "SELECT * FROM mspromodiscperitems a 
            INNER JOIN mspromo b on a.fin_promo_id = b.fin_promo_id 
            WHERE a.fin_item_id = ? and a.fst_unit = ? and a.fin_qty < ?
            and ? between b.fdt_start and b.fdt_end
            AND b.fst_active = 'A'";

        

    }

}

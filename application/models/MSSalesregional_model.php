<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MSSalesregional_model extends MY_Model {
    public $tableName = "mssalesregional";
    public $pkey = "fin_sales_regional_id";

    public function __construct() {
        parent::__construct();
    }

    public function getDataById($fin_sales_regional_id){
        $ssql = "SELECT a.*,b.fst_name as NationalName,c.fst_username as SalesName FROM mssalesregional a 
        left join mssalesnational b on a.fin_sales_national_id = b.fin_sales_national_id
        left join users c on a.fin_sales_id = c.fin_user_id 
        WHERE a.fin_sales_regional_id = ? ORDER BY fin_sales_regional_id";
        $qr = $this->db->query($ssql, [$fin_sales_regional_id]);
        $rwSalesregional = $qr->row();

        $data = [
            "sales_area_regional" => $rwSalesregional,
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];

        $rules[] = [
            'field' => 'fst_name',
            'label' => 'Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            ),
        ];

        return $rules;
    }
}
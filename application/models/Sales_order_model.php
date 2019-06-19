<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_order_model extends MY_Model {
    public $tableName = "trsalesorder";
    public $pkey = "fin_salesorder_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fdc_vat_percent',
            'label' => 'Vat Percent',
            'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka',
			)
        ];
        
        $rules[] = [
            'field' => 'fdc_vat_amount',
            'label' => 'Vat Amount',
            'rules' => 'required|numeric',
			'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka'
			)
        ];

        $rules[] = [
            'field' => 'fdc_disc_percent',
            'label' => 'Disc Percent',
            'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka',
			)
        ];
        
        $rules[] = [
            'field' => 'fdc_disc_amount',
            'label' => 'Disc Amount',
            'rules' => 'required|numeric',
			'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka'
			)
        ];

        return $rules;
    }

    public function getDataById($fin_salesorder_id){
        $ssql = "select * from trsalesorder where fin_salesorder_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_salesorder_id]);
        $rwSalesOrder = $qr->row();

        $ssql = "select a.*,b.ItemName,c.ItemDiscount from trsalesorderdetails a left join msitems b on a.fin_item_id = b.ItemId
        left join msitemdiscounts c on a.fst_disc_item = c.ItemDiscount 
        where a.fin_salesorder_id = ? order by fin_salesorder_id";
		$qr = $this->db->query($ssql,[$fin_salesorder_id]);
		$rsSODetails = $qr->result();

		$data = [
            "sales_order" => $rwSalesOrder,
            "so_details" => $rsSODetails
		];

		return $data;
    }

    public function GenerateSONo($soDate = null) {
        $soDate = ($soDate == null) ? date ("Y-m-d"): $soDate;
        $tahun = date("ym", strtotime ($soDate));
        $prefix = getDbConfig("salesorder_prefix");
        $query = $this->db->query("SELECT MAX(fst_salesorder_no) as max_id FROM trsalesorder where fst_salesorder_no like '$tahun%'"); 
        $row = $query->row_array();
        $max_id = $row['max_id']; 
        $max_id1 =(int) substr($max_id,8,5);
        $fst_salesorder_no = $max_id1 +1;
        $maxfst_salesorder_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_salesorder_no);
        return $maxfst_salesorder_no;
    }

    /*public function getSales_order() {
        $query = $this->db->get('trsalesorder');
        return $query->result_array();
    }*/
}
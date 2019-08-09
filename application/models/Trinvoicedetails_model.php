<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trinvoicedetails_model extends MY_Model {
    public $tableName = "trinvoicedetails";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fin_salesorder_id',
            'label' => 'Sales Order No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fdt_inv_date',
            'label' => lang('Tgl Invoice'),
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_terms_payment',
            'label' => 'term',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_sj_id',
            'label' => 'Driver',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        return $rules;
    }

    //fin_rec_id	fin_inv_id	fin_sj_id	fin_item_id	fst_custom_item_name	fst_unit	fdb_qty	fdc_price	fst_disc_item	fdc_disc_amount	fbl_is_promo_disc	fst_memo_item	fin_promo_id	fst_active	fin_insert_id	fdt_insert_datetime	fin_update_id	fdt_update_datetime

}

<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SetupAccount extends MY_Controller
{
    public $menuName="Purchase_SetupAcc"; 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msconfigjurnal_model');
    }

    public function index()
    {
        parent::index();
        //$this->lizt();
        $this->openForm();
    }
   
    private function openForm()
    {
        $this->load->library("menus");
        
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        //$mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);

        $data["title"] = "Setup Purchase GL Account";
        $data["fetch_list_data"] = site_url() ."tr/purchase/setupAccount/fetch_list_data";
        $data["save_url"] = site_url() ."tr/purchase/setupAccount/ajx_edit_save";
        //$data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>1], TRUE);
        //$data["mdlPrint"] = $mdlPrint;
        
        $page_content = $this->parser->parse('pages/gl/setupacc/form', $data, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = NULL;
        $this->data["MAIN_HEADER"] = $main_header;
        $this->data["MAIN_SIDEBAR"] = $main_sidebar;
        $this->data["PAGE_CONTENT"] = $page_content;
        $this->data["MAIN_FOOTER"] = $main_footer;
        $this->data["CONTROL_SIDEBAR"] = $control_sidebar;
        $this->parser->parse('template/main', $this->data);
    }

    public function ajx_edit_save(){        
        parent::ajx_edit_save();
        try{
            $this->db->trans_start(); 
            $dataH = $this->prepareData();
            $this->validateData($dataH);
            $this->msconfigjurnal_model->update($dataH);
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $dataH["fin_rec_id"];
            $this->json_output();
        }catch(CustomException $e){
            $this->db->trans_rollback();
            $this->json_output([
                "status"=>$e->getStatus(),
                "message"=>$e->getMessage(),
                "data"=>$e->getData()
            ]);	

        }        
    }

    public function prepareData(){
        $dataH= [
            "fin_rec_id"=>$this->input->post("fin_rec_id"),
            "fst_glaccount_code"=>$this->input->post("fst_glaccount_code"),
        ];
        return $dataH;
    }

    private function validateData($dataH){        
    }


    public function fetch_list_data()
    {
        $this->load->library("datatables");
        
        
        
        $key = "'PPN_MASUKAN','DP_OUT_LOKAL','AP_DAGANG_LOKAL','DP_OUT_IMPORT','AP_DAGANG_IMPORT','CB_OUT_GIRO_MUNDUR','SELISIH_KURS_UNTUNG','SELISIH_KURS_RUGI',";
        $key .= "'PURCHASE_LOKAL','PURCHASE_IMPORT','PURCHASE_LOKAL_JADI','PURCHASE_IMPORT_JADI','PURCHASE_DISC','PURCHASE_DISC_JADI','RETURN_LOKAL','RETURN_IMPORT','AP_BIAYA_PEMBELIAN_LOKAL','AP_BIAYA_PEMBELIAN_IMPORT',";
        $key .= "'RETUR_PEMBELIAN_BELUM_REALISASI'";

        $this->datatables->setTableName("( SELECT a.*,b.fst_glaccount_name,'A' as fst_active FROM msconfigjurnal a  
            LEFT JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code
            WHERE a.fst_key in ($key) and fbl_active = 1  order by a.fin_rec_id
        ) a");

        $selectFields = " a.* ";
        $this->datatables->setSelectFields($selectFields);
        $searchFields = ["fin_item_group_id"];
        //$searchFields[] = $this->input->get('optionSearch');
        $this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $datasources["data"] = $arrData;
        $this->json_output($datasources);
    }    

}
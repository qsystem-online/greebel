<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SetupAccountLogistic extends MY_Controller
{
    public $menuName="setupaccountlogistic"; 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msconfiglogisticjurnal_model');
    }

    public function index()
    {
        parent::index();
        //$this->lizt();
        $this->openForm();
    }

    public function lizt()
    {
        $this->load->library('menus');
        $this->list['page_name'] = "Master Items";
        $this->list['list_name'] = "Master Items List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/item/add';
        $this->list['report_url'] = site_url() . 'report/items';

        $this->list['pKey'] = "fin_item_id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'master/item/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'master/item/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'master/item/edit/';
        $this->list['arrSearch'] = [
            'fin_item_id' => 'Item ID',
            'fst_item_code' => 'Item Code',
            'fst_item_name' => 'Item Name'
        ];
        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Items', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Item ID', 'width' => '10%', 'data' => 'fin_item_id'],
            ['title' => 'Item Code', 'width' => '10%', 'data' => 'fst_item_code'],
            ['title' => 'Item Name', 'width' => '15%', 'data' => 'fst_item_name'],
            ['title' => 'Vendor Item Name', 'width' => '15%', 'data' => 'fst_vendor_item_name'],
            ['title' => 'Action', 'width' => '10%', 'data' => 'action', 'sortable' => false, 'className' => 'dt-body-center text-center']
        ];
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $page_content = $this->parser->parse('template/standardList', $this->list, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main', $this->data);
    }

    private function openForm()
    {
        $this->load->library("menus");
        
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);

        $data["title"] = "Setup Account Logistic & Fixed Asset";
        $data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>1], TRUE);
        $data["mdlPrint"] = $mdlPrint;
        
        $page_content = $this->parser->parse('pages/master/setupacc/form', $data, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = NULL;
        $this->data["MAIN_HEADER"] = $main_header;
        $this->data["MAIN_SIDEBAR"] = $main_sidebar;
        $this->data["PAGE_CONTENT"] = $page_content;
        $this->data["MAIN_FOOTER"] = $main_footer;
        $this->data["CONTROL_SIDEBAR"] = $control_sidebar;
        $this->parser->parse('template/main', $this->data);
    }

    public function add()
    {
        parent::add();
        $this->openForm("ADD", 0);
    }

    public function edit($fin_item_id)
    {
        parent::edit($fin_item_id);
        $this->openForm("EDIT", $fin_item_id);
    }

    public function ajx_add_save(){
        parent::ajx_add_save();
        try{
            $this->db->trans_start(); 
            $dataH = $this->prepareData();
            unset($dataH["fin_rec_id"]);

            $this->validateData($dataH);
            $insertId = $this->msconfiglogisticjurnal_model->insert($dataH);
            $this->db->trans_complete();
            $this->ajxResp["status"] = "SUCCESS";
            $this->ajxResp["message"] = "Data Saved !";
            $this->ajxResp["data"]["insert_id"] = $insertId;
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

    public function ajx_edit_save(){        
        parent::ajx_edit_save();
        try{
            $this->db->trans_start(); 
            $dataH = $this->prepareData();
            $this->validateData($dataH);
            $this->msconfiglogisticjurnal_model->update($dataH);
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
            "fin_item_group_id"=>$this->input->post("fin_item_group_id"),
            "fst_persediaan_account_code"=>$this->input->post("fst_persediaan_account_code"),
            "fst_biaya_pabrikasi_account_code"=>$this->input->post("fst_biaya_pabrikasi_account_code"),
            "fst_biaya_umum_account_code"=>$this->input->post("fst_biaya_umum_account_code"),
            "fst_active"=>"A"
        ];
        return $dataH;
    }

    private function validateData($dataH){
        
        if (isset($dataH["fin_rec_id"])){
            $ssql = "SELECT * FROM msconfiglogisticjurnal where fin_item_group_id = ? and fin_rec_id <> ? and fst_active ='A'";
            $qr = $this->db->query($ssql,[$dataH["fin_item_group_id"],$dataH["fin_rec_id"]]);
        }else{
            $ssql = "SELECT * FROM msconfiglogisticjurnal where fin_item_group_id = ? and fst_active ='A'";
            $qr = $this->db->query($ssql,[$dataH["fin_item_group_id"]]);
        }
        $rw = $qr->row();
        if($rw != null){
            //Duplicate
            throw new CustomException(lang("Data Item Group Sudah Terdaftar"),3003,"FAILED",$rw);
        }

        $this->form_validation->set_rules($this->msconfiglogisticjurnal_model->getRules("ADD", 0));
		$this->form_validation->set_data($dataH);
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
		if ($this->form_validation->run() == FALSE) {
			throw new CustomException(lang("Error Validation Data"),3003,"VALIDATION_FORM_FAILED",$this->form_validation->error_array());
        }
    }


    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("(SELECT a.*,b.fst_item_group_name,
            c.fst_glaccount_name as fst_persediaan_account_name,
            d.fst_glaccount_name as fst_biaya_pabrikasi_account_name,
            e.fst_glaccount_name as fst_biaya_umum_account_name 
            FROM msconfiglogisticjurnal a 
            INNER JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
            LEFT JOIN glaccounts c on a.fst_persediaan_account_code = c.fst_glaccount_code
            LEFT JOIN glaccounts d on a.fst_biaya_pabrikasi_account_code = d.fst_glaccount_code
            LEFT JOIN glaccounts e on a.fst_biaya_umum_account_code = e.fst_glaccount_code

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

    public function fetch_data($fin_item_id)
    {
        $this->load->model("msitems_model");
        $data = $this->msitems_model->getDataById($fin_item_id);
        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function delete($finRecId){
        parent::delete($finRecId);
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}        
        $this->db->trans_start();
		$result = $this->msconfiglogisticjurnal_model->delete($finRecId);
		$this->db->trans_complete();
		if ($result["status"] ==  true){
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["messages"] = lang("Data telah dihapus !");		
		}else{
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["messages"] = $result["message"];
		}
		$this->json_output();
	}

    public function ajxGetGlAccount(){
        $term = $this->input->get("term");
        $term = "%$term%";

        $ssql = "SELECT * FROM glaccounts 
            WHERE (fst_glaccount_code like ? or fst_glaccount_name like ?)
            AND fst_glaccount_level in ('DT')
            AND fst_active ='A'";
        $qr = $this->db->query($ssql,[$term,$term]);

        //var_dump($this->db->error());
        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);

    }
    

}
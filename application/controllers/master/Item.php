<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Item extends MY_Controller
{
    public $menuName="items"; 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msitems_model');
        $this->load->model('mslinebusiness_model');
    }

    public function index()
    {
        parent::index();
        $this->lizt();
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

    private function openForm($mode = "ADD", $fin_item_id = 0)
    {
        $this->load->library("menus");
        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $mdlPrint = $this->parser->parse('template/mdlPrint.php', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Items" : "Update Master Items";
        $data["fin_item_id"] = $fin_item_id;
        $data["mdlItemGroup"] =$this->parser->parse('template/mdlItemGroup', ["readOnly"=>0], true);
        $data["mdlPrint"] = $mdlPrint;
        $data["linebusinessList"] =$this->mslinebusiness_model->get_data_linebusiness();

        $page_content = $this->parser->parse('pages/master/msitems/form', $data, true);
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

    public function ajx_add_save()
    {
        parent::ajx_add_save();
        $this->load->model('msitems_model');
        $this->form_validation->set_rules($this->msitems_model->getRules("ADD", 0));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }
        $data = [
            "fst_item_code" => $this->input->post("fst_item_code"),
            "fst_item_name" => $this->input->post("fst_item_name"),
            "fst_vendor_item_name" => $this->input->post("fst_vendor_item_name"),
            "fst_name_on_pos" => $this->input->post("fst_name_on_pos"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fin_item_type_id" => $this->input->post("fin_item_type_id"),
            "fst_linebusiness_id" => implode(",",$this->input->post("fst_linebusiness_id")),
            "fbl_stock" => ($this->input->post("fbl_stock") == null) ? 0 : 1,
            "fbl_is_batch_number" => ($this->input->post("fbl_is_batch_number") == null) ? 0 : 1,
            "fbl_is_serial_number" => ($this->input->post("fbl_is_serial_number") == null) ? 0 : 1,
            "fdc_scale_for_bom" => $this->input->post("fdc_scale_for_bom"),
            "fst_storage_rack_info" => $this->input->post("fst_storage_rack_info"),
            "fst_memo" => $this->input->post("fst_memo"),
            "fst_sni_no" => $this->input->post("fst_sni_no"),
            "fst_max_item_discount" => $this->input->post("fst_max_item_discount"),
            "fdc_min_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_min_basic_unit_avg_cost")),
            "fdc_max_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_max_basic_unit_avg_cost")),
            "fbl_is_online" => ($this->input->post("fbl_is_online") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];
        $this->db->trans_start();
        $insertId = $this->msitems_model->insert($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }
        //Save Image
		if (!empty($_FILES['fst_image']['tmp_name'])) {
			$config['upload_path']          = './assets/app/items/image';
			$config['file_name']			= $data["fst_item_code"]. '.jpg';
			$config['overwrite']			= TRUE;
			$config['file_ext_tolower']		= TRUE;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0; //kilobyte
			$config['max_width']            = 0; //1024; //pixel
			$config['max_height']           = 0; //768; //pixel
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('fst_image')) {
				$this->ajxResp["status"] = "IMAGES_FAILED";
				$this->ajxResp["message"] = "Failed to upload images, " . $this->upload->display_errors();
				$this->ajxResp["data"] = $this->upload->display_errors();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			} else {
				//$data = array('upload_data' => $this->upload->data());			
			}
			$this->ajxResp["data"]["data_image"] = $this->upload->data();
		}
        //Save Unit Detail
        $this->load->model("msitemunitdetails_model");
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "fin_item_id" => $insertId,
                "fst_unit" => $item->fst_unit,
                "fbl_is_basic_unit" => $item->fbl_is_basic_unit,
                "fdc_conv_to_basic_unit" => $item->fdc_conv_to_basic_unit,
                "fbl_is_selling" => $item->fbl_is_selling,
                "fbl_is_buying" => $item->fbl_is_buying,
                "fbl_is_production_output" => $item->fbl_is_production_output,
                "fdc_price_list" => $item->fdc_price_list,
                "fdc_het" => $item->fdc_het
            ];
            $this->msitemunitdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }
        //Save Bom Detail
        $this->load->model("msitembomdetails_model");
        //$this->msitembomdetails_model->deleteByHeaderId($fin_item_id);
        $details = $this->input->post("detailBOM");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "fin_item_id" => $insertId,
                "fin_item_id_bom" => $item->fin_item_id_bom,
                "fst_unit" => $item->fst_unit,
                "fdb_qty" => $item->fdb_qty
            ];
            $this->msitembomdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }
        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }

    public function ajx_edit_save()
    {        
        parent::ajx_edit_save();
        $this->load->model('msitems_model');
        $fin_item_id = $this->input->post("fin_item_id");
        $data = $this->msitems_model->getDataById($fin_item_id);
        $msitems = $data["ms_items"];
        if (!$msitems) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $fin_item_id Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }
        $this->form_validation->set_rules($this->msitems_model->getRules("EDIT", $fin_item_id));
        $this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');
        if ($this->form_validation->run() == FALSE) {
            //print_r($this->form_validation->error_array());
            $this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
            $this->ajxResp["message"] = "Error Validation Forms";
            $this->ajxResp["data"] = $this->form_validation->error_array();
            $this->json_output();
            return;
        }
        $data = [
            "fin_item_id" => $fin_item_id,
            "fst_item_code" => $this->input->post("fst_item_code"),
            "fst_item_name" => $this->input->post("fst_item_name"),
            "fst_vendor_item_name" => $this->input->post("fst_vendor_item_name"),
            "fst_name_on_pos" => $this->input->post("fst_name_on_pos"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fin_item_type_id" => $this->input->post("fin_item_type_id"),
            "fst_linebusiness_id" => implode(",",$this->input->post("fst_linebusiness_id")),
            "fbl_stock" => ($this->input->post("fbl_stock") == null) ? 0 : 1,
            "fbl_is_batch_number" => ($this->input->post("fbl_is_batch_number") == null) ? 0 : 1,
            "fbl_is_serial_number" => ($this->input->post("fbl_is_serial_number") == null) ? 0 : 1,
            "fdc_scale_for_bom" => $this->input->post("fdc_scale_for_bom"),
            "fst_storage_rack_info" => $this->input->post("fst_storage_rack_info"),
            "fst_memo" => $this->input->post("fst_memo"),
            "fst_sni_no" => $this->input->post("fst_sni_no"),
            "fst_max_item_discount" => $this->input->post("fst_max_item_discount"),
            "fdc_min_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_min_basic_unit_avg_cost")),
            "fdc_max_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_max_basic_unit_avg_cost")),
            "fbl_is_online" => ($this->input->post("fbl_is_online") == null) ? 0 : 1,
            "fst_active" => 'A'
        ];
        $this->db->trans_start();
        $this->msitems_model->update($data);
        $dbError  = $this->db->error();
        if ($dbError["code"] != 0) {
            $this->ajxResp["status"] = "DB_FAILED";
            $this->ajxResp["message"] = "Insert Failed";
            $this->ajxResp["data"] = $this->db->error();
            $this->json_output();
            $this->db->trans_rollback();
            return;
        }
        //Save Image
		if (!empty($_FILES['fst_image']['tmp_name'])) {
			$config['upload_path']          = './assets/app/items/image';
			$config['file_name']			= $data["fst_item_code"]. '.jpg';
			$config['overwrite']			= TRUE;
			$config['file_ext_tolower']		= TRUE;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0; //kilobyte
			$config['max_width']            = 0; //1024; //pixel
			$config['max_height']           = 0; //768; //pixel
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('fst_image')) {
				$this->ajxResp["status"] = "IMAGES_FAILED";
				$this->ajxResp["message"] = "Failed to upload images, " . $this->upload->display_errors();
				$this->ajxResp["data"] = $this->upload->display_errors();
				$this->json_output();
				$this->db->trans_rollback();
				return;
			} else {
				//$data = array('upload_data' => $this->upload->data());			
			}
			$this->ajxResp["data"]["data_image"] = $this->upload->data();
		}
        //Save Unit Detail
        $this->load->model("msitemunitdetails_model");
        $this->msitemunitdetails_model->deleteByHeaderId($fin_item_id);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "fin_rec_id"=>$item->fin_rec_id,
                "fin_item_id" => $fin_item_id,
                "fst_unit" => $item->fst_unit,
                "fbl_is_basic_unit" => $item->fbl_is_basic_unit,
                "fdc_conv_to_basic_unit" => $item->fdc_conv_to_basic_unit,
                "fbl_is_selling" => $item->fbl_is_selling,
                "fbl_is_buying" => $item->fbl_is_buying,
                "fbl_is_production_output" => $item->fbl_is_production_output,
                "fdc_price_list" => $item->fdc_price_list,
                "fdc_het" => $item->fdc_het
            ];
            $this->msitemunitdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }
        //Save Bom Detail
        $this->load->model("msitembomdetails_model");
        $this->msitembomdetails_model->deleteByHeaderId($fin_item_id);
        $details = $this->input->post("detailBOM");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "fin_item_id" => $fin_item_id,
                "fin_item_id_bom" => $item->fin_item_id_bom,
                "fst_unit" => $item->fst_unit,
                "fdb_qty" => $item->fdb_qty
            ];
            $this->msitembomdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }

        //Save Return Non Component        
        $this->load->model("msitemnoncomponentdetails_model");
        $this->msitemnoncomponentdetails_model->deleteByHeaderId($fin_item_id);
        $details = $this->input->post("detailReturnNonComponent");
        $details = json_decode($details);
        
        foreach ($details as $item) {
            $hppItemList =  $item->fst_item_list_id;
            $strHppItemId = "";
            foreach($hppItemList as $hppItem){
                $strHppItemId .= $hppItem->id . ",";
            }
            $strHppItemId = rtrim($strHppItemId,",");


            $data = [
                "fin_item_id" => $fin_item_id,
                "fin_nc_item_id" => $item->fin_nc_item_id,
                "fst_hpp_type" => $item->fst_hpp_type,
                "fst_item_list_id" => $strHppItemId,
                "fst_active" => "A"
            ];

            $this->msitemnoncomponentdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->db->trans_rollback();
                $this->json_output([
                    "status"=>"DB_FAILED",
                    "message"=>"Insert Detail Failed",
                    "data"=>$this->db->error()
                ]);
                return;
            }
        }

        



        //Save Special pricing
        $this->load->model("msitemspecialpricinggroupdetails_model");
        $this->msitemspecialpricinggroupdetails_model->deleteByHeaderId($fin_item_id);
        $details = $this->input->post("specialprice");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "fin_item_id" => $fin_item_id,
                "fst_unit" => $item->fst_unit,
                "fin_cust_pricing_group_id" => $item->fin_cust_pricing_group_id,
                "fdc_selling_price" => $item->fdc_selling_price
            ];
            $this->msitemspecialpricinggroupdetails_model->insert($data);
            $dbError  = $this->db->error();
            if ($dbError["code"] != 0) {
                $this->ajxResp["status"] = "DB_FAILED";
                $this->ajxResp["message"] = "Insert Detail Failed";
                $this->ajxResp["data"] = $this->db->error();
                $this->json_output();
                $this->db->trans_rollback();
                return;
            }
        }
        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $fin_item_id;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("msitems");
        $selectFields = "fin_item_id,fst_item_code,fst_item_name,fst_vendor_item_name,'action' as action";
        $this->datatables->setSelectFields($selectFields);
        $searchFields = [];
        $searchFields[] = $this->input->get('optionSearch');
        $this->datatables->setSearchFields($searchFields);
        $this->datatables->activeCondition = "fst_active !='D'";
        // Format Data
        $datasources = $this->datatables->getData();
        $arrData = $datasources["data"];
        $arrDataFormated = [];
        foreach ($arrData as $data) {
            //action
            $data["action"]    = "<div style='font-size:16px'>
					<a class='btn-edit' href='#' data-id='" . $data["fin_item_id"] . "'><i class='fa fa-pencil'></i></a>
				</div>";
            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($fin_item_id)
    {
        $this->load->model("msitems_model");
        $data = $this->msitems_model->getDataById($fin_item_id);
        //$this->load->library("datatables");		
        $this->json_output($data);
    }

    public function delete($fin_item_id){
        parent::delete($fin_item_id);
		if (!$this->aauth->is_permit("")) {
			$this->ajxResp["status"] = "NOT_PERMIT";
			$this->ajxResp["message"] = "You not allowed to do this operation !";
			$this->json_output();
			return;
		}

		$this->db->trans_start();

		$result = $this->msitems_model->delete($fin_item_id);
		$this->db->trans_complete();
		if ($result["status"] ==  true){
			$this->ajxResp["status"] = "SUCCESS";
			$this->ajxResp["message"] = lang("ITEM Telah dihapus");		
		}else{
			$this->ajxResp["status"] = "FAILED";
			$this->ajxResp["message"] = $result["message"];
		}
		$this->json_output();
	}

    public function get_data_ItemMainGroupId(){
        $term = $this->input->get("term");
        $ssql = "select * from msmaingroupitems where fst_item_maingroup_name like ? order by fst_item_maingroup_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_data_ItemGroupId()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msgroupitems where fst_item_group_name like ? order by fst_item_group_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_data_ItemSubGroupId($fin_item_group_id)
    {
        $term = $this->input->get("term");
        $ssql = "select * from mssubgroupitems where fst_item_subgroup_name like ? and fin_item_group_id = ?";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $fin_item_group_id]);
        $rs = $qr->result();
        $this->json_output($rs);
    }
    public function get_data_vendorName(){
        $term = $this->input->get("term");
        $ssql = "select * from msitems where fst_vendor_item_name like ? order by fst_vendor_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_relationVendor(){
        $fstLinebusiness = $this->input->post("fst_linebusiness");
        $arrLB = json_decode ($fstLinebusiness);
        //var_dump($arrLB);
        //die();
        $fstLB = '';
        foreach($arrLB as $lb){
            $fstLB .= $lb . '|';
        }
        $fstLB = rtrim($fstLB,'|');
        //var_dump($fstLB);
        $term = $this->input->get("term");
        $ssql = "select * from msrelations where REPLACE(fst_linebusiness_id,',','|') REGEXP '$fstLB' and fst_relation_name like ? and FIND_IN_SET('2',fst_relation_type) order by fst_relation_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        //echo $this->db->last_query();
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_data_ItemCode(){
        $term = $this->input->get("term");
        $ssql = "select * from msitems where fst_item_code like ? order by fst_item_code";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function getAllList()
    {
        $this->load->model('msitems_model');
        $result = $this->msitems_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_data_ItemBom()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitems where fst_item_name like ? order by fst_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_data_unit()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msunits where fst_unit like ? order by fst_unit";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_data_unitbom($fin_item_id)
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitemunitdetails where fst_unit like ? and fin_item_id = ? order by fst_unit";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $fin_item_id]);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_data_pricinggroup()
    {
        $term = $this->input->get("term");
        $ssql = "select * from mscustpricinggroups where fst_cust_pricing_group_name like ? order by fin_cust_pricing_group_id";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();
        $this->json_output($rs);
    }

    public function get_selling_unit($itemId,$custId,$warehouseId){
        $this->load->model("msitemunitdetails_model");
        $this->load->model("trinventory_model");

        $units = $this->msitemunitdetails_model->getSellingListUnit($itemId);
        $result = [];
        foreach($units as $unit){
            $sellingPrice = $this->msitems_model->getSellingPrice($itemId,$unit->fst_unit,$custId);
            $result[] = (object) [
                "fst_unit"=>$unit->fst_unit,
                "fst_basic_unit"=>$this->msitemunitdetails_model->getBasicUnit($itemId),
                "fdc_conv_to_basic_unit"=>$unit->fdc_conv_to_basic_unit,
                "sellingPrice" => $sellingPrice,
                "real_stock" => $this->trinventory_model->getStock($itemId,$unit->fst_unit,$warehouseId),
                "marketing_stock" => $this->trinventory_model->getMarketingStock($itemId,$unit->fst_unit,$warehouseId)
            ];
        }
        //$this->json_output($units);
        $this->json_output($result);
    }
    
    public function get_selling_price($fin_item_id,$fst_unit,$fin_customer_id){
        $sellingPrice = $this->msitems_model->getSellingPrice($fin_item_id,$fst_unit,$fin_customer_id);
        $resp = [
            "sellingPrice" => $sellingPrice,
            "fin_item_id"=> $fin_item_id,
            "fst_unit" =>$fst_unit,
            "fin_customer_id" => $fin_customer_id
        ];
        $this->json_output($resp);
    }

    public function get_line_business($groupid)
    {
        $ssql = "SELECT * FROM msitems WHERE fin_item_group_id = ? ";
        $rs = $qr->result();
        foreach($rs as $rw){
            $lbC.=','.$rw->fst_linebusiness_id;
        }
        $lbA = '';
        $lbB = '';
        $lbC = $lbA .",". $lbB;
        $arrlbC = explode(",",$lbC);
        $arrlbD = array_unique($arrlbC);
        $lbD = implode(",",$arrlbD);
        $term = $this->input->get("term");
        $ssql = "SELECT * FROM mslinebusiness WHERE fin_linebusiness_id IN ($lbD) ";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $groupid]);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function testTree(){
        $this->load->library('menus');
        $this->list['page_name'] = "Master Items";
        $this->list['list_name'] = "Master Items List";
        $this->list['addnew_ajax_url'] = site_url() . 'master/item/add';
        $this->list['pKey'] = "id";
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
        $this->list['title'] = "Demo Tree";
        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
        $page_content = $this->parser->parse('test/test_tree', $this->list, true);
        $main_footer = $this->parser->parse('inc/main_footer', [], true);
        $control_sidebar = null;
        $this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
        $this->data['MAIN_HEADER'] = $main_header;
        $this->data['MAIN_SIDEBAR'] = $main_sidebar;
        $this->data['PAGE_CONTENT'] = $page_content;
        $this->data['MAIN_FOOTER'] = $main_footer;
        $this->parser->parse('template/main', $this->data);
    }

    public function get_printItem($lineBussines,$vendorName,$groupName,$itemCode_awal,$itemCode_akhir) {
        $layout = $this->input->post("layoutColumn");
        $arrLayout = json_decode($layout);
        $vendorName = urldecode($vendorName);
        
        /*var_dump($arrLayout);
        echo "PRINT......";
        
        foreach($arrLayout as $layout){
            if($layout->column == "fin_cust_pricing_group_id"){
                if($layout->hidden == true){
                    echo $hidden;
                }else{
                    echo $show;
                }
            }
        }
        //die();*/
        
        $this->load->model("msitems_model");
        $this->load->library("phpspreadsheet");
        
        $spreadsheet = $this->phpspreadsheet->load(FCPATH . "assets/templates/template_items_log.xlsx");
        $sheet = $spreadsheet->getActiveSheet();
        
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		$sheet->getPageMargins()->setTop(1);
		$sheet->getPageMargins()->setRight(0.5);
		$sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setBottom(1);

        //AUTO SIZE COLUMN
        $sheet->getColumnDimension("A")->setAutoSize(true);
        $sheet->getColumnDimension("B")->setAutoSize(true);
        $sheet->getColumnDimension("C")->setAutoSize(true);
        $sheet->getColumnDimension("D")->setAutoSize(true);
        $sheet->getColumnDimension("E")->setAutoSize(true);
        $sheet->getColumnDimension("F")->setAutoSize(true);
        $sheet->getColumnDimension("G")->setAutoSize(true);

        // SUBTITLE
        $sheet->mergeCells('B4:D4');
        $sheet->mergeCells('B5:D5');
        $sheet->mergeCells('B3:D3');

        //HEADER COLUMN
        $sheet->setCellValue("A7", "No.");
        $sheet->setCellValue("B7", "Item Code");
        $sheet->setCellValue("C7", "Item Name");
        $sheet->setCellValue("D7", "Harga Beli");
        $sheet->setCellValue("E7", "Satuan");
        $sheet->setCellValue("F7", "Harga Jual");
        $sheet->setCellValue("G7", "Satuan");
        $ssql = "Select * from mscustpricinggroups where fst_active = 'A' ";
        $qr = $this->db->query($ssql,[]);
        $rs = $qr->result();
        $i = 6;
        foreach($rs as $rw){
            $i = $i + 1;
            $col = $this->phpspreadsheet->getNameFromNumber($i);
            $sheet->setCellValue($col."7", $rw->fst_cust_pricing_group_name);
            $sheet->getColumnDimension($col)->setAutoSize (true);
        }

        //TITLE
        $sheet->mergeCells('A1:'.$col.'1');
        $sheet->setCellValue("A1", "Daftar Barang");

        //FORMAT NUMBER
        $spreadsheet->getActiveSheet()->getStyle('D8:'.$col.'500')->getNumberFormat()->setFormatCode('#,##0.00');
        
        //COLOR HEADER COLUMN
        $spreadsheet->getActiveSheet()->getStyle('A7:'.$col.'7')
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('99FFFF');

        //FONT HEADER CENTER
        $spreadsheet->getActiveSheet()->getStyle('A7:'.$col.'7')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //FONT ITALIC
        $italycArray = [
            'font' => [
                'italic' => true,
            ],
        ];

        //FONT BOLD
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
        ];
        $sheet->getStyle('A7:'.$col.'7')->applyFromArray($styleArray);
        $sheet->getStyle('B3:N3')->applyFromArray($styleArray);
        $sheet->getStyle('B4:N4')->applyFromArray($styleArray);
        $sheet->getStyle('B5:N5')->applyFromArray($styleArray);

        //FONT SIZE
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);
        $spreadsheet->getActiveSheet()->getStyle("A3:".$col."5")->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A7:".$col."7")->getFont()->setSize(12);
        $iRow1 = 4;
        $iRow2 = 5;
        $iRow = 8;
        $no = 1;

        //DATE & TIME
        $sheet->setCellValue('F3', '=NOW()');
        $sheet->mergeCells('F3:'.$col.'3');
        $sheet->setCellValue('F4', '=NOW()');
        $sheet->mergeCells('F4:'.$col.'4');
        $printItem = $this->msitems_model->getPrintItem($vendorName,$groupName,$itemCode_awal,$itemCode_akhir);
        $prevItemid ="";
        foreach ($printItem as $rw) {
            $ssql = "select * from mscustpricinggroups where fst_active ='A'";
            /*
            $sellingPrice = $this->msitems_model->getSellingPriceByPricingGroup($rw->fin_item_id,$rw->fst_unit,$rw->fin_cust_pricing_group_id);
            $ssql = "select a.*,b.fst_cust_pricing_group_name from msitemspecialpricinggroupdetails a
                left join mscustpricinggroups b on a.fin_cust_pricing_group_id = b.fin_cust_pricing_group_id
                where a.fin_item_id = ? and a.fst_unit = ? and a.fin_cust_pricing_group_id = ? and a.fst_active = 'A' ";
            $qr = $this->db->query($ssql,[$rw->fin_item_id,$rw->fst_unit,$rw->fin_cust_pricing_group_id]);
            */
            $qr = $this->db->query($ssql,[]);
            $rs = $qr->result();
            $i = 7;
            $nb = 1;
            //$sheet->setCellValue("A$iRow", $no++);
            //$prevItemid ="";
            if ($prevItemid != $rw->fin_item_id ){
                if ($prevItemid != ""){
                    $ssql = "select a.*,b.fst_item_name from msitembomdetails a left join msitems b on a.fin_item_id_bom = b.fin_item_id  where a.fin_item_id = ?";
                    $qr = $this->db->query($ssql, [$prevItemid]);
                    $rsBomDetail = $qr->result();

                    foreach ($rsBomDetail as $roBomDetail){
                        //
                        //$sheet->setCellValue("B$iRow", "BOM");
                        $sheet->getStyle("C$iRow:E$iRow")->applyFromArray($italycArray);
                        $spreadsheet->getActiveSheet()->getStyle("C$iRow:E$iRow")->getFont()->getColor()->setRGB('0000FF');
                        $sheet->setCellValue("C$iRow", $roBomDetail->fst_item_name);
                        $sheet->setCellValue("E$iRow", $roBomDetail->fst_unit);
                        $iRow++;
                    }
                }
                $sheet->setCellValue("A$iRow", $no++);
                $sheet->setCellValue("B$iRow1", $rw->vendorName1); //fin_item_id & fst_vendor_item_name
                $sheet->setCellValue("B$iRow2", $rw->itemGroup); //fin_item_group_id & fst_item_group_name
                //$sheet->setCellValue("A$iRow", $no++);
                $sheet->setCellValue("B$iRow", $rw->fst_item_code);
                $sheet->setCellValue("C$iRow", $rw->fst_item_name);
                $sheet->setCellValue("D$iRow", 0);
                $sheet->setCellValue("E$iRow", $rw->fst_unit);
                $sheet->setCellValue("F$iRow", $rw->fdc_price_list);
                $sheet->setCellValue("G$iRow", $rw->fst_unit);
                $prevItemid = $rw->fin_item_id;
            }
            $sheet->setCellValue("G$iRow", $rw->fst_unit);
            foreach ($rs as $ro){
                
                $sellingPrice = $this->msitems_model->getSellingPriceByPricingGroup($rw->fin_item_id,$rw->fst_unit,$ro->fin_cust_pricing_group_id);
                
                $col = $this->phpspreadsheet->getNameFromNumber($i);
                $sheet->setCellValue($col.$iRow, $sellingPrice);
                $i = $i + 1;
               
            }
            $iRow++;

            
            $iRow++;
            
        }
        $ssql = "select a.*,b.fst_item_name from msitembomdetails a left join msitems b on a.fin_item_id_bom = b.fin_item_id  where a.fin_item_id = ?";
        $qr = $this->db->query($ssql, [$prevItemid]);
        $rsBomDetail = $qr->result();

        foreach ($rsBomDetail as $roBomDetail){
            //$sheet->setCellValue("B$iRow", "BOM");
            $sheet->getStyle("C$iRow:E$iRow")->applyFromArray($italycArray);
            $spreadsheet->getActiveSheet()->getStyle("C$iRow:E$iRow")->getFont()->getColor()->setRGB('0000FF');
            $sheet->setCellValue("C$iRow", $roBomDetail->fst_item_name);
            $sheet->setCellValue("E$iRow", $roBomDetail->fst_unit);
            $iRow++;
        }

        //BORDER
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED
                ],
            ],
        ];
        $iRow = $iRow - 1;
        $sheet->getStyle('A7:'.$col.$iRow)->applyFromArray($styleArray);
        
        //FILE NAME WITH DATE
        $this->phpspreadsheet->save("item_report_" . date("Ymd") . ".xls" ,$spreadsheet);
    }

    public function ajx_get_list_stock($finItemId,$fstUnit){
        $this->load->model("trinventory_model");
        $finBranchId = $this->aauth->get_active_branch_id();
        $listStock = $this->trinventory_model->getListStock($finItemId,$fstUnit,$finBranchId);
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "";
        $this->ajxResp["data"]["stock_list"] = $listStock;
        $this->json_output();
    }


    public function ajxGetListItemBOM($finItemId){
        $term = $this->input->get("term");
        $term = "%$term%";

        $ssql = "SELECT b.fin_item_id,b.fst_item_name,b.fst_item_code FROM msitembomdetails a
            INNER JOIN msitems b on a.fin_item_id_bom = b.fin_item_id 
            where a.fin_item_id = ? and b.fst_item_name like ? and b.fst_item_code like ? and a.fst_active ='A'";

        $qr = $this->db->query($ssql,[$finItemId,$term,$term]);
        $rs =$qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
    }

    public function ajxGetListItemMaster(){
        $term = $this->input->get("term");
        $term = "%$term%";

        $ssql = "SELECT fin_item_id,fst_item_name,fst_item_code FROM msitems 
            where fst_item_name like ? and fst_item_code like ? and fst_active ='A'";

        $qr = $this->db->query($ssql,[$term,$term]);
        $rs =$qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
    }
    

}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Item extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('msitems_model');
    }
    public function index()
    {
        $this->lizt();
    }
    public function lizt()
    {
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
        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Items" : "Update Master Items";
        $data["fin_item_id"] = $fin_item_id;
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
        $this->openForm("ADD", 0);
    }
    public function Edit($fin_item_id)
    {
        $this->openForm("EDIT", $fin_item_id);
    }
    public function ajx_add_save()
    {
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
            "fin_item_maingroup_id" => $this->input->post("fin_item_maingroup_id"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fin_item_subgroup_id" => $this->input->post("fin_item_subgroup_id"),
            "fin_item_type_id" => $this->input->post("fin_item_type_id"),
            "fin_standard_vendor_id" => $this->input->post("fin_standard_vendor_id"),
            "fin_optional_vendor_id" => $this->input->post("fin_optional_vendor_id"),
            "fbl_is_batch_number" => ($this->input->post("fbl_is_batch_number") == null) ? 0 : 1,
            "fbl_is_serial_number" => ($this->input->post("fbl_is_serial_number") == null) ? 0 : 1,
            "fdc_scale_for_bom" => $this->input->post("fdc_scale_for_bom"),
            "fst_storage_rack_info" => $this->input->post("fst_storage_rack_info"),
            "fst_memo" => $this->input->post("fst_memo"),
            "fst_sni_no" => $this->input->post("fst_sni_no"),
            "fst_max_item_discount" => $this->input->post("fst_max_item_discount"),
            "fdc_min_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_min_basic_unit_avg_cost")),
            "fdc_max_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_max_basic_unit_avg_cost")),
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
        $this->db->trans_complete();
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["message"] = "Data Saved !";
        $this->ajxResp["data"]["insert_id"] = $insertId;
        $this->json_output();
    }
    public function ajx_edit_save()
    {
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
            "fin_item_maingroup_id" => $this->input->post("fin_item_maingroup_id"),
            "fin_item_group_id" => $this->input->post("fin_item_group_id"),
            "fin_item_subgroup_id" => $this->input->post("fin_item_subgroup_id"),
            "fin_item_type_id" => $this->input->post("fin_item_type_id"),
            "fin_standard_vendor_id" => $this->input->post("fin_standard_vendor_id"),
            "fin_optional_vendor_id" => $this->input->post("fin_optional_vendor_id"),
            "fbl_is_batch_number" => ($this->input->post("fbl_is_batch_number") == null) ? 0 : 1,
            "fbl_is_serial_number" => ($this->input->post("fbl_is_serial_number") == null) ? 0 : 1,
            "fdc_scale_for_bom" => $this->input->post("fdc_scale_for_bom"),
            "fst_storage_rack_info" => $this->input->post("fst_storage_rack_info"),
            "fst_memo" => $this->input->post("fst_memo"),
            "fst_sni_no" => $this->input->post("fst_sni_no"),
            "fst_max_item_discount" => $this->input->post("fst_max_item_discount"),
            "fdc_min_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_min_basic_unit_avg_cost")),
            "fdc_max_basic_unit_avg_cost" => parseNumber($this->input->post("fdc_max_basic_unit_avg_cost")),
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
                "fst_unit" => $item->fst_unit
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
					<a class='btn-delete' href='#' data-id='" . $data["fin_item_id"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
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
    public function delete($id)
    {
        if (!$this->aauth->is_permit("")) {
            $this->ajxResp["status"] = "NOT_PERMIT";
            $this->ajxResp["message"] = "You not allowed to do this operation !";
            $this->json_output();
            return;
        }
        $this->load->model("msitems_model");
        $this->msitems_model->delete($id);
        $this->ajxResp["status"] = "DELETED";
        $this->ajxResp["message"] = "File deleted successfully";
        $this->json_output();
    }
    public function get_data_ItemMainGroupId()
    {
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
                "sellingPrice" => $sellingPrice,
                "real_stock" => $this->trinventory_model->getStock($warehouseId,$itemId,$unit->fst_unit),
                "marketing_stock" => $this->trinventory_model->getMarketingStock($warehouseId,$itemId,$unit->fst_unit)
            ];
        }
        //$this->json_output($units);
        $this->json_output($result);
    }
    public function get_selling_price($itemId,$unit,$custId){
        $sellingPrice = $this->msitems_model->getSellingPrice($itemId,$unit,$custId);
        $resp = [
            "sellingPrice" => $sellingPrice,
            "fin_item_id"=> $itemId,
            "fst_unit" =>$unit,
            "fin_customer_id" => $custId
        ];
        $this->json_output($resp);
    }
}
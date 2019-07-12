<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MSItems extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('MSItems_model');
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
        $this->list['addnew_ajax_url'] = site_url() . 'Master/msitems/add';
        $this->list['pKey'] = "id";
        $this->list['fetch_list_data_ajax_url'] = site_url() . 'Master/msitems/fetch_list_data';
        $this->list['delete_ajax_url'] = site_url() . 'Master/msitems/delete/';
        $this->list['edit_ajax_url'] = site_url() . 'Master/msitems/edit/';
        $this->list['arrSearch'] = [
            'ItemId' => 'Item ID',
            'ItemCode' => 'Item Code',
            'ItemName' => 'Item Name'
        ];

        $this->list['breadcrumbs'] = [
            ['title' => 'Home', 'link' => '#', 'icon' => "<i class='fa fa-dashboard'></i>"],
            ['title' => 'Master Items', 'link' => '#', 'icon' => ''],
            ['title' => 'List', 'link' => NULL, 'icon' => ''],
        ];
        $this->list['columns'] = [
            ['title' => 'Item ID', 'width' => '10%', 'data' => 'ItemId'],
            ['title' => 'Item Code', 'width' => '10%', 'data' => 'ItemCode'],
            ['title' => 'Item Name', 'width' => '15%', 'data' => 'ItemName'],
            ['title' => 'Vendor Item Name', 'width' => '15%', 'data' => 'VendorItemName'],
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

    private function openForm($mode = "ADD", $ItemId = 0)
    {
        $this->load->library("menus");

        if ($this->input->post("submit") != "") {
            $this->add_save();
        }

        $main_header = $this->parser->parse('inc/main_header', [], true);
        $main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);

        $data["mode"] = $mode;
        $data["title"] = $mode == "ADD" ? "Add Master Items" : "Update Master Items";
        $data["ItemId"] = $ItemId;

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

    public function Edit($ItemId)
    {
        $this->openForm("EDIT", $ItemId);
    }

    public function ajx_add_save()
    {
        $this->load->model('MSItems_model');
        $this->form_validation->set_rules($this->MSItems_model->getRules("ADD", 0));
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
            "ItemCode" => $this->input->post("ItemCode"),
            "ItemName" => $this->input->post("ItemName"),
            "VendorItemName" => $this->input->post("VendorItemName"),
            "fst_name_on_pos" => $this->input->post("fst_name_on_pos"),
            "ItemMainGroupId" => $this->input->post("ItemMainGroupId"),
            "ItemGroupId" => $this->input->post("ItemGroupId"),
            "ItemSubGroupId" => $this->input->post("ItemSubGroupId"),
            "ItemTypeId" => $this->input->post("ItemTypeId"),
            "StandardVendorId" => $this->input->post("StandardVendorId"),
            "OptionalVendorId" => $this->input->post("OptionalVendorId"),
            "isBatchNumber" => ($this->input->post("isBatchNumber") == null) ? 0 : 1,
            "isSerialNumber" => ($this->input->post("isSerialNumber") == null) ? 0 : 1,
            "ScaleForBOM" => $this->input->post("ScaleForBOM"),
            "StorageRackInfo" => $this->input->post("StorageRackInfo"),
            "Memo" => $this->input->post("Memo"),
            "fst_sni_no" => $this->input->post("fst_sni_no"),
            "MaxItemDiscount" => $this->input->post("MaxItemDiscount"),
            "MinBasicUnitAvgCost" => parseNumber($this->input->post("MinBasicUnitAvgCost")),
            "MaxBasicUnitAvgCost" => parseNumber($this->input->post("MaxBasicUnitAvgCost")),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $insertId = $this->MSItems_model->insert($data);
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
			$config['file_name']			= $data["ItemCode"]. '.jpg';
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

        $this->load->model("MSItemunitdetails_model");
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "ItemId" => $insertId,
                "Unit" => $item->Unit,
                "isBasicUnit" => $item->isBasicUnit,
                "Conv2BasicUnit" => $item->Conv2BasicUnit,
                "isSelling" => $item->isSelling,
                "isBuying" => $item->isBuying,
                "isProductionOutput" => $item->isProductionOutput,
                "PriceList" => $item->PriceList,
                "HET" => $item->HET
            ];
            $this->MSItemunitdetails_model->insert($data);
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
        $this->load->model('MSItems_model');
        $ItemId = $this->input->post("ItemId");
        $data = $this->MSItems_model->getDataById($ItemId);
        $msitems = $data["msitems"];
        if (!$msitems) {
            $this->ajxResp["status"] = "DATA_NOT_FOUND";
            $this->ajxResp["message"] = "Data id $ItemId Not Found ";
            $this->ajxResp["data"] = [];
            $this->json_output();
            return;
        }

        $this->form_validation->set_rules($this->MSItems_model->getRules("EDIT", $ItemId));
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
            "ItemId" => $ItemId,
            "ItemCode" => $this->input->post("ItemCode"),
            "ItemName" => $this->input->post("ItemName"),
            "VendorItemName" => $this->input->post("VendorItemName"),
            "fst_name_on_pos" => $this->input->post("fst_name_on_pos"),
            "ItemMainGroupId" => $this->input->post("ItemMainGroupId"),
            "ItemGroupId" => $this->input->post("ItemGroupId"),
            "ItemSubGroupId" => $this->input->post("ItemSubGroupId"),
            "ItemTypeId" => $this->input->post("ItemTypeId"),
            "StandardVendorId" => $this->input->post("StandardVendorId"),
            "OptionalVendorId" => $this->input->post("OptionalVendorId"),
            "isBatchNumber" => ($this->input->post("isBatchNumber") == null) ? 0 : 1,
            "isSerialNumber" => ($this->input->post("isSerialNumber") == null) ? 0 : 1,
            "ScaleForBOM" => $this->input->post("ScaleForBOM"),
            "StorageRackInfo" => $this->input->post("StorageRackInfo"),
            "Memo" => $this->input->post("Memo"),
            "fst_sni_no" => $this->input->post("fst_sni_no"),
            "MaxItemDiscount" => $this->input->post("MaxItemDiscount"),
            "MinBasicUnitAvgCost" => parseNumber($this->input->post("MinBasicUnitAvgCost")),
            "MaxBasicUnitAvgCost" => parseNumber($this->input->post("MaxBasicUnitAvgCost")),
            "fst_active" => 'A'
        ];

        $this->db->trans_start();
        $this->MSItems_model->update($data);
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
			$config['file_name']			= $data["ItemCode"]. '.jpg';
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

        $this->load->model("MSItemunitdetails_model");
        $this->MSItemunitdetails_model->deleteByHeaderId($ItemId);
        $details = $this->input->post("detail");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "ItemId" => $ItemId,
                "Unit" => $item->Unit,
                "isBasicUnit" => $item->isBasicUnit,
                "Conv2BasicUnit" => $item->Conv2BasicUnit,
                "isSelling" => $item->isSelling,
                "isBuying" => $item->isBuying,
                "isProductionOutput" => $item->isProductionOutput,
                "PriceList" => $item->PriceList,
                "HET" => $item->HET
            ];
            $this->MSItemunitdetails_model->insert($data);
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

        $this->load->model("MSItembomdetails_model");
        $this->MSItembomdetails_model->deleteByHeaderId($ItemId);
        $details = $this->input->post("detailBOM");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "ItemId" => $ItemId,
                "ItemIdBOM" => $item->ItemIdBOM,
                "unit" => $item->unit
            ];
            $this->MSItembomdetails_model->insert($data);
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

        $this->load->model("MSItemspecialpricinggroupdetails_model");
        $this->MSItemspecialpricinggroupdetails_model->deleteByHeaderId($ItemId);
        $details = $this->input->post("specialprice");
        $details = json_decode($details);
        foreach ($details as $item) {
            $data = [
                "ItemId" => $ItemId,
                "Unit" => $item->Unit,
                "PricingGroupId" => $item->PricingGroupId,
                "SellingPrice" => $item->SellingPrice
            ];
            $this->MSItemspecialpricinggroupdetails_model->insert($data);
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
        $this->ajxResp["data"]["insert_id"] = $ItemId;
        $this->json_output();
    }

    public function fetch_list_data()
    {
        $this->load->library("datatables");
        $this->datatables->setTableName("msitems");

        $selectFields = "ItemId,ItemCode,ItemName,VendorItemName,'action' as action";
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
					<a class='btn-edit' href='#' data-id='" . $data["ItemId"] . "'><i class='fa fa-pencil'></i></a>
					<a class='btn-delete' href='#' data-id='" . $data["ItemId"] . "' data-toggle='confirmation'><i class='fa fa-trash'></i></a>
				</div>";

            $arrDataFormated[] = $data;
        }
        $datasources["data"] = $arrDataFormated;
        $this->json_output($datasources);
    }

    public function fetch_data($ItemId)
    {
        $this->load->model("msitems_model");
        $data = $this->msitems_model->getDataById($ItemId);

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
        $ssql = "select * from msmaingroupitems where ItemMainGroupName like ? order by ItemMainGroupName";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_ItemGroupId()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msgroupitems where ItemGroupName like ? order by ItemGroupName";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_ItemSubGroupId($itemgroupid)
    {
        $term = $this->input->get("term");
        $ssql = "select * from mssubgroupitems where ItemSubGroupName like ? and ItemGroupId = ?";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $itemgroupid]);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function getAllList()
    {
        $this->load->model('MSItems_model');
        $result = $this->MSItems_model->getAllList();
        $this->ajxResp["data"] = $result;
        $this->json_output();
    }

    public function get_data_ItemBom()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitems where ItemName like ? order by ItemName";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_unit()
    {
        $term = $this->input->get("term");
        $ssql = "select * from msunits where Unit like ? order by Unit";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_unitbom($ItemId)
    {
        $term = $this->input->get("term");
        $ssql = "select * from msitemunitdetails where Unit like ? and ItemId = ? order by Unit";
        $qr = $this->db->query($ssql, ['%' . $term . '%', $ItemId]);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function get_data_pricinggroup()
    {
        $term = $this->input->get("term");
        $ssql = "select * from mscustpricinggroups where CustPricingGroupName like ? order by CustPricingGroupId";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

        $this->json_output($rs);
    }

    public function getSellingUnit($itemId){
        $this->load->model("MSItemunitdetails_model");
        $units = $this->MSItemunitdetails_model->getSellingListUnit($itemId);
        $this->json_output($units);
    }

    public function getSellingPrice($itemId,$unit,$custId){
        $sellingPrice = $this->MSItems_model->getSellingPrice($itemId,$unit,$custId);
        $resp = [
            "sellingPrice" => $sellingPrice,
            "itemId"=> $itemId,
            "unit" =>$unit,
            "custId" => $custId
        ];
        $this->json_output($resp);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order extends MY_Controller
{

	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/trpo_rpt_model');
		$this->load->model('mswarehouse_model');
		$this->load->model('msrelations_model');
		$this->load->model('mscurrencies_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'Supplier', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.PO', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Tgl.PO', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.DO', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'No.Kontrak', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'GUD', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Kode Barang', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Nama Barang', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Qty', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Unit', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Harga', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Disc Amt', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Jumlah', 'value'=>'13', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Kurs', 'value'=>'14', 'selected'=>false,'sum_total'=>true],
			['layout' => 1, 'label'=>'Jumlah kurs', 'value'=>'15', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.P/O', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tgl.P/O', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'GUD', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.DO', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.Kontrak', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Supplier', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'M.U', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 2, 'label'=>'Rate', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Subtotal', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
            ['layout' => 2, 'label'=>'Total', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
            ['layout' => 2, 'label'=>'Total IDR', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 3, 'label'=>'GUD', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Supplier', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.PO', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl.PO', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Nama Barang', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty PO', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.Penerimaan', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tgl.Terima', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty Terima', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
            ['layout' => 3, 'label'=>'Qty O/S', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
            ['layout' => 3, 'label'=>'Status', 'value'=>'12', 'selected'=>false,'sum_total'=>false],

			['layout' => 5, 'label'=>'Sales', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Pelanggan/Customer', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Tgl.Faktur', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Jatuh Tempo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.S/J', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'No.S/O', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'GUD', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'M.U', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Nilai Faktur', 'value'=>'9', 'selected'=>false,'sum_total'=>true],
			['layout' => 5, 'label'=>'Total Retur', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Pembayaran', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
			['layout' => 5, 'label'=>'Nilai Netto', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 5, 'label'=>'Menunggak(hari)', 'value'=>'13', 'selected'=>false,'sum_total'=>true],
			['layout' => 6, 'label'=>'Sales', 'value'=>'0', 'selected'=>true,'sum_total'=>false],
			['layout' => 6, 'label'=>'No.', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'No.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'Tgl.Faktur', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'Jatuh Tempo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'No.S/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'GUD', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'Pelanggan/Customer', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 6, 'label'=>'Total IDR', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 7, 'label'=>'No.', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'Jatuh Tempo', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'No.S/O', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'GUD', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'Sales', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'Pelanggan/Customer', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'M.U', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 7, 'label'=>'Total IDR', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'Entry By', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 7, 'label'=>'Update By', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Pelanggan/Customer', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'No.Faktur', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'Tgl.Faktur', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'TOP', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'Jatuh Tempo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'No.S/O', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'GUD', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'Sales', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'Memo', 'value'=>'8', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Kode Barang', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'Nama Barang', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 8, 'label'=>'Qty', 'value'=>'11', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Unit', 'value'=>'12', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Disc%', 'value'=>'13', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Harga', 'value'=>'14', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Jumlah', 'value'=>'15', 'selected'=>false,'sum_total'=>true],
			['layout' => 8, 'label'=>'Voucher', 'value'=>'16', 'selected'=>false,'sum_total'=>true],
		];

	}

	public function index()
	{
		$this->loadForm();
	}

	public function loadForm()
	{
		$this->load->library('menus');
						
		$main_header = $this->parser->parse('inc/main_header', [], true);
		$fin_branch_id = 0;

		$this->data["fin_branch_id"] = $fin_branch_id;
		$this->data["mystatus"]="OK";
		$this->data["layout_columns"] = $this->layout_columns;
		$this->data["default_currency"] = getDefaultCurrency();
		

		$side_filter = $this->parser->parse('reports/purchase_order/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "PURCHASE ORDER REPORT";
		$mode = "Report";
		// $this->data["mode"] = $mode;
		// $this->data["title"] = $mode == "ADD" ? "Add Branch" : "Update Branch";
		$report_filterbar = $this->parser->parse('inc/report_filterbar', $this->data, true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		$page_content = null; // $this->parser->parse('template/standardList', $this->list, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);
		$control_sidebar = null;
		$this->data['ACCESS_RIGHT'] = "A-C-R-U-D-P";
		$this->data['MAIN_HEADER'] = $main_header;
		$this->data['MAIN_SIDEBAR'] = $main_sidebar;
		$this->data['REPORT_FILTERBAR'] = $report_filterbar;
		$this->data['REPORT_CONTENT'] = $page_content;
		$this->data['REPORT_FOOTER'] = $main_footer;
		$this->parser->parse('template/mainReport', $this->data);
	}

	//function ini untuk validasi form parameter report (jika ada parameter yg tidak boleh di kosongkan
	//sesuai di model)
	public function process()
	{
		// print_r('testing ajx-process');
		$this->load->model('trpo_rpt_model');
		$this->form_validation->set_rules($this->trpo_rpt_model->getRules());
		$this->form_validation->set_error_delimiters('<div class="text-danger">* ', '</div>');

		if ($this->form_validation->run() == FALSE) {
			//print_r($this->form_validation->error_array());
			$this->ajxResp["status"] = "VALIDATION_FORM_FAILED";
			$this->ajxResp["message"] = "Error Validation Forms";
			$this->ajxResp["data"] = $this->form_validation->error_array();
			$this->json_output();
			return;
		}


		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["message"] = "";
		$this->ajxResp["data"] = "";
		$this->json_output();        
		
	}


	public function generateReport($isPreview = 1){		
		//var_dump($this->input->post());
		//$activeBranchId = $this->session->userdata("active_branch_id");
		$data = [
			"fin_branch_id" => $this->input->post("fin_branch_id"),
			"fin_warehouse_id" => $this->input->post("fin_warehouse_id"),
			"fin_supplier_id" => $this->input->post("fin_supplier_id"),
            "fst_curr_code" => $this->input->post("fst_curr_code"),
            "fbl_is_import" => $this->input->post("fbl_is_import"),
			"fdt_po_datetime" => $this->input->post("fdt_po_datetime"),
			"fdt_po_datetime2" => $this->input->post("fdt_po_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->trpo_rpt_model->queryComplete($data,"a.fin_po_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}
		switch ($data['rpt_layout']){
			case "1":
				$this->parser->parse('reports/purchase_order/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "2":
				$this->parser->parse('reports/purchase_order/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "3":
				$this->parser->parse('reports/purchase_order/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "4":
				$this->parser->parse('reports/purchase_order/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "5":
				$this->parser->parse('reports/purchase_order/layout5', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "6":
				$this->parser->parse('reports/purchase_order/layout6', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "7":
				$this->parser->parse('reports/purchase_order/layout7', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			case "8":
				$this->parser->parse('reports/purchase_order/layout8', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
			default:
				$this->parser->parse('reports/purchase_order/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
				break;
		}
		
		//echo "<div id='tstdiv'>Show Report</div>";//
	}

	public function get_suppliers(){
		$this->load->model("msrelations_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msrelations_model->getSupplierList();
		$this->json_output();
	}

    public function get_data_items(){
        /*$term = $this->input->get("term");
        $ssql = "select * from msitems where fst_item_name like ? order by fst_item_name";
        $qr = $this->db->query($ssql, ['%' . $term . '%']);
        $rs = $qr->result();

		$this->json_output($rs);*/
		
		$this->load->model("msitems_model");			
		$this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $this->msitems_model->getItemList_report();
		$this->json_output();

	}
	
	public function ajxListItem(){
		$this->load->model("msitems_model");
		$searchKey = $this->input->get("term");
		$result = $this->msitems_model->getAllList($searchKey,"fin_item_id,fst_item_code,fst_item_name");        
		$this->json_output([
			"status"=>"SUCCESS",
			"data"=> $result
		]);
	}

}

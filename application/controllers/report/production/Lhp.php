<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lhp extends MY_Controller
{

	public $menuName="report_lhp";
	public $layout_columns =[];
	public $spreadsheet; 

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('vmodels/trlhp_rpt_model');

		$this->layout_columns = [
			['layout' => 1, 'label'=>'Kode', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Produk Jadi', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty WO', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty Prod', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Sisa Target', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Unit', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Qty Prod', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 1, 'label'=>'Unit', 'value'=>'7', 'selected'=>false,'sum_total'=>false],

			['layout' => 2, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Tanggal', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.LHP', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'No.WO', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Memo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Kode Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Nama Barang', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Qty LHP (Dasar)', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Unit', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Qty LHP', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Unit', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 2, 'label'=>'Sisa Target', 'value'=>'11', 'selected'=>false,'sum_total'=>false],

			['layout' => 3, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Tanggal', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.LHP', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'No.WO', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Memo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Kode Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Nama Barang', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty LHP (Dasar)', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Qty LHP', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Unit', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 3, 'label'=>'Sisa Target', 'value'=>'11', 'selected'=>false,'sum_total'=>false],

			
			['layout' => 4, 'label'=>'No', 'value'=>'0', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Tanggal', 'value'=>'1', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.LHP', 'value'=>'2', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'No.WO', 'value'=>'3', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Memo', 'value'=>'4', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Kode Barang', 'value'=>'5', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Nama Barang', 'value'=>'6', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Qty LHP (Dasar)', 'value'=>'7', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Unit', 'value'=>'8', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Qty LHP', 'value'=>'9', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Unit', 'value'=>'10', 'selected'=>false,'sum_total'=>false],
			['layout' => 4, 'label'=>'Sisa Target', 'value'=>'11', 'selected'=>false,'sum_total'=>false],
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
		

		$side_filter = $this->parser->parse('reports/production/lhp/form',$this->data, true);
		$this->data['REPORT_FILTER'] = $side_filter;
		$this->data['TITLE'] = "LAPORAN HASIL PRODUKSI";
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
		$this->load->model('trlhp_rpt_model');
		$this->form_validation->set_rules($this->trlhp_rpt_model->getRules());
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
			"fin_wo_id" => $this->input->post("fin_wo_id"),
			"fin_wo_id2" => $this->input->post("fin_wo_id2"),
			"fdt_datetime" => $this->input->post("fdt_datetime"),
			"fdt_datetime2" => $this->input->post("fdt_datetime2"),
			"rpt_layout" => $this->input->post("rpt_layout"),
			"selected_columns" => array($this->input->post("selected_columns"))
		];

				
		$dataReport = $this->trlhp_rpt_model->queryComplete($data,"a.fin_wo_id",$data['rpt_layout']);

		$selectedCols =$this->input->post("selected_columns");
		if ($dataReport==[]) {
			echo "Data Not Found !";
			return;
		}else{
			$totalColumn = sizeof($data["selected_columns"]);			
		}

		if ($data['rpt_layout'] == 1){
			$this->parser->parse('reports/production/lhp/layout1', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 2){
			$this->parser->parse('reports/production/lhp/layout2', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 3){
			$this->parser->parse('reports/production/lhp/layout3', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
		}else if($data['rpt_layout'] == 4){
            $this->parser->parse('reports/production/lhp/layout4', ["selectedCols"=>$selectedCols,"ttlCol"=>$totalColumn,"dataReport"=>$dataReport]);
        }
		
		//echo "<div id='tstdiv'>Show Report</div>";//
    }
    
    public function ajxGetWOList(){
        $term = $this->input->get("term");
        $term = "%$term%";

        $ssql = "SELECT a.fin_wo_id,a.fst_wo_no
            FROM trwo a             
            WHERE a.fst_active ='A' and a.fst_wo_no like ?";
        $qr = $this->db->query($ssql,[$term]);

        $rs = $qr->result();
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$rs
        ]);
	}

}

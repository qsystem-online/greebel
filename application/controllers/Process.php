<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('dashboard_model');
	}

	public function ajxDoClosing(){
		session_write_close();
		ini_set('max_execution_time',0);
		$period = "2020-12";
		$firstDate = $period ."-01";
		$lastDate = getPeriodDate($period);


		//bahan baku fin_item_type_id 1,2,3				
		try{		
			$ssql = "SELECT * FROM msprofitcostcenter where fst_active= 'A'";
			$qr = $this->db_>query($ssql,[]);
			$rsPCC =$qr->result();

			$this->db->trans_start();

			//PROCESS COGM = Persedian awal + Pembelian + biaya pembelian + biaya produksi - Persediaan akhir
			//COGM Di hitung dari item bahan baku			
			foreach($rsPCC as $pcc){
				//COGM Persediaan awal
				$ssql = "SELECT c.fin_pcc_id,
					SUM(fdb_qty_balance_after) as fdb_qty,
					SUM((fdb_qty_balance_after* fdc_avg_cost))/SUM(fdb_qty_balance_after) AS hpp
					FROM trinventory a
					INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id
					INNER JOIN msgroupitems c ON b.fin_item_group_id = c.fin_item_group_id 
					WHERE fin_rec_id IN (
						SELECT MAX(fin_rec_id) FROM trinventory WHERE fdt_trx_datetime < ? GROUP BY fin_item_id,fin_warehouse_id
					)
					AND c.fin_pcc_id = ? and b.fin_item_type_id in (1,2,3)
				GROUP BY c.fin_pcc_id";
				$qr = $this->db->query($ssql,[$firstDate,$pcc->fin_pcc_id]);
				$rw = $qr->row();
				$cogmPersediaanAwal = $rw->fdb_qty * $rw->hpp;								
				
				//COGM PEMBELIAN LPB & Biaya
				$ssql = "SELECT sum(fdb_qty_in * fdc_price_in) as ttl_pembelian,sum(fdc_add_cost) as ttl_biaya FROM trinventory a 
					INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
					INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
					where c.fin_pcc_id = ? and a.fdt_trx_datetime >= ? and a.fdt_trx_datetime < ? and b.fin_item_type_id in (1,2,3)
					and a.fst_active ='A' ";
				$qr = $this->db->query($ssql,[$pcc->fin_pcc_id,$firstDate,$lastDate]);
				$rw = $qr->row();
				$cogmTotalPembelian = 0;
				$cogmTotalBiayaPembelian = 0;
				if ($rw != null){
					$cogmTotalPembelian = $rw->ttl_pembelian;
					$cogmTotalBiayaPembelian = $rw->ttl_biaya;
				}

				//COGM Biaya Produksi
				$cogmTotalBiayaProduksi =0;
				//trlhpactivities

				//COGM Persediaan akhir
				$ssql = "SELECT c.fin_pcc_id,
					SUM(fdb_qty_balance_after) as fdb_qty,
					SUM((fdb_qty_balance_after* fdc_avg_cost))/SUM(fdb_qty_balance_after) AS hpp
					FROM trinventory a
					INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id
					INNER JOIN msgroupitems c ON b.fin_item_group_id = c.fin_item_group_id 
					WHERE fin_rec_id IN (
						SELECT MAX(fin_rec_id) FROM trinventory WHERE fdt_trx_datetime < ? GROUP BY fin_item_id,fin_warehouse_id
					)
					AND c.fin_pcc_id = ? and b.fin_item_type_id in (1,2,3)
				GROUP BY c.fin_pcc_id";
				$qr = $this->db->query($ssql,[$lastDate,$pcc->fin_pcc_id]);
				$rw = $qr->row();
				$cogmPersediaanAkhir = $rw->fdb_qty * $rw->hpp;		

				$cogmTotal = $cogmPersediaanAwal + $cogmTotalPembelian + $cogmTotalBiayaPembelian +$cogmtotalBiayaProduksi - $cogmPersediaanAkhir;



				//cogs = Persedian awal + Pembelian + biaya pembelian + hpp produksi(cogm) - Persediaan akhir
				//COGS Persediaan awal
				$ssql = "SELECT c.fin_pcc_id,
					SUM(fdb_qty_balance_after) as fdb_qty,
					SUM((fdb_qty_balance_after* fdc_avg_cost))/SUM(fdb_qty_balance_after) AS hpp
					FROM trinventory a
					INNER JOIN msitems b ON a.fin_item_id = b.fin_item_id
					INNER JOIN msgroupitems c ON b.fin_item_group_id = c.fin_item_group_id 
					WHERE fin_rec_id IN (
						SELECT MAX(fin_rec_id) FROM trinventory WHERE fdt_trx_datetime < ? GROUP BY fin_item_id,fin_warehouse_id
					)
					AND c.fin_pcc_id = ? and b.fin_item_type_id in (4)
				GROUP BY c.fin_pcc_id";
				$qr = $this->db->query($ssql,[$firstDate,$pcc->fin_pcc_id]);
				$rw = $qr->row();
				$cogsPersediaanAwal = $rw->fdb_qty * $rw->hpp;	


				//COGS PEMBELIAN  & Biaya
				$ssql = "SELECT sum(fdb_qty_in * fdc_price_in) as ttl_pembelian,sum(fdc_add_cost) as ttl_biaya FROM trinventory a 
					INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
					INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
					where c.fin_pcc_id = ? and a.fdt_trx_datetime >= ? and a.fdt_trx_datetime < ? and b.fin_item_type_id in (4)
					and a.fst_active ='A' ";
				$qr = $this->db->query($ssql,[$pcc->fin_pcc_id,$firstDate,$lastDate]);
				$rw = $qr->row();
				$cogmTotalPembelian = 0;
				$cogmTotalBiayaPembelian = 0;
				if ($rw != null){
					$cogmTotalPembelian = $rw->ttl_pembelian;
					$cogmTotalBiayaPembelian = $rw->ttl_biaya;
				}

				
				$ssql = "INSERT INTO trmonthlyclosing(
					fst_periode,
					fin_pcc_id,
					fdc_cogm_persediaan_awal,
					fdc_cogm_pembelian,
					fdc_cogm_biaya_pembelian,
					fdc_cogm_biaya_produksi,
					fdc_cogm_persediaan_akhir,
					fdc_cogm,
					fdc_cogs_persediaan_awal,
					
				) values(
					?,?,?,?,?,?,?,?,?
				)";
				
				$this->db->query($ssql,[
					$period,
					$pcc->fin_pcc_id,
					$cogmPersediaanAwal,
					$cogmTotalPembelian,
					$cogmTotalBiayaPembelian,
					$cogmTotalBiayaProduksi,
					$cogmPersediaanAkhir,
					$cogmTotal,
					$cogsPersediaanAwal					
				]);				
			
			
				
			}
					

			


			$this->db->trans_complete();
		}catch(Customexception $e){
			$this->db->trans_rollback();
		}

		for($i=0 ;$i < 15;$i++){
			sleep(1);
		}
		echo "Done...!";
	}

	public function ajxGetUpdateClosing($cekId){
		//session_write_close();
		$this->json_output([
			"status"=>"SUCCESS",
			"messages"=>"",
			"data"=>date("Y-m-d H:i:s")
		]);
	}

	public function form_closing(){
		$this->load->library("menus");		
		//$this->load->model("glaccounts_model");		
		$this->load->model("users_model");

		$main_header = $this->parser->parse('inc/main_header', [], true);
		$main_sidebar = $this->parser->parse('inc/main_sidebar', [], true);
		
		$data["title"] = lang("Closing Period");
		
		$page_content = $this->parser->parse('pages/process/closing/form', $data, true);
		$main_footer = $this->parser->parse('inc/main_footer', [], true);

		$control_sidebar = NULL;
		$this->data["MAIN_HEADER"] = $main_header;
		$this->data["MAIN_SIDEBAR"] = $main_sidebar;
		$this->data["PAGE_CONTENT"] = $page_content;
		$this->data["MAIN_FOOTER"] = $main_footer;
		$this->data["CONTROL_SIDEBAR"] = $control_sidebar;
		$this->parser->parse('template/main', $this->data);
	}

}
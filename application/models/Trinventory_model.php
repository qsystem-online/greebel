<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Trinventory_model extends MY_Model
{
	public $tableName = "trinventory";
	public $pkey = "fin_rec_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getDataById($fst_glaccount_code)
	{
	   
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];
		return $rules;
	}

	/*
		stock:
		marketing stock:
	*/
	public function getStock($fin_item_id,$fst_unit,$fin_warehouse_id){
		//$ssql = "select sum(fdb_qty_in) as ttl_qty_in,sum(fdb_qty_out) as ttl_qty_out from ". $this->tableName . " where fin_warehouse_id = ? and fin_item_id = ? and fst_unit = ? and fst_active = 'A'";
		$ssql = "select * from msitems where fin_item_id = ?";
		$qr = $this->db->query($ssql,[$fin_item_id]);
		$rw = $qr->row();
		if($rw == null){
			return 0;
		}
		if ($rw->fbl_stock == false){
			return 999999999;
		}

		$ssql ="select * from trinventory a 
			where fin_warehouse_id =? 
			and fin_item_id = ?
			and fst_basic_unit = ? 
			order by fdt_trx_datetime desc , fin_rec_id desc limit 1";
		$qr = $this->db->query($ssql,[$fin_warehouse_id,$fin_item_id,$fst_unit]);
		$rw = $qr->row();
		if ($rw == null){
			return 0;
		}else{
			//return (int) $rw->ttl_qty_in - (int) $rw->ttl_qty_out;
			return (float) $rw->fdb_qty_balance_after;
		}
		
	}

	public function getMarketingStock($fin_item_id,$fst_unit,$fin_warehouse_id){
		$qtyStock = $this->getStock($fin_item_id,$fst_unit,$fin_warehouse_id);
		//Get Qty SO yang masih belum terpenuhi
		$qtyUnprocessSO = 0;
		$ssql = "SELECT IFNULL(sum(fdb_qty - fdb_qty_out),0) AS ttl_qty_unprocess_so FROM trsalesorderdetails a 
			INNER JOIN trsalesorder b ON a.fin_salesorder_id = b.fin_salesorder_id 
			WHERE a.fin_item_id = ? AND b.fbl_is_closed = 0 AND b.fst_active ='A'";
		$qr = $this->db->query($ssql,[$fin_item_id]);
		$rw = $qr->row();
		$qtyUnprocessSO = $rw->ttl_qty_unprocess_so;

		//Get Qty PO yang belum diterima
		$qtyUnprocessPO = 0;
		$marketingStockIncPO = getDbConfig("marketing_stock_inc_po");
		
		if ($marketingStockIncPO != 0){
			$ssql = "SELECT IFNULL(sum(fdb_qty - fdb_qty_lpb),0) AS ttl_qty_unprocess_po FROM trpodetails a 
				INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
				WHERE a.fin_item_id = ? AND b.fbl_is_closed = 0 AND b.fst_active = 'A'";

			$qr = $this->db->query($ssql,[$fin_item_id]);
			$rw = $qr->row();
			if($rw == null){
				$qtyUnprocessPO = 0;
			}else{
				$qtyUnprocessPO = $rw->ttl_qty_unprocess_po;
			}
		}
		return $qtyStock - $qtyUnprocessSO + $qtyUnprocessPO;
	}

	public function insert($data){
		$this->load->model("msitems_model");
		
		$rw = $this->msitems_model->geSimpletDataById($data["fin_item_id"]);
		if ($rw == null){
			return ["status"=>"FAILED","message"=>lang("Item tidak ditemukan !")];
		}
		$itemName = $rw->fst_item_name;
		if ($rw->fbl_stock == false){
			return ["status"=>"SUCCESS","message"=>""];
		}


	   
		$basicUnit = $this->msitems_model->getBasicUnit($data["fin_item_id"]);
		if ($basicUnit == null){
			throw new CustomException(sprintf(lang("Item %s tidak memiliki basic unit"),$itemName),3003,"FAILED",[]);
		}

		$convToBasicUnit = $this->msitems_model->getConversionUnit($data["fin_item_id"],$data["fst_unit"],$basicUnit);
		$pricePerBasicUnit = floatval($data["fdc_price_in"]) / $convToBasicUnit;
		$addCost = 0;
		$qtyInBasicUnit = $this->msitems_model->getQtyConvertToBasicUnit($data["fin_item_id"],$data["fdb_qty_in"],$data["fst_unit"]);
		$qtyOutBasicUnit = $this->msitems_model->getQtyConvertToBasicUnit($data["fin_item_id"],$data["fdb_qty_out"],$data["fst_unit"]);
		$addCost = isset($data["fdc_add_cost"]) ? (float) $data["fdc_add_cost"]: 0;
		
		$data["fst_basic_unit"]= $basicUnit;
		$data["fdb_qty_in"]= $qtyInBasicUnit;
		$data["fdb_qty_out"]= $qtyOutBasicUnit;
		$data["fdc_price_in"]= $pricePerBasicUnit;
		$data["fdc_add_cost"] =$addCost;                        


		//get last record
		$ssql ="select * from trinventory 
		where fin_warehouse_id = ?
		and fin_item_id = ?
		and fst_basic_unit = ?        
		and fdt_trx_datetime < ? order by fdt_trx_datetime desc , fin_rec_id desc limit 1";

		$qr = $this->db->query($ssql,[$data["fin_warehouse_id"],$data["fin_item_id"],$basicUnit,$data["fdt_trx_datetime"]]);
		$rwPrev = $qr->row();
		$qtyBalanceBefore = 0;
		$avgCostBefore = 0;
		if ($rwPrev != null){
			$qtyBalanceBefore = (float) $rwPrev->fdb_qty_balance_after;
			$avgCostBefore = (float) $rwPrev->fdc_avg_cost;
		}

		$data["fdb_qty_balance_after"] = $qtyBalanceBefore  + $data["fdb_qty_in"] - $data["fdb_qty_out"];
		if ($data["fdb_qty_balance_after"] < 0){
			throw new CustomException(sprintf(lang("Stock %s tidak boleh kurang dari nol"),$itemName),3003,"FAILED");            
		}

		$data["fdc_avg_cost"] = $this->getHPP(
			$qtyBalanceBefore,
			$avgCostBefore,
			$data["fdb_qty_in"],
			$data["fdb_qty_out"],
			$data["fdc_price_in"],
			$data["fdc_add_cost"]
		);

		parent::insert($data);
		$rwPrev = $data;


		$ssql ="select * from trinventory 
		where fin_warehouse_id = ?
		and fin_item_id = ?
		and fdt_trx_datetime > ? order by fdt_trx_datetime ,fin_rec_id";

		$qr = $this->db->query($ssql,[$data["fin_warehouse_id"],$data["fin_item_id"],$data["fdt_trx_datetime"]]);
		$rs = $qr->result_array();        
		foreach($rs as $data){
			$data["fdb_qty_balance_after"] = $rwPrev["fdb_qty_balance_after"] + $data["fdb_qty_in"] -  $data["fdb_qty_out"];
			$data["fdc_avg_cost"] = $this->getHPP(
				$rwPrev["fdb_qty_balance_after"] ,
				$rwPrev["fdc_avg_cost"], 
				$data["fdb_qty_in"],
				$data["fdb_qty_out"],
				$data["fdc_price_in"],
				$data["fdc_add_cost"]
			);            
			unset($data["fdt_update_datetime"]);
			unset($data["fin_update_id"]);

			if ($data["fdb_qty_balance_after"] < 0){
				throw new CustomException(sprintf(lang("Stock %s tidak boleh kurang dari nol"),$itemName),3003,"FAILED");            
			}

			parent::update($data);
			$rwPrev = $data;
		}


	   
	}

	public function deleteByCodeId($trxCode,$trxId){
		$ssql = "select a.*,b.fst_item_name  from trinventory a 
			inner join msitems b on a.fin_item_id = b.fin_item_id 
			where a.fst_trx_code = ? and a.fin_trx_id =? and a.fst_active ='A' 
			order by a.fdt_trx_datetime,a.fin_rec_id";

		$qr = $this->db->query($ssql,[$trxCode,$trxId]);
		$rs = $qr->result();        
		foreach($rs as $rw){
			//delete row
			$ssql ="delete from trinventory where fin_rec_id = ?";
			$this->db->query($ssql,[$rw->fin_rec_id]);


			//Get Last record
			$ssql ="select * from trinventory
				where fin_warehouse_id = ?
				and fin_item_id = ?
				and fdt_trx_datetime <= ? order by fdt_trx_datetime desc , fin_rec_id desc limit 1";
			$qr = $this->db->query($ssql,[$rw->fin_warehouse_id,$rw->fin_item_id,$rw->fdt_trx_datetime]);
			$rwPrev = $qr->row_array();

			if ($rwPrev == null){
				$rwPrev = [
					"fdb_qty_balance_after"=>0,
					"fdc_avg_cost"=>0
				];
			}
			
			//Process maju
			$ssql ="select * from trinventory
				where fin_warehouse_id = ?
				and fin_item_id = ?
				and fdt_trx_datetime >= ? order by fdt_trx_datetime,fin_rec_id";
			$qr = $this->db->query($ssql,[$rw->fin_warehouse_id,$rw->fin_item_id,$rw->fdt_trx_datetime]);
			$rs = $qr->result();               
			foreach($rs as $data){
				$data->fdb_qty_balance_after = $rwPrev["fdb_qty_balance_after"] + (float) $data->fdb_qty_in -  (float) $data->fdb_qty_out;                
				if ($data->fdb_qty_balance_after < 0){
					throw new CustomException(sprintf(lang("Stock %s tidak boleh kurang dari nol"),$rw->fst_item_name),3003,"FAILED");            
				}
				
				$data->fdc_avg_cost = $this->getHPP(
					$rwPrev["fdb_qty_balance_after"],
					$rwPrev["fdc_avg_cost"],
					$data->fdb_qty_in,
					$data->fdb_qty_out,
					$data->fdc_price_in,
					$data->fdc_add_cost
				);                
				unset($data->fin_update_id);
				unset($data->fdt_update_datetime);
				parent::update((array) $data);
				$rwPrev = (array) $data;
			}


		}                 
	}

	public function updateById($finRecId,$fstUnit=null,$fdbQtyIn=null,$fdbQtyOut=null,$fdcPriceIn = null ,$fdcAddCost = null){
		$this->load->model("msitems_model");

			
		//Get Record        
		$ssql = "select a.*,b.fst_item_name from trinventory a 
			inner join msitems b on a.fin_item_id = b.fin_item_id 
			where a.fin_rec_id = ?";

		$qr = $this->db->query($ssql,[$finRecId]);
		$dataH=$qr->row_array();

		$fstUnit = $fstUnit == null ? $dataH["fst_basic_unit"] :$fstUnit;
		$fdbQtyIn = $fdbQtyIn == null ? $dataH["fdb_qty_in"] : $fdbQtyIn;
		$fdbQtyOut= $fdbQtyOut == null ? $dataH["fdb_qty_out"] : $fdbQtyOut;
		$fdcPriceIn = $fdcPriceIn == null ? $dataH["fdc_price_in"] : $fdcPriceIn;
		$fdcAddCost = $fdcAddCost == null ? $dataH["fdc_add_cost"] : $fdcAddCost;



		$convToBasicUnit = $this->msitems_model->getConversionUnit($dataH["fin_item_id"],$fstUnit,$dataH["fst_basic_unit"]);
		$pricePerBasicUnit = floatval($fdcPriceIn) / $convToBasicUnit;        
		$dataH["fdb_qty_in"] = $this->msitems_model->getQtyConvertToBasicUnit($dataH["fin_item_id"],$fdbQtyIn,$fstUnit);
		$dataH["fdb_qty_out"] = $this->msitems_model->getQtyConvertToBasicUnit($dataH["fin_item_id"],$fdbQtyOut,$fstUnit);
		$dataH["fdc_price_in"] = $pricePerBasicUnit;
		$dataH["fdc_add_cost"] = $fdcAddCost;
		$itemName = $dataH["fst_item_name"];


		//Prev Rec by date
		$ssql = "select * from trinventory 
		where  fin_warehouse_id = ?
		AND fdt_trx_datetime < ? 
		AND fin_item_id = ?
		AND fst_basic_unit = ? order by fdt_trx_datetime desc limit 1";

		$qr = $this->db->query($ssql,[
				$dataH["fin_warehouse_id"],
				$dataH["fdt_trx_datetime"],
				$dataH["fin_item_id"],
				$dataH["fst_basic_unit"],
			]);
		$rwPrev = $qr->row_array();

		$dataH["fdb_qty_balance_after"] = $rwPrev["fdb_qty_balance_after"] + $dataH["fdb_qty_in"] - $dataH["fdb_qty_out"];        
		$dataH["fdc_avg_cost"] = $this->getHPP(
			$rwPrev["fdb_qty_balance_after"],
			$rwPrev["fdc_avg_cost"],
			$dataH["fdb_qty_in"],
			$dataH["fdb_qty_out"],
			$dataH["fdc_price_in"],
			$dataH["fdc_add_cost"]
		);
		unset($dataH["fin_update_id"]);
		unset($dataH["fdt_update_datetime"]);

		if ($dataH["fdb_qty_balance_after"] < 0){
			throw new CustomException(sprintf(lang("Stock %s tidak boleh kurang dari nol"),$itemName),3003,"FAILED");            
		}

		parent::update($dataH);
		$rwPrev = $dataH;


		//Update Maju
		$ssql = "select * from trinventory 
		where  fin_warehouse_id = ?
		AND fdt_trx_datetime > ? 
		AND fin_item_id = ?
		AND fst_basic_unit = ? order by fdt_trx_datetime asc";
		$qr = $this->db->query($ssql,[
			$dataH["fin_warehouse_id"],
			$dataH["fdt_trx_datetime"],
			$dataH["fin_item_id"],
			$dataH["fst_basic_unit"],
		]);

		
		$rs = $qr->result_array();
		foreach($rs as $dataH){
			$dataH["fdb_qty_balance_after"] = $rwPrev["fdb_qty_balance_after"] + $dataH["fdb_qty_in"] - $dataH["fdb_qty_out"];        
			$dataH["fdc_avg_cost"] = $this->getHPP(
				$rwPrev["fdb_qty_balance_after"],
				$rwPrev["fdc_avg_cost"],
				$dataH["fdb_qty_in"],
				$dataH["fdb_qty_out"],
				$dataH["fdc_price_in"],
				$dataH["fdc_add_cost"]
			);
			unset($dataH["fin_update_id"]);
			unset($dataH["fdt_update_datetime"]); 
			if ($dataH["fdb_qty_balance_after"] < 0){
				throw new CustomException(sprintf(lang("Stock %s tidak boleh kurang dari nol"),$itemName),3003,"FAILED");            
			}       
			parent::update($dataH);
			$rwPrev = $dataH;
		}            
	}

	public function recalculate($finWarehouseId, $finItemId , $fdtTransactionDatetime){
		
		//Prev Rec by date
		$ssql = "select * from trinventory 
		where  fin_warehouse_id = ?
		AND fdt_trx_datetime < ? 
		AND fin_item_id = ?
		ORDER BY fdt_trx_datetime DESC LIMIT 1";

		$qr = $this->db->query($ssql,[
			$finWarehouseId,
			$fdtTransactionDatetime,
			$finItemId
		]);

		$rwPrev = $qr->row_array();

		//Update Maju
		$ssql = "select * from trinventory 
		where  fin_warehouse_id = ?
		AND fdt_trx_datetime > ? 
		AND fin_item_id = ?
		ORDER BY fdt_trx_datetime ASC";
		$qr = $this->db->query($ssql,[
			$finWarehouseId,
			$fdtTransactionDatetime,
			$finItemId
		]);
		
		$rs = $qr->result_array();
		foreach($rs as $dataH){
			$dataH["fdb_qty_balance_after"] = $rwPrev["fdb_qty_balance_after"] + $dataH["fdb_qty_in"] - $dataH["fdb_qty_out"];        
			$dataH["fdc_avg_cost"] = $this->getHPP(
				$rwPrev["fdb_qty_balance_after"],
				$rwPrev["fdc_avg_cost"],
				$dataH["fdb_qty_in"],
				$dataH["fdb_qty_out"],
				$dataH["fdc_price_in"],
				$dataH["fdc_add_cost"]
			);
			unset($dataH["fin_update_id"]);
			unset($dataH["fdt_update_datetime"]); 
			if ($dataH["fdb_qty_balance_after"] < 0){
				throw new CustomException(sprintf(lang("Stock %s tidak boleh kurang dari nol"),$itemName),3003,"FAILED");            
			}       
			parent::update($dataH);
			$rwPrev = $dataH;
		}
		   
	}

	public function getHPP($lastBalanceQty,$lastHPP,$qtyIn,$qtyOut,$fdcPrice,$fdcAddCost){
		
		$pricePlusCost = (float) $fdcPrice + (float) $fdcAddCost;

		if ($pricePlusCost == 0){
			return (float) $lastHPP;
		}

		$ttlPriceIn = (float) $qtyIn * $pricePlusCost;
		$ttlPriceOut = (float) $qtyOut * $pricePlusCost;
		$ttlPriceBefore = ((float) $lastBalanceQty * (float) $lastHPP );
		$newBalanceQty = (float) $lastBalanceQty + (float) $qtyIn - (float) $qtyOut;     
		if ($newBalanceQty <= 0){
			$newAvgCost = $lastHPP;
		}else{
			$newAvgCost = ( $ttlPriceIn   -  $ttlPriceOut + $ttlPriceBefore  ) / $newBalanceQty;
		}
		

		return $newAvgCost;

	}

	public function getSummarySerialNo($finWarehouseId,$finItemId,$arrSerialNo){
		$ssql = "Select fst_serial_no,fdb_qty_in,fdb_qty_out from msitemdetailssummary where fin_warehouse_id =? and fin_item_id = ? and fst_serial_no in ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWarehouseId,$finItemId,$arrSerialNo]);
		$rs = $qr->result();
		$result = [];
		foreach($rs as $rw){
			$result[$rw->fst_serial_no] = [
				"fdb_qty_in" => $rw->fdb_qty_in,
				"fdb_qty_out" => $rw->fdb_qty_out,
			];
		};
		return $result;
	}

	
	public function insertSerial($dataSerial){
		/**
		 *"fin_warehouse_id,fin_item_id,fst_unit,fst_serial_number_list,fst_batch_no,fst_trans_type,fin_trans_id,fst_trans_no,fin_trans_detail_id,fdb_qtyin_out"
		 */
		$this->load->model("msitems_model");


		$dataSerial["fst_basic_unit"] = $this->msitems_model->getBasicUnit($dataSerial["fin_item_id"]);
		$dataSerial["fst_active"] = "A";
		$dataSerial["fin_insert_id"] = $this->aauth->get_user_id();
		$dataSerial["fdt_insert_datetime"] = date("Y-m-d H:i:s");

		$strArrSerial = $dataSerial["fst_serial_number_list"];
		$arrSerial = json_decode($strArrSerial);
		
		
		if (is_array($arrSerial) && sizeof($arrSerial) > 0){
			unset($dataSerial["fst_unit"]);
			unset($dataSerial["fdb_qty"]);
			foreach($arrSerial as $serial){
				if($dataSerial["in_out"] == "IN"){
					$dataSerial["fdb_qty_in"] = 1;
					$dataSerial["fdb_qty_out"] = 0;
				}else{
					$dataSerial["fdb_qty_in"] = 0;
					$dataSerial["fdb_qty_out"] = 1;
					$isAvailable = $this->isBatchSerialAvailable(
						$dataSerial["fin_warehouse_id"],
						$dataSerial["fin_item_id"],
						$dataSerial["fst_batch_no"],
						$serial,
						$dataSerial["fdb_qty_out"],
						$dataSerial["fst_basic_unit"]
					);
					if ($isAvailable == false){
						throw new CustomException(sprintf(lang("serial %s:%s tidak tersedia / qty tidak mencukupi"),$dataSerial["fst_batch_no"],$serial),3003,"FAILED",null);
					}
				}

				$data = [
					//"fin_rec_id`, 
					"fin_warehouse_id"=>$dataSerial["fin_warehouse_id"],
					"fin_item_id"=>$dataSerial["fin_item_id"],
					"fst_basic_unit"=>$dataSerial["fst_basic_unit"],
					"fst_serial_no"=>$serial,
					"fst_batch_no"=>$dataSerial["fst_batch_no"],
					"fst_trans_type"=>$dataSerial["fst_trans_type"],
					"fin_trans_id"=>$dataSerial["fin_trans_id"],
					"fst_trans_no"=>$dataSerial["fst_trans_no"],
					"fin_trans_detail_id"=>$dataSerial["fin_trans_detail_id"],
					"fdb_qty_in"=>$dataSerial["fdb_qty_in"],
					"fdb_qty_out"=>$dataSerial["fdb_qty_out"],
					"fst_active"=>"A",
					"fin_insert_id"=>$this->aauth->get_user_id(),
					"fdt_insert_datetime"=>date("Y-m-d H:i:s")
				];
				$this->db->insert("msitemdetails",$data);                
				throwIfDBError(); 
				
				//Update Summary
				$ssql = "Select * from msitemdetailssummary where fin_warehouse_id = ? and fin_item_id = ? and fst_batch_no = ? and fst_serial_no = ? and fst_active ='A'";
				$qr = $this->db->query($ssql,[$dataSerial["fin_warehouse_id"],$dataSerial["fin_item_id"],$dataSerial["fst_batch_no"],$serial]);
				$rw = $qr->row();
				if ($rw == null){
					$dataSumm=[
						"fin_warehouse_id"=>$dataSerial["fin_warehouse_id"],
						"fin_item_id"=>$dataSerial["fin_item_id"],
						"fst_basic_unit"=>$dataSerial["fst_basic_unit"],
						"fst_batch_no"=>$dataSerial["fst_batch_no"],
						"fst_serial_no"=>$serial,
						"fdb_qty_in"=>$dataSerial["fdb_qty_in"],
						"fdb_qty_out"=>$dataSerial["fdb_qty_out"],
						"fst_active"=>"A",
						"fin_insert_id"=>$this->aauth->get_user_id(),
						"fdt_insert_datetime"=>date("Y-m-d H:i:s")
					];
					$this->db->insert("msitemdetailssummary",$dataSumm);
					throwIfDBError();
				}else{
					$dataSumm=[
						"fdb_qty_in"=> $rw->fdb_qty_in  + $dataSerial["fdb_qty_in"],
						"fdb_qty_out"=>$rw->fdb_qty_out  + $dataSerial["fdb_qty_out"],
						"fin_update_id"=>$this->aauth->get_user_id(),
						"fdt_update_datetime"=>date("Y-m-d H:i:s")
					];

					$this->db->where([
						"fin_warehouse_id"=>$dataSerial["fin_warehouse_id"],
						"fin_item_id"=>$dataSerial["fin_item_id"],
						"fst_batch_no"=>$dataSerial["fst_batch_no"],
						"fst_serial_no"=>$serial,
					]);
					$this->db->update("msitemdetailssummary",$dataSumm);
					throwIfDBError();
				}


			}                
		}else{
			if ($dataSerial["fst_batch_no"] != null && $dataSerial["fst_batch_no"] != "" ){
				$qtyInBasicUnit = $this->msitems_model->getQtyConvertToBasicUnit($dataSerial["fin_item_id"],$dataSerial["fdb_qty"],$dataSerial["fst_unit"]);
				unset($dataSerial["fdb_qty"]);
				unset($dataSerial["fst_unit"]);                
				if($dataSerial["in_out"] == "IN"){
					$dataSerial["fdb_qty_in"] = $qtyInBasicUnit;
					$dataSerial["fdb_qty_out"] = 0;
				}else{
					$dataSerial["fdb_qty_in"] = 0;
					$dataSerial["fdb_qty_out"] = $qtyInBasicUnit;
					$isAvailable = $this->isBatchSerialAvailable(
						$dataSerial["fin_warehouse_id"],
						$dataSerial["fin_item_id"],
						$dataSerial["fst_batch_no"],
						null,
						$dataSerial["fdb_qty_out"],
						$dataSerial["fst_basic_unit"]
					);
					if ($isAvailable == false){
						throw new CustomException(sprintf(lang("Batch %s:%s tidak tersedia / qty tidak mencukupi"),$dataSerial["fst_batch_no"],null),3003,"FAILED",null);
					}
				}

				$data = [
					"fin_warehouse_id"=>$dataSerial["fin_warehouse_id"],
					"fin_item_id"=>$dataSerial["fin_item_id"],
					"fst_basic_unit"=>$dataSerial["fst_basic_unit"],
					"fst_serial_no"=>null,
					"fst_batch_no"=>$dataSerial["fst_batch_no"],
					"fst_trans_type"=>$dataSerial["fst_trans_type"],
					"fin_trans_id"=>$dataSerial["fin_trans_id"],
					"fst_trans_no"=>$dataSerial["fst_trans_no"],
					"fin_trans_detail_id"=>$dataSerial["fin_trans_detail_id"],
					"fdb_qty_in"=>$dataSerial["fdb_qty_in"],
					"fdb_qty_out"=>$dataSerial["fdb_qty_out"],
					"fst_active"=>"A",
					"fin_insert_id"=>$this->aauth->get_user_id(),
					"fdt_insert_datetime"=>date("Y-m-d H:i:s")
				];
				$this->db->insert("msitemdetails",$data);                
				throwIfDBError(); 

				//Update Summary
				$ssql = "Select * from msitemdetailssummary where fin_warehouse_id = ? and fin_item_id = ? and fst_batch_no = ? and fst_active ='A'";
				$qr = $this->db->query($ssql,[$dataSerial["fin_warehouse_id"],$dataSerial["fin_item_id"],$dataSerial["fst_batch_no"]]);
				$rw = $qr->row();
				if ($rw == null){
					$dataSumm=[
						"fin_warehouse_id"=>$dataSerial["fin_warehouse_id"],
						"fin_item_id"=>$dataSerial["fin_item_id"],
						"fst_basic_unit"=>$dataSerial["fst_basic_unit"],
						"fst_batch_no"=>$dataSerial["fst_batch_no"],
						"fst_serial_no"=>null,
						"fdb_qty_in"=>$dataSerial["fdb_qty_in"],
						"fdb_qty_out"=>$dataSerial["fdb_qty_out"],
						"fst_active"=>$dataSerial["fst_active"],
						"fin_insert_id"=>$this->aauth->get_user_id(),
						"fdt_insert_datetime"=>date("Y-m-d H:i:s")
					];
					$this->db->insert("msitemdetailssummary",$dataSumm);
					throwIfDBError();
				   
				}else{
					$dataSumm=[
						"fdb_qty_in"=> $rw->fdb_qty_in  + $dataSerial["fdb_qty_in"],
						"fdb_qty_out"=>$rw->fdb_qty_out  + $dataSerial["fdb_qty_out"],
						"fin_update_id"=>$this->aauth->get_user_id(),
						"fdt_update_datetime"=>date("Y-m-d H:i:s")
					];

					$this->db->where([
						"fin_warehouse_id"=>$dataSerial["fin_warehouse_id"],
						"fin_item_id"=>$dataSerial["fin_item_id"],
						"fst_batch_no"=>$dataSerial["fst_batch_no"],
						"fst_serial_no"=>null
					]);
					$this->db->update("msitemdetailssummary",$dataSumm);
					throwIfDBError();
				}
			}
		}
	}

	public function isBatchSerialAvailable($finWarehouseId,$finItemId,$fstBatchNo,$fstSerialNo,$fdbQty,$fstUnit){
		$this->load->model("msitems_model");
		$opBatch = $fstBatchNo == null ? "is" : "=";
		$opSerial = $fstSerialNo == null ? "is" : "=";
		
		$ssql = "Select * from msitemdetailssummary where fin_warehouse_id = ? and fin_item_id = ? and fst_batch_no $opBatch ? and fst_serial_no $opSerial ? ";
		$qr =$this->db->query($ssql,[$finWarehouseId,$finItemId,$fstBatchNo,$fstSerialNo]);        
		$rw = $qr->row();

		if ($rw == null){            
			return false;
		}
		$basicUnit = $this->msitems_model->getBasicUnit($finItemId);
		$qtyBasicUnit = $this->msitems_model->getQtyConvertToBasicUnit($finItemId,$fdbQty,$fstUnit);
		if ($rw->fdb_qty_in - $rw->fdb_qty_out < $qtyBasicUnit){
			return false;
		}else{
			return true;
		}

	}

	public function deleteInsertSerial($transType,$finTransId){
		$ssql ="SELECT * FROM msitemdetails WHERE fst_trans_type =? AND fin_trans_id = ?";
		$qr = $this->db->query($ssql,[$transType,$finTransId]);
		$detailList = $qr->result();
		foreach($detailList as $dataD){
			$opBatch = $dataD->fst_batch_no == null ? "is" : "=";
			$opSerial = $dataD->fst_serial_no == null ? "is" : "=";

			$ssql = "UPDATE msitemdetailssummary SET fdb_qty_in = fdb_qty_in- ? , fdb_qty_out = fdb_qty_out - ? 
				WHERE fin_warehouse_id = ? AND fin_item_id = ? 
				AND fst_batch_no $opBatch ? and fst_serial_no $opSerial ?";
			$this->db->query($ssql,[$dataD->fdb_qty_in,$dataD->fdb_qty_out,$dataD->fin_warehouse_id,$dataD->fin_item_id,$dataD->fst_batch_no,$dataD->fst_serial_no]);            
			throwIfDBError();
		}
		$ssql ="DELETE FROM msitemdetails WHERE fst_trans_type = ? AND fin_trans_id = ?";
		$qr = $this->db->query($ssql,[$transType,$finTransId]);
		throwIfDBError();
	}


	public function getReadyBatchNoList($finWarehouseId,$finItemId){
		$ssql = "select distinct fst_batch_no from msitemdetailssummary where fin_warehouse_id = ? and fin_item_id = ? and fdb_qty_in > fdb_qty_out and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWarehouseId,$finItemId]);
		return $qr->result();
	}

	public function getReadySerialNoList($finWarehouseId,$finItemId,$fstBatchNo){

		$ssql = "select distinct fst_serial_no from msitemdetailssummary where fin_warehouse_id = ? and fin_item_id = ? and fst_batch_no = ?  and fdb_qty_in > fdb_qty_out and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWarehouseId,$finItemId,$fstBatchNo]);
		return $qr->result();
	}

	public function test_exception(){
		throw new CustomException("INI BAGIAN MESSAGE",100,"FAILED",["data1"=>"Ini Data 1","data2"=>"ini datat 2"]);

	}

	public function getLastHPP($finItemId,$finWarehouseId){
		$ssql = "select * from trinventory where fin_item_id = ? and fin_warehouse_id = ? 
			order by fdt_trx_datetime desc , fin_rec_id desc limit 1";
			
		$qr = $this->db->query($ssql,[$finItemId,$finWarehouseId]);
		$rw = $qr->row();
		if($rw == null){
			return 0;
		}else{
			return $rw->fdc_avg_cost;
		}
	}

	public function getListStock($finItemId,$fstUnit,$finBranchId){
		$this->load->model("msitems_model");

		$ssql = "SELECT a.fin_rec_id,a.fin_warehouse_id,b.fst_warehouse_name,a.fin_item_id,a.fst_basic_unit as fst_unit,a.fdb_qty_balance_after FROM trinventory a 
			INNER JOIN 
				(
					SELECT a.fin_warehouse_id,b.fst_warehouse_name,MAX(a.fdt_trx_datetime) AS fdt_trx_datetime 
					FROM trinventory a
					INNER JOIN mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id 
					WHERE a.fin_item_id = ? AND b.fin_branch_id = ? 
					GROUP BY a.fin_warehouse_id,b.fst_warehouse_name
				) b 
				ON a.fin_warehouse_id = b.fin_warehouse_id AND a.fdt_trx_datetime = b.fdt_trx_datetime                
			WHERE a.fin_item_id = ? ";

		$qr = $this->db->query($ssql,[$finItemId,$finBranchId,$finItemId]);
		//var_dump($this->db->error());
		$rs = $qr->result();

		//Convert From basic unit to request unit;
		for($i=0;$i<sizeof($rs);$i++){
			$rw = $rs[$i];
			$rw->fdb_qty_balance_after = $this->msitems_model->getQtyConvertUnit($finItemId,$rw->fdb_qty_balance_after,$rw->fst_unit,$fstUnit);
			$rw->fst_unit = $fstUnit;
			$rs[$i] = $rw;
		}

		return $rs;
		
	}
}

<?php
	//var_dump($header);
	//var_dump($details);
	//die();
?>
<style>
	.first-col{
		width:120px;
	}

	.first-col2{
		width:80px;
	}

	.last-col{
		width:80px;
	}
	.total{
		font-size:9pt;
	}	
</style>

<div class='voucher content'>	
	<div class="row"><h2><?= $title?></h2></div>

	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">No. Penerimaan</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_cbreceive_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col2">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_cbreceive_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Jenis</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_kasbank_name"]?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col2">Mata Uang</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$header["fst_curr_name"]?></div>
		</div>		
	</div>


	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Relasi</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_relation_name"]?></div>
		</div>		
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="notes"><?=$header["fst_memo"] ? "notes : " . $header["fst_memo"] : "" ?></div>
		</div>		
	</div>
	

	<!-- Detail -->
	<div class="row">
		<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
			<thead>
				<tr>
					<th style="width:25%">Type</th>
					<th style="width:55%">No Transaksi</th>
					<th style="width:20%;text-align:right">Nominal</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$total =0;
				?>
				<?php foreach($details as $detail){ ?>	
					<?php
						$total += $detail["fdc_receive_amount"];
						$transType ="";
						switch($detail["fst_trans_type"]){
							case "DP_SO":
								$transType = "DP Penjualan";
								break;
							case "INV_SO":
								$transType = "Faktur Penjualan";
								break;
							
							case "RETURN_SO":
								$transType = "Return Penjualan Non Faktur";
								break;
							case "PAYMENT_OVER":
								$transType = "Kelebihan Bayar";
								break;
							case "CLAIM_PAYMENT_OVER":
								$transType = "Klaim Kelebihan Bayar";
								break;
							case "CLAIM_PAYMENT_UNKNOWN":
								$transType = "Klaim DP tidak dikenal";
								break;
							default:
								return "";
						}

						$trans = $this->trcbreceive_model->getTransactionInfo( $detail["fst_trans_type"],$detail["fin_trans_id"]);
					?>						
					<tr class="<?=$detail["fst_memo"] ? "have-detail" : "" ?>">
						<td><?= $transType ?></td>
						<td><?= $trans["fst_trans_no"] ?> </td>					
						<td class="text-right"><?= formatNumber($detail["fdc_receive_amount"])?> </td>					
					</tr>				
					<tr>
						<td colspan="3"><?=$detail["fst_memo"]?></td>
					</tr>
				<?php }?>
				<tr>
					<td class="total text-right" colspan="2">Total</td>
					<td class="total text-right"><?= formatNumber($total)?></td>
				</tr>
			</tbody>	
		</table>	
	</div>

	<div class="row">
		<h3>Penerimaan :</h3>
		<table class="table  table-condensed" style="width:70%;padding-bottom:0px">
			<?php
				$total=0;
			?>
			<?php foreach($details2 as $detail){ ?>
				<?php
					$receiveType ="";
					$total += $detail["fdc_amount"];
					if ($detail["fst_cbreceive_type"] == "GLACCOUNT"){
						$receiveType = $detail["fst_cbreceive_type"] . " - " . $detail["fst_glaccount_code"];
					}else{
						$receiveType = $detail["fst_cbreceive_type"];
					}
				?>
				<tr>
					<td><?=$detail["fst_cbreceive_type"]?></td>					
					<td><?=$detail["fst_referensi"]?></td>
					<td class="text-right"><?=formatNumber($detail["fdc_amount"])?></td>
				</tr>
			<?php  } ?>
			<tr>
				<td class="total text-right" colspan="2">Total</td>
				<td class="total text-right" ><?=formatNumber($total)?></td>
			</tr>

			
		</table>

	</div>
	
	
</div>

<div class="assignment">							
	<table class="assignment" style="page-break-inside: avoid;width:100%">
		<tr>
			<td style="width:50%">Dibuat,</td>					
			<td style="width:50%">Disetujui,</td>					
		</tr>
		<tr>
			<td class="asign-col"><td>					
			<td class="asign-col"><td>					
		</tr>
		<tr>
			<td>.................</td>
			<td>.................</td>
		</tr>		
	</table>
</div>
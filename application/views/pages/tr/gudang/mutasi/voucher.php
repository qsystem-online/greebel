<?php
	//var_dump($header);
	//var_dump($details);
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
			<div class="inline first-col">No. MAG</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_mag_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col2">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_mag_datetime"]))?></div>
		</div>
	</div>
	


	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col"><?= lang("Asal Gudang")?></div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_from_warehouse_name"]?></div>
		</div>		
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col"><?= lang("Tujuan Gudang")?></div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_to_warehouse_name"]?></div>
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
					<th style="width:60%">Item</th>
					<th style="width:20%">Unit</th>
					<th style="width:20%;text-align:right">Qty</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$total =0;
				?>
				<?php foreach($details as $detail){ ?>	
					<?php
						$detail = (array)$detail;
						$total += $detail["fdb_qty"];
					?>					
					<tr class="have-detail">
						<td><?= $detail["fst_item_code"] . " - " . $detail["fst_item_name"] ?></td>
						<td><?= $detail["fst_unit"] ?></td>
						<td class="text-right"><?= formatNumber($detail["fdb_qty"]) ?></td>
					</tr>	
					<tr class="have-detail">
						<td colspan="3">Batch : <?= $detail["fst_batch_number"] ?>, Serial :<?= str_replace( '"','',$detail["fst_serial_number_list"]) ?></td>
					</tr>									
				<?php }?>
				<tr>
					<td class="total text-right" colspan="2">Total</td>
					<td class="total text-right"><?= formatNumber($total)?></td>
				</tr>
			</tbody>	
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
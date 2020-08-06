<?php
	//var_dump($header);
	//var_dump($details);
?>
<style>
	.first-col{
		width:130px;
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
			<div class="inline first-col">No. SO</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_salesorder_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline" style="width:80px">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_salesorder_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Customer</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_cust_name"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline" style="width:80px">Mata Uang</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$header["fst_curr_name"]?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Alamat Pengiriman</div>
			<div class="inline" style="width:15px">:</div>			
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline"><?=$header["fst_shipping_address"]?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:45%">Item Code - Name</th>
				<th style="width:5%">Unit</th>
				<th style="width:8%;text-align:right">Qty</th>


				<th class="text-right" style="width:12%">Price</th>
				<th style="width:10%;text-align:right">Disc</th>
				
				<th style="width:15%;text-align:right">Total</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$totalFooter =0;
				$ttlDisc =0;
			?>

			<?php foreach($details as $detail){ ?>
				
				<?php										
					$totalPerRow = $detail["fdb_qty"] * ($detail["fdc_price"] - $detail["fdc_disc_amount_per_item"]);
					$totalFooter += $totalPerRow ;
					$disc =  $detail["fdc_disc_amount_per_item"] * $detail["fdb_qty"];
					$ttlDisc += $disc;
				?>
				<tr>
					<td><?= $detail["fst_item_code"]  ." - " . $detail["fst_custom_item_name"] ?></td>
					<td><?= $detail["fst_unit"]?> </td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>

					<td class="text-right"><?= formatNumber($detail["fdc_price"])?> </td>
					<td class="text-right"><?= formatNumber($disc)?> </td>
					
					<td class="text-right"><?= formatNumber($totalPerRow) ?> </td>
				</tr>

				
			<?php }?>	
			<tr class="total">
				<td colspan="6">Downpayment : <?=formatNumber($header["fdc_downpayment"])?> </td>
			</tr>

			<tr class="total">
				<td class="text-right" colspan="5">Sub Total (DPP) </td>
				<td class="text-right"><?=formatNumber($header["fdc_dpp_amount"])?></td>
			</tr>
			<?php if ($ttlDisc > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="5">Total Disc</td>
					<td class="text-right"><?=formatNumber($ttlDisc)?></td>
				</tr>		
			<?php } ?>

			<?php if ($header["fdc_vat_amount"] > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="5">Ppn <?= $header["fdc_vat_percent"] ?> % </td>
					<td class="text-right"><?=formatNumber($header["fdc_vat_amount"])?></td>
				</tr>		
			<?php } ?>

			<tr class="total">
				<?php
					$total  = $totalFooter - $ttlDisc + $header["fdc_vat_amount"];
				?>
				<td class="text-right" colspan="5">Total</td>
				<td class="text-right"><?=formatNumber($total)?></td>
			</tr>
			
			
			
		</tbody>	
	</table>	
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

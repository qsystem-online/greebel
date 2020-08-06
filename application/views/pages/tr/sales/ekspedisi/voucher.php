<?php
	//var_dump($header);
	//echo "<br><br>";
	//var_dump($details);
?>
<style>
	.first-col{
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
			<div class="inline first-col">No. Ekspedisi</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_salesekspedisi_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_salesekspedisi_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Ekspedisi</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_supplier_name"] ?></div>
		</div>	
	</div>

	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Customer</div>
			<div class="inline" style="width:15px">&nbsp;</div>
			<div class="inline"><?=$header["fst_customer_name"]?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Alamat</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_shipping_address"]?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Notes</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_memo"]?></div>
		</div>
	</div>
	

	<?php
		$total = ($header["fdb_qty"] * $header["fdc_price"]) + $header["fdc_ppn_amount"] + $header["fdc_other_cost"];
	?>

	

	<!-- Detail -->

	<table class="table  table-condensed" style="float:right;width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:80%">No. Surat Jalan</th>
				<th style="width:20%"></th>
				
			</tr>
		</thead>

		<tbody>
			<?php
				$totalFooter =0;
				$ttlDisc =0;
			?>

			<?php foreach($details as $detail){ ?>
				
				<?php					
					
				?>
				<tr>
					<td colspan="2"><?= $detail["fst_sj_no"] ?></td>					
				</tr>
				
			<?php }?>	

			<tr class="total">
				<td class="text-right">Qty (kodi)</td>
				<td class="text-right"><?=$header["fdb_qty"]?></td>
			</tr>
			<tr class="total">
				<td class="text-right">Price @<?= formatNumber($header["fdc_price"]) ?> </td>
				<td class="text-right"><?=formatNumber($header["fdc_price"] * $header["fdb_qty"])?></td>
			</tr>
			<tr class="total">
				<td class="text-right">Ppn <?= $header["fdc_ppn_percent"] ?> % </td>
				<td class="text-right"><?=formatNumber($header["fdc_ppn_amount"])?></td>
			</tr>
			<?php if ($header["fdc_other_cost"] > 0 ) { ?>
				<tr class="total">
					<td class="text-right">Lain-lain</td>
					<td class="text-right"><?=formatNumber($header["fdc_other_cost"])?></td>
				</tr>
			<?php } ?>

			<tr class="total">
				<td class="text-right">Total</td>
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

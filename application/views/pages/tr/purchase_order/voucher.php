<?php
	//var_dump($header);
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
	<h2>Purchase Order</h2>
	<div class="row">
		<div class="col-xs-8">
			<label class="first-col">No. PO</label>
			<label>:</label>
			<label class="last-col"><?=$header["fst_po_no"]?></label>
		</div>
		<div class="col-xs-4 text-right">
			<label>Tanggal</label>
			<label>:</label>
			<label class="last-col"><?= date("d-M-Y",strtotime($header["fdt_po_datetime"]))?></label>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<label class="first-col">Supplier</label>
			<label>:</label>
			<label><?=$header["fst_supplier_name"] ?></label>
		</div>
		<div class="col-xs-4 text-right">
			<label>Currency</label>
			<label>:</label>
			<label class="last-col"><?=$header["fst_curr_name"] ?></label>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<label style="text-align:justify;font-style: italic;">Notes: asd asdasd asdasdasdasdjasd asdasdasaksda sadasdasdasdadasd asdasdjasdaksdakjsdjasdjaskdjasdasd asdaskdasjkdajsdajsdjasd asd asd asdasdasdkasd asda sdasd asdasdasdasdasd asdasdasdas dasdasd<?=$header["fst_memo"]?></label>
		</div>
	</div>

	<!-- Detail -->
	
	<table class="table  table-condensed" style="width:100%;padding-bottom:200px">
		<thead>
			<tr>
				<th style="width:45%">Item Code - Name</th>
				<th style="width:5%">Unit</th>
				<th class="text-right" style="width:12%">Price</th>
				<th class="text-center" style="width:5%">Disc</th>
				<th class="text-right" style="width:10%">Disc - Amount</th>
				<th class="text-right" style="width:8%">Qty</th>
				<th class="text-right" style="width:15%">Total</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$totalFooter =0;
				$ttlDisc =0;
			?>

			<?php foreach($details as $detail){ ?>
				
				<?php	
					for($i=0;$i<20;$i++){				
					$totalPerRow = $detail["fdb_qty"] * ($detail["fdc_price"] - $detail["fdc_disc_amount_per_item"]);
					$totalFooter += $totalPerRow ;
					$disc =  $detail["fdc_disc_amount_per_item"] * $detail["fdb_qty"];
					$ttlDisc += $disc;
				?>
				

				<tr>
					<td><?= $detail["fst_item_code"]  ." - " . $detail["fst_custom_item_name"] ?></td>
					<td><?= $detail["fst_unit"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_price"])?> </td>
					<td class="text-center"><?= $detail["fst_disc_item"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_disc_amount_per_item"])?> </td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
					<td class="text-right"><?= formatNumber($totalPerRow) ?> </td>
				</tr>

				<?php if ($detail["fst_notes"]) {?>
					<tr><td colspan="7" style="border-top:0px solid #000;font-style:italic"> notes: <?= $detail["fst_notes"] ?></td></tr>				
				<?php } ?>
			
			<?php }}?>	

			<tr class="total">
				<td class="text-right" colspan="6">Sub Total</td>
				<td class="text-right"><?=formatNumber($totalFooter)?></td>
			</tr>
			<?php if ($ttlDisc > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="6">Total Disc</td>
					<td class="text-right"><?=formatNumber($ttlDisc)?></td>
				</tr>		
			<?php } ?>

			<?php if ($header["fdc_ppn_amount"] > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="6">Ppn <?= $header["fdc_ppn_percent"] ?> % </td>
					<td class="text-right"><?=formatNumber($header["fdc_ppn_amount"])?></td>
				</tr>		
			<?php } ?>

			<?php if ($header["fdc_downpayment"] >0 ) {?>
				<tr class="total">
					<td class="text-right" colspan="6">DP</td>
					<td class="text-right"><?=formatNumber($header["fdc_downpayment"])?></td>
				</tr>	
			<?php } ?>

			<tr class="total">
				<?php
					$total  = $totalFooter - $ttlDisc + $header["fdc_ppn_amount"] - $header["fdc_downpayment"] ;
				?>
				<td class="text-right" colspan="6">Total</td>
				<td class="text-right"><?=formatNumber($total)?></td>
			</tr>	
			
		</tbody>		
		<tfoot>
				<tr>
					<td colspan="7" >
						<div>INI BAGIAN FOOTER 1</div>
						<div>INI BAGIAN FOOTER 2</div>
						<div>INI BAGIAN FOOTER 3</div>
						<div id="footer"></div>
					</td>
				</tr>
		</tfoot>
	</table>
	
	<div style="position:fixed;bottom:100px">
		INI BUAT TANDA TANGAN
	</div>
	
	
</div>
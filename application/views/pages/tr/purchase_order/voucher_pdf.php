<?php
	//var_dump($header);
	//var_dump($details);
?>
<style>
	label{
		display:block;
	}
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

<div class='voucher content' style="width:100%">	
	<div class="row">
		<h2><?=$title?></h2>
	</div>

	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">&nbsp;</div>			
			<div class="inline" style="width:15px">&nbsp;</div>
			<div class="inline">
				<barcode code="<?=$header["fst_po_no"]?>" type="C39" size="0.5"/>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">No. PO</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_po_no"]?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?= date("d-M-Y",strtotime($header["fdt_po_datetime"]))?></div>						
		</div>
	</div>
	
	
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Supplier</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_supplier_name"] ?></div>
		</div>


		<div class="col" style="width:30%">
			<div class="inline first-col">Currency</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$header["fst_curr_name"] ?></div>						
		</div>			
	</div>

	<div class="row" style="margin-top:10px">
		<div class="col" style="text-align:justify;font-style:italic;font-size:8pt">
			<?=$header["fst_memo"]?>
		</div>
	</div>

	<!-- Detail -->
	<div class="row">
		<table class="table table-condensed" style="width:100%;padding-bottom:0px">
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
						for($i=0;$i<8;$i++){	
						if (rand(0,1)){
							$detail["fst_notes"] = "";
						}else{
							$detail["fst_notes"] = "";
						}

						$totalPerRow = $detail["fdb_qty"] * ($detail["fdc_price"] - $detail["fdc_disc_amount_per_item"]);
						$totalFooter += $totalPerRow ;
						$disc =  $detail["fdc_disc_amount_per_item"] * $detail["fdb_qty"];
						$ttlDisc += $disc;
					?>
					

					<tr class="<?= $detail["fst_notes"] ? "have-detail" : "" ?>" > 
						<td> <?= $detail["fst_item_code"]  ." - " . $detail["fst_custom_item_name"] ?></td>
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
				
				<?php } } ?>	

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
		</table>

		<table class="assignment">
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
</div>
<?php
	var_dump($mspromo);
	var_dump($promoTerms);
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
			<div class="inline first-col">Nama Promo</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$mspromo["fst_promo_name"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col">Periode</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($mspromo["fdt_start"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Free Item</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$mspromo["fst_item_name"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">s/d Periode</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($mspromo["fdt_end"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">&nbsp;</div>
			<div class="inline" style="width:15px">&nbsp;</div>
			<div class="inline"><?=$mspromo["fst_promo_unit"]?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:45%">Item Code - Name</th>
				<th style="width:5%">Unit</th>
				<th class="text-right" style="width:12%">Price</th>
				<th class="text-center" style="width:5%">Disc</th>
				<th style="width:10%;text-align:right">Disc - Amount</th>
				<th style="width:8%;text-align:right">Qty</th>
				<th style="width:15%;text-align:right">Total</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$totalFooter =0;
				$ttlDisc =0;
			?>

			<?php foreach($promoTerms as $promoTerm){ ?>
				
				<?php					
					for($i =0 ;$i < 1;$i++){
					$totalPerRow = $promoTerm["fdb_qty"];
					$totalFooter += $totalPerRow ;
					$disc = $promoTerm["fdb_qty"];
					$ttlDisc += $disc;
				?>
				<tr>
					<td><?= $promoTerm["fst_item_type"]  ." - " . $promoTerm["ItemTerms"] ?></td>
					<td><?= $promoTerm["fst_unit"]?> </td>
					<td class="text-right"><?= $promoTerm["fdb_qty"]?> </td>
					<td class="text-right"><?= formatNumber($totalPerRow) ?> </td>
				</tr>
				<?php } ?>
			<?php }?>	

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

			<?php if ($mspromo["fdc_cashback"] > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="6">Ppn <?= $mspromo["fdc_cashback"] ?> % </td>
					<td class="text-right"><?=formatNumber($mspromo["fdc_cashback"])?></td>
				</tr>		
			<?php } ?>

			<tr class="total">
				<?php
					$total  = $totalFooter - $ttlDisc + $mspromo["fdc_cashback"];
				?>
				<td class="text-right" colspan="6">Total</td>
				<td class="text-right"><?=formatNumber($total)?></td>
			</tr>
		</tbody>	
	</table>

	
	
</div>

<htmlpagefooter name="AssigmentFooter">							
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
	<table width="100%">
		<tr>
			<td width="100%" align="right">Hal: {PAGENO}/{nbpg}</td>
		</tr>
	</table>
</htmlpagefooter>
<sethtmlpagefooter name="AssigmentFooter" value="ON"/>

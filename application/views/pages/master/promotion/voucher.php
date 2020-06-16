<?php
	//var_dump($mspromo);
	//var_dump($promoTerms);
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
            <div class="inline first-col">Cashback</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=number_format($mspromo["fdc_cashback"])?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Free Item</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$mspromo["fst_item_name"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Periode</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$mspromo["fst_other_prize"]?></div>
		</div>
	</div>
    <div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Qty</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$mspromo["fdb_promo_qty"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Nilai</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=number_format($mspromo["fdc_other_prize_in_value"])?></div>
		</div>
	</div>
    <div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Satuan</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$mspromo["fst_promo_unit"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Periode</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($mspromo["fdt_start"]))?></div>
		</div>
	</div>
    <div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Type</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$mspromo["fst_promo_type"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">s/d</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($mspromo["fdt_end"]))?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:75%">Type - Item Terms</th>
				<th style="width:5%">Unit</th>
				<!--<th class="text-right" style="width:12%">Price</th>
				<th class="text-center" style="width:5%">Disc</th>-->
				<th style="width:10%;text-align:right">Qty</th>
			</tr>
		</thead>

		<tbody>

			<?php foreach($promoTerms as $promoTerm){ ?>
			
				<tr>
					<td><?= $promoTerm["fst_item_type"]  ." - " . $promoTerm["ItemTerms"] ?></td>
					<td><?= $promoTerm["fst_unit"]?> </td>
					<td class="text-right"><?= $promoTerm["fdb_qty"]?> </td>
				</tr>
			<?php }?>	

		</tbody>	
	</table>

	<!-- Detail 2 -->
	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:75%">Type - Participant Name</th>
			</tr>
		</thead>

		<tbody>

			<?php foreach($promoParticipants as $promoParticipant){ ?>
				
				<tr>
					<td><?= $promoParticipant["fst_participant_type"]  ." - " . $promoParticipant["ParticipantName"] ?></td>
				</tr>
			<?php }?>	
		</tbody>	
	</table>
	<!-- Detail 2 -->
	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:65%">Item Name Discount</th>
				<th style="width:10%">Qty</th>
				<th style="width:5%">Unit</th>
				<th class="text-center" style="width:5%">Disc</th>
				<th class="text-right" style="width:12%">Disc Amount</th>
			</tr>
		</thead>

		<tbody>

			<?php foreach($promodiscItems as $promodiscItem){ ?>
				
				<tr>
					<td><?= $promodiscItem["fst_item_name"]?></td>
					<td><?= $promodiscItem["fin_qty"]?></td>
					<td><?= $promodiscItem["fst_unit"]?> </td>
					<td class="text-right"><?= $promodiscItem["fdc_disc_persen"]?> </td>
					<td class="text-right"><?= formatNumber($promodiscItem["fdc_disc_value"])?> </td>
				</tr>
			<?php }?>	
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

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
	
</style>

<div class='voucher content'>	
	<div class="row"><h2><?= $title?></h2></div>

	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">No. Cost</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_purchasecost_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_purchasecost_datetime"]))?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">No. PO</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_po_no"] ?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Tanggal PO</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_po_datetime"]))?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">&nbsp;</div>
			<div class="inline" style="width:15px">&nbsp;</div>
			<div class="inline"><?=$header["fst_supplier_name"]?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Currency</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$header["fst_curr_name"]?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline notes"><?=($header["fst_memo"]) ? "notes : " . $header["fst_memo"] : ""?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:30%">GL Account</th>
				<th style="width:40%">Notes</th>
				<th class="text-right" style="width:15%">Debet</th>
				<th class="text-right" style="width:15%">Credit</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$totalDebet = 0;
				$totalCredit = 0;
			?>

			<?php foreach($details as $detail){ ?>
				
				<?php										
					$totalDebet += $detail["fdc_debet"];
					$totalCredit += $detail["fdc_credit"];
				?>

				<tr>
					<td><?= $detail["fst_glaccount_code"]  ." - " . $detail["fst_glaccount_name"] ?></td>
					<td><?= $detail["fst_notes"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_debet"])?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_credit"])?> </td>
				</tr>

			<?php }?>	

			<tr class="total">
				<td class="text-right" colspan="2">Total</td>
				<td class="text-right"><?=formatNumber($totalDebet)?></td>
				<td class="text-right"><?=formatNumber($totalCredit)?></td>
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

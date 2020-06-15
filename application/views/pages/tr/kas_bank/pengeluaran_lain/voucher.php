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
			<div class="inline first-col">No. Pengeluaran</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_cbpaymentoth_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col2">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_cbpaymentoth_datetime"]))?></div>
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
			<div class="inline first-col">Dikeluarkan Untuk</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_give_to"]?></div>
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
					<th style="width:60%">Account</th>
					<th style="width:20%;text-align:right">Debet</th>
					<th style="width:20%;text-align:right">Credit</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$totalDebet =0;
					$totalCredit =0;
				?>
				<?php foreach($details as $detail){ ?>	
					<?php
						$totalDebet += $detail["fdc_debit"];
						$totalCredit += $detail["fdc_credit"];
						$transType ="";
					?>						
					<tr class="<?=$detail["fst_notes"] ? "have-detail" : "" ?>">
						<td><?= $detail["fst_glaccount_code"] ." - ". $detail["fst_glaccount_name"] ?></td>
						<td class="text-right"><?= formatNumber($detail["fdc_debit"])?> </td>
						<td class="text-right"><?= formatNumber($detail["fdc_credit"])?> </td>
					</tr>				
					<tr>
						<td colspan="3"><?=$detail["fst_notes"]?></td>
					</tr>
				<?php }?>
				<tr>
					<td class="total text-right">Total</td>
					<td class="total text-right"><?= formatNumber($totalDebet)?></td>
					<td class="total text-right"><?= formatNumber($totalCredit)?></td>
				</tr>
			</tbody>	
		</table>	
	</div>

	<div class="row">
		<h3>Penerimaan :</h3>
		<table class="table  table-condensed" style="width:70%;padding-bottom:0px">			
			<tr>
				<td>Cash / Transfer</td>					
				<td></td>
				<td class="text-right"><?=formatNumber($header["fdc_cash_transfer"])?></td>
			</tr>
			<?php if ($header["fdc_bilyet"] > 0) { ?>					
				<tr>
					<td>Cheque / Giro </td>					
					<td><?=formatNumber($header["fst_bilyet_no"])?></td>
					<td class="text-right"><?=formatNumber($header["fdc_bilyet"])?></td>
				</tr>
			<?php } ?>

			<tr>
				<td class="total text-right" colspan="2">Total</td>
				<td class="total text-right" ><?=formatNumber($header["fdc_cash_transfer"] + $header["fdc_bilyet"])?></td>
			</tr>

			
		</table>

	</div>
	
	
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

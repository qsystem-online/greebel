<?php
	$jurnalType = "";
	switch ($header["fst_journal_type"]){
		case "JA":
			$jurnalType = "Jurnal Penyesuaian";
			break;
		case "JB":
			$jurnalType = "Jurnal Pembelian";
			break;
		case "JC":
			$jurnalType = "Jurnal Penutup";
			break;
		case "JJ":
			$jurnalType = "Jurnal Penjualan";
			break;
		case "JK":
			$jurnalType = "Jurnal Pengeluaran";
			break;
		case "JT":
			$jurnalType = "Jurnal Penerimaan";
			break;
		case "JU":
			$jurnalType = "Jurnal Umum";
			break;
		case "KK";
			$jurnalType = "Kas Besar Keluar";
			break;
	};
		

	//var_dump($header);
	//var_dump($details);
	//die();
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
			<div class="inline first-col">No. Voucher</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_journal_no"]?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?= date("d-M-Y",strtotime($header["fdt_journal_datetime"]))?></div>						
		</div>
	</div>
	
	
	
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Type</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$jurnalType?></div>
		</div>


		<div class="col" style="width:30%">
			<div class="inline first-col">Currency</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$header["fst_curr_name"] ?></div>						
		</div>			
	</div>

	<div class="row" style="margin-top:10px">
		<div class="col" style="text-align:justify;font-style:italic;font-size:8pt">
			<?=$header["fst_desc"]?>
		</div>
	</div>

	<!-- Detail -->
	<div class="row">
		<table class="table table-condensed" style="width:100%;padding-bottom:0px">
			<thead>
				<tr>
					<th style="width:70%">Account</th>
					<th class="text-right" style="width:15%">Debet</th>
					<th class="text-right" style="width:15%">Credit</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$totalDebit = 0;
					$totalCredit = 0;
				?>

				<?php foreach($details as $detail){ ?>
					
					<?php	
						$detail = (array)$detail;
						$totalDebit += $detail["fdc_debit"];
						$totalCredit += $detail["fdc_credit"];
					?>
					

					<tr class="<?= $detail["fst_memo"] ? "have-detail" : "" ?>" > 
						<td> <?= $detail["fst_glaccount_code"]  ." - " . $detail["fst_glaccount_name"] ?></td>
						<td class="text-right"><?= formatNumber($detail["fdc_debit"])?> </td>
						<td class="text-right"><?= formatNumber($detail["fdc_credit"])?> </td>
					</tr>

					<?php if ($detail["fst_memo"]) {?>
						<tr><td colspan="3" style="border-top:0px solid #000;font-style:italic"> notes: <?= $detail["fst_memo"] ?></td></tr>				
					<?php } ?>
				
				<?php } ?>	

				<tr class="total">
					<td class="text-right" colspan="1">Sub Total</td>
					<td class="text-right"><?=formatNumber($totalDebit)?></td>
					<td class="text-right"><?=formatNumber($totalCredit)?></td>
				</tr>				
			</tbody>		
		</table>		
	</div>
</div>

<div class="assignment">
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
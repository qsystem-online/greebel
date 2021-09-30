<?php
	//var_dump($header);
	//echo "<br><br>";
	//var_dump($details);
?>
<style>
	.header-col{
		width:350px;
		font-size:15pt;
		font-weight: bold;
	}
	.first-col{
		width:80px;
	}
	.last-col{
		width:80px;
	}
	.total{
		font-size:10pt;
	}	
</style>
<style>
@media print{
    @page {
        size: Faktur portrait;
        /*height: 215mm;
        width: 139mm;*/
        margin: 0;
    }
}
</style>
<div class='nota'>
<section class="sheet padding-10mm">
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline header-col"><?= $company?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline header-col text-left"><?= $title?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Kepada Yth</div>
			<div class="inline" style="width:25px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_customer_name"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">No. Faktur</div>			
			<div class="inline" style="width:25px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_inv_no"]?></div>
		</div>	
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Alamat</div>
			<div class="inline" style="width:25px">:</div>
			<div class="inline"><?=$header["fst_customer_name"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:25px">:</div>
			<div class="inline"><?=date("d-M-Y",strtotime($header["fdt_inv_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col"></div>
			<div class="inline" style="width:25px"></div>
			<div class="inline"></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">No. SO</div>
			<div class="inline" style="width:25px">:</div>
			<div class="inline"><?=$header["fst_salesorder_no"] ?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:5%;text-align:left">No</th>
				<th style="width:45%">Nama Barang</th>
				<th style="width:8%;text-align:right">Qty</th>
				<th style="width:5%;text-align:right">Unit</th>
				<th class="text-right" style="width:12%">Harga</th>
				<th class="text-right" style="width:5%">Disc%</th>
				<!--<th style="width:10%;text-align:right">Disc</th>-->
				<th style="width:15%;text-align:right">Jumlah</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$totalFooter =0;
				$ttlDisc =0;
				$nou =1;
			?>

			<?php foreach($details as $detail){ ?>
				
				<?php					
					for($i =0 ;$i < 1;$i++){
					$totalPerRow = $detail["fdb_qty"] * ($detail["fdc_price"] - $detail["fdc_disc_amount_per_item"]);
					$totalFooter += $totalPerRow ;
					$disc =  $detail["fdc_disc_amount_per_item"] * $detail["fdb_qty"];
					$ttlDisc += $disc;
				?>
				<tr class="have-detail">
					<td><?= $nou++ ?></td>
					<td><?= $detail["fst_custom_item_name"] ?></td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
					<td class="text-right"><?= $detail["fst_unit"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_price"])?> </td>
					<td class="text-right"><?= $detail["fst_disc_item"]?> </td>
					<!--<td class="text-right"><?= formatNumber($detail["fdc_disc_amount_per_item"])?> </td>-->
					<td class="text-right"><?= formatNumber($totalPerRow) ?> </td>
				</tr>
				<?php } ?>
			<?php }?>	

			<!--<tr class="total">
				<td class="text-right" colspan="5">Sub Total</td>
				<td class="text-right"><?=formatNumber($totalFooter)?></td>
			</tr>
			<?php if ($ttlDisc > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="5">Total Disc</td>
					<td class="text-right"><?=formatNumber($ttlDisc)?></td>
				</tr>		
			<?php } ?>

			<?php if ($header["fdc_ppn_amount"] > 0) { ?>
				<tr class="total">
					<td class="text-right" colspan="5">Ppn <?= $header["fdc_ppn_percent"] ?> % </td>
					<td class="text-right"><?=formatNumber($header["fdc_ppn_amount"])?></td>
				</tr>		
			<?php } ?>

			<tr class="total">
				<?php
					//$total  = $totalFooter - $ttlDisc + $header["fdc_ppn_amount"];
					//$total  = $totalFooter + $header["fdc_ppn_amount"];
				?>
				<td class="text-right" colspan="5">DP</td>
				<td class="text-right"><?=formatNumber($header["fdc_downpayment_claim"])?></td>
			</tr>

			<tr class="total">
				<?php
					//$total  = $totalFooter - $ttlDisc + $header["fdc_ppn_amount"];
					$total  = $totalFooter + $header["fdc_ppn_amount"] - $header["fdc_downpayment_claim"];
				?>
				<td class="text-right" colspan="5">Total</td>
				<td class="text-right"><?=formatNumber($total)?></td>
			</tr>-->

		</tbody>
		<div class="assignment">
			<table class="assignment-total" style="width:100%">
			<?php
				//$total  = $totalFooter - $ttlDisc + $header["fdc_ppn_amount"];
				$total  = $header["fdc_dpp_amount"] + $header["fdc_ppn_amount"] - $header["fdc_downpayment_claim"];
			?>
				<tr>
					<td style="width:70%">Terbilang :</td>
					<td class="text-right" style="width:15%">TOTAL :</td>
					<td class="text-right" style="width:15%"><?=formatNumber($total)?></td>											
				</tr>
				<tr>
					<td style="width:70%">#<?=number_to_words($total)?>#</td>										
				</tr>
				<tr>
					<td style="width:70%">.</td>									
				</tr>
				<tr>
					<td style="width:70%">Pembayaran dengan transfer ke:</td>									
				</tr>
				<tr>
					<td style="width:70%">BCA a/c 583 042 7777 a/n <?= $company?></td>									
				</tr>
			</table>
			<table class="assignment" style="width:100%">
				<tr>
					<td style="width:40%">Dibuat Oleh,</td>					
					<td style="width:30%">Hormat Kami,</td>
					<td style="width:30%">Tanda Terima,</td>						
				</tr>
				<tr>
					<td class="asign-col"><td>					
					<td class="asign-col"><td>					
				</tr>
				<tr>
					<td><?=$header["fst_username"]?></td>
					<td>(.................)</td>
					<td>(.................)</td>
				</tr>		
			</table>
		</div>	
	</table>
</section>
</div>
<!--<div class="assignment-total">
	<table class="assignment-total" style="page-break-inside: avoid;width:100%">
		<tr>
			<td style="width:60%">.</td>									
		</tr>
		<tr>
			<td style="width:60%">.</td>										
		</tr>
		<tr>
			<td style="width:60%">.</td>									
		</tr>
		<tr>
			<td style="width:60%">Pembayaran dengan transfer ke:</td>									
		</tr>
		<tr>
		<?php
			//$total  = $totalFooter - $ttlDisc + $header["fdc_ppn_amount"];
			$total  = $header["fdc_dpp_amount"] + $header["fdc_ppn_amount"] - $header["fdc_downpayment_claim"];
		?>
			<td style="width:60%">BCA a/c 583 042 7777 a/n <?= $company?></td>						
			<td class="text-right" style="width:20%">TOTAL</td>
			<td class="text-right" style="width:20%"><?=formatNumber($total)?></td>					
		</tr>
	</table>
</div>-->
<script>
	window.print();
</script>
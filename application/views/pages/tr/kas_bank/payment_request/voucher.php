<?php
	//var_dump($header);
	//echo "<br><br>";
	//var_dump($details);
	if(empty($header["fst_pp_memo"])){
		$header["fst_pp_memo"] = "";
	}
	$full_memo = array_filter(explode(PHP_EOL, $header["fst_pp_memo"]));
	if(!empty($full_memo[0])) {
		$memo1 = $full_memo[0];
	}
	else {
		$memo1 = "";
	}
	if(!empty($full_memo[1])) {
		$memo2 = $full_memo[1];
	}
	else {
		$memo2 = "";
	}
	if(!empty($full_memo[2])) {
		$memo3 = $full_memo[2];
	}
	else {
		$memo3 = "";
	}
?>
<style>
	.header-col{
		width:500px;
		font-size:15pt;
		font-weight: bold;
	}
	.first-col{
		width:120px;
	}
	.last-col{
		width:80px;
	}
	.total{
		font-size:10pt;
	}
	.hidden {
		visibility: hidden;
	}		
</style>
<style>
@media print{
    @page {
        size: faktur portrait;
        /*height: 215mm;
        width: 139mm;*/
        margin: 0;
    }
}
</style>
<div class='nota'>
<section class="sheet padding-10mm">
	<div class="row" style="text-align:center"><h2><?= $title?></h2></div>

	<div class="row" style="padding-bottom:20px">
		<div class="col" style="width:65%">
			<div class="inline first-col">Company</div>
			<div class="inline" style="width:10px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_company_code"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Tgl Pengajuan</div>			
			<div class="inline" style="width:10px">:</div>
			<div class="inline" style="font-weight: bold"><?=date("d-M-Y",strtotime($header["fdt_paymentrequest_datetime"]))?></div>
		</div>	
	</div>
	<div class="row" style="padding-bottom:20px">
		<div class="col" style="width:65%">
			<div class="inline first-col">No.Request</div>
			<div class="inline" style="width:10px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_paymentrequest_no"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Tgl Jatuh Tempo</div>			
			<div class="inline" style="width:10px">:</div>
			<div class="inline" style="font-weight: bold"><?=date("d-M-Y",strtotime($header["fdt_payment_due_date"]))?></div>
		</div>	
	</div>
	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:5%;text-align:center">No</th>
				<th style="width:75%">Keterangan</th>
				<th style="width:8%;text-align:right">Qty</th>
				<th class="text-right" style="width:15%">Nilai</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$totalFooter =0;
				$nou =1;
			?>

			<?php foreach($details as $detail){ ?>
				
				<?php					
					for($i =0 ;$i < 1;$i++){
					$totalPerRow = $detail["fdb_qty"] * $detail["fdc_amount"];
					$totalFooter += $totalPerRow ;
				?>
				<tr class="have-detail">
					<td style="text-align:center"><?= $nou++ ?></td>
					<td><?= $detail["fst_notes"] ?></td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_amount"])?> </td>
				</tr>
				<?php } ?>
			<?php }?>	

		</tbody>
		<div class="assignment">
			<table class="assignment-total" style="width:100%">
			<?php
				//$total  = $totalFooter - $ttlDisc + $header["fdc_ppn_amount"];
				$total  = $header["fdc_total"];
			?>
				<tr>
					<td style="width:70%"><u><?=$header["fst_relation_name"]?></u></td>
					<td class="text-right" style="width:15%">TOTAL :</td>
					<td class="text-right" style="width:15%"><?=formatNumber($total)?></td>										
				</tr>
				<tr>
					<td style="width:70%"><?= $memo1 ?></td>
					<td class="text-right hidden" style="width:15%"></td>
					<td class="text-right hidden" style="width:15%"></td>										
				</tr>
				<tr>
					<td style="width:70%"><?= $memo2 ?></td>
					<td class="text-right hidden" style="width:15%"></td>
					<td class="text-right hidden" style="width:15%"></td>										
				</tr>
				<tr>
					<td style="width:70%"><?= $memo3 ?></td>
					<td class="text-right hidden" style="width:15%"></td>
					<td class="text-right hidden" style="width:15%"></td>										
				</tr>
			</table>
			<table class="assignment" style="width:100%">
				<tr>
					<td style="width:50%">Requestor</td>					
					<td style="width:50%">GM Operasional</td>						
				</tr>
				<tr>
					<td class="asign-col"><td>					
					<td class="asign-col"><td>					
				</tr>
				<tr>
					<td><?=$header["fst_username"]?></td>
					<td>(.................)</td>
				</tr>		
			</table>
		</div>	
	</table>
</section>
</div>
<script>
	window.print();
</script>

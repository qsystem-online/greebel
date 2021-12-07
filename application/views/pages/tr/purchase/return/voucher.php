<?php
	/*if(empty($header["fst_pp_memo"])){
		$header["fst_pp_memo"] = "";
	}
	$full_memo = array_filter(explode(PHP_EOL, $header["fst_pp_memo"]));
	*/
?>
<style>
	.header-col{
		width:350px;
		font-size:15pt;
		font-weight: bold;
		padding: 5px;
	}
	.first-col{
		width:90px;
	}
	.last-col{
		width:80px;
	}
	.total{
		font-size:10pt;
	}
	.padd-5 {
		padding: 5px;
	}
	.padd-header {
		padding: 2px;
	}
	.hidden {
		visibility: hidden;
	}
</style>

<style>
	* {
	box-sizing: border-box;
	}
	.rowFooter {
	display: flex;
	margin-left:-2px;
	margin-right:-2px;
	}

	.columnFooter1 {
	flex: 70%;
	padding: 1px;
	}
	.columnFooter2 {
	flex: 30%;
	padding: 1px;
	}
	.floatedTable {
		border-collapse: collapse;
		border-spacing: 0;
		width: 100%;
		border: 1px dotted #000;
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
	<div class="row padd-5">
		<div class="col" style="width:100%">
			<div class="inline"></div>
		</div>
	</div>
	<div class="row">
		<!--<div class="col" style="width:70%">
			<div class="inline header-col"><?=$company ?></div>
		</div>-->		
		<div class="col" style="width:100%;text-align:center">
			<div class="inline header-col"><?=$title ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Supplier</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_supplier_name"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">No. Retur</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_purchasereturn_no"]?></div>
		</div>	
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col " >No.LPB</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_lpbpurchase_no"] ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=date("d-M-Y",strtotime($header["fdt_purchasereturn_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col hidden" >No.LPB</div>
			<div class="inline hidden" style="width:15px">:</div>
			<div class="inline hidden"><?=$header["fst_lpbpurchase_no"] ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Mata Uang</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_curr_name"] ?></div>
		</div>
	</div>

	<!-- Detail -->
	<table class="table  table-condensed padd-5" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:5%;text-align:center">No</th>
				<th style="width:40%">Nama Barang</th>
				<th style="width:10%;text-align:right">Qty</th>
				<th style="width:5%;text-align:right">Unit</th>
				<th style="width:10%;text-align:right">Harga</th>
				<th style="width:10%;text-align:right">Jumlah</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$nou =1;
			?>

			<?php foreach($details as $detail){ ?>
				<?php					
					for($i =0 ;$i < 1;$i++){
					$totalPerRow = $detail["fdb_qty"] * ($detail["fdc_price"] - $detail["fdc_disc_amount_per_item"]);
				?>
				<tr class="have-detail">
					<td style="text-align:center"><?= $nou++ ?></td>
					<td><?= $detail["fst_custom_item_name"] ?></td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
					<td class="text-right"><?= $detail["fst_unit"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_price"])?> </td>
					<td class="text-right"><?= formatNumber($totalPerRow) ?> </td>
				</tr>
				<?php } ?>
			<?php }?>

		</tbody>
		<?php
			if(empty($header["fst_memo"])){
				$header["fst_memo"] = "";
			}
			$full_memo = array_filter(explode(PHP_EOL, $header["fst_memo"]));
			if(!empty($full_memo[0])) {
				$memo1 = $full_memo[0];
			}
			else {
				$memo1 = ".";
			}
			if(!empty($full_memo[1])) {
				$memo2 = $full_memo[1];
			}
			else {
				$memo2 = ".";
			}
			if(!empty($full_memo[2])) {
				$memo3 = $full_memo[2];
			}
			else {
				$memo3 = ".";
			}
		?>
		<!-- DIV dibawah ini jangan dibuang , utk mecah 2 kotak (memo dan keterangan) rowFooter dibawahnya -->
		<div class="rowFooter">
			<div class="columnFooter">
				<table class="floatedTable">

				</table>
			</div>
			<div class="columnFooter">
				<table class="floatedTable">
				</table>
			</div>
		</div>
		<div class="rowFooter padd-5">
			<div class="columnFooter1">
				<table class="floatedTable">
				<tr>
					<td><u>MEMO RTB :</u></td>
				</tr>
				<tr>
					<td><?=$memo1 ?></td>
				</tr>
				<tr>
					<td><?=$memo2 ?></td>
				</tr>
				<tr>
					<td><?=$memo3 ?></td>
				</tr>
				</table>
			</div>
			<div class="columnFooter2">
				<table class="floatedTable">
				<tr>
					<td class="text-left" style="width:50%;text-align:right">Subtotal :</td>
					<td class="text-left" style="width:50%;text-align:right"><?=formatNumber($header["fdc_subttl"])?></td>											
				</tr>
				<tr>
					<td class="text-left" style="width:50%;text-align:right">Disc :</td>
					<td class="text-left" style="width:50%;text-align:right"><?=formatNumber($header["fdc_disc_amount"])?></td>											
				</tr>
				<tr>
					<td class="text-left" style="width:50%;text-align:right">Ppn :</td>
					<td class="text-left" style="width:50%;text-align:right"><?=formatNumber($header["fdc_ppn_amount"])?></td>											
				</tr>
				<tr>
					<td class="text-left" style="width:50%;text-align:right">Total :</td>
					<td class="text-left" style="width:50%;text-align:right"><?=formatNumber($header["fdc_total"])?></td>											
				</tr>
				</table>
			</div>
		</div>
	</table>
	<table class="assignment" style="width:100%">
		<tr>
			<td style="width:30%">Disetujui,</td>					
			<td style="width:30%">Diperiksa,</td>
			<td style="width:40%">Disiapkan,</td>						
		</tr>
		<tr>
			<td class="asign-col"><td>					
			<td class="asign-col"><td>					
		</tr>
		<tr>
			<td>(.................)</td>
			<td>(.................)</td>
			<td>(.................)</td>
		</tr>		
	</table>
</section>
</div>
<script>
	window.print();
</script>

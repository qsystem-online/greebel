<?php
	if(empty($header["fst_shipping_address"])){
		$header["fst_shipping_address"] = "";
	}
	//--compatible on OS Windows
	$full_address = array_filter(explode("\n", $header["fst_shipping_address"]));
	//--compatible on OS Linux
	//$full_address = array_filter(explode(PHP_EOL, $header["fst_shipping_address"]));

	if(!empty($full_address[0])) {
		$address1 = $full_address[0];
	}
	else {
		$address1 = ".";
	}
	if(!empty($full_address[1])) {
		$address2 = $full_address[1];
	}
	else {
		$address2 = ".";
	}
	if(!empty($full_address[2])) {
		$address3 = $full_address[2];
	}
	else {
		$address3 = ".";
	}
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
	flex: 65%;
	padding: 1px;
	}
	.columnFooter2 {
	flex: 35%;
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
		<div class="col" style="width:100%">
			<div class="inline header-col text-left"><?=$title ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Customer</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_cust_name"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">No. S/O</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_salesorder_no"]?></div>
		</div>	
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Alamat Kirim</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$address1 ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=date("d-M-Y",strtotime($header["fdt_salesorder_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col hidden" style="text-align:right">A.Kirim2</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$address2 ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Termin</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fin_terms_payment"] ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col hidden" style="text-align:right">A.Kirim3</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$address3 ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Gudang</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_warehouse_name"] ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col hidden" style="text-align:right">A.Kirim4</div>
			<div class="inline hidden" style="width:15px">:</div>
			<div class="inline hidden">-</div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Sales</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["sales"] ?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:5%;text-align:center">No</th>
				<th style="width:35%">Nama Barang</th>
				<th style="width:8%;text-align:right">Qty</th>
				<th style="width:5%;text-align:right">Unit</th>
				<th style="width:10%;text-align:right">Harga</th>
				<th style="width:5%;text-align:right">Disc%</th>
				<th style="width:10%;text-align:right">Jumlah</th>
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
					<td style="text-align:center"><?= $nou++ ?></td>
					<td><?= $detail["fst_custom_item_name"] ?></td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
					<td class="text-right"><?= $detail["fst_unit"]?> </td>
					<td class="text-right"><?= formatNumber($detail["fdc_price"],2)?> </td>
					<td class="text-right"><?= $detail["fst_disc_item"]?> </td>
					<td class="text-right"><?= formatNumber($totalPerRow,2)?> </td>
				</tr>
				<?php } ?>
			<?php }?>

		</tbody>
		<?php
			$full_memo = explode(PHP_EOL, $header["fst_memo"]);
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
			if(!empty($full_memo[3])) {
				$memo4 = $full_memo[3];
			}
			else {
				$memo4 = ".";
			}
			if(!empty($full_memo[4])) {
				$memo5 = $full_memo[4];
			}
			else {
				$memo5 = ".";
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
		<div class="rowFooter">
			<div class="columnFooter1">
				<table class="floatedTable">
				<tr>
					<td><u>MEMO S/O :</u></td>
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
					<td class="text-right" style="width:50%">Sub total/Dpp :</td>
					<td class="text-right" style="width:50%"><?=formatNumber($header["fdc_dpp_amount"],2)?></td>	
				</tr>
				<tr>
					<td class="text-right" style="width:50%">Ppn :</td>
					<td class="text-right" style="width:50%"><?=formatNumber($header["fdc_vat_amount"],2)?></td>	
				</tr>
				<tr>
					<td class="text-right" style="width:50%">Total :</td>
					<td class="text-right" style="width:50%"><?=formatNumber($header["fdc_total"],2)?></td>	
				</tr>
				<tr>
					<td class="text-right" style="width:50%">Uang Muka:</td>
					<td class="text-right" style="width:50%"><?=formatNumber($header["fdc_downpayment"],2)?></td>	
				</tr>
				</table>
			</div>
		</div>	
	</table>
	<table class="assignment" style="width:100%">
		<tr>
			<td style="width:30%">Mengetahui,</td>					
			<td style="width:35%">Diperiksa,</td>
			<td style="width:35%">Dibuat,</td>					
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

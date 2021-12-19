<?php
?>
<style>
	.header-col{
		width:700px;
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
	flex: 40%;
	padding: 1px;
	}
	.columnFooter2 {
	flex: 60%;
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
			<div class="inline header-col" style="text-align:center"><?=$title ?></div>
		</div>
	</div>
	<div class="row padd-5">
		<div class="col" style="width:100%">
			<div class="inline header-col" style="text-align:center"><?=$header["fst_lhp_no"]?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Tanggal</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=date("d-M-Y",strtotime($header["fdt_lhp_datetime"]))?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Ke Gudang</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_warehouse_name"]?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">No.W/O</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_wo_no"]?></div>
		</div>
        <div class="col" style="width:35%">
			<div class="inline first-col">Kilo/Barang</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fdb_gramasi"]?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:5%;text-align:center">No</th>
				<th style="width:45%">Produk Jadi</th>
				<th style="width:10%;text-align:right">Qty</th>
                <th style="width:10%;text-align:right">Target</th>
                <th style="width:10%;text-align:right">Sisa Target</th>
				<th style="width:5%;text-align:right">Unit</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$nou =1;
			?>

            <tr class="have-detail">
                <td style="text-align:center"><?= $nou++ ?></td>
                <td><?= $header["fst_item_name"] ?></td>
                <td class="text-right"><?= $header["fdb_qty"]?> </td>
                <td class="text-right"><?= $header["fdb_qty_wo"]?> </td>
                <td class="text-right"><?= $header["fdb_qty_sisa"]?> </td>
                <td class="text-right"><?= $header["fst_unit"]?> </td>
            </tr>

		</tbody>
		<?php
			$full_memo = explode(PHP_EOL, $header["fst_notes"]);
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
		<div class="rowFooter">
			<div class="columnFooter1">
				<table class="floatedTable">
				<tr>
					<td><u>MEMO:</u></td>
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
				<tr>
					<td><?=$memo4 ?></td>
				</tr>
				<tr>
					<td><?=$memo5 ?></td>
				</tr>
				</table>
			</div>
		</div>
	</table>
</section>
</div>
<script>
	window.print();
</script>

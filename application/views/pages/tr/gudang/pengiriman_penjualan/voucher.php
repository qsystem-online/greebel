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
		<div class="col" style="width:70%">
			<div class="inline header-col"><?=$company ?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline header-col text-left"><?=$title ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">Kepada Yth</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_relation_name"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">No. S/J</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline" style="font-weight: bold"><?=$header["fst_sj_no"]?></div>
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
			<div class="inline"><?=date("d-M-Y",strtotime($header["fdt_sj_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col hidden" style="text-align:right">A.Kirim2</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$address2 ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Faktur Pajak</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_inv_no"] ?></div>
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
	<div class="row padd-5">
		<div class="col" style="width:65%">
			<div class="inline">Bersama ini kami kirimkan brg dengan mobil no</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_no_polisi"]?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline">Supir</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["sopir"]?></div>
		</div>
	</div>

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:5%;text-align:center">No</th>
				<th style="width:45%">Nama Barang</th>
				<th style="width:8%;text-align:right">Qty</th>
				<th style="width:5%;text-align:right">Unit</th>
				<th style="width:15%;text-align:right">Keterangan</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$nou =1;
			?>

			<?php foreach($details as $detail){ ?>
				<?php					
					for($i =0 ;$i < 1;$i++){
					$totalPerRow = $detail["fdb_qty"];
				?>
				<tr class="have-detail">
					<td style="text-align:center"><?= $nou++ ?></td>
					<td><?= $detail["fst_custom_item_name"] ?></td>
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
					<td class="text-right"><?= $detail["fst_unit"]?> </td>
					<td class="text-right">-</td>
				</tr>
				<?php } ?>
			<?php }?>

		</tbody>
		<?php
			$full_memo = explode(PHP_EOL, $header["fst_sj_memo"]);
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
					<td><u>MEMO S/J :</u></td>
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
			<div class="columnFooter2">
				<table class="floatedTable">
				<tr>
					<td>(1).Barang harus dihitung dengan teliti !!! Klaim hanya diakui</td>
				</tr>
				<tr>
					<td>    saat supir masih di lokasi</td>
				</tr>
				<tr>
					<td class="hidden">pemisah</td>
				</tr>
				<tr>
					<td>(2).Barang sudah dicek secara fisik dan sesuai dgn Surat Jalan, </td>
				</tr>
				<tr>
					<td>    Jika setelah pengecekan secara seksama,terdapat selisih,</td>
				</tr>
				<tr>
					<td>    menjadi tanggung jawab pihak Toko/Customer</td>
				</tr>
				</table>
			</div>
		</div>
		<div class="rowFooter">
			<div class="columnFooter2">
				<table class="floatedTable">
					<tr>
						<td><u>Pengiriman :</u><?=$header["fst_relation_notes"]?></td>
					</tr>
				</table>
			</div>
		</div>	
	</table>
	<table class="assignment" style="width:100%">
		<tr>
			<td style="width:25%">Hormat Kami,</td>					
			<td style="width:25%">Supir,</td>
			<td style="width:25%">Bagian Gudang,</td>
			<td style="width:25%">Penerima,</td>						
		</tr>
		<tr>
			<td class="asign-col"><td>					
			<td class="asign-col"><td>					
		</tr>
		<tr>
			<td>(.................)</td>
			<td>(.................)</td>
			<td><?=$header["user_input"]?></td>
			<td>(.................)</td>
		</tr>		
	</table>
</section>
</div>
<script>
	window.print();
</script>

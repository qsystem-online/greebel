<?php
	$full_address = $header["fst_shipping_address"];
	$address1 = substr($full_address,0,60);
	$address2 = substr($full_address,60,50);
?>
<style>
	.header-col{
		width:350px;
		font-size:15pt;
		font-weight: bold;
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
			<!--<textarea class="inline" rows="2" style="resize:none"><?=$header["fst_relation_name"]?></textarea>-->
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=date("d-M-Y",strtotime($header["fdt_sj_datetime"]))?></div>
		</div>
	</div>
	<div class="row">
		<div class="col" style="width:65%">
			<div class="inline first-col">-</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$address2 ?></div>
		</div>
		<div class="col" style="width:35%">
			<div class="inline first-col">Faktur Pajak</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_sj_no"] ?></div>
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
		<div class="assignment">
			<table class="assignment-total" style="width:100%">
				<tr>
					<td style="width:40%">MEMO S/J :</td>
					<td style="width:60%">(1).Barang harus dihitung dengan teliti !!! Klaim hanya diakui saat supir masih di lokasi</td>									
				</tr>
				<tr>
					<td style="width:40%">.</td>
					<td style="width:60%">(2).Barang sudah dicek secara fisik dan sesuai dengan Surat Jalan, Jika setelah pengecekan secara seksama,terdapat selisih, menjadi tanggung jawab pihak Toko/Customer</td>									
				</tr>
			</table>
		</div>
		<thead>
			<tr>
				<th style="width:100%">Pengiriman :</th>
			</tr>
		</thead>	
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
			<td>(.................)</td>
			<td>(.................)</td>
		</tr>		
	</table>
</section>
</div>
<script>
	window.print();
</script>

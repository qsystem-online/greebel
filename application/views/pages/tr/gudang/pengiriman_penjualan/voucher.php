<?php
	//var_dump($header);
	//var_dump($details);
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
			<div class="inline first-col">No. Surat Jalan</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_sj_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col2">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_sj_datetime"]))?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">Relasi</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_sj_type"]  . " - " . $header["fst_relation_name"]?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col2">Gudang</div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=$header["fst_warehouse_name"]?></div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col">Alamat Pengiriman</div>
			<div class="inline" style="width:15px">:</div>
		</div>
	</div>

	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline"><?=$header["fst_shipping_address"]?></div>
		</div>
	</div>

	<?php if ($header["fst_sj_memo"] != "" ) {?>
		<div class="row">
			<div class="col" style="width:100%">
				<div class="inline notes">notes : <?=$header["fst_sj_memo"]?></div>
			</div>
		</div>
	<?php } ?>
	

	<!-- Detail -->

	<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
		<thead>
			<tr>
				<th style="width:45%">Item Code - Name</th>
				<th style="width:5%">Unit</th>
				<th style="width:8%;text-align:right">Qty</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach($details as $detail){ ?>
				<?php for($i =0; $i < 15 ; $i++){ ?>
				<?php
					$detail = (array) $detail;
				?>							
				<tr>
					<td><?= $detail["fst_item_code"]  ." - " . $detail["fst_custom_item_name"] ?></td>
					<td><?= $detail["fst_unit"]?> </td>					
					<td class="text-right"><?= $detail["fdb_qty"]?> </td>
				</tr>				
			<?php }}?>
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

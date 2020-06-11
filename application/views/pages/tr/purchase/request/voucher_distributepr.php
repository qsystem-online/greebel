<?php
	//var_dump($header);
	//var_dump($details);
?>
<style>	
	.first-col{
		width:120px;
	}
	.last-col{
		width:80px;
	}	
</style>

<div class='voucher content' style="width:100%">	
	<div class="row">
		<h2><?=$title?></h2>
	</div>	
	
	<div class="row">
		<div class="col" style="width:70%">
			<div class="inline first-col">No. Distribution</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_distributepr_no"]?></div>
		</div>
		<div class="col" style="width:30%">
			<div class="inline first-col">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?= date("d-M-Y",strtotime($header["fdt_distributepr_datetime"]))?></div>						
		</div>
	</div>
	

	<div class="row" style="margin-top:10px">
		<div class="notes">
			<?=$header["fst_distributepr_notes"] ? "notes : " . $header["fst_distributepr_notes"] : ""?>
		</div>
	</div>

	<!-- Detail -->
	<div class="row">
		<table class="table table-condensed" style="width:100%;padding-bottom:0px">
			<thead>
				<tr>
					<th style="width:15%">Department</th>
					<th style="width:40%">Item Code - Name</th>
					<th style="width:10%">Unit</th>
					<th class="text-right" style="width:10%;">Qty</th>
					<th class="text-right" style="width:10%;">ETD</th>					
				</tr>
			</thead>

			<tbody>
				<?php
					$total =0;
				?>

				<?php foreach($details as $detail){ ?>					
					<tr class="have-detail">
						<td><?= $detail["fst_req_department_name"] ?></td>
						<td><?= $detail["fst_item_code"]  ." - " . $detail["fst_item_name"] ?></td>
						<td><?= $detail["fst_unit"]?> </td>
						<td class="text-right"><?= $detail["fdb_qty_distribute"] ?> </td>
						<td class="text-right"><?= date("d-M-Y",strtotime($detail["fdt_etd"]))  ?> </td>
						
					</tr>
					<tr>
						<td colspan="5" >
							<div>PR #: <?= $detail["fst_pr_no"] ?></div>							
							<?php								
								if ($detail["fst_notes"]){
									echo "<div>notes: $detail[fst_notes]</div>";
								}								
								if ($detail["fst_source_warehouse_name"]){
									echo "<div>warehouse: $detail[fst_source_warehouse_name]</div>";
								}							
							?>
						</td>
					</tr>				

					
					<?php
						$total += $detail["fdb_qty_distribute"];
					?>
				
				<?php } ?>	

			
			
				<tr class="total">					
					<td class="text-right" colspan="4">Total</td>
					<td class="text-right"><?=$total?></td>
				</tr>	

			</tbody>		
		</table>

		
	</div>	
</div>

<htmlpagefooter name="myFooter">
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
	<table width="100%">
		<tr>
			<td width="100%" align="right">Hal: {PAGENO}/{nbpg}</td>
		</tr>
	</table>
</htmlpagefooter>
<sethtmlpagefooter name="myFooter" value="on"/>
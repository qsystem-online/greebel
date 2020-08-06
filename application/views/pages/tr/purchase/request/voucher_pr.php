<?php
	//var_dump($header);
	//var_dump($details);
?>
<style>	
	.first-col{
		width:80px;
	}
	.last-col{
		width:80px;
	}
	.total{
		font-size:9pt;
	}	
	.inline{
		float:left;
	}	
</style>
<div class="container" style="width:100%">
	<div class='voucher content'S>	
		<div class="row">
			<h2><?=$title?></h2>
		</div>	
		
		<div class="row">
			<div class="col" style="width:70%">
				<div class="inline first-col">No. PR</div>			
				<div class="inline" style="width:15px">:</div>
				<div class="inline"><?=$header["fst_pr_no"]?></div>
			</div>
			<div class="col" style="width:30%">
				<div class="inline first-col">Tanggal</div>			
				<div class="inline" style="width:15px">:</div>
				<div class="inline text-right"><?= date("d-M-Y",strtotime($header["fdt_pr_datetime"]))?></div>						
			</div>
		</div>
		
		
		<div class="row">
			<div class="col" style="width:70%">
				<div class="inline first-col">Request By</div>			
				<div class="inline" style="width:15px">:</div>
				<div class="inline"><?=$header["fst_req_department_name"] ?></div>
			</div>		
		</div>

		<div class="row" style="margin-top:10px">
			<div class="col" style="text-align:justify;font-style:italic;font-size:8pt">
				<?=$header["fst_memo"]?>
			</div>
		</div>

		<!-- Detail -->
		<div class="row">
			<table class="table table-condensed" style="width:100%;padding-bottom:0px">
				<thead>
					<tr>
						<th style="width:50%">Item Code - Name</th>
						<th style="width:10%">Unit</th>
						<th style="width:20%;text-align:right">ETD</th>
						<th style="width:20%;text-align:right">Request</th>
						
					</tr>
				</thead>

				<tbody>
					<?php
						$total =0;
					?>

					<?php foreach($details as $detail){ ?>					
						<tr class="<?= $detail["fst_memo"] ? "have-detail" :"" ?>">
							<td><?= $detail["fst_item_code"]  ." - " . $detail["fst_item_name"] ?></td>
							<td><?= $detail["fst_unit"]?> </td>
							
							<td class="text-right"><?= date("d-M-Y",strtotime($detail["fdt_etd"]))  ?> </td>
							<td class="text-right"><?= $detail["fdb_qty_req"] ?> </td>
						</tr>

						<?php if ($detail["fst_memo"]) {?>
							<tr><td colspan="7" style="border-top:0px solid #000;font-style:italic"> notes: <?= $detail["fst_memo"] ?></td></tr>				
						<?php } ?>
						
						<?php
							$total += $detail["fdb_qty_req"];
						?>
					
					<?php } ?>	

				
				
					<tr class="total">					
						<td class="text-right" colspan="3">Total</td>
						<td class="text-right"><?=$total?></td>
					</tr>	

				</tbody>		
			</table>		
		</div>		
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
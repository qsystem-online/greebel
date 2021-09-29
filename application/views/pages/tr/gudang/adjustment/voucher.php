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
			<div class="inline first-col">No. Adjustment</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_adjustment_no"]?></div>
		</div>		
		<div class="col" style="width:30%">
			<div class="inline first-col2">Tanggal</div>			
			<div class="inline" style="width:15px">:</div>
			<div class="inline text-right"><?=date("d-M-Y",strtotime($header["fdt_adjustment_datetime"]))?></div>
		</div>
	</div>
	


	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col"><?= lang("Gudang")?></div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_warehouse_name"]?></div>
		</div>		
	</div>
	<div class="row">
		<div class="col" style="width:100%">
			<div class="inline first-col"><?= lang("No. Ref")?></div>
			<div class="inline" style="width:15px">:</div>
			<div class="inline"><?=$header["fst_reff"]?></div>
		</div>		
	</div>
	

	<!-- Detail -->
	<div class="row">
		<table class="table  table-condensed" style="width:100%;padding-bottom:0px">
			<thead>
				<tr>
                    <th style="width:5%">No</th>
					<th style="width:60%">Item</th>
                    <th style="width:5%">Stock</th>
					<th style="width:20%;text-align:right">Qty</th>
                    <th style="width:10%;text-align:right">Unit</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$total =0;
				?>
                <?php
                    $nou =1;
                ?>	
				<?php foreach($details as $detail){ ?>	
					<?php
						$detail = (array)$detail;
						$total += $detail["fdb_qty"];
					?>				
					<tr class="have-detail">
                        <td><?= $nou++ ?></td>
						<td><?= $detail["fst_item_name"] ?></td>
                        <td><?= $detail["fst_in_out"] ?></td>
						<td class="text-right"><?= formatNumber($detail["fdb_qty"],2) ?></td>
                        <td class="text-right"><?= $detail["fst_unit"] ?></td>
					</tr>	
					<tr class="have-detail">
						<td colspan="5"><?= $detail["fst_batch_number"] ? "Batch :" . $detail["fst_batch_number"] : "" ?></td>
					</tr>
                    <!--<tr class="have-detail">
                        <td colspan="5"><?= $detail["fst_serial_number_list"] ? "Serial :" . (str_replace( '"','',$detail["fst_serial_number_list"])) : "" ?></td>
					</tr>-->										
				<?php }?>
				<tr>
					<td class="total text-right" colspan="4"></td>
					<td class="total text-right"></td>
				</tr>
			</tbody>
            <tr>
                <td class="total text-right" colspan="4">Total</td>
                <td class="total text-right"><?= formatNumber($total,2)?></td>
            </tr>	
		</table>	
	</div>
    <div class="row">
		<div class="col" style="width:100%">
			<div class="notes"><?=$header["fst_notes"] ? "MEMO : " . $header["fst_notes"] : "" ?></div>
		</div>		
	</div>	
	
</div>

<div class="assignment">							
	<table class="assignment" style="page-break-inside: avoid;width:100%">
		<tr>
			<td style="width:30%">Dibuat,</td>					
			<td style="width:40%">Diperiksa,</td>
            <td style="width:30%">Disetujui,</td>					
		</tr>
		<tr>
			<td class="asign-col"><td>					
			<td class="asign-col"><td>
            <td class="asign-col"><td>					
		</tr>
		<tr>
			<td>.................</td>
			<td>.................</td>
            <td>.................</td>
		</tr>		
	</table>
</div>
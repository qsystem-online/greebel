<html>
	<head>
		<!-- jQuery 3 -->
		<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>        
	</head>

	<body id="bodyReport">
		<style>
			table{
				border:1px solid #000;
				
			}
			th,td{
				border:1px solid #000;
			}
			
		</style>
		<div>Laporans Stock Barang</div>
		<br>
		<div>Gudang : Gudang</div>
		<div>Tanggal: Tanggal  s/d Tanggal</div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>Group</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:50px'>Item</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:50px'>Tanggal</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>Trx Code</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>No. Transaksi</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Referensi</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Q.Awal</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>Q.Masuk</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Q.Keluar</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Q.Sisa</th>");
						echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Basic Unit</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php

					$groupItemId ="";
					$itemId ="";
					$sumPerItem = false;
					$ttlItemIn =0 ;
					$ttlItemOut =0 ;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $groupItemId != $row->fin_item_group_id){
							$groupItemId = $row->fin_item_group_id;
							echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->fst_item_group_name</td>");
						}else{
							echo "<td class='col-0'></td>";
						}

						$ttlItemIn += $row->fdb_qty_in;
						$ttlItemOut += $row->fdb_qty_out;

						if ( $itemId != $row->fin_item_id ){			
							if ($itemId != ""){
								$sumPerItem = true;
							}
							$itemId = $row->fin_item_id;	   
							echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_item_name</td>");
						}else{
							echo "<td class='col-0'></td>";
						}


						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fdt_trx_datetime</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->fst_trx_code</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->fst_trx_no</td>");
						echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->fst_referensi</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>-</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->fdb_qty_in</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->fdb_qty_out</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->fdb_qty_balance_after</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->fst_basic_unit</td>");											                                                                                                                                                                      
						echo "</tr>";

						if ($sumPerItem == true){
							if (in_array(7,$selectedCols) || in_array(8,$selectedCols) ){
								echo "<tr>";
								echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'>Total/Item</td>";
								echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
								echoIfColSelected(7,$selectedCols,"<td class='col-7'>$ttlItemIn</td>");
								echoIfColSelected(8,$selectedCols,"<td class='col-8'>$ttlItemOut</td>");
								echoIfColSelected(9,$selectedCols,"<td class='col-9'></td>");
								echoIfColSelected(10,$selectedCols,"<td class='col-10'></td>");								
								echo "</tr>";
							}
							$ttlItemIn =0;
							$ttlItemOut =0;							
						}
					}  
				?>
			</tbody>
		</table>
	</body>

	<script type="text/javascript">
		$(function(){
			//$('.col-2').remove();
			//$("#tblReport").css("display","table");

		});
		
		//$('thead tr').find('td:eq(4),th:eq(4)').remove();
		//$('tbody tr').find('td:eq(4),th:eq(4)').remove();
	</script>
</html>

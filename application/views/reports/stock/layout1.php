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
			.text-right{
				align:'right';
			}
		</style>
		<div>Laporans Stock Barang</div>
		<br>
		<div>Gudang : Gudang</div>
		<div>Tanggal: Tanggal  s/d Tanggal</div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:50px'>Tanggal</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:50px'>Trx Code</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>No.Transaksi</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>Referensi</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Q.Awal</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:80px'>Q.Masuk</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7 text-right' style='width:80px'>Q.Keluar</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8 text-right' style='width:80px'>Q.Sisa</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9 text-right' style='width:80px'>Unit</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php

					$groupItemId ="";
					$itemId ="";
					$nou = 0;
					$sumPerItem = false;
					$ttlItemIn =0 ;
					$ttlItemOut =0 ;
					$fdb_qty_in = 0;
					$fdb_qty_out = 0;
					$fdb_qty_balance_after = 0;
					$start_balance =0 ;
					$fst_item_name = "";
					foreach ($dataReport as $row){
						//if ( $itemId != $row->fin_item_id  && $itemId != ""){
							//Cetak Total
							//if (in_array(7,$selectedCols) || in_array(8,$selectedCols) ){
							//	echo "<tr>";
							//	//echo "<td colspan='".totalSelectedCol(6,$selectedCols)."' style='text-align:right'>Total/Item:".totalSelectedCol(6,$selectedCols)."</td>";
							//	echo "<td colspan='".totalSelectedCol(6,$selectedCols)."' style='text-align:right'>Total/Item </td>";
							//	echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
							//	echoIfColSelected(7,$selectedCols,"<td class='col-7'>$ttlItemIn</td>");
							//	echoIfColSelected(8,$selectedCols,"<td class='col-8'>$ttlItemOut</td>");
							//	echoIfColSelected(9,$selectedCols,"<td class='col-9'></td>");
							//	echoIfColSelected(10,$selectedCols,"<td class='col-10'></td>");								
							//	echo "</tr>";
							//}
							//$ttlItemIn =0;
							//$ttlItemOut =0;	
						//}

						//$ttlItemIn += $row->fdb_qty_in;
						//$ttlItemOut += $row->fdb_qty_out;

						if ( $groupItemId != $row->fin_item_group_id){
							$groupItemId = $row->fin_item_group_id;
							//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->fst_item_group_name</td>";
							echo "</tr>";	   
						}

						if ( $itemId != $row->fin_item_id ){
							$itemId = $row->fin_item_id;
							//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
							$fst_item_name = $row->fst_item_code .'-'. $row->fst_item_name;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: left;font-weight: bold'>$fst_item_name</td>";
							echo "</tr>";
							$nou = 0;	   
						}
						echo "<tr>";
						//if ( $groupItemId != $row->fin_item_group_id){
						//	$groupItemId = $row->fin_item_group_id;
						//	echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->fst_item_group_name</td>");
						//}else{
						//	echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
						//}

						
						//if ( $itemId != $row->fin_item_id){
						//	echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_item_name</td>");
						//	$itemId = $row->fin_item_id;
						//}else{
						//	echoIfColSelected(1,$selectedCols,"<td class='col-0'></td>");
						//}
						$nou++;
						$fdb_qty_in = formatNumber ($row->fdb_qty_in,2);
						$fdb_qty_out = formatNumber ($row->fdb_qty_out,2);
						$fdb_qty_balance_after = formatNumber ($row->fdb_qty_balance_after,2);
						$start_balance = ($row->fdb_qty_balance_after + $row->fdb_qty_out) - $row->fdb_qty_in;
						$start_balance = formatNumber ($start_balance,2);
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fdt_trx_datetime</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_trx_code</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->fst_trx_no</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->fst_referensi</td>");
						echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$start_balance</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$fdb_qty_in</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$fdb_qty_out</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8' style='text-align: right'>$fdb_qty_balance_after</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->fst_basic_unit</td>");											                                                                                                                                                                      
						echo "</tr>";	
					}

					//if (in_array(7,$selectedCols) || in_array(8,$selectedCols) ){
					//	echo "<tr>";
					//	echo "<td colspan='".totalSelectedCol(6,$selectedCols)."' style='text-align:right'>Total/Item</td>";
					//	echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
					//	echoIfColSelected(7,$selectedCols,"<td class='col-7'>$ttlItemIn</td>");
					//	echoIfColSelected(8,$selectedCols,"<td class='col-8'>$ttlItemOut</td>");
					//	echoIfColSelected(9,$selectedCols,"<td class='col-9'></td>");
					//	echoIfColSelected(10,$selectedCols,"<td class='col-10'></td>");								
					//	echo "</tr>";
					//}


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

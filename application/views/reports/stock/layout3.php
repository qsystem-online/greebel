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
		<div>MUTASI PERSEDIAAN</div>
		<br>
		<div>Gudang : Gudang</div>
		<div>Tanggal: Tanggal  s/d Tanggal</div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:50px'>Kode Item</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Nama Item</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>Q.Awal</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>Q.Masuk</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Q.Keluar</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:50px'>Q.Sisa</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7 text-right' style='width:50px'>Unit</th>");		
					?>
				</tr>
			</thead>
			<tbody>
				<?php

					$groupItemId ="";
					$itemId ="";
					$nou = 0;
					$sumPerItem = false;
					$ttlItemIn = 0;
                    $ttlItemOut = 0;
                    $fdc_jumlah = 0;
					$newItem = true;
					foreach ($dataReport as $row){
						if ( $groupItemId != $row->fin_item_group_id){
							$groupItemId = $row->fin_item_group_id;
							//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->fst_item_group_name</td>";
                            echo "</tr>";
                            $nou = 0;	   
						}


						echo "<tr>";
                        $nou++;
                        $fdb_qty_in = formatNumber ($row->fdb_qty_in,2);
						$fdb_qty_out = formatNumber ($row->fdb_qty_out,2);
                        $fdc_jumlah = formatNumber ($fdc_jumlah,2);
                        $fdb_qty_balance_after = $row->fdb_qty_in - $row->fdb_qty_out;
                        $fdb_qty_balance_after = formatNumber ($fdb_qty_balance_after,2);
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_item_code</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_item_name</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'>-</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'style='text-align: right'>$fdb_qty_in</td>");
						echoIfColSelected(5,$selectedCols,"<td class='col-5'style='text-align: right'>$fdb_qty_out</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$fdb_qty_balance_after</td>"); 
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->fst_basic_unit</td>"); 										                                                                                                                                                                      
						echo "</tr>";	
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

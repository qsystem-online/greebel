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
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>Q.Sisa</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>Unit</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Harga Jual</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$nou = 0;
                    $fdc_price_list = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						$nou++;
						$end_balance = formatNumber($row->end_balance,2);
                        //$fdb_qty_in = formatNumber ($row->fdb_qty_in,2);
						//$fdb_qty_out = formatNumber ($row->fdb_qty_out,2);
						$fdc_price_list = formatNumber ($row->fdc_price_list,2);
                        //$fdb_qty_balance_after = ($row->start_balance + $row->fdb_qty_in) - $row->fdb_qty_out;
						//$fdb_qty_balance_after = formatNumber ($fdb_qty_balance_after,2);
						if ($row->fst_basic_unit == null ){
							$fst_basic_unit = '???';
						}else{
							$fst_basic_unit = $row->fst_basic_unit;
						}
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_item_code</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_item_name</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'style='text-align: right'>$end_balance</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'style='text-align: right'>$fst_basic_unit</td>");
						echoIfColSelected(5,$selectedCols,"<td class='col-5'style='text-align: right'>$fdc_price_list</td>");										                                                                                                                                                                      
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

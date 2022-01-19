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
			th{
				border:1px solid #000;
			}
            td{
				border:1px solid;
				border-color: rgb(0,0,255,0.25);
			}		
		</style>
		<div>LAPORAN HASIL PRODUKSI(LHP)</div>
		<br>
		<div>Tanggal data LHP: <?= $this->input->post("fdt_datetime") ?>  s/d <?= $this->input->post("fdt_datetime2") ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1700px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>Tanggal</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>No.LHP</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>No.WO</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:200px'>Memo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:200px'>Nama Barang</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Qty LHP(Dasar)</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Unit</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Qty LHP</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Unit</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Sisa Target</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					$ttl_QtyDasar = 0;
                    $ttl_QtyLhp = 0;
					$ttl_QtySisa = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        $nou++;
						echo "<tr>";
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Lhp_Date</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Lhp_No</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Wo_No</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->fst_notes</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Code_Product</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Name_Product</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$row->Qty_Lhp</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Unit_Product</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$row->Qty_Lhp</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Unit_Product</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$row->fdb_qty_sisa</td>");										                                                                                                                                                                      
                        echo "</tr>";
                        $ttl_QtyDasar += $row->Qty_Lhp;
						$ttl_QtyLhp += $row->Qty_Lhp;
                        $ttl_QtySisa += $row->fdb_qty_sisa;
					}
                    /*$ttl_QtyDasar_New += $ttl_QtyDasar;
                    $ttl_QtyDasar_New = formatNumber ($ttl_QtyDasar_New, 2);
					$ttl_totalNew += $ttl_total;
					$ttl_totalNew = formatNumber ($ttl_totalNew, 2);*/

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: right;font-weight: bold'>Total : </td>";
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$ttl_QtyDasar</td>");
                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>-</td>");
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$ttl_QtyLhp</td>");
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'></td>");
					echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_QtySisa</td>");									
					echo "</tr>";
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

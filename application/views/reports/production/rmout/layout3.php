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
		<div>LAPORAN RM-OUT</div>
		<br>
		<div>Tanggal edit RM-OUT: <?= $this->input->post("fdt_datetime") ?>  s/d <?= $this->input->post("fdt_datetime2") ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1400px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Tanggal</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>No.RMOUT</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>No.WO</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>Kode Barang</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:300px'>Nama Barang</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Qty</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:30px'>Unit</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        $nou++;
						echo "<tr>";
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Rmout_Date</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Rmout_No</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Wo_No</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Code_Bahan</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Name_Bahan</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$row->Qty_Bahan</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Unit_Bahan</td>");									                                                                                                                                                                      
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

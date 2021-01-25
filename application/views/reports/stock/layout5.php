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
		<?php
		$fin_warehouse_id = $this->input->post("fin_warehouse_id");
		$this->load->model("mswarehouse_model");
		$data = $this->mswarehouse_model->getDataById($fin_warehouse_id);
		$wareHouse = $data ["warehouse"];
		if ($wareHouse != null){
			$name_wareHouse = $wareHouse->fst_warehouse_name;
		}else{
			$name_wareHouse = "ALL";
		}
		?>
		<?php
		$start_date = $this->input->post("fdt_from");
		if ($start_date != null){
			$start_date = $start_date;
		}else{
			$start_date = "1900-01-01";
		}
		?>
		<?php
		$end_date = $this->input->post("fdt_to");
		if ($end_date != null){
			$end_date = $end_date;
		}else{
			$end_date = "3000-01-01";
		}
		?>
		<div>Gudang : <?= $name_wareHouse ?></div>
		<div>Tanggal: <?= $start_date ?>  s/d <?= $end_date ?></div>                             
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
                        echoIfColSelected(8,$selectedCols,"<th class='col-8 text-right' style='width:50px'>Nilai</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9 text-right' style='width:50px'>Jumlah</th>");			
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
                        echo "<tr>";
                        $nou++;
                        $start_balance = formatNumber($row->start_balance,2);
                        $fdb_qty_in = formatNumber ($row->fdb_qty_in,2);
						$fdb_qty_out = formatNumber ($row->fdb_qty_out,2);
                        $end_balance = ($row->start_balance + $row->fdb_qty_in) - $row->fdb_qty_out;
                        $end_balance = formatNumber ($end_balance,2);
                        if ($row->fdc_avg_cost_last == null){
                            $fdc_avg_cost = $row->fdc_avg_cost_start;
                        }else{
                            $fdc_avg_cost = $row->fdc_avg_cost_last;
                        }
                        $fdc_jumlah = ((($row->start_balance + $row->fdb_qty_in) - $row->fdb_qty_out)* $fdc_avg_cost);
                        $fdc_jumlah = formatNumber ($fdc_jumlah,2);
                        $fdc_avg_cost = formatNumber($fdc_avg_cost,2);
						if ($row->fst_basic_unit == null ){
							$fst_basic_unit = '???';
						}else{
							$fst_basic_unit = $row->fst_basic_unit;
						}
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_item_code</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_item_name</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'style='text-align: right'>$start_balance</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'style='text-align: right'>$fdb_qty_in</td>");
						echoIfColSelected(5,$selectedCols,"<td class='col-5'style='text-align: right'>$fdb_qty_out</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$end_balance</td>"); 
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'>$fst_basic_unit</td>");
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$fdc_avg_cost</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_jumlah</td>");  										                                                                                                                                                                      
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

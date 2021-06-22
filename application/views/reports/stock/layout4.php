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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1100px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:10px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:30px'>Kode Item</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:250px'>Nama Item</th>");
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
						$start_balance = formatNumber($row->start_balance,2);
                        $fdb_qty_in = formatNumber ($row->fdb_qty_in,2);
						$fdb_qty_out = formatNumber ($row->fdb_qty_out,2);
                        $end_balance = ($row->start_balance + $row->fdb_qty_in) - $row->fdb_qty_out;
						$end_balance = formatNumber ($end_balance,2);
						$fdc_price_list = formatNumber ($row->fdc_price_list,2);
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

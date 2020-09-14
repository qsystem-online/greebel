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
		<div>LAPORAN S/O RINGKAS & STATUS OUTSTANDING S/J</div>
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
		$fin_relation_id = $this->input->post("fin_relation_id");
		if ($fin_relation_id >"0"){
			$this->load->model("msrelations_model");
			$data = $this->msrelations_model->getDataById($fin_relation_id);
			$ms_relations = $data ["ms_relations"];
			$name_relations = $ms_relations->fst_relation_name;
		}else{
			$name_relations = "ALL";
		}
		?>
		<?php
		$fin_sales_id = $this->input->post("fin_sales_id");
		$this->load->model('users_model');
		$data = $this->users_model->getDataById($fin_sales_id);
		$user = $data ["user"];
		if ($user != null){
			$name_sales = $user->fst_username;
		}else{
			$name_sales = "ALL";
		}
		?>
		<div>Gudang : <?= $name_wareHouse ?></div>
		<div>Tanggal S/O: <?= $this->input->post("fdt_salesorder_datetime") ?>  s/d <?= $this->input->post("fdt_salesorder_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
        <div>Sales: <?= $name_sales ?></div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1700px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>GUD</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:300px'>Customer</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Sales</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>No.S/O</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Tgl S/O</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:300px'>Nama Barang</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:70px'>Qty S/O</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'></th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>No.S/J</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Tgl S/J</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:70px'>Qty S/J</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:70px'>Qty O/S</th>");
                        //echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:100px'>DP</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					$id_SO = "";
					$noSO = "";
					$no_sj = "";
					$id_detailSO = "";
					$qty_sj = 0;
					$ttl_qty_sj = 0;
					$ttl_qty_os = 0;
					$cetakTotal = false;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){

						if ($id_detailSO != $row->ID_DetailSO){
							if ($id_detailSO != ""){
								echo "<tr>";
							//echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: right;font-weight: bold'></td>";
								//echo "<td colspan='".totalSelectedCol(10,$selectedCols)."' style='text-align: right;font-weight: bold'>Total</td>";
								echoIfColSelected(11,$selectedCols,"<td colspan='".totalSelectedCol(10,$selectedCols)."' style='text-align: right;font-weight: bold'>Total</td>");
								echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>");
								echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>");								

								//echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>";									
								//echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>";
								//echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$ttl_qty_sj</td>");
								//echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_qty_os</td>");							
								echo "</tr>";
								$ttl_qty_sj = 0;	
							}
						}

						echo "<tr>";
                        if ( $id_SO != $row->Id_SO ){
							$id_SO = $row->Id_SO;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0''border-25'>$row->Warehouse</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Relation_Name</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Sales_Name</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->No_SO</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->SO_Date</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
						}

						if ($id_detailSO != $row->ID_DetailSO){
							//$ttl_qty_sj += $row->qty_sj;
							$id_detailSO = $row->ID_DetailSO;
							echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Item_Name</td>");
							echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Qty</td>");
							echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Unit</td>");	
						}else{
							echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
							echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
							echoIfColSelected(7,$selectedCols,"<td class='col-7'></td>");	
						}
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->fst_sj_no</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->fdt_sj_datetime</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->qty_sj</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10'>0</td>");
						echo "</tr>";
						$ttl_qty_sj += $row->qty_sj;
						$ttl_qty_os = $row->Qty - $ttl_qty_sj;

					}   
					$ttl_qty_sj += $row->qty_sj;
					$ttl_qty_os = $row->Qty - $ttl_qty_sj;

					/*
					echo "<tr>";
					echo "<td colspan='10' style='text-align: right;font-weight: bold'>Total</td>";
					echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>";									
					echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>";
					echo "</tr>";*/

					echo "<tr>";
					echoIfColSelected(11,$selectedCols,"<td colspan='".totalSelectedCol(10,$selectedCols)."' style='text-align: right;font-weight: bold'>Total</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>");															
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

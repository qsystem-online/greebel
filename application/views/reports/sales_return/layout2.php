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
		<div>LAPORAN RETUR PENJUALAN RINGKAS</div>
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
		<div>Gudang    : <?= $name_wareHouse ?></div>
		<div>Tgl Return: <?= $this->input->post("fdt_salesreturn_datetime") ?>  s/d <?= $this->input->post("fdt_salesreturn_datetime2") ?></div> 
        <div>Customer  : <?= $name_relations ?> </div>
        <div>Sales     : <?= $name_sales ?></div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.Retur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl Retur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>M.U</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:400px'>Pelanggan/Customer</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Subtotal</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Discount</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Potongan</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>Total</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					$subttl = 0;
					$disc_total = 0;
					$disc_totalNew = 0;
					$potongan_total = 0;
					$potongan_totalNew = 0;
					$ttl_total = 0;
					$subttlNew = 0;
					$ttl_totalNew = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        $nou++;
						echo "<tr>";
                        $fdc_subttl = formatNumber ($row->fdc_subttl, 2);
						$fdc_disc_amount = formatNumber ($row->Disc_Amount, 2);
						$fdc_potongan = formatNumber ($row->fdc_potongan, 2);
						$fdc_total = formatNumber ($row->fdc_total, 2);
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Retur</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Retur_Date</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Mata_Uang</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Relation_Name</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'style='text-align: right'>$fdc_subttl</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$fdc_disc_amount</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$fdc_potongan</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$fdc_total</td>");										                                                                                                                                                                      
                        echo "</tr>";
						$subttl += $row->fdc_subttl;
						$disc_total += $row->Disc_Amount;
						$potongan_total += $row->fdc_potongan;
						$ttl_total += $row->fdc_total;
					}
                    $subttlNew += $subttl;
					$subttlNew = formatNumber ($subttlNew, 2);
					$disc_totalNew += $disc_total;
					$disc_totalNew = formatNumber ($disc_totalNew, 2);
					$potongan_totalNew += $potongan_total;
                    $potongan_totalNew = formatNumber ($potongan_totalNew, 2);
					$ttl_totalNew += $ttl_total;
					$ttl_totalNew = formatNumber ($ttl_totalNew, 2); 

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(5,$selectedCols)."'style='text-align: right;font-weight: bold'>Total : </td>";
					echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$subttlNew</td>");
					echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$disc_totalNew</td>");
					echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$potongan_totalNew</td>");
					echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");									
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

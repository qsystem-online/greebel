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
		<div>LAPORAN SALES ORDER RINGKAS</div>
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
		<table id="tblReport" cellpadding="0" cellspacing="0">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.S/O</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl S/O</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>TOP</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>GUD</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Sales</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:300px'>Customer</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:150px'>Sub Total</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>Diskon</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:150px'>Dpp</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Ppn</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Total</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:100px'>DP</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					$subttl = 0;
					$ttl_disc_amount = 0;
					$ttl_dpp_amount = 0;
					$ttl_vat_amount = 0;
					$ttl_total = 0;
					$ttl_downpayment = 0;
					$subttlNew = 0;
					$ttl_disc_amountNew = 0;
					$ttl_dpp_amountNew = 0;
					$ttl_vat_amountNew = 0;
					$ttl_totalNew = 0;
					$ttl_downpaymentNew = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        $nou++;
						echo "<tr>";
						$fdc_subttl = number_format ($row->fdc_subttl, 2, '.', ',');
						$fdc_disc_amount = number_format ($row->fdc_disc_amount, 2, '.', ',');
						$fdc_dpp_amount = number_format ($row->fdc_dpp_amount, 2, '.', ',');
						$fdc_vat_amount = number_format ($row->fdc_vat_amount, 2, '.', ',');
						$fdc_total = number_format ($row->fdc_total, 2, '.', ',');
						$fdc_downpayment = number_format ($row->fdc_downpayment, 2, '.', ',');
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_SO</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->SO_Date</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->TOP</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Warehouse</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Sales_Name</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Relation_Name</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$fdc_subttl</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$fdc_disc_amount</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_dpp_amount</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$fdc_vat_amount</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$fdc_total</td>");
                        echoIfColSelected(12,$selectedCols,"<td class='col-12' style='text-align: right'>$fdc_downpayment</td>");											                                                                                                                                                                      
                        echo "</tr>";
                        $subttl += $row->fdc_subttl;
						$ttl_disc_amount += $row->fdc_disc_amount;
						$ttl_dpp_amount += $row->fdc_dpp_amount;
						$ttl_vat_amount += $row->fdc_vat_amount;
						$ttl_total += $row->fdc_total;
						$ttl_downpayment += $row->fdc_downpayment;

					}
                    $subttlNew += $subttl;
                    $subttlNew = number_format ($subttlNew, 2, '.', ',');
                    $ttl_disc_amountNew += $ttl_disc_amount;
					$ttl_disc_amountNew = number_format ($ttl_disc_amountNew, 2, '.', ',');
					$ttl_dpp_amountNew += $ttl_dpp_amount;
					$ttl_dpp_amountNew = number_format ($ttl_dpp_amountNew, 2, '.', ',');
					$ttl_vat_amountNew += $ttl_vat_amount;
                    $ttl_vat_amountNew = number_format ($ttl_vat_amountNew, 2, '.', ',');
					$ttl_totalNew += $ttl_total;
					$ttl_totalNew = number_format ($ttl_totalNew, 2, '.', ',');
					$ttl_downpaymentNew += $ttl_downpayment;
					$ttl_downpaymentNew = number_format ($ttl_downpaymentNew, 2, '.', ',');   

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: right;font-weight: bold'>Total : </td>";
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$subttlNew</td>");
                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$ttl_disc_amountNew</td>");
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$ttl_dpp_amountNew</td>");
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$ttl_vat_amountNew</td>");
					echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
					echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$ttl_downpaymentNew</td>");									
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

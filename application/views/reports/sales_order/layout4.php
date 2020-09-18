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
		<div>LAPORAN LOST OF SALES</div>
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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1500px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>GUD</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:300px'>Customer</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Sales</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:70px'>No.S/O</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:70px'>Tgl S/O</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:300px'>Nama Barang</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:70px'>Qty O/S</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:70px'>Unit</th>");
                        echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>Harga Netto</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Jumlah</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $id_SO = "";
                    $Harga_Netto = 0;
                    $Amount = 0;
					$sub_Qty_OS = 0;
					$subAmount = 0;
					$total_Qty_OS = 0;
					$totalAmount = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
						echo "<tr>";
                        if ( $id_SO != $row->Id_SO ){
                            if ($id_SO != "") {
								//akumulasi total keseluruhan                            
								$total_Qty_OS += $sub_Qty_OS;
                                $totalAmount += $subAmount;
                                $newtotalAmount = formatNumber($totalAmount,2);                               
                                //tulis subtotal per-SO
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-S/O :</td>";
                                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$newsub_Qty_OS</td>");
                                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'></td>");
                                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'></td>");
                                    echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$newsubAmount</td>");							
                                    echo "</tr>";

								//reset subtotal variable (break SO)
								$sub_Qty_OS = 0;
								$subAmount = 0;
							}
							$id_SO = $row->Id_SO;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Warehouse</td>");	   
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
                        $Harga_Netto = formatNumber ($row->Harga_Netto,2);
                        $Amount = formatNumber ($row->Amount,2);
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Item_Name</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$row->Qty_OS</td>");
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Unit</td>");
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$Harga_Netto</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$Amount</td>");
                        echo "</tr>";
                        $sub_Qty_OS += $row->Qty_OS;
                        $newsub_Qty_OS = formatNumber ($sub_Qty_OS,2);
                        $subAmount += $row->Amount;
                        $newsubAmount = formatNumber ($subAmount,2);

                    }
                    $total_Qty_OS += $sub_Qty_OS; 
                    $newtotal_Qty_OS = formatNumber ($total_Qty_OS,2);  
					$totalAmount += $subAmount;
                    $newtotalAmount = formatNumber ($totalAmount,2);
                    
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-S/O :</td>";
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$newsub_Qty_OS</td>");
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$newsubAmount</td>");							
                    echo "</tr>";

                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan :</td>";
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$newtotal_Qty_OS</td>");
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$newtotalAmount</td>");							
                    echo "</tr>";

					/*echo "<tr>";
					echoIfColSelected(11,$selectedCols,"<td colspan='".totalSelectedCol(10,$selectedCols)."' style='text-align: right;font-weight: bold'>Total</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>");															
					echo "</tr>";*/

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

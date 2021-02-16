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
		<div>LAPORAN SALDO PIUTANG FAKTUR PENJUALAN</div>
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
		<div>Tgl Faktur: <?= $this->input->post("fdt_inv_datetime") ?>  s/d <?= $this->input->post("fdt_inv_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
        <div>Sales: <?= $name_sales ?></div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2000px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:100px'>Sales</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:300px'>Pelanggan/Customer</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>No.Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Tgl Faktur</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Jatuh Tempo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>No.S/J</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>No.S/O</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>GUD</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>M.U</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:150px'>Nilai Faktur</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Total Retur</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:150px'>Pembayaran</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:100px'>Nilai Netto</th>");
                        echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:50px'>Menunggak(Hr)</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $Id_Sales ="";
                    $Id_Relation ="";
					$subttl_total = 0;
					$subttl_return = 0;
					$subttl_paid = 0;
                    $subttl_piutang = 0;
                    $subttl_totalNew = 0;
					$subttl_returnNew = 0;
					$subttl_paidNew = 0;
					$subttl_piutangNew = 0;
					$subttlNew = 0;
					$ttl_totalNew = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        if ($Id_Relation != $row->Relation_Id){
							if ($Id_Relation != ""){
                                echo "<tr>";
                                echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-Customer: </td>";
                                echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$subttl_totalNew</td>");
                                echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$subttl_returnNew</td>");
                                echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$subttl_paidNew</td>");
                                echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$subttl_piutangNew</td>");
                                echoIfColSelected(13,$selectedCols,"<td class='col-13'></td>");										
                                echo "</tr>";
                                //reset subtotal variable (break group)
                                $subttl_total = 0;
                                $subttl_return = 0;
                                $subttl_paid = 0;
                                $subttl_piutang = 0;
                                $subttl_totalNew = 0;
                                $subttl_returnNew = 0;
                                $subttl_paidNew = 0;
                                $subttl_piutangNew = 0;	
							}
						}
                        echo "<tr>";
                        if ( $Id_Sales != $row->Sales_Id ){
                            $Id_Sales = $row->Sales_Id;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0' style='font-weight: bold;text-align: left'>$row->Sales_Name</td>");	   
                        }else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                        }

                        if ($Id_Relation != $row->Relation_Id){
                            $Id_Relation = $row->Relation_Id;
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Relation_Name</td>");
                        }else{
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                        }
                        $fdc_total_return = formatNumber ($row->fdc_total_return, 2);
                        $fdc_total_paid = formatNumber ($row->fdc_total_paid, 2);
                        $fdc_total = formatNumber ($row->fdc_total, 2);
                        $Saldo_Piutang = formatNumber ($row->Saldo_Piutang, 2);
                        if ($row->Menunggak_Hari <= 0){
                            $row->Menunggak_Hari = "Belum J/T";
                        }
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->No_Inv</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Inv_Date</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Jt_Date</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_SJ</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->No_SO</td>");
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Warehouse</td>");
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Mata_Uang</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_total</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$fdc_total_return</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$fdc_total_paid</td>");
                        echoIfColSelected(12,$selectedCols,"<td class='col-12'style='text-align: right'>$Saldo_Piutang</td>");
                        echoIfColSelected(13,$selectedCols,"<td class='col-13'style='text-align: center'>$row->Menunggak_Hari</td>");											                                                                                                                                                                      
                        echo "</tr>";
                        $subttl_total += $row->fdc_total;
                        $subttl_totalNew = formatNumber ($subttl_total, 2);
                        $subttl_return += $row->fdc_total_return;
                        $subttl_returnNew = formatNumber ($subttl_return, 2);
                        $subttl_paid += $row->fdc_total_paid;
                        $subttl_paidNew = formatNumber ($subttl_paid, 2);
                        $subttl_piutang += $row->Saldo_Piutang;
                        $subttl_piutangNew = formatNumber ($subttl_piutang, 2);
                        
					}
                    /*$subttlNew += $subttl;
                    $subttlNew = number_format ($subttlNew, 2, '.', ',');
					$ttl_totalNew += $ttl_total;
					$ttl_totalNew = number_format ($ttl_totalNew, 2, '.', ','); 

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Total : </td>";
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$subttlNew</td>");
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
                    echoIfColSelected(12,$selectedCols,"<td class='col-12'style='text-align: right'>$Saldo_Piutang</td>");
                    echoIfColSelected(13,$selectedCols,"<td class='col-13'style='text-align: right'>$Menunggak_Hari</td>");										
					echo "</tr>";*/

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-Customer: </td>";
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$subttl_totalNew</td>");
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$subttl_returnNew</td>");
					echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$subttl_paidNew</td>");
					echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$subttl_piutangNew</td>");
					echoIfColSelected(13,$selectedCols,"<td class='col-13'></td>");										
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

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
		<div>LAPORAN FAKTUR PENJUALAN DETAIL</div>
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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2300px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:300px'>Pelanggan/Customer</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.Faktur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>TOP</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Jatuh Tempo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>No.S/O</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>GUD</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Sales</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:200px'>Memo</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Kode Barang</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:400px'>Nama Barang</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:50px'>Qty</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:50px'>Unit</th>");
                        echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:50px'>Disc%</th>");
                        echoIfColSelected(14,$selectedCols,"<th class='col-14' style='width:100px'>Harga</th>");
                        echoIfColSelected(15,$selectedCols,"<th class='col-15' style='width:150px'>Jumlah</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$idInv = "";
					$subAmount = 0;
					$subDiscount = 0;
					$totalDiscount = 0;
					$totalAmount = 0;
					$fdc_subttl = 0;
					$Disc_Total = 0;
					$Dpp = 0;
					$Ppn = 0;
					$fdc_total = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idInv != $row->fin_inv_id ){
                            if ($idInv != "") {
								//akumulasi total keseluruhan                                                        
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total :</td>";
                                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$fdc_subttl</td>");							
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Discount :</td>";
                                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$Disc_Total</td>");							
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Dpp :</td>";
                                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$Dpp</td>");							
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Ppn :</td>";
                                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$Ppn</td>");							
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Nilai Total :</td>";
                                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$fdc_total</td>");							
                                    echo "</tr>";

								//reset subtotal variable (break group)
								$subAmount = 0;
							}
			
                            $idInv = $row->fin_inv_id;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Inv</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Inv_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->TOP</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Jt_Date</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_SO</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Warehouse</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Sales_Name</td>");
                            echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Inv_Memo</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'></td>");
							echoIfColSelected(8,$selectedCols,"<td class='col-8'></td>");
							
                        }
                        $Price_Netto = formatNumber ($row->Price_Netto,2);
                        $Amount = formatNumber ($row->Amount,2);
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->Item_Code</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Item_Name</td>");
						echoIfColSelected(11,$selectedCols,"<td class='col-11'>$row->Qty</td>");
                        echoIfColSelected(12,$selectedCols,"<td class='col-12'>$row->Unit</td>");
                        echoIfColSelected(13,$selectedCols,"<td class='col-13'>$row->Disc_Item</td>");
                        echoIfColSelected(14,$selectedCols,"<td class='col-14' style='text-align: right'>$Price_Netto</td>");
                        echoIfColSelected(15,$selectedCols,"<td class='col-15' style='text-align: right'>$Amount</td>");											                                                                                                                                                                      
                        echo "</tr>";
                        $subAmount += $row->Amount;
						$subAmountNew = formatNumber ($subAmount,2);
						
						$totalDiscount += $subDiscount;
						$totalAmount += $subAmount;
						$newtotalAmount = formatNumber($totalAmount,2);
						$fdc_subttl = formatNumber ($row->fdc_subttl,2);
						$Disc_Total = formatNumber ($row->Disc_Total,2);
						$Dpp = formatNumber ($row->Dpp,2);
						$Ppn = formatNumber ($row->Ppn,2);
						$fdc_total = formatNumber ($row->fdc_total,2);
					}
					
					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total :</td>";
					echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$fdc_subttl</td>");							
					echo "</tr>";

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Discount :</td>";
					echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$Disc_Total</td>");							
					echo "</tr>";

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Dpp :</td>";
					echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$Dpp</td>");							
					echo "</tr>";

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Ppn :</td>";
					echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$Ppn</td>");							
					echo "</tr>";

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Nilai Total :</td>";
					echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$fdc_total</td>");							
					echo "</tr>";

                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan : </td>";
                    echoIfColSelected(15,$selectedCols,"<td class='col-15'style='font-weight: bold;text-align: right'>$newtotalAmount</td>");							
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

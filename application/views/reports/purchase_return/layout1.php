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
		<div>LAPORAN PURCHASE RETURN DETAIL</div>
		<br>
		<?php
		$fin_branch_id = $this->input->post("fin_branch_id");
        $this->load->model("msbranches_model");
		$branch = $this->msbranches_model->getBranchById($fin_branch_id);
		if ($branch != null){
			$name_branch = $branch->fst_branch_name;
		}else{
			$name_branch = "ALL";
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
		<div>Branch : <?= $name_branch ?></div>
		<div>Tgl Retur: <?= $this->input->post("fdt_purchasereturn_datetime") ?>  s/d <?= $this->input->post("fdt_purchasereturn_datetime2") ?></div> 
        <div>Supplier: <?= $name_relations ?> </div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1800px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.Retur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl Retur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:150px'>No.LPB</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:300px'>Supplier</th>");
                        echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>M.U</th>");
                        echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Kurs</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:250px'>Nama Barang</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Qty</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Unit</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:50px'>Harga</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:50px'>Disc Amt</th>");
                        echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:50px'>Jumlah</th>");
                        echoIfColSelected(14,$selectedCols,"<th class='col-14' style='width:100px'>Jumlah IDR</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $idRetur = "";
					$idSupplier = "";
					$nou = 0;
					$subAmount = 0;
					$subAmount_Idr = 0;
					$subDiscount = 0;
					$totalDiscount = 0;
                    $totalAmount = 0;
                    $totalAmount_Idr = 0;
					$fdc_subttl = 0;
					$Disc_Total = 0;
					$fdc_total = 0;
					$fdc_total_Idr = 0;
                    $qty_subttl = 0;
					$qty_total = 0;
					$qty_totalNew = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idRetur != $row->fin_purchasereturn_id ){
                            if ($idRetur != "") {
								//akumulasi total keseluruhan                                                        
								//tulis subtotal per-group
                                echo "<tr>";
								echo "<td colspan='".totalSelectedCol(12,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal Per-Retur :</td>";
								echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$subDiscountNew</td>");
								echoIfColSelected(13,$selectedCols,"<td class='col-13' style='font-weight: bold;text-align: right'>$subAmountNew</td>");
								echoIfColSelected(14,$selectedCols,"<td class='col-14' style='font-weight: bold;text-align: right'>$subAmountNew_Idr</td>");						
                                echo "</tr>";
								//reset subtotal variable (break group)
								$subDiscount = 0;
								$subAmount = 0;
								$subAmount_Idr = 0;
                                $qty_subttl = 0;

                            }			
                            $idRetur = $row->fin_purchasereturn_id;
							$nou++;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Retur</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Retur_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->No_LPB</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Relation_Name</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Mata_Uang</td>");
							echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Rate_Idr</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
							
                        }
                        $Price = formatNumber ($row->Price,2);
                        $Price_Netto = formatNumber ($row->Price_Netto,2);
                        $Disc_Amount = formatNumber ($row->Disc_Amount,2);
						$Amount = formatNumber ($row->Amount,2);
						$Amount_Idr = formatNumber ($row->Amount_Idr,2);
                        $Rate_Idr = formatNumber ($row->Rate_Idr,2);
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Item_Code</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Item_Name</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9' style='text-align: right'>$row->Qty</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Unit</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11' style='text-align: right'>$Price</td>");
                        echoIfColSelected(12,$selectedCols,"<td class='col-12' style='text-align: right'>$Disc_Amount</td>");
                        echoIfColSelected(13,$selectedCols,"<td class='col-13' style='text-align: right'>$Amount</td>");
                        echoIfColSelected(14,$selectedCols,"<td class='col-14' style='text-align: right'>$Amount_Idr</td>");											                                                                                                                                                                      
						echo "</tr>";
						
						$subDiscount += $row->Disc_Amount;
						$subDiscountNew = formatNumber ($subDiscount,2);
                        $subAmount += $row->Amount;
						$subAmountNew = formatNumber ($subAmount,2);
						$subAmount_Idr += $row->Amount_Idr;
                        $subAmountNew_Idr = formatNumber ($subAmount_Idr,2);
						$qty_subttl += $row->Qty;
						$qty_subttlNew = formatNumber ($qty_subttl,2);

						$totalDiscount += $row->Disc_Amount;
						$newtotalDiscount = formatNumber($totalDiscount,2);
						$totalAmount += $row->Amount;
						$newtotalAmount = formatNumber($totalAmount,2);
						$totalAmount_Idr += $row->Amount_Idr;
						$newtotalAmount_Idr = formatNumber($totalAmount_Idr,2);
						
					}

					$fdc_subttl = formatNumber ($row->fdc_subttl,2);
					$fdc_subttl_Idr = formatNumber ($row->fdc_subttl_Idr,2);
					$Disc_Total = formatNumber ($row->Disc_Total,2);
					$fdc_total = formatNumber ($row->fdc_total,2);
					$fdc_total_Idr = formatNumber ($row->fdc_total_Idr,2);
					$qty_total += $row->Qty;
					$qty_totalNew = formatNumber ($qty_total,2);

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(12,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal Per-Retur :</td>";
					echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$subDiscountNew</td>");
					echoIfColSelected(13,$selectedCols,"<td class='col-13' style='font-weight: bold;text-align: right'>$subAmountNew</td>");
					echoIfColSelected(14,$selectedCols,"<td class='col-14' style='font-weight: bold;text-align: right'>$subAmountNew_Idr</td>");						
					echo "</tr>";
                    
					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(12,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan :</td>";
					echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$newtotalDiscount</td>");	
					echoIfColSelected(13,$selectedCols,"<td class='col-13' style='font-weight: bold;text-align: right'>$newtotalAmount</td>");
					echoIfColSelected(14,$selectedCols,"<td class='col-14' style='font-weight: bold;text-align: right'>$newtotalAmount_Idr</td>");				
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

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
		<div>LAPORAN ITEM PURCHASE ORDER O/S PENERIMAAN</div>
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
		<div>Gudang : <?= $name_wareHouse ?></div>
		<div>Tgl PO: <?= $this->input->post("fdt_po_datetime") ?>  s/d <?= $this->input->post("fdt_po_datetime2") ?></div> 
        <div>Supplier: <?= $name_relations ?> </div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2300px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:250px'>Supplier</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.P/O</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl P/O</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>TOP</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:150px'>No.DO</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:150px'>No.Kontrak</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>GUD</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:250px'>Nama Barang</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Qty</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Unit</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:50px'>Harga</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:50px'>Disc Amt</th>");
                        echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:50px'>Jumlah</th>");
                        echoIfColSelected(14,$selectedCols,"<th class='col-14' style='width:50px'>Kurs</th>");
                        echoIfColSelected(15,$selectedCols,"<th class='col-15' style='width:150px'>Jumlah kurs</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $idPO = "";
                    $idSupplier = "";
					$subAmount = 0;
					$subDiscount = 0;
					$totalDiscount = 0;
					$totalAmount = 0;
					$fdc_subttl = 0;
					$Disc_Total = 0;
					$Dpp = 0;
					$Ppn = 0;
                    $fdc_total = 0;
                    $qty_subttl = 0;
                    $qty_total = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idPO != $row->fin_po_id ){
                            if ($idPO != "") {
								//akumulasi total keseluruhan                                                        
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total Per-PO:</td>";
                                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$qty_subttl</td>");
                                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$fdc_subttl</td>");							
                                    echo "</tr>";

								//reset subtotal variable (break group)
                                $subAmount = 0;
                                $qty_subttl = 0;
							}			
                            $idPO = $row->fin_po_id;

                            if ( $idSupplier != $row->fin_supplier_id ){
                                if ($idSupplier != "") {
                                        echo "<tr>";
                                        echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right'>Total Per-Supplier</td>";
                                        echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$qty_total</td>");
                                        echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'></td>");						
                                        echo "</tr>";

                                        $qty_total= 0;
                                }
                                $idSupplier = $row->fin_supplier_id;
                                echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");	   
                            }else{
                                echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            }
                            	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_PO</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->PO_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->TOP</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->No_DO</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_Kontrak</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Warehouse</td>");
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
                        $Rate_Idr = formatNumber ($row->Rate_Idr,2);
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Item_Code</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Item_Name</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->Qty</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Unit</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'>$Price</td>");
                        echoIfColSelected(12,$selectedCols,"<td class='col-12' style='text-align: right'>$Disc_Amount</td>");
                        echoIfColSelected(13,$selectedCols,"<td class='col-13' style='text-align: right'>$Amount</td>");
                        echoIfColSelected(14,$selectedCols,"<td class='col-14' style='text-align: right'>$Rate_Idr</td>");
                        echoIfColSelected(15,$selectedCols,"<td class='col-15' style='text-align: right'>$Amount</td>");											                                                                                                                                                                      
                        echo "</tr>";
                        $subAmount += $row->Amount;
                        $subAmountNew = formatNumber ($subAmount,2);
                        $qty_subttl += $row->Qty;
						
						$totalDiscount += $subDiscount;
						$totalAmount += $subAmount;
						$newtotalAmount = formatNumber($totalAmount,2);
						$fdc_subttl = formatNumber ($row->fdc_subttl,2);
						$Disc_Total = formatNumber ($row->Disc_Total,2);
						$Dpp = formatNumber ($row->Dpp,2);
						$Ppn = formatNumber ($row->Ppn,2);
                        $fdc_total = formatNumber ($row->fdc_total,2);
                        $qty_total += $qty_subttl;
					}
					$qty_total += $qty_subttl;
					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total Per-PO:</td>";
					echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$fdc_subttl</td>");							
                    echo "</tr>";
                    
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: right'>Total Per-Supplier</td>";
                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'></td>");						
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

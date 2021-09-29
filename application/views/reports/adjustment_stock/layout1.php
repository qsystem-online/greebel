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
		<div>LAPORAN ADJUSTMENT STOCK</div>
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
		<div>Gudang : <?= $name_wareHouse ?></div>
		<div>Tanggal Adjust: <?= $this->input->post("fdt_sj_datetime") ?>  s/d <?= $this->input->post("fdt_sj_datetime2") ?></div> 
        <div>Kode Item: <?= $this->input->post("fst_item_code") ?>  s/d <?= $this->input->post("fst_item_code2") ?></div>                          
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1400px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.Adjustment</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tanggal</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:150px'>No.Ref</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>GUD</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Kode</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:400px'>Nama Barang</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>Qty</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Unit</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Stock</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_adj = "";
					$nou =0;
					$subQty = 0;
					$totalQty = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $id_adj != $row->Adj_No ){
                            if ($id_adj != "") {
								//akumulasi total keseluruhan                            
								$totalQty += $subQty;                              
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total Per-Adjustment :</td>";
                                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$subQtyNew</td>");							
                                    echo "</tr>";

								//reset subtotal variable (break group)
								$subQty = 0;
							}
							$nou++;
							$id_adj = $row->Adj_No;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Adj_No</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Adj_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Reff_No</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Warehouse</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                        }
                        
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Item_Code</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Item_Name</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$row->Qty</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Unit</td>");	
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->In_out</td>");									                                                                                                                                                                      
                        echo "</tr>";
                        $subQty += $row->Qty;
						$subQtyNew = formatNumber ($subQty,2);
					}
					$totalQty += $subQty;
					$totalQty = formatNumber ($totalQty,2);

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total Per-Adjustment : </td>";
					echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$subQtyNew</td>");							
					echo "</tr>";

                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan : </td>";
                    echoIfColSelected(8,$selectedCols,"<td class='col-8'style='font-weight: bold;text-align: right'>$totalQty</td>");							
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

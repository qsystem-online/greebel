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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1700px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:100px'>GUD</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:250px'>Supplier</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>No.P/O</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:150px'>Tgl.P/O</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:250px'>Nama Barang</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Qty P/O</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Unit</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>No.Penerimaan</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:150px'>Tgl.Terima</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Qty Terima</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Unit</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:50px'>Qty O/S</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:50px'>Status</th>");
					?>
				</tr>
			</thead>
			<tbody>
                <?php
					$id_PO = "";
					$noPO = "";
					$no_LPB = "";
					$id_detailPO = "";
					$status_close ="";
					$qty_LPB = 0;
					$ttl_qty_LPB = 0;
					$ttl_qty_os = 0;
					$ttl_qty_LPBNew = 0;
					$ttl_qty_osNew = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
						if ($id_detailPO != $row->Id_DetailPO){
							if ($id_detailPO != ""){
								echo "<tr>";
							//echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: right;font-weight: bold'></td>";
								//echo "<td colspan='".totalSelectedCol(10,$selectedCols)."' style='text-align: right;font-weight: bold'>Total</td>";
								echoIfColSelected(12,$selectedCols,"<td colspan='".totalSelectedCol(9,$selectedCols)."' style='text-align: right;font-weight: bold'>Total :</td>");
								echoIfColSelected(12,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_LPBNew</td>");
								echoIfColSelected(12,$selectedCols,"<td style='text-align: center;font-weight: bold'></td>");
								echoIfColSelected(12,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_osNew</td>");
								echoIfColSelected(12,$selectedCols,"<td style='text-align: right;font-weight: bold'>$status_close</td>");								

								//echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>";									
								//echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>";
								//echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$ttl_qty_sj</td>");
								//echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_qty_os</td>");							
								echo "</tr>";
								$ttl_qty_LPB = 0;
								$ttl_qty_os = 0;
								$status_close ="";	
							}
						}

						echo "<tr>";
                        if ( $id_PO != $row->Id_PO ){
							$id_PO = $row->Id_PO;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0''border-25'>$row->Warehouse</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Relation_Name</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->No_PO</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->PO_Date</td>");
                            
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            
						}

						if ($id_detailPO != $row->Id_DetailPO){
							//$ttl_qty_sj += $row->qty_sj;
                            $id_detailPO = $row->Id_DetailPO;
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Item_Name</td>");
							echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Qty_PO</td>");
							echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Unit_PO</td>");
								
						}else{
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
							echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
							echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");	
                        }
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->No_LPB</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->LPB_Date</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9' style='text-align: right'>$row->Qty_LPB</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10' style='text-align: right'>$row->Unit_LPB</td>");
						echoIfColSelected(11,$selectedCols,"<td class='col-11'></td>");
						echoIfColSelected(12,$selectedCols,"<td class='col-12'></td>");
						echo "</tr>";
						$ttl_qty_LPB += $row->Qty_LPB;
						$ttl_qty_LPBNew = formatNumber ($ttl_qty_LPB, 2);
						$ttl_qty_os = $row->Qty_PO - $ttl_qty_LPB;
						$ttl_qty_osNew = formatNumber ($ttl_qty_os, 2);
						if ($ttl_qty_os <= 0){
							$status_close = "";
						}else{
							$status_close = "O/S";
						}

					}   
					//$ttl_qty_LPB += $row->Qty_LPB;
					//$ttl_qty_os = $row->Qty_PO - $ttl_qty_LPB;

					/*
					echo "<tr>";
					echo "<td colspan='10' style='text-align: right;font-weight: bold'>Total</td>";
					echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_sj</td>";									
					echo "<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>";
					echo "</tr>";*/

					echo "<tr>";
					echoIfColSelected(12,$selectedCols,"<td colspan='".totalSelectedCol(9,$selectedCols)."' style='text-align: right;font-weight: bold'>Total :</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_LPB</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'></td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$ttl_qty_os</td>");
					echoIfColSelected(11,$selectedCols,"<td style='text-align: right;font-weight: bold'>$status_close</td>");															
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

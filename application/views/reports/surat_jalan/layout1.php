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
		<div>LAPORAN SURAT JALAN DETAIL</div>
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
		<div>Tanggal S/O: <?= $this->input->post("fdt_sj_datetime") ?>  s/d <?= $this->input->post("fdt_sj_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
		<div>Type: <?= $this->input->post("fst_sj_type") ?> </div>                           
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1400px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:300px'>Pelanggan/Customer</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.S/J</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl S/J</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:150px'>No.Ref</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>GUD</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Kode</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:400px'>Nama Barang</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>Qty</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Unit</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$idSJ = "";
					//$Relation_Name = "";
					$subQty = 0;
					$totalQty = 0;
					$sj_type = $this->input->post("fst_sj_type");
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idSJ != $row->No_SJ ){
                            if ($idSJ != "") {
								//akumulasi total keseluruhan                            
								$totalQty += $subQty;                              
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total Per-S/J :</td>";
                                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$subQty</td>");							
                                    echo "</tr>";

								//reset subtotal variable (break group)
								$subQty = 0;
							}
			
							$idSJ = $row->No_SJ;
							if ($sj_type == "ASSEMBLING_OUT"){
								$row->Relation_Name = "ASSEMBLING_OUT";
							}
                            echoIfColSelected(0,$selectedCols,"<td class='col-0''border-25'>$row->Relation_Name</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_SJ</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->SJ_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->No_Ref</td>");
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
                        echo "</tr>";
                        $subQty += $row->Qty;
					}
					$totalQty += $subQty;

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total Per-S/J : </td>";
					echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$subQty</td>");							
					echo "</tr>";

                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan : </td>";
                    echoIfColSelected(7,$selectedCols,"<td class='col-7'style='font-weight: bold;text-align: right'>$totalQty</td>");							
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

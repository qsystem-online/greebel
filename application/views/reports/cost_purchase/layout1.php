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
		<div>LAPORAN BIAYA PEMBELIAN DETAIL</div>
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
		<?php
		$fst_curr_code = $this->input->post("fst_curr_code");
		if ($fst_curr_code >"0"){
			$this->load->model("mscurrencies_model");
			$data = $this->mscurrencies_model->getDataById($fst_curr_code);
			$currency = $data ["ms_Currency"];
			$name_currency = $currency->fst_curr_name;
		}else{
			$name_currency = "ALL";
		}
		?>
		<div>Branch : <?= $name_branch ?></div>
		<div>Tgl Biaya: <?= $this->input->post("fdt_purchasecost_datetime") ?>  s/d <?= $this->input->post("fdt_purchasecost_datetime2") ?></div> 
        <div>Supplier: <?= $name_relations ?> </div>
		<div>Mata Uang : <?= $name_currency ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2100px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.Memo Biaya</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl.Memo Biaya</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>M.U</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Rate</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:150px'>No.P/O</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Kode Akun</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:250px'>Nama Akun</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Notes</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Debit</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Credit</th>");
						echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Profit/Cost Center</th>");
						echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:100px'>Analisa Department</th>");
                        echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:100px'>Analisa Customer</th>");
                        echoIfColSelected(14,$selectedCols,"<th class='col-14' style='width:100px'>Analisa Project</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $idCost = "";
					$idSupplier = "";
					$nou = 0;
					$fdc_total = 0;
					$fdc_total_Idr = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idCost != $row->fin_purchasecost_id ){
                            /*if ($idCost != "") {
								//akumulasi total keseluruhan                                                        
                                //tulis subtotal per-group
                                $fdc_total = formatNumber ($row->fdc_total,2);
                                echo "<tr>";
                                echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal :</td>";
                                echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$fdc_total</td>");						
                                echo "</tr>";
								//reset subtotal variable (break group)
								$fdc_total = 0;

                            }*/			
                            $idCost = $row->fin_purchasecost_id;
                            if ( $idSupplier != $row->fin_supplier_id ){
                                $idSupplier = $row->fin_supplier_id;
								//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
								echo "<tr>";
								echo "<td colspan='".totalSelectedCol(15,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->Relation_Name</td>";
								echo "</tr>";	   
							}
							$nou++;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Cost</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Cost_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Mata_Uang</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Rate_Idr</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_PO</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
							
                        }
                        $Debit = formatNumber ($row->fdc_debet,2);
                        $Credit = formatNumber ($row->fdc_credit,2);
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Account_Code</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Account_Name</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Notes_Detail</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9' style='text-align: right'>$Debit</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10' style='text-align: right'>$Credit</td>");
						echoIfColSelected(11,$selectedCols,"<td class='col-11'>$row->ProfitCost_Center</td>");
						echoIfColSelected(12,$selectedCols,"<td class='col-12'>$row->Analisa_Department</td>");
						echoIfColSelected(13,$selectedCols,"<td class='col-13'>$row->Analisa_Customer</td>");
						echoIfColSelected(14,$selectedCols,"<td class='col-14'>$row->Analisa_Project</td>");
                        echo "</tr>";
                    }
                    //echo "<tr>";
                    //echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal :</td>";
                    //echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$fdc_total</td>");						
                    //echo "</tr>";
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


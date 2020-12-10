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
		<div>LAPORAN LPB PURCHASE RINGKAS</div>
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
		if ($fst_curr_code !="0"){
			$this->load->model("mscurrencies_model");
			$data = $this->mscurrencies_model->getDataById($fst_curr_code);
			$ms_currency = $data ["ms_Currency"];
			$name_currency = $ms_currency->fst_curr_name;
		}else{
			$name_currency = "ALL";
		}
		?>
		<div>Branch : <?= $name_branch ?></div>
		<div>Tanggal LPB: <?= $this->input->post("fdt_lpbpurchase_datetime") ?>  s/d <?= $this->input->post("fdt_lpbpurchase_datetime2") ?></div> 
        <div>Supplier: <?= $name_relations ?> </div>
        <div>Mata Uang : <?= $name_currency ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2000px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
                <?php
                    echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No</th>");
                    echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.LPB</th>");
                    echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl LPB</th>");
                    echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>TOP</th>");
                    echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Tgl.J/T</th>");
                    echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>No.P/O</th>");
                    echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Tgl.P/O</th>");
                    echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:400px'>Supplier</th>");
                    echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>M.U</th>");
                    echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Rate</th>");
                    echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:130px'>Subtotal</th>");
                    echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:130px'>Total</th>");
					echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:130px'>Total IDR</th>");
					echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:130px'>DP Claimed</th>");	
                ?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					$subttl = 0;
					$ttl_disc_amount = 0;
					//$ttl_dpp_amount = 0;
					//$ttl_vat_amount = 0;
					$ttl_total = 0;;
					$ttl_Dp_Claimed = 0;
					$subttlNew = 0;
					$ttl_disc_amountNew = 0;
					$ttl_Dp_ClaimedNew = 0;
                    $ttl_totalNew = 0;
                    $ttl_total_Idr = 0;
                    $ttl_total_IdrNew = 0;
					//$ttl_downpaymentNew = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        $nou++;
						echo "<tr>";
						$fdc_subttl = formatNumber ($row->fdc_subttl, 2);
						$Rate_Idr = formatNumber ($row->Rate_Idr, 2);
						$fdc_total_Idr = formatNumber ($row->fdc_total_Idr, 2);
						$fdc_total = formatNumber ($row->fdc_total, 2);
						$Dp_Claimed = formatNumber ($row->Dp_Claimed, 2);
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_LPB</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->LPB_Date</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->TOP</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Jt_Date</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_PO</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->PO_Date</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Relation_Name</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Mata_Uang</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'>$Rate_Idr</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$fdc_subttl</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$fdc_total</td>");
						echoIfColSelected(12,$selectedCols,"<td class='col-12' style='text-align: right'>$fdc_total_Idr</td>");	
						echoIfColSelected(13,$selectedCols,"<td class='col-13'style='text-align: right'>$Dp_Claimed</td>");											                                                                                                                                                                      
                        echo "</tr>";
                        $subttl += $row->fdc_subttl;
						//$ttl_disc_amount += $row->fdc_disc_amount;
						$ttl_Dp_Claimed += $row->Dp_Claimed;
                        $ttl_total += $row->fdc_total;
                        $ttl_total_Idr += $row->fdc_total_Idr;
						//$ttl_downpayment += $row->fdc_downpayment;

					}
                    $subttlNew += $subttl;
                    $subttlNew = formatNumber ($subttlNew, 2);
                    //$ttl_disc_amountNew += $ttl_disc_amount;
					//$ttl_disc_amountNew = formatNumber ($ttl_disc_amountNew, 2);
					//$ttl_dpp_amountNew += $ttl_dpp_amount;
					//$ttl_dpp_amountNew = formatNumber ($ttl_dpp_amountNew, 2);
					//$ttl_vat_amountNew += $ttl_vat_amount;
                    //$ttl_vat_amountNew = formatNumber ($ttl_vat_amountNew, 2);
					$ttl_totalNew += $ttl_total;
                    $ttl_totalNew = formatNumber ($ttl_totalNew, 2);
                    $ttl_total_IdrNew += $ttl_total_Idr;
					$ttl_total_IdrNew = formatNumber ($ttl_total_IdrNew, 2);
					$ttl_Dp_ClaimedNew += $ttl_Dp_Claimed;
					$ttl_Dp_ClaimedNew = formatNumber ($ttl_Dp_ClaimedNew, 2);      

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(10,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan : </td>";
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$subttlNew</td>");
					echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
					echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$ttl_total_IdrNew</td>");
					echoIfColSelected(13,$selectedCols,"<td class='col-13' style='font-weight: bold;text-align: right'>$ttl_Dp_ClaimedNew</td>");									
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

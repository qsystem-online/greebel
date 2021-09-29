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
		<div>LAPORAN DETAIL PEMBAYARAN PER NO.FAKTUR</div>
		<br>
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
		<div>Tanggal Faktur: <?= $this->input->post("fdt_trx_datetime") ?>  s/d <?= $this->input->post("fdt_trx_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
		<div>Mata Uang : <?= $this->input->post("fst_orgi_curr_code") ?></div>                               
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1800px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No.Faktur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tgl Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:70px'>Tgl J/T</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:200px'>Customer/Pelanggan</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Nilai Faktur</th>");
                        echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Klaim DP</th>");
                        echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Alokasi Retur</th>");
                        echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>No.Kasbank</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Tgl</th>");
						echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Pembayaran</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
                    $Id_Inv = "";
					$ttl_total = 0;
					$ttl_return = 0;
					$sub_ttl_paid = 0;
					$ttl_paid = 0;
                    $ttl_dp_claim = 0;
					foreach ($dataReport as $row){
                        echo "<tr>";
                        if ($Id_Inv != $row->Id_Inv){
							//$ttl_qty_sj += $row->qty_sj;
							if ($Id_Inv !=""){
								//tulis subtotal per-group
								echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal:</td>";
								echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$sub_ttl_paidNew</td>");							
								echo "</tr>";
								//reset subtotal variable (break group)
								$sub_ttl_paid = 0;
							}
							$Id_Inv = $row->Id_Inv;
                            $nou++;
							$fdc_downpayment_claim = formatNumber ($row->fdc_downpayment_claim, 2);
                            $fdc_total_return = formatNumber ($row->fdc_total_return, 2);
                            $fdc_total = formatNumber ($row->fdc_total, 2);
                            $total_netto = formatNumber ($row->Total_Netto, 2);
                            //$fdc_outstandingNew = formatNumber ($fdc_outstanding,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Inv</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Inv_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Jt_Date</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Relation_Name</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'style='text-align: right'>$total_netto</td>");
							echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$fdc_downpayment_claim</td>");
							echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$fdc_total_return</td>");
                            $ttl_total += $row->fdc_total;
                            $ttl_totalNew = formatNumber ($ttl_total, 2);
                            $ttl_return += $row->fdc_total_return;
                            $ttl_returnNew = formatNumber ($ttl_return, 2);
                            $ttl_dp_claim += $row->fdc_downpayment_claim;
                            $ttl_dp_claimNew = formatNumber ($ttl_dp_claim, 2);
						}else{
							echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'></td>";
						}
						$Receive_Amount = formatNumber ($row->Receive_Amount, 2);
                        
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Receive_No</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->Receive_Date</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$Receive_Amount</td>");										                                                                                                                                                                      
						echo "</tr>";
						$sub_ttl_paid += $row->Receive_Amount;
						$sub_ttl_paidNew = formatNumber ($sub_ttl_paid, 2);
						$ttl_paid += $row->Receive_Amount;
						$ttl_paidNew = formatNumber ($ttl_paid, 2);
                        
					}
					echo "<td colspan='".totalSelectedCol(10,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal:</td>";
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$sub_ttl_paidNew</td>");							
					echo "</tr>";

					echo "<td colspan='".totalSelectedCol(5,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan: </td>";
					echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
					echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$ttl_dp_claimNew</td>");
					echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$ttl_returnNew</td>");
					echoIfColSelected(8,$selectedCols,"<td class='col-8'></td>");
					echoIfColSelected(9,$selectedCols,"<td class='col-9'></td>");
					echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$ttl_paidNew</td>");										
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

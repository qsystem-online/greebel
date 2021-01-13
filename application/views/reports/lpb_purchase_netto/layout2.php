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
		<div>LAPORAN LPB PURCHASE NETTO RINGKAS + PEMBAYARAN RINCI</div>
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
		<div>Tgl LPB: <?= $this->input->post("fdt_lpbpurchase_datetime") ?>  s/d <?= $this->input->post("fdt_lpbpurchase_datetime2") ?></div> 
        <div>Supplier: <?= $name_relations ?> </div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2100px">      
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
                    echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>M.U</th>");
                    echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Rate</th>");
                    echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:130px'>Nilai Faktur</th>");
                    echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:130px'>Klaim DP</th>");
                    echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:130px'>Total Retur</th>");
                    echoIfColSelected(12,$selectedCols,"<th class='col-12' style='width:130px'>Nilai Netto</th>");
                    echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:150px'>No.Pembayaran</th>");
                    echoIfColSelected(14,$selectedCols,"<th class='col-14' style='width:100px'>Tgl.Bayar</th>");
                    echoIfColSelected(15,$selectedCols,"<th class='col-15' style='width:130px'>Nilai Bayar</th>");
                    echoIfColSelected(16,$selectedCols,"<th class='col-16' style='width:130px'>Outstanding</th>");
                    
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $idLPB = "";
					$idSupplier = "";
                    $nou = 0;
                    $sub_total = 0;
                    $sub_total_downpayment_claim = 0;
                    $sub_total_retur = 0;
                    $sub_total_netto = 0;
                    $sub_total_payment = 0;
                    $sub_total_outstanding = 0;
                    $ttl_total = 0;
                    $ttl_total_downpayment_claim = 0;
                    $ttl_total_retur = 0;
                    $ttl_total_netto = 0;
                    $ttl_total_payment = 0;
                    $ttl_total_outstanding = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idLPB != $row->fin_lpbpurchase_id ){		
                            $idLPB = $row->fin_lpbpurchase_id;
                            if ( $idSupplier != $row->fin_supplier_id ){
                                if ($idSupplier != "") {
                                        echo "<tr>";
                                        echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-Supplier :</td>";
                                        echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$sub_totalNew</td>");
                                        echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$sub_total_downpayment_claimNew</td>");
                                        echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$sub_total_returNew</td>");
                                        echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$sub_total_nettoNew</td>");
                                        echoIfColSelected(13,$selectedCols,"<td class='col-13'></td>");
                                        echoIfColSelected(14,$selectedCols,"<td class='col-14'></td>");
                                        echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$sub_total_paymentNew</td>");
                                        echoIfColSelected(16,$selectedCols,"<td class='col-16' style='font-weight: bold;text-align: right'>$sub_total_outstandingNew</td>");						
										echo "</tr>";
										$nou = 0;
                                        $sub_total = 0;
                                        $sub_total_downpayment_claim = 0;
                                        $sub_total_retur = 0;
                                        $sub_total_netto = 0;
                                        $sub_total_payment = 0;
                                        $sub_total_outstanding = 0;
                                }
                                $idSupplier = $row->fin_supplier_id;
								//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
								echo "<tr>";
								echo "<td colspan='".totalSelectedCol(17,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->Relation_Name</td>";
								echo "</tr>";	   
							}
                            $nou++;
                            $Rate_Idr = formatNumber ($row->Rate_Idr, 2);
                            $fdc_total_retur = formatNumber ($row->fdc_total_retur, 2);
                            $fdc_total = formatNumber ($row->fdc_nilai_faktur, 2);
                            $fdc_downpayment_claim = formatNumber ($row->fdc_downpayment_claim, 2);
                            $fdc_total_netto = formatNumber ($row->fdc_total_netto, 2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_LPB</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->LPB_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->TOP</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Jt_Date</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_PO</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->PO_Date</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Mata_Uang</td>");
                            echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$Rate_Idr</td>");
                            echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_total</td>");
                            echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$fdc_downpayment_claim</td>");
                            echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$fdc_total_retur</td>");
                            echoIfColSelected(12,$selectedCols,"<td class='col-12'style='text-align: right'>$fdc_total_netto</td>");												                                                                                                                                                                      
                            $ttl_total += $row->fdc_nilai_faktur;
                            $ttl_total_downpayment_claim += $row->fdc_downpayment_claim;
                            $ttl_total_retur += $row->fdc_total_retur;
                            $ttl_total_netto += $row->fdc_total_netto;


                            $sub_total += $row->fdc_nilai_faktur;
                            $sub_totalNew = formatNumber ($sub_total,2);
                            $sub_total_downpayment_claim += $row->fdc_downpayment_claim;
                            $sub_total_downpayment_claimNew = formatNumber ($sub_total_downpayment_claim,2);
                            $sub_total_retur += $row->fdc_total_retur;
                            $sub_total_returNew = formatNumber ($sub_total_retur,2);
                            $sub_total_netto += $row->fdc_total_netto;
                            $sub_total_nettoNew = formatNumber ($sub_total_netto,2);

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
                            echoIfColSelected(9,$selectedCols,"<td class='col-9'></td>");
                            echoIfColSelected(10,$selectedCols,"<td class='col-10'></td>");
                            echoIfColSelected(11,$selectedCols,"<td class='col-11'></td>");
                            echoIfColSelected(12,$selectedCols,"<td class='col-12'></td>");
							
                        }
                        $fdc_payment = formatNumber ($row->fdc_payment,2);
                        echoIfColSelected(13,$selectedCols,"<td class='col-13'>$row->No_Payment</td>");
                        echoIfColSelected(14,$selectedCols,"<td class='col-14'>$row->Payment_Date</td>");
                        echoIfColSelected(15,$selectedCols,"<td class='col-15' style='text-align: right'>$fdc_payment</td>");
                        echoIfColSelected(16,$selectedCols,"<td class='col-16' style='text-align: right'>OS</td>");												                                                                                                                                                                      
                        echo "</tr>";

                        $sub_total_payment += $row->fdc_payment;
                        $sub_total_paymentNew = formatNumber ($sub_total_payment,2);
                        $sub_total_outstanding = $sub_total_netto - $sub_total_payment;
                        $sub_total_outstandingNew = formatNumber ($sub_total_outstanding,2);
                        $ttl_total_payment += $row->fdc_payment;
                        $ttl_total_outstanding = $ttl_total_netto - $ttl_total_payment;
                        

					}
                    $ttl_totalNew = formatNumber ($ttl_total,2);
                    $ttl_total_downpayment_claimNew = formatNumber ($ttl_total_downpayment_claim,2);
                    $ttl_total_returNew = formatNumber ($ttl_total_retur,2);
                    $ttl_total_nettoNew = formatNumber ($ttl_total_netto,2);
                    $ttl_total_paymentNew = formatNumber ($ttl_total_payment,2);
                    $ttl_total_outstandingNew = formatNumber ($ttl_total_outstanding,2);
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-Supplier :</td>";
                    echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$sub_totalNew</td>");
                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$sub_total_downpayment_claimNew</td>");
                    echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$sub_total_returNew</td>");
                    echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$sub_total_nettoNew</td>");
                    echoIfColSelected(13,$selectedCols,"<td class='col-13'></td>");
                    echoIfColSelected(14,$selectedCols,"<td class='col-14'></td>");
                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$sub_total_paymentNew</td>");
                    echoIfColSelected(16,$selectedCols,"<td class='col-16' style='font-weight: bold;text-align: right'>$sub_total_outstandingNew</td>");					
                    echo "</tr>";
                    
					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(9,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan :</td>";
                    echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$ttl_total_downpayment_claimNew</td>");
                    echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_total_returNew</td>");
                    echoIfColSelected(12,$selectedCols,"<td class='col-12' style='font-weight: bold;text-align: right'>$ttl_total_nettoNew</td>");
                    echoIfColSelected(13,$selectedCols,"<td class='col-13'></td>");
                    echoIfColSelected(14,$selectedCols,"<td class='col-14'></td>");
                    echoIfColSelected(15,$selectedCols,"<td class='col-15' style='font-weight: bold;text-align: right'>$ttl_total_paymentNew</td>");
                    echoIfColSelected(16,$selectedCols,"<td class='col-16' style='font-weight: bold;text-align: right'>$ttl_total_outstandingNew</td>");						
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

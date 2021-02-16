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
		<div>LAPORAN FAKTUR PENJUALAN OUTSTANDING</div>
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
        <?php
		$area_code = $this->input->post("fst_kode");
        $this->load->model("msarea_model");
        $data = $this->msarea_model->getDataById($area_code);
        $area = $data ["ms_area"];
		if ($area != null){
			$name_area = $area->fst_nama;
		}else{
			$name_area = "ALL";
		}
		?>
		<div>Tanggal Faktur: <?= $this->input->post("fdt_inv_datetime") ?>  s/d <?= $this->input->post("fdt_inv_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
        <div>Sales: <?= $name_sales ?></div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div> 
        <div>Area : <?= $name_area ?></div>                               
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No.Faktur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tgl Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Jatuh Tempo</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Sales</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>M.U</th>");
                        echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Nilai Faktur</th>");
                        echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Total Retur</th>");
                        echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>Jumlah</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Pembayaran</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Outstanding</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
                    $district ="";
					$idCustomer = "";
                    $fdc_outstanding = 0;
					$sub_total = 0;
					$sub_total_return = 0;
					$sub_total_paid = 0;
                    $sub_total_netto = 0;
                    $sub_total_outstanding = 0;
					$ttl_total = 0;
					$ttl_return = 0;
					$ttl_paid = 0;
                    $ttl_nilai_netto = 0;
                    $ttl_nilai_outstanding = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idCustomer != $row->Relation_Id ){
							if ($idCustomer != "") {
									echo "<tr>";
									echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>SUBTOTAL :</td>";
									echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$sub_totalNew</td>");
									echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$sub_total_returnNew</td>");
									echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$sub_total_nettoNew</td>");
									echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$sub_total_paidNew</td>");
                                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$sub_total_nettoNew</td>");						
									echo "</tr>";
									$nou = 0;
									$sub_total = 0;
									$sub_total_return = 0;
									$sub_total_netto = 0;
									$sub_total_paid = 0;
                                    $ttl_outstanding = 0;
							}
							$idCustomer = $row->Relation_Id;
                            if ( $district != $row->district ){
                                $district = $row->district;
                                switch (strlen($area_code)){
                                    case "2":
                                        $grouping_area = $row->fst_district_name;
                                        break;
                                    case "5":
                                        $grouping_area = $row->fst_subdistrict_name;
                                        break;
                                    case "8":
                                        $grouping_area = $row->fst_village_name;
                                        break;
                                    case "13":
                                        $grouping_area = $row->fst_village_name;
                                        break;
                                    default:
                                        $grouping_area = $row->fst_district_name;
                                        break;
                                }
                                echo "<tr>";
                                echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: left;font-weight: bold'>AREA : $grouping_area</td>";
                                echo "</tr>";	   
                            }
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: left;font-weight: bold'>CUSTOMER : $row->Relation_Name</td>";
							echo "</tr>";	   
						}
						$nou++;
						$fdc_total_return = formatNumber ($row->fdc_total_return, 2);
						$fdc_total_paid = formatNumber ($row->fdc_total_paid, 2);
						$fdc_total = formatNumber ($row->fdc_total, 2);
						$total_netto = formatNumber ($row->Total_Netto, 2);
                        $fdc_outstanding = ($row->Total_Netto - $row->fdc_total_paid);
                        $fdc_outstandingNew = formatNumber ($fdc_outstanding,2);
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Inv</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Inv_Date</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Jt_Date</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Sales_Name</td>");
						echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Mata_Uang</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$fdc_total</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$fdc_total_return</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$total_netto</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_total_paid</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$fdc_outstandingNew</td>");											                                                                                                                                                                      
						echo "</tr>";
						$ttl_total += $row->fdc_total;
						$ttl_totalNew = formatNumber ($ttl_total, 2);
						$ttl_return += $row->fdc_total_return;
						$ttl_returnNew = formatNumber ($ttl_return, 2);
						$ttl_paid += $row->fdc_total_paid;
						$ttl_paidNew = formatNumber ($ttl_paid, 2);
						$ttl_nilai_netto += $row->Total_Netto;
						$ttl_nilai_nettoNew = formatNumber ($ttl_nilai_netto, 2);
                        $ttl_outstanding = $ttl_nilai_netto - $ttl_paid;
                        $ttl_outstandingNew = formatNumber($ttl_outstanding,2);

						$sub_total += $row->fdc_total;
						$sub_totalNew = formatNumber ($sub_total,2);
						$sub_total_return += $row->fdc_total_return;
						$sub_total_returnNew = formatNumber ($sub_total_return,2);
						$sub_total_paid += $row->fdc_total_paid;
						$sub_total_paidNew = formatNumber ($sub_total_paid,2);
						$sub_total_netto += $row->Total_Netto;
						$sub_total_nettoNew = formatNumber ($sub_total_netto,2);
                        $sub_total_outstanding = $sub_total_netto - $sub_total_paid;
                        $sub_total_outstandingNew = formatNumber($sub_total_outstanding,2);
                        
					}

					echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>SUBTOTAL :</td>";
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$sub_totalNew</td>");
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$sub_total_returnNew</td>");
                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$sub_total_nettoNew</td>");
                    echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$sub_total_paidNew</td>");
                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$sub_total_outstandingNew</td>");						
					echo "</tr>";

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>TOTAL: </td>";
					echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
					echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$ttl_returnNew</td>");
					echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$ttl_nilai_nettoNew</td>");
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$ttl_paidNew</td>");
                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'>$ttl_outstandingNew</td>");											
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

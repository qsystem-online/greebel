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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1500px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No.Faktur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tgl Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Jatuh Tempo</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Pelanggan</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Sales</th>");
                        echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>M.U</th>");
                        echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Outstanding</th>");
                        echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>Pembayaran</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Selisih</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>No.Pembayaran</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Tgl.Pembayaran</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
                    $Id_Inv = "";
                    $fdc_outstanding = 0;
					$ttl_total = 0;
					$ttl_return = 0;
					$ttl_paid = 0;
                    $ttl_nilai_netto = 0;
                    $ttl_nilai_outstanding = 0;
					foreach ($dataReport as $row){
                        echo "<tr>";
                        if ($Id_Inv != $row->Id_Inv){
							//$ttl_qty_sj += $row->qty_sj;
							$Id_Inv = $row->Id_Inv;
                            $nou++;
                            $fdc_total_return = formatNumber ($row->fdc_total_return, 2);
                            $fdc_total_paid = formatNumber ($row->fdc_total_paid, 2);
                            $fdc_total = formatNumber ($row->fdc_total, 2);
                            $total_netto = formatNumber ($row->Total_Netto, 2);
                            $fdc_outstanding = formatNumber ($row->Total_Netto - $row->fdc_total_paid, 2);
                            //$fdc_outstandingNew = formatNumber ($fdc_outstanding,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Inv</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Inv_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Jt_Date</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Relation_Name</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Sales_Name</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Mata_Uang</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$total_netto</td>");
                            echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$fdc_total_paid</td>");
                            echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_outstanding</td>");
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
						}
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Receive_No</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'>$row->Receive_Date</td>");											                                                                                                                                                                      
						echo "</tr>";
                        
					}

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: right;font-weight: bold'>TOTAL: </td>";
					echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$ttl_nilai_nettoNew</td>");
					echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$ttl_paidNew</td>");
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'>$ttl_outstandingNew</td>");
                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'></td>");											
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

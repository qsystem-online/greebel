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
		<div>LAPORAN TRANSAKSI EKSPEDISI + PENERIMAAN PELUNASAN</div>
		<br>
		<?php
		$fin_customer_id = $this->input->post("fin_customer_id");
		if ($fin_customer_id >"0"){
			$this->load->model("msrelations_model");
			$data = $this->msrelations_model->getDataById($fin_customer_id);
			$ms_relations = $data ["ms_relations"];
			$name_customer = $ms_relations->fst_relation_name;
		}else{
			$name_customer = "ALL";
		}
		?>
		<?php
		$fin_ekspedisi_id = $this->input->post("fin_ekspedisi_id");
		if ($fin_ekspedisi_id >"0"){
			$this->load->model("msrelations_model");
			$data = $this->msrelations_model->getDataById($fin_ekspedisi_id);
			$ms_relations = $data ["ms_relations"];
			$name_ekspedisi = $ms_relations->fst_relation_name;
		}else{
			$name_ekspedisi = "ALL";
		}
		?>
		<div>Tgl Ekpedisi: <?= $this->input->post("fdt_salesekspedisi_datetime") ?>  s/d <?= $this->input->post("fdt_salesekspedisi_datetime2") ?></div> 
        <div>Customer    : <?= $name_customer ?> </div>
        <div>Ekspedisi   : <?= $name_ekspedisi ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1800px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No.Ekspedisi</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tgl.Ekspedisi</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Ekspedisi</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Customer/Pelanggan</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>No.Ref</th>");
                        echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Total</th></th>");
                        echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>A/R</th>");
                        echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>No.Pembayaran</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>Tgl.Pembayaran</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Pembayaran</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Outstanding</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
                    $Id_Ekspedisi = "";
					$ttl_total = 0;
					$ttl_qty = 0;
					foreach ($dataReport as $row){
                        echo "<tr>";
                        if ($Id_Ekspedisi != $row->Id_Ekspedisi){
                            $Id_Ekspedisi = $row->Id_Ekspedisi;
                            $nou++;
                            $qty_kodi = formatNumber ($row->qty_kodi, 2);
                            $price_kodi = formatNumber ($row->price_kodi, 2);
                            $fdc_total = formatNumber ($row->fdc_total, 2);
                            $Ppn = formatNumber ($row->Ppn, 2);
                            $fdc_other_cost = formatNumber ($row->fdc_other_cost, 2);
                            if ($row->Reclaimable ==1){
                                $row->Reclaimable = 'Yes';
                            }else{
                                $row->Reclaimable = 'No';
                            }
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Ekspedisi</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Ekspedisi_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Ekspedisi_Name</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Customer_Name</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_Ref</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'>$fdc_total</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: center'>$row->Reclaimable</td>");	
                            $ttl_total += $row->fdc_total;
                            $ttl_totalNew = formatNumber ($ttl_total, 2);
                            $ttl_qty += $row->qty_kodi;
                            $ttl_qtyNew = formatNumber ($ttl_qty, 2);
                        }else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'style='text-align: right'></td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'></td>");
                        }
                        $Payment_Amount = formatNumber ($row->Payment_Amount, 2);
                        $Outstanding = formatNumber ($row->Payment_Amount, 2);
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->No_Payment</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->Payment_Date</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$Payment_Amount</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$Outstanding</td>");                                                                                                                                                  
						echo "</tr>";
                        
					}

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right;font-weight: bold'>TOTAL: </td>";
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");
					echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'></td>");
					echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'></td>");
					echoIfColSelected(9,$selectedCols,"<td class='col-9' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(10,$selectedCols,"<td class='col-10' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");											
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

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
			th,td{
				border:1px solid #000;
			}			
			.text-right{
				align:'right';
			}
		</style>
		<div>LAPORAN KARTU PIUTANG RINGKAS</div>
		<br>
        <?php
		$start_date = $this->input->post("fdt_trx_datetime");
		?>
		<?php
		$end_date = $this->input->post("fdt_trx_datetime2");
		?>
		<div>Tanggal: <?= $start_date ?>  s/d <?= $end_date ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>ID</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:50px'>Customer/Pelanggan</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Saldo Awal</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:200px'>Debit</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5 text-right' style='width:100px'>Credit</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:100px'>Saldo Akhir</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $sales_id = "";
					$relation_id = "";
					$nou = 0;
					$saldo_awal = 0;
					$saldo_akhir = 0;
                    //$piutang_gl_code = getGLConfig("AR_DAGANG_LOKAL");
					//$piutangExpedisi_gl_code = getGLConfig("PIUTANG_EKSPEDISI_PENJUALAN");
					foreach ($dataReport as $row){
						if ( $sales_id != $row->fin_sales_id){
							$sales_id = $row->fin_sales_id;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: left;font-weight: bold'>Sales : $row->Sales_Name</td>";
							echo "</tr>";
							$nou = 0;	   
						}
						$relation_id = $row->fin_relation_id;
						//$ssql = "SELECT IFNULL(SUM(fdc_debit),0) AS fdc_debit,IFNULL(SUM(fdc_credit),0) AS fdc_credit FROM glledger WHERE fin_relation_id = '".$relation_id."' AND fst_account_code = '" .$piutang_gl_code."' AND fdt_trx_datetime < '". date('Y-m-d', strtotime($start_date)). "' AND fst_active = 'A' ";
						//$qr = $this->db->query($ssql);
						//echo $this->db->last_query();
						//die();
						//$rw = $qr->row();
						
						//$saldo_awal = ($rw->fdc_debit - $rw->fdc_credit);
						//$saldo_awal_credit = (0 - $saldo_awal);
						//$saldo_awal_creditNew = formatNumber($saldo_awal_credit,2);
						//$saldo_awalNew = formatNumber ($saldo_awal,2); 
						$nou++;
                        $saldo_awal = ($row->SA_Debit - $row->SA_Credit);
                        $saldo_awalNew = formatNumber ($saldo_awal,2); 
						$Total_Debit = formatNumber ($row->Total_Debit,2);
						$Total_Credit = formatNumber ($row->Total_Credit,2);
						$saldo_akhir = ($saldo_awal + $row->Total_Debit) - $row->Total_Credit;
						$saldo_akhirNew = formatNumber ($saldo_akhir,2);
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fin_relation_id</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_relation_name</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$saldo_awalNew</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4' style='text-align: right'>$Total_Debit</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$Total_Credit</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$saldo_akhirNew</td>");	                                                                                                                                                                      
						echo "</tr>";	
					}
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
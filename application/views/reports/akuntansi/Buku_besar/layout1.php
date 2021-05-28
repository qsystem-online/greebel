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
		<div>BUKU BESAR G/L</div>
		<br>
        <?php
		$start_date = $this->input->post("fdt_trx_datetime");
		?>
		<?php
		$end_date = $this->input->post("fdt_trx_datetime2");
		?>
		<div>Tanggal: <?= $start_date ?>  s/d <?= $end_date ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1400px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Tanggal</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:50px'>SRC</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>No.Transaksi</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:400px'>Referensi</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5 text-right' style='width:100px'>Debit</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:100px'>Credit</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7 text-right' style='width:100px'>Saldo</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$account_code ="";
					$nou = 0;
					$saldo_awal = 0;
					$saldo_akhir = 0;
                    $saldo_awal_credit = 0;
					$account_name = "";
                    $saldo_awal_date = "";
					foreach ($dataReport as $row){
						if ( $account_code != $row->fst_glaccount_code ){
							$account_code = $row->fst_glaccount_code;
							//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
							$account_name = $row->fst_glaccount_code .'-'. $row->fst_glaccount_name;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: left;font-weight: bold'>$account_name</td>";
							echo "</tr>";

                            $ssql = "SELECT IFNULL(SUM(fdc_debit),0) AS fdc_debit,IFNULL(SUM(fdc_credit),0) AS fdc_credit FROM glledger WHERE fst_account_code = '".$account_code."' AND fdt_trx_datetime < '". date('Y-m-d', strtotime($start_date)). "' AND fst_active = 'A' ";
                            $qr = $this->db->query($ssql);
                            //echo $this->db->last_query();
                            //die();
                            $rw = $qr->row();
                            
                            $saldo_awal = ($rw->fdc_debit - $rw->fdc_credit);
                            $saldo_awal_credit = (0 - $saldo_awal);
                            $saldo_awal_creditNew = formatNumber($saldo_awal_credit,2);
                            $saldo_awalNew = formatNumber ($saldo_awal,2); 
                            $saldo_awal_date = strtotime($start_date);
                            $saldo_awal_date = strtotime("-1 day", $saldo_awal_date);
                            $saldo_awal_date = date('Y-m-d', $saldo_awal_date);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>1</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$saldo_awal_date</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>OPB</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>O/B</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>Opening Balance</td>");
                            if($saldo_awal < 0){
                                echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>0</td>");
                                echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$saldo_awal_creditNew</td>");
                            }else{
                                echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$saldo_awalNew</td>");
                                echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>0</td>");
                            }
                            echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$saldo_awalNew</td>");
							$nou = 1;	   
						}
						echo "<tr>";
						$nou++;
                        if($nou == 2){
                            $saldo_akhir = ($saldo_awal + $row->fdc_debit) - $row->fdc_credit;
                        }else{
                            $saldo_akhir = ($saldo_akhir + $row->fdc_debit) - $row->fdc_credit;
                        }
						$fdc_debit = formatNumber ($row->fdc_debit,2);
						$fdc_credit = formatNumber ($row->fdc_credit,2);
						$saldo_akhirNew = formatNumber ($saldo_akhir,2);
                        if($row->fdt_trx_datetime != null || $row->fst_trx_sourcecode != ""){
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fdt_trx_datetime</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_trx_sourcecode</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->fst_trx_no</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->fst_reference</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$fdc_debit</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$fdc_credit</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$saldo_akhirNew</td>");
                        }		                                                                                                                                                                      
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

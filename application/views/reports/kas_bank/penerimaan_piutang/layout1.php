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
		<div>LAPORAN PENGGUNAAN GL ACCOUNT PADA PENERIMAAN PIUTANG DAGANG</div>
		<br>
        <?php
		$start_date = $this->input->post("fdt_cbreceive_datetime");
		?>
		<?php
		$end_date = $this->input->post("fdt_cbreceive_datetime2");
		?>
		<div>Tanggal: <?= $start_date ?>  s/d <?= $end_date ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1000px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:200px'>Tgl.Penerimaan</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:200px'>No.Penerimaan</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:400px'>Customer/Pelanggan</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:200px'>Nilai</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$account_code ="";
					$sub_amount = 0;
					$ttl_amount = 0;
					$account_name = "";
					foreach ($dataReport as $row){
						if ($account_code != $row->fst_glaccount_code){
                            if ($account_code != "") {
								//akumulasi total keseluruhan                            
                                $ttl_amount += $sub_amount;
                                $ttl_amountNew = formatNumber ($ttl_amount, 2);  
                                $sub_amount = formatNumber ($sub_amount,2);                                
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(3,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal Per-Akun:</td>";
                                    echoIfColSelected(3,$selectedCols,"<td class='col-4' style='font-weight: bold;text-align: right'>$sub_amount</td>");							
                                    echo "</tr>";
								//reset subtotal variable (break group)
                                $sub_amount = 0;
							}
							$account_code = $row->fst_glaccount_code;
							$account_name = $row->fst_glaccount_code .'-'. $row->fst_glaccount_name;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(4,$selectedCols)."'style='text-align: left;font-weight: bold'>$account_name</td>";
							echo "</tr>";
						}
						echo "<tr>";
						$fdc_amount = formatNumber ($row->fdc_amount,2);
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->fdt_cbreceive_datetime</td>");
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_cbreceive_no</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_relation_name</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'style='text-align: right'>$fdc_amount</td>");	                                                                                                                                                                      
						echo "</tr>";
                        $sub_amount += $row->fdc_amount;	
					}
                    $ttl_amount += $sub_amount;
                    $sub_amount = formatNumber ($sub_amount,2);
                    $ttl_amountNew = formatNumber ($ttl_amount, 2);  
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(3,$selectedCols)."'style='text-align: right;font-weight: bold'>Subtotal Per-Akun:</td>";
                    echoIfColSelected(3,$selectedCols,"<td class='col-4' style='font-weight: bold;text-align: right'>$sub_amount</td>");							
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(3,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan:</td>";
                    echoIfColSelected(3,$selectedCols,"<td class='col-4' style='font-weight: bold;text-align: right'>$ttl_amountNew</td>");							
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

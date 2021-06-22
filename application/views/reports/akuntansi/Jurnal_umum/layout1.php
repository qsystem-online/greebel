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
			.text-right{
				align:'right';
			}
		</style>
		<div>LAPORAN JOURNAL UMUM</div>
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
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No. Journal</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:200px'>Tanggal</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:400px'>Keterangan</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>Entry By</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5 text-right' style='width:100px'>Debit</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:100px'>Credit</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$Jurnal_Id ="";
                    $Jurnal_Type ="";
                    $Rec_Id ="";
					$nou = 0;
					$Total_Jurnal = 0;
					$ttl_Total = 0;
					$account_name = "";
                    $saldo_awal_date = "";
					foreach ($dataReport as $row){
                        $jurnalType = "";
                        switch ($row->Jurnal_Type){
                            case "JA":
                                $jurnalType = "Jurnal Penyesuaian";
                                break;
                            case "JB":
                                $jurnalType = "Jurnal Pembelian";
                                break;
                            case "JC":
                                $jurnalType = "Jurnal Penutup";
                                break;
                            case "JJ":
                                $jurnalType = "Jurnal Penjualan";
                                break;
                            case "JK":
                                $jurnalType = "Jurnal Pengeluaran";
                                break;
                            case "JT":
                                $jurnalType = "Jurnal Penerimaan";
                                break;
                            case "JU":
                                $jurnalType = "Jurnal Umum";
                                break;
                            case "KK";
                                $jurnalType = "Kas Besar Keluar";
                                break;
                        };
                        if ( $Jurnal_Type != $row->Jurnal_Type ){
							$Jurnal_Type = $row->Jurnal_Type;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: left;font-weight: bold'>JOURNAL :$Jurnal_Type - $jurnalType</td>";
							echo "</tr>";
                            $nou =0;  
						}
						if ( $Jurnal_Id != $row->Jurnal_Id ){
							$Jurnal_Id = $row->Jurnal_Id;
							//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
							///$account_name = $row->fst_glaccount_code .'-'. $row->fst_glaccount_name;
							echo "<tr>";
                            $nou++;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0' style='font-weight: bold'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1' style='font-weight: bold'>$row->Jurnal_No</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2' style='font-weight: bold'>$row->Jurnal_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3' style='font-weight: bold'>$row->Keterangan</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4' style='font-weight: bold'>$row->Entry_By</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");	
							echo "</tr>";
                            echo "<tr>";
                            $fdc_debit = formatNumber ($row->fdc_debit,2);
                            $fdc_credit = formatNumber ($row->fdc_credit,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0' style='border-top:0px solid #000'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1' style='border-top:0px solid #000;text-align: right'>$row->Glaccount_Code</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2' style='border-top:0px solid #000'>$row->Glaccount_Name</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3' style='border-top:0px solid #000'>$row->Memo_Detail</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4' style='border-top:0px solid #000'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='border-top:0px solid #000;text-align: right'>$fdc_debit</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='border-top:0px solid #000;text-align: right'>$fdc_credit</td>");
                            echo "</tr>";	
						}else{
                            echo "<tr>";
                            $fdc_debit = formatNumber ($row->fdc_debit,2);
                            $fdc_credit = formatNumber ($row->fdc_credit,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0' style='border-top:0px solid #000'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1' style='border-top:0px solid #000;text-align: right'>$row->Glaccount_Code</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2' style='border-top:0px solid #000'>$row->Glaccount_Name</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3' style='border-top:0px solid #000'>$row->Memo_Detail</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4' style='border-top:0px solid #000'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='border-top:0px solid #000;text-align: right'>$fdc_debit</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='border-top:0px solid #000;text-align: right'>$fdc_credit</td>");
                            echo "</tr>";
                        }
                        //if($row->fst_reference !="" || $row->fst_reference != null){
                            //echo "<tr>";
                        //    echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='border-top:0px solid #000;font-style:italic'>$row->fst_reference</td>";
                            //echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            //echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            //echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            //echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            //echoIfColSelected(4,$selectedCols,"<td class='col-4' style='border-top:0px solid #000;font-style:italic'>$row->fst_reference</td>");
                            //echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'></td>");
                            //echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'></td>");
                            //echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'></td>");
                            //echo "</tr>";
                        //}	
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

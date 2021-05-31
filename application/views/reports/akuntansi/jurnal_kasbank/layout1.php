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
		<div>LAPORAN JOURNAL KAS/BANK</div>
		<br>
        <?php
		$start_date = $this->input->post("fdt_trx_datetime");
		?>
		<?php
		$end_date = $this->input->post("fdt_trx_datetime2");
		?>
		<div>Tanggal: <?= $start_date ?>  s/d <?= $end_date ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1300px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No. Transaksi</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tanggal</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>No. Rekening</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:400px'>Nama Rekening/Referensi</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5 text-right' style='width:100px'>Debit</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:100px'>Credit</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$trxNo ="";
                    $trxCode ="";
                    $Rec_Id ="";
					$nou = 0;
					foreach ($dataReport as $row){
                        $trxName = "";
                        switch ($row->Trx_Sourcecode){
                            case "CBIN":
                                $trxName = "Penerimaan Kas/Bank";
                                break;
                            case "CBOUT":
                                $trxName = "Pengeluaran Kas/Bank";
                                break;
                            case "CBPO":
                                $trxName = "Penerimaan Lain2";
                                break;
                            case "CBRO":
                                $trxName = "Pengeluaran Lain2";
                                break;
                            case "SRT":
                                $trxName = "Retur Penjualan";
                                break;
                            case "SO":
                                $trxName = "Sales Order";
                                break;
                            case "SIV":
                                $trxName = "Faktur Penjualan";
                                break;
                            case "PRT":
                                $trxName = "Retur Pembelian";
                                break;
                            case "PRD":
                                $trxName = "Distribusi Logistic";
                                break;
                            case "PO":
                                $trxName = "Purchase Order";
                                break;
                            case "PINV":
                                $trxName = "Faktur Pembelian";
                                break;
                            case "JM":
                                $trxName = "Journal Umum";
                                break;
                            case "PCS":
                                $trxName = "Biaya Pembelian";
                                break;
                            case "EXP":
                                $trxName = "Ekspedisi";
                                break;
                            case "WOEI":
                                $trxName = "Faktur WO External";
                                break;
                            case "DFA":
                                $trxName = "Disposal Fixed Asset";
                                break;
                            case "DCFA":
                                $trxName = "Penyusutan Fixed Asset";
                                break;
                        };
                        if ( $trxCode != $row->Trx_Sourcecode ){
							$trxCode = $row->Trx_Sourcecode;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: left;font-weight: bold'>Jenis Journal :$trxCode - $trxName</td>";
							echo "</tr>";
                            $nou =0;  
						}
						if ( $trxNo != $row->Trx_No ){
							$trxNo = $row->Trx_No;
							echo "<tr>";
                            $nou++;
                            $fdc_debit = formatNumber ($row->fdc_debit,2);
                            $fdc_credit = formatNumber ($row->fdc_credit,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Trx_No</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Trx_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Glaccount_Code</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Glaccount_Name</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='border-top:0px solid #000;text-align: right'>$fdc_debit</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='border-top:0px solid #000;text-align: right'>$fdc_credit</td>");	
							echo "</tr>";
						}else{
                            echo "<tr>";
                            $fdc_debit = formatNumber ($row->fdc_debit,2);
                            $fdc_credit = formatNumber ($row->fdc_credit,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Glaccount_Code</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Glaccount_Name</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='border-top:0px solid #000;text-align: right'>$fdc_debit</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='border-top:0px solid #000;text-align: right'>$fdc_credit</td>");	
							echo "</tr>";
                        }
                        if ($Rec_Id != $row->Rec_Id){
                            $Rec_Id = $row->Rec_Id;
                            if($row->Keterangan != null || $row->Keterangan !=""){
                                echo "<tr>";
                                echo "<td colspan='".totalSelectedCol(7,$selectedCols)."'style='text-align: center;font-style:italic'>$row->Keterangan</td>";
                                echo "</tr>";
                            }
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

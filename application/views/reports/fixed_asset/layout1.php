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
		<div>LAPORAN FIXED ASSET</div>
		<br>
        <?php
        $fin_branch_id = $this->input->post("fin_branch_id");
        $this->load->model("msbranches_model");
        $data = $this->msbranches_model->getBranchReport($fin_branch_id);
        $branch = $data["branch"];
        if($branch != null){
            $branch_name = $branch->fst_branch_name;
        }else{
            $branch_name = "ALL";
        }
        ?>
        <?php
        $fin_fa_group_id = $this->input->post("fin_fa_group_id");
        $this->load->model("msfagroups_model");
        $data = $this->msfagroups_model->getDataById($fin_fa_group_id);
        $group = $data;
        if($group != null){
            $group_name = $group->fst_fa_group_name;
        }else{
            $group_name = "ALL";
        }
        ?>
        <?php
		$start_date = $this->input->post("fdt_aquisition_date");
		?>
		<?php
		$end_date = $this->input->post("fdt_aquisition_date2");
		?>
        <div>Branch: <?= $branch_name ?>
		<div>Tgl Perolehan: <?= $start_date ?>  s/d <?= $end_date ?></div>
        <div>Group F/A: <?= $group_name ?>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2000px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Tgl Pemakaian</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:50px'>Kode F/A</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:200px'>Nama F/A</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Tgl Beli</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>No. Pembelian</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6 text-right' style='width:100px'>Nilai Perolehan</th>");
                        echoIfColSelected(7,$selectedCols,"<th class='col-7 text-right' style='width:100px'>Residu</th>");
                        echoIfColSelected(8,$selectedCols,"<th class='col-8 text-right' style='width:50px'>Umur(Bln)</th>");
                        echoIfColSelected(9,$selectedCols,"<th class='col-9 text-right' style='width:50px'>Rate(Thn)</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>P/C</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Lokasi terakhir</th>");
                        echoIfColSelected(12,$selectedCols,"<th class='col-12 text-right' style='width:100px'>Akumulasi susut</th>");
                        echoIfColSelected(13,$selectedCols,"<th class='col-13' style='width:50px'>Periode</th>");
                        echoIfColSelected(14,$selectedCols,"<th class='col-14 text-right' style='width:100px'>NAB</th>");
                        echoIfColSelected(15,$selectedCols,"<th class='col-15' style='width:50px'>Final</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $group_id = "";
					$profil_id = "";
					$Nilai_NAB = 0;
					$nou = 0;
					foreach ($dataReport as $row){
						if ( $group_id != $row->fin_fa_group_id){
							$group_id = $row->fin_fa_group_id;
							echo "<tr>";
							echo "<td colspan='".totalSelectedCol(16,$selectedCols)."'style='text-align: left;font-weight: bold'>F/A Group : $row->fst_fa_group_name</td>";
							echo "</tr>";
							$nou = 0;	   
						}

						$nou++;
                        $Nilai_Residu = formatNumber ($row->Nilai_Residu,2); 
						$Nilai_Perolehan = formatNumber ($row->Nilai_Perolehan,2);
						$fdc_depre_amount = formatNumber ($row->fdc_depre_amount,2);
						$Nilai_Sisa = $row->Nilai_Perolehan - $row->fdc_depre_amount;
						$Nilai_NAB = formatNumber($Nilai_Sisa,2);
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Aquisition_Date</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->FA_Code</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->FA_Name</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Lpb_Date</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Lpb_No</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$Nilai_Perolehan</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$Nilai_Residu</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Umur_FA</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->fst_depre_period</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->fst_pcc_name</td>");
						echoIfColSelected(11,$selectedCols,"<td class='col-11'>$row->fst_branch_name</td>");
						echoIfColSelected(12,$selectedCols,"<td class='col-12' style='text-align: right'>$fdc_depre_amount</td>");
						echoIfColSelected(13,$selectedCols,"<td class='col-13'>$row->fst_period</td>");
						echoIfColSelected(14,$selectedCols,"<td class='col-14' style='text-align: right'>$Nilai_NAB</td>");
						echoIfColSelected(15,$selectedCols,"<td class='col-15'>$row->fst_period</td>");			                                                                                                                                                                      
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

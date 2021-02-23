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
		<div>LAPORAN REKAPAN BIAYA</div>
		<br>
        <?php
		$start_date = $this->input->post("fdt_trx_datetime");
		?>
		<?php
		$end_date = $this->input->post("fdt_trx_datetime2");
		?>
        <?php
		$fin_branch_id = $this->input->post("fin_branch_id");
		$this->load->model('msbranches_model');
		$data = $this->msbranches_model->getBranchReport($fin_branch_id);
		$branch = $data ["branch"];
		if ($branch != null){
			$branch_name = $branch->fst_branch_name;
		}else{
			$branch_name = "ALL";
		}
		?>
        <?php
		$fin_pcc_id = $this->input->post("fin_pcc_id");
		$this->load->model('profitcostcenter_model');
		$data = $this->profitcostcenter_model->getDataById($fin_pcc_id);
		$pcc = $data ["profitcost_center"];
		if ($pcc != null){
			$pcc_name = $pcc->fst_pcc_name;
		}else{
			$pcc_name = "ALL";
		}
		?>
		<div>Branch      : <?= $branch_name ?></div>
        <div>Profit/Cost Center : <?= $pcc_name ?></div>
		<div>Tanggal     : <?= $start_date ?>  s/d <?= $end_date ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1000px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:200px'>Kode Akun</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:600px'>Nama Akun</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:200px'>Jumlah</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$account_code ="";
					$ttl_total_amount = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						$fdcJumlah = formatNumber ($row->fdcJumlah,2);
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->fst_account_code</td>");
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_glaccount_name</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2' style='text-align: right'>$fdcJumlah</td>");		                                                                                                                                                                      
						echo "</tr>";
                        $ttl_total_amount += $row->fdcJumlah;	
					}
					$ttl_total_amountNew = formatNumber ($ttl_total_amount, 2);   

					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(2,$selectedCols)."'style='text-align: right;font-weight: bold'>Total : </td>";
                    echoIfColSelected(2,$selectedCols,"<td class='col-2' style='text-align: right'>$ttl_total_amountNew</td>");									
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

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
		<div>LAPORAN JUMLAH PRINT FAKTUR PENJUALAN RINGKAS</div>
		<br>
		<?php
		$fin_warehouse_id = $this->input->post("fin_warehouse_id");
		$this->load->model("mswarehouse_model");
		$data = $this->mswarehouse_model->getDataById($fin_warehouse_id);
		$wareHouse = $data ["warehouse"];
		if ($wareHouse != null){
			$name_wareHouse = $wareHouse->fst_warehouse_name;
		}else{
			$name_wareHouse = "ALL";
		}
		?>
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
		<div>Gudang : <?= $name_wareHouse ?></div>
		<div>Tanggal Faktur: <?= $this->input->post("fdt_inv_datetime") ?>  s/d <?= $this->input->post("fdt_inv_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
        <div>Sales: <?= $name_sales ?></div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1700px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:150px'>No.Faktur</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Jatuh Tempo</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:150px'>No.S/O</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>GUD</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Sales</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:400px'>Pelanggan/Customer</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>M.U</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:150px'>Total IDR</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Entry By</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:150px'>Update By</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
					//$numOfRecs = count($dataReport);
					foreach ($dataReport as $row){
                        $nou++;
						echo "<tr>";
						$fdc_total = number_format ($row->fdc_total, 2, '.', ',');
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_Inv</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Inv_Date</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Jt_Date</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->No_SO</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Warehouse</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Sales_Name</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Relation_Name</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Mata_Uang</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9'style='text-align: right'>$fdc_total</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'style='text-align: right'>$row->Entry_BY</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'style='text-align: right'>$row->Update_BY</td>");										                                                                                                                                                                      
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

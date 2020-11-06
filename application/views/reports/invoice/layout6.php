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
		<div>LAPORAN SALES PER-FAKTUR PENJUALAN RINGKAS</div>
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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1300px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:100px'>Sales</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:50px'>No</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>No.Faktur</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:150px'>Tgl Faktur</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Jatuh Tempo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:150px'>No.S/O</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>GUD</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:300px'>Pelanggan/Customer</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:130px'>Total</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $nou = 0;
                    $idSales = "";
					$subttl = 0;
					$ttl_total = 0;
					$subttlNew = 0;
					$ttl_totalNew = 0;
					foreach ($dataReport as $row){
                        echo "<tr>";
                        if ( $idSales != $row->Sales_Id ){
                            if ($idSales != "") {
								//akumulasi total keseluruhan                            
                                $ttl_total += $subttl;
                                $ttl_totalNew = formatNumber($ttl_total,2);
                                $subttl = formatNumber ($subttl,2);                                
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total per-Sales:</td>";
                                    echoIfColSelected(8,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$subttl</td>");							
                                    echo "</tr>";
								//reset subtotal variable (break group)
                                $subttl = 0;
                                $nou = 0;
							}
                            $idSales = $row->Sales_Id;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Sales_Name</td>");	   
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                        }
                        $fdc_total = formatNumber ($row->fdc_total, 2);
                        $nou++;	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$nou</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->No_Inv</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Inv_Date</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Jt_Date</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->No_SO</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Warehouse</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Relation_Name</td>");
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'style='text-align: right'>$fdc_total</td>");										                                                                                                                                                                      
                        echo "</tr>";
                        $subttl += $row->fdc_total;
					}
                    $ttl_total += $subttl;
                    $subttl = formatNumber ($subttl,2); 
                    $ttl_totalNew = formatNumber ($ttl_total, 2); 
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total per-Sales:</td>";
                    echoIfColSelected(8,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$subttl</td>");							
                    echo "</tr>";
					echo "<tr>";
					echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan: </td>";
					echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'>$ttl_totalNew</td>");									
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

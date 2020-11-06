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
		<div>LAPORAN FAKTUR PENJUALAN PER-ITEM PER-CUSTOMER</div>
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
		<div>Tgl Faktur: <?= $this->input->post("fdt_inv_datetime") ?>  s/d <?= $this->input->post("fdt_inv_datetime2") ?></div> 
        <div>Customer: <?= $name_relations ?> </div>
        <div>Sales: <?= $name_sales ?></div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1100px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:300px'>Pelanggan/Customer</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:300px'>Nama Barang</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Qty</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Unit</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:150px'>Jumlah</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$idRelation = "";
					$subAmount = 0;
					$totalAmount = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idRelation != $row->Relation_Id ){
                            if ($idRelation != "") {
								//akumulasi total keseluruhan                            
                                $totalAmount += $subAmount;
                                $newtotalAmount = formatNumber($totalAmount,2);
                                $fdc_jumlah = formatNumber ($row->fdc_jumlah,2);
                                $subAmount = formatNumber ($subAmount,2);                                
                                //tulis subtotal per-group
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(5,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total :</td>";
                                    echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$subAmount</td>");							
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right'>_</td>";
                                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'></td>");						
                                    echo "</tr>";

								//reset subtotal variable (break group)
								$subAmount = 0;
							}
			
                            $idRelation = $row->Relation_Id;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");	   
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                        }
                        $fdc_jumlah = formatNumber ($row->fdc_jumlah,2);
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Item_Code</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Item_Name</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Ttl_Qty</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Unit</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$fdc_jumlah</td>");											                                                                                                                                                                      
                        echo "</tr>";
                        $subAmount += $row->fdc_jumlah;
                        $subAmountNew = formatNumber ($subAmount,2);
					}
                    $totalAmount += $subAmount;
                    $subAmount = formatNumber ($subAmount,2);
                    $newtotalAmount = formatNumber ($totalAmount,2);
                    
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(5,$selectedCols)."'style='text-align: right;font-weight: bold'>Sub total :</td>";
                    echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$subAmount</td>");							
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(6,$selectedCols)."'style='text-align: right'>_</td>";
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'></td>");						
                    echo "</tr>";

                    echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(5,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan : </td>";
                    echoIfColSelected(5,$selectedCols,"<td class='col-5'style='font-weight: bold;text-align: right'>$newtotalAmount</td>");							
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

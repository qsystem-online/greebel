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
		<div>LAPORAN LPB PURCHASE PER-ITEM PER-SUPPLIER</div>
		<br>
		<?php
		$fin_branch_id = $this->input->post("fin_branch_id");
        $this->load->model("msbranches_model");
		$branch = $this->msbranches_model->getBranchById($fin_branch_id);
		if ($branch != null){
			$name_branch = $branch->fst_branch_name;
		}else{
			$name_branch = "ALL";
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
		<div>Branch    : <?= $name_branch ?></div>
		<div>Tgl LPB   : <?= $this->input->post("fdt_lpbpurchase_datetime") ?>  s/d <?= $this->input->post("fdt_lpbpurchase_datetime2") ?></div> 
        <div>Supplier  : <?= $name_relations ?> </div>
		<div>Mata Uang : <?= $this->input->post("fst_curr_code") ?></div>                              
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:900px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
                        echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No</th>");
                        echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Kode Barang</th>");
                        echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:250px'>Nama Barang</th>");
                        echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Qty</th>");
                        echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:50px'>Unit</th>");
                        echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:150px'>Jumlah</th>");
                        echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Kurs</th>");
                        echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:150px'>Jumlah IDR</th>");	
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $idSupplier = "";
                    $nou = 0;
                    $qty_total= 0;
                    $sub_amount = 0;
                    $sub_amount_Idr = 0;
                    $ttl_qty = 0;
                    $ttl_amount = 0;
                    $ttl_amount_Idr = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
                        if ( $idSupplier != $row->fin_supplier_id ){
                            if ($idSupplier != "") {
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(3,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-Supplier :</td>";
                                    echoIfColSelected(3,$selectedCols,"<td class='col-3' style='font-weight: bold;text-align: right'>$qty_total_New</td>");
                                    echoIfColSelected(4,$selectedCols,"<td class='col-4' style='font-weight: bold;text-align: right'></td>");
                                    echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$sub_amount_New</td>");
                                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'></td>");
                                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$sub_amount_IdrNew</td>");							
                                    echo "</tr>";
                                    $nou = 0;
                                    $qty_total= 0;
                                    $sub_amount = 0;
                                    $sub_amount_Idr = 0;
                            }
                            $idSupplier = $row->fin_supplier_id;
                            echo "<tr>";
                            echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->Relation_Name</td>";
                            echo "</tr>";
                            echo "<tr>";
                            $nou++;
                            $Amount = formatNumber ($row->Amount,2);
                            $Amount_Idr = formatNumber ($row->Amount_Idr,2);
                            $Rate_Idr = formatNumber ($row->Rate_Idr,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Item_Code</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Item_Name</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3' style='text-align: right'>$row->Qty</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Unit</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$Amount</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$Rate_Idr</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$Amount_Idr</td>");
                            echo "</tr>";	   
                        }else{
                            $nou++;
                            $Amount = formatNumber ($row->Amount,2);
                            $Amount_Idr = formatNumber ($row->Amount_Idr,2);
                            $Rate_Idr = formatNumber ($row->Rate_Idr,2);
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Item_Code</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Item_Name</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3' style='text-align: right'>$row->Qty</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Unit</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$Amount</td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$Rate_Idr</td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$Amount_Idr</td>");
                        }
                        $qty_total += $row->Qty;
                        $sub_amount += $row->Amount;
                        $sub_amount_Idr += $row->Amount_Idr;
                        $qty_total_New = formatNumber ($qty_total, 2);
                        $sub_amount_New = formatNumber ($sub_amount, 2);
                        $sub_amount_IdrNew = formatNumber ($sub_amount_Idr, 2);
                        $ttl_qty += $row->Qty;
                        $ttl_amount += $row->Amount;
                        $ttl_amount_Idr += $row->Amount_Idr;
					}
					$ttl_qty_New = formatNumber ($ttl_qty, 2);
					$ttl_amount_New = formatNumber ($ttl_amount, 2);
                    $ttl_amount_IdrNew = formatNumber ($ttl_amount_Idr, 2); 

					echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(3,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Per-Supplier :</td>";
                    echoIfColSelected(3,$selectedCols,"<td class='col-3' style='font-weight: bold;text-align: right'>$qty_total_New</td>");
                    echoIfColSelected(4,$selectedCols,"<td class='col-4' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$sub_amount_New</td>");
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$sub_amount_IdrNew</td>");								
					echo "</tr>";
                    
					echo "<tr>";
                    echo "<td colspan='".totalSelectedCol(3,$selectedCols)."'style='text-align: right;font-weight: bold'>Total Keseluruhan :</td>";
                    echoIfColSelected(3,$selectedCols,"<td class='col-3' style='font-weight: bold;text-align: right'>$ttl_qty_New</td>");
					echoIfColSelected(4,$selectedCols,"<td class='col-4' style='font-weight: bold;text-align: right'></td>");
					echoIfColSelected(5,$selectedCols,"<td class='col-5' style='font-weight: bold;text-align: right'>$ttl_amount_New</td>");
                    echoIfColSelected(6,$selectedCols,"<td class='col-6' style='font-weight: bold;text-align: right'></td>");
                    echoIfColSelected(7,$selectedCols,"<td class='col-7' style='font-weight: bold;text-align: right'>$ttl_amount_IdrNew</td>");							
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

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
		<div>LAPORAN PENERIMAAN BARANG PEMBELIAN DAN ASSEMBLING DETAIL</div>
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
		$fin_supplier_id = $this->input->post("fin_supplier_id");
		if ($fin_supplier_id >"0"){
			$this->load->model("msrelations_model");
			$data = $this->msrelations_model->getDataById($fin_supplier_id);
			$ms_relations = $data ["ms_relations"];
			$name_relations = $ms_relations->fst_relation_name;
		}else{
			$name_relations = "ALL";
		}
		?>
		<?php
		$fin_item_id = $this->input->post("fin_item_id");
		$this->load->model('msitems_model');
		$data = $this->msitems_model->getDataById($fin_item_id);
		$item = $data ["ms_items"];
		if ($item != null){
			$name_item = $item->fst_item_name;
		}else{
			$name_item = "ALL";
		}
		?>
		<div>Gudang        : <?= $name_wareHouse ?></div>
		<div>Tgl Penerimaan: <?= $this->input->post("fdt_lpbgudang_datetime") ?>  s/d <?= $this->input->post("fdt_lpbgudang_datetime2") ?></div> 
        <div>Supplier      : <?= $name_relations ?> </div>
        <div>Item          : <?= $name_item ?></div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:2300px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:100px'>No.Penerimaan</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Tgl.Penerimaan</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>No.PO/Assembling</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Tgl</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:300px'>Memo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>GUD</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>No.Pembelian</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:100px'>Tgl.Pembelian</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:300px'>Nama Barang</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Qty</th>");
                        echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:50px'>Unit</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$Id_LpbGudang = "";
                    $idSupplier = "";
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $Id_LpbGudang != $row->Id_LpbGudang ){
                            $Id_LpbGudang = $row->Id_LpbGudang;
                            if ( $idSupplier != $row->fin_supplier_id ){
                                $idSupplier = $row->fin_supplier_id;
								//echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Relation_Name</td>");
								echo "<tr>";
								echo "<td colspan='".totalSelectedCol(12,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->Supplier_Name</td>";
								echo "</tr>";	   
							}
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->No_Penerimaan</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Penerimaan_Date</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->No_PO</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->PO_Date</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->LpbGudang_Memo</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Warehouse</td>");
							if($row->Lpb_Type =='PO'){
								echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->No_LPB</td>");
								echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->LPB_Date</td>");
							}else{
								echoIfColSelected(6,$selectedCols,"<td class='col-6'>Assembling In</td>");
								echoIfColSelected(7,$selectedCols,"<td class='col-7'>Assembling In</td>");
							}
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
                            echoIfColSelected(6,$selectedCols,"<td class='col-6'></td>");
                            echoIfColSelected(7,$selectedCols,"<td class='col-7'></td>");
							
							
                        }
                        $Qty = formatNumber ($row->Qty,2);
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Item_Code</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->Item_Name</td>");
						echoIfColSelected(10,$selectedCols,"<td class='col-10' style='text-align: right'>$Qty</td>");
						echoIfColSelected(11,$selectedCols,"<td class='col-11'>$row->Unit</td>");											                                                                                                                                                                      
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

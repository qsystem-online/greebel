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
		<div>LAPORAN PENERIMAAN BARANG RETURN PER-NO.PENERIMAAN</div>
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
		$fin_customer_id = $this->input->post("fin_customer_id");
		if ($fin_customer_id >"0"){
			$this->load->model("msrelations_model");
			$data = $this->msrelations_model->getDataById($fin_customer_id);
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
		<div>Tgl Penerimaan: <?= $this->input->post("fdt_lpbsalesreturn_datetime") ?>  s/d <?= $this->input->post("fdt_lpbsalesreturn_datetime2") ?></div> 
        <div>Customer      : <?= $name_relations ?> </div>
        <div>Item          : <?= $name_item ?></div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1900px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:100px'>No.Penerimaan</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>Tgl.Penerimaan</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:300px'>Memo</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:80px'>GUD</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:300px'>Customer/Pelanggan</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:300px'>Nama Barang</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>Qty</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Unit</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:100px'>No.Invoice</th>");
                        echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:100px'>Tgl.Invoice</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$Id_LpbReturn = "";
                    $idCustomer = "";
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $Id_LpbReturn != $row->Id_LpbReturn ){
                            $Id_LpbReturn = $row->Id_LpbReturn;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->No_Penerimaan</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Penerimaan_Date</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->LpbReturn_Memo</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Warehouse</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Customer_Name</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                        }
                        $Qty = formatNumber ($row->Qty,2);                       
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Item_Code</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Item_Name</td>");
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'style='text-align: right'>$Qty</td>");
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Unit</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->No_Inv</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Inv_Date</td>");												                                                                                                                                                                      
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

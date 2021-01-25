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
		<div>LAPORAN PERSEDIAAN SEMUA GUDANG</div>
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
		$start_date = $this->input->post("fdt_from");
		if ($start_date != null){
			$start_date = $start_date;
		}else{
			$start_date = "1900-01-01";
		}
		?>
		<?php
		$end_date = $this->input->post("fdt_to");
		if ($end_date != null){
			$end_date = $end_date;
		}else{
			$end_date = "3000-01-01";
		}
		?>
		<div>Gudang : <?= $name_wareHouse ?></div>
		<div>Tanggal: <?= $start_date ?>  s/d <?= $end_date ?></div>                             
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">      
			<thead>
				<tr style="background-color:navy;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:30px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:50px'>Kode Item</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Nama Item</th>");
                        echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:50px'>Unit</th>");
                        $ssql = "Select fin_warehouse_id,fst_warehouse_name,fst_active from mswarehouse where fst_active = 'A' ";
                        $qr = $this->db->query($ssql,[]);
                        $rs = $qr->result();
                        $i = 3;
                        $selectedCols = 17;
                        $warehouse_id = "";
                        foreach($rs as $rw){
                            $i = $i + 1;
                            echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:30px'>$rw->fst_warehouse_name</th>");
                            //$i = $i + 1;
                        }
                            
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					$itemId ="";
					$nou = 0;
					$newItem = true;
					foreach ($dataReport as $row){
                        echo "<tr>";
                        $nou++;
						if ($row->fst_basic_unit == null ){
							$fst_basic_unit = '???';
						}else{
							$fst_basic_unit = $row->fst_basic_unit;
						}
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->fst_item_code</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->fst_item_name</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'style='text-align: right'>$fst_basic_unit</td>");  										                                                                                                                                                                      
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

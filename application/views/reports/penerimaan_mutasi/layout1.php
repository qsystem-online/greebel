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
		<div>LAPORAN PAG DETAIL</div>
		<br>
        <?php
		$fin_branch_id = $this->input->post("fin_branch_id");
		$this->load->model("msbranches_model");
		$data = $this->msbranches_model->getBranchReport($fin_branch_id);
		$branch = $data ["branch"];
		if ($branch != null){
			$branch_name = $branch->fst_branch_name;
		}else{
			$branch_name = "ALL";
		}
		?>

        <?php
		$fin_type_id = $this->input->post("fin_type_id");
        switch($fin_type_id){
            case "0":
                $jenis_mag ="ALL";
                break;
            case "1":
                $jenis_mag ="PRODUKSI W/O";
                break;
            case "2":
                $jenis_mag ="PRODUKSI NON-W/O";
                break;
            case "3":
                $jenis_mag ="STANDARD";
                break;

        }
		?>

		<?php
		$fin_from_warehouse_id = $this->input->post("fin_from_warehouse_id");
		$this->load->model("mswarehouse_model");
		$data = $this->mswarehouse_model->getDataById($fin_from_warehouse_id);
		$wareHouse = $data ["warehouse"];
		if ($wareHouse != null){
			$name_from_wareHouse = $wareHouse->fst_warehouse_name;
		}else{
			$name_from_wareHouse = "ALL";
		}
		?>

		<?php
		$fin_to_warehouse_id = $this->input->post("fin_to_warehouse_id");
		$this->load->model("mswarehouse_model");
		$data = $this->mswarehouse_model->getDataById($fin_to_warehouse_id);
		$wareHouse = $data ["warehouse"];
		if ($wareHouse != null){
			$name_to_wareHouse = $wareHouse->fst_warehouse_name;
		}else{
			$name_to_wareHouse = "ALL";
		}
		?>
        <div>Branch: <?= $branch_name ?> </div>
        <div>Jenis MAG: <?= $jenis_mag ?> </div>
		<div>Asal : <?= $name_from_wareHouse ?></div>
        <div>Tujuan : <?= $name_to_wareHouse ?></div>
		<div>Tgl: <?= $this->input->post("fdt_datetime") ?>  s/d <?= $this->input->post("fdt_datetime2") ?></div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1500px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:20px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No.PAG</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tgl.PAG</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Gud. Asal</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Gud. Tujuan</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:250px'>Memo</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Kode Barang</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:150px'>Nama Barang</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Qty PAG</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Unit</th>");
					?>
				</tr>
			</thead>
			<tbody>
				<?php
                    $idPAG = "";
					$nou = 0;
                    $Qty_PAG = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $idPAG != $row->fin_mag_confirm_id ){		
                            $idPAG = $row->fin_mag_confirm_id;
							$nou++;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_PAG</td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->PAG_Date</td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->warehouse_from</td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->warehouse_to</td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Memo_PAG</td>");
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                            echoIfColSelected(1,$selectedCols,"<td class='col-1'></td>");
                            echoIfColSelected(2,$selectedCols,"<td class='col-2'></td>");
                            echoIfColSelected(3,$selectedCols,"<td class='col-3'></td>");
                            echoIfColSelected(4,$selectedCols,"<td class='col-4'></td>");
                            echoIfColSelected(5,$selectedCols,"<td class='col-5'></td>");
							
                        }
                        $Qty_PAG = formatnumber($row->Qty_confirm,2);
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>&nbsp;$row->Item_Code</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Item_Name</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8' style='text-align: right'>$Qty_PAG</td>");
                        echoIfColSelected(9,$selectedCols,"<td class='col-9' style='text-align: right'>$row->Unit</td>");										                                                                                                                                                                      
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

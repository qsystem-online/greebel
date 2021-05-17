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
		<div>LAPORAN PURCHASE REQUEST O/S PO</div>
		<br>
		<?php
		$fin_department_id = $this->input->post("fin_req_department_id");
		$this->load->model("msdepartments_model");
		$data = $this->msdepartments_model->getDataById($fin_department_id);
		$department = $data ["departments"];
		if ($department != null){
			$name_department = $department->fst_department_name;
		}else{
			$name_department = "ALL";
		}
		?>
		<div>Department : <?= $name_department ?></div>
		<div>Tgl Request: <?= $this->input->post("fdt_pr_datetime") ?>  s/d <?= $this->input->post("fdt_pr_datetime2") ?></div>                            
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:20px'>No.</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No. PR</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Tgl. Request</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:300px'>Nama Barang</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:50px'>Qty Request</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Qty PO</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>Unit</th>");
					?>
				</tr>
			</thead>
			<tbody>
                <?php
					$id_Department = "";
					$id_PR = "";
					$nou = 0;
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $id_Department != $row->fin_req_department_id ){
                            if ($id_Department != "") {
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: right'>.</td>";
                                    echoIfColSelected(8,$selectedCols,"<td class='col-8' style='font-weight: bold;text-align: right'></td>");						
                                    echo "</tr>";
							}
                            $id_Department = $row->fin_req_department_id;
                            //echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Request_By</td>");
                            echo "<tr>";
							echo "<td colspan='".totalSelectedCol(8,$selectedCols)."'style='text-align: left;font-weight: bold'>$row->Request_By</td>";
							echo "</tr>";
							$nou = 0;
                        }
						$nou++;
						echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_PR</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->PR_Date</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Item_Code</td>");
						echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->Item_Name</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$row->Qty_Req</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$row->Qty_Po</td>");
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Unit</td>");	
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

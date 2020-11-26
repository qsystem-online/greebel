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
		<div>LAPORAN PURCHASE REQUEST DETAIL</div>
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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1800px">      
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:200px'>Department</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:20px'>No</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>No PR</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:130px'>Tgl Request</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:300px'>Memo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Kode Barang</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:400px'>Nama Barang</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:50px'>Qty Request</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:50px'>Qty Process</th>");
						echoIfColSelected(9,$selectedCols,"<th class='col-9' style='width:50px'>Qty Distribute</th>");
						echoIfColSelected(10,$selectedCols,"<th class='col-10' style='width:50px'>Unit</th>");
						echoIfColSelected(11,$selectedCols,"<th class='col-11' style='width:100px'>Tgl Distribusi</th>");
					?>
				</tr>
			</thead>
			<tbody>
                <?php
					$id_Department = "";
					foreach ($dataReport as $row){
						echo "<tr>";
						if ( $id_Department != $row->fin_req_department_id ){
                            if ($id_Department != "") {
                                    echo "<tr>";
                                    echo "<td colspan='".totalSelectedCol(11,$selectedCols)."'style='text-align: right'>*</td>";
                                    echoIfColSelected(11,$selectedCols,"<td class='col-11' style='font-weight: bold;text-align: right'></td>");						
                                    echo "</tr>";
							}
                            $id_Department = $row->fin_req_department_id;
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Request_By</td>");	   
						}else{
                            echoIfColSelected(0,$selectedCols,"<td class='col-0'></td>");
                        }
						echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Item_Code</td>");
						echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->No_PR</td>");
						echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->PR_Date</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->PR_Memo</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Item_Code</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Item_Name</td>");
                        echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Qty_Req</td>");
                        echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->Qty_Process</td>");
						echoIfColSelected(9,$selectedCols,"<td class='col-9'>$row->Qty_Distribute</td>");
                        echoIfColSelected(10,$selectedCols,"<td class='col-10'>$row->Unit</td>");
                        echoIfColSelected(11,$selectedCols,"<td class='col-11'>$row->fdt_distribute_datetime</td>");		
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

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
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1400px">       
			<thead>
				<tr style="background-color:RoyalBlue;color:white">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:50px'>No</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:100px'>No.PR</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:150px'>Tgl Request</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:150px'>Tgl Publish</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:400px'>Memo</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>By Department</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:50px'>Close</th>");
						echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:150px'>Tgl Close</th>");
						echoIfColSelected(8,$selectedCols,"<th class='col-8' style='width:200px'>Info Close</th>");
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
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$nou</td>");	   
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->No_PR</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->PR_Date</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3'>$row->Publish_Date</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4'>$row->PR_Memo</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5'>$row->Request_By</td>");
						echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->fbl_rejected</td>");
						echoIfColSelected(7,$selectedCols,"<td class='col-7'>$row->Publish_Date</td>");
						echoIfColSelected(8,$selectedCols,"<td class='col-8'>$row->fst_rejected_note</td>");										                                                                                                                                                                      
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

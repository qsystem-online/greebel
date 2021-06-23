<html>
	<head>
		<!-- jQuery 3 -->
		<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>        
	</head>

	<body id="bodyReport">
		<style>
            td{
				border:1px solid;
				border-color: rgb(0,0,255,0.25);
			}
            .first-col{
                width:80px;
            }
            .last-col{
                width:80px;
            }
            .headerteks{
            text-align: center;
            }		
		</style>
        <?php 
        $idWO = "";
		$Qty_Wo = 0;
		$Qty_Lhp = 0;
        foreach ($dataReport as $row){?>
            <?php if ($idWO != $row->Wo_Id){?>
				<?php $Qty_Wo = formatNumber($row->Qty_Wo,2);?>
				<?php $Qty_Lhp = formatNumber($row->Qty_Lhp,2);?>
                <div class="row"><h2 class="headerteks">WORK ORDER - REALISASI PEMAKAIAN BAHAN</h2></div>
                <div class="row"><h3 class="headerteks"><?= $row->Wo_No ?></h3></div>
                <!--<div class="row">
                    <div class="col" style="width:100%">
                        <div class="col">Tanggal : <?=date("d-M-Y",strtotime($row->Wo_Date))?></div>
                    </div>
                    <div class="col" style="width:100%">
                        <div class="col">Target : <?=date("d-M-Y",strtotime($row->Target_Date))?></div>
                    </div>	
                </div>-->
				<table class="stripe row-border order-column" cellpadding="0" cellspacing="0" style="width:1200px">      
					<thead>
						<tr>
							<?php
								echo "<th class='col-0' style='width:50px;text-align: left'>Tanggal W/O</th>";
								echo "<th class='col-1' style='width:300px;text-align: left'>: $row->Wo_Date</th>";
								echo "<th class='col-2' style='width:50px;text-align: left'></th>";
								echo "<th class='col-3' style='width:50px;text-align: left'></th>";
								echo "<th class='col-4' style='width:50px;text-align: left'>Target</th>";
								echo "<th class='col-5' style='width:100px;text-align: left'>: $row->Target_Date</th>";
							?>
						</tr>
						<tr>
							<?php
								echo "<th class='col-0' style='width:50px;text-align: left'>Group</th>";
								echo "<th class='col-1' style='width:300px;text-align: left'>: $row->Group_Product</th>";
								echo "<th class='col-2' style='width:50px;text-align: left'></th>";
								echo "<th class='col-3' style='width:50px;text-align: left'></th>";
								echo "<th class='col-4' style='width:50px;text-align: left'>Qty W/O</th>";
								echo "<th class='col-5' style='width:100px;text-align: left'>: $Qty_Wo</th>";
							?>
						</tr>
						<tr>
							<?php
								echo "<th class='col-0' style='width:50px;text-align: left'>Produk Jadi</th>";
								echo "<th class='col-1' style='width:300px;text-align: left'>: $row->Name_Product</th>";
								echo "<th class='col-2' style='width:50px;text-align: left'></th>";
								echo "<th class='col-3' style='width:50px;text-align: left'></th>";
								echo "<th class='col-4' style='width:50px;text-align: left'>Qty LHP</th>";
								echo "<th class='col-5' style='width:100px;text-align: left'>: $Qty_Lhp</th>";
							?>
						</tr>
						<tr>
							<?php
								echo "<th class='col-0' style='width:50px;text-align: left'></th>";
								echo "<th class='col-1' style='width:300px;text-align: left'></th>";
								echo "<th class='col-2' style='width:50px;text-align: left'></th>";
								echo "<th class='col-3' style='width:50px;text-align: left'></th>";
								echo "<th class='col-4' style='width:50px;text-align: left'>Unit</th>";
								echo "<th class='col-5' style='width:100px;text-align: left'>: $row->Unit_Product</th>";
							?>
						</tr>
					</thead>
				</table>
                <?php $idWO = $row->Wo_Id;?>
            <?php }?>
        <?php }?> 
		<br>                       
		<table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px;border:1px solid #000">      
			<thead>
				<tr style="background-color:white;color:black">
					<?php
						echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:150px'>Kode</th>");
						echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:500px'>Nama Bahan</th>");
						echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Qty</th>");
						echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Total</th>");
						echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Qty Realisasi</th>");
						echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:100px'>Qty Selisih</th>");
						echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:30px'>Unit</th>");
					?>
				</tr>
			</thead>
			<tbody>
            <?php
				$Qty_Total = 0;
				$Qty_Selisih = 0;
				$Rm_Item_Id = "";
                foreach ($dataReport as $row){
					$Rm_Item_Id = $row->Id_Bom;
					$ssql = "SELECT IFNULL(SUM(b.fdb_qty),0) AS Qty_Real FROM trrmout a INNER JOIN trrmoutitems b ON a.fin_rmout_id = b.fin_rmout_id WHERE a.fin_wo_id = '".$idWO."' AND b.fin_item_id = '".$Rm_Item_Id."' AND a.fst_active = 'A' ";
					$qr = $this->db->query($ssql);
					//echo $this->db->last_query();
					//die();
					$rw = $qr->row();
					$Qty_Total = $row->Qty_Wo * $row->Qty_Bom;
					$Qty_TotalNew = formatNumber($Qty_Total,4);
					$Qty_RealNew = formatNumber($rw->Qty_Real,4);
					$Qty_Selisih = ($Qty_Total - $rw->Qty_Real);
					$Qty_SelisihNew = formatNumber($Qty_Selisih,4);
                    echo "<tr>";
                    echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Code_Bom</td>");
                    echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Name_Bom</td>");
                    echoIfColSelected(2,$selectedCols,"<td class='col-2'>$row->Qty_Bom</td>");
                    echoIfColSelected(3,$selectedCols,"<td class='col-3'>$Qty_TotalNew</td>");
                    echoIfColSelected(4,$selectedCols,"<td class='col-4'>$Qty_RealNew</td>");
                    echoIfColSelected(5,$selectedCols,"<td class='col-5'>$Qty_SelisihNew</td>");
					echoIfColSelected(6,$selectedCols,"<td class='col-6'>$row->Unit_Bom</td>");						
                    echo "</tr>";
					$Qty_Total = 0;
                }
            ?>
			</tbody>
		</table>
	</body>
	<htmlpagefooter name="AssigmentFooter">							
		<div class="row"><h5 class="footerteks">Dibuat Oleh,</h5></div>
        <div class="row"><h5 class="footerteks">............</h5></div>
	</htmlpagefooter>
	<sethtmlpagefooter name="AssigmentFooter" value="ON"/>

	<script type="text/javascript">
		$(function(){
			//$('.col-2').remove();
			//$("#tblReport").css("display","table");

		});
		
		//$('thead tr').find('td:eq(4),th:eq(4)').remove();
		//$('tbody tr').find('td:eq(4),th:eq(4)').remove();
	</script>
</html>

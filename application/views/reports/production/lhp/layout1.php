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
            .padd-header {
                padding: 2px;
            }		
		</style>
        <?php 
        $idWO = "";
		$Qty_Wo = 0;
        $Qty_BaseWo = 0;
		$Qty_Lhp = 0;
        $Qty_Sisa = 0;
        foreach ($dataReport as $row){?>
            <?php if ($idWO != $row->Wo_Id){?>
				<?php $Qty_Wo = formatNumber($row->Qty_Wo,2);?>
                <?php $idWO = $row->Wo_Id;?>
                <div class="row"><h3 class="headerteks">LAPORAN HASIL PRODUKSI</h3></div>
                <div class="row"><h4 class="headerteks"><?= $row->Wo_No ?>-(<?= $row->Wo_Date ?>)</h4></div>
                <table id="tblReport" cellpadding="0" cellspacing="0" style="width:1200px;border:1px solid #000">      
                    <thead>
                        <tr style="background-color:white;color:black">
                            <?php
                                echoIfColSelected(0,$selectedCols,"<th class='col-0' style='width:150px'>Kode</th>");
                                echoIfColSelected(1,$selectedCols,"<th class='col-1' style='width:500px'>Produk Jadi</th>");
                                echoIfColSelected(2,$selectedCols,"<th class='col-2' style='width:100px'>Qty WO</th>");
                                echoIfColSelected(3,$selectedCols,"<th class='col-3' style='width:100px'>Qty Prod</th>");
                                echoIfColSelected(4,$selectedCols,"<th class='col-4' style='width:100px'>Sisa Target</th>");
                                echoIfColSelected(5,$selectedCols,"<th class='col-5' style='width:30px'>Unit</th>");
                                echoIfColSelected(6,$selectedCols,"<th class='col-6' style='width:100px'>Qty Prod</th>");
                                echoIfColSelected(7,$selectedCols,"<th class='col-7' style='width:30px'>Unit</th>");
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $ssql = "SELECT fin_wo_id,COALESCE(SUM(fdb_qty),0) AS Qty_Prod,fst_unit AS Unit_Lhp,COALESCE(SUM(fdb_qty_baseonwo),0) AS Qty_BaseWo,fst_wo_unit FROM trlhp WHERE fin_wo_id = '".$idWO."' AND fst_active = 'A' ";
                        $qr = $this->db->query($ssql);
                        //echo $this->db->last_query();
                        //die();
                        $rw = $qr->row();
                        $Qty_Sisa = formatNumber(($row->Qty_Wo - $rw->Qty_BaseWo),4);
                        $Qty_BaseWo = formatNumber($rw->Qty_BaseWo,4);
                        $Qty_Lhp = formatNumber($rw->Qty_Prod,4);
                        echo "<tr>";
                        echoIfColSelected(0,$selectedCols,"<td class='col-0'>$row->Code_Product</td>");
                        echoIfColSelected(1,$selectedCols,"<td class='col-1'>$row->Name_Product</td>");
                        echoIfColSelected(2,$selectedCols,"<td class='col-2' style='text-align: right'>$Qty_Wo</td>");
                        echoIfColSelected(3,$selectedCols,"<td class='col-3' style='text-align: right'>$Qty_BaseWo</td>");
                        echoIfColSelected(4,$selectedCols,"<td class='col-4' style='text-align: right'>$Qty_Sisa</td>");
                        echoIfColSelected(5,$selectedCols,"<td class='col-5' style='text-align: right'>$rw->fst_wo_unit</td>");
                        echoIfColSelected(6,$selectedCols,"<td class='col-6' style='text-align: right'>$Qty_Lhp</td>");
                        echoIfColSelected(7,$selectedCols,"<td class='col-7' style='text-align: right'>$rw->Unit_Lhp</td>");						
                        echo "</tr>";
                    ?>
                    </tbody>
                </table>
                <div class="row"><h4><u>MEMO:</u> &nbsp; <?= $row->fst_notes ?></h4></div>
                <br>
            <?php }?>
        <?php }?>                     
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

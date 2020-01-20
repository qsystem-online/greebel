<!-- Modal -->
<style>
	#tblJurnal tbody tr td.C{
		padding-left:70px;
	}
	#tblJurnal tfoot tr td{
		font-weight:bold;
	}
	#tblJurnal thead tr td{
		font-weight:bold;
	}
</style>
<div id="mdlJurnal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:900px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Jurnal Transaksi") ?></h4>
			</div>
			<div class="modal-body">
				<h4 id="fstTrxNo"></h4>
				<table id="tblJurnal" style="width:100%" class="table table-bordered table-hover table-striped dataTable">					
					<thead>
						<tr>
							<th style="width:10px"></th>
							<th>Account</th>
							<th style="width:15%" class="text-right">Debet (IDR)</th>
							<th style="width:15%" class="text-right">Credit (IDR)</th>
							
						</tr>
					</thead>
					<tbody>
					
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
	var MdlJurnal = {
		generateDetail:function(data){
			var result =  "";
			result +=  "<div>Profit/Cost Center :" + data.pccName + "</div>";
			result +=  "<div>Analisa Divisi :" + data.fst_pc_divisi_name + "</div>";
			result +=  "<div>Analisa Customer :" + data.fst_pc_customer_name + "</div>";
			result +=  "<div>Analisa Project :" + data.fst_pc_project_name + "</div>";
			return result;
		},
		showJurnal:function(arrData,fstTrxNo){
			ttlDebet = 0;
			ttlCredit =0;

			fstTrxNo = typeof fstTrxNo == "undefined" ? "" : fstTrxNo;

			$("#tblJurnal > tbody").empty();
			$("#tblJurnal > tfoot").empty();

			$.each(arrData,function(i,v){
				ttlDebet += v.debet;
				ttlCredit += v.credit;
				$("#tblJurnal > tbody").append("<tr class='row_rec' id='rec_"+ v.fin_rec_id +"'><td><i class='fa fa-caret-right' aria-hidden='true'></i></td><td class='"+ v.pos + "'>" + v.code + " - " + v.name + "</td><td class='text-right'>"+money_format(v.debet)+"</td><td class='text-right'>"+money_format(v.credit)+"</td></tr>");
				$("#tblJurnal > tbody").append("<tr id='sub_rec_"+ v.fin_rec_id +"' style='display:none'><td colspan='4'>" + MdlJurnal.generateDetail(v) +"</td></tr>");

			});
			$("#fstTrxNo").text(fstTrxNo);

			$("#tblJurnal > tfoot").append("<tr><td class='text-right' colspan='2'>Total</td><td class='text-right'>"+money_format(ttlDebet)+"</td><td class='text-right'>"+money_format(ttlCredit)+"</td></tr>");
			$("#mdlJurnal").modal({
				backdrop:"static",
			});
		},

		showJurnalByRef:function(fstTrxSourceCode,finTrxId){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>api/get_jurnal/" + fstTrxSourceCode +"/" + finTrxId,
				method:"GET"
			}).done(function(resp){
				arrData = resp.data;
				arrDataNormalisasi = [];
				trxNo  ="";
				$.each(arrData,function(i,data){
					trxNo =  data.fst_trx_no;
					var pos = parseFloat(data.fdc_debit) > 0 ? "D" : "C";
					arrDataNormalisasi.push({
						fin_rec_id : data.fin_rec_id,
						code: data.fst_account_code,
						name: data.fst_glaccount_name,
						pos: pos,
						debet: parseFloat(data.fdc_debit),
						credit:	parseFloat(data.fdc_credit),
						pccName: (data.fst_pcc_name) == null ? " - " : data.fst_pcc_name,
						fst_pc_divisi_name: (data.fst_pc_divisi_name) == null ? " - " : data.fst_pc_divisi_name,
						fst_pc_customer_name: (data.fst_pc_customer_name) == null ? " - " : data.fst_pc_customer_name,
						fst_pc_project_name :(data.fst_pc_project_name) == null ? " - " : data.fst_pc_project_name						
					});					
				});
				MdlJurnal.showJurnal(arrDataNormalisasi,trxNo);
			}).always(function(){				
			});

		}
	}

	$(function(){
		$('.modal-dialog').draggable();
		$("#tblJurnal").on("click",".row_rec",function(e){
			e.preventDefault();
			var id = $(this).attr('id');
			$("#sub_" + id).toggle();
			
		});
	});

</script>
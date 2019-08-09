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
	<div class="modal-dialog" style="width:800px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Jurnal Transaksi</h4>
			</div>
			<div class="modal-body">
				<table id="tblJurnal" style="width:100%" class="table table-bordered table-hover table-striped dataTable">					
					<thead>
						<tr>
							<th style="width:60%">Account</th>
							<th style="width:20%" class="text-right">Debet</th>
							<th style="width:20%" class="text-right">Credit</th>
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

<script type="TEXT/JAVASCRIPT">
$(function(){
$('.modal-dialog').draggable();
});


function showJurnal(arrData){
		ttlDebet = 0;
		ttlCredit =0;
		$("#tblJurnal > tbody").empty();
		$("#tblJurnal > tfoot").empty();

		$.each(arrData,function(i,v){
			ttlDebet += v.debet;
			ttlCredit += v.credit;

			$("#tblJurnal > tbody").append("<tr><td class='"+ v.pos + "'>" + v.code + " - " + v.name + "</td><td class='text-right'>"+money_format(v.debet)+"</td><td class='text-right'>"+money_format(v.credit)+"</td></tr>");
		});

		$("#tblJurnal > tfoot").append("<tr><td class='text-right'>Total</td><td class='text-right'>"+money_format(ttlDebet)+"</td><td class='text-right'>"+money_format(ttlCredit)+"</td></tr>");
		$("#mdlJurnal").modal({
			backdrop:"static",
		});
	}
</script>
<!-- Modal -->
<style>
	#mdlStock tbody tr td.C{
		padding-left:70px;
	}
	#mdlStock tfoot tr td{
		font-weight:bold;
	}
	#mdlStock thead tr td{
		font-weight:bold;
	}
</style>
<div id="mdlStock" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:500px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Item Stock") ?></h4>
			</div>
			<div class="modal-body">
				<h4 id="fstTrxNo"></h4>
				<table id="mdlstock_tbl" style="width:100%" class="table table-bordered table-hover table-striped dataTable">					
					<thead>
						<tr>
							<th colspan="2"> 
								<span><?=lang("Item Name")?> : </span>
								<span id="mdlStock_itemName">Nama Item</span>
								<span style='margin-left:20px'><?=lang("Unit")?> : </span>
								<span id="mdlStock_itemUnit">Unit Item</span>								
							</th>
						</tr>
						<tr>
							<th>Branch</th>
							<th style="width:15%" class="text-right">Qty Stock</th>							
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
	
	var MdlStock = {
		itemName:"",		
		show: function(finItemId,fstUnit){
			buildData(finItemId,fstUnit);
			$("#mdlStock").modal("show");
		},		
	}

	$(function(){
		$('.modal-dialog').draggable();
		$("#mdlStock").on("click",".row_rec",function(e){
			e.preventDefault();
			var id = $(this).attr('id');
			$("#sub_" + id).toggle();
			
		});
	});

	function buildData(finItemId,fstUnit){
		$("#mdlStock_itemName").html(MdlStock.itemName);
		$("#mdlStock_itemUnit").html(fstUnit);
		$.ajax({
			url:"<?=site_url()?>master/item/ajx_get_list_stock/" + finItemId +"/"+fstUnit,
			method:"GET",
		}).done(function(resp){
			if (resp.status == "SUCCESS"){
				var listStock = resp.data.stock_list;
				$("#mdlstock_tbl tbody").empty();
				$.each(listStock, function (i,v){

					var sstr = "<tr>";
					sstr += "<td>"+v.fst_warehouse_name+"</td>";
					sstr += "<td>"+v.fdb_qty_balance_after+"</td>";
					sstr += "<tr>";
					$("#mdlstock_tbl tbody").append(sstr);
				});
			}
		});
	}

</script>
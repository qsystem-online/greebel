<!-- Modal -->
<style>
	#tblNeedAuthorize tbody tr td.C{
		padding-left:70px;
	}
	#tblNeedAuthorize tfoot tr td{
		font-weight:bold;
	}
	#tblNeedAuthorize thead tr td{
		font-weight:bold;
	}
</style>
<div id="mdlConfirmAuthorize" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:900px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Transaksi butuh autorisasi") ?></h4>
			</div>
			<div class="modal-body">
				<table id="tblNeedAuthorize" style="width:100%" class="table table-bordered table-hover table-striped dataTable">	

					<thead>
						<tr>
							<th style="width:100%">Message</th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
					<tfoot>
					</tfoot>
				</table>
                <div class="text-right">
                    <button id="mdlConfirmAuthorize-ok" class="btn btn-primary">OK</button>
                    <button id="mdlConfirmAuthorize-cancel" class="btn btn-secondary">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	var MdlConfirmAuthorize = {
		show:function(arrData,okCallback,cancelCallback){
			
			$("#tblNeedAuthorize > tbody").empty();
			$("#tblNeedAuthorize > tfoot").empty();

			$.each(arrData,function(i,message){			
				$("#tblNeedAuthorize > tbody").append("<tr><td>" + message + "</td></tr>");
			});
	
			$("#mdlConfirmAuthorize").modal({
				backdrop:"static",
            });
        },
        authorizeCallback:function(){},
        cancelCallback:null,        
	}

	$(function(){
        //$('.modal-dialog').draggable();
        $("#mdlConfirmAuthorize-ok").click(function(e){
            e.preventDefault();
            MdlConfirmAuthorize.authorizeCallback();
        });
        $("#mdlConfirmAuthorize-cancel").click(function(e){
            e.preventDefault();
            if (MdlConfirmAuthorize.cancelCallback == null){
                $("#mdlConfirmAuthorize").modal("hide");
            }else{
                MdlConfirmAuthorize.cancelCallback();
            }
        });
        
	});

</script>
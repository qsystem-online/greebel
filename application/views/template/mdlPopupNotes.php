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
<div id="mdlPopupNotes" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:500px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Notes") ?></h4>
			</div>
			<div class="modal-body">
                <div class="row">
                        <div class="col-md-12" >
                            <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                                <fieldset style="padding:10px">                    
                                    <form id="form-detail" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="fin_trans_id" class="col-md-2 control-label"><?=lang("Notes")?></label>
                                            <div class="col-md-10">
                                                <textarea id="mdlPopupNotes-fst_notes" class="form-control" style="width:100%" rows="4"></textarea>
                                            </div>
                                        </div>									
                                    </form>								
                                    <div class="modal-footer">
                                        <button id="mdlPopupNotes-btn-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("save")?></button>
                                        <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    var mdlPopupNotes_callback;

	var MdlPopupNotes = {
        showNotes:function(notes,callback){
            mdlPopupNotes_callback = callback;

            $("#mdlPopupNotes").modal({
				backdrop:"static",
			});
        }
	}

	$(function(){
		$('.modal-dialog').draggable();
        $("#mdlPopupNotes-btn-save").click(function(e){
            e.preventDefault();
            mdlPopupNotes_callback($("#mdlPopupNotes-fst_notes").val());
            $("#mdlPopupNotes").modal("hide");
        });

	});

</script>
<div id="mdlEditForm" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:700px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
								<form id="form-detail" class="form-horizontal">
									<input type='hidden' id='fin_po_detail_id'/>
									<div class="form-group">
										<label for="fst_trans_type" class="col-md-2 control-label"><?=lang("Request by")?></label>
										<div class="col-md-10">
											<select id="fst_edit_request_by_user" class="form-control" style="width:100%">
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="fin_trans_id" class="col-md-2 control-label"><?=lang("Notes")?></label>
										<div class="col-md-10">
											<textarea id="fst_edit_reason" class="form-control" style="width:100%" rows="4"></textarea>
										</div>
									</div>									
								</form>								
								<div class="modal-footer">
									<button id="btn-edit-popup-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("save")?></button>
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
	MdlEditForm = {
		show: function(){
			
			App.getValueAjax({
				site_url:"<?=site_url()?>",
				model:"users_model",
				func:"getAllList",
				params:[],
				callback:function(users){
					console.log(users);
					$.each(users,function(i,user){
						$("#fst_edit_request_by_user").append("<option value='"+user.fin_user_id+"'>"+user.fst_username+"</option>");
					});
				}
			});

			$("#fst_edit_request_by_user").val(MdlEditForm.user);
			$("#fst_edit_reason").val(MdlEditForm.notes);			

			$("#mdlEditForm").modal("show");

		},
		user:"",
		notes:"",
		saveCallBack: null,		
	};

	$(function(){
		$("#btn-edit-popup-save").click(function(e){
			e.preventDefault();


			MdlEditForm.user = $("#fst_edit_request_by_user").val();
			MdlEditForm.notes = $("#fst_edit_reason").val();			

			if (MdlEditForm.user == "" || MdlEditForm.notes == ""){
				alert("Silahkan masukan request by dan notes untuk melakukan penyimpanan !");	
			}else{
				$("#mdlEditForm").modal("hide");
				MdlEditForm.user=$("#fst_edit_request_by_user").val();
				MdlEditForm.notes =$("#fst_edit_reason").val();
				MdlEditForm.saveCallBack();
			}
			
		});
	});

</script>
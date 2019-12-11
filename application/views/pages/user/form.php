<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">

<style type="text/css">
	.border-0{
		border: 0px;
	}
	td{
		padding: 2px; !important 		
	}

    .nav-tabs-custom>.nav-tabs>li.active>a{
        font-weight:bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style:fixed;
    }
    .nav-tabs-custom>.nav-tabs{
        border-bottom-color: #3c8dbc;        
        border-bottom-style:fixed;
    }
</style>

<section class="content-header">
	<h1><?=lang("User")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("User") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrinted" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
			<!-- end box header -->
			
			<!-- form start -->
            <form id="frmUser" class="form-horizontal" action="<?=site_url()?>user/add" method="POST" enctype="multipart/form-data">				
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
                    <input type="hidden" id="frm-mode" value="<?=$mode?>">

                    <div class='form-group'>
                    <label for="fin_user_id" class="col-sm-2 control-label"><?=lang("User ID")?></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fin_user_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_user_id" value="<?=$fin_user_id?>" readonly>
							<div id="fin_user_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_username" class="col-sm-2 control-label"><?=lang("User Name")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fst_username" placeholder="<?=lang("Username")?>" name="fst_username" value="<?= set_value("fst_username") ?>">
							<div id="fst_username_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_fullname" class="col-sm-2 control-label"><?=lang("Full Name")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fst_fullname" placeholder="<?=lang("Full Name")?>" name="fst_fullname">
							<div id="fst_fullname_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_gender" class="col-sm-2 control-label"><?=lang("Gender")?></label>
						<div class="col-sm-4">
							<select class="form-control" id="fst_gender" name="fst_gender">
								<option value='M'><?=lang("Male")?></option>
								<option value='F'><?=lang("Female")?></option>
							</select>
						</div>

						<label for="fst_email" class="col-md-2 control-label"><?=lang("Email")?> *</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_email" placeholder="<?=lang("Email")?>" name="fst_email">
							<div id="fst_email_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdt_birthdate" class="col-sm-2 control-label text-right"><?=lang("Birth Date")?> *</label>
						<div class="col-sm-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datepicker" id="fdt_birthdate" name="fdt_birthdate"/>								
							</div>
							<div id="fdt_birthdate_err" class="text-danger"></div>
						</div>

						<label for="fst_birthplace" class="col-sm-2 control-label text-right"><?=lang("Birth Place")?></label>
						<div class="col-sm-4">
							<input type="text" class="form-control text-right" id="fst_birthplace" placeholder="<?=lang("Birth Place")?>" name="fst_birthplace">
							<div id="fst_birthplace_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_address" class="col-md-2 control-label"><?=lang("Address")?> *</label>
						<div class="col-md-10" row="10" cols="50">
							<textarea class="form-control" id="fst_address" placeholder="<?=lang("Address")?>" name="fst_address"></textarea>
							<div id="fst_address_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_phone" class="col-md-2 control-label"><?=lang("Phone")?> *</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_phone" placeholder="<?=lang("Phone")?>" name="fst_phone">
							<div id="fst_phone_err" class="text-danger"></div>
						</div>

						<label for="fin_branch_id" class="col-md-2 control-label"><?=lang("Branch")?></label>
						<div class="col-md-4">
							<select class="form-control select2" id="fin_branch_id" name="fin_branch_id"></select>
							<div id="fin_branch_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="select-departmentname" class="col-md-2 control-label"><?=lang("Department ID")?></label>
						<div class="col-md-4">
							<select id="select-departmentname" class="form-control" name="fin_department_id"></select>
							<div id="fst_department_name_err" class="text-danger"></div>
						</div>
                    
                        <label for="fin_group_id" class="col-md-2 control-label"><?=lang("Group")?></label>
						<div class="col-md-4">
							<select class="form-control select2" id="fin_group_id" name="fin_group_id"></select>
							<div id="fin_group_id_err" class="text-danger"></div>
						</div>
                    </div>

					<div class="form-group">
						<label for="fst_birthplace" class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
							<img id="imgAvatar" style="border:1px solid #999;width:128px;" src="<?=site_url()?>assets/app/users/avatar/default.jpg"/>
						</div>
					</div>
					<div class="form-group">
						<label for="fst_birthplace" class="col-sm-2 control-label"><?=lang("Avatar")?></label>
						<div class="col-sm-4">
							<input type="file" class="form-control" id="fst_avatar"  name="fst_avatar">
						</div>
					</div>

					<div class="form-group">
						<label for="fbl_admin" class="col-sm-2 control-label"><?=lang("Admin")?></label>
						<div class="col-md-2">
							<label class="checkbox-inline"><input id="fbl_admin" name='fbl_admin' type="checkbox">Admin</label>
						</div>
					</div>
				</div>
				<!-- end box-body -->
				<div class="box-footer">
					
				</div>
				<!-- end box-footer -->			
			</form>
		</div>
	</div>
</section>

<div id="modal_Printed" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:60%;min-width:600px;max-width:100%">
        <!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("User List") ?></h4>
			</div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="select-branch_R" class="col-md-3 control-label"><?= lang("Branch") ?> :</label>
                                    <div class="col-md-7">
                                        <select id="select-branch_R" class="form-control" name="fin_branch_id">
                                        </select>
                                        <div id="fin_branch_id_err" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="select-department_R" class="col-md-3 control-label"><?= lang("Department") ?> :</label>
                                    <div class="col-md-7">
                                        <select id="select-department_R" class="form-control" name="fin_department_id">
                                        </select>
                                        <div id="fin_department_id_err" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="select-userId_start" class="col-md-3 control-label"><?= lang("User ID") ?> :</label>
                                    <div class="col-md-3">
                                        <select id="select-userId_start" class="form-control" name="fin_user_id">
                                            <option value="0">--  <?= lang("select") ?>  --</option>
                                        </select>
                                        <div id="fin_user_id_err" class="text-danger"></div>
                                    </div>
                                    <label for="select-userId_end" class="col-md-1 control-label"><?= lang("s/d") ?> :</label>
                                    <div class="col-md-3">
                                        <select id="select-userId_end" class="form-control" name="fin_user_id">
                                            <option value="0">--  <?= lang("select") ?>  --</option>
                                        </select>
                                        <div id="fin_user_id_err" class="text-danger"></div>
                                    </div>
                                </div>
                            </form>

                            <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                <button id="btnPrint" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Print")?></button>
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

<?php
    echo $mdlPrint;
?>

<script type="text/javascript">
    $(function() {
        branchList = [];
        <?php foreach($arrBranch as $branch){ ?>
            branchList.push({
                "id":"<?= $branch->fin_branch_id ?>",
                "text":"<?= $branch->fst_branch_name ?>"
            });  
        <?php } ?>

		groupList = [];
        <?php foreach($arrGroup as $group){ ?>
            groupList.push({
                "id":"<?= $group->fin_group_id ?>",
                "text":"<?= $group->fst_group_name ?>"
            });  
        <?php } ?>

		userList_R = [];
        <?php foreach($arrUser_R as $user_R){ ?>
            userList_R.push({
                "id":"<?= $user_R->fin_user_id ?>",
                "text":"<?= $user_R->fst_username ?>"
            });  
        <?php } ?>
		<?php if($mode == "EDIT"){?>
			init_form($("#fin_user_id").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmUser")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>user/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>user/ajx_edit_save";
			}

			App.blockUIOnAjaxRequest("Please wait while saving data.....");
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: url,
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (resp) {	
					if (resp.message != "")	{
						$.alert({
							title: 'Message',
							content: resp.message,
							buttons : {
								OK : function(){
									if(resp.status == "SUCCESS"){
										window.location.href = "<?= site_url() ?>user/add";
										return;
									}
								},
							}
						});
					}

					if(resp.status == "VALIDATION_FORM_FAILED" ){
						//Show Error
						errors = resp.data;
						for (key in errors) {
							$("#"+key+"_err").html(errors[key]);
						}
					}else if(resp.status == "SUCCESS") {
						data = resp.data;
						$("#fin_user_id").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#fst_username').prop('readonly', true);
						$("#tabs-user-detail").show();
						console.log(data.data_image);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});

		$("#fst_avatar").change(function(event){
			event.preventDefault();
			var reader = new FileReader();
			reader.onload = function (e) {
               $("#imgAvatar").attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
		});

		/*$(".datepicker").datepicker({
			format:"yyyy-mm-dd"
		});*/

		$("#select-departmentname").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>user/get_department',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.fin_department_id,
							"text" : value.fst_department_name
						});	
					});
					console.log(data2);
					return {
						results: data2
					};
				},
				cache: true,
			}
		});

		$("#fin_branch_id").select2({
            width: '100%',
            data: branchList
        });

		$("#fin_group_id").select2({
            width: '100%',
            data: groupList
        });

		$("#select-branch_R").select2({
            width: '100%',
			allowClear: true,           
            placeholder: 'ALL',
            data: branchList
        });

		$("#select-department_R").select2({
			width: '100%',
			allowClear: true,           
            placeholder: 'ALL',
			ajax: {
				url: '<?=site_url()?>user/get_department',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.fin_department_id,
							"text" : value.fst_department_name
						});	
					});
					console.log(data2);
					return {
						results: data2
					};
				},
				cache: true,
			}
		});

		$("#select-userId_start").select2({
            width: '100%',
            data: userList_R
        });

		$("#select-userId_end").select2({
            width: '100%',
            data: userList_R
        });

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>user/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>user/delete/" + $("#fin_user_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>user/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_user_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_username').prop('readonly', true);
				}
			});
		});

		$("#btnPrinted").click(function(e){
			$("#modal_Printed").modal("toggle");
		});

		$("#btnPrint").click(function(e){
			layoutColumn = [
				{column: "Birth date",hidden:false,id:"fdt_birthdate"},
                {column: "Birth place",hidden:false,id:"fst_birthplace"},
				{column: "Address",hidden:false,id:"fst_address"},
				{column: "Phone",hidden:false,id:"fst_phone"},
				{column: "Email",hidden:false,id:"fst_email"},
			];
			url = "<?= site_url() ?>user/get_printUser/" + $("#select-branch_R").val() + '/' + $("#select-department_R").val() + '/' + $("#select-userId_start").val() + '/' + $("#select-userId_end").val();
            MdlPrint.showPrint(layoutColumn,url);
        });

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>user/lizt");
		});
	});


	function init_form(fin_user_id){
		//alert("Init Form");
		var url = "<?=site_url()?>user/fetch_data/" + fin_user_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.user);

				$.each(resp.user, function(name, val){
					var $el = $('[name="'+name+'"]'),
					type = $el.attr('type');
					switch(type){
						case 'checkbox':
							$el.attr('checked', 'checked');
							break;
						case 'radio':
							$el.filter('[value="'+val+'"]').attr('checked', 'checked');
							break;
						default:
							$el.val(val);
							console.log(val);
					}
				});

				$("#fdt_birthdate").datepicker('update', dateFormat(resp.user.fdt_birthdate));

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.user.fst_department_name, resp.user.fin_department_id, true, true);
    			// Append it to the select
    			$('#select-departmentname').append(newOption).trigger('change');

				var newOption = new Option(resp.user.fst_group_name, resp.user.fin_group_id, true, true);
    			// Append it to the select
    			$('#fin_group_id').append(newOption).trigger('change');

				var newOption = new Option(resp.user.fst_branch_name, resp.user.fin_branch_id, true, true);
    			// Append it to the select
    			$('#fin_branch_id').append(newOption).trigger('change');

				//Image Load 
				$('#imgAvatar').attr("src",resp.user.avatarURL);

				//populate Group (select2)
				/*var groups = [];
				$.each(resp.list_group, function(name, val){
					groups.push(val.fin_group_id);
				})
				$("#fin_group_id").val(groups).trigger("change");*/
			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
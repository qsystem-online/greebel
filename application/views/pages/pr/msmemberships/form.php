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
	<h1><?=lang("Master Memberships")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Master Memberships") ?></a></li>
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
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmMSMemberShips" class="form-horizontal" action="<?=site_url()?>pr/membership/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

                    <div class="form-group">
                    <label for="fin_rec_id" class="col-md-2 control-label"><?=lang("Rec ID")?> #</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fin_rec_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_rec_id" value="<?=$fin_rec_id?>" readonly>
							<div id="fin_rec_id_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
                    <label for="fst_member_no" class="col-md-2 control-label"><?=lang("Member No")?> *</label>
                        <div class="col-md-10">
                        <input type="text" class="form-control" id="fst_member_no" placeholder="<?=lang("Member No")?>" name="fst_member_no">
							<div id="fst_member_no_err" class="text-danger"></div>
                        </div>
                    </div>

                    <div class="form-group">
                    <label for="fin_relation_id" class="col-md-2 control-label"><?=lang("Relation Name")?> *</label>
                        <div class="col-md-4">
                            <select id="select-relationId" class="form-control" name="fin_relation_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_relation_id_err" class="text-danger"></div>
                        </div>

					<label for="select-MemberGroup" class="col-md-2 control-label"><?=lang("Member Group Name")?> :</label>
						<div class="col-md-4">
							<select id="select-MemberGroup" class="form-control" name="fin_member_group_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="nama_err" class="text-danger"></div>
						</div>
                    </div>

                    <div class="form-group">
                    <label for="fst_name_on_card" class="col-md-2 control-label"><?=lang("Name On Card")?> </label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_name_on_card" placeholder="<?=lang("Name On Card")?>" name="fst_name_on_card">
							<div id="fst_name_on_card_err" class="text-danger"></div>
						</div>
                    </div>

                    <div class="form-group">
					<label for="fdt_expiry_date" class="col-md-2 control-label"><?=lang("Expiry Date")?> </label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right datepicker" id="fdt_expiry_date" name="fdt_expiry_date"/>								
							</div>
							<div id="fdt_expiry_date_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>

                    <label for="fdc_member_discount_percent" class="col-md-2 control-label"><?=lang("Member Discount")?> (%) </label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" id="fdc_member_discount_percent" placeholder="<?=lang("Member Discount")?>" name="fdc_member_discount_percent">
							<div id="fdc_member_discount_percent_err" class="text-danger"></div>
						</div>
                    </div>
                </div>
                <!-- end box-body -->

                <div class="box-footer text-right">
                    
                </div>

            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function(){

        <?php if($mode == "EDIT"){?>
			init_form($("#fin_rec_id").val());
		<?php } ?>

        $("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmMSMemberShips")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/membership/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/membership/ajx_edit_save";
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
										$("#btnNew").trigger("click");
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
						$("#fin_rec_id").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#fst_relation_name').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});

		$("#select-MemberGroup").select2({
			width: '100%',
			//minimumInputLength: 2,
			ajax: {
				url: '<?=site_url()?>pr/membership/get_MemberGroup',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.fin_member_group_id,
							"text" : value.fst_member_group_name
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

        $("#select-relationId").select2({
			width: '100%',
			//minimumInputLength: 2,
			ajax: {
				url: '<?=site_url()?>pr/membership/get_relations',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name
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

        $("#fdc_member_discount_percent").inputmask({
			alias : 'numeric',
			allowMinus : false,
			digits : 2,
			max : 100
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>pr/membership/add")
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
				url:"<?= site_url() ?>pr/membership/delete/" + $("#fin_rec_id").val(),
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
									window.location.href = "<?= site_url() ?>pr/membership/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_rec_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_relation_name').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>pr/membership/lizt");
		});
    })

    function init_form(fin_rec_id){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/membership/fetch_data/" + fin_rec_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.ms_memberships);

				$.each(resp.ms_memberships, function(name, val){
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

				$("#fdt_expiry_date").datepicker('update', dateFormat(resp.ms_memberships.fdt_expiry_date));

                // menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.ms_memberships.fst_relation_name, resp.ms_memberships.fin_relation_id, true, true);
				// Append it to the select
    			$('#select-relationId').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_memberships.fst_member_group_name, resp.ms_memberships.fin_member_group_id, true, true);
				$('#select-MemberGroup').append(newOption).trigger('change');

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
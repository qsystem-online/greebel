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
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmMSMemberShips" class="form-horizontal" action="<?=site_url()?>pr/msmemberships/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

                    <div class="form-group">
                    <label for="RecId" class="col-md-2 control-label"><?=lang("Rec ID")?> #</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RecId" placeholder="<?=lang("(Autonumber)")?>" name="RecId" value="<?=$RecId?>" readonly>
							<div id="RecId_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
                    <label for="MemberNo" class="col-md-2 control-label"><?=lang("Member No")?> *</label>
                        <div class="col-md-10">
                        <input type="text" class="form-control" id="MemberNo" placeholder="<?=lang("Member No")?>" name="MemberNo">
							<div id="MemberNo_err" class="text-danger"></div>
                        </div>
                    </div>

                    <div class="form-group">
                    <label for="RelationId" class="col-md-2 control-label"><?=lang("Relation Name")?> *</label>
                        <div class="col-md-4">
                            <select id="select-relationId" class="form-control" name="RelationId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="RelationId_err" class="text-danger"></div>
                        </div>

                    <label for="MemberGroupId" class="col-md-2 control-label"><?=lang("Member Group ID")?> </label>
                        <div class="col-md-4">
                        <input type="text" class="form-control" id="MemberGroupId" placeholder="<?=lang("Member Group ID")?>" name="MemberGroupId">
							<div id="MemberGroupId_err" class="text-danger"></div>
                        </div>
                    </div>

                    <div class="form-group">
                    <label for="NameOnCard" class="col-md-2 control-label"><?=lang("Name On Card")?> </label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="NameOnCard" placeholder="<?=lang("Name On Card")?>" name="NameOnCard">
							<div id="NameOnCard_err" class="text-danger"></div>
						</div>
                    </div>

                    <div class="form-group">
					<label for="ExpiryDate" class="col-md-2 control-label"><?=lang("Expiry Date")?> </label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right datepicker" id="ExpiryDate" name="ExpiryDate"/>								
							</div>
							<div id="ExpiryDate_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>

                    <label for="MemberDiscount" class="col-md-2 control-label"><?=lang("Member Discount")?> (%) </label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="MemberDiscount" placeholder="<?=lang("Member Discount")?>" name="MemberDiscount">
							<div id="MemberDiscount_err" class="text-danger"></div>
						</div>
                    </div>
                </div>
                <!-- end box-body -->

                <div class="box-footer text-right">
                    <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?=lang("Save Ajax")?></a>
                </div>

            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function(){

        <?php if($mode == "EDIT"){?>
			init_form($("#RecId").val());
		<?php } ?>

        $("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmMSMemberShips")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/msmemberships/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/msmemberships/ajx_edit_save";
			}

			//var formData = new FormData($('form')[0])
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
										window.location.href = "<?= site_url() ?>pr/msmemberships/lizt";
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
						$("#RecId").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#RelationName').prop('readonly', true);
						//$("#tabs-relation-detail").show();
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}

			});
		});

        $("#select-relationId").select2({
			width: '100%',
			//minimumInputLength: 2,
			ajax: {
				url: '<?=site_url()?>pr/msmemberships/get_relations',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.RelationId,
							"text" : value.RelationName
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

        $("#MemberDiscount").inputmask({
			alias : 'numeric',
			allowMinus : false,
			digits : 2,
			max : 100
		});
    })

    function init_form(RecId){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/msmemberships/fetch_data/" + RecId;
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

				$("#ExpiryDate").datepicker('update', dateFormat(resp.ms_memberships.ExpiryDate));

                // menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.ms_memberships.RelationName, resp.ms_memberships.RelationId, true, true);
				// Append it to the select
    			$('#select-relationId').append(newOption).trigger('change');

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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

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
	<h1><?=lang("Master Countries")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Master Countries") ?></a></li>
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
            <form id="frmMSCountries" class="form-horizontal" action="<?=site_url()?>pr/mscountries/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class='form-group'>
                    <label for="CountryId" class="col-sm-2 control-label"><?=lang("Country ID")?></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="CountryId" placeholder="<?=lang("(Autonumber)")?>" name="CountryId" value="<?=$CountryId?>" readonly>
							<div id="CountryId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="CountryName" class="col-sm-2 control-label"><?=lang("Country Name")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="CountryName" placeholder="<?=lang("Country Name")?>" name="CountryName">
							<div id="CountryName_err" class="text-danger"></div>
						</div>
					</div>
                </div>
				<!-- end box body -->

                <div class="box-footer">
                    <a id="btnSubmitAjax" href="#" class="btn btn-primary">Save Ajax</a>
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#CountryId").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmMSCountries")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/mscountries/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/mscountries/ajx_edit_save";
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
										window.location.href = "<?= site_url() ?>pr/mscountries/lizt";
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
						$("#CountryId").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#CountryName').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});

		});
	});

	function init_form(CountryId){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/mscountries/fetch_data/" + CountryId;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.mscountries);

				$.each(resp.mscountries, function(name, val){
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
					}
				});
			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}
</script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

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
	.is-promo{
		color:#ff0000;
		background-color:#ed8fa9 !important;
	}
</style>

<section class="content-header">
	<h1><?=lang("Master Sales Regional")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Master Sales Regional") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title title"><?= $title ?></h3>
                </div>
                <!-- end box header -->

                <!-- form start -->
                <form id="frmSalRegional" class="form-horizontal" action="<?= site_url() ?>master/sales_area/add_regional" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class="form-group">
                            <label for="fin_sales_regional_id" class="col-sm-2 control-label"><?=lang("Sales Regional ID")?> :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fin_sales_regional_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_sales_regional_id" value="<?= $fin_sales_regional_id ?>" readonly>
                                <div id="fin_sales_regional_id_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_name" class="col-sm-2 control-label"><?=lang("Name")?> :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fst_name" placeholder="<?= lang("Name")?>" name="fst_name">
                                <div id="fst_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-National" class="col-md-2 control-label"><?=lang("Sales National Name")?> :</label>
                            <div class="col-md-4">
                                <select id="select-National" class="form-control" name="fin_sales_national_id">
                                    <option value="0">-- <?=lang("select")?> --</option>
                                </select>
                                <div id="fin_sales_national_id_err" class="text-danger"></div>
                            </div>

                            <label for="select-salesId" class="col-md-2 control-label"><?=lang("Sales Name")?> :</label>
                            <div class="col-md-4">
                                <select id="select-salesId" class="form-control" name="fin_sales_id">
                                    <option value="0">-- <?=lang("select")?> --</option>
                                </select>
                                <div id="fin_sales_id_err" class="text-danger"></div>
                            </div>
                        </div>
                        <!-- end box body -->

                        <div class="box-footer text-right">
                            <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?=lang("Save Record")?></a>
                        </div>
                        <!-- end box-footer -->
                    </div>
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_sales_regional_id").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = $("#frmSalRegional").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/sales_area/reg_add_save";
            } else {
                url = "<?= site_url() ?>master/sales_area/reg_edit_save";
            }
            console.log(data);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url,
                data: data,
                timeout: 600000,
                success: function(resp) {
                    if (resp.message != "") {
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons: {
                                OK: function() {
                                    if (resp.status == "SUCCESS") {
                                        //location.reload();
                                        window.location.href = "<?= site_url() ?>master/sales_area/regional/lizt";
                                        return;
                                    }
                                },
                            }
                        });
                    }

                    if (resp.status == "VALIDATION_FORM_FAILED") {
                        //Show Error
                        errors = resp.data;
                        for (key in errors) {
                            $("#" + key + "_err").html(errors[key]);
                        }
                    } else if (resp.status == "SUCCESS") {
                        data = resp.data;
                        $("#fin_sales_regional_id").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $("#fst_name").prop('readonly', true);

                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#select-salesId").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>master/sales_area/get_salesId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					data2 = [];
					data = data.data;
					$.each(data,function(index,value){
						data2.push({
							"id" : value.fin_user_id,
							"text" : value.fst_username
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

        $("#select-National").select2({
            width: '100%',
			ajax: {
				url: '<?=site_url()?>master/sales_area/get_National',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					data2 = [];
					data = data.data;
					$.each(data,function(index,value){
						data2.push({
							"id" : value.fin_sales_national_id,
							"text" : value.fst_name
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
    });

    function init_form(fin_sales_regional_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/sales_area/regional_data/" + fin_sales_regional_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.sales_area_regional);

                $.each(resp.sales_area_regional, function(name, val) {
                    var $el = $('[name="' + name + '"]'),
                        type = $el.attr('type');
                    switch (type) {
                        case 'checkbox':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        case 'radio':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        default:
                            $el.val(val);
                            console.log(val);
                    }
                });

                var newOption = new Option(resp.sales_area_regional.SalesName, resp.sales_area_regional.fin_sales_id, true, true);
				$('#select-salesId').append(newOption).trigger('change');

                var newOption = new Option(resp.sales_area_regional.NationalName, resp.sales_area_regional.fin_sales_national_id, true, true);
                $('#select-National').append(newOption).trigger('change');
            },

            error: function(e) {
                $("#result").text(e.responseText);
                console.log("ERROR : ", e);
            }
        });
    }
</script>

<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
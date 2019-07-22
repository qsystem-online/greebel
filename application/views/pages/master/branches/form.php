<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">

<section class="content-header">
    <h1><?= lang("Menus") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Menus") ?></a></li>
        <li class="active title"><?= $title ?></li>
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
                <form id="frmBranch" class="form-horizontal" action="<?= site_url() ?>master/branch/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_branch_id" class="col-sm-2 control-label"><?= lang("Branch ID") ?> :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fin_branch_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_branch_id" value="<?= $fin_branch_id ?>" readonly>
                                <div id="fin_branch_id_err" class="text-danger"></div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_branch_name" class="col-sm-2 control-label"><?= lang("Branch Name") ?> :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fst_branch_name" placeholder="<?= lang("Branch Name") ?>" name="fst_branch_name">
                                <div id="fst_branch_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_address" class="col-md-2 control-label"><?= lang("Address") ?> :</label>
                            <div class="col-md-10">
                                <textarea class="form-control" id="fst_address" placeholder="<?= lang("Address") ?>" name="fst_address"></textarea>
                                <div id="fst_address_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-country" class="col-md-2 control-label"><?= lang("Country Name") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-country" class="form-control" name="fin_country_id">
                                    <option value="0">-- <?= lang("select") ?> --</option>
                                </select>
                                <div id="fin_country_id_err" class="text-danger"></div>
                            </div>

                            <label for="select-provinces" class="col-md-2 control-label"><?= lang("Province Name") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-provinces" class="form-control" name="fst_kode">
                                    <option value="0">-- <?= lang("select") ?> --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-district" class="col-md-2 control-label"><?= lang("District Name") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-district" class="form-control" name="fst_kode">
                                    <option value="0">-- <?= lang("select") ?> --</option>
                                </select>
                            </div>

                            <label for="select-subdistrict" class="col-md-2 control-label"><?= lang("Sub District Name") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-subdistrict" class="form-control" name="fst_kode">
                                    <option value="0">-- <?= lang("select") ?> --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_postalcode" class="col-md-2 control-label"><?= lang("Postal Code") ?> :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_postalcode" placeholder="<?= lang("Postal Code") ?>" name="fst_postalcode">
                                <div id="fst_postalcode_err" class="text-danger"></div>
                            </div>

                            <label for="fst_branch_phone" class="col-sm-2 control-label"><?= lang("Phone") ?> :</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fst_branch_phone" placeholder="<?= lang("Phone") ?>" name="fst_branch_phone">
                                <div id="fst_branch_phone_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_notes" class="col-sm-2 control-label"><?= lang("Notes") ?> :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fst_notes" placeholder="<?= lang("Notes") ?>" name="fst_notes">
                                <div id="fst_notes_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fbl_is_hq" class="col-sm-2 control-label"><?= lang("HQ") ?> :</label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_hq" type="checkbox" name="fbl_is_hq" value="1"><?= lang("HQ") ?></label><br>
                                <div id="fbl_is_hq_err" class="text-danger" style="padding-left:200px"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end box body -->

                    <div class="box-footer text-right">
                        <a id="btnSubmitAjax" href="#" class="btn btn-primary">Save Record</a>
                    </div>
                    <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_branch_id").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmBranch")[0]);
            data = $("#frmBranch").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/branch/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/branch/ajx_edit_save";
            }

            //var formData = new FormData($('form')[0])
            $.ajax({
                type: "POST",
                //enctype: 'multipart/form-data',
                url: url,
                data: data,
                //processData: false,
                //contentType: false,
                //cache: false,
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
                                        window.location.href = "<?= site_url() ?>master/branch/lizt";
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
                        $("#fin_branch_id").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fst_branch_name').prop('readonly', true);

                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#select-country").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_countries',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_country_id,
							"text" : value.fst_country_name
						});
					});
					console.log(items);
					return {
						results: items
					};
				},
				cache: true,
			}
		});

		$("#select-country").change(function(event){
			event.preventDefault();
			$('#select-provinces').val(null).trigger('change');
			$("#select-provinces").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_provinces/'+$("#select-country").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
							});
						});
						console.log(items);
						return {
							results: items
						};
					},
					cache: true,
				}
			});
		});

		$("#select-provinces").change(function(event){
			event.preventDefault();
			$('#select-district').val(null).trigger('change');
			$("#select-district").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_districts/'+$("#select-provinces").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
							});
						});
						console.log(items);
						return {
							results: items
						};
					},
					cache: true,
				}
			});
		});

		$("#select-district").change(function(event){
			event.preventDefault();
			$('#select-subdistrict').val(null).trigger('change');
			$("#select-subdistrict").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_subdistricts/'+$("#select-district").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
							});
						});
						console.log(items);
						return {
							results: items
						};
					},
					cache: true,
				}
			});
		});

		$("#select-subdistrict").change(function(event){
			event.preventDefault();
			$('#select-village').val(null).trigger('change');
			$("#select-village").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_village/'+$("#select-subdistrict").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
							});
						});
						console.log(items);
						return {
							results: items
						};
					},
					cache: true,
				}
			});
		});


    });

    function init_form(fin_branch_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/branch/fetch_data/" + fin_branch_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.branches);

                $.each(resp.branches, function(name, val) {
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

                // menampilkan data di select2, menu edit/update
                var newOption = new Option(resp.branches.fst_country_name, resp.branches.fin_country_id, true, true);
                // Append it to the select
                $('#select-country').append(newOption).trigger('change');

                var newOption = new Option(resp.branches.fst_province_name, resp.branches.province, true, true);
                $('#select-provinces').append(newOption).trigger('change');

                var newOption = new Option(resp.branches.fst_district_name, resp.branches.district, true, true);
                $('#select-district').append(newOption).trigger('change');

                var newOption = new Option(resp.branches.fst_subdistrict_name, resp.branches.subdistrict, true, true);
                $('#select-subdistrict').append(newOption).trigger('change');
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
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
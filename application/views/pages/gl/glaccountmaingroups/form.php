<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">

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
                <form id="frmGLMaingroup" class="form-horizontal" action="<?= site_url() ?>GLAccountMainGroupId" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="GLAccountMainGroupId" class="col-sm-2 control-label"><?= lang("GL Main Group ID") ?> #</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="GLAccountMainGroupId" placeholder="<?= lang("(Autonumber)") ?>" name="GLAccountMainGroupId" value="<?= $GLAccountMainGroupId ?>" readonly>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="GLAccountMainGroupName" class="col-sm-2 control-label"><?= lang("GL Main Group Name") ?> *</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="GLAccountMainGroupName" placeholder="<?= lang("GL Group Name") ?>" name="GLAccountMainGroupName" readonly>
                                <div id="GLAccountMainGroupName_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="GLAccountMainPrefix" class="col-sm-2 control-label"><?= lang("Prefix") ?></label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="GLAccountMainPrefix" placeholder="<?= lang("Prefix") ?>" name="GLAccountMainPrefix">
                                <div id="GLAccountMainPrefix_err" class="text-danger"></div>
                            </div>
                        </div>

                        <!-- end box body -->

                        <div class="box-footer text-right">
                            
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#GLAccountMainGroupId").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = new FormData($("#frmGLMaingroup")[0]);

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>GL/GLAccountMainGroups/ajx_add_save";
            } else {
                url = "<?= site_url() ?>GL/GLAccountMainGroups/ajx_edit_save";
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
                success: function(resp) {
                    if (resp.message != "") {
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons: {
                                OK: function() {
                                    if (resp.status == "SUCCESS") {
                                        //location.reload();
                                        window.location.href = "<?= site_url() ?>GL/GLAccountMainGroups";
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
                        $("#GLAccountMainGroupId").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT

                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

    });

    function init_form(GLAccountMainGroupId) {
        //alert("Init Form");
        var url = "<?= site_url() ?>GL/GLAccountMainGroups/fetch_data/" + GLAccountMainGroupId;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.glAccountMainGroups);

                $.each(resp.glAccountMainGroups, function(name, val) {
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
<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style type="text/css">
    .border-0 {
        border: 0px;
    }

    td {
        padding: 2px;
         !important
    }

    .nav-tabs-custom>.nav-tabs>li.active>a {
        font-weight: bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style: fixed;
    }

    .nav-tabs-custom>.nav-tabs {
        border-bottom-color: #3c8dbc;
        border-bottom-style: fixed;
    }
</style>

<section class="content-header">
    <h1><?= lang("Change password") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Changepassword") ?></a></li>
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
                <form id="frm_changepassword" class="form-horizontal" action="<?= site_url() ?>user/change_password" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class='form-group'>
                            <label for="current_password" class="col-sm-2 control-label"><?= lang("Current Password") ?> :</label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" id="current_password" placeholder="<?= lang("current password") ?>" name="current_password">
                                <div id="current_password_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password1" class="col-sm-2 control-label"><?= lang("New Password") ?> :</label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" id="new_password1" placeholder="<?= lang("New Password") ?>" name="new_password1">
                                <div id="new_password1_err" class="text-danger"></div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="new_password2" class="col-sm-2 control-label"><?= lang("Repeat Password") ?> :</label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" id="new_password2" placeholder="<?= lang("Repeat Password") ?>" name="new_password2">
                                <div id="new_password2_err" class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end box body -->

                    <div class="box-footer">
                        <a id="btnSubmitAjax" href="#" class="btn btn-primary">Change password</a>
                    </div>
                    <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">
    $(function() {

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            $(".text-danger").html("");
            data = new FormData($("#frm_changepassword")[0]);

            url = "<?= site_url() ?>user/change_password";

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
                                        window.location.href = "<?= site_url() ?>/login";
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
                        //$("#fin_department_id").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");
                        //window.location.href = "<?= site_url() ?>/login";
                        //return;

                        // Change to Edit mode
                        //$("#frm-mode").val("EDIT"); //ADD|EDIT

                        //$('#fst_department_name').prop('readonly', true);
                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                }
            });

        });
    });
</script>

<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
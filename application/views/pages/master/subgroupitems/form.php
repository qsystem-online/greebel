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
                </div>
                <!-- end box header -->

                <!-- form start -->
                <form id="frmSubgroup" class="form-horizontal" action="<?= site_url() ?>ItemSubGroupId" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="ItemSubGroupId" class="col-sm-2 control-label"><?= lang("Subgroup ID") ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ItemSubGroupId" placeholder="<?= lang("(Autonumber)") ?>" name="ItemSubGroupId" value="<?= $ItemSubGroupId ?>" readonly>
                                <div id="ItemSubGroupId_err" class="text-danger"></div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ItemSubGroupName" class="col-sm-2 control-label"><?= lang("Subgroup Name") ?> * </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ItemSubGroupName" placeholder="<?= lang("Subgroup Name") ?>" name="ItemSubGroupName">
                                <div id="ItemSubGroupName_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-GroupItem" class="col-md-2 control-label"><?= lang("Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-GroupItem" class="form-control" name="ItemGroupId"></select>
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
            init_form($("#ItemSubGroupId").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = new FormData($("#frmSubgroup")[0]);

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>Master/mssubgroupitems/ajx_add_save";
            } else {
                url = "<?= site_url() ?>Master/mssubgroupitems/ajx_edit_save";
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
                                        window.location.href = "<?= site_url() ?>Master/mssubgroupitems";
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
                        $("#ItemGroupId").val(data.insert_id);

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


        $("#select-GroupItem").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>Master/mssubgroupitems/get_data_ItemGroup',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.ItemGroupId,
                            "text": value.ItemGroupName
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

    function init_form(ItemSubGroupId) {
        //alert("Init Form");
        var url = "<?= site_url() ?>Master/mssubgroupitems/fetch_data/" + ItemSubGroupId;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.subgroupitems);

                $.each(resp.subgroupitems, function(name, val) {
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
                // menampilkan data di select2
                var newOption = new Option(resp.subgroupitems.ItemGroupName, resp.subgroupitems.ItemGroupId, true, true);
                // Append it to the select
                $('#select-GroupItem').append(newOption).trigger('change');
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
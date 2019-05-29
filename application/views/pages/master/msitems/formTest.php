<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.css">

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
    <h1><?= lang("Master Items") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Master Items") ?></a></li>
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
                <form id="frmMSItems" class="form-horizontal" action="<?= site_url() ?>master/msitems/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="ItemId" class="col-md-2 control-label"><?= lang("Item ID") ?> #</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="ItemId" placeholder="<?= lang("(Autonumber)") ?>" name="ItemId" value="<?= $ItemId ?>" readonly>
                                <div id="ItemId_err" class="text-danger"></div>
                            </div>
                            <label for="ItemCode" class="col-md-2 control-label"><?= lang("Item Code") ?> #</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="ItemCode" placeholder="<?= lang("Item Code") ?>" name="ItemCode">
                                <div id="ItemCode_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ItemName" class="col-md-2 control-label"><?= lang("Item Name") ?> *</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="ItemName" placeholder="<?= lang("Item Name") ?>" name="ItemName">
                                <div id="ItemName_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="VendorItemName" class="col-md-2 control-label"><?= lang("Vendor Item Name") ?> *</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="VendorItemName" placeholder="<?= lang("Vendor Item Name") ?>" name="VendorItemName">
                                <div id="VendorItemName_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-maingroupitem" class="col-md-2 control-label"><?= lang("Main Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-maingroupitem" class="form-control" name="ItemMainGroupId"></select>
                            </div>

                            <label for="ItemTypeId" class="col-md-2 control-label"><?= lang("Item Type") ?> *</label>
                            <div class="col-md-4">
                                <select class="form-control" id="ItemTypeId" name="ItemTypeId">
                                    <option value='1'><?= lang("Raw Material") ?></option>
                                    <option value='2'><?= lang("Semi Finished Material") ?></option>
                                    <option value='3'><?= lang("Supporting Material") ?></option>
                                    <option value='4'><?= lang("Ready Product") ?></option>
                                    <option value='5'><?= lang("Logistic") ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-GroupItemId" class="col-md-2 control-label"><?= lang("Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-GroupItemId" class="form-control" name="ItemGroupId"></select>
                            </div>

                            <label for="select-SubGroupItemId" class="col-md-2 control-label"><?= lang("Sub Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-SubGroupItemId" class="form-control" name="ItemSubGroupId"></select>
                                <div id="ItemSubGroupId_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="isBatchNumber" class="col-sm-2 control-label"><?= lang("Batch Number") ?> :</label>
                            <div class="checkbox">
                                <label><input id="isBatchNumber" type="checkbox" name="isBatchNumber" value="1"><?= lang("Batch Number") ?></label><br>
                            </div>

                            <label for="isSerialNumber" class="col-sm-2 control-label"><?= lang("Serial Number") ?> :</label>
                            <div class="checkbox">
                                <label><input id="isSerialNumber" type="checkbox" name="isSerialNumber" value="1"><?= lang("Serial Number") ?></label><br>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label for="ScaleForBOM" class="col-md-2 control-label"><?= lang("Scale For BOM") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="ScaleForBOM" placeholder="<?= lang(" 1 : ") ?>" name="ScaleForBOM">
                                <div id="ScaleForBOM_err" class="text-danger"></div>
                            </div>
                            <label for="StorageRackInfo" class="col-md-2 control-label"><?= lang("Storage Rack Info") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="StorageRackInfo" placeholder="<?= lang("Storage Rack Info") ?>" name="StorageRackInfo">
                                <div id="StorageRackInfo_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_memo" class="col-md-2 control-label"><?= lang("Memo") ?>:</label>
                            <div class="col-md-10">
                                <textarea rows="4" style="width:100%" class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo"></textarea>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="MaxItemDiscount" class="col-md-2 control-label"><?= lang("Max Item Discount") ?>:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="MaxItemDiscount" placeholder="<?= lang("Max Item Discount") ?>" name="MaxItemDiscount">
                                <div id="MaxItemDiscount_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="MinBasicUnitAvgCost" class="col-md-2 control-label"><?= lang("Min AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="MinBasicUnitAvgCost" placeholder="<?= lang("Minimal BasicUnitAvgCost") ?>" value="0" name="MinBasicUnitAvgCost">
                                <div id="MinBasicUnitAvgCost_err" class="text-danger"></div>
                            </div>

                            <label for="MaxBasicUnitAvgCost" class="col-md-2 control-label"><?= lang("Max AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="MaxBasicUnitAvgCost" placeholder="<?= lang("Maximal BasicUnitAvgCost") ?>" value="0" name="MaxBasicUnitAvgCost">
                                <div id="MaxBasicUnitAvgCost_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="box-footer text-right">
                            <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?= lang("Save Record") ?></a>
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#ItemId").val());
        <?php } ?>

        $("#select-maingroupitem").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>Master/msitems/get_data_ItemMainGroupId',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.ItemMainGroupId,
                            "text": value.ItemMainGroupName
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

        $("#select-GroupItemId").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>Master/msitems/get_data_ItemGroupId',
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

        $("#select-GroupItemId").change(function(event) {
            event.preventDefault();
            $('#select-SubGroupItemId').val(null).trigger('change');
            $("#select-SubGroupItemId").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/msitems/get_data_ItemSubGroupId/' + $("#select-GroupItemId").val(),
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.ItemSubGroupId,
                                "text": value.ItemSubGroupName
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

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            $(".text-danger").html("");

            data = new FormData($("#frmMSItems")[0]);
            //data = $("#frmSalesOrder").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>Master/msitems/ajx_add_save";
            } else {
                url = "<?= site_url() ?>Master/msitems/ajx_edit_save";
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
                                        window.location.href = "<?= site_url() ?>Master/msitems";
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
                        $("#ItemId").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#ItemName').prop('readonly', true);
                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });


    });

    function init_form(ItemId) {
        //alert("Init Form");
        var url = "<?= site_url() ?>Master/msitems/fetch_data/" + ItemId;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.msitems);

                $.each(resp.msitems, function(name, val) {
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
                var newOption = new Option(resp.msitems.ItemMainGroupName, resp.msitems.ItemMainGroupId, true, true);
                // Append it to the select
                $('#select-maingroupitem').append(newOption).trigger('change');
                var newOption = new Option(resp.msitems.ItemGroupName, resp.msitems.ItemGroupId, true, true);
                // Append it to the select
                $('#select-GroupItemId').append(newOption).trigger('change');
                var newOption = new Option(resp.msitems.ItemSubGroupName, resp.msitems.ItemSubGroupId, true, true);
                // Append it to the select
                $('#select-SubGroupItemId').append(newOption).trigger('change');

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
<script src="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.min.js"></script>
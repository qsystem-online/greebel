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
                <form id="frmItem" class="form-horizontal" action="<?= site_url() ?>master/item/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_item_id" class="col-md-2 control-label"><?= lang("Item ID") ?> #</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_item_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_item_id" value="<?= $fin_item_id ?>" readonly>
                                <div id="fin_item_id_err" class="text-danger"></div>
                            </div>
                            <label for="fst_item_code" class="col-md-2 control-label"><?= lang("Item Code") ?> #</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_item_code" placeholder="<?= lang("Item Code") ?>" name="fst_item_code">
                                <div id="fst_item_code_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_item_name" class="col-md-2 control-label"><?= lang("Item Name") ?> *</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_item_name" placeholder="<?= lang("Item Name") ?>" name="fst_item_name">
                                <div id="fst_item_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_vendor_item_name" class="col-md-2 control-label"><?= lang("Vendor Item Name") ?> *</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_vendor_item_name" placeholder="<?= lang("Vendor Item Name") ?>" name="fst_vendor_item_name">
                                <div id="fst_vendor_item_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-maingroupitem" class="col-md-2 control-label"><?= lang("Main Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-maingroupitem" class="form-control" name="fin_item_maingroup_id"></select>
                            </div>

                            <label for="fin_item_type_id" class="col-md-2 control-label"><?= lang("Item Type") ?> *</label>
                            <div class="col-md-4">
                                <select class="form-control" id="fin_item_type_id" name="fin_item_type_id">
                                    <option value='1'><?= lang("Raw Material") ?></option>
                                    <option value='2'><?= lang("Semi Finished Material") ?></option>
                                    <option value='3'><?= lang("Supporting Material") ?></option>
                                    <option value='4'><?= lang("Ready Product") ?></option>
                                    <option value='5'><?= lang("Logistic") ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-GroupItem" class="col-md-2 control-label"><?= lang("Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-GroupItem" class="form-control" name="fin_item_group_id"></select>
                            </div>

                            <label for="select-SubGroupItem" class="col-md-2 control-label"><?= lang("Sub Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-SubGroupItem" class="form-control" name="fin_item_subgroup_id"></select>
                                <div id="fin_item_subgroup_id_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fbl_is_batch_number" class="col-sm-2 control-label"><?= lang("Batch Number") ?> :</label>
                            <div class="checkbox">
                                <label><input id="fbl_is_batch_number" type="checkbox" name="fbl_is_batch_number" value="1"><?= lang("Batch Number") ?></label><br>
                            </div>

                            <label for="fbl_is_serial_number" class="col-sm-2 control-label"><?= lang("Serial Number") ?> :</label>
                            <div class="checkbox">
                                <label><input id="fbl_is_serial_number" type="checkbox" name="fbl_is_serial_number" value="1"><?= lang("Serial Number") ?></label><br>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label for="fdc_scale_for_bom" class="col-md-2 control-label"><?= lang("Scale For BOM") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fdc_scale_for_bom" placeholder="<?= lang(" 1 : ") ?>" name="fdc_scale_for_bom">
                                <div id="fdc_scale_for_bom_err" class="text-danger"></div>
                            </div>
                            <label for="fst_storage_rack_info" class="col-md-2 control-label"><?= lang("Storage Rack Info") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_storage_rack_info" placeholder="<?= lang("Storage Rack Info") ?>" name="fst_storage_rack_info">
                                <div id="fst_storage_rack_info_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_memo" class="col-md-2 control-label"><?= lang("Memo") ?>:</label>
                            <div class="col-md-10">
                                <textarea rows="4" style="width:100%" class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo"></textarea>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fst_max_item_discount" class="col-md-2 control-label"><?= lang("Max Item Discount") ?>:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_max_item_discount" placeholder="<?= lang("Max Item Discount") ?>" name="fst_max_item_discount">
                                <div id="fst_max_item_discount_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fdc_min_basic_unit_avg_cost" class="col-md-2 control-label"><?= lang("Min AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fdc_min_basic_unit_avg_cost" placeholder="<?= lang("Minimal BasicUnitAvgCost") ?>" value="0" name="fdc_min_basic_unit_avg_cost">
                                <div id="fdc_min_basic_unit_avg_cost_err" class="text-danger"></div>
                            </div>

                            <label for="fdc_max_basic_unit_avg_cost" class="col-md-2 control-label"><?= lang("Max AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fdc_max_basic_unit_avg_cost" placeholder="<?= lang("Maximal BasicUnitAvgCost") ?>" value="0" name="fdc_max_basic_unit_avg_cost">
                                <div id="fdc_max_basic_unit_avg_cost_err" class="text-danger"></div>
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
            init_form($("#fin_item_id").val());
        <?php } ?>

        $("#select-maingroupitem").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemMainGroupId',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_item_maingroup_id,
                            "text": value.fst_item_maingroup_name
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

        $("#select-GroupItem").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemGroupId',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_item_group_id,
                            "text": value.fst_item_group_name
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

        $("#select-GroupItem").change(function(event) {
            event.preventDefault();
            $('#select-SubGroupItem').val(null).trigger('change');
            $("#select-SubGroupItem").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/item/get_data_ItemSubGroupId/' + $("#select-GroupItem").val(),
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fin_item_subgroup_id,
                                "text": value.fst_item_subgroup_name
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

            data = new FormData($("#frmItem")[0]);
            //data = $("#frmSalesOrder").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/item/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/item/ajx_edit_save";
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
                                        window.location.href = "<?= site_url() ?>master/item/lizt";
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
                        $("#fin_item_id").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fst_item_name').prop('readonly', true);
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

    function init_form(fin_item_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/item/fetch_data/" + fin_item_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.ms_items);

                $.each(resp.ms_items, function(name, val) {
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
                var newOption = new Option(resp.ms_items.fst_item_maingroup_name, resp.ms_items.fin_item_maingroup_id, true, true);
                // Append it to the select
                $('#select-maingroupitem').append(newOption).trigger('change');
                var newOption = new Option(resp.ms_items.fst_item_group_name, resp.ms_items.fin_item_group_id, true, true);
                // Append it to the select
                $('#select-GroupItem').append(newOption).trigger('change');
                var newOption = new Option(resp.ms_items.fst_item_subgroup_name, resp.msitems.fin_item_subgroup_id, true, true);
                // Append it to the select
                $('#select-SubGroupItem').append(newOption).trigger('change');

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
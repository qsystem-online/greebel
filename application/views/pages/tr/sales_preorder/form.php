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
    <h1><?= lang("Pre-Order") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Sales Pre-Order") ?></a></li>
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
                <form id="frmPreorder" class="form-horizontal" action="<?= site_url() ?>tr/Sales_preorder/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_preorder_id" class="col-md-2 control-label"><?= lang("Pre-Order ID") ?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_preorder_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_preorder_id" value="<?= $fin_preorder_id ?>" readonly>
                                <div id="fin_preorder_id_err" class="text-danger"></div>
                            </div>
                            <label for="fst_preorder_code" class="col-md-2 control-label"><?= lang("Pre-Order Code") ?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_preorder_code" placeholder="<?= lang("Pre-Order Code") ?>" name="fst_preorder_code">
                                <div id="fst_preorder_code_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_preorder_name" class="col-md-2 control-label"><?= lang("Pre-Order Name") ?> </label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_preorder_name" placeholder="<?= lang("Pre-Order Name") ?>" name="fst_preorder_name">
                                <div id="fst_preorder_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fdt_start_date" class="col-md-2 control-label"><?= lang("Start Date") ?> </label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_start_date" name="fdt_start_date" />
                                </div>
                                <div id="fdt_start_date_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                            <label for="fdt_end_date" class="col-md-2 control-label"><?= lang("End Date") ?> </label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_end_date" name="fdt_end_date" />
                                </div>
                                <div id="fdt_end_date_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-maingroupitem" class="col-md-2 control-label"><?= lang("Item Main group") ?> </label>
                            <div class="col-md-4">
                                <select id="select-maingroupitem" class="form-control" name="fin_item_maingroup_id"></select>
                            </div>

                            <label for="select-GroupItemId" class="col-md-2 control-label"><?= lang("Item group") ?> </label>
                            <div class="col-md-4">
                                <select id="select-GroupItemId" class="form-control" name="fin_item_group_id"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-SubGroupItemId" class="col-md-2 control-label"><?= lang("Item Sub group") ?> </label>
                            <div class="col-md-4">
                                <select id="select-SubGroupItemId" class="form-control" name="fin_item_subgroup_id"></select>
                                <div id="fin_item_subgroup_id_err" class="text-danger"></div>
                            </div>
                            <label for="select-Currency" class="col-md-2 control-label"><?= lang("Curr Code") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-Currency" class="form-control" name="fst_curr_code"></select>
                                <div id="fst_curr_code_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fdc_preorder_price" class="col-md-2 control-label"><?= lang("Price") ?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-left money" id="fdc_preorder_price" placeholder="<?= lang("Pre-order price") ?>" value="0" name="fdc_preorder_price">
                                <div id="fdc_preorder_price_err" class="text-danger"></div>
                            </div>

                            <label for="fdc_minimal_deposit" class="col-md-2 control-label"><?= lang("Minimal deposit") ?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-left money" id="fdc_minimal_deposit" placeholder="<?= lang("Minimal deposit") ?>" value="0" name="fdc_minimal_deposit">
                                <div id="fdc_minimal_deposit_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-ItemId" class="col-md-2 control-label"><?= lang("Item Name") ?></label>
                            <div class="col-md-4">
                                <select id="select-ItemId" class="form-control" name="fin_item_id"></select>
                                <div id="fin_item_id_id_err" class="text-danger"></div>
                            </div>

                            <label for="fdt_eta_date" class="col-md-2 control-label"><?= lang("Estimasi") ?> </label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_eta_date" name="fdt_eta_date" />
                                </div>
                                <div id="fdt_eta_date_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_notes" class="col-md-2 control-label"><?= lang("Notes") ?></label>
                            <div class="col-md-10">
                                <textarea rows="4" style="width:100%" class="form-control" id="fst_notes" placeholder="<?= lang("Notes") ?>" name="fst_notes"></textarea>
                            </div>
                        </div>
                        <!-- end box body -->
                        <?php $displaytabs = ($mode == "ADD") ? "none" : "" ?>
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#preorder_details" data-toggle="tab" aria-expanded="true"><?= lang("Pre-order Branch details") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="preorder_details">
                                    <button id="btn-add-preorder-details" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Branch") ?></button>
                                    <div>
                                        <table id="tbl_preorder_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->

                        <div class="box-footer text-right">
                            <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?= lang("Save Record") ?></a>
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>
</div>

<script type="text/javascript">
    $(function() {
        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_preorder_id").val());
        <?php } ?>
        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = $("#frmPreorder").serializeArray();
            //data = new FormData($("#frmPreorder")[0]);
            /*detail = new Array();
            t = $('#tbl_preorder_details').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });*/
            /*data.push({
                name: "detail",
                value: JSON.stringify(detail)
            });*/
            /*data.append("detail",JSON.stringify(detail));*/
            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>tr/Sales_preorder/ajx_add_save";
            } else {
                url = "<?= site_url() ?>tr/Sales_preorder/ajx_edit_save";
            }
            console.log(data);
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
                                        window.location.href = "<?= site_url() ?>tr/Sales_preorder/lizt";
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
                        $("#fin_preorder_id").val(data.insert_id);
                        //Clear all previous error
                        $(".text-danger").html("");
                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fst_preorder_name').prop('readonly', true);
                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#select-maingroupitem").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>tr/Sales_preorder/get_data_ItemMainGroupId',
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
        $("#select-GroupItemId").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>tr/Sales_preorder/get_data_ItemGroupId',
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
        $("#select-GroupItemId").change(function(event) {
            event.preventDefault();
            $('#select-SubGroupItemId').val(null).trigger('change');
            $("#select-SubGroupItemId").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>tr/Sales_preorder/get_data_ItemSubGroupId/' + $("#select-GroupItemId").val(),
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
        })

        $("#select-Currency").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>gl/glaccount/get_Currency',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    items = [];
                    $.each(data, function(index, value) {
                        items.push({
                            "id": value.fst_curr_code,
                            "text": value.fst_curr_name
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

        $("#select-ItemId").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>tr/Sales_preorder/get_data_Item',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_item_id,
                            "text": value.fst_item_name
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
    function init_form(fin_preorder_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>tr/Sales_preorder/fetch_data/" + fin_preorder_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.preOrder);

                $.each(resp.preOrder, function(name, val) {
                    var $el = $('[name="' + name + '"]'),
                        type = $el.attr('type');
                    switch (type) {
                        case 'checkbox':
                            $el.attr('checked', 'checked');
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
                var newOption = new Option(resp.preOrder.fst_item_maingroup_name, resp.preOrder.fin_item_maingroup_id, true, true);
                // Append it to the select
                $('#select-maingroupitem').append(newOption).trigger('change');
                var newOption = new Option(resp.preOrder.fst_item_group_name, resp.preOrder.fin_item_group_id, true, true);
                // Append it to the select
                $('#select-GroupItemId').append(newOption).trigger('change');
                var newOption = new Option(resp.preOrder.fst_item_subgroup_name, resp.preOrder.fin_item_subgroup_id, true, true);
                // Append it to the select
                $('#select-SubGroupItemId').append(newOption).trigger('change');
                var newOption = new Option(resp.preOrder.fst_curr_name, resp.preOrder.fst_curr_code, true, true);
                // Append it to the select
                $('#select-Currency').append(newOption).trigger('change');
                var newOption = new Option(resp.preOrder.fst_item_name, resp.preOrder.fin_item_id, true, true);
                // Append it to the select
                $('#select-ItemId').append(newOption).trigger('change');
                //populate Pre-Order branch Detail
                /*$.each(resp.preorderDetail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_preorder_details').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_preorder_id: val.fin_preorder_id,
                        fst_unit: val.fst_unit,
                        fbl_is_basic_unit: val.fbl_is_basic_unit,
                        fdc_conv_to_basic_unit: val.fdc_conv_to_basic_unit,
                        fbl_is_selling: val.fbl_is_selling,
                        fbl_is_buying: val.fbl_is_buying,
                        fbl_is_production_output: val.fbl_is_production_output,
                        fdc_price_list: val.fdc_price_list,
                        fdc_het: val.fdc_het,
                        action: action
                    }).draw(false);
                })*/
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
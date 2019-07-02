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
    <h1><?= lang("Sales Promotion") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Sales Promotion") ?></a></li>
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
                <form id="frmPromotion" class="form-horizontal" action="<?= site_url() ?>master/promotion/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_promo_id" class="col-md-2 control-label"><?= lang("Promo ID") ?> :</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="fin_promo_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_promo_id" value="<?= $fin_promo_id ?>" readonly>
                                <div id="fin_promo_id_err" class="text-danger"></div>
                            </div>
                            <label for="fst_promo_name" class="col-md-2 control-label"><?= lang("Promo Name") ?> :</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="fst_promo_name" placeholder="<?= lang("Promo Name") ?>" name="fst_promo_name">
                                <div id="fst_promo_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-promo_item" class="col-md-2 control-label"><?= lang("Free Item") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-promo_item" class="form-control" name="fin_promo_item_id"></select>
                            </div>

                            <label for="fin_promo_qty" class="col-md-1 control-label"><?= lang("Free Qty") ?> :</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="fin_promo_qty" placeholder="<?= lang("0") ?>" name="fin_promo_qty">
                                <div id="fin_promo_qty_err" class="text-danger"></div>
                            </div>

                            <label for="select-promo_unit" class="col-md-1 control-label"><?= lang("Unit") ?> :</label>
                            <div class="col-md-2">
                                <select id="select-promo_unit" class="form-control" name="fin_promo_unit"></select>
                                <div id="fin_promo_unit_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fin_cashback" class="col-md-2 control-label"><?= lang("Or CashBack (Rp.)") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control money" id="fin_cashback" placeholder="<?= lang("0") ?>" name="fin_cashback">
                                <div id="fin_cashback_err" class="text-danger"></div>
                            </div>
                            <label for="fdt_start" class="col-md-2 control-label"><?= lang("Start Date") ?> *</label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_start" name="fdt_start" />
                                </div>
                                <div id="fdt_start_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_other_prize" class="col-md-2 control-label"><?= lang("Or Other Item") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_other_prize" placeholder="<?= lang("Other Item") ?>" name="fst_other_prize">
                                <div id="fst_other_prize_err" class="text-danger"></div>
                            </div>
                            <label for="fdt_end" class="col-md-2 control-label"><?= lang("End Date") ?> *</label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_end" name="fdt_end" />
                                </div>
                                <div id="fdt_end_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <!-- end box body -->
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#promo_item_details" data-toggle="tab" aria-expanded="true"><?= lang("Promo Terms") ?></a></li>
                                <li class="promo_customer_details" id="tab-doc"><a href="#promo_customer_details" data-toggle="tab" aria-expanded="false"><?= lang("Promo Participants") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="promo_item_details">
                                    <button id="btn-add-item-details" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Item") ?></button>

                                    <label for="fbl_qty_gabungan" class="col-sm-2 control-label"><?= lang("Combined") ?> :</label>
                                    <div class="checkbox" style="width:900px">
                                        <label><input id="fbl_qty_gabungan" type="checkbox" name="fbl_qty_gabungan" value="1"><?= lang("Combined") ?></label><br>
                                    </div>
                                    <label for="fin_qty_gabungan" class="col-md-2 control-label"><?= lang("Qty Terms") ?> :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="fin_qty_gabungan" placeholder="<?= lang("0") ?>" name="fin_qty_gabungan">
                                        <div id="fin_qty_gabungan_err" class="text-danger"></div>
                                    </div>

                                    <label for="fst_satuan_gabungan" class="col-md-1 control-label"><?= lang("Unit") ?> :</label>
                                    <div class="col-md-2">
                                        <select class="select2 form-control" id="fst_satuan_gabungan" name="fst_satuan_gabungan" style="width:100%"></select>
                                        <div id="fst_satuan_gabungan_err" class="text-danger"></div>
                                    </div>
                                    <div>
                                        <table id="tbl_item_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="promo_customer_details">
                                    <button id="btn-add-customer-promo" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Customer") ?></button>
                                    <div>
                                        <table id="tbl_customer_promo" class="table table-bordered table-hover" style="width:100%;"></table>
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

<div id="mdlItemDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:50%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Item Details") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="fin_item_id" class="col-md-3 control-label"><?= lang("Item") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="fin_item_id" style="width:100%"></select>
                            <span id="fin_item_id_error" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fst_unit" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                        <div class="col-md-4">
                            <select class="select2 form-control" id="fst_unit" style="width:100%"></select>
                            <span id="fst_unit_error" class="text-danger"></span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-add-item" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            $("#btn-add-item-details").click(function(event) {
                event.preventDefault();
                $("#mdlItemDetails").modal('show');
            });
            $("#tbl_item_details").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_id",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item ID ") ?>",
                        "width": "20%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Item Name ") ?>",
                        "width": "20%",
                        data: "ItemName",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Unit ") ?>",
                        "width": "5%",
                        data: "fst_unit",
                        visible: true,

                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "7%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-item-details edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });

            $("#tbl_item_details").on("click", ".btn-delete-item-details", function(event) {
                event.preventDefault();
                t = $("#tbl_item_details").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });

            $("#fst_satuan_gabungan").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/promotion/get_data_unit',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.Unit,
                                "text": value.Unit
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


            $("#fin_item_id").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/promotion/get_data_ItemPromo',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.ItemId,
                                "text": value.ItemName
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

            var selected_itempromo;

            $('#fin_item_id').on('select2:select', function(e) {
                console.log(selected_itempromo);
                var data = e.params.data;
                selected_itempromo = data;
            });

            $("#fin_item_id").change(function(event) {
                event.preventDefault();
                $('#fst_unit').val(null).trigger('change');
                $("#fst_unit").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>Master/promotion/get_data_unitPromo/' + $("#fin_item_id").val(),
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            data2 = [];
                            $.each(data, function(index, value) {
                                data2.push({
                                    "id": value.ItemId,
                                    "text": value.Unit
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

            var selected_unitdetail;

            $('#fst_unit').on('select2:select', function(e) {
                console.log(selected_unitdetail);
                var data = e.params.data;
                selected_unitdetail = data;
            });

            $("#btn-add-item").click(function(event) {
                event.preventDefault();

                var itemTerms = $("#fin_item_id").val();
                if (itemTerms == null || itemTerms == "") {
                    $("#fin_item_id_error").html("Please select item");
                    $("#fin_item_id_error").show();
                } else {
                    $("#fin_item_id_error").hide();
                }

                var unitTerms = $("#fst_unit").val();
                if (unitTerms == null || unitTerms == "") {
                    $("#fst_unit_error").html("Please select Unit");
                    $("#fst_unit_error").show();
                } else {
                    $("#fst_unit_error").hide();
                }

                t = $('#tbl_item_details').DataTable();
                addRow = true;

                t.row.add({
                    fin_id: 0,
                    fin_promo_id: 0,
                    fin_item_id: selected_itempromo.id,
                    ItemName: selected_itempromo.text,
                    fst_unit: selected_unitdetail.text,
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<div id="mdlCustomerPromo" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:50%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Participants") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="fin_customer_id" class="col-md-3 control-label"><?= lang("Customer") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="fin_customer_id" style="width:100%"></select>
                            <span id="fin_customer_id_error" class="text-danger"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-add-participants-promo" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            $("#btn-add-customer-promo").click(function(event) {
                event.preventDefault();
                $("#mdlCustomerPromo").modal('show');
            });
            $("#tbl_customer_promo").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_id",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Promo ID ") ?>",
                        "width": "10%",
                        data: "fin_promo_id",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Customer ID") ?>",
                        "width": "10%",
                        data: "fin_customer_id",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Customer Name ") ?>",
                        "width": "30%",
                        data: "customer_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-customer-promo edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });

            $("#tbl_customer_promo").on("click", ".btn-delete-customer-promo", function(event) {
                event.preventDefault();
                t = $("#tbl_customer_promo").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });

            $("#fin_customer_id").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/promotion/get_participants',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.RelationId,
                                "text": value.RelationName
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
            
            var selected_participants;

            $('#fin_customer_id').on('select2:select', function(e) {
                console.log(selected_participants);
                var data = e.params.data;
                selected_participants = data;

            });

            $("#btn-add-participants-promo").click(function(event) {
                event.preventDefault();
                t = $('#tbl_customer_promo').DataTable();
                addRow = true;

                t.row.add({
                    fin_id: 0,
                    fin_promo_id: 0,
                    fin_customer_id: selected_participants.id,
                    customer_name: selected_participants.text,
                    action: action
                }).draw(false);

            });
        });
    </script>
</div>

<script type="text/javascript">
    $(function() {
        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_promo_id").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();

            data = $("#frmPromotion").serializeArray();
            //data = new FormData($("#frmMSItems")[0]);
            detail = new Array();

            t = $('#tbl_item_details').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });

            data.push({
                name: "detail",
                value: JSON.stringify(detail)
            });

            // save Participants
            detailParticipants = new Array();

            b = $('#tbl_customer_promo').DataTable();
            datas = b.data();
            $.each(datas, function(i, v) {
                detailParticipants.push(v);
            });

            data.push({
                name: "detailParticipants",
                value: JSON.stringify(detailParticipants)
            });

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>Master/promotion/ajx_add_save";
            } else {
                url = "<?= site_url() ?>Master/promotion/ajx_edit_save";
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
                                        window.location.href = "<?= site_url() ?>Master/promotion";
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
                        $("#fin_promo_id").val(data.insert_id);

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

        $("#select-promo_item").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>Master/promotion/get_data_ItemPromo',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.ItemId,
                            "text": value.ItemName
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
        var selected_promoitem;

        $('#select-promo_item').on('select2:select', function(e) {
            console.log(selected_promoitem);
            var data = e.params.data;
            selected_promoitem = data;

        });

        $("#select-promo_item").change(function(event) {
            event.preventDefault();
            $('#select-promo_unit').val(null).trigger('change');
            $("#select-promo_unit").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/promotion/get_data_unitPromo/' + $("#select-promo_item").val(),
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.Unit,
                                "text": value.Unit
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


    });

    function init_form(fin_promo_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>Master/promotion/fetch_data/" + fin_promo_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.mspromo);

                $.each(resp.mspromo, function(name, val) {
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
                var newOption = new Option(resp.mspromo.ItemName, resp.mspromo.fin_promo_item_id, true, true);
                // Append it to the select
                $('#select-promo_item').append(newOption).trigger('change');

                var newOption = new Option(resp.mspromo.fin_promo_unit, resp.mspromo.fin_promo_unit, true, true);
                // Append it to the select
                $('#select-promo_unit').append(newOption).trigger('change');

                var newOption = new Option(resp.mspromo.fst_satuan_gabungan, resp.mspromo.fst_satuan_gabungan, true, true);
                // Append it to the select
                $('#fst_satuan_gabungan').append(newOption).trigger('change');

                //populate Promo Terms
                $.each(resp.promoTerms, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_item_details').DataTable();

                    t.row.add({
                    fin_id: val.fin_id,
                    fin_promo_id: val.fin_promo_id,
                    fin_item_id: val.fin_item_id,
                    ItemName: val.ItemTerms,
                    fst_unit: val.fst_unit,
                    action: action
                }).draw(false);
                })

                //populate Promo Participants
                $.each(resp.promoParticipants, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_customer_promo').DataTable();

                    t.row.add({
                        fin_id: val.fin_id,
                        fin_promo_id: val.fin_promo_id,
                        fin_customer_id: val.fin_customer_id,
                        customer_name: val.RelationName,
                        action: action
                    }).draw(false);
                })

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
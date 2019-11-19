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
    .form-group{
		margin-bottom: 5px;
	}
	.checkbox label, .radio label {
		font-weight:700;
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
                    <div class="btn-group btn-group-sm  pull-right">					
                        <a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrinted" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
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
                            <label for="fst_name_on_pos" class="col-md-2 control-label"><?= lang("Item Name on POS") ?> *</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_name_on_pos" placeholder="<?= lang("Item Name on POS") ?>" name="fst_name_on_pos">
                                <div id="fst_name_on_pos_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-GroupItemId" class="col-md-2 control-label"><?= lang("Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-GroupItemId" class="form-control" name="fin_item_group_id"></select>
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
                        <label for="select-lineBusiness" class="col-md-2 control-label"><?=lang("Line Of Business")?> :</label>
                            <div class="col-md-10">
                                <select class="form-control select2" id="select-lineBusiness" name="fst_linebusiness_id[]"  multiple="multiple">
                                <?php foreach ($linebusinessList as $linebusiness) {    ?>
                                            <option value='<?= $linebusiness->fin_linebusiness_id ?>'><?= $linebusiness->fst_linebusiness_name ?> </option>
                                        <?php
                                    } ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-10" style="left: 18px;">				
                                <label for="fdc_scale_for_bom" class="col-md-2 control-label"><?= lang("Scale For BOM") ?>:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="fdc_scale_for_bom" placeholder="<?= lang(" 1 : ") ?>" name="fdc_scale_for_bom">
                                    <div id="fdc_scale_for_bom_err" class="text-danger"></div>
                                </div>
                            </div>				
                            <div class="checkbox col-sm-2">
                                <div>
                                    <input type="checkbox" id="fbl_is_batch_number" name="fbl_is_batch_number" value="1"> &nbsp;
                                    <label for="fbl_is_batch_number" class=""> <?= lang("Batch Number")?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10" style="left: 18px;">				
                                <label for="fst_storage_rack_info" class="col-md-2 control-label"><?= lang("Storage Rack Info") ?>:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="fst_storage_rack_info" placeholder="<?= lang("Storage Rack Info") ?>" name="fst_storage_rack_info">
                                    <div id="fst_storage_rack_info_err" class="text-danger"></div>
                                </div>
                            </div>				
                            <div class="checkbox col-sm-2">
                                <div>
                                    <input type="checkbox" id="fbl_is_serial_number" name="fbl_is_serial_number" value="1"> &nbsp;
                                    <label for="fbl_is_serial_number" class=""> <?= lang("Serial Number")?> </label>
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label for="fst_sni_no" class="col-md-2 control-label"><?= lang("SNI No") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_sni_no" placeholder="<?= lang("SNI Number") ?>" name="fst_sni_no">
                                <div id="fst_sni_no_err" class="text-danger"></div>
                            </div>
                            <label for="fst_max_item_discount" class="col-md-2 control-label"><?= lang("Max Item Discount") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fst_max_item_discount" placeholder="<?= lang("0+0+0") ?>" name="fst_max_item_discount">
                                <div id="fst_max_item_discount_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fdc_min_basic_unit_avg_cost" class="col-md-2 control-label"><?= lang("Min AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-left money" id="fdc_min_basic_unit_avg_cost" placeholder="<?= lang("Minimal BasicUnitAvgCost") ?>" value="0" name="fdc_min_basic_unit_avg_cost">
                                <div id="fdc_min_basic_unit_avg_cost_err" class="text-danger"></div>
                            </div>

                            <label for="fdc_max_basic_unit_avg_cost" class="col-md-2 control-label"><?= lang("Max AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-left money" id="fdc_max_basic_unit_avg_cost" placeholder="<?= lang("Maximal BasicUnitAvgCost") ?>" value="0" name="fdc_max_basic_unit_avg_cost">
                                <div id="fdc_max_basic_unit_avg_cost_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_memo" class="col-md-2 control-label"><?= lang("Memo") ?>:</label>
                            <div class="col-md-10">
                                <textarea rows="4" style="width:100%" class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_image" class="col-sm-2 control-label"><?= lang("Image") ?> :</label>
                            <div class="col-sm-4">
                                <input type="file" class="form-control" id="fst_image" name="fst_image">
                            </div>

                            <label for="imgItem" class="col-sm-1 control-label"></label>
                            <div class="col-sm-5">
                                <img id="imgItem" style="border:1px solid #999;width:128px;" align="right" src="<?= site_url() ?>assets/app/items/image/default.jpg" />
                            </div>
                        </div>
                        <!-- end box body -->
                        <?php $displaytabs = ($mode == "ADD") ? "none" : "" ?>
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#unit_details" data-toggle="tab" aria-expanded="true"><?= lang("Unit Details") ?></a></li>
                                <li class="bom_details" id="tab-doc"><a href="#bom_details" data-toggle="tab" aria-expanded="false"><?= lang("BOM Details") ?></a></li>
                                <li class="special_pricing" style="display:<?= $displaytabs ?>;"><a href="#special_pricing" data-toggle="tab" aria-expanded="false"><?= lang("Special Pricing") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="unit_details">
                                    <button id="btn-add-unit-details" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Unit") ?></button>
                                    <div>
                                        <table id="tbl_unit_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="bom_details">
                                    <button id="btn-add-bom" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add BOM") ?></button>
                                    <div>
                                        <table id="tbl_bom_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div <?= $displaytabs ?> class="tab-pane" id="special_pricing">
                                    <button id="btn-add-special_pricing" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Special Pricing") ?></button>
                                    <div>
                                        <table id="tbl_special_pricing" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->

                        <div class="box-footer text-right">
                            
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<div id="mdlUnitDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Unit Details") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="fst_unit" class="col-md-2 control-label"><?= lang("Unit") ?></label>
                                        <div class="col-md-4">
                                            <select class="select2 form-control" id="fst_unit" style="width:100%"></select>
                                            <span id="fst_unit_error" class="text-danger"></span>
                                        </div>
                                        <label for="fdc_conv_to_basic_unit" class="col-md-2 control-label"><?= lang("Konversi") ?></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control text-right numeric" id="fdc_conv_to_basic_unit" value="1">
                                            <span id="fdc_conv_to_basic_unit_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdc_price_list" class="col-md-2 control-label"><?= lang("Price") ?></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control text-right money" id="fdc_price_list" value="0">
                                            <span id="fdc_price_list_error" class="text-danger"></span>
                                        </div>
                                        <label for="fdc_het" class="col-md-2 control-label"><?= lang("HET") ?></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control text-right money" id="fdc_het" value="0">
                                            <span id="fdc_het_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fbl_is_basic_unit" class="col-sm-3 control-label"><?= lang("Basic") ?> :</label>
                                        <div class="checkbox">
                                            <label><input id="fbl_is_basic_unit" type="checkbox" name="fbl_is_basic_unit" value="1"><?= lang("Basic") ?></label><br>
                                        </div>

                                        <label for="fbl_is_production_output" class="col-sm-3 control-label"><?= lang("Production") ?> :</label>
                                        <div class="checkbox">
                                            <label><input id="fbl_is_production_output" type="checkbox" name="fbl_is_production_output" value="1"><?= lang("Production") ?></label><br>
                                        </div>

                                        <label for="fbl_is_selling" class="col-sm-3 control-label"><?= lang("Selling") ?> :</label>
                                        <div class="checkbox">
                                            <label><input id="fbl_is_selling" type="checkbox" name="fbl_is_selling" value="1"><?= lang("Selling") ?></label><br>
                                        </div>

                                        <label for="fbl_is_buying" class="col-sm-3 control-label"><?= lang("Buying") ?> :</label>
                                        <div class="checkbox">
                                            <label><input id="fbl_is_buying" type="checkbox" name="fbl_is_buying" value="1"><?= lang("Buying") ?></label><br>
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-unit" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
                                    <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            $("#btn-add-unit-details").click(function(event) {
                event.preventDefault();
                $("#mdlUnitDetails").modal('show');
            });
            $("#tbl_unit_details").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID") ?>",
                        "width": "5%",
                        data: "fin_rec_id",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item Name") ?>",
                        "width": "20%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Unit") ?>",
                        "width": "5%",
                        data: "fst_unit",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Basic") ?>",
                        "width": "5%",
                        data: "fbl_is_basic_unit",
                        render: function(data, type, row) {
                            if (data == true) {
                                return '<input type="checkbox" class="editor-active" onclick="return false" checked disabled>';
                            } else {
                                return '<input type="checkbox" class="editor-active" disabled>';
                            }
                            return data;
                        },
                        className: "dt-body-center text-center",
                        "sortable": false,
                        readonly: true,
                    },
                    {
                        "title": "<?= lang("Konversi") ?>",
                        "width": "7%",
                        data: "fdc_conv_to_basic_unit"
                    },
                    {
                        "title": "<?= lang("Selling") ?>",
                        "width": "5%",
                        data: "fbl_is_selling",
                        render: function(data, type, row) {
                            if (data == true) {
                                return '<input type="checkbox" class="editor-active" onclick="return false" checked disabled>';
                            } else {
                                return '<input type="checkbox" class="editor-active" disabled>';
                            }
                            return data;
                        },
                        className: "dt-body-center text-center",
                        "sortable": false,
                    },
                    {
                        "title": "<?= lang("Buying") ?>",
                        "width": "5%",
                        data: "fbl_is_buying",
                        render: function(data, type, row) {
                            if (data == true) {
                                return '<input type="checkbox" class="editor-active" onclick="return false" checked disabled>';
                            } else {
                                return '<input type="checkbox" class="editor-active" disabled>';
                            }
                            return data;
                        },
                        className: "dt-body-center text-center",
                        "sortable": false,
                    },
                    {
                        "title": "<?= lang("Production") ?>",
                        "width": "5%",
                        data: "fbl_is_production_output",
                        render: function(data, type, row) {
                            if (data == true) {
                                return '<input type="checkbox" class="editor-active" onclick="return false" checked disabled>';
                            } else {
                                return '<input type="checkbox" class="editor-active" readonly disabled>';
                            }
                            return data;
                        },
                        className: "dt-body-center text-center",
                        "sortable": false,
                    },
                    {
                        "title": "<?= lang("Price") ?>",
                        "width": "10%",
                        data: "fdc_price_list",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "<?= lang("HET") ?>",
                        "width": "10%",
                        data: "fdc_het",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "<?= lang("Action") ?>",
                        "width": "7%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-unit-details edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            //action += "<a class='btn-view-document-items' href='#'><i class='fa fa-folder-open' aria-hidden='true'></i></a>";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_unit_details").on("click", ".btn-delete-unit-details", function(event) {
                event.preventDefault();
                t = $("#tbl_unit_details").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });
            $("#fst_unit").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/item/get_data_unit',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fst_unit,
                                "text": value.fst_unit
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
            var selected_unit;
            $('#fst_unit').on('select2:select', function(e) {
                console.log(selected_unit);
                var data = e.params.data;
                selected_unit = data;
            });
            $("#btn-add-unit").click(function(event) {
                event.preventDefault();
                t = $('#tbl_unit_details').DataTable();
                addRow = true;
                var HET = numeral($("#fdc_het").val());
                var PriceList = numeral($("#fdc_price_list").val());
                var conversion = $("#fdc_conv_to_basic_unit").val();
                if (conversion == null || conversion == "") {
                    $("#fdc_conv_to_basic_unit_error").html("minimum value 1");
                    $("#fdc_conv_to_basic_unit_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#fdc_conv_to_basic_unit_error").hide();
                }

                var unit = $("#fst_unit").val();
                if (unit == null || unit == "") {
                    $("#fst_unit_error").html("Please select Unit");
                    $("#fst_unit_error").show();
                    addRow = false;
                    return;
                } else {
                    //$("#fst_unit_error").hide();
                    data = t.rows().data();
                    console.log(data);
                    var valid = true;
                    $.each(data, function(i, v) {
                        if (v.fst_unit == unit) {
                            $("#fst_unit_error").html("Unit is already exist!");
                            $("#fst_unit_error").show();
                            addRow = false;
                            valid = false;
                            return false;
                        } else {
                            $("#fst_unit_error").hide();
                        }
                    });

                    if (valid == false){
                        return;
                    }
                }
                var priceList = $("#fdc_price_list").val();
                if (priceList == null || priceList == "") {
                    $("#fdc_price_list_error").html("required");
                    $("#fdc_price_list_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#fdc_price_list_error").hide();
                }
                var het = $("#fdc_het").val();
                if (het == null || het == "") {
                    $("#fdc_het_error").html("required");
                    $("#fdc_het_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#fdc_het_error").hide();
                }

                if ($("#fbl_is_basic_unit").is(":checked")) {
                    data = t.rows().data();
                    console.log(data);
                    $.each(data, function(i, v) {
                        if (v.fbl_is_basic_unit == "1") {
                            if (confirm("Basic unit telah didefinisikan untuk " + v.fst_unit + ", ganti basic unit ?")) {
                                v.fbl_is_basic_unit = false;
                                console.log(data);
                                t.row(i).data(v).draw(false);
                            } else {
                                addRow = false;
                                return;
                            }
                        }
                    });
                }
                if ($("#fbl_is_production_output").is(":checked")) {
                    data = t.rows().data();
                    console.log(data);
                    $.each(data, function(i, v) {
                        if (v.fbl_is_production_output == "1") {
                            if (confirm("Production unit telah didefinisikan untuk " + v.fst_unit + ", ganti production unit ?")) {
                                v.fbl_is_production_output = false;
                                console.log(data);
                                t.row(i).data(v).draw(false);
                            } else {
                                addRow = false;
                                return;
                            }
                        }
                    });
                }
                if (addRow) {
                    t.row.add({
                        fin_rec_id: 0,
                        fin_item_id: 0,
                        fst_unit: selected_unit.text,
                        fbl_is_basic_unit: $("#fbl_is_basic_unit").prop("checked"),
                        fdc_conv_to_basic_unit: $("#fdc_conv_to_basic_unit").val(),
                        fbl_is_selling: $("#fbl_is_selling").prop("checked"),
                        fbl_is_buying: $("#fbl_is_buying").prop("checked"),
                        fbl_is_production_output: $("#fbl_is_production_output").prop("checked"),
                        fdc_price_list: PriceList.value(),
                        fdc_het: HET.value(),
                        action: action
                    }).draw(false);
                }
            });
        });
    </script>
</div>

<div id="mdlBomDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add BOM Details") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="fin_item_id_bom" class="col-md-3 control-label"><?= lang("Item BOM") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="fin_item_id_bom" style="width:100%"></select>
                                            <span id="fin_item_id_bom_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fst_unit-bom" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="fst_unit-bom" style="width:100%"></select>
                                            <span id="fst_unit-bom_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                </form>

                                <div class="modal-footer">
                                    <button id="btn-add-bom-details" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
                                    <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $("#btn-add-bom").click(function(event) {
                event.preventDefault();
                $("#mdlBomDetails").modal('show');
            });
            $("#tbl_bom_details").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID") ?>",
                        "width": "5%",
                        data: "fin_rec_id",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item Name") ?>",
                        "width": "10%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("") ?>",
                        "width": "10%",
                        data: "fin_item_id_bom",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Item BOM") ?>",
                        "width": "30%",
                        data: "BomName",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Unit") ?>",
                        "width": "5%",
                        data: "fst_unit",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-bom-details edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            //action += "<a class='btn-view-document-items' href='#'><i class='fa fa-folder-open' aria-hidden='true'></i></a>";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_bom_details").on("click", ".btn-delete-bom-details", function(event) {
                event.preventDefault();
                t = $("#tbl_bom_details").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });
            $("#fin_item_id_bom").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/item/get_data_ItemBom',
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
            var selected_bom;
            $('#fin_item_id_bom').on('select2:select', function(e) {
                console.log(selected_bom);
                var data = e.params.data;
                selected_bom = data;
            });
            $("#fin_item_id_bom").change(function(event) {
                event.preventDefault();
                $('#fst_unit-bom').val(null).trigger('change');
                $("#fst_unit-bom").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/item/get_data_unitbom/' + $("#fin_item_id_bom").val(),
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            data2 = [];
                            $.each(data, function(index, value) {
                                data2.push({
                                    "id": value.fin_item_id,
                                    "text": value.fst_unit
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
            var selected_unitbom;
            $('#fst_unit-bom').on('select2:select', function(e) {
                console.log(selected_unitbom);
                var data = e.params.data;
                selected_unitbom = data;
            });
            $("#btn-add-bom-details").click(function(event) {
                event.preventDefault();
                var itemBom = $("#fin_item_id_bom").val();
                if (itemBom == null || itemBom == "") {
                    $("#fin_item_id_bom_error").html("Please select Item");
                    $("#fin_item_id_bom_error").show();
                } else {
                    $("#fin_item_id_bom_error").hide();
                }
                var unitBom = $("#fst_unit-bom").val();
                if (unitBom == null || unitBom == "") {
                    $("#fst_unit-bom_error").html("Please select Unit");
                    $("#fst_unit-bom_error").show();
                } else {
                    $("#fst_unit-bom_error").hide();
                }
                t = $('#tbl_bom_details').DataTable();
                t.row.add({
                    fin_rec_id: 0,
                    fin_item_id: 0,
                    fin_item_id_bom: selected_bom.id,
                    BomName: selected_bom.text,
                    fst_unit: selected_unitbom.text,
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<div id="mdlSpecialPricing" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Special Pricing") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="fin_cust_pricing_group_id" class="col-md-3 control-label"><?= lang("Pricing group") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="fin_cust_pricing_group_id" style="width:100%"></select>
                                            <span id="fin_cust_pricing_group_id_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fst_unit" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="fst_unit_pricing" style="width:100%"></select>
                                            <span id="fst_unit_pricing_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdc_selling_price" class="col-md-3 control-label"><?= lang("Selling Price") ?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control text-right money" id="fdc_selling_price" value="0">
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-footer">
                                    <button id="btn-add-special-pricing" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
                                    <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $("#btn-add-special_pricing").click(function(event) {
                event.preventDefault();
                $("#mdlSpecialPricing").modal('show');
            });
            $("#tbl_special_pricing").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID") ?>",
                        "width": "5%",
                        data: "fin_rec_id",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item Name") ?>",
                        "width": "5%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Pricing group") ?>",
                        "width": "5%",
                        data: "fin_cust_pricing_group_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Pricing group") ?>",
                        "width": "15%",
                        data: "fst_cust_pricing_group_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Unit") ?>",
                        "width": "5%",
                        data: "fst_unit",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Selling Price") ?>",
                        "width": "10%",
                        data: "fdc_selling_price",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "<?= lang("Action") ?>",
                        "width": "7%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-special-pricing edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            //action += "<a class='btn-view-document-items' href='#'><i class='fa fa-folder-open' aria-hidden='true'></i></a>";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_special_pricing").on("click", ".btn-delete-special-pricing", function(event) {
                event.preventDefault();
                t = $("#tbl_special_pricing").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });
            $("#fin_cust_pricing_group_id").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/item/get_data_pricinggroup',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fin_cust_pricing_group_id,
                                "text": value.fst_cust_pricing_group_name
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
            var selected_pricinggroup;
            $('#fin_cust_pricing_group_id').on('select2:select', function(e) {
                console.log(selected_pricinggroup);
                var data = e.params.data;
                selected_pricinggroup = data;
            });
            //var data = $('#frmItem').val();
            var $fst_item_code = $("#fin_item_id").val();
            $("#fst_unit_pricing").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/item/get_data_unitbom/' + $fst_item_code,
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fst_unit,
                                "text": value.fst_unit
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
            var selected_unitpricing;
            $('#fst_unit_pricing').on('select2:select', function(e) {
                console.log(selected_unitpricing);
                var data = e.params.data;
                selected_unitpricing = data;
            });
            $("#btn-add-special-pricing").click(function(event) {
                event.preventDefault();
                var pricingGroup = $("#fin_cust_pricing_group_id").val();
                if (pricingGroup == null || pricingGroup == "") {
                    $("#fin_cust_pricing_group_id_error").html("Please select Pricing Group");
                    $("#fin_cust_pricing_group_id_error").show();
                } else {
                    $("#fin_cust_pricing_group_id_error").hide();
                }
                var unitPricing = $("#fst_unit_pricing").val();
                if (unitPricing == null || unitPricing == "") {
                    $("#fst_unit_pricing_error").html("Please select Unit");
                    $("#fst_unit_pricing_error").show();
                } else {
                    $("#fst_unit_pricing_error").hide();
                }
                t = $('#tbl_special_pricing').DataTable();
                var sellingPrice = numeral($("#fdc_selling_price").val());
                t.row.add({
                    fin_rec_id: 0,
                    fin_item_id: 0,
                    fst_unit: selected_unitpricing.id,
                    fin_cust_pricing_group_id: selected_pricinggroup.id,
                    fst_cust_pricing_group_name: selected_pricinggroup.text,
                    fdc_selling_price: sellingPrice.value(),
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<div id="modal_Printed" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:60%;min-width:600px;max-width:100%">
        <!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= lang("Daftar Barang") ?></h4>
			</div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="select-vendorName" class="col-md-3 control-label"><?= lang("Vendor Item Name") ?> :</label>
                                    <div class="col-md-7">
                                        <select id="select-vendorName" class="form-control" name="fst_vendor_item_name">
                                            
                                        </select>
                                        <div id="fst_vendor_item_name_err" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="select-groupItemName" class="col-md-3 control-label"><?= lang("Group") ?> :</label>
                                    <div class="col-md-7">
                                        <select id="select-groupItemName" class="form-control" name="fin_item_group_id">
                                            <option value="0">-- <?= lang("select") ?> --</option>
                                        </select>
                                        <div id="fst_item_group_name_err" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="select-ItemCode" class="col-md-3 control-label"><?= lang("Item Code") ?> :</label>
                                    <div class="col-md-3">
                                        <select id="select-ItemCode" class="form-control" name="fst_item_code">
                                            <option value="0">--  <?= lang("select") ?>  --</option>
                                        </select>
                                        <div id="fst_item_code_err" class="text-danger"></div>
                                    </div>
                                    <label for="select-CodeItem" class="col-md-1 control-label"><?= lang("s/d") ?> :</label>
                                    <div class="col-md-3">
                                        <select id="select-CodeItem" class="form-control" name="fst_item_code">
                                            <option value="0">--  <?= lang("select") ?>  --</option>
                                        </select>
                                        <div id="fst_item_code_err" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mdlBomDetails" class="col-md-3 control-label"><?= lang("BOM") ?> :</label>
                                    <div class="checkbox col-md-2">
                                        <label><input id="mdlBomDetails" type="checkbox" name="mdlBomDetails" value="1"><?= lang("BOM") ?></label><br>
                                    </div>
                                </div>
                            </form>

                            <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                <button id="btnPrint" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Print")?></button>
                                <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                            </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    echo $mdlItemGroup;
?>

<?php
    echo $mdlPrint;
?>

<script type="text/javascript">
    $(function() {
        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_item_id").val());
        <?php } ?>
        
        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            t = $('#tbl_unit_details').DataTable();
            if ( ! t.data().any()){
                alert("Unit Details is empty");
            }else{
                saveAjax();
            }
            console.log(data);
            //var formData = new FormData($('form')[0])
            /*$.ajax({
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
                                        window.location.href = "<?= site_url() ?>master/item";
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
            });*/
        });

        $("#fst_image").change(function(event) {
			event.preventDefault();
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#imgItem").attr('src', e.target.result);
			}
			reader.readAsDataURL(this.files[0]);
		});

        $("#select-GroupItemId").select2({
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
        }).on("select2:open",function(e){
            e.preventDefault();
            $(this).select2("close");
            showItemGroup(true,function(node){
                //consoleLog(node);                
                $("#select-GroupItemId").empty();
                var newOption = new Option(node.text,node.id, false, false);
                $('#select-GroupItemId').append(newOption).trigger('change');
            });
        });

        $("#select-vendorName").select2({
            width: '100%',
            allowClear: true,           
            placeholder: 'select.....',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_relationVendor',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value){
                        data2.push({
                            "id": value.fin_relation_id,
                            "text": value.fst_relation_name
                        });
                    });
                    console.log(data2);
                    return {
                        results: data2
                    };
                },
                cache: true
            }
        });

        $("#select-groupItemName").select2({
            width: '100%',
            allowClear: true,           
            placeholder: 'select.....',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_groupItemName',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value){
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
        }).on("select2:open",function(e){
            e.preventDefault();
            $(this).select2("close");
            showItemGroup(true,function(node){
                $("#select-groupItemName").empty();
                var newOption = new Option(node.text,node.id,false,false);
                $('#select-groupItemName').append(newOption).trigger('change');
            });
        });

        $("#select-lineBusiness").select2();

        $("#select-ItemCode").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemCode',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value){
                        data2.push({
                            "id": value.fst_item_code,
                            "text": value.fst_item_code
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

        $("#select-CodeItem").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemCode',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value){
                        data2.push({
                            "id": value.fst_item_code,
                            "text": value.fst_item_code
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
        
        $("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/item/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>master/item/delete/" + $("#fin_item_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>master/item/lizt";
									//return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_item_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_item_name').prop('readonly', true);
				}
			});
		});

		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/item/lizt");
		});

        $("#btnPrinted").click(function(e){
			$("#modal_Printed").modal("toggle");
		});

        $("#btnPrint").click(function(e){
            layoutColumn = [
				{column: "Item Code",hidden:false,id:"fst_item_code"},
                {column: "Item Name",hidden:false,id:"fst_item_name"},
				{column: "Harga Beli",hidden:false,id:"fdc_price_list"},
				{column: "Satuan",hidden:false,id:"fst_unit"},
				{column: "Harga Jual",hidden:false,id:"fdc_selling_price"},
				{column: "Satuan",hidden:false,id:"fst_unit"},
                {column: "Retail",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Hypermart",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Grosir",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Sekolah/PO",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "MT Lokal",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Group SMM/Internal",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Online Shop",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Tous Les Jours",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Tourtuile",hidden:true,id:"fin_cust_pricing_group_id"},
                {column: "Bazar",hidden:true,id:"fin_cust_pricing_group_id"}
			];
			url = "<?= site_url() ?>master/item/get_printItem/" + $("#select-vendorName").val() + '/' + $("#select-groupItemName").val() + '/' + $("#select-ItemCode").val() + '/' + $("#select-CodeItem").val();
            MdlPrint.showPrint(layoutColumn,url);
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
                var newOption = new Option(resp.ms_items.fst_item_group_name, resp.ms_items.fin_item_group_id, true, true);
                // Append it to the select
                $('#select-GroupItemId').append(newOption).trigger('change');
                //var newOption = new Option(resp.ms_items.fst_item_subgroup_name, resp.ms_items.fin_item_subgroup_id, true, true);

                //----select2 for modal report
                //var newOption = new Option(resp.ms_items.standardVendor, resp.ms_items.fin_standard_vendor_id, true, true);
                //$('#select-vendorName').append(newOption).trigger('change');
                //var newOption = new Option(resp.ms_items.fst_item_group_name, resp.ms_items.fin_item_group_id, true, true);
                //$('#select-groupItemName').append(newOption).trigger('change');

                //populate Linebusiness (select2)
                var arrLinebusiness = (resp.ms_items.fst_linebusiness_id.split(","));
                $('#select-lineBusiness').val(arrLinebusiness).trigger("change.select2");

                var newOption = new Option(resp.ms_items.fst_item_code, resp.ms_items.fst_item_code, true, true);
                $('#select-ItemCode').append(newOption).trigger('change');
                var newOption = new Option(resp.ms_items.fst_item_code, resp.ms_items.fst_item_code, true, true);
                $('#select-CodeItem').append(newOption).trigger('change');

                // Append it to the select
                $('#select-SubgItemId').append(newOption).trigger('change');
                //Image Load 
				$('#imgItem').attr("src", resp.ms_items.imageURL);
                //populate Unit Detail
                $.each(resp.unit_Detail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_unit_details').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_item_id: val.fin_item_id,
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
                })
                //populate Bom Detail
                $.each(resp.bom_Detail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_bom_details').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_item_id: val.fin_item_id,
                        fin_item_id_bom: val.fin_item_id_bom,
                        BomName: val.fst_item_name,
                        fst_unit: val.fst_unit,
                        action: action
                    }).draw(false);
                })
                //populate Special pricing
                $.each(resp.special_Pricing, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_special_pricing').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_item_id: val.fin_item_id,
                        fin_cust_pricing_group_id: val.fin_cust_pricing_group_id,
                        fst_cust_pricing_group_name: val.fst_cust_pricing_group_name,
                        fst_unit: val.fst_unit,
                        fdc_selling_price: val.fdc_selling_price,
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

    function saveAjax(){
        data = new FormData($("#frmItem")[0]);

        detail = new Array();
        t = $('#tbl_unit_details').DataTable();
        datas = t.data();
        $.each(datas, function(i, v) {
            detail.push(v);
        });
        /*data.push({
            name: "detail",
            value: JSON.stringify(detail)
        });*/
        data.append("detail",JSON.stringify(detail));
        // save BOM
        detailBOM = new Array();
        b = $('#tbl_bom_details').DataTable();
        datas = b.data();
        $.each(datas, function(i, v) {
            detailBOM.push(v);
        });
        /*data.push({
            name: "detailBOM",
            value: JSON.stringify(detailBOM)
        });*/
        data.append("detailBOM",JSON.stringify(detailBOM));
        // save Special pricing
        specialprice = new Array();
        p = $('#tbl_special_pricing').DataTable();
        datas = p.data();
        $.each(datas, function(i, v) {
            specialprice.push(v);
        });
        /*data.push({
            name: "specialprice",
            value: JSON.stringify(specialprice)
        });*/
        data.append("specialprice",JSON.stringify(specialprice));
        mode = $("#frm-mode").val();
        if (mode == "ADD") {
            url = "<?= site_url() ?>master/item/ajx_add_save";
        } else {
            url = "<?= site_url() ?>master/item/ajx_edit_save";
        }
        console.log(data);
        //var formData = new FormData($('form')[0])
        App.blockUIOnAjaxRequest("Please wait while saving data.....");
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
                                    $("#btnNew").trigger("click");
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
    }
</script>

<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.min.js"></script>

<script type="text/javascript">
   function fixedSelect2(){
        $(".select2-container").addClass("form-control"); 
        $(".select2-selection--single , .select2-selection--multiple").css({
            "border":"0px solid #000",
            "padding":"0px 0px 0px 0px"
        });         
        $(".select2-selection--multiple").css({
            "margin-top" : "-5px",
            "background-color":"unset"
        });
    };
</script>
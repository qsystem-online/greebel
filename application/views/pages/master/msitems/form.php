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
                                <input type="text" class="form-control text-left money" id="MinBasicUnitAvgCost" placeholder="<?= lang("Minimal BasicUnitAvgCost") ?>" value="0" name="MinBasicUnitAvgCost">
                                <div id="MinBasicUnitAvgCost_err" class="text-danger"></div>
                            </div>

                            <label for="MaxBasicUnitAvgCost" class="col-md-2 control-label"><?= lang("Max AvgCost") ?>:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-left money" id="MaxBasicUnitAvgCost" placeholder="<?= lang("Maximal BasicUnitAvgCost") ?>" value="0" name="MaxBasicUnitAvgCost">
                                <div id="MaxBasicUnitAvgCost_err" class="text-danger"></div>
                            </div>
                        </div>
                        <!-- end box body -->
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#unit_details" data-toggle="tab" aria-expanded="true"><?= lang("Unit Details") ?></a></li>
                                <li class="bom_details" id="tab-doc"><a href="#bom_details" data-toggle="tab" aria-expanded="false"><?= lang("BOM Details") ?></a></li>
                                <li class="special_pricing"><a href="#special_pricing" data-toggle="tab" aria-expanded="false"><?= lang("Special Pricing") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="unit_details">
                                    <button id="btn-add-unit-details" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Unit") ?></button>
                                    <div>
                                        <table id="tbl_unit_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="bom_details">
                                    <button id="btn-add-bom" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add BOM") ?></button>
                                    <div>
                                        <table id="tbl_bom_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="special_pricing">
                                    <button id="btn-add-special_pricing" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Special Pricing") ?></button>
                                    <div>
                                        <table id="tbl_special_pricing" class="table table-bordered table-hover" style="width:100%;"></table>
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

<div id="mdlUnitDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Unit Details") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="Unit" class="col-md-2 control-label"><?= lang("Unit") ?></label>
                        <div class="col-md-4">
                            <select class="select2 form-control" id="Unit" style="width:100%"></select>
                            <span id="Unit_error" class="text-danger"></span>
                        </div>
                        <label for="Conv2BasicUnit" class="col-md-2 control-label"><?= lang("Konversi") ?></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control text-right numeric" id="Conv2BasicUnit" value="1">
                            <span id="Conv2BasicUnit_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="PriceList" class="col-md-2 control-label"><?= lang("Price") ?></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control text-right money" id="PriceList" value="0">
                            <span id="PriceList_error" class="text-danger"></span>
                        </div>
                        <label for="HET" class="col-md-2 control-label"><?= lang("HET") ?></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control text-right money" id="HET" value="0">
                            <span id="HET_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isBasicUnit" class="col-sm-3 control-label"><?= lang("Basic") ?> :</label>
                        <div class="checkbox">
                            <label><input id="isBasicUnit" type="checkbox" name="isBasicUnit" value="1"><?= lang("Basic") ?></label><br>
                        </div>

                        <label for="isProductionOutput" class="col-sm-3 control-label"><?= lang("Production") ?> :</label>
                        <div class="checkbox">
                            <label><input id="isProductionOutput" type="checkbox" name="isProductionOutput" value="1"><?= lang("Production") ?></label><br>
                        </div>

                        <label for="isSelling" class="col-sm-3 control-label"><?= lang("Selling") ?> :</label>
                        <div class="checkbox">
                            <label><input id="isSelling" type="checkbox" name="isSelling" value="1"><?= lang("Selling") ?></label><br>
                        </div>

                        <label for="isBuying" class="col-sm-3 control-label"><?= lang("Buying") ?> :</label>
                        <div class="checkbox">
                            <label><input id="isBuying" type="checkbox" name="isBuying" value="1"><?= lang("Buying") ?></label><br>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-add-unit" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        data: "RecId",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item Name") ?>",
                        "width": "20%",
                        data: "ItemId",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Unit") ?>",
                        "width": "5%",
                        data: "Unit",
                        visible: true,

                    },

                    {
                        "title": "<?= lang("Basic") ?>",
                        "width": "5%",
                        data: "isBasicUnit",
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
                        data: "Conv2BasicUnit"
                    },
                    {
                        "title": "<?= lang("Selling") ?>",
                        "width": "5%",
                        data: "isSelling",
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
                        data: "isBuying",
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
                        data: "isProductionOutput",
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
                        data: "PriceList",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "<?= lang("HET") ?>",
                        "width": "10%",
                        data: "HET",
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


            $("#Unit").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/msitems/get_data_unit',
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

            var selected_unit;

            $('#Unit').on('select2:select', function(e) {
                console.log(selected_unit);
                var data = e.params.data;
                selected_unit = data;
            });

            $(".money").inputmask({
                alias: 'numeric',
                autoGroup: true,
                groupSeparator: ",",
                radixPoint: ".",
                allowMinus: false,
                autoUnmask: true,
                digits: 2
            });

            $("#btn-add-unit").click(function(event) {
                event.preventDefault();

                t = $('#tbl_unit_details').DataTable();
                addRow = true;
                var HET = numeral($("#HET").val());
                var PriceList = numeral($("#PriceList").val());

                var conversion = $("#Conv2BasicUnit").val();
                if (conversion == null || conversion == "") {
                    $("#Conv2BasicUnit_error").html("minimal 1");
                    $("#Conv2BasicUnit_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#Conv2BasicUnit_error").hide();
                }

                var unit = $("#Unit").val();
                if (unit == null || unit == "") {
                    $("#Unit_error").html("Please select Unit");
                    $("#Unit_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#Unit_error").hide();
                }

                var priceList = $("#PriceList").val();
                if (priceList == null || priceList == "") {
                    $("#PriceList_error").html("required");
                    $("#PriceList_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#PriceList_error").hide();
                }

                var het = $("#HET").val();
                if (het == null || het == "") {
                    $("#HET_error").html("required");
                    $("#HET_error").show();
                    addRow = false;
                    return;
                } else {
                    $("#HET_error").hide();
                }

                if ($("#isBasicUnit").is(":checked")) {
                    data = t.rows().data();
                    console.log(data);
                    $.each(data, function(i, v) {
                        if (v.isBasicUnit == "1") {
                            if (confirm("Basic unit telah didefinisikan untuk " + v.Unit + ", tambah data ?")) {
                                v.isBasicUnit = false;
                                console.log(data);
                                t.row(i).data(v).draw(false);
                            } else {
                                addRow = false;
                                return;
                            }
                        }
                    });

                }

                if ($("#isProductionOutput").is(":checked")) {
                    data = t.rows().data();
                    console.log(data);
                    $.each(data, function(i, v) {
                        if (v.isProductionOutput == "1") {
                            if (confirm("Production unit telah didefinisikan untuk " + v.Unit + ", tambah data ?")) {
                                v.isProductionOutput = false;
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
                        RecId: 0,
                        ItemId: 0,
                        Unit: selected_unit.text,
                        isBasicUnit: $("#isBasicUnit").prop("checked"),
                        Conv2BasicUnit: $("#Conv2BasicUnit").val(),
                        isSelling: $("#isSelling").prop("checked"),
                        isBuying: $("#isBuying").prop("checked"),
                        isProductionOutput: $("#isProductionOutput").prop("checked"),
                        PriceList: PriceList.value(),
                        HET: HET.value(),
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
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add BOM Details") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="ItemIdBOM" class="col-md-3 control-label"><?= lang("Item BOM") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="ItemIdBOM" style="width:100%"></select>
                            <span id="ItemIdBOM_error" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="unit-bom" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="unit-bom" style="width:100%"></select>
                            <span id="unit-bom_error" class="text-danger"></span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-add-bom-details" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        data: "recid",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item Name") ?>",
                        "width": "10%",
                        data: "ItemId",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("") ?>",
                        "width": "10%",
                        data: "ItemIdBOM",
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
                        data: "unit",
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

            $("#ItemIdBOM").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/msitems/get_data_ItemBom',
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
            var selected_bom;

            $('#ItemIdBOM').on('select2:select', function(e) {
                console.log(selected_bom);
                var data = e.params.data;
                selected_bom = data;

            });

            $("#ItemIdBOM").change(function(event) {
                event.preventDefault();
                $('#unit-bom').val(null).trigger('change');
                $("#unit-bom").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>Master/msitems/get_data_unitbom/' + $("#ItemIdBOM").val(),
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

            var selected_unitbom;

            $('#unit-bom').on('select2:select', function(e) {
                console.log(selected_unitbom);
                var data = e.params.data;
                selected_unitbom = data;
            });

            $("#btn-add-bom-details").click(function(event) {
                event.preventDefault();

                var itemBom = $("#ItemIdBOM").val();
                if (itemBom == null || itemBom == "") {
                    $("#ItemIdBOM_error").html("Please select Item");
                    $("#ItemIdBOM_error").show();
                } else {
                    $("#ItemIdBOM_error").hide();
                }

                var unitBom = $("#unit-bom").val();
                if (unitBom == null || unitBom == "") {
                    $("#unit-bom_error").html("Please select Unit");
                    $("#unit-bom_error").show();
                } else {
                    $("#unit-bom_error").hide();
                }
                t = $('#tbl_bom_details').DataTable();

                t.row.add({
                    recid: 0,
                    ItemId: 0,
                    ItemIdBOM: selected_bom.id,
                    BomName: selected_bom.text,
                    unit: selected_unitbom.text,
                    action: action
                }).draw(false);

            });
        });
    </script>
</div>
<div id="mdlSpecialPricing" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Special Pricing") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="PricingGroupId" class="col-md-3 control-label"><?= lang("Pricing group") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="PricingGroupId" style="width:100%"></select>
                            <span id="PricingGroupId_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Unit" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="unit_pricing" style="width:100%"></select>
                            <span id="unit_pricing_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="SellingPrice" class="col-md-3 control-label"><?= lang("Selling Price") ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control text-right money" id="SellingPrice" value="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-add-special-pricing" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        data: "RecId",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item Name") ?>",
                        "width": "5%",
                        data: "ItemId",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Pricing group") ?>",
                        "width": "5%",
                        data: "PricingGroupId",
                        visible: false,

                    },
                    {
                        "title": "<?= lang("Pricing group") ?>",
                        "width": "15%",
                        data: "PricingGroupName",
                        visible: true,

                    },
                    {
                        "title": "<?= lang("Unit") ?>",
                        "width": "5%",
                        data: "Unit",
                        visible: true,

                    },
                    {
                        "title": "<?= lang("Selling Price") ?>",
                        "width": "10%",
                        data: "SellingPrice",
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

            $("#PricingGroupId").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/msitems/get_data_pricinggroup',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.CustPricingGroupId,
                                "text": value.CustPricingGroupName
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

            $('#PricingGroupId').on('select2:select', function(e) {
                console.log(selected_pricinggroup);
                var data = e.params.data;
                selected_pricinggroup = data;

            });

            //var data = $('#frmMSItems').val();
            var $itemCode = $("#ItemId").val();
            $("#unit_pricing").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>Master/msitems/get_data_unitbom/' + $itemCode,
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
            var selected_unitpricing;

            $('#unit_pricing').on('select2:select', function(e) {
                console.log(selected_unitpricing);
                var data = e.params.data;
                selected_unitpricing = data;

            });

            $("#btn-add-special-pricing").click(function(event) {
                event.preventDefault();

                var pricingGroup = $("#PricingGroupId").val();
                if (pricingGroup == null || pricingGroup == "") {
                    $("#PricingGroupId_error").html("Please select Pricing Group");
                    $("#PricingGroupId_error").show();
                } else {
                    $("#PricingGroupId_error").hide();
                }

                var unitPricing = $("#unit_pricing").val();
                if (unitPricing == null || unitPricing == "") {
                    $("#unit_pricing_error").html("Please select Unit");
                    $("#unit_pricing_error").show();
                } else {
                    $("#unit_pricing_error").hide();
                }

                t = $('#tbl_special_pricing').DataTable();
                var sellingPrice = numeral($("#SellingPrice").val());

                t.row.add({
                    RecId: 0,
                    ItemId: 0,
                    Unit: selected_unitpricing.id,
                    PricingGroupId: selected_pricinggroup.id,
                    PricingGroupName: selected_pricinggroup.text,
                    SellingPrice: sellingPrice.value(),
                    action: action
                }).draw(false);

            });
        });
    </script>
</div>
<script type="text/javascript">
    $(function() {
        <?php if ($mode == "EDIT") { ?>
            init_form($("#ItemId").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();

            data = $("#frmMSItems").serializeArray();
            //data = new FormData($("#frmMSItems")[0]);
            detail = new Array();

            t = $('#tbl_unit_details').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });

            data.push({
                name: "detail",
                value: JSON.stringify(detail)
            });

            // save BOM
            detailBOM = new Array();

            b = $('#tbl_bom_details').DataTable();
            datas = b.data();
            $.each(datas, function(i, v) {
                detailBOM.push(v);
            });

            data.push({
                name: "detailBOM",
                value: JSON.stringify(detailBOM)
            });


            // save Special pricing
            specialprice = new Array();

            p = $('#tbl_special_pricing').DataTable();
            datas = p.data();
            $.each(datas, function(i, v) {
                specialprice.push(v);
            });

            data.push({
                name: "specialprice",
                value: JSON.stringify(specialprice)
            });

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>Master/msitems/ajx_add_save";
            } else {
                url = "<?= site_url() ?>Master/msitems/ajx_edit_save";
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
        })


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

                //populate Unit Detail
                $.each(resp.unitDetail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_unit_details').DataTable();

                    t.row.add({
                        RecId: val.RecId,
                        ItemId: val.ItemId,
                        Unit: val.Unit,
                        isBasicUnit: val.isBasicUnit,
                        Conv2BasicUnit: val.Conv2BasicUnit,
                        isSelling: val.isSelling,
                        isBuying: val.isBuying,
                        isProductionOutput: val.isProductionOutput,
                        PriceList: val.PriceList,
                        HET: val.HET,
                        action: action
                    }).draw(false);
                })

                //populate Bom Detail
                $.each(resp.bomDetail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_bom_details').DataTable();

                    t.row.add({
                        recid: val.recid,
                        ItemId: val.ItemId,
                        ItemIdBOM: val.ItemIdBOM,
                        BomName: val.ItemName,
                        unit: val.unit,
                        action: action
                    }).draw(false);
                })

                //populate Special pricing
                $.each(resp.specialpricing, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_special_pricing').DataTable();

                    t.row.add({
                        RecId: val.RecId,
                        ItemId: val.ItemId,
                        PricingGroupId: val.PricingGroupId,
                        PricingGroupName: val.CustPricingGroupName,
                        Unit: val.Unit,
                        SellingPrice: val.SellingPrice,
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
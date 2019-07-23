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

                <!-- form start fbl_is_multiples_prize -->
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

                            <!--<label for="fin_promo_qty" class="col-md-1 control-label"><?= lang("Free Qty") ?> :</label>-->
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_promo_qty" placeholder="<?= lang("0") ?>" name="fin_promo_qty">
                                <div id="fin_promo_qty_err" class="text-danger"></div>
                            </div>

                            <!---<label for="select-promo_unit" class="col-md-1 control-label"><?= lang("Unit") ?> :</label>-->
                            <div class="col-md-2">
                                <select id="select-promo_unit" class="form-control" name="fin_promo_unit"></select>
                                <div id="fin_promo_unit_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fin_cashback" class="col-md-2 control-label"><?= lang("/CashBack (Rp.)") ?>:</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control money" id="fin_cashback" placeholder="<?= lang("0") ?>" name="fin_cashback">
                                <div id="fin_cashback_err" class="text-danger"></div>
                            </div>
                            <label for="fst_other_prize" class="col-md-2 control-label"><?= lang("/Other Item") ?>:</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="fst_other_prize" placeholder="<?= lang("Other Item") ?>" name="fst_other_prize">
                                <div id="fst_other_prize_err" class="text-danger"></div>
                            </div>
                            <label for="fdc_other_prize_in_value" class="col-md-1 control-label"><?= lang("Value") ?>:</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control money" id="fdc_other_prize_in_value" placeholder="<?= lang("0") ?>" name="fdc_other_prize_in_value">
                                <div id="fdc_other_prize_in_value_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_promo_type" class="col-sm-2 control-label"><?= lang("Type") ?></label>
                            <div class="col-sm-2">
                                <select class="form-control" id="fst_promo_type" name="fst_promo_type">
                                    <option value='OFFICE'><?= lang("OFFICE") ?></option>
                                    <option value='POS'><?= lang("POS") ?></option>
                                </select>
                            </div>
                            <label for="fdt_start" class="col-md-2 control-label"><?= lang("Start Date") ?> *</label>
                            <div class="col-md-2">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_start" name="fdt_start" />
                                </div>
                                <div id="fdt_start_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                            <label for="fdt_end" class="col-md-2 control-label"><?= lang("End Date") ?> *</label>
                            <div class="col-md-2">
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
                        <div class='form-group'>
                            <label for="fbl_promo_gabungan" class="col-md-2 control-label"><?= lang("") ?></label>
                            <div class="col-md-2">
                                <div>
                                    <input type="checkbox" class="minimal form-control icheck" id="fbl_promo_gabungan" name="fbl_promo_gabungan"> &nbsp;
                                    <label for="fbl_promo_gabungan" class=""> <?= lang("Allow Combined")?> </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div>
                                    <input type="checkbox" class="minimal form-control icheck" id="fbl_is_multiples_prize" name="fbl_is_multiples_prize"> &nbsp;
                                    <label for="fbl_is_multiples_prize" class=""> <?= lang("Multiple prize")?> </label>
                                </div>
                            </div>
                        </div>
                        <!-- end box body -->
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#promo_item_details" data-toggle="tab" aria-expanded="true"><?= lang("Promo Terms") ?></a></li>
                                <li class="promo_customer_details" id="tab-doc"><a href="#promo_customer_details" data-toggle="tab" aria-expanded="false"><?= lang("Promo Participants") ?></a></li>
                                <li class="promo_discount_details" id="tab-doc"><a href="#promo_discount_details" data-toggle="tab" aria-expanded="false"><?= lang("Discount Items") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="promo_item_details">
                                    <form class="form-horizontal edit-mode ">	
                                        <div class="form-group">
                                            <div class="col-md-9">				
                                                <label for="fdc_min_total_purchase" class="col-md-3 control-label"><?= lang("Minimum Purchase") ?>:</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control money" id="fdc_min_total_purchase" placeholder="<?= lang("0") ?>" name="fdc_min_total_purchase">
                                                    <div id="fdc_min_total_purchase_err" class="text-danger"></div>
                                                </div>		
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" id="fdb_qty_gabungan" placeholder="<?= lang("0") ?>" name="fdb_qty_gabungan">
                                                    <div id="fdb_qty_gabungan_err" class="text-danger"></div>
                                                </div>						
                                                <div class="col-md-2">
                                                    <select class="select2 form-control" id="fst_unit_gabungan" name="fst_unit_gabungan" style="width:100%"></select>
                                                    <div id="fst_unit_gabungan_err" class="text-danger"></div>
                                                </div>
                                            </div>				
                                            <div class="col-md-2">
                                                <div>
                                                    <input type="checkbox" class="minimal form-control icheck" id="fbl_qty_gabungan" name="fbl_qty_gabungan"> &nbsp;
                                                    <label for="fbl_qty_gabungan" class=""> <?= lang("Combined")?> </label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-1">
                                            <button id="btn-add-item-details" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Item") ?></button>
                                            </div>						
                                        
                                        </div>
                                    </form>
                                    <table id="tbl_item_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                </div>
                                <div class="tab-pane" id="promo_customer_details">
                                    <button id="btn-add-customer-promo" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Customer") ?></button>
                                    <div>
                                        <table id="tbl_customer_promo" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="promo_discount_details">
                                    <button id="btn-add-discount-promo" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= lang("Add Disc Item") ?></button>
                                    <div>
                                        <table id="tbl_discount_promo" class="table table-bordered table-hover" style="width:100%;"></table>
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
                <h4 class="modal-title"><?= lang("Add Item terms") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="fst_item_type" class="col-sm-3 control-label"><?= lang("Type") ?></label>
                        <div class="col-sm-4">
                            <select class="form-control" id="fst_item_type" name="fst_item_type">
                                <option value="0">-- <?=lang("select")?> --</option>
                                <option value="ITEM"><?= lang("ITEM") ?></option>
                                <option value="SUB GROUP"><?= lang("SUB GROUP") ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fin_item_id" class="col-md-3 control-label"><?= lang("Item") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="fin_item_id" style="width:100%"></select>
                            <span id="fin_item_id_err" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fst_unit" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                        <div class="col-md-4">
                            <select id="fst_unit" class="form-control" name="fst_unit" style="width:100%"></select>
                            <span id="fst_unit_err" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
						<label for="fdb_qty" class="col-md-3 control-label"><?=lang("Qty")?></label>
						<div class="col-md-4">
							<input type="number" class="form-control text-right numeric" id="fdb_qty" value="0">
							<div id="fdb_qty_err" class="text-danger"></div>
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
                var unitCombined = $("#fst_unit_gabungan").val();
                if ($("#fbl_qty_gabungan").is(":checked")){
                    if (unitCombined == null || unitCombined == "") {
                        $("#fst_unit_gabungan_err").html("Please select Unit terms before add item");
                        $("#fst_unit_gabungan_err").show();
                    } else {
                        $("#fst_unit_gabungan_err").hide();
                        $("#mdlItemDetails").modal('show');
                    }
                } else {
                    $("#fst_unit_gabungan_err").hide();
                    $("#mdlItemDetails").modal('show');
                }
            });
            $("#tbl_item_details").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [/*{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_id",
                        visible: false
                    },*/
                    {
                        "title": "<?= lang("Item ID ") ?>",
                        "width": "10%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Item Name ") ?>",
                        "width": "25%",
                        data: "fst_item_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Type ") ?>",
                        "width": "10%",
                        data: "fst_item_type",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Unit ") ?>",
                        "width": "5%",
                        data: "fst_unit",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Qty ") ?>",
                        "width": "5%",
                        data: "fdb_qty",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
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
            $("#fst_unit_gabungan").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/promotion/get_data_unit',
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
            /*$("#fin_item_id").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/promotion/get_data_ItemPromo',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.ItemId,
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
            });*/
            $("#fst_item_type").change(function(event){
                event.preventDefault();
                //$('#fin_item_id').val(null).trigger('change');
                $('#fin_item_id').empty();
                type = $("#fst_item_type").val();
                if (type =="ITEM"){
                    select_itemDetail();
                }else{
                    select_itemSubgroup();
                }
            });
            $("#fin_item_id").change(function(event) {
                event.preventDefault();
                $('#fst_unit').val(null).trigger('change');
                type = $("#fst_item_type").val();
                if (type =="ITEM"){
                    $("#fst_unit").select2({
                        width: '100%',
                        ajax: {
                            url: '<?= site_url() ?>master/promotion/get_data_unitTerms/'+$("#fin_item_id").val(),
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                units = [];
                                data = data.data;
                                $.each(data,function(index,value) {
                                    units.push({
                                        "id" : value.fst_unit,
                                        "text" : value.fst_unit
                                    });
                                });
                                console.log(units);
                                return {
                                    results: units
                                };
                            },
                            cache: true,
                        }
                    });
                }else{
                    $("#fst_unit").select2({
                        width: '100%',
                        ajax: {
                            url: '<?= site_url() ?>master/promotion/get_data_unit',
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
                }
            });
            $('#fin_item_id').on('select2:select', function(e) {
                //console.log(selected_itempromo);
                selected_itempromo = $('#fin_item_id').select2('data')[0];
                console.log(selected_itempromo);
                //var data = e.params.data;
                //selected_itempromo = data;
            });
            $('#fst_unit').on('select2:select', function(e) {
                selected_unitdetail = $('#fst_unit').select2('data')[0];
                console.log(selected_unitdetail);
            });
            function select_itemDetail(){
                $("#fin_item_id").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/promotion/get_data_ItemPromo',
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
            }
            function select_itemSubgroup(){
                $("#fin_item_id").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/promotion/get_item_SubgroupPromo',
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
            }
            $("#btn-add-item").click(function(event) {
                event.preventDefault();
                t = $('#tbl_item_details').DataTable();
                addRow = true;
                var itemTerms = $("#fin_item_id").val();
                if (itemTerms == null || itemTerms == "") {
                    $("#fin_item_id_err").html("Please select item");
                    $("#fin_item_id_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#fin_item_id_err").hide();
                }
                var unitTerms = $("#fst_unit").val();              
                if (unitTerms == null || unitTerms == "") {
                    $("#fst_unit_err").html("Please select unit");
                    $("#fst_unit_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#fst_unit_err").hide();
                }
                var unitCombined = $("#fst_unit_gabungan").val();
                if ($("#fbl_qty_gabungan").is(":checked")){
                    if (unitCombined != selected_unitdetail.text ) {
                        alert(selected_unitdetail.text);
                        $("#fst_unit_err").html("Not match with unit terms");
                        $("#fst_unit_err").show();
                        addRow = false;
                        return;
                    } else {
                        $("#fst_unit_err").hide();
                    }  
                }else{
                    $("#fst_unit_err").hide();                    
                }
                t.row.add({
                    fin_id: 0,
                    fin_promo_id: 0,
                    fst_item_type: $("#fst_item_type").val(),
                    fin_item_id: selected_itempromo.id,
                    fst_item_name: selected_itempromo.text,
                    fst_unit: selected_unitdetail.text,
                    fdb_qty: $("#fdb_qty").val(),
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
                        <label for="fst_participant_type" class="col-sm-3 control-label"><?= lang("Type") ?></label>
                        <div class="col-sm-4">
                            <select class="form-control" id="fst_participant_type" name="fst_participant_type">
                                <option value="0">-- <?=lang("select")?> --</option>
                                <option value="RELATION"><?= lang("RELATION") ?></option>
                                <option value="MEMBER GROUP"><?= lang("MEMBER GROUP") ?></option>
                                <option value="RELATION GROUP"><?= lang("RELATION GROUP") ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fin_customer_id" class="col-md-3 control-label"><?= lang("Customer") ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control" id="fin_customer_id" style="width:100%"></select>
                            <span id="fin_customer_id_err" class="text-danger"></span>
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
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Promo ID ") ?>",
                        "width": "5%",
                        data: "fin_promo_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Customer ID") ?>",
                        "width": "5%",
                        data: "fin_customer_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Customer Name ") ?>",
                        "width": "40%",
                        data: "customer_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Type") ?>",
                        "width": "5%",
                        data: "fst_participant_type",
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
            $("#fst_participant_type").change(function(event){
                event.preventDefault();
                //$('#fin_customer_id').val(null).trigger('change');
                $('#fin_customer_id').empty();
                type = $("#fst_participant_type").val();
                if (type =="RELATION"){
                    select_relation();
                }else if (type =="RELATION GROUP"){
                    select_relationgroup();
                }else {
                    select_membergroup();
                }
            });
            function select_relation(){
                $("#fin_customer_id").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/promotion/get_relationpromo',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            data2 = [];
                            $.each(data, function(index, value) {
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
                        cache: true,
                    }
                });
            }
            function select_membergroup(){
                $("#fin_customer_id").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/promotion/get_membergrouppromo',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            data2 = [];
                            $.each(data, function(index, value) {
                                data2.push({
                                    "id": value.fin_member_group_id,
                                    "text": value.fst_member_group_name
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
            }
            function select_relationgroup(){
                $("#fin_customer_id").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/promotion/get_relationgrouppromo',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            data2 = [];
                            $.each(data, function(index, value) {
                                data2.push({
                                    "id": value.fin_relation_group_id,
                                    "text": value.fst_relation_group_name
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
            }
            
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
                    fst_participant_type: $("#fst_participant_type").val(),
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<div id="mdlDiscountDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:50%;min-width:350px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add discount item") ?></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal ">
                    <div class="form-group">
                        <label for="select-item-disc" class="col-md-3 control-label"><?= lang("Item") ?></label>
                        <div class="col-md-9">
                            <select id="select-item-disc" class="form-control" name="fin_item_id" style="width:100%"></select>
                            <span id="fin_item_id_err" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="select-unit-disc" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                        <div class="col-md-4">
                            <select id="select-unit-disc" class="form-control" name="fst_unit" style="width:100%"></select>
                            <span id="fst_unit_err" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
						<label for="fin_qty" class="col-md-3 control-label"><?=lang("Qty")?></label>
						<div class="col-md-4">
							<input type="number" class="form-control text-right numeric" id="fin_qty" value="0">
							<div id="fin_qty_err" class="text-danger"></div>
						</div>
					</div>
                    <div class="form-group">
						<label for="fdc_disc_persen" class="col-md-3 control-label"><?=lang("Disc %")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right numeric" id="fdc_disc_persen" value="0">
							<div id="fdc_disc_persen_err" class="text-danger"></div>
						</div>
					</div>
                    <div class="form-group">
						<label for="fdc_disc_value" class="col-md-3 control-label"><?=lang("Disc Value")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right money" id="fdc_disc_value" value="0">
							<div id="fdc_disc_value_err" class="text-danger"></div>
						</div>
					</div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-add-item-disc" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            $("#btn-add-discount-promo").click(function(event) {
                event.preventDefault();
                $("#mdlDiscountDetails").modal('show');
            });
            $("#tbl_discount_promo").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("Item ID ") ?>",
                        "width": "5%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Item Name ") ?>",
                        "width": "25%",
                        data: "fst_item_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Qty ") ?>",
                        "width": "3%",
                        data: "fin_qty",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Unit ") ?>",
                        "width": "3%",
                        data: "fst_unit",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Disc% ") ?>",
                        "width": "5%",
                        data: "fdc_disc_persen",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Disc") ?>",
                        "width": "5%",
                        data: "fdc_disc_value",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-discount-details edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_discount_promo").on("click", ".btn-delete-discount-details", function(event) {
                event.preventDefault();
                t = $("#tbl_discount_promo").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });

            $("#select-item-disc").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/promotion/get_data_ItemPromo',
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
            $("#select-item-disc").change(function(event) {
                event.preventDefault();
                $('#select-unit-disc').val(null).trigger('change');
                $("#select-unit-disc").select2({
                    width: '100%',
                    ajax: {
                        url: '<?= site_url() ?>master/promotion/get_data_unitTerms/'+$("#select-item-disc").val(),
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            units = [];
                            data = data.data;
                            $.each(data,function(index,value) {
                                units.push({
                                    "id" : value.fst_unit,
                                    "text" : value.fst_unit
                                });
                            });
                            console.log(units);
                            return {
                                results: units
                            };
                        },
                        cache: true,
                    }
                });
            });
            $('#select-item-disc').on('select2:select', function(e) {
                selected_itemdisc = $('#select-item-disc').select2('data')[0];
                console.log(selected_itemdisc);
            });
            $('#select-unit-disc').on('select2:select', function(e) {
                selected_unitdisc = $('#select-unit-disc').select2('data')[0];
                console.log(selected_unitdisc);
            });
            $("#btn-add-item-disc").click(function(event) {
                event.preventDefault();
                t = $('#tbl_discount_promo').DataTable();
                addRow = true;
                t.row.add({
                    fin_id: 0,
                    fin_promo_id: 0,
                    fin_item_id: selected_itemdisc.id,
                    fst_item_name: selected_itemdisc.text,
                    fin_qty: $("#fin_qty").val(),
                    fst_unit: selected_unitdisc.text,
                    fdc_disc_persen: $("#fdc_disc_persen").val(),
                    fdc_disc_value: $("#fdc_disc_value").val(),
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
            // save Discount per item promo
            detaildiscItem = new Array();
            b = $('#tbl_discount_promo').DataTable();
            datas = b.data();
            $.each(datas, function(i, v) {
                detaildiscItem.push(v);
            });
            data.push({
                name: "detaildiscItem",
                value: JSON.stringify(detaildiscItem)
            });
            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/promotion/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/promotion/ajx_edit_save";
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
                                        window.location.href = "<?= site_url() ?>master/promotion";
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
        $("#select-promo_item").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/promotion/get_data_ItemPromo',
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
        $("#select-promo_item").change(function(event) {
            event.preventDefault();
            $('#select-promo_unit').val(null).trigger('change');
            $("#select-promo_unit").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/promotion/get_data_unitPromo/' + $("#select-promo_item").val(),
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
        })
    });
    function init_form(fin_promo_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/promotion/fetch_data/" + fin_promo_id;
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
                $("#fdt_start").datepicker('update', dateFormat(resp.mspromo.fdt_start));
                $("#fdt_end").datepicker('update', dateFormat(resp.mspromo.fdt_end));
                // menampilkan data di select2
                var newOption = new Option(resp.mspromo.fst_item_name, resp.mspromo.fin_promo_item_id, true, true);
                // Append it to the select
                $('#select-promo_item').append(newOption).trigger('change');
                var newOption = new Option(resp.mspromo.fin_promo_unit, resp.mspromo.fin_promo_unit, true, true);
                // Append it to the select
                $('#select-promo_unit').append(newOption).trigger('change');
                var newOption = new Option(resp.mspromo.fst_unit_gabungan, resp.mspromo.fst_unit_gabungan, true, true);
                // Append it to the select
                $('#fst_unit_gabungan').append(newOption).trigger('change');
                //populate Promo Terms
                $.each(resp.promoTerms, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_item_details').DataTable();
                    t.row.add({
                    fin_id: val.fin_id,
                    fin_promo_id: val.fin_promo_id,
                    fst_item_type: val.fst_item_type,
                    fin_item_id: val.fin_item_id,
                    fst_item_name: val.ItemTerms,
                    fdb_qty: val.fdb_qty,
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
                        fst_participant_type: val.fst_participant_type,
                        fin_customer_id: val.fin_customer_id,
                        customer_name: val.ParticipantName,
                        action: action
                    }).draw(false);
                })

                //populate discount per item promo
                $.each(resp.promodiscItems, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_discount_promo').DataTable();
                    t.row.add({
                        fin_id: val.fin_id,
                        fin_promo_id: val.fin_promo_id,
                        fin_item_id: val.fin_item_id,
                        fst_item_name: val.fst_item_name,
                        fin_qty: val.fin_qty,
                        fst_unit: val.fst_unit,
                        fdc_disc_persen: val.fdc_disc_persen,
                        fdc_disc_value: val.fdc_disc_value,
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
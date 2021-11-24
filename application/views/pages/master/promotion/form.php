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
    .detail-terms{
        display: none;
    }
    .flag{
    display: none;
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
                    <div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
                </div>
                <!-- end box header -->

                <!-- form start fbl_is_multiples_prize -->
                <form id="frmPromotion" class="form-horizontal" action="<?= site_url() ?>master/promotion/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_promo_id" class="col-md-2 control-label"><?= lang("Promo ID") ?> #</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_promo_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_promo_id" value="<?= $fin_promo_id ?>" readonly>
                                <div id="fin_promo_id_err" class="text-danger"></div>
                            </div>
                            <label for="fst_promo_type" class="col-sm-2 control-label"><?= lang("Type") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="fst_promo_type" name="fst_promo_type">
                                    <option value='OFFICE'><?= lang("BULANAN") ?></option>
                                    <option value='PERIODE'><?= lang("PERIODE") ?></option>
                                    <!-- <option value='POS'><= lang("POS") ?></option> -->                                    
                                </select>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fst_promo_name" class="col-md-2 control-label"><?= lang("Promo Name") ?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_promo_name" placeholder="<?= lang("Promo Name") ?>" name="fst_promo_name">
                                <div id="fst_promo_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <label for="select-promo_item" class="col-md-2 control-label"><?= lang("Free Item") ?></label>
                            <div class="col-md-4">
                                <select id="select-promo_item" class="form-control" name="fin_promo_item_id"></select>
                            </div>

                            <label for="fin_promo_qty" class="col-md-1 control-label"><?= lang("Free Qty") ?> :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-right" id="fdb_promo_qty" placeholder="<?= lang("0") ?>" name="fdb_promo_qty">
                                <div id="fdb_promo_qty_err" class="text-danger"></div>
                            </div>

                            <label for="select-promo_unit" class="col-md-1 control-label"><?= lang("Unit") ?> :</label>
                            <div class="col-md-2">
                                <select id="select-promo_unit" class="form-control" name="fst_promo_unit"></select>
                                <div id="fst_promo_unit_err" class="text-danger"></div>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label for="fdt_start" class="col-md-2 control-label text-right"><?= lang("Start Date") ?> #</label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control text-right datepicker" id="fdt_start" name="fdt_start" />
                                </div>
                                <div id="fdt_start_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                            <label for="fdt_end" class="col-md-2 control-label text-right"><?= lang("End Date") ?></label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control text-right datepicker" id="fdt_end" name="fdt_end" />
                                </div>
                                <div id="fdt_end_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="fst_list_branch_id" class="col-md-2 control-label"><?=lang("Branch")?> #</label>
                            <div class="col-md-10">
                                <select class="form-control select2" id="fst_list_branch_id" name="fst_list_branch_id[]"  multiple="multiple">
                                </select>
                                <div id="fst_list_branch_id_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
						<label for="fst_other_prizes" class="col-md-2 control-label"><h6><i>*<?=lang("Other optional Free prize")?>  :</i></h6></label>
					    </div>
                        <div class='form-group'>
                            <label for="fdc_cashback" class="col-md-2 control-label"><?= lang("CashBack") ?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control money" id="fdc_cashback" placeholder="<?= lang("0") ?>" name="fdc_cashback">
                                <div id="fdc_cashback_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fst_other_prize" class="col-md-2 control-label"><?= lang("Other Item") ?></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_other_prize" placeholder="<?= lang("Other Item") ?>" name="fst_other_prize">
                                <div id="fst_other_prize_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fdc_other_prize_in_value" class="col-md-2 control-label"><?= lang("Value") ?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control money" id="fdc_other_prize_in_value" placeholder="<?= lang("0") ?>" name="fdc_other_prize_in_value">
                                <div id="fdc_other_prize_in_value_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="checkbox col-sm-2 col-md-offset- flag">
								<label class="checkbox-inline"><input id="fbl_is_multiples_prize" name='fbl_is_multiples_prize' type="checkbox" value="1"><?=lang("Multiple Prize")?></label>
							</div>
                            <div class="checkbox col-sm-2 col-md-offset-1 flag">
                                <label class="checkbox-inline"><input id="fbl_disc_per_item" name='fbl_disc_per_item' type="checkbox" value="1"><?=lang("Disk Per Item")?></label>
                            </div>
                        </div>

                        <div class="form-group">
						<label for="fdc_min_total_purchase" class="col-md-2 control-label"><h6><i>*<?=lang("Terms Of Promotion")?> :</i></h6></label>
					    </div>

                        <div class="form-group">				
                            <label for="fdc_min_total_purchase" class="col-md-2 control-label"><?= lang("Minimum Purchase") ?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control money" id="fdc_min_total_purchase" placeholder="<?= lang("0") ?>" name="fdc_min_total_purchase">
                                <div id="fdc_min_total_purchase_err" class="text-danger"></div>
                            </div>
                            <div class="checkbox col-sm-2">
								<label><input id="fbl_promo_gabungan" name='fbl_promo_gabungan' type="checkbox" value="1"><?=lang("Allow Other Promo")?></label>
							</div>					
                        </div>
                        <div class="form-group">
                            <label for="fdb_qty_gabungan" class="col-md-2 control-label"><?= lang("Minimum Qty") ?></label>					
                            <div class="col-md-2">
                                <input type="text" class="form-control text-right" id="fdb_qty_gabungan" placeholder="<?= lang("0") ?>" name="fdb_qty_gabungan">
                                <div id="fdb_qty_gabungan_err" class="text-danger"></div>
                            </div>						
                            <div class="col-md-2">
                                <select class="select2 form-control" id="fst_unit_gabungan" name="fst_unit_gabungan"></select>
                                <div id="fst_unit_gabungan_err" class="text-danger"></div>
                            </div>
                            <!--<div class="col-md-4">
                                <div>
                                    <input type="checkbox" class="minimal form-control icheck" id="fbl_qty_gabungan" name="fbl_qty_gabungan"> &nbsp;
                                    <label for="fbl_qty_gabungan" class=""> <= lang("combined qty of item terms")?> </label>
                                </div>
                            </div>-->
                            <div class="checkbox col-sm-2">
                                <label class="checkbox-inline"><input id="fbl_qty_gabungan" name='fbl_qty_gabungan' type="checkbox" value="1"><?=lang("(Mix item terms)")?></label>
                            </div>				
                        </div>
                        <!-- end box body -->
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#promo_item_details" data-toggle="tab" aria-expanded="true"><?= lang("Promo Terms") ?></a></li>
                                <li class="promo_customer_details" id="tab-doc"><a href="#promo_customer_details" data-toggle="tab" aria-expanded="false"><?= lang("Participants") ?></a></li>
                                <!--<li class="promo_discount_details" id="tab-doc"><a href="#promo_discount_details" data-toggle="tab" aria-expanded="false"><= lang("Discount Items") ?></a></li>-->
                                <li class="promo_customer_areas" id="tab-doc"><a href="#promo_customer_areas" data-toggle="tab" aria-expanded="false"><?= lang("Participants By Area") ?></a></li>
                                <li class="promo_customer_restric" id="tab-doc"><a href="#promo_customer_restric" data-toggle="tab" aria-expanded="false"><?= lang("Exclude Participants") ?></a></li>
                                <li class="free_item_details" id="tab-doc"><a href="#free_item_details" data-toggle="tab" aria-expanded="false"><?= lang("Free Items") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="promo_item_details">
                                    <form class="form-horizontal edit-mode ">	
                                        <div class="form-group">
                                            <div class="col-md-12">
                                            <button id="btn-add-item-details" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Item") ?></button>
                                            </div>						
                                        
                                        </div>
                                    </form>
                                    <table id="tbl_item_details" class="table table-bordered table-hover" style="width:100%;"></table>
                                </div>
                                <div class="tab-pane" id="promo_customer_details">
                                    <button id="btn-add-customer-promo" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Customer") ?></button>
                                    <div>
                                        <table id="tbl_customer_promo" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <!--<div class="tab-pane" id="promo_discount_details">
                                    <button id="btn-add-discount-promo" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Disc Item") ?></button>
                                    <div>
                                        <table id="tbl_discount_promo" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>-->
                                <div class="tab-pane" id="promo_customer_areas">
                                    <button id="btn-add-customer-area" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Area") ?></button>
                                    <div>
                                        <table id="tbl_customer_area" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="promo_customer_restric">
                                    <button id="btn-add-customer-restric" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Exclude Customer") ?></button>
                                    <div>
                                        <table id="tbl_customer_restric" class="table table-bordered table-hover" style="width:100%;"></table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="free_item_details">
                                    <button id="btn-add-free-item" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Free Item") ?></button>
                                    <div>
                                        <table id="tbl_free_items" class="table table-bordered table-hover" style="width:100%;"></table>
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

<?php echo $mdlPrint ?>

<div id="mdlItemDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Item terms") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="fst_item_type" class="col-sm-3 control-label"><?= lang("Type") ?></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="fst_item_type" name="fst_item_type">
                                                <option value="0">-- <?=lang("select")?> --</option>
                                                <option value="ITEM"><?= lang("ITEM") ?></option>
                                                <option value="SUB GROUP"><?= lang("SUB GROUP") ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fin_item_id" class="col-md-3 control-label"><?= lang("Item terms") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="fin_item_id" style="width:100%"></select>
                                            <span id="fin_item_id_err" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group detail-terms">
                                        <label for="fst_unit" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                                        <div class="col-md-9">
                                            <select id="fst_unit" class="form-control" name="fst_unit" style="width:100%"></select>
                                            <span id="fst_unit_err" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group detail-terms">
                                        <label for="fdb_qty" class="col-md-3 control-label"><?=lang("Qty")?></label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control text-right numeric" id="fdb_qty" value="0">
                                            <div id="fdb_qty_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-item" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
                                    <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    echo $mdlItemGroup;
    ?>

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

            tblItemDetails = $("#tbl_item_details").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [/*{
                        "title": "<= lang("ID ") ?>",
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
                        "title": "<?= lang("Item terms ") ?>",
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
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Qty ") ?>",
                        "width": "5%",
                        data: "fdb_qty",
                        visible: false,
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

            $("#fst_item_type").change(function(event){
                event.preventDefault();
                //$('#fin_item_id').val(null).trigger('change');
                $('#fin_item_id').empty();
                type = $("#fst_item_type").val();
                if (type =="ITEM"){
                    select_itemDetail();
                }else{
                    select_itemGroup();
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
                }).off("select2:open");
            }

            function select_itemGroup(){
                $("#fin_item_id").select2({
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
                        $("#fin_item_id").empty();
                        var newOption = new Option(node.text,node.id, false, false);
                        $('#fin_item_id').append(newOption).trigger('change');
                        $("#fin_item_id").trigger({
                            type: 'select2:select',
                            params: {
                                data: {
                                    id: node.id,
                                    text: node.text,
                                }
                            }
                        })
                    });
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

                var unit = null;
                var qty = 0;

                if ($("#fst_promo_type").val() == "PERIODE"){                
                    unit = $("#fst_unit").val();
                    qty = $("#fdb_qty").val();
                }

                t.row.add({
                    fin_id: 0,
                    fin_promo_id: 0,
                    fst_item_type: $("#fst_item_type").val(),
                    fin_item_id: selected_itempromo.id,
                    fst_item_name: selected_itempromo.text,
                    fst_unit: $("#fst_unit").val(),
                    fdb_qty: $("#fdb_qty").val(),
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<div id="mdlCustomerPromo" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Participants") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="fst_participant_type" class="col-sm-3 control-label"><?= lang("Type") ?></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="fst_participant_type" name="fst_participant_type">
                                                <option value="0">-- <?=lang("select")?> --</option>
                                                <option value="RELATION"><?= lang("RELATION") ?></option>
                                                <!--<option value="MEMBER GROUP"><?= lang("MEMBER GROUP") ?></option>-->
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
                    
                                <div class="modal-footer">
                                    <button id="btn-add-participants-promo" type="button" class="btn btn-primary btn-sm text-center" style="width:15%">Add</button>
                                    <button type="button" class="btn btn-default btn-sm text-center" data-dismiss="modal" style="width:15%">Close</button>
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
                        "title": "<?= lang("Participant ID") ?>",
                        "width": "5%",
                        data: "fin_customer_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Participant Name ") ?>",
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
                /*}else {
                    select_membergroup();*/
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
    <div class="modal-dialog" style="display:table;width:40%;min-width:250px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add discount item") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
					<div class="col-md-12" >
						<div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
							<fieldset style="padding:10px">
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
                                        <div class="col-md-9">
                                            <select id="select-unit-disc" class="form-control" name="fst_unit" style="width:100%"></select>
                                            <span id="fst_unit_err" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fin_qty" class="col-md-3 control-label"><?=lang("Qty")?></label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control text-right numeric" id="fin_qty" value="0">
                                            <div id="fin_qty_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdc_disc_persen" class="col-md-3 control-label"><?=lang("Disc %")?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control text-right numeric" id="fdc_disc_persen" value="0">
                                            <div id="fdc_disc_persen_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdc_disc_value" class="col-md-3 control-label"><?=lang("Disc Amt")?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control text-right money" id="fdc_disc_value" value="0">
                                            <div id="fdc_disc_value_err" class="text-danger"></div>
                                        </div>
                                    </div>

                                </form>
                           
                                <div class="modal-footer">
                                    <button id="btn-add-item-disc" type="button" class="btn btn-primary btn-sm text-center" style="width:15%">Add</button>
                                    <button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal">Close</button>
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
                        "title": "<?= lang("Disc Amount") ?>",
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

            // On Change disc % and disc value
            $("#fdc_disc_persen").change(function(){
                $("#fdc_disc_value").val(0);
            });

            $("#fdc_disc_value").change(function(){
                $("#fdc_disc_persen").val(0);
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

<!--- // PARTISIPAN BY AREA CUSTOMER \\ ---------------------------------------------------------------------------------------------------------------->

<div id="mdlAreaPromo" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Participants By Customer Area") ?></h4>
            </div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
								<form  class="form-horizontal">

                                    <div class="form-group">
                                        <label for="select-country" class="col-md-3 control-label"><?=lang("Country")?></label>
                                        <div class="col-md-9">
                                            <select id="select-country" class="form-control" name="fin_country_id">
                                                <option value="0">-- <?=lang("select")?> --</option>
                                            </select>
                                            <div id="fst_country_name_err" class="text-danger"></div>
                                        </div>
                                    </div>
								
									<div class="form-group">
										<label for="provincePromo" class="col-md-3 control-label"><?=lang("Province")?></label>
										<div class="col-md-9">
											<select id="provincePromo" class="form-control" name="fst_kode">
												<option value="0">-- <?=lang("select")?> --</option>
											</select>
											<div id="fst_nama_err" class="text-danger"></div>
										</div>
                                    </div>
                                    <div class="form-group">
										<label for="districtPromo" class="col-md-3 control-label"><?=lang("District")?></label>
										<div class="col-md-9">
											<select id="districtPromo" class="form-control" name="fst_kode">
												<option value="0">-- <?=lang("select")?> --</option>
											</select>
											<div id="fst_nama_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="subdistrictPromo" class="col-md-3 control-label"><?=lang("Sub District")?></label>
										<div class="col-md-9">
											<select id="subdistrictPromo" class="form-control" name="fst_kode">
												<option value="0">-- <?=lang("select")?> --</option>
											</select>
											<div id="fst_nama_err" class="text-danger"></div>
										</div>
                                    </div>
                                    <div class="form-group">
										<label for="villagePromo" class="col-md-3 control-label"><?=lang("Village")?></label>
										<div class="col-md-9">
											<select id="villagePromo" class="form-control" name="fst_kode">
												<option value="0">-- <?=lang("select")?> --</option>
											</select>
											<div id="fst_nama_err" class="text-danger"></div>
										</div>
									</div>
								</form>

								<div class="modal-footer" style="width:100%;padding:10px" class="text-center">
									<button id="btn-add-areaPromo" type="button" class="btn btn-primary btn-sm text-center" style="width:10%" ><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:10%" data-dismiss="modal"><?=lang("Close")?></button>
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

			$("#btn-add-customer-area").click(function(event) {
				event.preventDefault();
				$("#mdlAreaPromo").modal('show');
			});

			$("#tbl_customer_area").DataTable({
				searching: false,
				paging: false,
				info:false,
				columns:[
					{"title": "<?=lang("Rec ID")?>","width": "15%",data: "fin_rec_id",visible: false},
					{"title": "<?=lang("Promo ID")?>","width": "10%",data: "fin_promo_id",visible: false},
					{"title": "<?=lang("Area Code")?>","width": "5%",data: "fst_kode",visible: true},
					{"title": "<?=lang("Area Name")?>","width": "15%",data: "fst_nama",visible: true},
					{"title": "<?= lang("Action")?>","width": "10%",render: function(data, type, row) {
                            action = "<a class='btn-delete-area-promo edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
						"sortable":false,"className":"dt-body-center text-center"}
				],
			});

			$("#tbl_customer_area").on("click", ".btn-delete-area-promo", function(event) {
				event.preventDefault();
				t = $('#tbl_customer_area').DataTable();
				var trRow = $(this).parents('tr');
				t.row(trRow).remove().draw();
			});

            $("#select-country").select2({
                width: '100%',
                ajax: {
                    url: '<?=site_url()?>pr/relation/get_countries',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data){
                        items = [];
                        data = data.data;
                        $.each(data,function(index,value){
                            items.push({
                                "id" : value.fin_country_id,
                                "text" : value.fst_country_name
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

			$("#select-country").change(function(event){
				event.preventDefault();
				$('#provincePromo').val(null).trigger('change');
				$("#provincePromo").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_dataProvince/'+ $("#select-country").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			$("#provincePromo").change(function(event){
				event.preventDefault();
				$('#districtPromo').val(null).trigger('change');
				$("#districtPromo").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_district/'+ $("#provincePromo").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			$("#districtPromo").change(function(event){
				event.preventDefault();
				$('#subdistrictPromo').val(null).trigger('change');
				$("#subdistrictPromo").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_subdistrict/'+ $("#districtPromo").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			$("#subdistrictPromo").change(function(event){
				event.preventDefault();
				$('#villagePromo').val(null).trigger('change');
				$("#villagePromo").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_village/'+ $("#subdistrictPromo").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			var selected_areaCode;
			$('#provincePromo').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$('#districtPromo').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$('#subdistrictPromo').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$('#villagePromo').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$("#btn-add-areaPromo").click(function(event) {
				event.preventDefault();
				t = $('#tbl_customer_area').DataTable();
				t.row.add({
					fin_rec_id: 0,
					fin_promo_id: 0,
					//fst_area_code: selected_areaCode.id,
					fst_kode: selected_areaCode.id,
					fst_nama: selected_areaCode.text,
					action:action
				}).draw(false);
			});
		});	
	</script>
</div>

<!--- // EXCLUDE PARTISIPAN CUSTOMER \\ ---------------------------------------------------------------------------------------------------------------->
<div id="mdlCustomerRestric" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Exclude Participants") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="fin_customer_id_restric" class="col-md-3 control-label"><?= lang("Customer") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="fin_customer_id_restric" style="width:100%"></select>
                                            <span id="fin_customer_id_restric_err" class="text-danger"></span>
                                        </div>
                                    </div>
                                </form>
                    
                                <div class="modal-footer">
                                    <button id="btn-add-participants-restric" type="button" class="btn btn-primary btn-sm text-center" style="width:15%">Add</button>
                                    <button type="button" class="btn btn-default btn-sm text-center" data-dismiss="modal" style="width:15%">Close</button>
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
            $("#btn-add-customer-restric").click(function(event) {
                event.preventDefault();
                $("#mdlCustomerRestric").modal('show');
            });
            $("#tbl_customer_restric").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_rec_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Promo ID ") ?>",
                        "width": "5%",
                        data: "fin_promo_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Participant ID") ?>",
                        "width": "5%",
                        data: "fin_customer_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Exclude Participant Name ") ?>",
                        "width": "40%",
                        data: "customer_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-customer-restric edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_customer_restric").on("click", ".btn-delete-customer-restric", function(event) {
                event.preventDefault();
                t = $("#tbl_customer_restric").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });

            $("#fin_customer_id_restric").select2({
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
            
            var selected_participants_restric;
            $('#fin_customer_id_restric').on('select2:select', function(e) {
                console.log(selected_participants_restric);
                var data = e.params.data;
                selected_participants_restric = data;
            });
            $("#btn-add-participants-restric").click(function(event) {
                event.preventDefault();
                t = $('#tbl_customer_restric').DataTable();
                addRow = true;
                t.row.add({
                    fin_rec_id: 0,
                    fin_promo_id: 0,
                    fin_customer_id: selected_participants_restric.id,
                    customer_name: selected_participants_restric.text,
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<!--- // PRIZE / FREE ITEMS \\ ---------------------------------------------------------------------------------------------------------------->
<div id="mdlFreeItems" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Free Item") ?></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="free_item_id" class="col-md-3 control-label"><?= lang("Free Item") ?></label>
                                        <div class="col-md-9">
                                            <select class="select2 form-control" id="free_item_id" style="width:100%"></select>
                                            <span id="free_item_id_err" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fst_unit_free" class="col-md-3 control-label"><?= lang("Unit") ?></label>
                                        <div class="col-md-9">
                                            <select id="fst_unit_free" class="form-control" name="fst_unit_free" style="width:100%"></select>
                                            <span id="fst_unit_free_err" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdb_qty_free" class="col-md-3 control-label"><?=lang("Qty")?></label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control text-right numeric" id="fdb_qty_free" value="0">
                                            <div id="fdb_qty_free_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-itemFree" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
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
            $("#btn-add-free-item").click(function(event) {
                event.preventDefault();
                if($("#fst_other_prize").val() != "" || $("#fdc_cashback").val() > 0){
                    if (confirm("Cashback dan Other Item akan dikosongkan, Lanjut ?")) {
                        $("#fdc_cashback").val(0);
                        $("#fst_other_prize").val("");
                        $("#fdc_other_prize_in_value").val(0);
                        $("#mdlFreeItems").modal('show');
                        return;
                    } else {
                        return;
                    }
                }else{
                    $("#mdlFreeItems").modal('show');
                }
				
            });
            $("#tbl_free_items").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_rec_id",
                        visible: false
                    },
                    {
                        "title": "<?= lang("Item ID ") ?>",
                        "width": "10%",
                        data: "fin_item_id",
                        visible: false,
                    },
                    {
                        "title": "<?= lang("Free Item ") ?>",
                        "width": "25%",
                        data: "fst_item_name",
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
                        data: "fdb_qty_free",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-free-item edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_free_items").on("click", ".btn-delete-free-item", function(event) {
                event.preventDefault();
                t = $("#tbl_free_items").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });
			
			$("#free_item_id").select2({
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

            $("#free_item_id").change(function(event) {
                event.preventDefault();
                $("#fst_unit_free").val(null).trigger('change');
				$("#fst_unit_free").select2({
					width: '100%',
					ajax: {
						url: '<?= site_url() ?>master/promotion/get_data_unitTerms/'+$("#free_item_id").val(),
						dataType: 'json',
						delay: 250,
						processResults: function(data) {
							data2 = [];
                            data = data.data;
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
            });
            $('#free_item_id').on('select2:select', function(e) {
                selected_freeitem = $('#free_item_id').select2('data')[0];
                console.log(selected_freeitem);
            });
            $('#fst_unit_free').on('select2:select', function(e) {
                selected_unitFree = $('#fst_unit_free').select2('data')[0];
                console.log(selected_unitFree);
            });
			
            $("#btn-add-itemFree").click(function(event) {
                event.preventDefault();
                t = $('#tbl_free_items').DataTable();
                addRow = true;
                var freeItem = $("#free_item_id").val();
                if (freeItem == null || freeItem == "") {
                    $("#free_item_id_err").html("Please select item");
                    $("#free_item_id_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#free_item_id_err").hide();
                }
                var unitFree = $("#fst_unit_free").val();              
                if (unitFree == null || unitFree == "") {
                    $("#fst_unit_free_err").html("Please select unit");
                    $("#fst_unit_free_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#fst_unit_free_err").hide();
                }

                t.row.add({
                    fin_rec_id: 0,
                    fin_promo_id: 0,
                    fin_item_id: selected_freeitem.id,
                    fst_item_name: selected_freeitem.text,
                    fst_unit: selected_unitFree.text,
                    fdb_qty_free: $("#fdb_qty_free").val(),
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<script type="text/javascript" info="defined">
    var tblItemDetails;
</script>

<script type="text/javascript" info="event">
    $(function(){
        $("#fst_promo_type").change(function(e){
            e.preventDefault();
            //alert($("#fst_promo_type").val());

            var columnUnit = tblItemDetails.column(3);
            var columnQty = tblItemDetails.column(4);

            columnUnit.visible(false);
            columnQty.visible(false);
            $(".detail-terms").hide();

            if ($("#fst_promo_type").val() == "PERIODE"){                            
                columnUnit.visible(true);
                columnQty.visible(true);
                $(".detail-terms").show();
            }


        })
    });

</script>


<script type="text/javascript">
    $(function() {
        branchList = [];
        <?php foreach($arrBranch as $branch){ ?>
            branchList.push({
                "id":"<?= $branch->fin_branch_id ?>",
                "text":"<?= $branch->fst_branch_name ?>"
            });  
        <?php } ?>

        <?php if ($mode == "EDIT" || $mode == "COPY") { ?>
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
            //alert(detail);
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
            // save Participants By Area Customer
            participantsByArea = new Array();
            b = $('#tbl_customer_area').DataTable();
            datas = b.data();
            $.each(datas, function(i, v) {
                participantsByArea.push(v);
            });
            data.push({
                name: "participantsByArea",
                value: JSON.stringify(participantsByArea)
            });
            // save Participants customer restric / exclude
            detailParticipantsRestric = new Array();
            b = $('#tbl_customer_restric').DataTable();
            datas = b.data();
            $.each(datas, function(i, v) {
                detailParticipantsRestric.push(v);
            });
            data.push({
                name: "detailParticipantsRestric",
                value: JSON.stringify(detailParticipantsRestric)
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
            // save Free Items
            detailfreeItem = new Array();
            t = $('#tbl_free_items').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detailfreeItem.push(v);
            });
            data.push({
                name: "detailfreeItem",
                value: JSON.stringify(detailfreeItem)
            });
            mode = $("#frm-mode").val();
            if (mode != "EDIT") {
                url = "<?= site_url() ?>master/promotion/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/promotion/ajx_edit_save";
            }
            console.log(data);

            App.blockUIOnAjaxRequest("Please wait while saving data.....");
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
        /*$("#select-promo_item").select2({
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
                    url: '<= site_url() ?>master/promotion/get_data_unitPromo/' + $("#select-promo_item").val(),
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
        });*/

        $("#fst_list_branch_id").select2({
            width: '100%',
            data: branchList
        });

        // On Change Cashback
        var cashback = 0;
        $("#fdc_cashback").change(function(){
            //alert(cashback);
            if(cashback < $("#fdc_cashback").val()){
                if (confirm("Free Other Item dan Tabel Free Items akan dikosongkan, Lanjut ?")) {
                    $("#fst_other_prize").val("");
                    $("#fdc_other_prize_in_value").val(0);
                    t = $("#tbl_free_items").DataTable();
                    t.rows().remove();
                    t.draw(false);
                    return;
                } else {
                    $("#fdc_cashback").val(0);
                    return;
                }
            }
        });
        $("#fst_other_prize").change(function(){
            //alert(cashback);
            if($("#fst_other_prize").val() != ""){
                if (confirm("Cashback dan Tabel Free Items akan dikosongkan, Lanjut ?")) {
                    $("#fdc_cashback").val(0);
                    t = $("#tbl_free_items").DataTable();
                    t.rows().remove();
                    t.draw(false);
                    return;
                } else {
                    $("#fst_other_prize").val("");
                    $("#fdc_other_prize_in_value").val(0);
                    return;
                }
            }
        });

        $("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/promotion/add")
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
				url:"<?= site_url() ?>master/promotion/delete/" + $("#fin_promo_id").val(),
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
									window.location.href = "<?= site_url() ?>master/promotion/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_promo_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_item_name').prop('readonly', true);
				}
			});
		});

        $("#btnPrint").click(function(e){
            //e.preventDefault();
			//window.open("<?= site_url() ?>master/promotion/form_promotion_pdf/" + $("#fin_promo_id").val());
            frameVoucher.print("<?=site_url()?>master/promotion/print_voucher/" + $("#fin_promo_id").val());
        });

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/promotion/lizt");
		});
    });


    function init_form(fin_promo_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/promotion/fetch_data/" + fin_promo_id;
        //alert(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
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

                if (resp.mspromo.fst_list_branch_id != null){
                    var fst_list_branch_id = resp.mspromo.fst_list_branch_id.split(",");
                    $("#fst_list_branch_id").val(fst_list_branch_id).trigger('change');
                }

                $("#fdt_start").datepicker('update', dateFormat(resp.mspromo.fdt_start));
                $("#fdt_end").datepicker('update', dateFormat(resp.mspromo.fdt_end));
                // menampilkan data di select2
                var newOption = new Option(resp.mspromo.fst_item_name, resp.mspromo.fin_promo_item_id, true, true);
                // Append it to the select
                $('#select-promo_item').append(newOption).trigger('change');
                var newOption = new Option(resp.mspromo.fst_promo_unit, resp.mspromo.fst_promo_unit, true, true);
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

                //populate Promo Participants By Area
                $.each(resp.promoparticipantsarea, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_customer_area').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_promo_id: val.fin_promo_id,
                        fst_kode: val.fst_kode_area,
						fst_nama: val.fst_province_name,
						fst_nama: val.fst_district_name,
						fst_nama: val.fst_subdistrict_name,
						fst_nama: val.fst_village_name,
                        action: action
                    }).draw(false);
                })

                //populate Promo Participants Restric/Exclude
                $.each(resp.promoParticipantsRestric, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_customer_restric').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_promo_id: val.fin_promo_id,
                        fin_customer_id: val.fin_customer_id,
                        customer_name: val.ParticipantRestric_Name,
                        action: action
                    }).draw(false);
                })

                //populate prize free items
                $.each(resp.freeItems, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_free_items').DataTable();
                    t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_promo_id: val.fin_promo_id,
                        fin_item_id: val.fin_item_id,
                        fst_item_name: val.FreeItem,
                        fdb_qty_free: val.fdb_qty,
                        fst_unit: val.fst_unit,
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

                $("#fst_promo_type").trigger("change");
                
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
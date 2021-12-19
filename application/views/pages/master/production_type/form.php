<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">

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
    <h1><?= lang("RM-OUT Type (Non Produksi)") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("RM-OUT Type") ?></a></li>
        <li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
                <h3 class="box-title title"><?=$title?></h3>
                <div class="btn-group btn-group-sm  pull-right">					
                    <a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    <a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                    <a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    <a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
                </div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="<?= site_url() ?>master/production_type/add" method="POST" enctype="multipart/form-data">
                <div class="box-body">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" class="form-control" id="fin_rec_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_rec_id" value="<?=$fin_rec_id?>" readonly>

                    <div class="form-group">
                        <label for="fst_production_type" class="col-sm-2 control-label"><?=lang("Production Type")?> #</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="fst_production_type"  name="fst_production_type"  />
                            <div id="fst_production_type_err" class="text-danger"></div>
                        </div>
                    </div>                    
                </div>
                <!-- end box body -->

                <div class="box-footer text-right"></div>
                <!-- end box-footer -->
            </form>
        </div>
        
        
        
    </div>
</section>

<script type="text/javascript">
    var mode = "<?=$mode?>";
    $(function(){
        $("#btnSubmitAjax").click(function(event){
            event.preventDefault();
            data = new FormData($("#frmHeader")[0]);

            if (mode == "ADD"){
                url = "<?= site_url() ?>master/production_type/ajx_add_save";
            }else{
                url = "<?= site_url() ?>master/production_type/ajx_edit_save";
            }

            App.blockUIOnAjaxRequest("Please wait while saving data.....");
            $.ajax({
                type: "POST",               
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function (resp){
                    if (resp.message != ""){
                        alert(resp.message);
                        /*
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons : {
                                OK : function(){
                                    if(resp.status == "SUCCESS"){
                                        $("#btnNew").trigger("click");
                                        return;
                                    }
                                },
                            }
                        });
                        */
                    }

                    if (resp.status == "VALIDATION_FORM_FAILED"){
                        //Show Error
                        errors = resp.data;
                        for (key in errors){
                            $("#"+key+"_err").html(errors[key]);
                        }
                    }else if(resp.status == "SUCCESS"){
                        data = resp.data;
                        $("#btnNew").trigger("click");
                        //$("#fin_rec_id").val(data.insert_id);

                        
                    }
                },
                error: function (e) {
                    $("#result").text(E.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#btnNew").click(function(e){
            e.preventDefault();
            window.location.replace("<?= site_url() ?>master/production_type/add")
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
                url:"<?= site_url() ?>master/project/delete/" + $("#fin_project_id").val(),
            }).done(function(resp){
                //consoleLog(resp);
                $.unblockUI();
                if (resp.message != ""){
                    $.alert({
                        title: 'Message',
                        content: resp.message,
                        buttons : {
                            OK : function(){
                                if (resp.status == "SUCCESS"){
                                    window.location.href = "<?= site_url() ?>master/project/lizt";
                                    return;
                                }
                            },
                        }
                    });
                }

                if(resp.status == "SUCCESS"){
                    data = resp.data;
                    $("#fin_project_id").val(data.insert_id);
                    
                    //Clear all previous error
                    $(".text-danger").html("");
                    //Change to Edit mode
                    $("#frm-mode").val("EDIT"); //ADD|EDIT
                    $('#fst_project_name').prop('readonly', true);
                }
            });
        });

        $("#btnList").click(function(e){
            e.preventDefault();
            window.location.replace(" <?= site_url() ?>master/production_type");
        });

        init_form();
    });

    function init_form(){
        //alert("Init Form");
        if (mode == "ADD"){
            return;
        }

        var url = "<?= site_url() ?>master/production_type/fetch_data/" + $("#fin_rec_id").val();
        $.ajax({
            typr: "GET",
            url: url,
            success: function (resp){
                if (resp.message != ""){
                    alert(resp.message);
                }

                if (resp.status == "SUCCESS"){
                    var data = resp.data;
                    $("#fin_rec_id").val(data.fin_rec_id);
                    $("#fst_production_type").val(data.fst_production_type);
                }
            },
            error: function(e){
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
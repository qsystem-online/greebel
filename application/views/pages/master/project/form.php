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
    <h1><?= lang("Projects") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Projects") ?></a></li>
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
            <form id="frmProjects" class="form-horizontal" action="<?= site_url() ?>master/project/add" method="POST" enctype="multipart/form-data">
                <div class="box-body">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                    <div class="form-group">
                        <label for="fin_project_id" class="col-sm-2 control-label"><?=lang("Project ID")?> #</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="fin_project_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_project_id" value="<?=$fin_project_id?>" readonly>
                            <div id="fin_project_id_err" class="text-danger"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fst_project_name" class="col-sm-2 control-label"><?=lang("Project Name")?> :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="fst_project_name" placeholder="<?=lang("Project Name")?>" name="fst_project_name">
                            <div id="fst_project_name_err" class="text-danger"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fdt_project_start" class="col-sm-2 control-label"><?=lang("Start Date")?> :</label>
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right datepicker" autocomplete="off" id="fdt_project_start" name="fdt_project_start" />
                            </div>
                            <div id="fdt_project_start_err" class="text-danger"></div>
                            <!-- /.input group -->
                        </div>

                        <label for="fdt_project_end" class="col-sm-2 control-label"><?=lang("End Date")?> :</label>
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right datepicker" autocomplete="off" id="fdt_project_end" name="fdt_project_end" />
                            </div>
                            <div id="fdt_project_end_err" class="text-danger"></div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fst_memo" class="col-sm-2 control-label"><?=lang("Memo")?> :</label>
                        <div class="col-sm-10">
                            <textarea rows="4" style="width:100%" class="form-control" id="fst_memo" placeholder="<?=lang("Memo")?>" name="fst_memo"></textarea>
                        </div>
                    </div>
                    <!-- end box body -->
                    <div class="box-footer text-right">
                    </div>
                    <!-- end box-footer -->
                </div>
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function(){
        <?php if($mode == "EDIT"){?>
            init_form($("#fin_project_id").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event){
            event.preventDefault();
            data = new FormData($("#frmProjects")[0]);

            mode = $("#frm-mode").val();
            if (mode == "ADD"){
                url = "<?= site_url() ?>master/project/ajx_add_save";
            }else{
                url = "<?= site_url() ?>master/project/ajx_edit_save";
            }

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function (resp){
                    if (resp.message != ""){
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons : {
                                OK : function(){
                                    if(resp.status == "SUCCESS"){
                                        window.location.href = "<?= site_url() ?>master/project/";
                                        return;
                                    }
                                },
                            }
                        });
                    }

                    if (resp.status == "VALIDATION_FORM_FAILED"){
                        //Show Error
                        errors = resp.data;
                        for (key in errors){
                            $("#"+key+"_err").html(errors[key]);
                        }
                    }else if(resp.status == "SUCCESS"){
                        data = resp.data;
                        $("#fin_project_id").val(data.insert_id);

                        //Clear to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT

                        $('#fst_project_name').prop('readonly', true);
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
            window.location.replace("<?= site_url() ?>master/project/add")
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
            window.location.replace(" <?= site_url() ?>master/project/lizt");
        });
    });

    function init_form(fin_project_id){
        //alert("Init Form");
        var url = "<?= site_url() ?>master/project/fetch_data/" + fin_project_id;
        $.ajax({
            typr: "GET",
            url: url,
            success: function (resp){
                console.log(resp.ms_projects);

                $.each(resp.ms_projects, function(name, val){
                    var $el = $('[name="'+ name +'"]'),
                        type = $el.attr('type');
                    switch(type){
                        case 'checkbox':
                            $el.filter('checked', 'checked');
                            break;
                        case 'radio':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        default:
                            $el.val(val);
                            console.log(val);
                    }
                });

                $("#fdt_project_start").datepicker('update', dateFormat(resp.ms_projects.fdt_project_start));
                $("#fdt_project_end").datepicker('update', dateFormat(resp.ms_projects.fdt_project_end));
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
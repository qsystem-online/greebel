<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">
<style>
	.content-header .fa {
	transition: .3s transform ease-in-out;
	}
	.content-header .collapsed .fa {
	transform: rotate(90deg);
	}
    div#title .breadcrumb {
    float: right;
    background: transparent;
    margin-top: 0;
    margin-bottom: 0;
    font-size: 12px;
    padding: 7px 5px;
    position: absolute;
    top: 15px;
    right: 10px;
    border-radius: 2px;
    }
    .questions {
    display: none;
    background: #0d7453;
    position: absolute;
    z-index: 100;
    color: #EEE;
    margin-top: 20px;
    width: 360px;
    min-height: 112px;
    }
    .question {
        position: absolute;
        width: 200px;
        padding: 25px 10px 0 20px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }
    .question p {
        margin: 0;
        padding: 0;
    }
    .close {
        font-size:22px;
        cursor: pointer;
        position: absolute;
        right:10px;
        top:5px;
    }    
</style>
<section class="content-header">
    <a id="toggle-window" data-toggle="collapse" href="#filter-content" class="pull-right" aria-expanded="true" aria-controls="collapse-example">
        <i class="fa fa-chevron-down fa-2x"></i>
    </a>        
    <div class="btn-group btn-group">					
        <button id="btnExcel" type="button" disabled class="btn btn-primary" href="#" title="<?=lang("Export ke Excel")?>"><i class="fa fa-file-excel-o fa-2x" aria-hidden="true"></i></button>
        <button id="btnPrint" type="button" disabled class="btn btn-primary" href="#" title="<?=lang("Print")?>"><i class="fa fa-print fa-2x" aria-hidden="true" onclick="printFrame('rpt_iframe')" ></i></button>
        <!-- <button id="btnColumns" type="button" class="btn btn-primary" href="#" title="<?=lang("Select Columns")?>"><i class="fa fa-list-alt fa-2x" aria-hidden="true"></i></button> -->
        <button id="btnProcess" type="button" class="btn btn-primary" href="#" title="<?=lang("Proses")?>"><i class="fa fa-play-circle fa-2x" aria-hidden="true"></i></button>
    </div>
    <div class="questions">
        <div class="close"><i class="fa fa-times"></i>

        </div>
        <div class="question"></div>
    </div>
</section>

<section class="content collapse in" id="filter-content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div id="title" class="box-header with-border">
                    <h3 class="box-title title"><?= $TITLE ?></h3>
                </div>
                <!-- end box header -->

                <!-- form start -->
                {REPORT_FILTER}
                <!-- form end -->
            </div>
        </div>
</section>

<script  type="text/javascript">
    $(document).ready(function() {
        $("a#toggle-window").bind('click', function () {
            if (!$('#filter-content').hasClass('in')) {
                $("#btnProcess").removeAttr("disabled");    
                $("#btnExcel").attr("disabled", "disabled");
                $("#btnPdf").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
            } else {
                $("#btnProcess").attr("disabled", "disabled");
                $("#btnExcel").removeAttr("disabled");
                $("#btnPdf").removeAttr("disabled");
                $("#btnPrint").removeAttr("disabled");
            }
        });        
        if ($("#btnProcess").attr("disabled")) {
            $("#btnExcel").removeAttr("disabled");
            $("#btnPdf").removeAttr("disabled");
            $("#btnPrint").removeAttr("disabled");
        } else {
            $("#btnExcel").attr("disabled", "disabled");
            $("#btnPdf").attr("disabled", "disabled");
            $("#btnPrint").attr("disabled", "disabled");
        }
    });

    function updateIFrame($rptData) {
        console.log($rptData);
        var reportFrame = $("#rpt_iframe").contents().find('body');
        reportFrame.html($rptData);
        $("#btnProcess").attr("disabled", "disabled");
        $("#btnExcel").removeAttr("disabled");
        $("#btnPdf").removeAttr("disabled");
        $("#btnPrint").removeAttr("disabled");
        $('#toggle-window')[0].click();

        $('#btnColumns').click(function () {
            // popup(this, '<p>What trends could potentially drive growth in the U.S.?</p>');});
            showQuestion($(this), '<p>What trends could potentially drive growth in the U.S.?</p>');
        });                   

    }
    function popup(jqBtn, question){
        // mind that the .position does not account for borders etc.
        var btn = $(jqBtn).parents('.buttons'),
            posLeft = btn.position().left + btn.outerWidth(),
            posTop = btn.position().top;

        $('.questions').fadeIn();
        $('.question').html(question);
        $('.questions')
            .appendTo(btn.parent())
            .css('left', posLeft + 'px')
            .css('top', posTop + 'px');    
    }
    function showQuestion(button, question) {
        var offset = button.offset();
        $('.question').html(question);
        $('.questions')
            .fadeIn()
            .css({
            left: button.left,
            top: offset.top + button.innerHeight()

        // $('.questions')
        //     .fadeIn()
        //     .css({
        //     left: Math.min(offset.left, $(window).innerWidth()-$('.questions').outerWidth()),
        //     top: offset.top + button.innerHeight()
    });
    $('.close').click(function (e) {
        e.stopPropagation();
        $(this).parent().hide();
        $('.items').removeClass('no-effect');
    });    
}    
</script>

<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
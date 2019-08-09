<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/jstree/dist/themes/default/style.min.css" />

<section class="content-header">
    <h1><?= lang("Menus") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Menus") ?></a></li>
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
                <div class="box-body">
                    <div id="jstree_demo">
                    </div>
                </div>                
            </div>
        </div>
</section>

<script type="text/javascript">
    $(function () {
        //,icon: "glyphicon glyphicon-file"
        $('#jstree_demo').jstree({
             'core':{
                 'data':[
                    { id: "1",parent:"#",text:"Root 1",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                    { id: "1.1",parent:"1",text:"Root 1.1",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                    { id: "2",parent:"#",text:"Root 2",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                    { id: "3",parent:"#",text:"Root 3",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                 ]
             }
        }); 


        $("#jstree_demo").on('changed.jstree', function (e, data) {
            consoleLog(data);
        
        });
    });
</script>

<!-- jstree -->
<script src="<?= base_url() ?>bower_components/jstree/dist/jstree.min.js"></script>
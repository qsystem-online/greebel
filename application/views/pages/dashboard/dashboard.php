<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<section class="content-header">
    <h1><?=lang("Dashboard")?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Menus") ?></a></li>
		<li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <!--<h3 class="box-title title"><?=$title?></h3>-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{ttlApproved}</h3>
                    <p><?=lang("Approved")?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkbox-outline"></i>
                </div>
                <a href="<?= site_url() ?>tr/approval" class="small-box-footer">More info <i class="fa fa-check" aria-hidden="true"></i></a>
            </div>
        </div>

        <!--<div class="col-lg-3 col-xs-6">
            // small box 
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{ttlNeedApproval}</h3>
                    <p><?=lang("Need Approval")?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-compose"></i>
                </div>
                <a href="<?= site_url() ?>tr/approval" class="small-box-footer">More info <i class="fa fa-pencil" aria-hidden="true"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            // small box
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{ttlChangeAfterApproved}</h3>
                    <p><?= lang("Version changed")?></p>
                </div>
                <div class="icon">			  
                    <i class="fa fa-recycle"></i>
                </div>
                <a href="<?= site_url() ?>tr/approval" class="small-box-footer">More info <i class="fa fa-recycle"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            // small box 
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{ttlVoidAuthorize}</h3>
                    <p><?=lang("Void Authorize")?></p>
                </div>
                <div class="icon">
                    <i class="fa fa-ban"></i>
                </div>
                <a href="<?= site_url() ?>tr/approval" class="small-box-footer">More info <i class="fa fa-ban"></i></a>
            </div>
        </div>-->
    </div>
</section>
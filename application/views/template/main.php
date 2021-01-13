<!DOCTYPE html>
<html>
	<head>		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>AdminLTE 2 | Dashboard</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- JQUERY-UI -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/jquery-ui/jquery-ui.min.css">
		
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/Ionicons/css/ionicons.min.css">
		
		<!-- Morris chart -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/morris.js/morris.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/jvectormap/jquery-jvectormap.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!-- DateTime Picker -->
		<link rel="stylesheet" href="<?=base_url()?>plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">		
		<!-- Daterange picker -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/bootstrap-daterangepicker/daterangepicker.css">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="<?=base_url()?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

		<!-- Jquery-Confirm - Notification and allert box -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/jquery-confirm/dist/jquery-confirm.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- Google Font 
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
		-->
		
		<!-- jQuery 3 -->
		<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>				
		<!-- CONFIG JS -->
		<script src="<?=base_url()?>assets/system/js/config.js"></script>
		<!-- APP JS -->
		<script src="<?=base_url()?>assets/system/js/app.js"></script>

		<!-- Theme style -->
		<link rel="stylesheet" href="<?=base_url()?>dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		   folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="<?=base_url()?>dist/css/skins/_all-skins.min.css">
		
	</head>
	<script type="text/javascript">
		var SECURITY_NAME = "<?=$this->security->get_csrf_token_name()?>";
		var SECURITY_VALUE = "<?=$this->security->get_csrf_hash()?>";	
		var SITE_URL = "<?=site_url()?>";
	</script>
	<?php
		$sidebarCollapse = ($this->session->userdata('sidebar_collapse') == 1) ? "sidebar-collapse" : "";
	?>
	<style>
		.dataTable{
			border:1px solid #1aa3ff;
		}
		.dataTable thead tr th{
			/*background-color:#80ccff;height:25px;padding:5px;border:1px solid #1aa3ff;*/
			background-color:#3c8dbc;
			/*height:25px;*/
			padding:5px;
			border:1px solid #000;
			color:#fff;
			font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
			font-weight: 400;
			
		}
		.dataTable tbody tr td{
			padding:5px;
			/*border:1px solid #1aa3ff;*/
		}
		textarea {
			resize: none;
		}

		td.details-control {
			background: url('<?=site_url()?>/assets/system/icons/details_open.png') no-repeat center center;
			cursor: pointer;
		}
		tr.shown td.details-control {
			background: url('<?=site_url()?>/assets/system/icons/details_close.png') no-repeat center center;
		}

	</style>

	<body class="hold-transition skin-blue sidebar-mini <?= $sidebarCollapse?>" style="overflow-x:auto">
		<div class="wrapper" style="overflow-x:auto;min-width:800px">
			<header class="main-header">
				{MAIN_HEADER}
			</header>

			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">				
				{MAIN_SIDEBAR}
			</aside>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
		    	{PAGE_CONTENT}
		    	<script>
					/*
		    		$(function(){
		    			alert(moment('2019-08-02','YYYY-DD-MM').format('<?php //echo DATEPICKER_FORMAT_MOMENT;?>'));

					});
					*/
		    	</script>
			</div>
			<!-- /.content-wrapper -->

			<footer class="main-footer">
				{MAIN_FOOTER}
			</footer>

			{CONTROL_SIDEBAR}
		
		  
		</div>
		<!-- ./wrapper -->

		

		
		
		<script type="text/javascript">
			$(function(){	
				/*
				$(document).ajaxStart(function() {
					$.blockUI({ message: '<h1><=lang("Please wait....")?></h1>'});
				});
				$(document).ajaxStop(function() {
					$.unblockUI();
				});
				*/

				//if session expired redirect to login page
				$(document).ajaxError(function(event, jqxhr, settings, thrownError){
					var resp = jqxhr.responseJSON;
					if(typeof resp == "undefined"){
						return;
					}
					if(resp.hasOwnProperty("status")){						
						if(resp.status == "SESSION_EXPIRED"){
							$.dialog({
								title: 'Error',
								content: 'Session is timeout!',
								onClose: function(){
									window.location.replace("<?= site_url() ?>signout/expired");	
								},
							});						
						}

					}
				});
			});
		</script>



		

		<!-- jQuery UI 1.11.4 -->
		<script src="<?=base_url()?>bower_components/jquery-ui/jquery-ui.min.js"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<script>
		  $.widget.bridge('uibutton', $.ui.button);
		</script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?=base_url()?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Morris.js charts -->
		<script src="<?=base_url()?>bower_components/raphael/raphael.min.js"></script>
		<script src="<?=base_url()?>bower_components/morris.js/morris.min.js"></script>
		<!-- Sparkline -->
		<script src="<?=base_url()?>bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
		<!-- jvectormap -->
		<script src="<?=base_url()?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="<?=base_url()?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
		<!-- jQuery Knob Chart -->
		<script src="<?=base_url()?>bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
		<!-- daterangepicker -->
		<script src="<?=base_url()?>bower_components/moment/min/moment.min.js"></script>
		<script src="<?=base_url()?>bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- datepicker -->
		<script src="<?=base_url()?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!-- datetimepicker -->
		<script src="<?=base_url()?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
		<!-- Bootstrap WYSIHTML5 -->
		<script src="<?=base_url()?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
		<!-- Jquery-Confirm -->
		<script src="<?=base_url()?>bower_components/jquery-confirm/dist/jquery-confirm.min.js"></script>
		<script src="<?=base_url()?>bower_components/bootstrap-confirmation2/bootstrap-confirmation.min.js"></script>
		<!-- Inputmask -->
		<script src="<?=base_url()?>bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>		
		<!-- Slimscroll -->
		<script src="<?=base_url()?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="<?=base_url()?>bower_components/fastclick/lib/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>dist/js/adminlte.min.js"></script>	
		<!-- Numeral -->
		<script src="<?=base_url()?>bower_components/numeral/numeral.min.js"></script>
		<!-- iCheck 1.0.1 -->
		<script src="<?=base_url()?>plugins/iCheck/icheck.min.js"></script>
		<!-- maskmoney -->
		<script src="<?=base_url()?>bower_components/maskmoney/dist/jquery.maskMoney.min.js"></script>
		<!-- Deafult App -->
		<script src="<?=base_url()?>dist/js/app.js"></script>	
		<!-- jquery.hotkeys -->
		<script src="<?=base_url()?>bower_components/jquery.hotkeys/jquery.hotkeys.js"></script>
		<!-- BlockUI -->
		<script src="<?=base_url()?>bower_components/jquery.blockUI.js"></script>		
	</body>
</html>

<!DOCTYPE html>
<html>
	<head>		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{title}</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/font-awesome/css/font-awesome.min.css">		
		<!-- jQuery 3 -->
		<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>				
		<!-- CONFIG JS -->
		<script src="<?=base_url()?>assets/system/js/config.js"></script>
		<!-- APP JS -->
		<script src="<?=base_url()?>assets/system/js/app.js"></script>		

		<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css"/>		
		<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

		<style>			
			@font-face {
				font-family: "PT Mono";
				src: url("<?=site_url()?>assets/fonts/PT_Mono/PTMono-Regular.ttf") format('truetype');
			}	
			body{
				width:8.5in;
				font-size:8pt;
				padding-left:0.2in;
				padding-right:0.2in;
				padding-top:0.2in;
				padding-bottom:0.2in;
				border:1px solid #000;
			}	
			.table-condensed>thead>tr>th{
				font-size:10pt;
				text-transform:uppercase;
			}
			.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
				padding: 0.5px;
				font-weight:normal;
			}	
			h2{
				font-size:12pt;
				text-decoration: underline;
			}	
			.voucher{
				font-family:'PT Mono',Roboto, 'Segoe UI', Tahoma, sans-serif;
			}
			label{
				font-weight:normal;
			}				
			
			

		</style>
	</head>



	<script type="text/javascript">
		var SECURITY_NAME = "<?=$this->security->get_csrf_token_name()?>";
		var SECURITY_VALUE = "<?=$this->security->get_csrf_hash()?>";	
		var SITE_URL = "<?=site_url()?>";
	</script>
	
	

	<body>
		<div class="container" style="width:100%">
			{PAGE_CONTENT}
		</div>
		
		<script type="text/javascript">
			$(function(){
				//window.print();
			});
		</script>		
		<!-- Bootstrap 3.3.7 -->
		<script src="<?=base_url()?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Bootstrap WYSIHTML5 -->
		<script src="<?=base_url()?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
		<!-- Numeral -->
		<script src="<?=base_url()?>bower_components/numeral/numeral.min.js"></script>
		<!-- maskmoney -->
		<script src="<?=base_url()?>bower_components/maskmoney/dist/jquery.maskMoney.min.js"></script>
		<!-- Deafult App -->
		<script src="<?=base_url()?>dist/js/app.js"></script>	
		<!-- BlockUI -->
		<script src="<?=base_url()?>bower_components/jquery.blockUI.js"></script>		
	</body>
</html>

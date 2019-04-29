<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Login</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?=base_url()?>bower_components/Ionicons/css/ionicons.min.css">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			html,body{height:100%;margin:0;padding:0}
			body{
				background-image: url("<?=site_url()?>assets/system/images/login_bg.jpg");
				background-repeat: no-repeat;
  				background-size: cover;
			}

		</style>
		<!-- jQuery 3 -->
		<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>		
	</head>
	<body>
		<table style="width:100%;height: 100%"><tr><td style='vertical-align: middle;'>
		<div class="container-fluid" style="">
			<div id="loginbox"  class="mainbox col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="panel-title">Sign In</div>
						<div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
					</div>     
					<div style="padding-top:30px" class="panel-body">
						<?php if(isset($message)) { ?>
							<div style="display:inline" id="login-alert" class="alert alert-danger col-sm-12"><?= $message ?> </div>
						<?php } ?>

						<form id="loginform" class="form-horizontal" role="form" method="POST">

							<input type="hidden" name= "<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>"/>
							


							<div style="margin-bottom: 25px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">                                        
							</div>
							<div style="margin-bottom: 25px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="login-password" type="password" class="form-control" name="password" placeholder="password">
							</div>
							<div class="input-group">
								<div class="checkbox">
									<label><input id="login-remember" type="checkbox" name="remember" value="1"> Remember me</div>
								</div>
								<div style="margin-top:10px" class="form-group">
									<!-- Button -->
                                    <div class="col-sm-12 controls">
										<input type="submit" id="btn-login" href="#" class="btn btn-info" style="width:100%" value="Login">
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-12 control">
										<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%">
											Don't have an account! 
											<a href="#" onclick="$('#loginbox').hide(); $('#signupbox').show()">Sign Up Here</a>
										</div>
									</div>
								</div>    
						</form>    

					</div>                     
				</div>  
			</div>
		</div>
		</td></tr></table>	
		<!-- jQuery UI 1.11.4 -->
		<script src="<?=base_url()?>bower_components/jquery-ui/jquery-ui.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?=base_url()?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	</body>
</html>

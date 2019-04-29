<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- Sidebar user panel -->
	<div class="user-panel">
		<div class="pull-left image">
			<img src="<?=base_url()?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		</div>
		<div class="pull-left info">
			<p>
				<?php
					$active_user = $this->session->userdata("active_user");
					echo $active_user->fst_fullname;
				?>
			</p>
			<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
		</div>
	</div>
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<?= $this->menus->build_menu(); ?>
	</ul>
</section>
<!-- /.sidebar -->		  
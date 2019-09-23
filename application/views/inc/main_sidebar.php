<?php
defined('BASEPATH') or exit('No direct script access allowed');
$cekAvatar = APPPATH . '../assets/app/users/avatar/avatar_' . $this->aauth->get_user_id() . '.jpg';
if (file_exists($cekAvatar)) {
	$avatar = base_url() . 'assets/app/users/avatar/avatar_' . $this->aauth->get_user_id() . '.jpg';
} else {
	$avatar = base_url() . 'assets/app/users/avatar/default.jpg';
}

?>

<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- Sidebar user panel -->
	<div class="user-panel">
		<div class="pull-left image">
			<img src="<?= $avatar ?>" class="img-circle" alt="User Image">
		</div>
		<div class="pull-left info">
			<?php
			$active_user = $this->session->userdata("active_user");			
			$branchs = $this->msbranches_model->getAllList();
			$disabledSelect = ($active_user->fbl_is_hq == 1) ? "" : "disabled";
			$disabledSelect = ($active_user->fin_level <= getDbConfig("change_branch_level")) ? "" : "disabled";
			?>
			<select id="active_branch_id" style="color:#b9ecde;width:150px;background:#333" <?= $disabledSelect ?>>
				<?php
				//print_r($branchs);
				$activeBranchId = $this->session->userdata("active_branch_id");
				foreach ($branchs as $branch) {
					$isActive = ($branch->fin_branch_id == $activeBranchId) ? "selected" : "";
					echo "<option value=" . $branch->fin_branch_id . " $isActive >" . $branch->fst_branch_name . "</option>";
				}
				?>
			</select>
		</div>
		<div style="clear:both"></div>
	</div>
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<?= $this->menus->build_menu(); ?>
	</ul>
</section>
<!-- /.sidebar -->
<script type="text/javascript">
	$(function() {
		$("#active_branch_id").change(function(event) {
			event.preventDefault();
			window.location = "<?= site_url() ?>user/change_branch/" + $("#active_branch_id").val();
		});
	});
</script>
<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
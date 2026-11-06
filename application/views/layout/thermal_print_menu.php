<?php if (is_superadmin_loggedin() || is_admin_loggedin() || get_permission('collect_fees', 'is_view')): ?>
<li class="<?php if ($main_menu == 'thermal_print') echo 'nav-active'; ?>">
	<a href="<?=base_url('thermal_print/settings')?>">
		<i class="fas fa-print" style="color: #6366f1;"></i><span>Thermal Print (থার্মাল প্রিন্ট)</span>
	</a>
</li>
<?php endif; ?>

<?php if (get_permission('biometric_device', 'is_view') || get_permission('biometric_sync', 'is_view') || get_permission('biometric_mapping', 'is_view') || get_permission('biometric_log', 'is_view') || is_superadmin_loggedin()) { ?>
<li class="nav-parent <?php if ($main_menu == 'biometric_attendance') echo 'nav-expanded nav-active';?>">
	<a>
		<i class="fas fa-fingerprint"></i><span><?=translate('biometric') . " " . translate('attendance')?></span>
	</a>
	<ul class="nav nav-children">
		<li class="<?php if ($sub_page == 'biometric/index') echo 'nav-active';?>">
			<a href="<?=base_url('biometric')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('dashboard')?></span>
			</a>
		</li>
		<?php if (get_permission('biometric_device', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'biometric/devices' || $sub_page == 'biometric/device_edit') echo 'nav-active';?>">
			<a href="<?=base_url('biometric/devices')?>">
				<span><i class="fas fa-caret-right"></i>Devices</span>
			</a>
		</li>
		<?php } ?>
		<?php if (get_permission('biometric_mapping', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'biometric/mapping') echo 'nav-active';?>">
			<a href="<?=base_url('biometric/mapping')?>">
				<span><i class="fas fa-caret-right"></i>User Mapping</span>
			</a>
		</li>
		<?php } ?>
		<?php if (get_permission('biometric_log', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'biometric/logs') echo 'nav-active';?>">
			<a href="<?=base_url('biometric/logs')?>">
				<span><i class="fas fa-caret-right"></i>Punch Logs</span>
			</a>
		</li>
		<?php } ?>
		<?php if (get_permission('biometric_device', 'is_edit') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'biometric/settings') echo 'nav-active';?>">
			<a href="<?=base_url('biometric/settings')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('settings')?></span>
			</a>
		</li>
		<?php } ?>
	</ul>
</li>
<?php } ?>

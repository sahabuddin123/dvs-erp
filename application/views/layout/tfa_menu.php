<?php if (is_superadmin_loggedin() || is_admin_loggedin() || get_permission('two_factor_auth', 'is_view')): ?>
<li class="nav-parent <?php if ($main_menu == 'two_factor_auth') echo 'nav-expanded nav-active'; ?>">
	<a>
		<i class="fas fa-shield-alt"></i><span>Two Factor Auth (2FA)</span>
	</a>
	<ul class="nav nav-children">
		<li class="<?php if ($sub_page == 'two_factor_auth/index') echo 'nav-active'; ?>">
			<a href="<?=base_url('two_factor_auth')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('settings')?> / কনফিগারেশন</span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'two_factor_auth/logs') echo 'nav-active'; ?>">
			<a href="<?=base_url('two_factor_auth/logs')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('activity_logs')?> / লগ রিপোর্ট</span>
			</a>
		</li>
	</ul>
</li>
<?php endif; ?>

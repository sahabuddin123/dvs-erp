<?php if (moduleIsEnabled('two_fa')): ?>
	<li><a href="<?php echo base_url('two_factor_auth/my_security');?>"><i class="fas fa-shield-alt"></i> <?php echo translate('two_factor_authentication');?></a></li>
<?php endif; ?>


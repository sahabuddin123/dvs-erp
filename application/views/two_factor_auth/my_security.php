<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-shield-alt"></i> <?=translate('two_factor_authentication')?> - <?=translate('my_account_security')?></h4>
			</header>
			<?php echo form_open('two_factor_auth/my_security', ['class' => 'form-horizontal form-bordered']); ?>
				<div class="panel-body">
					<div class="text-center" style="margin-bottom: 25px;">
						<div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: rgba(0, 127, 61, 0.1); border-radius: 50%; color: #007F3D; font-size: 38px; margin-bottom: 10px;">
							<i class="fas fa-user-shield"></i>
						</div>
						<h3 style="color: #1e293b; font-weight: 700; margin-top: 5px;"><?=translate('account_security_profile')?></h3>
						<p style="color: #64748b; font-size: 14px;"><?=translate('two_fa_account_desc')?></p>
					</div>

					<div class="alert alert-info">
						<i class="fas fa-info-circle"></i> <strong><?=translate('how_it_works')?></strong> <?=translate('how_it_works_2fa_desc')?>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label"><?=translate('tfa_security_status')?></label>
						<div class="col-md-7">
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="my_2fa_on" name="is_enabled" value="1" <?=(!isset($user_config->is_enabled) || $user_config->is_enabled == 1) ? 'checked' : ''?>>
								<label for="my_2fa_on"><i class="fas fa-shield-alt text-success"></i> <?=translate('keep_enabled')?></label>
							</div>
							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" id="my_2fa_off" name="is_enabled" value="0" <?=(isset($user_config->is_enabled) && $user_config->is_enabled == 0) ? 'checked' : ''?>>
								<label for="my_2fa_off"><i class="fas fa-times-circle text-danger"></i> <?=translate('disable_now')?></label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-4 col-md-7">
							<button type="submit" class="btn btn-default"><i class="fas fa-save"></i> <?=translate('save')?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>


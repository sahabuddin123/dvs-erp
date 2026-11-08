<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-shield-alt text-primary"></i> <?=translate('two_factor_authentication_settings')?>
				</h4>
			</header>
			<?php echo form_open(base_url('two_factor_auth/save_settings'), array('class' => 'form-horizontal form-bordered validate')); ?>
			<div class="panel-body">
				<!-- Two Factor Authentication (Enable / Disable) -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('two_factor_authentication')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$status_val = isset($settings->is_active) ? $settings->is_active : 1;
						?>
						<select name="is_active" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" required>
							<option value="1" <?=($status_val == 1) ? 'selected' : ''?>><?=translate('enable')?></option>
							<option value="0" <?=($status_val == 0) ? 'selected' : ''?>><?=translate('disable')?></option>
						</select>
					</div>
				</div>

				<!-- 2fa Show Remember Browser -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('2fa_show_remember_browser')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$rem_val = isset($settings->remember_browser) ? $settings->remember_browser : 1;
						?>
						<select name="remember_browser" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" required>
							<option value="1" <?=($rem_val == 1) ? 'selected' : ''?>><?=translate('yes')?></option>
							<option value="0" <?=($rem_val == 0) ? 'selected' : ''?>><?=translate('no')?></option>
						</select>
					</div>
				</div>

				<!-- 2fa Cookie Expiry -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('2fa_cookie_expiry')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$expiry_options = [
								'1 Day' => '1 Day',
								'1 Week' => '1 Week',
								'1 Month' => '1 Month',
								'3 Months' => '3 Months',
								'6 Months' => '6 Months',
								'1 Year' => '1 Year'
							];
							$cur_expiry = !empty($settings->cookie_expiry) ? $settings->cookie_expiry : '1 Year';
						?>
						<select name="cookie_expiry" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" required>
							<?php foreach ($expiry_options as $k => $v): ?>
								<option value="<?=$k?>" <?=($cur_expiry == $k) ? 'selected' : ''?>><?=$v?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<!-- Email Instruction -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('email_instruction')?></label>
					<div class="col-md-6">
						<textarea name="email_instruction" class="form-control" rows="3"><?=!empty($settings->email_instruction) ? html_escape($settings->email_instruction) : 'A unique code has been sent to your email address. Please check your inbox for the 2FA verification code.'?></textarea>
					</div>
				</div>

				<!-- App Instruction -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('app_instruction')?></label>
					<div class="col-md-6">
						<textarea name="app_instruction" class="form-control" rows="3"><?=!empty($settings->app_instruction) ? html_escape($settings->app_instruction) : 'Download Google Authenticator App in your mobile device.'?></textarea>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-3 col-md-6">
						<button type="submit" class="btn btn-default">
							<i class="fas fa-save"></i> <?=translate('save')?>
						</button>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

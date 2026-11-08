<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-cogs"></i> <?php echo translate('general_settings'); ?></h4>
			</header>
			<?php echo form_open_multipart('saas/settings_general', array('class' => 'form-horizontal form-bordered validate')); ?>
				<div class="panel-body">
					<!-- SEO & Meta Information -->
					<div class="headers-line mt-none">
						<i class="fas fa-search"></i> SEO & Meta Information
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('seo_title'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="seo_title" value="<?php echo html_escape($settings['seo_title']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('meta_keyword'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="seo_keyword" value="<?php echo html_escape($settings['seo_keyword']); ?>" placeholder="comma separated keywords" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('meta_description'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="seo_description" rows="3"><?php echo html_escape($settings['seo_description']); ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('google_analytics'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="google_analytics" rows="3" placeholder="UA-XXXXX-Y or G-XXXXXXXXXX tracking tag"><?php echo html_escape($settings['google_analytics']); ?></textarea>
						</div>
					</div>

					<!-- Automation & Features -->
					<div class="headers-line">
						<i class="fas fa-sliders-h"></i> Automation & Core Modules
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('automatic_subscription_approval'); ?></label>
						<div class="col-md-6">
							<select name="automatic_approval" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $settings['automatic_approval'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $settings['automatic_approval'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('offline_payments'); ?></label>
						<div class="col-md-6">
							<select name="offline_payments" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $settings['offline_payments'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $settings['offline_payments'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('pwa_enable'); ?></label>
						<div class="col-md-6">
							<select name="pwa_enable" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $settings['pwa_enable'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $settings['pwa_enable'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>

					<!-- Google Recaptcha on School Register -->
					<div class="headers-line">
						<i class="fas fa-shield-alt"></i> <?php echo translate('google_recaptcha'); ?>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Recaptcha Status</label>
						<div class="col-md-6">
							<select name="captcha_status" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $settings['captcha_status'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $settings['captcha_status'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Recaptcha Site Key</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="recaptcha_site_key" value="<?php echo html_escape($settings['recaptcha_site_key']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Recaptcha Secret Key</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="recaptcha_secret_key" value="<?php echo html_escape($settings['recaptcha_secret_key']); ?>" />
						</div>
					</div>

					<!-- Terms & Conditions -->
					<div class="headers-line">
						<i class="fas fa-file-contract"></i> <?php echo translate('terms_and_conditions'); ?>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Terms & Conditions Status</label>
						<div class="col-md-6">
							<select name="terms_status" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $settings['terms_status'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $settings['terms_status'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Checkbox Agreement Text</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="agree_checkbox_text" value="<?php echo html_escape($settings['agree_checkbox_text']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Terms and Conditions Content</label>
						<div class="col-md-6">
							<textarea class="form-control" name="terms_and_conditions" rows="5"><?php echo html_escape($settings['terms_and_conditions']); ?></textarea>
						</div>
					</div>

					<!-- Slider & Overlay Image Feature -->
					<div class="headers-line">
						<i class="fas fa-images"></i> Slider & Overlay Features
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('overlay_image_enable_disable'); ?></label>
						<div class="col-md-6">
							<select name="overly_image_status" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $settings['overly_image_status'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $settings['overly_image_status'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('slider_overlay_image'); ?></label>
						<div class="col-md-6">
							<input type="file" name="overly_image" class="form-control" />
							<?php if (!empty($settings['overly_image'])): ?>
								<small class="text-muted">Current: <?php echo html_escape($settings['overly_image']); ?></small>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Slider Title</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="slider_title" value="<?php echo html_escape($settings['slider_title']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Slider Description</label>
						<div class="col-md-6">
							<textarea class="form-control" name="slider_description" rows="3"><?php echo html_escape($settings['slider_description']); ?></textarea>
						</div>
					</div>

					<!-- Expiration Alerts -->
					<div class="headers-line">
						<i class="fas fa-bell"></i> Expiration Alerts
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Alert Days Before Expiry</label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="expired_alert_days" value="<?php echo html_escape($settings['expired_alert_days']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Expired Alert Message</label>
						<div class="col-md-6">
							<textarea class="form-control" name="expired_alert_message" rows="2"><?php echo html_escape($settings['expired_alert_message']); ?></textarea>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" name="submit" value="save" class="btn btn-default btn-block"><i class="fas fa-check"></i> <?php echo translate('save'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>


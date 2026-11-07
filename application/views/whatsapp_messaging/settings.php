<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fab fa-whatsapp" style="color: #25D366;"></i> <?=translate('whatsapp_settings')?></h4>
			</header>
			<?php echo form_open('whatsapp_messaging/settings', ['class' => 'form-horizontal form-bordered validate']); ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('status')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="wa_status_1" name="is_active" value="1" <?=(isset($settings->is_active) && $settings->is_active == 1) ? 'checked' : ''?>>
								<label for="wa_status_1"><i class="fas fa-check-circle text-success"></i> <?=translate('active')?></label>
							</div>
							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" id="wa_status_0" name="is_active" value="0" <?=(isset($settings->is_active) && $settings->is_active == 0) ? 'checked' : ''?>>
								<label for="wa_status_0"><i class="fas fa-times-circle text-danger"></i> <?=translate('inactive')?></label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('gateway_type')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<?php $gw = isset($settings->gateway_type) ? $settings->gateway_type : 'web'; ?>
							<div class="radio-custom radio-inline">
								<input type="radio" id="gw_web" name="gateway_type" value="web" <?=($gw == 'web') ? 'checked' : ''?>>
								<label for="gw_web"><strong><i class="fab fa-whatsapp text-success"></i> <?=translate('web_whatsapp_desc')?></strong></label>
							</div>
							<div class="radio-custom radio-inline" style="margin-top: 8px;">
								<input type="radio" id="gw_api" name="gateway_type" value="custom_api" <?=($gw == 'custom_api') ? 'checked' : ''?>>
								<label for="gw_api"><strong><i class="fas fa-server text-primary"></i> <?=translate('custom_api_desc')?></strong></label>
							</div>
							<span class="help-block"><small><?=translate('gateway_help_text')?></small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('country_code')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<span class="input-group-addon">+</span>
								<input type="text" class="form-control" name="country_code" value="<?=(isset($settings->country_code) ? html_escape($settings->country_code) : '880')?>" placeholder="880" required />
							</div>
							<span class="help-block"><small><?=translate('country_code_help')?></small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('message_header_text')?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="header_text" value="<?=(isset($settings->header_text) ? html_escape($settings->header_text) : 'ড্রিম ভ্যালি স্কুল')?>" placeholder="যেমন: ড্রিম ভ্যালি স্কুল" />
							<span class="help-block"><small>প্রতিটি বার্তার শুরুতে বোল্ড হরফে এই নাম যুক্ত হবে (খালি রাখতে পারেন)।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('message_footer_text')?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="footer_text" value="<?=(isset($settings->footer_text) ? html_escape($settings->footer_text) : 'ধন্যবাদান্তে, স্কুল প্রশাসন')?>" placeholder="যেমন: ধন্যবাদান্তে, স্কুল প্রশাসন" />
							<span class="help-block"><small>প্রতিটি বার্তার শেষে ইটালিক হরফে এই বার্তাটি যুক্ত হবে (খালি রাখতে পারেন)।</small></span>
						</div>
					</div>

					<!-- Custom API Configuration Fields -->
					<div id="custom_api_config" style="<?=($gw == 'custom_api') ? '' : 'display: none;'?>">
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('api_endpoint_url')?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="api_url" value="<?=(isset($settings->api_url) ? html_escape($settings->api_url) : '')?>" placeholder="https://api.ultramsg.com/YOUR_INSTANCE/messages/chat" />
								<span class="help-block"><small>আপনার WhatsApp Gateway প্রোভাইডারের API URL প্রদান করুন।</small></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('api_key_token')?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="api_key" value="<?=(isset($settings->api_key) ? html_escape($settings->api_key) : '')?>" placeholder="API Secret Key বা Bearer Token" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('instance_id')?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="instance_id" value="<?=(isset($settings->instance_id) ? html_escape($settings->instance_id) : '')?>" placeholder="instance12345" />
							</div>
						</div>
					</div>
				</div>

				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-3 col-md-8">
							<button type="submit" class="btn btn-default"><i class="fas fa-save"></i> <?=translate('save')?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('input[name="gateway_type"]').on('change', function() {
		if ($(this).val() === 'custom_api') {
			$('#custom_api_config').slideDown();
		} else {
			$('#custom_api_config').slideUp();
		}
	});
});
</script>


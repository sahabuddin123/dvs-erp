<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fab fa-whatsapp" style="color: #25D366;"></i> WhatsApp কনফিগারেশন ও গেটওয়ে সেটিংস</h4>
			</header>
			<?php echo form_open('whatsapp_messaging/settings', ['class' => 'form-horizontal form-bordered validate']); ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('status')?> / স্ট্যাটাস <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="wa_status_1" name="is_active" value="1" <?=(isset($settings->is_active) && $settings->is_active == 1) ? 'checked' : ''?>>
								<label for="wa_status_1"><i class="fas fa-check-circle text-success"></i> <?=translate('active')?> (সক্রিয়)</label>
							</div>
							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" id="wa_status_0" name="is_active" value="0" <?=(isset($settings->is_active) && $settings->is_active == 0) ? 'checked' : ''?>>
								<label for="wa_status_0"><i class="fas fa-times-circle text-danger"></i> <?=translate('inactive')?> (নিষ্ক্রিয়)</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">প্রেরণ মাধ্যম (Gateway Type) <span class="required">*</span></label>
						<div class="col-md-8">
							<?php $gw = isset($settings->gateway_type) ? $settings->gateway_type : 'web'; ?>
							<div class="radio-custom radio-inline">
								<input type="radio" id="gw_web" name="gateway_type" value="web" <?=($gw == 'web') ? 'checked' : ''?>>
								<label for="gw_web"><strong><i class="fab fa-whatsapp text-success"></i> Web WhatsApp / Click to Chat</strong> (সরাসরি ব্রাউজার/অ্যাপ)</label>
							</div>
							<div class="radio-custom radio-inline" style="margin-top: 8px;">
								<input type="radio" id="gw_api" name="gateway_type" value="custom_api" <?=($gw == 'custom_api') ? 'checked' : ''?>>
								<label for="gw_api"><strong><i class="fas fa-server text-primary"></i> Custom WhatsApp API Gateway</strong> (স্বয়ংক্রিয় API)</label>
							</div>
							<span class="help-block"><small>ডিফল্টভাবে "Web WhatsApp" মোড ব্যবহার করলে কোনো থার্ড-পার্টি সাবস্ক্রিপশন ছাড়াই সরাসরি WhatsApp Web / Mobile App দিয়ে ফ্রিতে বার্তা পাঠানো যায়।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">কান্ট্রি কোড (Country Code) <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<span class="input-group-addon">+</span>
								<input type="text" class="form-control" name="country_code" value="<?=(isset($settings->country_code) ? html_escape($settings->country_code) : '880')?>" placeholder="880" required />
							</div>
							<span class="help-block"><small>বাংলাদেশের জন্য <code>880</code>। স্থানীয় নম্বরসমূহ (যেমন 017...) স্বয়ংক্রিয়ভাবে আন্তর্জাতিক ফরম্যাটে রূপান্তরিত হবে।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">মেসেজ হেডার টেক্সট</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="header_text" value="<?=(isset($settings->header_text) ? html_escape($settings->header_text) : 'ড্রিম ভ্যালি স্কুল')?>" placeholder="যেমন: ড্রিম ভ্যালি স্কুল" />
							<span class="help-block"><small>প্রতিটি বার্তার শুরুতে বোল্ড হরফে এই নাম যুক্ত হবে (খালি রাখতে পারেন)।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">মেসেজ ফুটার টেক্সট</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="footer_text" value="<?=(isset($settings->footer_text) ? html_escape($settings->footer_text) : 'ধন্যবাদান্তে, স্কুল প্রশাসন')?>" placeholder="যেমন: ধন্যবাদান্তে, স্কুল প্রশাসন" />
							<span class="help-block"><small>প্রতিটি বার্তার শেষে ইটালিক হরফে এই বার্তাটি যুক্ত হবে (খালি রাখতে পারেন)।</small></span>
						</div>
					</div>

					<!-- Custom API Configuration Fields -->
					<div id="custom_api_config" style="<?=($gw == 'custom_api') ? '' : 'display: none;'?>">
						<div class="form-group">
							<label class="col-md-3 control-label">API Endpoint URL</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="api_url" value="<?=(isset($settings->api_url) ? html_escape($settings->api_url) : '')?>" placeholder="https://api.ultramsg.com/YOUR_INSTANCE/messages/chat" />
								<span class="help-block"><small>আপনার WhatsApp Gateway প্রোভাইডারের API URL প্রদান করুন।</small></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">API Key / Token</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="api_key" value="<?=(isset($settings->api_key) ? html_escape($settings->api_key) : '')?>" placeholder="API Secret Key বা Bearer Token" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Instance ID</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="instance_id" value="<?=(isset($settings->instance_id) ? html_escape($settings->instance_id) : '')?>" placeholder="instance12345" />
							</div>
						</div>
					</div>
				</div>

				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-3 col-md-8">
							<button type="submit" class="btn btn-default"><i class="fas fa-save"></i> <?=translate('save')?> / সংরক্ষণ করুন</button>
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

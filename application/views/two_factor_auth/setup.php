<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="panel-title" style="margin: 0;">
					<i class="fas fa-lock text-primary"></i> <?=translate('my_2fa_setup')?>
				</h4>
				<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#savedLoginModal">
					<i class="fas fa-shield-alt text-primary"></i> <?=translate('saved_login')?>
				</button>
			</header>
			<div class="panel-body">
				<div class="row">
					<!-- Left Card: Two Factor Authentication (APP) -->
					<div class="col-md-6 mb-lg" style="border-right: 1px solid #e2e8f0;">
						<div class="card-2fa-box p-md" style="text-align: center;">
							<h4 style="font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 15px;">
								<i class="fas fa-mobile-alt text-primary"></i> <?=translate('two_factor_authentication_app')?>
							</h4>

							<?php $app_is_on = (!empty($user_config->app_enabled) && $user_config->app_enabled == 1); ?>

							<div class="status-indicator mb-md">
								<?php if ($app_is_on): ?>
								<span style="color: #16a34a; font-weight: 600; font-size: 13px;">
									<i class="fas fa-check-circle"></i> <?=translate('you_have_two_factor_authentication_enabled')?>
								</span>
								<?php else: ?>
								<span style="color: #dc2626; font-weight: 600; font-size: 13px;">
									<i class="fas fa-times-circle"></i> <?=translate('you_have_two_factor_authentication_disabled')?>
								</span>
								<?php endif; ?>
							</div>

							<!-- QR Code for Google/Microsoft/Authy Authenticator -->
							<div class="qr-container" style="margin: 15px auto;">
								<img src="<?=$qr_base64?>" alt="2FA QR Code" class="img-thumbnail" style="width: 210px; height: 210px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
								<p class="text-muted mt-xs" style="font-size: 11px;">
									<?=translate('scan_qr_code_with_authenticator_app')?>
								</p>
							</div>

							<!-- Secret Key -->
							<div class="secret-key-wrap" style="max-width: 320px; margin: 0 auto 20px;">
								<label style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;">
									<?=translate('secret_key')?>
								</label>
								<div class="input-group">
									<input type="text" id="secret_key_val" class="form-control text-center" value="<?=$secret_key?>" readonly style="font-family: monospace; font-weight: 700; font-size: 14px; letter-spacing: 2px; background: #f8fafc;">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" onclick="copySecretKey()" title="<?=translate('copy')?>">
											<i class="fas fa-copy"></i>
										</button>
									</span>
								</div>
							</div>

							<!-- Action Button -->
							<div class="action-btn-wrap">
								<?php if ($app_is_on): ?>
								<button type="button" class="btn btn-danger" onclick="openAppModal('disable')">
									<i class="fas fa-power-off"></i> <?=translate('disable_two_factor_authentication')?>
								</button>
								<?php else: ?>
								<button type="button" class="btn btn-success" onclick="openAppModal('enable')">
									<i class="fas fa-check-circle"></i> <?=translate('enable_two_factor_authentication')?>
								</button>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<!-- Right Card: Two Factor Authentication (Email) -->
					<div class="col-md-6 mb-lg">
						<div class="card-2fa-box p-md">
							<h4 style="font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 20px;">
								<i class="fas fa-envelope text-primary"></i> <?=translate('two_factor_authentication_email')?>
							</h4>

							<?php $email_is_on = (!empty($user_config->email_enabled) && $user_config->email_enabled == 1); ?>

							<div class="form-group mb-md">
								<label class="control-label" style="font-weight: 600;">
									<?=translate('email')?> <span class="required">*</span>
								</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-at"></i></span>
									<input type="email" id="user_registered_email" class="form-control" value="<?=html_escape($user_email)?>" readonly style="background:#f8fafc;">
								</div>
								<span class="help-block text-muted" style="font-size: 11px;">
									<?=translate('2fa_code_will_be_delivered_to_this_email')?>
								</span>
							</div>

							<div class="status-indicator mb-lg">
								<?php if ($email_is_on): ?>
								<span style="color: #16a34a; font-weight: 600; font-size: 13px;">
									<i class="fas fa-check-circle"></i> <?=translate('you_have_two_factor_authentication_enabled')?>
								</span>
								<?php else: ?>
								<span style="color: #64748b; font-weight: 600; font-size: 13px;">
									<i class="fas fa-info-circle"></i> <?=translate('email_authentication_is_currently_disabled')?>
								</span>
								<?php endif; ?>
							</div>

							<!-- Action Button -->
							<div class="action-btn-wrap">
								<?php if ($email_is_on): ?>
								<button type="button" class="btn btn-danger" onclick="openEmailModal('disable')">
									<i class="fas fa-power-off"></i> <?=translate('disable_two_factor_authentication')?>
								</button>
								<?php else: ?>
								<button type="button" class="btn btn-default" onclick="openEmailModal('enable')">
									<i class="fas fa-envelope"></i> <?=translate('enable_two_factor_authentication')?>
								</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- ============================================================== -->
<!-- Modal 1: Two Factor Authentication (APP) - Verify Code         -->
<!-- ============================================================== -->
<div class="modal fade" id="modalVerifyApp" role="dialog" aria-labelledby="modalVerifyAppLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background: #1e293b; color: #fff;">
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" style="color:#fff; opacity:0.8;">&times;</button>
				<h4 class="modal-title" id="modalVerifyAppLabel">
					<i class="fas fa-mobile-alt"></i> <?=translate('two_factor_authentication_app')?>
				</h4>
			</div>
			<div class="modal-body" style="padding: 25px;">
				<div class="form-group mb-md">
					<label class="control-label" style="font-weight: 600; margin-bottom: 8px;">
						<?=translate('enter_verification_code_from_your_authenticator_app')?>
					</label>
					<input type="text" id="app_code_input" class="form-control text-center" placeholder="123456" maxlength="6" autocomplete="off" style="font-size: 24px; font-family: monospace; letter-spacing: 6px; height: 50px; font-weight: 700;">
					<input type="hidden" id="app_action_target" value="enable">
				</div>
				<div id="app_modal_alert" class="alert mb-none" style="display:none;"></div>
			</div>
			<div class="modal-footer" style="background:#f8fafc;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=translate('close')?></button>
				<button type="button" class="btn btn-primary" id="btnSubmitVerifyApp" onclick="submitAppVerify()">
					<i class="fas fa-check"></i> <?=translate('verify')?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- ============================================================== -->
<!-- Modal 2: Two Factor Authentication (Email) - Verify Code       -->
<!-- ============================================================== -->
<div class="modal fade" id="modalVerifyEmail" role="dialog" aria-labelledby="modalVerifyEmailLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background: #1e293b; color: #fff;">
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" style="color:#fff; opacity:0.8;">&times;</button>
				<h4 class="modal-title" id="modalVerifyEmailLabel">
					<i class="fas fa-envelope"></i> <?=translate('two_factor_authentication_email')?>
				</h4>
			</div>
			<div class="modal-body" style="padding: 25px;">
				<div class="form-group mb-md">
					<label class="control-label" style="font-weight: 600; margin-bottom: 8px;">
						<?=translate('enter_verification_code_sent_to_your_email')?>
					</label>
					<input type="text" id="email_code_input" class="form-control text-center" placeholder="123456" maxlength="6" autocomplete="off" style="font-size: 24px; font-family: monospace; letter-spacing: 6px; height: 50px; font-weight: 700;">
					<input type="hidden" id="email_action_target" value="enable">
				</div>
				<div id="email_modal_alert" class="alert mb-none" style="display:none;"></div>
			</div>
			<div class="modal-footer" style="background:#f8fafc;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=translate('close')?></button>
				<button type="button" class="btn btn-primary" id="btnSubmitVerifyEmail" onclick="submitEmailVerify()">
					<i class="fas fa-check"></i> <?=translate('verify')?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- ============================================================== -->
<!-- Modal 3: Login Saved Browser List (Screenshot 4)              -->
<!-- ============================================================== -->
<div class="modal fade" id="savedLoginModal" role="dialog" aria-labelledby="savedLoginModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background: #1e293b; color: #fff;">
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" style="color:#fff; opacity:0.8;">&times;</button>
				<h4 class="modal-title" id="savedLoginModalLabel">
					<i class="fas fa-shield-alt"></i> <?=translate('login_saved_browser_list')?>
				</h4>
			</div>
			<div class="modal-body" style="padding: 20px;">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr style="background:#f8fafc;">
								<th style="width: 40px;" class="text-center">#</th>
								<th><?=translate('ip_address')?></th>
								<th><?=translate('browser')?></th>
								<th><?=translate('platform')?></th>
								<th><?=translate('login_date_time')?></th>
								<th style="width: 80px;" class="text-center"><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($saved_browsers)): 
								$sl = 1;
								foreach ($saved_browsers as $sb):
							?>
							<tr>
								<td class="text-center"><?=$sl++?></td>
								<td><code><?=$sb['ip_address']?></code></td>
								<td><strong><?=html_escape($sb['browser'])?></strong></td>
								<td><?=html_escape($sb['platform'])?></td>
								<td><?=date('d.M.Y h:i:s A', strtotime($sb['created_at']))?></td>
								<td class="text-center">
									<a href="<?=base_url('two_factor_auth/remove_browser/' . $sb['id'])?>" class="btn btn-danger btn-xs" onclick="return confirm('<?=translate('are_you_sure_to_delete')?>');">
										<i class="fas fa-trash-alt"></i> <?=translate('remove')?>
									</a>
								</td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted" style="padding: 25px;">
									<i class="fas fa-info-circle mb-xs" style="display:block;"></i>
									<?=translate('no_saved_browsers_found')?>
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer" style="background:#f8fafc;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=translate('close')?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function copySecretKey() {
	var copyText = document.getElementById("secret_key_val");
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	navigator.clipboard.writeText(copyText.value);
	alert("<?=translate('secret_key_copied_to_clipboard')?>");
}

function openAppModal(action) {
	$('#app_action_target').val(action);
	$('#app_code_input').val('');
	$('#app_modal_alert').hide().removeClass('alert-danger alert-success');
	$('#modalVerifyApp').modal('show');
}

function openEmailModal(action) {
	$('#email_action_target').val(action);
	$('#email_code_input').val('');
	$('#email_modal_alert').hide().removeClass('alert-danger alert-success');

	// Send code to email first
	var email = $('#user_registered_email').val();
	var postData = { email: email };
	if (typeof csrfData !== 'undefined') {
		$.extend(postData, csrfData);
	}

	$.ajax({
		url: '<?=base_url('two_factor_auth/send_email_code')?>',
		type: 'POST',
		data: postData,
		dataType: 'json',
		success: function(res) {
			if (res.status === 'success') {
				$('#modalVerifyEmail').modal('show');
			} else {
				alert(res.message || '<?=translate("failed_to_send_email_code")?>');
			}
		},
		error: function() {
			alert('Server error.');
		}
	});
}

function submitAppVerify() {
	var code = $('#app_code_input').val().trim();
	var action = $('#app_action_target').val();

	if (code.length !== 6) {
		$('#app_modal_alert').addClass('alert-danger').html('<?=translate("please_enter_6_digit_security_code")?>').show();
		return;
	}

	var $btn = $('#btnSubmitVerifyApp');
	$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?=translate("verifying")?>...');

	var postData = { code: code, action: action };
	if (typeof csrfData !== 'undefined') {
		$.extend(postData, csrfData);
	}

	$.ajax({
		url: '<?=base_url('two_factor_auth/verify_toggle_app')?>',
		type: 'POST',
		data: postData,
		dataType: 'json',
		success: function(res) {
			$btn.prop('disabled', false).html('<i class="fas fa-check"></i> <?=translate("verify")?>');
			if (res.status === 'success') {
				$('#modalVerifyApp').modal('hide');
				window.location.reload();
			} else {
				$('#app_modal_alert').removeClass('alert-success').addClass('alert-danger').html(res.message).show();
			}
		},
		error: function() {
			$btn.prop('disabled', false).html('<i class="fas fa-check"></i> <?=translate("verify")?>');
			$('#app_modal_alert').addClass('alert-danger').html('Server error.').show();
		}
	});
}

function submitEmailVerify() {
	var code = $('#email_code_input').val().trim();
	var action = $('#email_action_target').val();

	if (code.length !== 6) {
		$('#email_modal_alert').addClass('alert-danger').html('<?=translate("please_enter_6_digit_security_code")?>').show();
		return;
	}

	var $btn = $('#btnSubmitVerifyEmail');
	$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?=translate("verifying")?>...');

	var postData = { code: code, action: action };
	if (typeof csrfData !== 'undefined') {
		$.extend(postData, csrfData);
	}

	$.ajax({
		url: '<?=base_url('two_factor_auth/verify_toggle_email')?>',
		type: 'POST',
		data: postData,
		dataType: 'json',
		success: function(res) {
			$btn.prop('disabled', false).html('<i class="fas fa-check"></i> <?=translate("verify")?>');
			if (res.status === 'success') {
				$('#modalVerifyEmail').modal('hide');
				window.location.reload();
			} else {
				$('#email_modal_alert').removeClass('alert-success').addClass('alert-danger').html(res.message).show();
			}
		},
		error: function() {
			$btn.prop('disabled', false).html('<i class="fas fa-check"></i> <?=translate("verify")?>');
			$('#email_modal_alert').addClass('alert-danger').html('Server error.').show();
		}
	});
}
</script>

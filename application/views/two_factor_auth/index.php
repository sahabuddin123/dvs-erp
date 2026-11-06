<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#settings" data-toggle="tab"><i class="fas fa-sliders-h"></i> <?=translate('settings')?> / কনফিগারেশন</a>
			</li>
			<li>
				<a href="#logs" data-toggle="tab"><i class="fas fa-history"></i> <?=translate('activity_logs')?> / লগ রিপোর্ট</a>
			</li>
		</ul>
		<div class="tab-content">
			<!-- Settings Tab -->
			<div class="tab-pane active" id="settings">
				<?php echo form_open('two_factor_auth/save_settings', ['class' => 'form-horizontal form-bordered validate']); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('status')?> / স্ট্যাটাস <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="status_active" name="is_active" value="1" <?=(isset($settings->is_active) && $settings->is_active == 1) ? 'checked' : ''?>>
								<label for="status_active"><i class="fas fa-check-circle text-success"></i> <?=translate('active')?> (চালু)</label>
							</div>
							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" id="status_inactive" name="is_active" value="0" <?=(isset($settings->is_active) && $settings->is_active == 0) ? 'checked' : ''?>>
								<label for="status_inactive"><i class="fas fa-times-circle text-danger"></i> <?=translate('inactive')?> (বন্ধ)</label>
							</div>
							<span class="help-block"><small>দ্বি-স্তর বিশিষ্ট নিরাপত্তা (2FA) লগইন ভেরিফিকেশন সিস্টেম চালু বা বন্ধ করুন।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('method')?> / যাচাইকরণ মাধ্যম <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
							$current_method = isset($settings->auth_method) ? $settings->auth_method : 'email';
							?>
							<div class="radio-custom radio-inline">
								<input type="radio" id="method_email" name="auth_method" value="email" <?=($current_method == 'email') ? 'checked' : ''?>>
								<label for="method_email"><i class="fas fa-envelope text-primary"></i> ইমেইল ওটিপি (Email OTP)</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="method_sms" name="auth_method" value="sms" <?=($current_method == 'sms') ? 'checked' : ''?>>
								<label for="method_sms"><i class="fas fa-sms text-warning"></i> এসএমএস ওটিপি (SMS OTP - M-RAM)</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="method_both" name="auth_method" value="both" <?=($current_method == 'both') ? 'checked' : ''?>>
								<label for="method_both"><i class="fas fa-bell text-success"></i> উভয় মাধ্যম (Email & SMS)</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">প্রযোজ্য ইউজার রোল (Enforced Roles) <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
							$enforced = [];
							if (!empty($settings->roles_enforced)) {
								$dec = json_decode($settings->roles_enforced, true);
								$enforced = is_array($dec) ? $dec : explode(',', $settings->roles_enforced);
							}
							?>
							<div style="margin-bottom: 8px;">
								<a href="javascript:void(0);" id="select_all_roles" class="btn btn-default btn-xs"><i class="fas fa-check-double"></i> সব নির্বাচন করুন</a>
								<a href="javascript:void(0);" id="deselect_all_roles" class="btn btn-default btn-xs"><i class="fas fa-times"></i> সব বাতিল করুন</a>
							</div>
							<div class="row">
								<?php foreach ($roles as $role_id => $role_name): 
									if (empty($role_id)) continue;
									$is_checked = in_array((string)$role_id, $enforced);
								?>
									<div class="col-md-6" style="margin-bottom: 6px;">
										<div class="checkbox-custom checkbox-success">
											<input type="checkbox" name="roles_enforced[]" id="role_<?=$role_id?>" value="<?=$role_id?>" <?=$is_checked ? 'checked' : ''?>>
											<label for="role_<?=$role_id?>"><strong><?=$role_name?></strong></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<span class="help-block"><small>যেসব রোল সিলেক্ট করা থাকবে, তাদের লগইনের সময় অবশ্যই ২-স্টেপ সিকিউরিটি ওটিপি কোড যাচাই করতে হবে।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">ওটিপি কোডের মেয়াদ (OTP Expiry Time) <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-group">
								<input type="number" name="otp_expire" class="form-control" value="<?=(isset($settings->otp_expire) ? $settings->otp_expire : 10)?>" min="1" max="60" required />
								<span class="input-group-addon">মিনিট (Minutes)</span>
							</div>
							<span class="help-block"><small>প্রেরিত সিকিউরিটি কোডটি কত মিনিট পর্যন্ত বৈধ থাকবে (ডিফল্ট: ১০ মিনিট)।</small></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">সুপার এডমিন মাস্টার বাইপাস পিন <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon"><i class="fas fa-key text-danger"></i></span>
								<input type="text" name="master_bypass_pin" class="form-control" value="<?=(isset($settings->master_bypass_pin) ? $settings->master_bypass_pin : '998877')?>" required />
							</div>
							<span class="help-block"><small style="color: #007F3D;"><i class="fas fa-shield-alt"></i> জরুরি অবস্থায় (যেমন স্কুল মেইল বা এসএমএস গেটওয়ে সাময়িক অফলাইনে থাকলে) সুপার এডমিন এই পিন দিয়ে সরাসরি লগইন করতে পারবেন। এটি অত্যন্ত সুরক্ষিত রাখুন।</small></span>
						</div>
					</div>

					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<button type="submit" class="btn btn-default"><i class="fas fa-save"></i> <?=translate('save')?> / সংরক্ষণ করুন</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>

			<!-- Logs Tab -->
			<div class="tab-pane" id="logs">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th>#</th>
								<th><?=translate('user')?> / ব্যবহারকারী</th>
								<th><?=translate('role')?> / রোল</th>
								<th><?=translate('method')?></th>
								<th>প্রেরিত ঠিকানা / নম্বর</th>
								<th>সিকিউরিটি কোড</th>
								<th><?=translate('status')?> / স্ট্যাটাস</th>
								<th>আইপি অ্যাড্রেস</th>
								<th><?=translate('date')?> ও সময়</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if (!empty($logs)):
								foreach ($logs as $log):
									$is_expired = strtotime($log['expires_at']) < time();
							?>
							<tr>
								<td><?=$i++?></td>
								<td><strong><?=(!empty($log['username']) ? html_escape($log['username']) : 'UID #' . $log['user_id'])?></strong></td>
								<td><span class="label label-primary"><?=(!empty($log['role_name']) ? $log['role_name'] : 'N/A')?></span></td>
								<td>
									<?php if ($log['auth_method'] == 'sms'): ?>
										<span class="badge badge-warning"><i class="fas fa-sms"></i> SMS</span>
									<?php elseif ($log['auth_method'] == 'both'): ?>
										<span class="badge badge-info"><i class="fas fa-bell"></i> Both</span>
									<?php else: ?>
										<span class="badge badge-primary"><i class="fas fa-envelope"></i> Email</span>
									<?php endif; ?>
								</td>
								<td>
									<?php 
									$contact = !empty($log['email']) ? $log['email'] : (!empty($log['mobile']) ? $log['mobile'] : '-');
									echo html_escape($contact);
									?>
								</td>
								<td><code><?=html_escape($log['code'])?></code></td>
								<td>
									<?php if ($log['is_used'] == 1): ?>
										<span class="label label-success"><i class="fas fa-check"></i> যাচাইকৃত (Verified)</span>
									<?php elseif ($is_expired): ?>
										<span class="label label-danger"><i class="fas fa-clock"></i> মেয়াদোত্তীর্ণ (Expired)</span>
									<?php else: ?>
										<span class="label label-warning"><i class="fas fa-hourglass-half"></i> অপেক্ষমাণ (Pending)</span>
									<?php endif; ?>
								</td>
								<td><small><?=html_escape($log['ip_address'])?></small></td>
								<td><?=_d($log['created_at']) . ' ' . date('h:i A', strtotime($log['created_at']))?></td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="9" class="text-center text-muted">কোনো ভেরিফিকেশন লগ পাওয়া যায়নি।</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('#select_all_roles').on('click', function() {
		$('input[name="roles_enforced[]"]').prop('checked', true);
	});
	$('#deselect_all_roles').on('click', function() {
		$('input[name="roles_enforced[]"]').prop('checked', false);
	});
});
</script>

<div class="row">
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-clock"></i> Biometric Shift & Attendance Rules</h4>
			</header>
			<?php echo form_open(base_url('biometric/settings'), array('class' => 'form-horizontal form-bordered validate')); ?>
				<div class="panel-body">
					<?php if (is_superadmin_loggedin()) { ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?></label>
						<div class="col-md-8">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $settings['branch_id'] ?? 1), "class='form-control' data-plugin-selectTwo data-width='100%' onchange='this.form.submit()'");
							?>
						</div>
					</div>
					<?php } ?>

					<div class="form-group">
						<label class="col-md-3 control-label">Auto Late Detect</label>
						<div class="col-md-8">
							<div class="checkbox-custom checkbox-success">
								<input type="checkbox" name="auto_late_detect" id="auto_late_detect" value="1" <?=($settings['auto_late_detect'] ?? 1) ? 'checked' : ''?> />
								<label for="auto_late_detect">Mark 'L' (Late) if punch time is after In-Time</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">ADMS Cloud Push</label>
						<div class="col-md-8">
							<div class="checkbox-custom checkbox-primary">
								<input type="checkbox" name="adms_push_enabled" id="adms_push_enabled" value="1" <?=($settings['adms_push_enabled'] ?? 1) ? 'checked' : ''?> />
								<label for="adms_push_enabled">Accept realtime attendance pushes from ADMS-enabled terminals</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('student_in_time')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-clock"></i></span>
								<input type="text" name="student_in_time" class="form-control timepicker" value="<?=$settings['student_in_time'] ?? '09:00:00'?>" required />
							</div>
							<span class="error"><?=form_error('student_in_time')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('student_out_time')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-clock"></i></span>
								<input type="text" name="student_out_time" class="form-control timepicker" value="<?=$settings['student_out_time'] ?? '16:00:00'?>" required />
							</div>
							<span class="error"><?=form_error('student_out_time')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('staff_in_time')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-clock"></i></span>
								<input type="text" name="staff_in_time" class="form-control timepicker" value="<?=$settings['staff_in_time'] ?? '09:00:00'?>" required />
							</div>
							<span class="error"><?=form_error('staff_in_time')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('staff_out_time')?> <span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-clock"></i></span>
								<input type="text" name="staff_out_time" class="form-control timepicker" value="<?=$settings['staff_out_time'] ?? '17:00:00'?>" required />
							</div>
							<span class="error"><?=form_error('staff_out_time')?></span>
						</div>
					</div>
				</div>

				<div class="panel-footer">
					<div class="row">
						<div class="col-md-3 col-md-offset-3">
							<button type="submit" class="btn btn-default btn-block">
								<i class="fas fa-save"></i> <?=translate('save')?>
							</button>
						</div>
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>

	<!-- ADMS Cloud Server & Automation Info -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-cloud-upload-alt"></i> ZKTeco ADMS Cloud Push Setup</h4>
			</header>
			<div class="panel-body">
				<p class="text-muted">
					If your ZKTeco machine supports <strong>ADMS / Cloud Server</strong>, the machine can automatically push punches in real-time without needing port forwarding or polling!
				</p>
				<table class="table table-bordered table-condensed text-dark">
					<tbody>
						<tr>
							<td><strong>Server Address / Host:</strong></td>
							<td><code><?=isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'Your_Server_IP'?></code></td>
						</tr>
						<tr>
							<td><strong>Server Port:</strong></td>
							<td><code><?=$_SERVER['SERVER_PORT'] ?? '80'?></code></td>
						</tr>
						<tr>
							<td><strong>ADMS Push URL:</strong></td>
							<td><code><?=base_url('biometric/cdata')?></code></td>
						</tr>
					</tbody>
				</table>
				<div class="alert alert-info mb-none">
					<strong>How to enable on machine:</strong><br>
					1. Go to <strong>Menu &rarr; Comm. &rarr; Cloud Server / ADMS</strong>.<br>
					2. Turn <strong>Enable Cloud Server</strong> to <strong>ON</strong>.<br>
					3. Enter Server Address & Port.<br>
					4. Device will immediately connect and stream punch logs to ERP!
				</div>
			</div>
		</section>

		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-clock"></i> Automated Sync Cron Job</h4>
			</header>
			<div class="panel-body">
				<p class="text-muted">
					For local network UDP/TCP polling, configure a scheduled task or cron job to run every 5 or 10 minutes:
				</p>
				<p><strong>Windows PowerShell / Task Scheduler:</strong></p>
				<div class="well well-sm">
					<code>curl.exe -s <?=base_url('biometric/cron_sync')?></code>
				</div>
				<p><strong>Linux crontab:</strong></p>
				<div class="well well-sm">
					<code>*/10 * * * * curl -s <?=base_url('biometric/cron_sync')?> > /dev/null 2>&1</code>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	if ($.fn.timepicker) {
		$('.timepicker').timepicker({
			minuteStep: 5,
			showSeconds: true,
			showMeridian: false,
			defaultTime: false
		});
	}
});
</script>

<?php
$assigned_modules = !empty($package['permission']) ? json_decode($package['permission'], true) : array();
if (!is_array($assigned_modules)) $assigned_modules = array();
?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-edit"></i> <?php echo translate('edit_package'); ?> - <?php echo html_escape($package['name']); ?></h4>
			</header>
			<?php echo form_open('saas/package_edit/' . $package['id'], array('class' => 'form-horizontal form-bordered validate')); ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name" value="<?php echo set_value('name', $package['name']); ?>" required />
							<div class="checkbox-custom checkbox-default mt-xs">
								<input type="checkbox" name="recommended" id="recommended" value="1" <?php echo $package['recommended'] == 1 ? 'checked' : ''; ?> />
								<label for="recommended"><?php echo translate('recommended'); ?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" step="any" class="form-control" name="price" value="<?php echo set_value('price', $package['price']); ?>" required />
							<div class="checkbox-custom checkbox-default mt-xs">
								<input type="checkbox" name="free_trial" id="free_trial" value="1" <?php echo $package['free_trial'] == 1 ? 'checked' : ''; ?> />
								<label for="free_trial"><?php echo translate('free_trial'); ?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('discount'); ?></label>
						<div class="col-md-6">
							<input type="number" step="any" class="form-control" name="discount" value="<?php echo set_value('discount', $package['discount']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('student_limit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="student_limit" value="<?php echo set_value('student_limit', $package['student_limit']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('parents_limit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="parents_limit" value="<?php echo set_value('parents_limit', $package['parents_limit']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('staff_limit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="staff_limit" value="<?php echo set_value('staff_limit', $package['staff_limit']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('teacher_limit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="teacher_limit" value="<?php echo set_value('teacher_limit', $package['teacher_limit']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('subscription_period'); ?> <span class="required">*</span></label>
						<div class="col-md-3">
							<select name="period_type" id="period_type" class="form-control" required>
								<option value="1" <?php echo $package['period_type'] == 1 ? 'selected' : ''; ?>><?php echo translate('lifetime'); ?></option>
								<option value="2" <?php echo $package['period_type'] == 2 ? 'selected' : ''; ?>><?php echo translate('days'); ?></option>
								<option value="3" <?php echo $package['period_type'] == 3 ? 'selected' : ''; ?>><?php echo translate('monthly'); ?></option>
								<option value="4" <?php echo $package['period_type'] == 4 ? 'selected' : ''; ?>><?php echo translate('yearly'); ?></option>
							</select>
						</div>
						<div class="col-md-3">
							<input type="number" class="form-control" name="period_value" id="period_value" value="<?php echo set_value('period_value', $package['period_value']); ?>" <?php echo $package['period_type'] == 1 ? 'disabled' : ''; ?> />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('show_hide_package_on_website'); ?></label>
						<div class="col-md-6">
							<div class="checkbox-custom checkbox-default mt-xs">
								<input type="checkbox" name="show_onwebsite" id="show_onwebsite" value="1" <?php echo $package['show_onwebsite'] == 1 ? 'checked' : ''; ?> />
								<label for="show_onwebsite"><?php echo translate('show_on_website'); ?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('module_enabled'); ?></label>
						<div class="col-md-8">
							<div class="row">
								<?php if (!empty($modules)): ?>
									<?php foreach ($modules as $mod): ?>
										<div class="col-md-4 mb-xs">
											<div class="checkbox-custom checkbox-default">
												<input type="checkbox" name="modules[]" id="mod_<?php echo $mod['id']; ?>" value="<?php echo $mod['id']; ?>" <?php echo in_array($mod['id'], $assigned_modules) ? 'checked' : ''; ?> />
												<label for="mod_<?php echo $mod['id']; ?>"><?php echo html_escape($mod['name']); ?></label>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" name="submit" value="update" class="btn btn-default btn-block"><i class="fas fa-check"></i> <?php echo translate('update'); ?></button>
						</div>
						<div class="col-md-2">
							<a href="<?php echo base_url('saas/package'); ?>" class="btn btn-default btn-block"><?php echo translate('cancel'); ?></a>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<script type="text/javascript">
$('#period_type').on('change', function() {
	if ($(this).val() == '1') {
		$('#period_value').val('0').prop('disabled', true);
	} else {
		$('#period_value').prop('disabled', false);
	}
});
</script>


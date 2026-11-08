<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('package_list'); ?></a>
					</li>
					<li>
						<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add_package'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="list" class="tab-pane active">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-condensed mb-none table-export">
								<thead>
									<tr>
										<th width="40"><?php echo translate('sl'); ?></th>
										<th><?php echo translate('name'); ?></th>
										<th><?php echo translate('price'); ?></th>
										<th><?php echo translate('discount'); ?></th>
										<th><?php echo translate('period_type'); ?></th>
										<th><?php echo translate('period'); ?></th>
										<th><?php echo translate('status'); ?></th>
										<th><?php echo translate('action'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$count = 1;
									$period_types = array(1 => translate('lifetime'), 2 => translate('days'), 3 => translate('monthly'), 4 => translate('yearly'));
									if (!empty($packages)):
										foreach ($packages as $row):
									?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td>
											<strong><?php echo html_escape($row['name']); ?></strong>
											<?php if ($row['recommended'] == 1): ?>
												<span class="label label-info ml-xs"><i class="fas fa-star"></i> <?php echo translate('recommended'); ?></span>
											<?php endif; ?>
											<?php if ($row['free_trial'] == 1): ?>
												<span class="label label-warning ml-xs"><?php echo translate('free_trial'); ?></span>
											<?php endif; ?>
										</td>
										<td>$<?php echo number_format($row['price'], 2); ?></td>
										<td>$<?php echo number_format($row['discount'], 2); ?></td>
										<td><?php echo isset($period_types[$row['period_type']]) ? $period_types[$row['period_type']] : 'N/A'; ?></td>
										<td><?php echo ($row['period_type'] == 1) ? '0' : $row['period_value']; ?></td>
										<td>
											<?php if ($row['status'] == 1): ?>
												<span class="label label-success"><i class="fas fa-check"></i> <?php echo translate('active'); ?></span>
											<?php else: ?>
												<span class="label label-danger"><?php echo translate('inactive'); ?></span>
											<?php endif; ?>
										</td>
										<td class="action">
											<a href="<?php echo base_url('saas/package_edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" title="<?php echo translate('edit'); ?>">
												<i class="fas fa-pen-nib"></i>
											</a>
											<?php echo btn_delete('saas/package_delete/' . $row['id']); ?>
										</td>
									</tr>
									<?php 
										endforeach;
									else:
									?>
									<tr>
										<td colspan="8" class="text-center"><?php echo translate('no_information_available'); ?></td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<div id="create" class="tab-pane">
						<?php echo form_open('saas/package', array('class' => 'form-horizontal form-bordered validate')); ?>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" required placeholder="Package Name (e.g. Premium)" />
									<div class="checkbox-custom checkbox-default mt-xs">
										<input type="checkbox" name="recommended" id="recommended" value="1" />
										<label for="recommended"><?php echo translate('recommended'); ?></label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('price'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="number" step="any" class="form-control" name="price" value="<?php echo set_value('price', '0'); ?>" required />
									<div class="checkbox-custom checkbox-default mt-xs">
										<input type="checkbox" name="free_trial" id="free_trial" value="1" />
										<label for="free_trial"><?php echo translate('free_trial'); ?></label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('discount'); ?></label>
								<div class="col-md-6">
									<input type="number" step="any" class="form-control" name="discount" value="<?php echo set_value('discount', '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('student_limit'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="student_limit" value="<?php echo set_value('student_limit', '500'); ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('parents_limit'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="parents_limit" value="<?php echo set_value('parents_limit', '500'); ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('staff_limit'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="staff_limit" value="<?php echo set_value('staff_limit', '50'); ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('teacher_limit'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="teacher_limit" value="<?php echo set_value('teacher_limit', '50'); ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subscription_period'); ?> <span class="required">*</span></label>
								<div class="col-md-3">
									<select name="period_type" id="period_type" class="form-control" required>
										<option value="1"><?php echo translate('lifetime'); ?></option>
										<option value="2"><?php echo translate('days'); ?></option>
										<option value="3" selected><?php echo translate('monthly'); ?></option>
										<option value="4"><?php echo translate('yearly'); ?></option>
									</select>
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" name="period_value" id="period_value" value="<?php echo set_value('period_value', '1'); ?>" placeholder="Enter Day / Month / Year" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('show_hide_package_on_website'); ?></label>
								<div class="col-md-6">
									<div class="checkbox-custom checkbox-default mt-xs">
										<input type="checkbox" name="show_onwebsite" id="show_onwebsite" value="1" checked />
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
														<input type="checkbox" name="modules[]" id="mod_<?php echo $mod['id']; ?>" value="<?php echo $mod['id']; ?>" checked />
														<label for="mod_<?php echo $mod['id']; ?>"><?php echo html_escape($mod['name']); ?></label>
													</div>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" name="submit" value="save" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
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

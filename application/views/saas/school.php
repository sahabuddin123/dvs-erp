<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('school_list'); ?></a>
					</li>
					<li>
						<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add_school'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="list" class="tab-pane active">
						<div class="mb-md">
							<a href="<?php echo base_url('saas/school?filter=all'); ?>" class="btn btn-sm <?php echo $filter == 'all' ? 'btn-primary' : 'btn-default'; ?>"><?php echo translate('all'); ?></a>
							<a href="<?php echo base_url('saas/school?filter=active'); ?>" class="btn btn-sm <?php echo $filter == 'active' ? 'btn-success' : 'btn-default'; ?>"><?php echo translate('active'); ?></a>
							<a href="<?php echo base_url('saas/school?filter=inactive'); ?>" class="btn btn-sm <?php echo $filter == 'inactive' ? 'btn-warning' : 'btn-default'; ?>"><?php echo translate('inactive'); ?></a>
							<a href="<?php echo base_url('saas/school?filter=expired'); ?>" class="btn btn-sm <?php echo $filter == 'expired' ? 'btn-danger' : 'btn-default'; ?>"><?php echo translate('expired'); ?></a>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-condensed mb-none table-export">
								<thead>
									<tr>
										<th width="40"><?php echo translate('sl'); ?></th>
										<th><?php echo translate('school_name'); ?></th>
										<th><?php echo translate('plan_name'); ?></th>
										<th><?php echo translate('price'); ?></th>
										<th><?php echo translate('email'); ?></th>
										<th><?php echo translate('mobile_no'); ?></th>
										<th><?php echo translate('date'); ?></th>
										<th><?php echo translate('last_upgrade'); ?></th>
										<th><?php echo translate('status'); ?></th>
										<th><?php echo translate('action'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$count = 1;
									if (!empty($school_list)):
										foreach ($school_list as $row):
											$is_active = ($row['expire_date'] == 'Lifetime' || strtotime($row['expire_date']) >= strtotime(date('Y-m-d')));
									?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td>
											<strong><?php echo html_escape($row['school_name']); ?></strong><br>
											<small class="text-muted"><i class="fas fa-code-branch"></i> <?php echo html_escape($row['branch_name']); ?></small>
										</td>
										<td><span class="label label-primary"><?php echo !empty($row['plan_name']) ? html_escape($row['plan_name']) : 'N/A'; ?></span></td>
										<td><?php echo !empty($row['plan_price']) ? ($row['currency'] . ' ' . number_format($row['plan_price'], 2)) : 'Free'; ?></td>
										<td><?php echo html_escape($row['email']); ?></td>
										<td><?php echo html_escape($row['mobileno']); ?></td>
										<td>
											<small><strong><?php echo translate('start_date'); ?>:</strong> <?php echo !empty($row['branch_created']) ? date('d.M.Y', strtotime($row['branch_created'])) : '-'; ?></small><br>
											<small><strong><?php echo translate('expiry_date'); ?>:</strong> <?php echo ($row['expire_date'] == 'Lifetime') ? '<span class="text-success font-weight-bold">Lifetime</span>' : (!empty($row['expire_date']) ? date('d.M.Y', strtotime($row['expire_date'])) : '-'); ?></small>
										</td>
										<td><?php echo !empty($row['upgrade_lasttime']) ? date('d.M.Y', strtotime($row['upgrade_lasttime'])) : '-'; ?></td>
										<td>
											<?php if ($row['branch_status'] == 1 && $is_active): ?>
												<span class="label label-success"><i class="fas fa-check"></i> <?php echo translate('active'); ?></span>
											<?php elseif ($row['branch_status'] == 1 && !$is_active): ?>
												<span class="label label-danger"><i class="fas fa-times"></i> <?php echo translate('expired'); ?></span>
											<?php else: ?>
												<span class="label label-warning"><?php echo translate('inactive'); ?></span>
											<?php endif; ?>
										</td>
										<td class="action">
											<!-- Change / Upgrade Plan -->
											<button class="btn btn-default btn-circle icon" onclick="changePlanModal(<?php echo $row['school_id']; ?>, '<?php echo html_escape(addslashes($row['school_name'])); ?>', <?php echo (int)$row['package_id']; ?>)" data-toggle="tooltip" title="<?php echo translate('upgrade_plan'); ?>">
												<i class="fas fa-cubes"></i>
											</button>
											<!-- Edit School Branch -->
											<a href="<?php echo base_url('branch/edit/' . $row['school_id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" title="<?php echo translate('edit'); ?>">
												<i class="fas fa-pen-nib"></i>
											</a>
										</td>
									</tr>
									<?php 
										endforeach;
									else:
									?>
									<tr>
										<td colspan="10" class="text-center"><?php echo translate('no_information_available'); ?></td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<div id="create" class="tab-pane">
						<?php echo form_open('saas/school', array('class' => 'form-horizontal form-bordered validate')); ?>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('branch_name'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="branch_name" value="<?php echo set_value('branch_name'); ?>" required placeholder="e.g. Main Branch" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('school_name'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="school_name" value="<?php echo set_value('school_name'); ?>" required placeholder="e.g. Dream Valley International School" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('admin_name'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="admin_name" value="<?php echo set_value('admin_name'); ?>" required placeholder="Administrator Full Name" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="email" value="<?php echo set_value('email'); ?>" required placeholder="schooladmin@example.com" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('mobile_no'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="mobileno" value="<?php echo set_value('mobileno'); ?>" required placeholder="+8801XXXXXXXXX" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('currency'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="currency" value="BDT" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('currency_symbol'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="currency_symbol" value="৳" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('city'); ?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="city" value="<?php echo set_value('city'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('state'); ?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="state" value="<?php echo set_value('state'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('address'); ?></label>
								<div class="col-md-6">
									<textarea class="form-control" name="address" rows="3"><?php echo set_value('address'); ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('plan'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<select name="package_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
										<option value=""><?php echo translate('select'); ?></option>
										<?php foreach ($packages as $pkg): ?>
											<option value="<?php echo $pkg['id']; ?>"><?php echo html_escape($pkg['name']); ?> (<?php echo $pkg['price'] > 0 ? '$' . $pkg['price'] : 'Free'; ?>)</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('status'); ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<select name="status" class="form-control" data-plugin-selectTwo data-width="100%" required>
										<option value="1"><?php echo translate('active'); ?></option>
										<option value="0"><?php echo translate('inactive'); ?></option>
									</select>
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

<!-- Change / Upgrade Plan Modal -->
<div class="modal fade" id="changePlanModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('saas/update_school_plan', array('class' => 'validate')); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fas fa-cubes"></i> <?php echo translate('upgrade_plan'); ?> - <span id="modal_school_name"></span></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="school_id" id="modal_school_id" value="" />
					<div class="form-group mb-md">
						<label class="control-label"><?php echo translate('select_plan'); ?> <span class="required">*</span></label>
						<select name="package_id" id="modal_package_id" class="form-control" required>
							<?php foreach ($packages as $pkg): ?>
								<option value="<?php echo $pkg['id']; ?>"><?php echo html_escape($pkg['name']); ?> - $<?php echo $pkg['price']; ?> (<?php echo $pkg['discount'] > 0 ? 'Discount: $' . $pkg['discount'] : ''; ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> <?php echo translate('update'); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
function changePlanModal(schoolId, schoolName, currentPackageId) {
	$('#modal_school_id').val(schoolId);
	$('#modal_school_name').text(schoolName);
	$('#modal_package_id').val(currentPackageId);
	$('#changePlanModal').modal('show');
}
</script>


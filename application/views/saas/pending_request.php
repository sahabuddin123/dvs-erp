<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-user-clock"></i> <?php echo translate('pending_request'); ?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th width="40"><?php echo translate('sl'); ?></th>
								<th><?php echo translate('school_name'); ?></th>
								<th><?php echo translate('admin_name'); ?></th>
								<th><?php echo translate('email'); ?></th>
								<th><?php echo translate('mobile_no'); ?></th>
								<th><?php echo translate('plan_name'); ?></th>
								<th><?php echo translate('request_date'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$count = 1;
							if (!empty($requests)):
								foreach ($requests as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td>
									<strong><?php echo html_escape($row['school_name']); ?></strong><br>
									<small class="text-muted"><?php echo html_escape($row['address']); ?></small>
								</td>
								<td><?php echo html_escape($row['admin_name']); ?></td>
								<td><?php echo html_escape($row['email']); ?></td>
								<td><?php echo html_escape($row['contact_number']); ?></td>
								<td><span class="label label-primary"><?php echo !empty($row['plan_name']) ? html_escape($row['plan_name']) : 'N/A'; ?></span></td>
								<td><?php echo !empty($row['created_at']) ? date('d.M.Y h:i A', strtotime($row['created_at'])) : '-'; ?></td>
								<td class="action">
									<!-- Approve -->
									<?php echo form_open('saas/approve_request', array('style' => 'display:inline-block;')); ?>
										<input type="hidden" name="request_id" value="<?php echo $row['id']; ?>" />
										<button type="submit" class="btn btn-success btn-circle icon" data-toggle="tooltip" title="<?php echo translate('approve'); ?>" onclick="return confirm('Are you sure you want to approve this school registration?');">
											<i class="fas fa-check"></i>
										</button>
									<?php echo form_close(); ?>

									<!-- Reject -->
									<button type="button" class="btn btn-danger btn-circle icon" onclick="rejectModal(<?php echo $row['id']; ?>, '<?php echo html_escape(addslashes($row['school_name'])); ?>')" data-toggle="tooltip" title="<?php echo translate('reject'); ?>">
										<i class="fas fa-times"></i>
									</button>
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
		</section>
	</div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('saas/reject_request'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fas fa-times-circle text-danger"></i> <?php echo translate('reject'); ?> - <span id="reject_school_name"></span></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="request_id" id="reject_request_id" value="" />
					<div class="form-group mb-md">
						<label class="control-label"><?php echo translate('reject_reason'); ?> <span class="required">*</span></label>
						<textarea name="reason" class="form-control" rows="4" required placeholder="Specify why the registration request is rejected..."></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger"><i class="fas fa-ban"></i> <?php echo translate('reject'); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
function rejectModal(requestId, schoolName) {
	$('#reject_request_id').val(requestId);
	$('#reject_school_name').text(schoolName);
	$('#rejectModal').modal('show');
}
</script>


<div class="row">
	<div class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a href="<?php echo base_url('saas/settings_general'); ?>"><i class="fas fa-cog"></i> <?php echo translate('general_settings'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('saas/settings_website'); ?>"><i class="fas fa-globe"></i> <?php echo translate('website_settings'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('saas/settings_payment'); ?>"><i class="fas fa-dollar-sign"></i> <?php echo translate('payment_settings'); ?></a>
			</li>
			<li class="active">
				<a href="<?php echo base_url('saas/settings_email'); ?>"><i class="fas fa-envelope"></i> <?php echo translate('email_settings'); ?></a>
			</li>
		</ul>
	</div>

	<div class="col-md-9">
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#emailTestModal">
						<i class="fas fa-paper-plane text-primary"></i> <?php echo translate('saas_email_test'); ?>
					</button>
				</div>
				<h4 class="panel-title"><i class="fas fa-envelope-open-text"></i> SaaS Email Templates & Notifications</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover mb-none">
						<thead>
							<tr>
								<th width="50">#</th>
								<th>Template Name</th>
								<th>Subject</th>
								<th>Notification</th>
								<th width="80"><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($templates)): ?>
								<?php $cnt = 1; foreach ($templates as $tmpl): ?>
									<tr>
										<td><?php echo $cnt++; ?></td>
										<td><strong><?php echo ucwords(str_replace('_', ' ', $tmpl['name'])); ?></strong></td>
										<td><?php echo html_escape($tmpl['subject']); ?></td>
										<td>
											<?php if ($tmpl['notified'] == 1): ?>
												<span class="label label-success"><i class="fas fa-check"></i> Enabled</span>
											<?php else: ?>
												<span class="label label-danger"><i class="fas fa-times"></i> Disabled</span>
											<?php endif; ?>
										</td>
										<td>
											<button class="btn btn-default btn-circle icon" onclick="editTemplateModal(<?php echo $tmpl['id']; ?>, '<?php echo html_escape(addslashes($tmpl['subject'])); ?>', <?php echo $tmpl['notified']; ?>, '<?php echo html_escape(addslashes($tmpl['tags'])); ?>')" data-toggle="tooltip" title="<?php echo translate('edit'); ?>">
												<i class="fas fa-pen-nib"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="5" class="text-center"><?php echo translate('no_information_available'); ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- SaaS Email Test Modal -->
<div class="modal fade" id="emailTestModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('saas/email_test'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fas fa-paper-plane text-primary"></i> <?php echo translate('saas_email_test'); ?></h4>
				</div>
				<div class="modal-body">
					<p class="text-muted">Enter a test recipient email address to send a verification message and confirm your school ERP's email delivery system is functioning properly.</p>
					<div class="form-group mb-md">
						<label class="control-label">Recipient Email <span class="required">*</span></label>
						<input type="email" class="form-control" name="test_email" required placeholder="recipient@example.com" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Test Email</button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<!-- Edit Email Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open('saas/settings_email'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fas fa-edit"></i> Edit Email Template</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="template_id" id="edit_template_id" value="" />
					<div class="form-group mb-md">
						<label class="control-label">Email Subject <span class="required">*</span></label>
						<input type="text" class="form-control" name="subject" id="edit_template_subject" required />
					</div>
					<div class="form-group mb-md">
						<label class="control-label">Notification Status</label>
						<div class="checkbox-custom checkbox-default">
							<input type="checkbox" name="notified" id="edit_template_notified" value="1" />
							<label for="edit_template_notified">Send automatic notification for this event</label>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="control-label">Allowed Tags</label>
						<p id="edit_template_tags" class="text-primary font-weight-bold"></p>
					</div>
					<div class="form-group mb-md">
						<label class="control-label">Template Body <span class="required">*</span></label>
						<textarea class="form-control" name="template_body" id="edit_template_body" rows="6" required></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="submit" value="save_template" class="btn btn-primary"><i class="fas fa-check"></i> <?php echo translate('save'); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
function editTemplateModal(id, subject, notified, tags) {
	$('#edit_template_id').val(id);
	$('#edit_template_subject').val(subject);
	if (notified == 1) {
		$('#edit_template_notified').prop('checked', true);
	} else {
		$('#edit_template_notified').prop('checked', false);
	}
	$('#edit_template_tags').text(tags);
	$('#edit_template_body').val('Loading...');
	// Simple default template body placeholder
	$('#edit_template_body').val('<p>Hello {admin_name}, this is an automated notification regarding your school {school_name}. Reference: {reference_no}</p>');
	$('#editTemplateModal').modal('show');
}
</script>

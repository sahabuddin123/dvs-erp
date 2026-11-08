<div class="row">
	<!-- Left: Custom Domain Request & Status -->
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fab fa-wikipedia-w text-primary"></i> <?php echo translate('custom_domain'); ?></h4>
			</header>
			<div class="panel-body">
				<?php if (!empty($my_request)): ?>
					<div class="well well-sm">
						<h4><strong>Requested Domain:</strong> <a href="http://<?php echo html_escape($my_request['url']); ?>" target="_blank" class="text-primary"><?php echo html_escape($my_request['url']); ?> <i class="fas fa-external-link-alt"></i></a></h4>
						<p><strong>Request Date:</strong> <?php echo date('d.M.Y h:i A', strtotime($my_request['request_date'])); ?></p>
						<p>
							<strong>Current Status:</strong> 
							<?php if ($my_request['status'] == 1): ?>
								<span class="label label-success"><i class="fas fa-check"></i> <?php echo translate('approved'); ?></span>
							<?php elseif ($my_request['status'] == 2): ?>
								<span class="label label-danger"><i class="fas fa-times"></i> <?php echo translate('rejected'); ?></span>
							<?php else: ?>
								<span class="label label-warning"><i class="fas fa-clock"></i> <?php echo translate('pending'); ?></span>
							<?php endif; ?>
						</p>
						<?php if (!empty($my_request['comments'])): ?>
							<p><strong>Admin Remarks:</strong> <?php echo html_escape($my_request['comments']); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php echo form_open('custom_domain/mylist', array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label">Your Custom Domain <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="url" value="<?php echo !empty($my_request['url']) ? html_escape($my_request['url']) : ''; ?>" placeholder="e.g. school.yourdomain.com" required />
							<small class="text-muted">Enter your domain without http:// or https:// (e.g., myschool.edu.bd)</small>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-8 col-md-offset-3">
							<button type="submit" name="submit" value="request" class="btn btn-primary">
								<i class="fas fa-paper-plane"></i> <?php echo !empty($my_request) ? 'Update Domain Request' : 'Submit Domain Request'; ?>
							</button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</section>
	</div>

	<!-- Right: DNS Setup Instructions -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-info-circle text-info"></i> <?php echo !empty($instruction['dns_title']) ? html_escape($instruction['dns_title']) : 'DNS Setup Instructions'; ?></h4>
			</header>
			<div class="panel-body">
				<?php echo $instruction['instruction']; ?>

				<?php if (!empty($instruction['dns_status'])): ?>
					<div class="table-responsive mt-md">
						<table class="table table-bordered table-striped mb-none">
							<thead>
								<tr>
									<th>Type</th>
									<th>Host</th>
									<th>Points To / Value</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($instruction['dns_type_1'])): ?>
									<tr>
										<td><span class="label label-info"><?php echo html_escape($instruction['dns_type_1']); ?></span></td>
										<td><code><?php echo html_escape($instruction['dns_host_1']); ?></code></td>
										<td><code><?php echo html_escape($instruction['dns_value_1']); ?></code></td>
									</tr>
								<?php endif; ?>
								<?php if (!empty($instruction['dns_type_2'])): ?>
									<tr>
										<td><span class="label label-primary"><?php echo html_escape($instruction['dns_type_2']); ?></span></td>
										<td><code><?php echo html_escape($instruction['dns_host_2']); ?></code></td>
										<td><code><?php echo html_escape($instruction['dns_value_2']); ?></code></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
</div>


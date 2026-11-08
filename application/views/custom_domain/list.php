<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="<?php echo base_url('custom_domain/dns_instruction'); ?>" class="btn btn-default btn-xs">
						<i class="fas fa-network-wired"></i> <?php echo translate('dns_instruction'); ?>
					</a>
				</div>
				<h4 class="panel-title"><i class="fab fa-wikipedia-w"></i> <?php echo translate('custom_domain'); ?> - All Requests</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th width="40"><?php echo translate('sl'); ?></th>
								<th><?php echo translate('school_name'); ?></th>
								<th><?php echo translate('domain_url') ?? 'Custom Domain URL'; ?></th>
								<th><?php echo translate('request_date'); ?></th>
								<th><?php echo translate('approved_date'); ?></th>
								<th><?php echo translate('status'); ?></th>
								<th><?php echo translate('comments'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$count = 1;
							if (!empty($domain_requests)):
								foreach ($domain_requests as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td>
									<strong><?php echo html_escape($row['school_name']); ?></strong><br>
									<small class="text-muted"><?php echo html_escape($row['school_email']); ?></small>
								</td>
								<td><a href="http://<?php echo html_escape($row['url']); ?>" target="_blank" class="text-primary font-weight-bold"><?php echo html_escape($row['url']); ?> <i class="fas fa-external-link-alt"></i></a></td>
								<td><?php echo !empty($row['request_date']) ? date('d.M.Y h:i A', strtotime($row['request_date'])) : '-'; ?></td>
								<td><?php echo !empty($row['approved_date']) ? date('d.M.Y h:i A', strtotime($row['approved_date'])) : '-'; ?></td>
								<td>
									<?php if ($row['status'] == 1): ?>
										<span class="label label-success"><i class="fas fa-check"></i> <?php echo translate('approved'); ?></span>
									<?php elseif ($row['status'] == 2): ?>
										<span class="label label-danger"><i class="fas fa-times"></i> <?php echo translate('rejected'); ?></span>
									<?php else: ?>
										<span class="label label-warning"><i class="fas fa-clock"></i> <?php echo translate('pending'); ?></span>
									<?php endif; ?>
								</td>
								<td><small><?php echo html_escape($row['comments']); ?></small></td>
								<td class="action">
									<button class="btn btn-default btn-circle icon" onclick="reviewDomainModal(<?php echo $row['id']; ?>, '<?php echo html_escape(addslashes($row['url'])); ?>', <?php echo $row['status']; ?>, '<?php echo html_escape(addslashes($row['comments'])); ?>')" data-toggle="tooltip" title="Review Request">
										<i class="fas fa-edit"></i>
									</button>
									<a href="<?php echo base_url('custom_domain/delete/' . $row['id']); ?>" class="btn btn-danger btn-circle icon" onclick="return confirm('Delete this custom domain record?');" data-toggle="tooltip" title="<?php echo translate('delete'); ?>">
										<i class="fas fa-trash-alt"></i>
									</a>
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

<!-- Review Domain Modal -->
<div class="modal fade" id="reviewDomainModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('custom_domain/status_update'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fab fa-wikipedia-w text-primary"></i> Review Custom Domain: <span id="rev_domain_url"></span></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="request_id" id="rev_request_id" value="" />
					<div class="form-group mb-md">
						<label class="control-label"><?php echo translate('status'); ?> <span class="required">*</span></label>
						<select name="status" id="rev_status" class="form-control" required>
							<option value="0"><?php echo translate('pending'); ?></option>
							<option value="1"><?php echo translate('approved'); ?></option>
							<option value="2"><?php echo translate('rejected'); ?></option>
						</select>
					</div>
					<div class="form-group mb-md">
						<label class="control-label"><?php echo translate('comments'); ?></label>
						<textarea name="comments" id="rev_comments" class="form-control" rows="3" placeholder="Notes for the school admin..."></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> <?php echo translate('save'); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
function reviewDomainModal(id, url, status, comments) {
	$('#rev_request_id').val(id);
	$('#rev_domain_url').text(url);
	$('#rev_status').val(status);
	$('#rev_comments').val(comments);
	$('#reviewDomainModal').modal('show');
}
</script>

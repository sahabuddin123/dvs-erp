<?php if (is_superadmin_loggedin()): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; ?>

<!-- Stats Row -->
<div class="row">
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fas fa-inbox"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?php echo translate('total_messages'); ?></h4>
							<div class="info">
								<strong class="amount"><?php echo $total_count; ?></strong>
							</div>
						</div>
						<div class="summary-footer">
							<a href="<?php echo base_url('frontend/contact_messages?status=all'); ?>" class="text-muted text-uppercase"><?php echo translate('view_all'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-danger">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-danger">
							<i class="fas fa-envelope"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?php echo translate('unread_messages'); ?></h4>
							<div class="info">
								<strong class="amount text-danger"><?php echo $unread_count; ?></strong>
							</div>
						</div>
						<div class="summary-footer">
							<a href="<?php echo base_url('frontend/contact_messages?status=unread'); ?>" class="text-danger text-uppercase"><?php echo translate('filter_unread'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-success">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-success">
							<i class="fas fa-envelope-open-text"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?php echo translate('read_messages'); ?></h4>
							<div class="info">
								<strong class="amount"><?php echo $read_count; ?></strong>
							</div>
						</div>
						<div class="summary-footer">
							<a href="<?php echo base_url('frontend/contact_messages?status=read'); ?>" class="text-muted text-uppercase"><?php echo translate('filter_read'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-warning">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-warning">
							<i class="far fa-calendar-check"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?php echo translate('today_inquiries'); ?></h4>
							<div class="info">
								<strong class="amount"><?php echo $today_count; ?></strong>
							</div>
						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase"><?php echo date('d M Y'); ?></span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<section class="panel">
	<header class="panel-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
		<div>
			<h4 class="panel-title" style="display: inline-block; margin-right: 15px;">
				<i class="fas fa-envelope-open-text"></i> <?php echo translate('contact_messages') . ' / যোগাযোগ বার্তা'; ?>
			</h4>
			<div class="btn-group" role="group">
				<a href="<?php echo base_url('frontend/contact_messages?status=all' . (isset($_GET['branch_id']) ? '&branch_id=' . $_GET['branch_id'] : '')); ?>" class="btn btn-xs <?php echo ($status_filter == 'all') ? 'btn-primary' : 'btn-default'; ?>">
					<?php echo translate('all'); ?> (<?php echo $total_count; ?>)
				</a>
				<a href="<?php echo base_url('frontend/contact_messages?status=unread' . (isset($_GET['branch_id']) ? '&branch_id=' . $_GET['branch_id'] : '')); ?>" class="btn btn-xs <?php echo ($status_filter == 'unread') ? 'btn-danger' : 'btn-default'; ?>">
					<?php echo translate('unread'); ?> (<?php echo $unread_count; ?>)
				</a>
				<a href="<?php echo base_url('frontend/contact_messages?status=read' . (isset($_GET['branch_id']) ? '&branch_id=' . $_GET['branch_id'] : '')); ?>" class="btn btn-xs <?php echo ($status_filter == 'read') ? 'btn-success' : 'btn-default'; ?>">
					<?php echo translate('read'); ?> (<?php echo $read_count; ?>)
				</a>
			</div>
		</div>
		<div>
			<a href="<?php echo base_url('frontend/section/contact' . (isset($_GET['branch_id']) ? '?branch_id=' . $_GET['branch_id'] : '')); ?>" class="btn btn-default btn-xs">
				<i class="fas fa-cog"></i> <?php echo translate('contact_page_settings'); ?>
			</a>
			<a href="<?php echo base_url('home/contact'); ?>" target="_blank" class="btn btn-info btn-xs">
				<i class="fas fa-external-link-alt"></i> <?php echo translate('view_website_contact'); ?>
			</a>
		</div>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table_default" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="40"><?php echo translate('sl'); ?></th>
						<th width="120"><?php echo translate('date_time'); ?></th>
						<th width="180"><?php echo translate('sender'); ?></th>
						<th width="130"><?php echo translate('contact'); ?></th>
						<th><?php echo translate('subject'); ?></th>
						<th><?php echo translate('message'); ?></th>
						<th width="80" class="text-center"><?php echo translate('status'); ?></th>
						<th width="100" class="text-center"><?php echo translate('action'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$count = 1;
					if (!empty($messagelist)) {
						foreach ($messagelist as $row):
							$is_unread = ($row['status'] == 0);
					?>
					<tr class="<?php echo $is_unread ? 'warning text-bold' : ''; ?>" id="msg-row-<?php echo $row['id']; ?>">
						<td><?php echo $count++; ?></td>
						<td>
							<small class="text-muted"><i class="far fa-clock"></i> <?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
							<br>
							<small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
						</td>
						<td>
							<strong style="color: #1e293b;"><?php echo html_escape($row['name']); ?></strong>
							<?php if (!empty($row['ip_address'])): ?>
								<br><span class="label label-default" style="font-size: 9px;"><i class="fas fa-network-wired"></i> <?php echo html_escape($row['ip_address']); ?></span>
							<?php endif; ?>
						</td>
						<td>
							<?php if (!empty($row['phone'])): ?>
								<a href="tel:<?php echo html_escape($row['phone']); ?>" class="text-dark" title="<?php echo translate('call'); ?>">
									<i class="fas fa-phone-alt text-success"></i> <?php echo html_escape($row['phone']); ?>
								</a>
								<br>
							<?php endif; ?>
							<?php if (!empty($row['email'])): ?>
								<a href="mailto:<?php echo html_escape($row['email']); ?>" class="text-muted small" title="<?php echo translate('send_email'); ?>">
									<i class="fas fa-envelope text-primary"></i> <?php echo html_escape($row['email']); ?>
								</a>
							<?php endif; ?>
						</td>
						<td>
							<strong><?php echo html_escape($row['subject']); ?></strong>
						</td>
						<td>
							<div style="max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo html_escape($row['message']); ?>">
								<?php echo html_escape($row['message']); ?>
							</div>
						</td>
						<td class="text-center" id="status-cell-<?php echo $row['id']; ?>">
							<?php if ($is_unread): ?>
								<span class="label label-danger"><i class="fas fa-envelope"></i> <?php echo translate('unread'); ?></span>
							<?php else: ?>
								<span class="label label-success"><i class="fas fa-envelope-open"></i> <?php echo translate('read'); ?></span>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-circle icon btn-view-msg" data-id="<?php echo $row['id']; ?>" data-toggle="tooltip" data-original-title="<?php echo translate('view_message'); ?>">
								<i class="fas fa-eye text-primary"></i>
							</button>
							<?php if ($is_unread): ?>
								<a href="<?php echo base_url('frontend/contact_messages/mark_status/' . $row['id'] . '/1'); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('mark_as_read'); ?>">
									<i class="fas fa-check text-success"></i>
								</a>
							<?php else: ?>
								<a href="<?php echo base_url('frontend/contact_messages/mark_status/' . $row['id'] . '/0'); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('mark_as_unread'); ?>">
									<i class="fas fa-envelope text-warning"></i>
								</a>
							<?php endif; ?>
							<?php echo btn_delete('frontend/contact_messages/delete/' . $row['id']); ?>
						</td>
					</tr>
					<?php 
						endforeach; 
					} 
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<!-- View Message Modal -->
<div class="modal fade" id="messageDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary" style="color: #fff;">
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" style="opacity: 1; color: #fff;">&times;</button>
				<h4 class="modal-title" style="color: #fff;"><i class="fas fa-envelope-open-text"></i> <?php echo translate('message_details'); ?></h4>
			</div>
			<div class="modal-body" id="modalMessageContent">
				<div class="text-center py-4" id="modalLoader">
					<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
					<p class="mt-2 text-muted"><?php echo translate('loading'); ?>...</p>
				</div>
				<div id="modalDataContainer" style="display: none;">
					<div class="row mb-md" style="background: #f8fafc; padding: 15px; border-radius: 8px; margin: 0 0 15px 0;">
						<div class="col-md-7">
							<h4 class="mt-none mb-xs" id="m_name" style="font-weight: 700; color: #1e293b;"></h4>
							<p class="mb-xs">
								<i class="fas fa-envelope text-primary"></i> <strong id="m_email"></strong> 
								<a href="#" id="m_email_link" class="btn btn-xs btn-primary ml-xs" target="_blank"><i class="fas fa-reply"></i> <?php echo translate('reply_email'); ?></a>
							</p>
							<p class="mb-none">
								<i class="fas fa-phone-alt text-success"></i> <strong id="m_phone"></strong>
								<a href="#" id="m_phone_link" class="btn btn-xs btn-success ml-xs"><i class="fas fa-phone"></i> <?php echo translate('call'); ?></a>
							</p>
						</div>
						<div class="col-md-5 text-right">
							<span class="label label-info" id="m_date" style="font-size: 11px;"></span>
							<div class="mt-xs text-muted" style="font-size: 11px;">
								<i class="fas fa-network-wired"></i> IP: <span id="m_ip"></span>
							</div>
						</div>
					</div>

					<div class="form-group mb-md">
						<label class="control-label" style="font-weight: 700; color: #475569;"><?php echo translate('subject'); ?>:</label>
						<div class="well well-sm" id="m_subject" style="font-size: 15px; font-weight: 600; background: #fff; border-left: 4px solid var(--primary-color, #0284c7);"></div>
					</div>

					<div class="form-group">
						<label class="control-label" style="font-weight: 700; color: #475569;"><?php echo translate('message'); ?>:</label>
						<div class="well" id="m_message" style="background: #ffffff; min-height: 140px; font-size: 14px; line-height: 1.7; border-left: 4px solid #10b981; white-space: pre-wrap;"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" id="modalReplyBtn" class="btn btn-primary" target="_blank"><i class="fas fa-reply"></i> <?php echo translate('reply'); ?></a>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('.btn-view-msg').on('click', function(e) {
		e.preventDefault();
		var msgId = $(this).data('id');
		var modal = $('#messageDetailsModal');
		$('#modalLoader').show();
		$('#modalDataContainer').hide();
		modal.modal('show');

		$.ajax({
			url: '<?php echo base_url("frontend/contact_messages/get_details/"); ?>' + msgId,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status === 'success') {
					var d = res.data;
					$('#m_name').text(d.name);
					$('#m_email').text(d.email);
					$('#m_phone').text(d.phone);
					$('#m_date').html('<i class="far fa-clock"></i> ' + d.formatted_date);
					$('#m_ip').text(d.safe_ip);
					$('#m_subject').text(d.subject);
					$('#m_message').html(d.safe_message);

					$('#m_email_link').attr('href', 'mailto:' + encodeURIComponent(d.email) + '?subject=' + encodeURIComponent('Re: ' + d.subject));
					$('#m_phone_link').attr('href', 'tel:' + encodeURIComponent(d.phone));
					$('#modalReplyBtn').attr('href', 'mailto:' + encodeURIComponent(d.email) + '?subject=' + encodeURIComponent('Re: ' + d.subject));

					$('#modalLoader').hide();
					$('#modalDataContainer').fadeIn();

					// Update UI row status to read
					var row = $('#msg-row-' + msgId);
					row.removeClass('warning text-bold');
					$('#status-cell-' + msgId).html('<span class="label label-success"><i class="fas fa-envelope-open"></i> <?php echo translate("read"); ?></span>');
				} else {
					$('#modalLoader').html('<p class="text-danger">' + res.message + '</p>');
				}
			},
			error: function() {
				$('#modalLoader').html('<p class="text-danger">Failed to load message details.</p>');
			}
		});
	});
});
</script>

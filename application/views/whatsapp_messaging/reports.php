<div class="row">
	<div class="col-md-12">
		<!-- Search Filter -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter_search')?></h4>
			</header>
			<?php echo form_open('whatsapp_messaging/reports', ['method' => 'GET']); ?>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4 mb-sm">
							<label class="control-label"><?=translate('recipient_type')?></label>
							<select class="form-control" name="role" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('all_roles')?></option>
								<option value="student" <?=(isset($filters['role']) && $filters['role'] == 'student') ? 'selected' : ''?>><?=translate('student')?></option>
								<option value="parent" <?=(isset($filters['role']) && $filters['role'] == 'parent') ? 'selected' : ''?>><?=translate('parents')?></option>
								<option value="staff" <?=(isset($filters['role']) && $filters['role'] == 'staff') ? 'selected' : ''?>><?=translate('employee')?></option>
								<option value="custom" <?=(isset($filters['role']) && $filters['role'] == 'custom') ? 'selected' : ''?>><?=translate('custom_numbers')?></option>
							</select>
						</div>
						<div class="col-md-4 mb-sm">
							<label class="control-label"><?=translate('date_from')?></label>
							<input type="text" class="form-control" name="date_from" data-plugin-datepicker value="<?=isset($filters['date_from']) ? html_escape($filters['date_from']) : ''?>" autocomplete="off" />
						</div>
						<div class="col-md-4 mb-sm">
							<label class="control-label"><?=translate('date_to')?></label>
							<input type="text" class="form-control" name="date_to" data-plugin-datepicker value="<?=isset($filters['date_to']) ? html_escape($filters['date_to']) : ''?>" autocomplete="off" />
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<button type="submit" class="btn btn-default btn-sm"><i class="fas fa-search"></i> <?=translate('filter')?></button>
					<a href="<?=base_url('whatsapp_messaging/reports')?>" class="btn btn-light btn-sm"><?=translate('reset')?></a>
				</footer>
			<?php echo form_close(); ?>
		</section>

		<!-- Log Table -->
		<section class="panel">
			<header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="panel-title"><i class="fas fa-history"></i> <?=translate('whatsapp_dispatch_logs')?></h4>
				<a href="<?=base_url('whatsapp_messaging/send')?>" class="btn btn-default btn-xs"><i class="fas fa-paper-plane"></i> <?=translate('send_message')?></a>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th>#</th>
								<th><?=translate('recipient')?></th>
								<th><?=translate('role')?></th>
								<th><?=translate('mobile_no')?></th>
								<th><?=translate('message')?></th>
								<th><?=translate('gateway_medium')?></th>
								<th><?=translate('status')?></th>
								<th><?=translate('date_time')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if (!empty($logs)):
								foreach ($logs as $log):
									$wa_url = 'https://api.whatsapp.com/send?phone=' . rawurlencode($log['recipient_mobile']) . '&text=' . rawurlencode($log['message']);
							?>
							<tr>
								<td><?=$i++?></td>
								<td><strong><?=html_escape($log['recipient_name'])?></strong></td>
								<td><span class="label label-primary"><?=ucfirst(html_escape($log['recipient_role']))?></span></td>
								<td><code><?=html_escape($log['recipient_mobile'])?></code></td>
								<td>
									<div style="max-width: 380px; font-size: 12.5px; white-space: pre-wrap; color: #334155;"><?=html_escape($log['message'])?></div>
								</td>
								<td>
									<?php if ($log['gateway_type'] == 'custom_api'): ?>
										<span class="badge badge-info"><i class="fas fa-cloud"></i> API</span>
									<?php else: ?>
										<span class="badge badge-success"><i class="fab fa-whatsapp"></i> Web</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($log['status'] == 'sent' || $log['status'] == 'delivered'): ?>
										<span class="label label-success"><i class="fas fa-check"></i> <?=translate('sent')?></span>
									<?php elseif ($log['status'] == 'failed'): ?>
										<span class="label label-danger"><i class="fas fa-times"></i> <?=translate('failed')?></span>
									<?php else: ?>
										<span class="label label-warning"><i class="fas fa-clock"></i> <?=translate('ready')?></span>
									<?php endif; ?>
								</td>
								<td><?=_d($log['created_at']) . ' ' . date('h:i A', strtotime($log['created_at']))?></td>
								<td class="text-center">
									<a href="<?=$wa_url?>" target="_blank" class="btn btn-default btn-circle btn-xs text-success" title="WhatsApp" data-toggle="tooltip">
										<i class="fab fa-whatsapp"></i>
									</a>
								</td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="9" class="text-center text-muted"><?=translate('no_information_available')?></td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>


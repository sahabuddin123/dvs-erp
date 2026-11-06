<div class="row">
	<div class="col-md-12">
		<!-- Search Filter -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-filter"></i> ফিল্টার ও অনুসন্ধান</h4>
			</header>
			<?php echo form_open('whatsapp_messaging/reports', ['method' => 'GET']); ?>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4 mb-sm">
							<label class="control-label">প্রাপকের ধরন (Role)</label>
							<select class="form-control" name="role" data-plugin-selectTwo data-width="100%">
								<option value="">-- সকল রোল --</option>
								<option value="student" <?=(isset($filters['role']) && $filters['role'] == 'student') ? 'selected' : ''?>>শিক্ষার্থী (Student)</option>
								<option value="parent" <?=(isset($filters['role']) && $filters['role'] == 'parent') ? 'selected' : ''?>>অভিভাবক (Parent)</option>
								<option value="staff" <?=(isset($filters['role']) && $filters['role'] == 'staff') ? 'selected' : ''?>>শিক্ষক/কর্মচারী (Staff)</option>
								<option value="custom" <?=(isset($filters['role']) && $filters['role'] == 'custom') ? 'selected' : ''?>>কাস্টম নম্বর (Custom)</option>
							</select>
						</div>
						<div class="col-md-4 mb-sm">
							<label class="control-label">শুরুর তারিখ</label>
							<input type="text" class="form-control" name="date_from" data-plugin-datepicker value="<?=isset($filters['date_from']) ? html_escape($filters['date_from']) : ''?>" autocomplete="off" />
						</div>
						<div class="col-md-4 mb-sm">
							<label class="control-label">শেষের তারিখ</label>
							<input type="text" class="form-control" name="date_to" data-plugin-datepicker value="<?=isset($filters['date_to']) ? html_escape($filters['date_to']) : ''?>" autocomplete="off" />
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<button type="submit" class="btn btn-default btn-sm"><i class="fas fa-search"></i> ফিল্টার করুন</button>
					<a href="<?=base_url('whatsapp_messaging/reports')?>" class="btn btn-light btn-sm">রিসেট</a>
				</footer>
			<?php echo form_close(); ?>
		</section>

		<!-- Log Table -->
		<section class="panel">
			<header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="panel-title"><i class="fas fa-history"></i> WhatsApp বার্তা প্রেরণ লগ ও রিপোর্ট</h4>
				<a href="<?=base_url('whatsapp_messaging/send')?>" class="btn btn-default btn-xs"><i class="fas fa-paper-plane"></i> নতুন বার্তা পাঠান</a>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th>#</th>
								<th>প্রাপক</th>
								<th>ভূমিকা / রোল</th>
								<th>WhatsApp নম্বর</th>
								<th>বার্তা</th>
								<th>মাধ্যম</th>
								<th>স্ট্যাটাস</th>
								<th>তারিখ ও সময়</th>
								<th>অ্যাকশন</th>
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
										<span class="label label-success"><i class="fas fa-check"></i> পাঠানো হয়েছে</span>
									<?php elseif ($log['status'] == 'failed'): ?>
										<span class="label label-danger"><i class="fas fa-times"></i> ব্যর্থ</span>
									<?php else: ?>
										<span class="label label-warning"><i class="fas fa-clock"></i> প্রস্তুত</span>
									<?php endif; ?>
								</td>
								<td><?=_d($log['created_at']) . ' ' . date('h:i A', strtotime($log['created_at']))?></td>
								<td class="text-center">
									<a href="<?=$wa_url?>" target="_blank" class="btn btn-default btn-circle btn-xs text-success" title="WhatsApp-এ পুনরায় খুলুন">
										<i class="fab fa-whatsapp"></i>
									</a>
								</td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="9" class="text-center text-muted">কোনো বার্তা লগ পাওয়া যায়নি।</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>


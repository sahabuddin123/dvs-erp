<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-history"></i> <?=translate('two_factor_authentication')?> - <?=translate('activity_logs')?> / লগ রিপোর্ট</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th>#</th>
								<th><?=translate('user')?> / ব্যবহারকারী</th>
								<th><?=translate('role')?> / রোল</th>
								<th><?=translate('method')?></th>
								<th>প্রেরিত ঠিকানা / নম্বর</th>
								<th>সিকিউরিটি কোড</th>
								<th><?=translate('status')?> / স্ট্যাটাস</th>
								<th>আইপি অ্যাড্রেস</th>
								<th>ইউজার এজেন্ট / ব্রাউজার</th>
								<th><?=translate('date')?> ও সময়</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if (!empty($logs)):
								foreach ($logs as $log):
									$is_expired = strtotime($log['expires_at']) < time();
							?>
							<tr>
								<td><?=$i++?></td>
								<td><strong><?=(!empty($log['username']) ? html_escape($log['username']) : 'UID #' . $log['user_id'])?></strong></td>
								<td><span class="label label-primary"><?=(!empty($log['role_name']) ? $log['role_name'] : 'N/A')?></span></td>
								<td>
									<?php if ($log['auth_method'] == 'sms'): ?>
										<span class="badge badge-warning"><i class="fas fa-sms"></i> SMS</span>
									<?php elseif ($log['auth_method'] == 'both'): ?>
										<span class="badge badge-info"><i class="fas fa-bell"></i> Both</span>
									<?php else: ?>
										<span class="badge badge-primary"><i class="fas fa-envelope"></i> Email</span>
									<?php endif; ?>
								</td>
								<td>
									<?php 
									$contact = !empty($log['email']) ? $log['email'] : (!empty($log['mobile']) ? $log['mobile'] : '-');
									echo html_escape($contact);
									?>
								</td>
								<td><code><?=html_escape($log['code'])?></code></td>
								<td>
									<?php if ($log['is_used'] == 1): ?>
										<span class="label label-success"><i class="fas fa-check"></i> যাচাইকৃত (Verified)</span>
									<?php elseif ($is_expired): ?>
										<span class="label label-danger"><i class="fas fa-clock"></i> মেয়াদোত্তীর্ণ (Expired)</span>
									<?php else: ?>
										<span class="label label-warning"><i class="fas fa-hourglass-half"></i> অপেক্ষমাণ (Pending)</span>
									<?php endif; ?>
								</td>
								<td><small><?=html_escape($log['ip_address'])?></small></td>
								<td><small style="max-width: 200px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?=html_escape($log['user_agent'])?></small></td>
								<td><?=_d($log['created_at']) . ' ' . date('h:i A', strtotime($log['created_at']))?></td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="10" class="text-center text-muted">কোনো ভেরিফিকেশন লগ পাওয়া যায়নি।</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

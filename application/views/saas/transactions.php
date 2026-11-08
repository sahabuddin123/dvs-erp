<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-file-invoice-dollar"></i> <?php echo translate('subscription_transactions_history'); ?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th width="40"><?php echo translate('sl'); ?></th>
								<th><?php echo translate('trx_id') ?? 'Trx ID'; ?></th>
								<th><?php echo translate('school_name'); ?></th>
								<th><?php echo translate('plan_name'); ?></th>
								<th><?php echo translate('amount'); ?></th>
								<th><?php echo translate('discount'); ?></th>
								<th><?php echo translate('payment_method'); ?></th>
								<th><?php echo translate('purchase_date') ?? 'Purchase Date'; ?></th>
								<th><?php echo translate('expiry_date'); ?></th>
								<th><?php echo translate('type'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$count = 1;
							$methods = array(1 => 'System / Offline', 2 => 'bKash', 3 => 'Nagad', 4 => 'Paytm', 5 => 'Stripe', 6 => 'PayPal');
							if (!empty($transactions)):
								foreach ($transactions as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><code><?php echo html_escape($row['payment_id']); ?></code></td>
								<td>
									<strong><?php echo html_escape($row['school_name']); ?></strong><br>
									<small class="text-muted"><?php echo html_escape($row['school_email']); ?></small>
								</td>
								<td><span class="label label-primary"><?php echo !empty($row['plan_name']) ? html_escape($row['plan_name']) : 'N/A'; ?></span></td>
								<td>$<?php echo number_format($row['amount'], 2); ?></td>
								<td>$<?php echo number_format($row['discount'], 2); ?></td>
								<td><span class="label label-default"><?php echo isset($methods[$row['payment_method']]) ? $methods[$row['payment_method']] : 'Online / Gateway'; ?></span></td>
								<td><?php echo !empty($row['purchase_date']) ? date('d.M.Y', strtotime($row['purchase_date'])) : '-'; ?></td>
								<td><?php echo !empty($row['expire_date']) ? date('d.M.Y', strtotime($row['expire_date'])) : '<span class="text-success">Lifetime</span>'; ?></td>
								<td>
									<?php if ($row['renew'] == 1): ?>
										<span class="label label-info"><i class="fas fa-sync-alt"></i> Renew</span>
									<?php else: ?>
										<span class="label label-success"><i class="fas fa-plus"></i> New</span>
									<?php endif; ?>
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
		</section>
	</div>
</div>

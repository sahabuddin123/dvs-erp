<div class="row">
	<!-- Left Column: Current Subscription & Limit Usage -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#plans" class="btn btn-default btn-xs" onclick="$('a[href=\'#available_plans\']').tab('show');">
						<i class="fas fa-arrow-up text-success"></i> <?php echo translate('upgrade_plan'); ?>
					</a>
				</div>
				<h4 class="panel-title"><i class="icons icon-directions text-primary"></i> Current Subscription</h4>
			</header>
			<div class="panel-body">
				<?php if (!empty($subscription)): ?>
					<div class="well well-sm text-center" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
						<h3 style="margin-top: 5px; color: #1e293b; font-weight: bold;"><?php echo html_escape($subscription['plan_name']); ?></h3>
						<p class="text-muted" style="margin-bottom: 5px;">
							<?php echo $subscription['price'] > 0 ? '$' . number_format($subscription['price'], 2) : 'Free Package'; ?>
						</p>
						<span class="badge" style="background: #16a34a; font-size: 13px; padding: 6px 12px;">
							<i class="fas fa-calendar-alt"></i> Expiry: <?php echo ($subscription['expire_date'] == 'Lifetime') ? 'Lifetime' : (!empty($subscription['expire_date']) ? date('d M Y', strtotime($subscription['expire_date'])) : 'Lifetime'); ?>
						</span>
					</div>

					<!-- Limits Usage Progress Bars -->
					<h5 class="text-weight-semibold mt-lg"><i class="fas fa-chart-pie text-info"></i> Resource Limits & Utilization</h5>
					
					<!-- Students Limit -->
					<?php 
					$stu_limit = (float)$subscription['student_limit'];
					$stu_used = (int)$usage['students'];
					$stu_pct = $stu_limit > 0 ? min(100, round(($stu_used / $stu_limit) * 100)) : 0;
					?>
					<div class="mb-md">
						<div class="row mb-xs">
							<div class="col-xs-6"><strong>Students:</strong></div>
							<div class="col-xs-6 text-right"><?php echo $stu_used; ?> / <?php echo $stu_limit; ?> (<?php echo $stu_pct; ?>%)</div>
						</div>
						<div class="progress progress-sm mb-none">
							<div class="progress-bar progress-bar-<?php echo $stu_pct >= 90 ? 'danger' : 'info'; ?>" style="width: <?php echo $stu_pct; ?>%;"></div>
						</div>
					</div>

					<!-- Teachers Limit -->
					<?php 
					$tch_limit = (float)$subscription['teacher_limit'];
					$tch_used = (int)$usage['teachers'];
					$tch_pct = $tch_limit > 0 ? min(100, round(($tch_used / $tch_limit) * 100)) : 0;
					?>
					<div class="mb-md">
						<div class="row mb-xs">
							<div class="col-xs-6"><strong>Teachers:</strong></div>
							<div class="col-xs-6 text-right"><?php echo $tch_used; ?> / <?php echo $tch_limit; ?> (<?php echo $tch_pct; ?>%)</div>
						</div>
						<div class="progress progress-sm mb-none">
							<div class="progress-bar progress-bar-<?php echo $tch_pct >= 90 ? 'danger' : 'success'; ?>" style="width: <?php echo $tch_pct; ?>%;"></div>
						</div>
					</div>

					<!-- Staff Limit -->
					<?php 
					$stf_limit = (float)$subscription['staff_limit'];
					$stf_used = (int)$usage['staff'];
					$stf_pct = $stf_limit > 0 ? min(100, round(($stf_used / $stf_limit) * 100)) : 0;
					?>
					<div class="mb-md">
						<div class="row mb-xs">
							<div class="col-xs-6"><strong>Staff:</strong></div>
							<div class="col-xs-6 text-right"><?php echo $stf_used; ?> / <?php echo $stf_limit; ?> (<?php echo $stf_pct; ?>%)</div>
						</div>
						<div class="progress progress-sm mb-none">
							<div class="progress-bar progress-bar-<?php echo $stf_pct >= 90 ? 'danger' : 'primary'; ?>" style="width: <?php echo $stf_pct; ?>%;"></div>
						</div>
					</div>

					<!-- Parents Limit -->
					<?php 
					$prt_limit = (float)$subscription['parents_limit'];
					$prt_used = (int)$usage['parents'];
					$prt_pct = $prt_limit > 0 ? min(100, round(($prt_used / $prt_limit) * 100)) : 0;
					?>
					<div class="mb-md">
						<div class="row mb-xs">
							<div class="col-xs-6"><strong>Parents:</strong></div>
							<div class="col-xs-6 text-right"><?php echo $prt_used; ?> / <?php echo $prt_limit; ?> (<?php echo $prt_pct; ?>%)</div>
						</div>
						<div class="progress progress-sm mb-none">
							<div class="progress-bar progress-bar-<?php echo $prt_pct >= 90 ? 'danger' : 'warning'; ?>" style="width: <?php echo $prt_pct; ?>%;"></div>
						</div>
					</div>
				<?php else: ?>
					<div class="alert alert-info">No active subscription found. Please select a plan from the list.</div>
				<?php endif; ?>
			</div>
		</section>
	</div>

	<!-- Right Column: Available Plans & Transaction History -->
	<div class="col-md-7">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#available_plans" data-toggle="tab"><i class="fas fa-cubes"></i> Available Packages</a>
					</li>
					<li>
						<a href="#history" data-toggle="tab"><i class="fas fa-history"></i> Payment History</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="available_plans" class="tab-pane active">
						<div class="row">
							<?php if (!empty($packages)): ?>
								<?php foreach ($packages as $pkg): ?>
									<div class="col-md-6 mb-lg">
										<div class="pricing-table" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; text-align: center; position: relative; background: #fff;">
											<?php if ($pkg['recommended'] == 1): ?>
												<span class="badge" style="position: absolute; top: 10px; right: 10px; background: #3b82f6;"><i class="fas fa-star"></i> Recommended</span>
											<?php endif; ?>
											<h4 style="font-weight: bold; color: #1e293b;"><?php echo html_escape($pkg['name']); ?></h4>
											<h2 style="color: #0284c7; font-weight: 800; margin: 15px 0;">$<?php echo number_format($pkg['price'], 2); ?></h2>
											<?php if ($pkg['discount'] > 0): ?>
												<p class="text-danger"><small>Discount: $<?php echo number_format($pkg['discount'], 2); ?></small></p>
											<?php endif; ?>
											<ul class="list-unstyled text-left" style="margin: 15px 0; font-size: 13px; line-height: 2;">
												<li><i class="fas fa-check text-success"></i> Students: <strong><?php echo $pkg['student_limit']; ?></strong></li>
												<li><i class="fas fa-check text-success"></i> Teachers: <strong><?php echo $pkg['teacher_limit']; ?></strong></li>
												<li><i class="fas fa-check text-success"></i> Staff: <strong><?php echo $pkg['staff_limit']; ?></strong></li>
												<li><i class="fas fa-check text-success"></i> Parents: <strong><?php echo $pkg['parents_limit']; ?></strong></li>
												<li><i class="fas fa-check text-success"></i> Period: <strong><?php echo ($pkg['period_type'] == 1) ? 'Lifetime' : ($pkg['period_value'] . ' Period'); ?></strong></li>
											</ul>
											<a href="<?php echo base_url('subscription/payment/' . $pkg['id']); ?>" class="btn btn-primary btn-block">
												<i class="fas fa-shopping-cart"></i> Choose Plan
											</a>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>

					<div id="history" class="tab-pane">
						<div class="table-responsive">
							<table class="table table-bordered table-hover mb-none">
								<thead>
									<tr>
										<th>Trx ID</th>
										<th>Plan</th>
										<th>Amount</th>
										<th>Date</th>
										<th>Type</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($transactions)): ?>
										<?php foreach ($transactions as $t): ?>
											<tr>
												<td><code><?php echo html_escape($t['payment_id']); ?></code></td>
												<td><span class="label label-primary"><?php echo html_escape($t['plan_name']); ?></span></td>
												<td>$<?php echo number_format($t['amount'], 2); ?></td>
												<td><?php echo date('d M Y', strtotime($t['purchase_date'])); ?></td>
												<td><?php echo $t['renew'] == 1 ? '<span class="label label-info">Renew</span>' : '<span class="label label-success">New</span>'; ?></td>
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
				</div>
			</div>
		</section>
	</div>
</div>

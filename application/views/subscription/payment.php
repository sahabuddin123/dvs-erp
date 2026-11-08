<?php
$net_amount = max(0, (float)$package['price'] - (float)$package['discount']);
?>
<div class="row">
	<!-- Left: Order Summary -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-shopping-basket"></i> Order Summary</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered mb-none">
						<tbody>
							<tr>
								<td><strong>Selected Plan</strong></td>
								<td><span class="label label-primary font-weight-bold"><?php echo html_escape($package['name']); ?></span></td>
							</tr>
							<tr>
								<td><strong>Original Price</strong></td>
								<td>$<?php echo number_format($package['price'], 2); ?></td>
							</tr>
							<tr>
								<td><strong>Discount</strong></td>
								<td class="text-danger">-$<?php echo number_format($package['discount'], 2); ?></td>
							</tr>
							<tr style="background: #f8fafc; font-size: 16px;">
								<td><strong>Net Amount Payable</strong></td>
								<td><strong class="text-success">$<?php echo number_format($net_amount, 2); ?></strong></td>
							</tr>
							<tr>
								<td><strong>Duration</strong></td>
								<td><?php echo ($package['period_type'] == 1) ? 'Lifetime' : ($package['period_value'] . ' Period'); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="mt-md text-center">
					<a href="<?php echo base_url('subscription/index'); ?>" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Change Plan</a>
				</div>
			</div>
		</section>
	</div>

	<!-- Right: Payment Methods Selection -->
	<div class="col-md-7">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#online_gateways" data-toggle="tab"><i class="fas fa-bolt text-warning"></i> Online Payment</a>
					</li>
					<?php if (!empty($settings['offline_payments'])): ?>
					<li>
						<a href="#offline_wire" data-toggle="tab"><i class="fas fa-university text-info"></i> Offline Payment</a>
					</li>
					<?php endif; ?>
				</ul>
				<div class="tab-content">
					<!-- Online Gateways: bKash / Nagad / Paytm -->
					<div id="online_gateways" class="tab-pane active">
						<h4>Select Payment Method</h4>
						<p class="text-muted">Pay securely using your preferred payment gateway.</p>

						<div class="row mt-lg">
							<!-- bKash -->
							<div class="col-sm-4 mb-md">
								<div class="text-center" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; background: #fff;">
									<h4 style="color: #e2136e; font-weight: bold;"><i class="fas fa-money-bill-wave"></i> bKash</h4>
									<p class="text-muted"><small>Instant BKash Checkout</small></p>
									<a href="<?php echo base_url('saas_payment/checkout/bkash/' . $package['id']); ?>" class="btn btn-block" style="background: #e2136e; color: #fff; font-weight: bold;">
										Pay with bKash
									</a>
								</div>
							</div>

							<!-- Nagad -->
							<div class="col-sm-4 mb-md">
								<div class="text-center" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; background: #fff;">
									<h4 style="color: #f7941d; font-weight: bold;"><i class="fas fa-wallet"></i> Nagad</h4>
									<p class="text-muted"><small>Nagad Payment Gateway</small></p>
									<a href="<?php echo base_url('saas_payment/checkout/nagad/' . $package['id']); ?>" class="btn btn-block" style="background: #f7941d; color: #fff; font-weight: bold;">
										Pay with Nagad
									</a>
								</div>
							</div>

							<!-- Paytm -->
							<div class="col-sm-4 mb-md">
								<div class="text-center" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; background: #fff;">
									<h4 style="color: #002970; font-weight: bold;"><i class="fas fa-university"></i> Paytm</h4>
									<p class="text-muted"><small>Paytm UPI & Cards</small></p>
									<a href="<?php echo base_url('saas_payment/checkout/paytm/' . $package['id']); ?>" class="btn btn-block" style="background: #002970; color: #fff; font-weight: bold;">
										Pay with Paytm
									</a>
								</div>
							</div>
						</div>
					</div>

					<!-- Offline Payments -->
					<?php if (!empty($settings['offline_payments'])): ?>
					<div id="offline_wire" class="tab-pane">
						<h4>Submit Offline Payment Proof</h4>
						<p class="text-muted">Deposit or wire transfer your subscription fee and upload the voucher below.</p>

						<?php echo form_open_multipart('subscription/offline_payment_submit', array('class' => 'validate')); ?>
							<input type="hidden" name="package_id" value="<?php echo $package['id']; ?>" />
							<input type="hidden" name="amount" value="<?php echo $net_amount; ?>" />

							<div class="form-group mb-md">
								<label class="control-label">Payment Method / Bank <span class="required">*</span></label>
								<select name="payment_type" class="form-control" required>
									<?php if (!empty($offline_types)): ?>
										<?php foreach ($offline_types as $ot): ?>
											<option value="<?php echo $ot['id']; ?>"><?php echo html_escape($ot['name']); ?> (<?php echo html_escape($ot['note']); ?>)</option>
										<?php endforeach; ?>
									<?php else: ?>
										<option value="1">Direct Bank Wire / Cash Slip</option>
									<?php endif; ?>
								</select>
							</div>

							<div class="form-group mb-md">
								<label class="control-label">Transaction / Bank Reference No <span class="required">*</span></label>
								<input type="text" class="form-control" name="reference" required placeholder="e.g. TRX10928374 or Bank Slip Number" />
							</div>

							<div class="form-group mb-md">
								<label class="control-label">Deposit Slip / Payment Voucher (Image/PDF)</label>
								<input type="file" class="form-control" name="proof_file" />
							</div>

							<div class="form-group mb-md">
								<label class="control-label">Additional Notes</label>
								<textarea class="form-control" name="note" rows="2" placeholder="Any extra information..."></textarea>
							</div>

							<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane"></i> Submit Payment Proof</button>
						<?php echo form_close(); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<section class="panel">
			<header class="panel-heading text-center">
				<h4 class="panel-title"><i class="fas fa-credit-card"></i> Pay with <?php echo html_escape($gateway_name); ?></h4>
			</header>
			<div class="panel-body text-center" style="padding: 30px;">
				<div class="mb-lg">
					<?php if (strtolower($gateway_name) == 'bkash'): ?>
						<span style="font-size: 40px; color: #e2136e; font-weight: bold;"><i class="fas fa-money-bill-wave"></i> bKash Checkout</span>
					<?php elseif (strtolower($gateway_name) == 'nagad'): ?>
						<span style="font-size: 40px; color: #f7941d; font-weight: bold;"><i class="fas fa-wallet"></i> Nagad Pay</span>
					<?php else: ?>
						<span style="font-size: 40px; color: #002970; font-weight: bold;"><i class="fas fa-university"></i> Paytm Payment</span>
					<?php endif; ?>
				</div>

				<h3 style="margin-top: 10px; font-weight: bold;">Amount Payable: $<?php echo number_format($amount, 2); ?></h3>
				<p class="text-muted">Subscription Package: <strong><?php echo html_escape($package['name']); ?></strong></p>

				<div class="alert alert-info text-left mt-md">
					<i class="fas fa-info-circle"></i> Complete your test payment below to activate or renew your subscription instantly.
				</div>

				<?php echo form_open('saas_payment/callback/' . strtolower($gateway_name)); ?>
					<input type="hidden" name="package_id" value="<?php echo $package['id']; ?>" />
					<input type="hidden" name="school_id" value="<?php echo $school_id; ?>" />
					<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
					<input type="hidden" name="trx_id" value="<?php echo strtoupper($gateway_name) . '_' . strtoupper(uniqid()); ?>" />

					<button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 20px;">
						<i class="fas fa-lock"></i> Confirm & Pay $<?php echo number_format($amount, 2); ?>
					</button>
				<?php echo form_close(); ?>

				<div class="mt-md">
					<a href="<?php echo base_url('subscription/index'); ?>" class="btn btn-default btn-sm">Cancel Transaction</a>
				</div>
			</div>
		</section>
	</div>
</div>

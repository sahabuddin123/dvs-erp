<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<section class="panel text-center">
			<div class="panel-body" style="padding: 40px 30px;">
				<div style="width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
					<i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
				</div>
				<h2 style="color: #166534; font-weight: bold; margin-top: 0;">Subscription Activated!</h2>
				<p class="text-muted" style="font-size: 15px;">Your plan has been successfully activated and all modules and resource limits have been updated.</p>

				<div class="well well-sm text-left mt-lg" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
					<p style="margin-bottom: 8px;"><strong>Transaction ID:</strong> <code><?php echo html_escape($trx_id); ?></code></p>
					<?php if (!empty($package)): ?>
						<p style="margin-bottom: 8px;"><strong>Plan Package:</strong> <span class="label label-primary"><?php echo html_escape($package['name']); ?></span></p>
						<p style="margin-bottom: 8px;"><strong>Amount Paid:</strong> $<?php echo number_format(max(0, (float)$package['price'] - (float)$package['discount']), 2); ?></p>
						<p style="margin-bottom: 0;"><strong>Validity:</strong> <?php echo ($package['period_type'] == 1) ? 'Lifetime' : ($package['period_value'] . ' Period'); ?></p>
					<?php endif; ?>
				</div>

				<div class="mt-lg">
					<a href="<?php echo base_url('subscription/index'); ?>" class="btn btn-primary btn-lg" style="margin-right: 10px;">
						<i class="icons icon-directions"></i> View Subscription
					</a>
					<a href="<?php echo base_url('dashboard'); ?>" class="btn btn-default btn-lg">
						<i class="fas fa-home"></i> Go to Dashboard
					</a>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
        <?php include 'sidebar.php'; ?>
    </div>
	<div class="col-md-6">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#bkash" data-toggle="tab"><i class="fas fa-mobile-alt"></i> bKash Config</a>
					</li>
					<li>
						<a href="#nagad" data-toggle="tab"><i class="fas fa-mobile-alt"></i> Nagad Config</a>
					</li>
					<li>
						<a href="#offline_tab" data-toggle="tab"><i class="fas fa-money-bill-wave"></i> Offline Payment</a>
					</li>
				</ul>
				<div class="tab-content">
					<!-- bKash Config Tab -->
					<div class="tab-pane box active" id="bkash">
						<?php echo form_open('settings/bkash_save', array('class' => 'form-horizontal frm-submit-msg')); ?>
							<input type="hidden" name="branch_id" value="<?=$branch_id?>">
							<div class="form-group">
								<label class="col-sm-3 control-label">bKash App Key <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" autocomplete="off" name="bkash_app_key" value="<?=html_escape($config['bkash_app_key'] ?? '')?>" placeholder="Enter bKash App Key">
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">bKash App Secret <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" autocomplete="off" name="bkash_app_secret" value="<?=html_escape($config['bkash_app_secret'] ?? '')?>" placeholder="Enter bKash App Secret">
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">bKash Username <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" autocomplete="off" name="bkash_username" value="<?=html_escape($config['bkash_username'] ?? '')?>" placeholder="Enter bKash Username">
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">bKash Password <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="password" class="form-control" autocomplete="off" name="bkash_password" value="<?=html_escape($config['bkash_password'] ?? '')?>" placeholder="Enter bKash Password">
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-3 col-md-8 mb-md">
									<div class="checkbox-replace">
										<label class="i-checks">
											<input type="checkbox" name="bkash_sandbox" id="bkash_sandbox" <?=((isset($config['bkash_sandbox']) && $config['bkash_sandbox'] == 1) ? 'checked' : '')?>>
											<i></i> bKash Sandbox (Test Mode)
										</label>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-3 col-sm-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?=translate('save')?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>

					<!-- Nagad Config Tab -->
					<div class="tab-pane box" id="nagad">
						<?php echo form_open('settings/nagad_save', array('class' => 'form-horizontal frm-submit-msg')); ?>
							<input type="hidden" name="branch_id" value="<?=$branch_id?>">
							<div class="form-group">
								<label class="col-sm-3 control-label">Merchant ID <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" autocomplete="off" name="nagad_merchant_id" value="<?=html_escape($config['nagad_merchant_id'] ?? '')?>" placeholder="Enter Nagad Merchant ID">
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Merchant Number <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" autocomplete="off" name="nagad_merchant_number" value="<?=html_escape($config['nagad_merchant_number'] ?? '')?>" placeholder="Enter Nagad Merchant Account Number">
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">PGW Public Key</label>
								<div class="col-md-8">
									<textarea class="form-control" name="nagad_public_key" rows="4" placeholder="Nagad Public Key (Certificate / Key)"><?=html_escape($config['nagad_public_key'] ?? '')?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Merchant Private Key</label>
								<div class="col-md-8">
									<textarea class="form-control" name="nagad_private_key" rows="4" placeholder="Merchant Private Key (Certificate / Key)"><?=html_escape($config['nagad_private_key'] ?? '')?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-3 col-md-8 mb-md">
									<div class="checkbox-replace">
										<label class="i-checks">
											<input type="checkbox" name="nagad_sandbox" id="nagad_sandbox" <?=((isset($config['nagad_sandbox']) && $config['nagad_sandbox'] == 1) ? 'checked' : '')?>>
											<i></i> Nagad Sandbox (Test Mode)
										</label>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-3 col-sm-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?=translate('save')?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>

					<!-- Offline Payment Tab -->
					<div class="tab-pane box" id="offline_tab">
						<div class="panel-body">
							<div class="alert alert-subl">
								<h4><i class="fas fa-info-circle"></i> <?=translate('offline_payments')?></h4>
								<p>Students and parents can submit bank transfer slips, cash receipts, and cheque information for fee payment approval.</p>
							</div>
							<div class="row mt-lg">
								<div class="col-md-6 mb-md">
									<div class="panel panel-custom">
										<div class="panel-heading">
											<h5 class="panel-title"><i class="fas fa-list"></i> Offline Payment Types</h5>
										</div>
										<div class="panel-body">
											<p>Manage bank accounts and deposit instructions for students.</p>
											<a href="<?=base_url('offline_payments/type')?>" class="btn btn-default btn-block"><i class="fas fa-external-link-alt"></i> Manage Payment Types</a>
										</div>
									</div>
								</div>
								<div class="col-md-6 mb-md">
									<div class="panel panel-custom">
										<div class="panel-heading">
											<h5 class="panel-title"><i class="fas fa-check-double"></i> Pending Approvals</h5>
										</div>
										<div class="panel-body">
											<p>Review submitted payment receipts and approve student fees.</p>
											<a href="<?=base_url('offline_payments/payments')?>" class="btn btn-default btn-block"><i class="fas fa-external-link-alt"></i> Review Payments</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- Active Gateway Panel -->
	<div class="col-md-3">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-credit-card"></i> <?=translate('active_gateway')?></h4>
			</header>
			<?php echo form_open('settings/payment_active', array('class' => 'form-horizontal frm-submit-msg')); ?>
			<input type="hidden" name="branch_id" value="<?=$branch_id?>">
			<div class="panel-body mt-sm">
				<div class="alert alert-info" style="padding: 8px 12px; font-size: 12px;">
					<i class="fas fa-shield-alt"></i> Only <strong>bKash</strong>, <strong>Nagad</strong>, and <strong>Offline Payment</strong> are available. All international gateways are permanently disabled.
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<div class="checkbox-replace">
							<label class="i-checks">
								<input type="checkbox" name="bkash_status" id="bkash_status" <?=((isset($config['bkash_status']) && $config['bkash_status'] == 1) ? 'checked' : '')?>>
								<i></i> <strong>bKash</strong> (Online Gateway)
							</label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<div class="checkbox-replace">
							<label class="i-checks">
								<input type="checkbox" name="nagad_status" id="nagad_status" <?=((isset($config['nagad_status']) && $config['nagad_status'] == 1) ? 'checked' : '')?>>
								<i></i> <strong>Nagad</strong> (Online Gateway)
							</label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<div class="checkbox-replace">
							<label class="i-checks">
								<input type="checkbox" name="offline_payments" id="offline_payments" <?=((isset($branch['offline_payments']) && $branch['offline_payments'] == 1) ? 'checked' : '')?>>
								<i></i> <strong>Offline Payment</strong> (Bank / Slip)
							</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer mt-sm">
				<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
					<i class="fas fa-save"></i> <?=translate('save')?>
				</button>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

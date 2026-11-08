<div class="row">
	<div class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a href="<?php echo base_url('saas/settings_general'); ?>"><i class="fas fa-cog"></i> <?php echo translate('general_settings'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('saas/settings_website'); ?>"><i class="fas fa-globe"></i> <?php echo translate('website_settings'); ?></a>
			</li>
			<li class="active">
				<a href="<?php echo base_url('saas/settings_payment'); ?>"><i class="fas fa-dollar-sign"></i> <?php echo translate('payment_settings'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('saas/settings_email'); ?>"><i class="fas fa-envelope"></i> <?php echo translate('email_settings'); ?></a>
			</li>
		</ul>
	</div>

	<div class="col-md-9">
		<!-- Payment Gateways Configuration -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-credit-card"></i> Online Payment Gateways (bKash / Nagad / Paytm)</h4>
			</header>
			<?php echo form_open('saas/settings_payment', array('class' => 'form-horizontal form-bordered validate')); ?>
				<div class="panel-body">
					<!-- bKash Configuration -->
					<div class="headers-line mt-none">
						<i class="fas fa-money-bill-wave text-danger"></i> bKash Payment Gateway
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">bKash Status</label>
						<div class="col-md-6">
							<div class="material-switch mt-xs">
								<input id="bkash_status" name="bkash_status" type="checkbox" value="1" <?php echo !empty($payment_config['bkash_status']) ? 'checked' : ''; ?> />
								<label for="bkash_status" class="label-primary"></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">bKash App Key</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="bkash_app_key" value="<?php echo !empty($payment_config['bkash_app_key']) ? html_escape($payment_config['bkash_app_key']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">bKash App Secret</label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="bkash_app_secret" value="<?php echo !empty($payment_config['bkash_app_secret']) ? html_escape($payment_config['bkash_app_secret']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">bKash Username</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="bkash_username" value="<?php echo !empty($payment_config['bkash_username']) ? html_escape($payment_config['bkash_username']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">bKash Password</label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="bkash_password" value="<?php echo !empty($payment_config['bkash_password']) ? html_escape($payment_config['bkash_password']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">bKash Sandbox</label>
						<div class="col-md-6">
							<div class="material-switch mt-xs">
								<input id="bkash_sandbox" name="bkash_sandbox" type="checkbox" value="1" <?php echo !empty($payment_config['bkash_sandbox']) ? 'checked' : ''; ?> />
								<label for="bkash_sandbox" class="label-primary"></label>
							</div>
						</div>
					</div>

					<!-- Nagad Configuration -->
					<div class="headers-line">
						<i class="fas fa-wallet text-warning"></i> Nagad Payment Gateway
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nagad Status</label>
						<div class="col-md-6">
							<div class="material-switch mt-xs">
								<input id="nagad_status" name="nagad_status" type="checkbox" value="1" <?php echo !empty($payment_config['nagad_status']) ? 'checked' : ''; ?> />
								<label for="nagad_status" class="label-primary"></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nagad Merchant ID</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="nagad_merchant_id" value="<?php echo !empty($payment_config['nagad_merchant_id']) ? html_escape($payment_config['nagad_merchant_id']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nagad Merchant Number</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="nagad_merchant_number" value="<?php echo !empty($payment_config['nagad_merchant_number']) ? html_escape($payment_config['nagad_merchant_number']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nagad Public Key</label>
						<div class="col-md-6">
							<textarea class="form-control" name="nagad_public_key" rows="3"><?php echo !empty($payment_config['nagad_public_key']) ? html_escape($payment_config['nagad_public_key']) : ''; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nagad Private Key</label>
						<div class="col-md-6">
							<textarea class="form-control" name="nagad_private_key" rows="3"><?php echo !empty($payment_config['nagad_private_key']) ? html_escape($payment_config['nagad_private_key']) : ''; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nagad Sandbox</label>
						<div class="col-md-6">
							<div class="material-switch mt-xs">
								<input id="nagad_sandbox" name="nagad_sandbox" type="checkbox" value="1" <?php echo !empty($payment_config['nagad_sandbox']) ? 'checked' : ''; ?> />
								<label for="nagad_sandbox" class="label-primary"></label>
							</div>
						</div>
					</div>

					<!-- Paytm Configuration -->
					<div class="headers-line">
						<i class="fas fa-university text-info"></i> Paytm Payment Gateway
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Paytm Status</label>
						<div class="col-md-6">
							<div class="material-switch mt-xs">
								<input id="paytm_status" name="paytm_status" type="checkbox" value="1" <?php echo !empty($payment_config['paytm_status']) ? 'checked' : ''; ?> />
								<label for="paytm_status" class="label-primary"></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Paytm Merchant MID</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="paytm_merchantmid" value="<?php echo !empty($payment_config['paytm_merchantmid']) ? html_escape($payment_config['paytm_merchantmid']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Paytm Merchant Key</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="paytm_merchantkey" value="<?php echo !empty($payment_config['paytm_merchantkey']) ? html_escape($payment_config['paytm_merchantkey']) : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Paytm Website</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="paytm_merchant_website" value="<?php echo !empty($payment_config['paytm_merchant_website']) ? html_escape($payment_config['paytm_merchant_website']) : 'WEBSTAGING'; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Paytm Industry Type</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="paytm_industry_type" value="<?php echo !empty($payment_config['paytm_industry_type']) ? html_escape($payment_config['paytm_industry_type']) : 'Retail'; ?>" />
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" name="submit" value="save_gateways" class="btn btn-default btn-block"><i class="fas fa-check"></i> <?php echo translate('save'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>

		<!-- Offline Payment Types -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-hand-holding-usd"></i> Offline Payment Types</h4>
			</header>
			<div class="panel-body">
				<div class="row mb-md">
					<div class="col-md-12 text-right">
						<button class="btn btn-default btn-sm" data-toggle="modal" data-target="#addOfflineTypeModal">
							<i class="fas fa-plus"></i> Add Offline Payment Type
						</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover mb-none">
						<thead>
							<tr>
								<th width="50">#</th>
								<th><?php echo translate('name'); ?></th>
								<th><?php echo translate('note'); ?></th>
								<th width="80"><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($offline_types)): ?>
								<?php $cnt = 1; foreach ($offline_types as $ot): ?>
									<tr>
										<td><?php echo $cnt++; ?></td>
										<td><strong><?php echo html_escape($ot['name']); ?></strong></td>
										<td><?php echo html_escape($ot['note']); ?></td>
										<td>
											<a href="<?php echo base_url('saas/delete_offline_type/' . $ot['id']); ?>" class="btn btn-danger btn-circle icon" onclick="return confirm('Are you sure?');">
												<i class="fas fa-trash-alt"></i>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="4" class="text-center"><?php echo translate('no_information_available'); ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- Add Offline Type Modal -->
<div class="modal fade" id="addOfflineTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('saas/settings_payment'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fas fa-plus-circle"></i> Add Offline Payment Type</h4>
				</div>
				<div class="modal-body">
					<div class="form-group mb-md">
						<label class="control-label">Payment Method Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="type_name" required placeholder="e.g. Bank Wire Transfer" />
					</div>
					<div class="form-group mb-md">
						<label class="control-label">Instruction / Note</label>
						<textarea class="form-control" name="note" rows="3" placeholder="e.g. Account: 12345678, Bank: City Bank"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="submit" value="add_offline_type" class="btn btn-primary"><i class="fas fa-plus"></i> <?php echo translate('add'); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

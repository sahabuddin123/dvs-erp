<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('accounting/voucher_expense')?>"><i class="fas fa-list-ul"></i> <?php echo translate('expense') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('expense'); ?></a>
			</li>
			<li>
				<a href="<?=base_url('accounting/voucher_print/' . $expense['id'])?>" target="_blank" style="color: #007F3D; font-weight: 600;"><i class="fas fa-print"></i> ভাউচার প্রিন্ট (A5 Voucher)</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
				<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<input type="hidden" name="voucher_type" value="expense">
					<input type="hidden" name="voucher_old_id" value="<?=$expense['id']?>">
					<?php if (is_superadmin_loggedin() && is_multiple_branches() ): ?>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, $expense['branch_id'], "class='form-control' id='branch_id' disabled
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<?php else: ?>
					<input type="hidden" name="branch_id" id="branch_id" value="<?=$expense['branch_id']?>">
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('account'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$accounts_list = $this->app_lib->getSelectByBranch('accounts', $expense['branch_id']);
								echo form_dropdown("account_id", $accounts_list, $expense['account_id'], "class='form-control' id='account_id' disabled
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('voucher') . " " . translate('head'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayVoucherHead = $this->app_lib->getSelectByBranch('voucher_head', $expense['branch_id'], false, array('type' => 'expense'));
								echo form_dropdown("voucher_head_id", $arrayVoucherHead, $expense['voucher_head_id'], "class='form-control' id='voucher_head_id'
								data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('ref'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="ref_no" value="<?=$expense['ref']?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Pay By / Payer Name (পরিশোধকারী)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="pay_by" placeholder="পরিশোধকারীর নাম / Pay By (Payer Name)" value="<?=isset($expense['pay_by']) ? html_escape($expense['pay_by']) : ''?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Received By / Receiver Name (গ্রহীতা)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="received_by" placeholder="গ্রহীতার নাম / Received By (Receiver Name)" value="<?=isset($expense['received_by']) ? html_escape($expense['received_by']) : ''?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('amount'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="amount" value="<?=$expense['amount'] ?>" disabled />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="date" value="<?php echo set_value('date', $expense['date']); ?>" data-plugin-datepicker
							data-plugin-options='{ "todayHighlight" : true, "endDate": "+0d" }' readonly />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('pay_via'); ?></label>
						<div class="col-md-6">
    						<?php
    							$payvia_list = get_office_accounting_payvia_list();
    							echo form_dropdown("pay_via", $payvia_list, $expense['pay_via'], "class='form-control' data-plugin-selectTwo data-width='100%'
    							data-minimum-results-for-search='Infinity' ");
    						?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?=$expense['description']?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('attachment'); ?></label>
						<div class="col-md-6 mb-md">
							<input type="file" name="attachment_file" class="dropify" data-height="70" />
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?php echo translate('update'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>
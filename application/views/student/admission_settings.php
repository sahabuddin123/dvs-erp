<?php
$current_lang = $this->session->userdata('set_lang');
if (empty($current_lang)) {
    $current_lang = get_global_setting('translation');
}
if (empty($current_lang)) {
    $current_lang = 'bengali';
}
$is_bn = ($current_lang === 'bengali');
?>
<style>
    <?php if ($is_bn): ?>
    @font-face {
        font-family: 'DVSNumerals';
        src: local('Noto Sans Bengali'), local('Hind Siliguri');
        unicode-range: U+0030-0039, U+09E6-09EF;
    }
    .admission-settings-box, .admission-settings-box * {
        font-family: 'DVSNumerals', 'Hind Siliguri', 'Signika', sans-serif !important;
    }
    <?php endif; ?>
</style>
<div class="row admission-settings-box">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading" style="background: linear-gradient(135deg, #007F3D 0%, #005f2e 100%); color: #fff; padding: 18px 22px;">
				<div class="panel-actions">
					<a href="<?=base_url('student/add')?>" class="btn btn-default btn-xs text-dark" style="background: #fff; font-weight: 600; padding: 5px 12px; border-radius: 4px;">
						<i class="fas fa-user-plus text-success"></i> <?=translate('create_admission')?>
					</a>
				</div>
				<h4 class="panel-title" style="color: #fff; margin: 0; font-weight: 700;">
					<i class="fas fa-sliders-h mr-xs"></i> <?=translate('admission_settings_fee')?>
				</h4>
			</header>

			<?php echo form_open('student/admission_settings', array('class' => 'form-horizontal form-bordered validate')); ?>
			<div class="panel-body" style="padding: 25px;">
				
				<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' data-plugin-selectTwo data-width='100%' required");
						?>
					</div>
				</div>
				<?php endif; ?>

				<!-- Admission Fee Setting -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('default_admission_fee')?> <span class="required">*</span>
					</label>
					<div class="col-md-6">
						<div class="input-group">
							<span class="input-group-addon" style="font-weight: bold; background: #f8fafc;">৳</span>
							<input type="number" step="0.01" min="0" class="form-control" name="admission_fee" value="<?=set_value('admission_fee', (isset($settings['admission_fee']) ? $settings['admission_fee'] : '15000.00'))?>" required style="font-size: 15px; font-weight: bold; color: #007F3D;" />
						</div>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-info-circle"></i> <?=translate('default_admission_fee_help')?>
						</span>
					</div>
				</div>

				<!-- Minimum Required Paid Amount -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('minimum_paid_required')?> <span class="required">*</span>
					</label>
					<div class="col-md-6">
						<div class="input-group">
							<span class="input-group-addon" style="font-weight: bold; background: #f8fafc;">৳</span>
							<input type="number" step="0.01" min="0" class="form-control" name="min_paid_amount" value="<?=set_value('min_paid_amount', (isset($settings['min_paid_amount']) ? $settings['min_paid_amount'] : '2000.00'))?>" required style="font-size: 15px; font-weight: bold; color: #b91c1c;" />
						</div>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-exclamation-triangle text-warning"></i> <?=translate('minimum_paid_required_help')?>
						</span>
					</div>
				</div>

				<!-- Allow Free Admission Toggle -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('allow_100_percent_free_admission')?>
					</label>
					<div class="col-md-6">
						<div class="radio-custom radio-inline">
							<input type="radio" id="allow_free_1" name="allow_free_admission" value="1" <?=((isset($settings['allow_free_admission']) && $settings['allow_free_admission'] == 1) ? 'checked' : '')?>>
							<label for="allow_free_1"><strong><?=translate('yes')?></strong> - <?=translate('allow_free_admission_help_yes')?></label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="allow_free_0" name="allow_free_admission" value="0" <?=((isset($settings['allow_free_admission']) && $settings['allow_free_admission'] == 0) ? 'checked' : '')?>>
							<label for="allow_free_0"><strong><?=translate('no')?></strong> - <?=translate('allow_free_admission_help_no')?></label>
						</div>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-check-circle text-success"></i> <?=translate('allow_free_admission_help_note')?>
						</span>
					</div>
				</div>

				<!-- Default Payment Method -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('default_payment_method')?>
					</label>
					<div class="col-md-6">
						<?php
							$default_pay = isset($settings['default_pay_via']) ? $settings['default_pay_via'] : 'Cash';
							$pay_methods = array(
								'Cash' => $is_bn ? 'ক্যাশ / নগদ' : 'Cash',
								'Bkash' => $is_bn ? 'বিকাশ' : 'bKash',
								'Nagad' => $is_bn ? 'নগদ' : 'Nagad',
								'Bank Transfer' => $is_bn ? 'ব্যাংক ট্রান্সফার' : 'Bank Transfer',
								'Cheque' => $is_bn ? 'চেক' : 'Cheque',
								'Card' => $is_bn ? 'কার্ড' : 'Card',
								'Others' => $is_bn ? 'অন্যান্য' : 'Others'
							);
							echo form_dropdown("default_pay_via", $pay_methods, set_value('default_pay_via', $default_pay), "class='form-control' data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>

				<!-- Terms and Conditions / Undertaking -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('terms_conditions_undertaking')?>
					</label>
					<div class="col-md-8">
						<textarea name="terms_conditions" rows="8" class="form-control" style="font-size: 13.5px; line-height: 1.6; border-radius: 6px;"><?=set_value('terms_conditions', (isset($settings['terms_conditions']) ? $settings['terms_conditions'] : ''))?></textarea>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-print"></i> <?=translate('terms_conditions_help')?>
						</span>
					</div>
				</div>

			</div>
			
			<footer class="panel-footer" style="background: #f8fafc; padding: 18px 25px;">
				<div class="row">
					<div class="col-md-offset-3 col-md-6">
						<button type="submit" name="submit" value="1" class="btn btn-default" style="background: #007F3D; color: #fff; font-weight: 700; padding: 10px 28px; border-radius: 6px; border: none; box-shadow: 0 3px 10px rgba(0,127,61,0.3);">
							<i class="fas fa-save mr-xs"></i> <?=translate('save_settings')?>
						</button>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

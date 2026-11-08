<div class="row">
	<!-- Left Side: Settings Form -->
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-print" style="color: #6366f1;"></i> <?=translate('thermal_print_configuration')?></h4>
			</header>
			<?php echo form_open('thermal_print/settings', ['class' => 'form-horizontal form-bordered validate']); ?>
				<div class="panel-body">
					<!-- Status -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('status')?> <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="thermal_status_1" name="is_active" value="1" <?=(isset($settings->is_active) && $settings->is_active == 1) ? 'checked' : ''?>>
								<label for="thermal_status_1"><i class="fas fa-check-circle text-success"></i> <?=translate('active')?></label>
							</div>
							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" id="thermal_status_0" name="is_active" value="0" <?=(isset($settings->is_active) && $settings->is_active == 0) ? 'checked' : ''?>>
								<label for="thermal_status_0"><i class="fas fa-times-circle text-danger"></i> <?=translate('inactive')?></label>
							</div>
						</div>
					</div>

					<!-- Printer Roll Width -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('printer_paper_size')?> <span class="required">*</span></label>
						<div class="col-md-9">
							<?php $p_width = isset($settings->printer_width) ? $settings->printer_width : '80mm'; ?>
							<div class="radio-custom radio-inline">
								<input type="radio" id="width_80" name="printer_width" value="80mm" <?=($p_width == '80mm') ? 'checked' : ''?>>
								<label for="width_80"><strong><?=translate('standard_pos')?></strong></label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="width_58" name="printer_width" value="58mm" <?=($p_width == '58mm') ? 'checked' : ''?>>
								<label for="width_58"><strong><?=translate('mini_pos')?></strong></label>
							</div>
							<span class="help-block"><small><?=translate('printer_paper_size_help')?></small></span>
						</div>
					</div>

					<!-- Options: Logo, Barcode, Auto-print -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('receipt_options')?></label>
						<div class="col-md-9">
							<div class="checkbox-custom checkbox-primary" style="margin-bottom: 6px;">
								<input type="checkbox" name="show_logo" id="show_logo" value="1" <?=(isset($settings->show_logo) && $settings->show_logo == 1) ? 'checked' : ''?>>
								<label for="show_logo"><?=translate('show_school_logo')?></label>
							</div>
							<div class="checkbox-custom checkbox-primary" style="margin-bottom: 6px;">
								<input type="checkbox" name="show_barcode" id="show_barcode" value="1" <?=(isset($settings->show_barcode) && $settings->show_barcode == 1) ? 'checked' : ''?>>
								<label for="show_barcode"><?=translate('show_barcode_invoice_code')?></label>
							</div>
							<div class="checkbox-custom checkbox-primary">
								<input type="checkbox" name="auto_print" id="auto_print" value="1" <?=(isset($settings->auto_print) && $settings->auto_print == 1) ? 'checked' : ''?>>
								<label for="auto_print"><?=translate('auto_trigger_print_dialog')?></label>
							</div>
						</div>
					</div>

					<!-- Header Title -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('header_title')?> <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="header_title" id="header_title" value="<?=(isset($settings->header_title) ? html_escape($settings->header_title) : 'Dream Valley School')?>" required />
						</div>
					</div>

					<!-- Header Subtitle -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('header_subtitle')?></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="header_subtitle" id="header_subtitle" value="<?=(isset($settings->header_subtitle) ? html_escape($settings->header_subtitle) : '')?>" placeholder="<?=translate('header_subtitle')?>..." />
						</div>
					</div>

					<!-- Address -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('school_address')?></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="address" id="address" value="<?=(isset($settings->address) ? html_escape($settings->address) : '')?>" placeholder="<?=translate('school_address')?>..." />
						</div>
					</div>

					<!-- Contact Info -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('contact_info')?></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="contact_info" id="contact_info" value="<?=(isset($settings->contact_info) ? html_escape($settings->contact_info) : '')?>" placeholder="<?=translate('contact_info')?>..." />
						</div>
					</div>

					<!-- Footer Text -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('footer_message_terms')?></label>
						<div class="col-md-9">
							<textarea class="form-control" name="footer_text" id="footer_text" rows="3"><?=(isset($settings->footer_text) ? html_escape($settings->footer_text) : '')?></textarea>
						</div>
					</div>
				</div>

				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-3 col-md-9">
							<button type="submit" class="btn btn-default"><i class="fas fa-save"></i> <?=translate('save')?></button>
							<a href="<?=base_url('thermal_print/test_print')?>" target="_blank" class="btn btn-info ml-sm"><i class="fas fa-print"></i> <?=translate('test_print')?></a>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

	<!-- Right Side: POS Receipt Live Preview -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-receipt"></i> <?=translate('thermal_receipt_live_preview')?></h4>
			</header>
			<div class="panel-body" style="background: #f1f5f9; padding: 20px; display: flex; justify-content: center;">
				<!-- Simulated Paper Roll -->
				<div id="simulated_roll" style="background: #ffffff; width: <?=($p_width == '58mm') ? '240px' : '310px'?>; box-shadow: 0 4px 15px rgba(0,0,0,0.12); padding: 18px 14px; font-family: 'Courier New', Courier, monospace; font-size: 11px; line-height: 1.35; color: #111; border-top: 5px solid #6366f1; border-bottom: 5px dashed #cbd5e1; transition: width 0.3s ease;">
					<!-- Header -->
					<div class="text-center" style="text-align: center; margin-bottom: 6px;">
						<?php if (isset($settings->show_logo) && $settings->show_logo == 1): ?>
							<img src="<?=$this->application_model->getBranchImage($branch_id, 'printing-logo')?>" style="max-height: 35px; max-width: 140px; object-fit: contain; filter: grayscale(100%); margin-bottom: 3px;" alt="Logo" />
						<?php endif; ?>
						<div style="font-weight: 700; font-size: 13px;" id="pv_title"><?=html_escape($settings->header_title)?></div>
						<div style="font-size: 9.5px; color: #444;" id="pv_subtitle"><?=html_escape($settings->header_subtitle)?></div>
						<div style="font-size: 9px; color: #555;" id="pv_address"><?=html_escape($settings->address)?></div>
						<div style="font-size: 9px; color: #555;" id="pv_contact"><?=html_escape($settings->contact_info)?></div>
					</div>

					<div style="border-top: 2px solid #000; margin: 6px 0;"></div>
					<div style="text-align: center; font-weight: 700; font-size: 11px;">
						** <?=translate('receipt_voucher')?> **
					</div>
					<div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

					<!-- Student Demo Info -->
					<table style="width: 100%; font-size: 10.5px; border-collapse: collapse;">
						<tr>
							<td><strong><?=translate('receipt_no')?>:</strong> REC-2026-0012</td>
							<td style="text-align: right;"><strong><?=translate('date')?>:</strong> <?=date('d M Y')?></td>
						</tr>
						<tr>
							<td><strong><?=translate('register_no')?>:</strong> SCH-00001</td>
							<td style="text-align: right;"><strong><?=translate('roll')?>:</strong> 01</td>
						</tr>
						<tr>
							<td colspan="2"><strong><?=translate('student')?>:</strong> <?=get_current_lang() == 'bn' ? 'আবু বকর ছিদ্দিক' : 'Abu Bakar Siddique'?></td>
						</tr>
						<tr>
							<td colspan="2"><strong><?=translate('class')?>:</strong> <?=get_current_lang() == 'bn' ? 'প্লে (গোলাপ শাখা)' : 'Play (Rose)'?></td>
						</tr>
					</table>

					<!-- Table -->
					<table style="width: 100%; border-collapse: collapse; margin-top: 6px;">
						<thead>
							<tr style="border-top: 1px dashed #000; border-bottom: 1px dashed #000;">
								<th style="text-align: left; padding: 4px 0; font-size: 10.5px;"><?=translate('fees_type')?></th>
								<th style="text-align: right; padding: 4px 0; font-size: 10.5px;"><?=translate('discount')?></th>
								<th style="text-align: right; padding: 4px 0; font-size: 10.5px;"><?=translate('paid')?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="padding: 3px 0;"><?=get_current_lang() == 'bn' ? 'মাসিক বেতন (নভেম্বর)' : 'Monthly Tuition (Nov)'?></td>
								<td style="text-align: right; padding: 3px 0;">0.00</td>
								<td style="text-align: right; padding: 3px 0; font-weight: 700;">1,500.00</td>
							</tr>
							<tr>
								<td style="padding: 3px 0;"><?=get_current_lang() == 'bn' ? 'পরীক্ষা ফি' : 'Exam Fee'?></td>
								<td style="text-align: right; padding: 3px 0;">0.00</td>
								<td style="text-align: right; padding: 3px 0; font-weight: 700;">500.00</td>
							</tr>
						</tbody>
					</table>

					<div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

					<!-- Totals -->
					<table style="width: 100%; font-size: 11px;">
						<tr>
							<td><?=translate('total')?> <?=translate('fee')?>:</td>
							<td style="text-align: right;">৳ 2,000.00</td>
						</tr>
						<tr style="font-weight: 700; font-size: 12.5px;">
							<td><?=translate('total_paid')?>:</td>
							<td style="text-align: right;">৳ 2,000.00</td>
						</tr>
						<tr>
							<td><?=translate('payment_method')?>:</td>
							<td style="text-align: right; font-weight: 700;">Cash</td>
						</tr>
					</table>

					<div style="border-top: 2px solid #000; margin: 6px 0;"></div>

					<!-- Signatures -->
					<table style="width: 100%; margin-top: 15px; font-size: 9px; text-align: center;">
						<tr>
							<td style="width: 50%;">----------------<br/><?=translate('guardian')?></td>
							<td style="width: 50%;">----------------<br/><?=translate('cashier')?></td>
						</tr>
					</table>

					<div style="text-align: center; font-size: 9px; margin-top: 10px; letter-spacing: 2px;">
						*REC-2026-0012*
					</div>

					<div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

					<div style="text-align: center; font-size: 9px; font-style: italic; color: #444;" id="pv_footer">
						<?=nl2br(html_escape($settings->footer_text))?>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	// Paper Width Switcher
	$('input[name="printer_width"]').on('change', function() {
		if ($(this).val() === '58mm') {
			$('#simulated_roll').css('width', '240px');
		} else {
			$('#simulated_roll').css('width', '310px');
		}
	});

	// Live text reflection
	$('#header_title').on('input', function() { $('#pv_title').text($(this).val()); });
	$('#header_subtitle').on('input', function() { $('#pv_subtitle').text($(this).val()); });
	$('#address').on('input', function() { $('#pv_address').text($(this).val()); });
	$('#contact_info').on('input', function() { $('#pv_contact').text($(this).val()); });
	$('#footer_text').on('input', function() { $('#pv_footer').text($(this).val()); });
});
</script>


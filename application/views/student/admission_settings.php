<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading" style="background: linear-gradient(135deg, #007F3D 0%, #005f2e 100%); color: #fff; padding: 18px 22px;">
				<div class="panel-actions">
					<a href="<?=base_url('student/add')?>" class="btn btn-default btn-xs text-dark" style="background: #fff; font-weight: 600; padding: 5px 12px; border-radius: 4px;">
						<i class="fas fa-user-plus text-success"></i> <?=translate('create_admission')?> (নতুন ভর্তি ফরম)
					</a>
				</div>
				<h4 class="panel-title" style="color: #fff; margin: 0; font-weight: 700;">
					<i class="fas fa-sliders-h mr-xs"></i> ভর্তি ফি ও শর্তাবলী কনফিগারেশন (Admission Fee & Settings)
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
						নির্ধারিত ভর্তি ফি (Default Admission Fee) <span class="required">*</span>
					</label>
					<div class="col-md-6">
						<div class="input-group">
							<span class="input-group-addon" style="font-weight: bold; background: #f8fafc;">৳</span>
							<input type="number" step="0.01" min="0" class="form-control" name="admission_fee" value="<?=set_value('admission_fee', (isset($settings['admission_fee']) ? $settings['admission_fee'] : '15000.00'))?>" required style="font-size: 15px; font-weight: bold; color: #007F3D;" />
						</div>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-info-circle"></i> নতুন শিক্ষার্থী ভর্তির সময় ফরমের ৫নং ধাপে এই ফি-টি প্রারম্ভিক ভর্তি ফি হিসেবে প্রদর্শিত হবে।
						</span>
					</div>
				</div>

				<!-- Minimum Required Paid Amount -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						ভর্তি নিশ্চিতের জন্য সর্বনিম্ন ফি (Minimum Paid Required) <span class="required">*</span>
					</label>
					<div class="col-md-6">
						<div class="input-group">
							<span class="input-group-addon" style="font-weight: bold; background: #f8fafc;">৳</span>
							<input type="number" step="0.01" min="0" class="form-control" name="min_paid_amount" value="<?=set_value('min_paid_amount', (isset($settings['min_paid_amount']) ? $settings['min_paid_amount'] : '2000.00'))?>" required style="font-size: 15px; font-weight: bold; color: #b91c1c;" />
						</div>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-exclamation-triangle text-warning"></i> শিক্ষার্থী ভর্তি নিশ্চিত করতে অন্তত এই পরিমাণ টাকা দিতে হবে। এর চেয়ে কম দিলে আবেদন ড্রাফট (Draft) হিসেবে থাকবে।
						</span>
					</div>
				</div>

				<!-- Allow Free Admission Toggle -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						বিনামূল্যে ভর্তি অনুমোদন (Allow 100% Free Admission)
					</label>
					<div class="col-md-6">
						<div class="radio-custom radio-inline">
							<input type="radio" id="allow_free_1" name="allow_free_admission" value="1" <?=((isset($settings['allow_free_admission']) && $settings['allow_free_admission'] == 1) ? 'checked' : '')?>>
							<label for="allow_free_1"><strong>হ্যাঁ (অনুমোদিত)</strong> - অ্যাডমিন বিশেষ বিবেচনায় বিনামূল্যে ভর্তি করাতে পারবেন</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="allow_free_0" name="allow_free_admission" value="0" <?=((isset($settings['allow_free_admission']) && $settings['allow_free_admission'] == 0) ? 'checked' : '')?>>
							<label for="allow_free_0"><strong>না (অনুমোদিত নয়)</strong> - সর্বনিম্ন ফি প্রদান বাধ্যতামূলক</label>
						</div>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-check-circle text-success"></i> এটি চালু থাকলে ভর্তি ফরমে 'বিশেষ বিবেচনায় বিনামূল্যে ভর্তি' অপশন থাকবে যা নির্বাচন করলে ০ টাকাতেও ভর্তি নিশ্চিত করা যাবে।
						</span>
					</div>
				</div>

				<!-- Default Payment Method -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						ডিফল্ট পেমেন্ট মাধ্যম (Default Payment Method)
					</label>
					<div class="col-md-6">
						<?php
							$default_pay = isset($settings['default_pay_via']) ? $settings['default_pay_via'] : 'Cash';
							$pay_methods = array(
								'Cash' => 'Cash (ক্যাশ / নগদ)',
								'Bkash' => 'Bkash (বিকাশ)',
								'Nagad' => 'Nagad (নগদ)',
								'Bank Transfer' => 'Bank Transfer (ব্যাংক ট্রান্সফার)',
								'Cheque' => 'Cheque (চেক)',
								'Card' => 'Card (কার্ড)',
								'Others' => 'Others (অন্যান্য)'
							);
							echo form_dropdown("default_pay_via", $pay_methods, set_value('default_pay_via', $default_pay), "class='form-control' data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>

				<!-- Terms and Conditions / Undertaking -->
				<div class="form-group">
					<label class="col-md-3 control-label">
						ভর্তির শর্তাবলী ও অঙ্গীকারনামা (Terms, Conditions & Undertaking)
					</label>
					<div class="col-md-8">
						<textarea name="terms_conditions" rows="8" class="form-control" style="font-size: 13.5px; line-height: 1.6; border-radius: 6px;"><?=set_value('terms_conditions', (isset($settings['terms_conditions']) ? $settings['terms_conditions'] : ''))?></textarea>
						<span class="help-block text-muted" style="font-size: 12.5px;">
							<i class="fas fa-print"></i> এই শর্তাবলী ও অঙ্গীকারনামাটি শিক্ষার্থীর চূড়ান্ত ভর্তি ফরম ও ফি রসিদের নিচে স্বয়ংক্রিয়ভাবে মুদ্রিত হবে।
						</span>
					</div>
				</div>

			</div>
			
			<footer class="panel-footer" style="background: #f8fafc; padding: 18px 25px;">
				<div class="row">
					<div class="col-md-offset-3 col-md-6">
						<button type="submit" name="submit" value="1" class="btn btn-default" style="background: #007F3D; color: #fff; font-weight: 700; padding: 10px 28px; border-radius: 6px; border: none; box-shadow: 0 3px 10px rgba(0,127,61,0.3);">
							<i class="fas fa-save mr-xs"></i> সেটিংস সংরক্ষণ করুন (Save Settings)
						</button>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>


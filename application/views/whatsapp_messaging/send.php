<div class="row">
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fab fa-whatsapp" style="color: #25D366;"></i> WhatsApp বার্তা তৈরি করুন (Compose Message)</h4>
			</header>
			<?php echo form_open('whatsapp_messaging/send', ['class' => 'form-horizontal form-bordered validate', 'id' => 'whatsapp_send_form']); ?>
				<div class="panel-body">
					<!-- Recipient Type -->
					<div class="form-group">
						<label class="col-md-3 control-label">প্রাপকের ধরন <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="type_student" name="recipient_type" value="student" checked>
								<label for="type_student"><i class="fas fa-user-graduate text-primary"></i> শিক্ষার্থী (Student)</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="type_parent" name="recipient_type" value="parent">
								<label for="type_parent"><i class="fas fa-user-friends text-success"></i> অভিভাবক (Parent)</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="type_staff" name="recipient_type" value="staff">
								<label for="type_staff"><i class="fas fa-chalkboard-teacher text-info"></i> শিক্ষক/কর্মচারী (Staff)</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="type_custom" name="recipient_type" value="custom">
								<label for="type_custom"><i class="fas fa-mobile-alt text-warning"></i> সরাসরি নম্বর (Custom)</label>
							</div>
						</div>
					</div>

					<!-- Class & Section Selector (for Student and Parent) -->
					<div id="class_section_group">
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('class')?> / শ্রেণি</label>
							<div class="col-md-9">
								<?php
									$arrayClass = $this->app_lib->getClass($branch_id);
									echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('section')?> / শাখা</label>
							<div class="col-md-9">
								<select class="form-control" name="section_id" id="section_id" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
									<option value=""><?=translate('select_class_first')?></option>
								</select>
							</div>
						</div>
					</div>

					<!-- Staff Role Selector -->
					<div id="staff_role_group" style="display: none;">
						<div class="form-group">
							<label class="col-md-3 control-label">কর্মচারী রোল (Role)</label>
							<div class="col-md-9">
								<?php
									$role_list = $this->app_lib->getRoles();
									echo form_dropdown("role_id", $role_list, set_value('role_id'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>
					</div>

					<!-- Custom Mobile Numbers -->
					<div id="custom_numbers_group" style="display: none;">
						<div class="form-group">
							<label class="col-md-3 control-label">মোবাইল নম্বরসমূহ</label>
							<div class="col-md-9">
								<textarea class="form-control" name="custom_numbers" rows="3" placeholder="কমা (,) দিয়ে পৃথক করে একাধিক নম্বর লিখুন, যেমন: 01716122899, 01800000000"></textarea>
								<span class="help-block"><small>একাধিক নম্বরে পাঠাতে কমা (,) ব্যবহার করুন।</small></span>
							</div>
						</div>
					</div>

					<!-- Template Select -->
					<div class="form-group">
						<label class="col-md-3 control-label">মেসেজ টেমপ্লেট</label>
						<div class="col-md-9">
							<select class="form-control" id="template_selector" data-plugin-selectTwo data-width="100%">
								<option value="">-- টেমপ্লেট থেকে মেসেজ নির্বাচন করুন --</option>
								<?php if (!empty($templates)): foreach ($templates as $tpl): ?>
									<option value="<?=html_escape($tpl['template_body'])?>"><?=html_escape($tpl['title'])?> (<?=ucfirst($tpl['category'])?>)</option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>

					<!-- Dynamic Tags -->
					<div class="form-group">
						<label class="col-md-3 control-label">ট্যাগ যুক্ত করুন</label>
						<div class="col-md-9">
							<div class="tag-buttons" style="margin-bottom: 8px;">
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{name}"><code>{name}</code> নাম</button>
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{student_name}"><code>{student_name}</code> শিক্ষার্থী</button>
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{roll}"><code>{roll}</code> রোল</button>
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{class}"><code>{class}</code> শ্রেণি</button>
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{section}"><code>{section}</code> শাখা</button>
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{date}"><code>{date}</code> তারিখ</button>
								<button type="button" class="btn btn-default btn-xs insert-tag" data-tag="{school}"><code>{school}</code> স্কুল</button>
							</div>
							<span class="help-block"><small>যেকোনো ট্যাগে ক্লিক করলে তা মেসেজে যুক্ত হবে এবং স্বয়ংক্রিয়ভাবে সংশ্লিষ্ট তথ্য দিয়ে প্রতিস্থাপিত হবে।</small></span>
						</div>
					</div>

					<!-- Message Textarea -->
					<div class="form-group">
						<label class="col-md-3 control-label">মেসেজের বিবরণ <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea class="form-control" name="message" id="message_text" rows="5" placeholder="আপনার WhatsApp বার্তা এখানে লিখুন..." required></textarea>
							<div style="margin-top: 5px; font-size: 12px; color: #64748b;">
								অক্ষর সংখ্যা: <span id="char_count" style="font-weight: 700; color: #007F3D;">0</span>
							</div>
						</div>
					</div>
				</div>

				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-3 col-md-9">
							<button type="submit" class="btn btn-success" style="background-color: #25D366; border-color: #25D366; font-weight: 700;">
								<i class="fab fa-whatsapp"></i> WhatsApp বার্তা প্রেরণ প্রক্রিয়া শুরু করুন
							</button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

	<!-- Live Mobile WhatsApp Preview Card -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-mobile-alt"></i> WhatsApp মেসেজ লাইভ প্রিভিউ</h4>
			</header>
			<div class="panel-body" style="background: #e5ddd5; border-radius: 0 0 4px 4px; padding: 20px;">
				<!-- Mobile Screen Mockup -->
				<div style="background: #075e54; padding: 10px 15px; border-radius: 8px 8px 0 0; color: #ffffff; display: flex; align-items: center;">
					<div style="width: 36px; height: 36px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
						<i class="fab fa-whatsapp" style="color: #075e54; font-size: 22px;"></i>
					</div>
					<div>
						<div style="font-weight: 700; font-size: 14px;"><?=(!empty($settings->header_text) ? html_escape($settings->header_text) : 'Dream Valley School')?></div>
						<small style="color: #d1fae5; font-size: 11px;">অনলাইন (Official Notification)</small>
					</div>
				</div>

				<!-- Chat Message Bubble -->
				<div style="background: #ffffff; min-height: 200px; padding: 15px; border-radius: 0 0 8px 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); background-image: radial-gradient(#d4cfc7 1px, transparent 1px); background-size: 16px 16px;">
					<div style="background: #dcf8c6; padding: 10px 14px; border-radius: 8px 8px 0 8px; margin-left: auto; max-width: 90%; box-shadow: 0 1px 2px rgba(0,0,0,0.15); word-break: break-word;">
						<?php if (!empty($settings->header_text)): ?>
							<div style="font-weight: 700; color: #075e54; margin-bottom: 6px;">*<?=html_escape($settings->header_text)?>*</div>
						<?php endif; ?>
						
						<div id="preview_text" style="font-size: 13.5px; color: #111b21; white-space: pre-wrap;">আপনার বার্তার নমুনা এখানে প্রদর্শিত হবে...</div>

						<?php if (!empty($settings->footer_text)): ?>
							<div style="font-style: italic; color: #667781; font-size: 12px; margin-top: 8px;">_<?=html_escape($settings->footer_text)?>_</div>
						<?php endif; ?>

						<div style="text-align: right; font-size: 10px; color: #667781; margin-top: 4px;">
							<span id="preview_time"><?=date('h:i A')?></span> <i class="fas fa-check-double text-primary" style="font-size: 10px;"></i>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	// Recipient type switcher
	$('input[name="recipient_type"]').on('change', function() {
		var val = $(this).val();
		if (val === 'student' || val === 'parent') {
			$('#class_section_group').slideDown();
			$('#staff_role_group').slideUp();
			$('#custom_numbers_group').slideUp();
		} else if (val === 'staff') {
			$('#class_section_group').slideUp();
			$('#staff_role_group').slideDown();
			$('#custom_numbers_group').slideUp();
		} else if (val === 'custom') {
			$('#class_section_group').slideUp();
			$('#staff_role_group').slideUp();
			$('#custom_numbers_group').slideDown();
		}
	});

	// Template Selector
	$('#template_selector').on('change', function() {
		var tpl = $(this).val();
		if (tpl) {
			$('#message_text').val(tpl).trigger('input');
		}
	});

	// Insert tag button
	$('.insert-tag').on('click', function() {
		var tag = $(this).data('tag');
		var textarea = document.getElementById('message_text');
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var text = textarea.value;
		textarea.value = text.substring(0, start) + tag + text.substring(end);
		textarea.selectionStart = textarea.selectionEnd = start + tag.length;
		textarea.focus();
		$('#message_text').trigger('input');
	});

	// Live Preview Updater
	$('#message_text').on('input', function() {
		var val = $(this).val();
		$('#char_count').text(val.length);
		if (val.trim() === '') {
			$('#preview_text').text('আপনার বার্তার নমুনা এখানে প্রদর্শিত হবে...');
		} else {
			// Replace demo tags for preview
			var preview = val.replace(/{name}/g, 'আবু বকর ছিদ্দিক')
							 .replace(/{student_name}/g, 'আবু বকর ছিদ্দিক')
							 .replace(/{roll}/g, '০১')
							 .replace(/{class}/g, 'প্লে (Play)')
							 .replace(/{section}/g, 'গোলাপ')
							 .replace(/{school}/g, 'ড্রিম ভ্যালি স্কুল')
							 .replace(/{date}/g, '<?=date("d M Y")?>');
			$('#preview_text').text(preview);
		}
	});
});
</script>


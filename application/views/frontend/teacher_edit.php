<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-edit"></i> Edit Teacher</h4>
	</header>
	<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
		<div class="panel-body">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="form-group">
				<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $teacher['branch_id']), "class='form-control' data-plugin-selectTwo
						data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label class="col-md-3 control-label">Name (Bangla) <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name_bn" placeholder="শিক্ষকের পূর্ণ নাম (বাংলা)" value="<?php echo set_value('name_bn', !empty($teacher['name_bn']) ? $teacher['name_bn'] : $teacher['name']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Name (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name_en" placeholder="Teacher full name (English)" value="<?php echo set_value('name_en', !empty($teacher['name_en']) ? $teacher['name_en'] : $teacher['name']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Title / পদবী (Bangla)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="title_bn" placeholder="যেমন: সহকারী শিক্ষক / সহকারী মৌলভী" value="<?php echo set_value('title_bn', !empty($teacher['title_bn']) ? $teacher['title_bn'] : $teacher['title']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Title (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="title_en" placeholder="e.g. Assistant Teacher / Senior Teacher" value="<?php echo set_value('title_en', !empty($teacher['title_en']) ? $teacher['title_en'] : $teacher['title']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Designation (Bangla)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="designation_bn" placeholder="যেমন: সহকারী মৌলভী / সিনিয়র শিক্ষক" value="<?php echo set_value('designation_bn', !empty($teacher['designation_bn']) ? $teacher['designation_bn'] : $teacher['designation']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Designation (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="designation_en" placeholder="e.g. Assistant Teacher / Lecturer" value="<?php echo set_value('designation_en', !empty($teacher['designation_en']) ? $teacher['designation_en'] : $teacher['designation']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Subject / বিভাগ (Bangla)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="subject_bn" placeholder="যেমন: আরবি, ইংরেজি, গণিত" value="<?php echo set_value('subject_bn', !empty($teacher['subject_bn']) ? $teacher['subject_bn'] : $teacher['subject']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Subject (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="subject_en" placeholder="e.g. Arabic, English, Mathematics" value="<?php echo set_value('subject_en', !empty($teacher['subject_en']) ? $teacher['subject_en'] : $teacher['subject']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Display Order on Frontend / পজিশন <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="position" value="<?php echo set_value('position', $teacher['position']); ?>" min="1" required />
					<span class="text-muted small"><i class="fas fa-info-circle text-info"></i> ওয়েবসাইটে কার পরে কাকে দেখানো হবে সেই ক্রমিক নম্বর (১, ২, ৩...)</span>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Phone</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="phone" placeholder="e.g. 017xxxxxxxx" value="<?php echo set_value('phone', $teacher['phone']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Email</label>
				<div class="col-md-6">
					<input type="email" class="form-control" name="email" placeholder="Enter email address" value="<?php echo set_value('email', $teacher['email']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Message / Bio (Bangla)</label>
				<div class="col-md-8">
					<textarea class="form-control" id="teacher_edit_message_bn" name="message_bn" rows="5" placeholder="শিক্ষকের বার্তা বা জীবনবৃত্তান্ত (বাংলা)"><?php echo set_value('message_bn', !empty($teacher['message_bn']) ? $teacher['message_bn'] : $teacher['message']); ?></textarea>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Message / Bio (English)</label>
				<div class="col-md-8">
					<textarea class="form-control" id="teacher_edit_message_en" name="message_en" rows="5" placeholder="Teacher bio or message to students (English)"><?php echo set_value('message_en', !empty($teacher['message_en']) ? $teacher['message_en'] : $teacher['message']); ?></textarea>
					<span class="error"></span>
				</div>
			</div>

			<?php
				$current_img_url = '';
				if (!empty($teacher['image'])) {
					if (file_exists(FCPATH . 'uploads/frontend/teacher/' . $teacher['image'])) {
						$current_img_url = base_url('uploads/frontend/teacher/' . $teacher['image']);
					} elseif (file_exists(FCPATH . 'uploads/images/staff/' . $teacher['image'])) {
						$current_img_url = base_url('uploads/images/staff/' . $teacher['image']);
					} elseif (file_exists(FCPATH . 'uploads/frontend/founder/' . $teacher['image'])) {
						$current_img_url = base_url('uploads/frontend/founder/' . $teacher['image']);
					}
				}
			?>
			<div class="form-group">
				<label class="col-md-3 control-label">Image</label>
				<div class="col-md-4">
					<input type="file" name="image" class="dropify" data-height="160" data-default-file="<?php echo $current_img_url; ?>" data-allowed-file-extensions="jpg jpeg png gif webp" />
					<input type="hidden" name="remove_image" id="remove_image" value="0" />
					<span class="text-muted small">Accepted formats: JPEG, PNG, JPG, GIF, WEBP (Max 2MB). Click Remove (✕) to remove image and show default avatar.</span>
					<?php if (!empty($teacher['image'])): ?>
					<div class="checkbox-custom checkbox-danger mt-xs">
						<input type="checkbox" name="remove_image_chk" id="remove_image_chk" value="1" />
						<label for="remove_image_chk" class="text-danger"><i class="fas fa-trash-alt"></i> বর্তমান ছবি মুছে ফেলুন (মুছে ফেললে সিস্টেমের ডিফল্ট ছবি দেখাবে)</label>
					</div>
					<?php endif; ?>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-3 col-md-6">
					<div class="checkbox-custom checkbox-primary mb-xs">
						<input type="checkbox" name="show_on_homepage" id="show_on_homepage_edit" value="1" <?php echo ($teacher['show_on_homepage'] == 1 ? 'checked' : ''); ?> />
						<label for="show_on_homepage_edit"><b>Show on Homepage (হোমপেজে দৃশ্যমান)</b> - চেক করা থাকলে মূল হোমপেজে "শিক্ষকমণ্ডলী" সেকশনে দেখাবে</label>
					</div>
					<div class="checkbox-custom checkbox-success">
						<input type="checkbox" name="active" id="active_edit_teacher" value="1" <?php echo ($teacher['active'] == 1 ? 'checked' : ''); ?> />
						<label for="active_edit_teacher"><b>Active (সক্রিয়)</b> - আনচেক করলে ওয়েবসাইট থেকে সাময়িকভাবে লুকানো থাকবে</label>
					</div>
				</div>
			</div>
		</div>

		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-3 col-md-6">
					<a href="<?php echo base_url('frontend/teacher'); ?>" class="btn btn-default" style="margin-right: 8px;">
						<i class="fas fa-times"></i> Cancel
					</a>
					<button type="submit" class="btn btn-success" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-check-circle"></i> Update Teacher
					</button>
				</div>
			</div>
		</footer>
	<?php echo form_close(); ?>
</section>

<script type="text/javascript">
$(document).ready(function() {
    if ($.fn.dropify) {
        var drEvent = $('.dropify').dropify();
        drEvent.on('dropify.afterClear', function(event, element) {
            $('#remove_image').val('1');
            $('#remove_image_chk').prop('checked', true);
        });
        drEvent.on('dropify.fileReady', function(event, element) {
            $('#remove_image').val('0');
            $('#remove_image_chk').prop('checked', false);
        });
        $('#remove_image_chk').on('change', function() {
            if ($(this).is(':checked')) {
                $('#remove_image').val('1');
                if ($('.dropify').data('dropify')) {
                    $('.dropify').data('dropify').clearElement();
                }
            } else {
                $('#remove_image').val('0');
            }
        });
    }
    if (typeof CKEDITOR !== 'undefined') {
        if ($('#teacher_edit_message_bn').length) {
            CKEDITOR.replace('teacher_edit_message_bn', { height: 180 });
        }
        if ($('#teacher_edit_message_en').length) {
            CKEDITOR.replace('teacher_edit_message_en', { height: 180 });
        }
    }

    $('form.frm-submit-data').on('submit', function() {
        if (typeof CKEDITOR !== 'undefined') {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    });
});
</script>


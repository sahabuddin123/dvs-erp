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
				<label class="col-md-3 control-label">Name <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name" placeholder="Teacher full name" value="<?php echo set_value('name', $teacher['name']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Title <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="title" placeholder="e.g. Senior Teacher / সহকারী প্রধান শিক্ষক" value="<?php echo set_value('title', $teacher['title']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Designation <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="designation" placeholder="e.g. Coordinator / বিভাগীয় প্রধান" value="<?php echo set_value('designation', $teacher['designation']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="subject" placeholder="e.g. Arabic, English, Mathematics" value="<?php echo set_value('subject', $teacher['subject']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Position <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="position" value="<?php echo set_value('position', $teacher['position']); ?>" required />
					<span class="text-muted small">Display order on frontend</span>
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
				<label class="col-md-3 control-label">Message / Bio</label>
				<div class="col-md-8">
					<textarea class="form-control" id="teacher_edit_message" name="message" rows="6" placeholder="Teacher bio or message to students"><?php echo set_value('message', $teacher['message']); ?></textarea>
					<span class="error"></span>
				</div>
			</div>

			<?php
				$current_img_url = '';
				if (!empty($teacher['image'])) {
					if (file_exists(FCPATH . 'uploads/frontend/teacher/' . $teacher['image'])) {
						$current_img_url = base_url('uploads/frontend/teacher/' . $teacher['image']);
					} elseif (file_exists(FCPATH . 'uploads/frontend/founder/' . $teacher['image'])) {
						$current_img_url = base_url('uploads/frontend/founder/' . $teacher['image']);
					} elseif (file_exists(FCPATH . 'assets/frontend/dvs/storage/founders/' . $teacher['image'])) {
						$current_img_url = base_url('assets/frontend/dvs/storage/founders/' . $teacher['image']);
					}
				}
			?>
			<div class="form-group">
				<label class="col-md-3 control-label">Image</label>
				<div class="col-md-4">
					<input type="file" name="image" class="dropify" data-height="160" data-default-file="<?php echo $current_img_url; ?>" data-allowed-file-extensions="jpg jpeg png gif webp" />
					<span class="text-muted small">Accepted formats: JPEG, PNG, JPG, GIF, WEBP (Max 2MB). Leave blank to keep current image.</span>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-3 col-md-6">
					<div class="checkbox-custom checkbox-primary mb-xs">
						<input type="checkbox" name="show_on_homepage" id="show_on_homepage_edit" value="1" <?php echo ($teacher['show_on_homepage'] == 1 ? 'checked' : ''); ?> />
						<label for="show_on_homepage_edit"><b>Show on Homepage</b> (Check to display teacher in the Our Teachers section)</label>
					</div>
					<div class="checkbox-custom checkbox-success">
						<input type="checkbox" name="active" id="active_edit_teacher" value="1" <?php echo ($teacher['active'] == 1 ? 'checked' : ''); ?> />
						<label for="active_edit_teacher"><b>Active</b> (Check this to make the teacher visible in the system)</label>
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
        $('.dropify').dropify();
    }
    if (typeof CKEDITOR !== 'undefined') {
        if ($('#teacher_edit_message').length) {
            CKEDITOR.replace('teacher_edit_message', { height: 220 });
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


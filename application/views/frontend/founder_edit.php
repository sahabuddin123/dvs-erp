<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-edit"></i> Edit Founder</h4>
	</header>
	<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
		<div class="panel-body">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="form-group">
				<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $founder['branch_id']), "class='form-control' data-plugin-selectTwo
						data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label class="col-md-3 control-label">Name (Bangla) <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name_bn" value="<?php echo set_value('name_bn', $founder['name_bn']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Name (English) <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name_en" value="<?php echo set_value('name_en', $founder['name_en']); ?>" required />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Title (Bangla)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="title_bn" value="<?php echo set_value('title_bn', $founder['title_bn']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Title (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="title_en" value="<?php echo set_value('title_en', $founder['title_en']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Designation (Bangla)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="designation_bn" value="<?php echo set_value('designation_bn', $founder['designation_bn']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Designation (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="designation_en" value="<?php echo set_value('designation_en', $founder['designation_en']); ?>" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Position <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="position" value="<?php echo set_value('position', $founder['position']); ?>" required />
					<span class="text-muted small">Display order on frontend</span>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Message (Bangla)</label>
				<div class="col-md-8">
					<textarea class="form-control" id="message_bn" name="message_bn" rows="8"><?php echo set_value('message_bn', $founder['message_bn']); ?></textarea>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Message (English)</label>
				<div class="col-md-8">
					<textarea class="form-control" id="message_en" name="message_en" rows="8"><?php echo set_value('message_en', $founder['message_en']); ?></textarea>
					<span class="error"></span>
				</div>
			</div>

			<?php
				$current_img_url = '';
				if (!empty($founder['image'])) {
					if (file_exists(FCPATH . 'uploads/frontend/founder/' . $founder['image'])) {
						$current_img_url = base_url('uploads/frontend/founder/' . $founder['image']);
					}
				}
			?>
			<div class="form-group">
				<label class="col-md-3 control-label">Image</label>
				<div class="col-md-4">
					<input type="file" name="image" class="dropify" data-height="160" data-default-file="<?php echo $current_img_url; ?>" data-allowed-file-extensions="jpg jpeg png gif" />
					<span class="text-muted small">Accepted formats: JPEG, PNG, JPG, GIF (Max 2MB). Leave blank to keep current image.</span>
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-3 col-md-6">
					<div class="checkbox-custom checkbox-success">
						<input type="checkbox" name="active" id="active_edit" value="1" <?php echo ($founder['active'] == 1 ? 'checked' : ''); ?> />
						<label for="active_edit"><b>Active</b> (Check this to make the founder visible on the frontend)</label>
					</div>
				</div>
			</div>
		</div>

		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-3 col-md-6">
					<a href="<?php echo base_url('frontend/founder'); ?>" class="btn btn-default" style="margin-right: 8px;">
						<i class="fas fa-times"></i> Cancel
					</a>
					<button type="submit" class="btn btn-success" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-check-circle"></i> Update Founder
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
        if ($('#message_bn').length) {
            CKEDITOR.replace('message_bn', { height: 250 });
        }
        if ($('#message_en').length) {
            CKEDITOR.replace('message_en', { height: 250 });
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

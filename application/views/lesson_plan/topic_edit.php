<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-edit"></i> <?=translate('edit') . ' ' . translate('topic')?></h4>
			</header>
			<?php echo form_open(base_url('lesson_plan/topic_edit/' . $topic['id']), array('class' => 'frm-submit')); ?>
			<div class="panel-body">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $topic['branch_id'], "class='form-control' id='edit_branch_id' data-width='100%' data-plugin-selectTwo");
						?>
						<span class="error"></span>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$arrayClass = $this->app_lib->getClass($topic['branch_id']);
						echo form_dropdown("class_id", $arrayClass, $topic['class_id'], "class='form-control' id='edit_class_id' onchange='getSectionByClass(this.value, 0, \"#edit_section_id\")' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
					<?php
						$arraySection = $this->app_lib->getSections($topic['class_id']);
						echo form_dropdown("section_id", $arraySection, $topic['section_id'], "class='form-control' id='edit_section_id' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
					<select name="subject_id" id="edit_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<?php
						$query = $this->subject_model->getSubjectByClassSection($topic['class_id'], $topic['section_id']);
						if ($query->num_rows() > 0) {
							$subjects = $query->result_array();
							foreach ($subjects as $s) {
								$sel = ($s['subject_id'] == $topic['subject_id']) ? 'selected' : '';
								echo "<option value='{$s['subject_id']}' {$sel}>{$s['subjectname']} ({$s['subject_code']})</option>";
							}
						}
						?>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('topic_name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" value="<?=html_escape($topic['name'])?>" />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('update')?>
						</button>
						<a href="<?=base_url('lesson_plan/topic')?>" class="btn btn-default"><?=translate('cancel')?></a>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('#edit_section_id').on('change', function () {
		var classID = $('#edit_class_id').val();
		var sectionID = $(this).val();
		$.ajax({
			url: base_url + 'subject/getByClassSection',
			type: 'POST',
			data: { classID: classID, sectionID: sectionID },
			success: function (data) {
				$('#edit_subject_id').html(data);
			}
		});
	});
});
</script>

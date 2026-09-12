<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-edit"></i> <?=translate('edit') . ' ' . translate('lesson')?></h4>
			</header>
			<?php echo form_open(base_url('lesson_plan/lesson_edit/' . $lesson['id']), array('class' => 'frm-submit')); ?>
			<div class="panel-body">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $lesson['branch_id'], "class='form-control' id='edit_branch_id' data-width='100%' data-plugin-selectTwo");
						?>
						<span class="error"></span>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$arrayClass = $this->app_lib->getClass($lesson['branch_id']);
						echo form_dropdown("class_id", $arrayClass, $lesson['class_id'], "class='form-control' id='edit_class_id' onchange='getSectionByClass(this.value, 0, \"#edit_section_id\")' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
					<?php
						$arraySection = $this->app_lib->getSections($lesson['class_id']);
						echo form_dropdown("section_id", $arraySection, $lesson['section_id'], "class='form-control' id='edit_section_id' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
					<select name="subject_id" id="edit_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<?php
						$query = $this->subject_model->getSubjectByClassSection($lesson['class_id'], $lesson['section_id']);
						if ($query->num_rows() > 0) {
							$subjects = $query->result_array();
							foreach ($subjects as $s) {
								$sel = ($s['subject_id'] == $lesson['subject_id']) ? 'selected' : '';
								echo "<option value='{$s['subject_id']}' {$sel}>{$s['subjectname']} ({$s['subject_code']})</option>";
							}
						}
						?>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('topic')?> <span class="required">*</span></label>
					<select name="topic_id" id="edit_topic_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<?php
						$topics = $this->lesson_plan_model->getTopicsBySubject($lesson['class_id'], $lesson['section_id'], $lesson['subject_id']);
						foreach ($topics as $t) {
							$sel = ($t['id'] == $lesson['topic_id']) ? 'selected' : '';
							echo "<option value='{$t['id']}' {$sel}>" . html_escape($t['name']) . "</option>";
						}
						?>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('lesson_name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" value="<?=html_escape($lesson['name'])?>" />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('update')?>
						</button>
						<a href="<?=base_url('lesson_plan/lesson')?>" class="btn btn-default"><?=translate('cancel')?></a>
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
				$('#edit_topic_id').html('<option value=""><?=translate("select")?></option>');
			}
		});
	});

	$('#edit_subject_id').on('change', function () {
		var classID = $('#edit_class_id').val();
		var sectionID = $('#edit_section_id').val();
		var subjectID = $(this).val();
		$.ajax({
			url: base_url + 'lesson_plan/getTopicsBySubject',
			type: 'POST',
			data: { class_id: classID, section_id: sectionID, subject_id: subjectID },
			success: function (data) {
				$('#edit_topic_id').html(data);
			}
		});
	});
});
</script>

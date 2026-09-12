<section class="panel">
	<header class="panel-heading">
		<div class="panel-btn">
		<?php if (get_permission('lesson_syllabus', 'is_add')): ?>
			<button class="btn btn-default btn-circle" onclick="openSyllabusModal()"><i class="fas fa-plus-circle"></i> <?=translate('update_lesson_status')?></button>
		<?php endif; ?>
		</div>
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter')?></h4>
	</header>
	<div class="panel-body">
		<?php echo form_open(base_url('lesson_plan/manage'), array('method' => 'get')); ?>
		<div class="row">
			<div class="col-md-3 mb-sm">
				<label class="control-label"><?=translate('class')?></label>
				<?php
					$arrayClass = $this->app_lib->getClass($branch_id);
					echo form_dropdown("class_id", $arrayClass, $class_id, "class='form-control' id='filter_class_id' onchange='getSectionByClass(this.value, 0, \"#filter_section_id\")' data-plugin-selectTwo data-width='100%'");
				?>
			</div>
			<div class="col-md-3 mb-sm">
				<label class="control-label"><?=translate('section')?></label>
				<?php
					$arraySection = $this->app_lib->getSections($class_id);
					echo form_dropdown("section_id", $arraySection, $section_id, "class='form-control' id='filter_section_id' data-plugin-selectTwo data-width='100%'");
				?>
			</div>
			<div class="col-md-3 mb-sm">
				<label class="control-label"><?=translate('subject')?></label>
				<select name="subject_id" id="filter_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
					<option value=""><?=translate('select')?></option>
					<?php
					if (!empty($class_id) && !empty($section_id)) {
						$query = $this->subject_model->getSubjectByClassSection($class_id, $section_id);
						if ($query->num_rows() > 0) {
							$subjects = $query->result_array();
							foreach ($subjects as $s) {
								$sel = ($s['subject_id'] == $subject_id) ? 'selected' : '';
								echo "<option value='{$s['subject_id']}' {$sel}>{$s['subjectname']} ({$s['subject_code']})</option>";
							}
						}
					}
					?>
				</select>
			</div>
			<div class="col-md-3 mb-sm">
				<label class="control-label"><?=translate('status')?></label>
				<select name="status" class="form-control" data-plugin-selectTwo data-width="100%">
					<option value=""><?=translate('all')?></option>
					<option value="pending" <?=$status == 'pending' ? 'selected' : ''?>><?=translate('pending')?></option>
					<option value="in_progress" <?=$status == 'in_progress' ? 'selected' : ''?>><?=translate('in_progress')?></option>
					<option value="completed" <?=$status == 'completed' ? 'selected' : ''?>><?=translate('completed')?></option>
				</select>
			</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div class="col-md-12 text-right">
				<button type="submit" class="btn btn-default"><i class="fas fa-search"></i> <?=translate('filter')?></button>
				<a href="<?=base_url('lesson_plan/manage')?>" class="btn btn-default"><i class="fas fa-redo"></i> <?=translate('reset')?></a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</section>

<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-tasks"></i> <?=translate('syllabus_status_list')?></h4>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-hover table-condensed mb-none table-export">
			<thead>
				<tr>
					<th style="width: 40px;"><?=translate('sl')?></th>
					<th><?=translate('class')?> (<?=translate('section')?>)</th>
					<th><?=translate('subject')?></th>
					<th><?=translate('topic')?></th>
					<th><?=translate('lesson')?></th>
					<th><?=translate('date')?></th>
					<th><?=translate('time')?></th>
					<th><?=translate('status')?></th>
					<th><?=translate('attachment')?></th>
					<th><?=translate('teacher')?></th>
					<th style="width: 90px;"><?=translate('action')?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$count = 1;
				if (!empty($syllabus_list)) {
					foreach ($syllabus_list as $row) {
						$badge = 'label-warning';
						if ($row['status'] === 'completed') {
							$badge = 'label-success';
						} elseif ($row['status'] === 'in_progress') {
							$badge = 'label-info';
						}
						?>
						<tr>
							<td><?=$count++?></td>
							<td><?=html_escape($row['class_name'])?> (<?=html_escape($row['section_name'])?>)</td>
							<td><?=html_escape($row['subject_name'])?> (<?=html_escape($row['subject_code'])?>)</td>
							<td><?=html_escape($row['topic_name'])?></td>
							<td><strong><?=html_escape($row['lesson_name'])?></strong></td>
							<td><?=date('d M Y', strtotime($row['date']))?></td>
							<td>
								<?php
								if (!empty($row['time_from']) && !empty($row['time_to'])) {
									echo date('h:i A', strtotime($row['time_from'])) . ' - ' . date('h:i A', strtotime($row['time_to']));
								} else {
									echo '-';
								}
								?>
							</td>
							<td><span class="label <?=$badge?>"><?=translate($row['status'])?></span></td>
							<td>
								<?php if (!empty($row['attachment'])): ?>
									<a href="<?=base_url('uploads/attachments/lesson_plan/' . $row['attachment'])?>" target="_blank" class="btn btn-default btn-xs" title="<?=translate('download')?>">
										<i class="fas fa-paperclip"></i> <?=translate('file')?>
									</a>
								<?php else: ?>
									-
								<?php endif; ?>
							</td>
							<td><?=html_escape($row['staff_name'])?></td>
							<td>
								<?php if (get_permission('lesson_syllabus', 'is_edit')): ?>
									<button type="button" class="btn btn-default btn-circle icon" onclick='editSyllabusModal(<?=json_encode($row)?>)' title="<?=translate('edit')?>">
										<i class="fas fa-pen-nib"></i>
									</button>
								<?php endif; ?>
								<?php if (get_permission('lesson_syllabus', 'is_delete')): ?>
									<?=btn_delete('lesson_plan/syllabus_delete/' . $row['id'])?>
								<?php endif; ?>
							</td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
</section>

<!-- Manage Syllabus Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="syllabusModal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-tasks"></i> <span id="modal_title"><?=translate('update_lesson_status')?></span></h4>
		</header>
		<?php echo form_open_multipart('lesson_plan/save_syllabus_status', array('class' => 'frm-submit-data')); ?>
		<div class="panel-body">
			<input type="hidden" name="syllabus_id" id="modal_syllabus_id" value="" />

			<div class="form-group">
				<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
				<?php
					$arrayClass = $this->app_lib->getClass($branch_id);
					echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='modal_class_id' onchange='getSectionByClass(this.value, 0, \"#modal_section_id\")' data-plugin-selectTwo data-width='100%'");
				?>
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
				<?php
					$arraySection = $this->app_lib->getSections(set_value('class_id'));
					echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='modal_section_id' data-plugin-selectTwo data-width='100%'");
				?>
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
				<select name="subject_id" id="modal_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
					<option value=""><?=translate('select_class_and_section_first')?></option>
				</select>
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=translate('topic')?> <span class="required">*</span></label>
				<select name="topic_id" id="modal_topic_id" class="form-control" data-plugin-selectTwo data-width="100%">
					<option value=""><?=translate('select_subject_first')?></option>
				</select>
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=translate('lesson')?> <span class="required">*</span></label>
				<select name="lesson_id" id="modal_lesson_id" class="form-control" data-plugin-selectTwo data-width="100%">
					<option value=""><?=translate('select_topic_first')?></option>
				</select>
				<span class="error"></span>
			</div>
			<div class="row">
				<div class="col-md-6 form-group">
					<label class="control-label"><?=translate('date')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="date" id="modal_date" data-plugin-datepicker value="<?=date('Y-m-d')?>" />
					<span class="error"></span>
				</div>
				<div class="col-md-6 form-group">
					<label class="control-label"><?=translate('status')?> <span class="required">*</span></label>
					<select name="status" id="modal_status" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value="pending"><?=translate('pending')?></option>
						<option value="in_progress"><?=translate('in_progress')?></option>
						<option value="completed"><?=translate('completed')?></option>
					</select>
					<span class="error"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 form-group">
					<label class="control-label"><?=translate('time_from')?></label>
					<input type="text" class="form-control" name="time_from" id="modal_time_from" data-plugin-timepicker data-plugin-options='{ "showMeridian": false }' />
					<span class="error"></span>
				</div>
				<div class="col-md-6 form-group">
					<label class="control-label"><?=translate('time_to')?></label>
					<input type="text" class="form-control" name="time_to" id="modal_time_to" data-plugin-timepicker data-plugin-options='{ "showMeridian": false }' />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label"><?=translate('note')?></label>
				<textarea class="form-control" name="note" id="modal_note" rows="3" placeholder="<?=translate('optional_note_or_summary')?>"></textarea>
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=translate('attachment')?></label>
				<input type="file" name="attachment" class="form-control" />
				<small class="text-muted"><?=translate('allowed_types')?>: pdf, doc, docx, txt, jpg, png, ppt, pptx, xls, xlsx (Max 10MB)</small>
			</div>
		</div>
		<footer class="panel-footer text-right">
			<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
				<i class="fas fa-save"></i> <?=translate('save')?>
			</button>
			<button type="button" class="btn btn-default modal-dismiss"><?=translate('close')?></button>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<script type="text/javascript">
$(document).ready(function () {
	// Filter Cascading
	$('#filter_section_id').on('change', function () {
		var classID = $('#filter_class_id').val();
		var sectionID = $(this).val();
		$.ajax({
			url: base_url + 'subject/getByClassSection',
			type: 'POST',
			data: { classID: classID, sectionID: sectionID },
			success: function (data) {
				$('#filter_subject_id').html(data);
			}
		});
	});

	// Modal Cascading
	$('#modal_section_id').on('change', function () {
		var classID = $('#modal_class_id').val();
		var sectionID = $(this).val();
		$.ajax({
			url: base_url + 'subject/getByClassSection',
			type: 'POST',
			data: { classID: classID, sectionID: sectionID },
			success: function (data) {
				$('#modal_subject_id').html(data);
				$('#modal_topic_id').html('<option value=""><?=translate("select_subject_first")?></option>');
				$('#modal_lesson_id').html('<option value=""><?=translate("select_topic_first")?></option>');
			}
		});
	});

	$('#modal_subject_id').on('change', function () {
		var classID = $('#modal_class_id').val();
		var sectionID = $('#modal_section_id').val();
		var subjectID = $(this).val();
		$.ajax({
			url: base_url + 'lesson_plan/getTopicsBySubject',
			type: 'POST',
			data: { class_id: classID, section_id: sectionID, subject_id: subjectID },
			success: function (data) {
				$('#modal_topic_id').html(data);
				$('#modal_lesson_id').html('<option value=""><?=translate("select_topic_first")?></option>');
			}
		});
	});

	$('#modal_topic_id').on('change', function () {
		var topicID = $(this).val();
		$.ajax({
			url: base_url + 'lesson_plan/getLessonsByTopic',
			type: 'POST',
			data: { topic_id: topicID },
			success: function (data) {
				$('#modal_lesson_id').html(data);
			}
		});
	});
});

function openSyllabusModal() {
	$('#modal_title').text('<?=translate("update_lesson_status")?>');
	$('#modal_syllabus_id').val('');
	$('#modal_note').val('');
	$('#modal_status').val('completed').trigger('change');
	mfp_modal('#syllabusModal');
}

function editSyllabusModal(row) {
	$('#modal_title').text('<?=translate("edit_lesson_status")?>');
	$('#modal_syllabus_id').val(row.id);
	$('#modal_class_id').val(row.class_id).trigger('change');

	// Populate sections, subjects, topics, and lessons
	setTimeout(function() {
		$('#modal_section_id').val(row.section_id).trigger('change');
		setTimeout(function() {
			$('#modal_subject_id').val(row.subject_id).trigger('change');
			setTimeout(function() {
				$('#modal_topic_id').val(row.topic_id).trigger('change');
				setTimeout(function() {
					$('#modal_lesson_id').val(row.lesson_id).trigger('change');
				}, 300);
			}, 300);
		}, 300);
	}, 200);

	$('#modal_date').val(row.date);
	$('#modal_status').val(row.status).trigger('change');
	if (row.time_from) $('#modal_time_from').val(row.time_from);
	if (row.time_to) $('#modal_time_to').val(row.time_to);
	if (row.note) $('#modal_note').val(row.note);

	mfp_modal('#syllabusModal');
}
</script>

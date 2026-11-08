<?php
$is_bn = ($this->session->userdata('set_lang') == 'bengali');
?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('lesson_plan/manage_lesson_plan')?>"><i class="fas fa-list-ul"></i> <?=translate('manage_lesson_plan')?> <?=translate('list')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="fas fa-edit"></i> <?=translate('edit')?> <?=translate('lesson_plan')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
				<?php echo form_open_multipart(current_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('class')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id', $plan['class_id']), "class='form-control' id='edit_class_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('section')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arraySection = $this->app_lib->getSections($plan['class_id']);
								echo form_dropdown("section_id", $arraySection, set_value('section_id', $plan['section_id']), "class='form-control' id='edit_section_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="subject_id" id="edit_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select')?></option>
								<?php
								$query = $this->subject_model->getSubjectByClassSection($plan['class_id'], $plan['section_id']);
								if ($query && $query->num_rows() > 0) {
									$subjects = $query->result_array();
									foreach ($subjects as $s) {
										$sel = ($s['subject_id'] == $plan['subject_id']) ? 'selected' : '';
										echo "<option value='{$s['subject_id']}' {$sel}>" . html_escape($s['subjectname']) . " (" . html_escape($s['subject_code']) . ")</option>";
									}
								}
								?>
							</select>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('topic')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$topics = $this->lesson_plan_model->getTopicsBySubject($plan['class_id'], $plan['section_id'], $plan['subject_id']);
								$topicList = ['' => translate('select')];
								foreach ($topics as $t) {
									$topicList[$t['id']] = $t['name'];
								}
								echo form_dropdown("topic_id", $topicList, set_value('topic_id', $plan['topic_id']), "class='form-control' id='edit_topic_id'
								data-plugin-selectTwo data-width='100%' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('lesson')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$lessons = $this->lesson_plan_model->getLessonsByTopic($plan['topic_id']);
								$lessonList = ['' => translate('select')];
								foreach ($lessons as $l) {
									$lessonList[$l['id']] = $l['name'];
								}
								echo form_dropdown("lesson_id", $lessonList, set_value('lesson_id', $plan['lesson_id']), "class='form-control' id='edit_lesson_id'
								data-plugin-selectTwo data-width='100%' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="date" value="<?=set_value('date', $plan['date'])?>" data-plugin-datepicker autocomplete="off" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('time')?></label>
						<div class="col-md-6">
							<div class="row">
								<div class="col-xs-6">
									<input type="text" class="form-control" name="time_from" value="<?=set_value('time_from', $plan['time_from'])?>" placeholder="10:00 AM" />
								</div>
								<div class="col-xs-6">
									<input type="text" class="form-control" name="time_to" value="<?=set_value('time_to', $plan['time_to'])?>" placeholder="10:45 AM" />
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('status')?></label>
						<div class="col-md-6">
							<?php
								$statusList = [
									'Pending'     => $is_bn ? 'অপেক্ষমান (Pending)' : 'Pending',
									'In_Progress' => $is_bn ? 'চলমান (In Progress)' : 'In Progress',
									'Completed'   => $is_bn ? 'সম্পন্ন (Completed)' : 'Completed',
								];
								echo form_dropdown("status", $statusList, set_value('status', $plan['status']), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('objectives') ?? ($is_bn ? 'শিখনফল / উদ্দেশ্য' : 'Objectives')?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="objectives" rows="3"><?=set_value('objectives', $plan['objectives'])?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('teaching_method') ?? ($is_bn ? 'শিক্ষণ পদ্ধতি ও কৌশল' : 'Teaching Method')?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="teaching_method" rows="3"><?=set_value('teaching_method', $plan['teaching_method'])?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('homework') ?? ($is_bn ? 'বাড়ির কাজ / অ্যাসাইনমেন্ট' : 'Homework')?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="homework" rows="2"><?=set_value('homework', $plan['homework'])?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('attachment')?></label>
						<div class="col-md-6">
							<input type="file" name="attachment" class="dropify" data-height="80" data-default-file="<?=!empty($plan['attachment']) ? base_url('uploads/attachments/lesson_plan/' . $plan['attachment']) : ''?>" />
						</div>
					</div>
					<footer class="panel-footer mt-md">
						<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
								<a href="<?=base_url('lesson_plan/manage_lesson_plan')?>" class="btn btn-default btn-block mt-xs">
									<i class="fas fa-arrow-left"></i> <?=translate('cancel')?>
								</a>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function () {
	$('#edit_class_id').on('change', function() {
		var classID = $(this).val();
		$.ajax({
			url: base_url + 'ajax/getSectionByClass',
			type: 'POST',
			data: { class_id: classID },
			success: function (response) {
				$('#edit_section_id').html(response).trigger('change.select2');
			}
		});
		$.ajax({
			url: base_url + 'ajax/getDataByBranch',
			type: 'POST',
			data: { table: 'subject', branch_id: '<?=$branch_id?>', class_id: classID },
			success: function (response) {
				$('#edit_subject_id').html(response).trigger('change.select2');
			}
		});
	});

	$('#edit_subject_id, #edit_section_id').on('change', function() {
		var classID = $('#edit_class_id').val();
		var sectionID = $('#edit_section_id').val();
		var subjectID = $('#edit_subject_id').val();
		if (classID && sectionID && subjectID) {
			$.ajax({
				url: base_url + 'lesson_plan/getTopicsBySubject',
				type: 'POST',
				data: { class_id: classID, section_id: sectionID, subject_id: subjectID },
				success: function (response) {
					$('#edit_topic_id').html(response).trigger('change.select2');
					$('#edit_lesson_id').html('<option value=""><?=translate("select_topic_first")?></option>').trigger('change.select2');
				}
			});
		}
	});

	$('#edit_topic_id').on('change', function() {
		var topicID = $(this).val();
		if (topicID) {
			$.ajax({
				url: base_url + 'lesson_plan/getLessonsByTopic',
				type: 'POST',
				data: { topic_id: topicID },
				success: function (response) {
					$('#edit_lesson_id').html(response).trigger('change.select2');
				}
			});
		}
	});
});
</script>

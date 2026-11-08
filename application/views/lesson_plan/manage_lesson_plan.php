<?php
$is_bn = ($this->session->userdata('set_lang') == 'bengali');
?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('select_ground')?></h4>
	</header>
	<div class="panel-body">
		<?php echo form_open(base_url('lesson_plan/manage_lesson_plan'), array('method' => 'get')); ?>
			<div class="row mb-sm">
				<div class="col-md-3 mb-sm">
					<div class="form-group">
						<label class="control-label"><?=translate('class')?></label>
						<?php
							$arrayClass = $this->app_lib->getClass($branch_id);
							echo form_dropdown("class_id", $arrayClass, set_value('class_id', $class_id), "class='form-control' id='filter_class_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
					</div>
				</div>
				<div class="col-md-3 mb-sm">
					<div class="form-group">
						<label class="control-label"><?=translate('section')?></label>
						<?php
							$arraySection = $this->app_lib->getSections($class_id);
							echo form_dropdown("section_id", $arraySection, set_value('section_id', $section_id), "class='form-control' id='filter_section_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
					</div>
				</div>
				<div class="col-md-3 mb-sm">
					<div class="form-group">
						<label class="control-label"><?=translate('subject')?></label>
						<select name="subject_id" id="filter_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php
							if (!empty($class_id) && !empty($section_id)) {
								$query = $this->subject_model->getSubjectByClassSection($class_id, $section_id);
								if ($query && $query->num_rows() > 0) {
									$subjects = $query->result_array();
									foreach ($subjects as $s) {
										$sel = ($s['subject_id'] == $subject_id) ? 'selected' : '';
										echo "<option value='{$s['subject_id']}' {$sel}>" . html_escape($s['subjectname']) . " (" . html_escape($s['subject_code']) . ")</option>";
									}
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-3 mb-sm">
					<div class="form-group">
						<label class="control-label"><?=translate('date')?></label>
						<div class="input-daterange input-group" data-plugin-datepicker>
							<input type="text" class="form-control" name="date_from" value="<?=html_escape($date_from)?>" placeholder="YYYY-MM-DD" autocomplete="off" />
							<span class="input-group-addon"><i class="fas fa-chevron-right"></i></span>
							<input type="text" class="form-control" name="date_to" value="<?=html_escape($date_to)?>" placeholder="YYYY-MM-DD" autocomplete="off" />
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default"><i class="fas fa-filter"></i> <?=translate('filter')?></button>
					<a href="<?=base_url('lesson_plan/manage_lesson_plan')?>" class="btn btn-default"><i class="fas fa-redo"></i> <?=translate('reset')?></a>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</section>

<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('manage_lesson_plan')?> <?=translate('list')?></a>
			</li>
			<?php if (get_permission('lesson_plan_manage', 'is_add') || is_superadmin_loggedin()): ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="fas fa-plus-circle"></i> <?=translate('add')?> <?=translate('lesson_plan')?></a>
			</li>
			<?php endif; ?>
		</ul>
		<div class="tab-content">
			<!-- TAB 1: LIST -->
			<div id="list" class="tab-pane active">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th width="40"><?=translate('sl')?></th>
								<th><?=translate('date')?></th>
								<th><?=translate('class')?> &amp; <?=translate('section')?></th>
								<th><?=translate('subject')?></th>
								<th><?=translate('topic')?></th>
								<th><?=translate('lesson')?></th>
								<th><?=translate('status')?></th>
								<th width="120"><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($plan_list)) {
								foreach ($plan_list as $row):
									$st = strtolower($row['status']);
							?>
							<tr>
								<td><?=$count++?></td>
								<td>
									<i class="far fa-calendar-alt text-primary"></i> <?=_d($row['date'])?>
									<?php if (!empty($row['time_from']) || !empty($row['time_to'])): ?>
										<br><small class="text-muted"><i class="far fa-clock"></i> <?=html_escape($row['time_from'])?> - <?=html_escape($row['time_to'])?></small>
									<?php endif; ?>
								</td>
								<td><?=html_escape($row['class_name'])?> (<?=html_escape($row['section_name'])?>)</td>
								<td><?=html_escape($row['subject_name'])?></td>
								<td><?=html_escape($row['topic_name'])?></td>
								<td><strong><?=html_escape($row['lesson_name'])?></strong></td>
								<td>
									<?php
									if ($st === 'completed') {
										echo '<span class="badge badge-success"><i class="fas fa-check"></i> ' . ($is_bn ? 'সম্পন্ন' : 'Completed') . '</span>';
									} elseif ($st === 'in_progress') {
										echo '<span class="badge badge-info"><i class="fas fa-spinner fa-spin"></i> ' . ($is_bn ? 'চলমান' : 'In Progress') . '</span>';
									} else {
										echo '<span class="badge badge-warning"><i class="far fa-clock"></i> ' . ($is_bn ? 'অপেক্ষমান' : 'Pending') . '</span>';
									}
									?>
								</td>
								<td>
									<button type="button" class="btn btn-default btn-circle icon btn-view-plan" data-id="<?=$row['id']?>" title="<?=translate('view')?>">
										<i class="fas fa-eye text-primary"></i>
									</button>
									<?php if (get_permission('lesson_plan_manage', 'is_edit') || is_superadmin_loggedin()): ?>
									<a href="<?=base_url('lesson_plan/manage_lesson_plan_edit/' . $row['id'])?>" class="btn btn-default btn-circle icon" title="<?=translate('edit')?>">
										<i class="fas fa-pen-nib text-warning"></i>
									</a>
									<?php endif; ?>
									<?php if (get_permission('lesson_plan_manage', 'is_delete') || is_superadmin_loggedin()): ?>
									<?=btn_delete('lesson_plan/manage_lesson_plan_delete/' . $row['id'])?>
									<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach;
							} else {
								echo '<tr><td colspan="8" class="text-center text-muted" style="padding: 20px;">' . translate('no_information_available') . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- TAB 2: CREATE -->
			<?php if (get_permission('lesson_plan_manage', 'is_add') || is_superadmin_loggedin()): ?>
			<div id="create" class="tab-pane">
				<?php echo form_open_multipart(base_url('lesson_plan/manage_lesson_plan'), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('class')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("class_id", $arrayClass, "", "class='form-control' id='modal_class_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('section')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="section_id" id="modal_section_id" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
								<option value=""><?=translate('select_class_first')?></option>
							</select>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="subject_id" id="modal_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select_class_first')?></option>
							</select>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('topic')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="topic_id" id="modal_topic_id" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select_subject_first')?></option>
							</select>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('lesson')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="lesson_id" id="modal_lesson_id" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select_topic_first')?></option>
							</select>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="date" value="<?=date('Y-m-d')?>" data-plugin-datepicker autocomplete="off" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('time')?></label>
						<div class="col-md-6">
							<div class="row">
								<div class="col-xs-6">
									<input type="text" class="form-control" name="time_from" placeholder="10:00 AM" />
								</div>
								<div class="col-xs-6">
									<input type="text" class="form-control" name="time_to" placeholder="10:45 AM" />
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
								echo form_dropdown("status", $statusList, "Pending", "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('objectives') ?? ($is_bn ? 'শিখনফল / উদ্দেশ্য' : 'Objectives')?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="objectives" rows="3" placeholder="<?=$is_bn ? 'এই পাঠ শেষে শিক্ষার্থীরা কী অর্জন করবে তা লিখুন...' : 'Enter lesson objectives / learning outcomes...'?>"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('teaching_method') ?? ($is_bn ? 'শিক্ষণ পদ্ধতি ও কৌশল' : 'Teaching Method')?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="teaching_method" rows="3" placeholder="<?=$is_bn ? 'কী পদ্ধতিতে পড়াবেন (আলোচনা, প্রদর্শন, কুইজ ইত্যাদি)...' : 'Teaching method, presentation and pedagogical strategy...'?>"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('homework') ?? ($is_bn ? 'বাড়ির কাজ / অ্যাসাইনমেন্ট' : 'Homework')?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="homework" rows="2" placeholder="<?=$is_bn ? 'শিক্ষার্থীদের বাড়ির কাজ...' : 'Homework or assignments for students...'?>"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('attachment')?></label>
						<div class="col-md-6">
							<input type="file" name="attachment" class="dropify" data-height="80" />
						</div>
					</div>
					<footer class="panel-footer mt-md">
						<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- Plan View Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="planModal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-chalkboard-teacher"></i> <?=translate('lesson_plan')?> <?=translate('details')?></h4>
		</header>
		<div class="panel-body" id="modalPlanContent">
			<div class="text-center" style="padding: 30px;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?=translate('close')?></button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
$(document).ready(function () {
	// Filter class change
	$('#filter_class_id').on('change', function() {
		var classID = $(this).val();
		$.ajax({
			url: base_url + 'ajax/getSectionByClass',
			type: 'POST',
			data: { class_id: classID },
			success: function (response) {
				$('#filter_section_id').html(response).trigger('change.select2');
			}
		});
		$.ajax({
			url: base_url + 'ajax/getDataByBranch',
			type: 'POST',
			data: { table: 'subject', branch_id: '<?=$branch_id?>', class_id: classID },
			success: function (response) {
				$('#filter_subject_id').html(response).trigger('change.select2');
			}
		});
	});

	// Create modal class change
	$('#modal_class_id').on('change', function() {
		var classID = $(this).val();
		$.ajax({
			url: base_url + 'ajax/getSectionByClass',
			type: 'POST',
			data: { class_id: classID },
			success: function (response) {
				$('#modal_section_id').html(response).trigger('change.select2');
			}
		});
		$.ajax({
			url: base_url + 'ajax/getDataByBranch',
			type: 'POST',
			data: { table: 'subject', branch_id: '<?=$branch_id?>', class_id: classID },
			success: function (response) {
				$('#modal_subject_id').html(response).trigger('change.select2');
			}
		});
	});

	// Create modal subject change -> load topics
	$('#modal_subject_id, #modal_section_id').on('change', function() {
		var classID = $('#modal_class_id').val();
		var sectionID = $('#modal_section_id').val();
		var subjectID = $('#modal_subject_id').val();
		if (classID && sectionID && subjectID) {
			$.ajax({
				url: base_url + 'lesson_plan/getTopicsBySubject',
				type: 'POST',
				data: { class_id: classID, section_id: sectionID, subject_id: subjectID },
				success: function (response) {
					$('#modal_topic_id').html(response).trigger('change.select2');
					$('#modal_lesson_id').html('<option value=""><?=translate("select_topic_first")?></option>').trigger('change.select2');
				}
			});
		}
	});

	// Create modal topic change -> load lessons
	$('#modal_topic_id').on('change', function() {
		var topicID = $(this).val();
		if (topicID) {
			$.ajax({
				url: base_url + 'lesson_plan/getLessonsByTopic',
				type: 'POST',
				data: { topic_id: topicID },
				success: function (response) {
					$('#modal_lesson_id').html(response).trigger('change.select2');
				}
			});
		}
	});

	// View Plan Modal
	$('.btn-view-plan').on('click', function() {
		var planId = $(this).data('id');
		$('#modalPlanContent').html('<div class="text-center" style="padding: 30px;"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
		$.magnificPopup.open({
			items: { src: '#planModal' },
			type: 'inline',
			preloader: false,
			modal: true
		});
		$.ajax({
			url: base_url + 'lesson_plan/view_lesson_plan/' + planId,
			type: 'GET',
			success: function(res) {
				$('#modalPlanContent').html(res);
			}
		});
	});
});
</script>

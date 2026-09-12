<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter')?></h4>
	</header>
	<div class="panel-body">
		<?php echo form_open(base_url('lesson_plan/lesson'), array('method' => 'get')); ?>
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
				<label class="control-label"><?=translate('topic')?></label>
				<select name="topic_id" id="filter_topic_id" class="form-control" data-plugin-selectTwo data-width="100%">
					<option value=""><?=translate('select')?></option>
					<?php
					if (!empty($class_id) && !empty($section_id) && !empty($subject_id)) {
						$topics = $this->lesson_plan_model->getTopicsBySubject($class_id, $section_id, $subject_id);
						foreach ($topics as $t) {
							$sel = ($t['id'] == $topic_id) ? 'selected' : '';
							echo "<option value='{$t['id']}' {$sel}>" . html_escape($t['name']) . "</option>";
						}
					}
					?>
				</select>
			</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div class="col-md-12 text-right">
				<button type="submit" class="btn btn-default"><i class="fas fa-search"></i> <?=translate('filter')?></button>
				<a href="<?=base_url('lesson_plan/lesson')?>" class="btn btn-default"><i class="fas fa-redo"></i> <?=translate('reset')?></a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</section>

<div class="row">
<?php if (get_permission('lesson_manage', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('add') . ' ' . translate('lesson')?></h4>
			</header>
			<?php echo form_open(base_url('lesson_plan/lesson'), array('class' => 'frm-submit')); ?>
			<div class="panel-body">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $branch_id, "class='form-control' id='branch_id' data-width='100%' data-plugin-selectTwo");
						?>
						<span class="error"></span>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$arrayClass = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='create_class_id' onchange='getSectionByClass(this.value, 0, \"#create_section_id\")' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
					<?php
						$arraySection = $this->app_lib->getSections(set_value('class_id'));
						echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='create_section_id' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
					<select name="subject_id" id="create_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select_class_and_section_first')?></option>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('topic')?> <span class="required">*</span></label>
					<select name="topic_id" id="create_topic_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select_subject_first')?></option>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=translate('lesson_name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" placeholder="<?=translate('lesson_name')?>" value="<?=set_value('name')?>" />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
					<i class="fas fa-plus-circle"></i> <?=translate('save')?>
				</button>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
	<div class="col-md-7">
<?php else: ?>
	<div class="col-md-12">
<?php endif; ?>
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('lesson_list')?></h4>
			</header>
			<div class="panel-body">
				<table class="table table-bordered table-hover table-condensed mb-none table-export">
					<thead>
						<tr>
							<th style="width: 40px;"><?=translate('sl')?></th>
							<?php if (is_superadmin_loggedin()): ?>
								<th><?=translate('branch')?></th>
							<?php endif; ?>
							<th><?=translate('class')?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('subject')?></th>
							<th><?=translate('topic')?></th>
							<th><?=translate('lesson_name')?></th>
							<th><?=translate('status')?></th>
							<th style="width: 90px;"><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (!empty($lesson_list)) {
							foreach ($lesson_list as $row) {
								$st = !empty($row['syllabus_status']) ? $row['syllabus_status'] : 'pending';
								$badgeClass = 'label-warning';
								if ($st === 'completed') {
									$badgeClass = 'label-success';
								} elseif ($st === 'in_progress') {
									$badgeClass = 'label-info';
								}
								?>
								<tr>
									<td><?=$count++?></td>
									<?php if (is_superadmin_loggedin()): ?>
										<td><?=html_escape($row['branch_name'])?></td>
									<?php endif; ?>
									<td><?=html_escape($row['class_name'])?></td>
									<td><?=html_escape($row['section_name'])?></td>
									<td><?=html_escape($row['subject_name'])?> (<?=html_escape($row['subject_code'])?>)</td>
									<td><span class="text-weight-semibold"><?=html_escape($row['topic_name'])?></span></td>
									<td><strong><?=html_escape($row['name'])?></strong></td>
									<td>
										<span class="label <?=$badgeClass?>"><?=translate($st)?></span>
										<?php if (!empty($row['syllabus_date'])): ?>
											<br><small class="text-muted"><i class="far fa-calendar-alt"></i> <?=date('d M Y', strtotime($row['syllabus_date']))?></small>
										<?php endif; ?>
									</td>
									<td>
										<?php if (get_permission('lesson_manage', 'is_edit')): ?>
											<a href="<?=base_url('lesson_plan/lesson_edit/' . $row['id'])?>" class="btn btn-default btn-circle icon" title="<?=translate('edit')?>">
												<i class="fas fa-pen-nib"></i>
											</a>
										<?php endif; ?>
										<?php if (get_permission('lesson_manage', 'is_delete')): ?>
											<?=btn_delete('lesson_plan/lesson_delete/' . $row['id'])?>
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
	</div>
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
				$('#filter_topic_id').html('<option value=""><?=translate("select")?></option>');
			}
		});
	});

	$('#filter_subject_id').on('change', function () {
		var classID = $('#filter_class_id').val();
		var sectionID = $('#filter_section_id').val();
		var subjectID = $(this).val();
		$.ajax({
			url: base_url + 'lesson_plan/getTopicsBySubject',
			type: 'POST',
			data: { class_id: classID, section_id: sectionID, subject_id: subjectID },
			success: function (data) {
				$('#filter_topic_id').html(data);
			}
		});
	});

	// Create Cascading
	$('#create_section_id').on('change', function () {
		var classID = $('#create_class_id').val();
		var sectionID = $(this).val();
		$.ajax({
			url: base_url + 'subject/getByClassSection',
			type: 'POST',
			data: { classID: classID, sectionID: sectionID },
			success: function (data) {
				$('#create_subject_id').html(data);
				$('#create_topic_id').html('<option value=""><?=translate("select_subject_first")?></option>');
			}
		});
	});

	$('#create_subject_id').on('change', function () {
		var classID = $('#create_class_id').val();
		var sectionID = $('#create_section_id').val();
		var subjectID = $(this).val();
		$.ajax({
			url: base_url + 'lesson_plan/getTopicsBySubject',
			type: 'POST',
			data: { class_id: classID, section_id: sectionID, subject_id: subjectID },
			success: function (data) {
				$('#create_topic_id').html(data);
			}
		});
	});
});
</script>

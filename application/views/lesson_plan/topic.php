<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter')?></h4>
	</header>
	<div class="panel-body">
		<?php echo form_open(base_url('lesson_plan/topic'), array('method' => 'get')); ?>
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
			<div class="col-md-3 mb-sm" style="margin-top: 24px;">
				<button type="submit" class="btn btn-default"><i class="fas fa-search"></i> <?=translate('filter')?></button>
				<a href="<?=base_url('lesson_plan/topic')?>" class="btn btn-default"><i class="fas fa-redo"></i> <?=translate('reset')?></a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</section>

<div class="row">
<?php if (get_permission('lesson_topic', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('add') . ' ' . translate('topic')?></h4>
			</header>
			<?php echo form_open(base_url('lesson_plan/topic'), array('class' => 'frm-submit')); ?>
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
					<label class="control-label"><?=translate('topic_name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" placeholder="<?=translate('topic_name')?>" value="<?=set_value('name')?>" />
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
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('topic_list')?></h4>
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
							<th><?=translate('topic_name')?></th>
							<th><?=translate('total_lessons')?></th>
							<th style="width: 90px;"><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (!empty($topic_list)) {
							foreach ($topic_list as $row) {
								?>
								<tr>
									<td><?=$count++?></td>
									<?php if (is_superadmin_loggedin()): ?>
										<td><?=html_escape($row['branch_name'])?></td>
									<?php endif; ?>
									<td><?=html_escape($row['class_name'])?></td>
									<td><?=html_escape($row['section_name'])?></td>
									<td><?=html_escape($row['subject_name'])?> (<?=html_escape($row['subject_code'])?>)</td>
									<td><strong><?=html_escape($row['name'])?></strong></td>
									<td><span class="label label-primary-custom"><?=$row['total_lessons']?></span></td>
									<td>
										<?php if (get_permission('lesson_topic', 'is_edit')): ?>
											<a href="<?=base_url('lesson_plan/topic_edit/' . $row['id'])?>" class="btn btn-default btn-circle icon" title="<?=translate('edit')?>">
												<i class="fas fa-pen-nib"></i>
											</a>
										<?php endif; ?>
										<?php if (get_permission('lesson_topic', 'is_delete')): ?>
											<?=btn_delete('lesson_plan/topic_delete/' . $row['id'])?>
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
	// Cascading dropdown for Filter
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

	// Cascading dropdown for Create
	$('#create_section_id').on('change', function () {
		var classID = $('#create_class_id').val();
		var sectionID = $(this).val();
		$.ajax({
			url: base_url + 'subject/getByClassSection',
			type: 'POST',
			data: { classID: classID, sectionID: sectionID },
			success: function (data) {
				$('#create_subject_id').html(data);
			}
		});
	});
});
</script>

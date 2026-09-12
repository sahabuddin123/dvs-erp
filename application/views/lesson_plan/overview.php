<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter')?></h4>
	</header>
	<div class="panel-body">
		<?php echo form_open(base_url('lesson_plan/overview'), array('method' => 'get')); ?>
		<div class="row">
			<div class="col-md-3 mb-sm">
				<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
				<?php
					$arrayClass = $this->app_lib->getClass($branch_id);
					echo form_dropdown("class_id", $arrayClass, $class_id, "class='form-control' id='filter_class_id' onchange='getSectionByClass(this.value, 0, \"#filter_section_id\")' data-plugin-selectTwo data-width='100%' required");
				?>
			</div>
			<div class="col-md-3 mb-sm">
				<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
				<?php
					$arraySection = $this->app_lib->getSections($class_id);
					echo form_dropdown("section_id", $arraySection, $section_id, "class='form-control' id='filter_section_id' data-plugin-selectTwo data-width='100%' required");
				?>
			</div>
			<div class="col-md-4 mb-sm">
				<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
				<select name="subject_id" id="filter_subject_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
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
			<div class="col-md-2 mb-sm" style="margin-top: 24px;">
				<button type="submit" class="btn btn-default btn-block"><i class="fas fa-search"></i> <?=translate('search')?></button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</section>

<?php if (!empty($summary)): ?>
<div class="row">
	<div class="col-md-3 col-sm-6">
		<div class="panel panel-custom mb-md">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary"><i class="fas fa-book-reader"></i></div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=translate('total_topics')?></h4>
							<div class="info"><strong class="amount"><?=$summary['total_topics']?></strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6">
		<div class="panel panel-custom mb-md">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-tertiary"><i class="fas fa-chalkboard"></i></div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=translate('total_lessons')?></h4>
							<div class="info"><strong class="amount"><?=$summary['total_lessons']?></strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6">
		<div class="panel panel-custom mb-md">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-success"><i class="fas fa-check-circle"></i></div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=translate('completed')?></h4>
							<div class="info"><strong class="amount text-success"><?=$summary['completed_lessons']?></strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6">
		<div class="panel panel-custom mb-md">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-warning"><i class="fas fa-hourglass-half"></i></div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=translate('pending')?> / <?=translate('in_progress')?></h4>
							<div class="info"><strong class="amount text-warning"><?=$summary['pending_lessons'] + $summary['in_progress_lessons']?></strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<section class="panel" id="printArea">
	<header class="panel-heading">
		<div class="panel-btn">
			<button class="btn btn-default btn-circle" onclick="window.print()"><i class="fas fa-print"></i> <?=translate('print')?></button>
		</div>
		<h4 class="panel-title"><i class="fas fa-chart-line"></i> <?=translate('syllabus_overview_progress')?></h4>
	</header>
	<div class="panel-body">
		<!-- Overall progress bar -->
		<div class="row mb-lg">
			<div class="col-md-12">
				<div class="progress progress-xl progress-squared" style="height: 28px; margin-bottom: 5px;">
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$summary['overall_progress']?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$summary['overall_progress']?>%; font-size: 14px; line-height: 28px; font-weight: bold;">
						<?=$summary['overall_progress']?>% <?=translate('completed')?>
					</div>
				</div>
				<div class="text-muted text-right">
					<small><?=translate('overall_syllabus_completion_rate')?>: <?=$summary['completed_lessons']?> / <?=$summary['total_lessons']?> <?=translate('lessons')?></small>
				</div>
			</div>
		</div>

		<!-- Topic wise breakdown -->
		<?php if (!empty($summary['topics'])): ?>
			<div class="panel-group" id="accordionTopics">
				<?php foreach ($summary['topics'] as $index => $topic): ?>
					<div class="panel panel-accordion">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionTopics" href="#collapseTopic<?=$topic['id']?>">
									<i class="fas fa-folder-open"></i> <strong><?=html_escape($topic['name'])?></strong>
									<span class="pull-right" style="margin-right: 15px;">
										<span class="label <?=$topic['progress'] == 100 ? 'label-success' : ($topic['progress'] > 0 ? 'label-info' : 'label-default')?>">
											<?=$topic['progress']?>% <?=translate('completed')?> (<?=$topic['completed_lessons']?>/<?=$topic['total_lessons']?>)
										</span>
									</span>
								</a>
							</h4>
						</div>
						<div id="collapseTopic<?=$topic['id']?>" class="accordion-body collapse <?=$index === 0 ? 'in' : ''?>">
							<div class="panel-body">
								<?php if (!empty($topic['lessons'])): ?>
									<table class="table table-bordered table-striped table-condensed mb-none">
										<thead>
											<tr>
												<th style="width: 40px;"><?=translate('sl')?></th>
												<th><?=translate('lesson_name')?></th>
												<th style="width: 120px;"><?=translate('status')?></th>
												<th style="width: 140px;"><?=translate('completion_date')?></th>
												<th style="width: 160px;"><?=translate('time')?></th>
												<th><?=translate('notes')?></th>
												<th style="width: 100px;"><?=translate('attachment')?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$lCount = 1;
											foreach ($topic['lessons'] as $l):
												$st = !empty($l['syllabus_status']) ? $l['syllabus_status'] : 'pending';
												$badge = 'label-warning';
												if ($st === 'completed') {
													$badge = 'label-success';
												} elseif ($st === 'in_progress') {
													$badge = 'label-info';
												}
											?>
											<tr>
												<td><?=$lCount++?></td>
												<td><strong><?=html_escape($l['name'])?></strong></td>
												<td><span class="label <?=$badge?>"><?=translate($st)?></span></td>
												<td><?=!empty($l['syllabus_date']) ? date('d M Y', strtotime($l['syllabus_date'])) : '-'?></td>
												<td>
													<?php
													if (!empty($l['time_from']) && !empty($l['time_to'])) {
														echo date('h:i A', strtotime($l['time_from'])) . ' - ' . date('h:i A', strtotime($l['time_to']));
													} else {
														echo '-';
													}
													?>
												</td>
												<td><?=!empty($l['syllabus_note']) ? nl2br(html_escape($l['syllabus_note'])) : '-'?></td>
												<td>
													<?php if (!empty($l['syllabus_attachment'])): ?>
														<a href="<?=base_url('uploads/attachments/lesson_plan/' . $l['syllabus_attachment'])?>" target="_blank" class="btn btn-default btn-xs">
															<i class="fas fa-download"></i> <?=translate('download')?>
														</a>
													<?php else: ?>
														-
													<?php endif; ?>
												</td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<p class="text-muted mb-none"><?=translate('no_lessons_found_for_this_topic')?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="alert alert-subl text-center"><?=translate('no_information_available')?></div>
		<?php endif; ?>
	</div>
</section>
<?php elseif ($_GET): ?>
<section class="panel">
	<div class="panel-body text-center">
		<h5 class="text-danger"><?=translate('no_information_available')?></h5>
	</div>
</section>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function () {
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
});
</script>

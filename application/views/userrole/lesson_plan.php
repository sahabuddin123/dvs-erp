<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-chalkboard-teacher"></i> <?=translate('lesson_plan')?> - <?=translate('syllabus_status')?></h4>
	</header>
	<div class="panel-body">
		<!-- Subject Selector -->
		<div class="row mb-md">
			<div class="col-md-6 col-sm-12">
				<form action="<?=base_url('userrole/lesson_plan')?>" method="get" id="subjectForm">
					<label class="control-label"><strong><?=translate('select_subject')?>:</strong></label>
					<select name="subject_id" class="form-control" data-plugin-selectTwo onchange="this.form.submit()">
						<?php if (!empty($subjects)): ?>
							<?php foreach ($subjects as $s): ?>
								<option value="<?=$s['subject_id']?>" <?=$s['subject_id'] == $subject_id ? 'selected' : ''?>>
									<?=html_escape($s['subjectname'])?> (<?=html_escape($s['subject_code'])?>)
								</option>
							<?php endforeach; ?>
						<?php else: ?>
							<option value=""><?=translate('no_subject_assigned')?></option>
						<?php endif; ?>
					</select>
				</form>
			</div>
			<div class="col-md-6 col-sm-12 text-right">
				<span class="text-muted" style="display: inline-block; margin-top: 25px;">
					<i class="fas fa-graduation-cap"></i> <?=translate('class')?>: <strong><?=html_escape($student['class_name'])?> (<?=html_escape($student['section_name'])?>)</strong>
				</span>
			</div>
		</div>

		<?php if (!empty($summary)): ?>
			<!-- Summary Counters -->
			<div class="row mb-md">
				<div class="col-md-3 col-xs-6">
					<div class="panel panel-custom mb-sm">
						<div class="panel-body text-center" style="padding: 10px;">
							<h5 class="text-muted mb-xs"><?=translate('total_topics')?></h5>
							<h3 class="text-semibold text-dark mt-none mb-none"><?=$summary['total_topics']?></h3>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-6">
					<div class="panel panel-custom mb-sm">
						<div class="panel-body text-center" style="padding: 10px;">
							<h5 class="text-muted mb-xs"><?=translate('total_lessons')?></h5>
							<h3 class="text-semibold text-dark mt-none mb-none"><?=$summary['total_lessons']?></h3>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-6">
					<div class="panel panel-custom mb-sm">
						<div class="panel-body text-center" style="padding: 10px;">
							<h5 class="text-muted mb-xs"><?=translate('completed')?></h5>
							<h3 class="text-semibold text-success mt-none mb-none"><?=$summary['completed_lessons']?></h3>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-6">
					<div class="panel panel-custom mb-sm">
						<div class="panel-body text-center" style="padding: 10px;">
							<h5 class="text-muted mb-xs"><?=translate('pending')?> / <?=translate('in_progress')?></h5>
							<h3 class="text-semibold text-warning mt-none mb-none"><?=$summary['pending_lessons'] + $summary['in_progress_lessons']?></h3>
						</div>
					</div>
				</div>
			</div>

			<!-- Progress Bar -->
			<div class="row mb-lg">
				<div class="col-md-12">
					<div class="progress progress-xl progress-squared" style="height: 26px; margin-bottom: 5px;">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$summary['overall_progress']?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$summary['overall_progress']?>%; font-size: 13px; line-height: 26px; font-weight: bold;">
							<?=$summary['overall_progress']?>% <?=translate('completed')?>
						</div>
					</div>
				</div>
			</div>

			<!-- Topics & Lessons -->
			<?php if (!empty($summary['topics'])): ?>
				<div class="panel-group" id="accordionPortalTopics">
					<?php foreach ($summary['topics'] as $index => $topic): ?>
						<div class="panel panel-accordion">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPortalTopics" href="#portalTopic<?=$topic['id']?>">
										<i class="fas fa-book-open"></i> <strong><?=html_escape($topic['name'])?></strong>
										<span class="pull-right" style="margin-right: 15px;">
											<span class="label <?=$topic['progress'] == 100 ? 'label-success' : ($topic['progress'] > 0 ? 'label-info' : 'label-default')?>">
												<?=$topic['progress']?>% <?=translate('completed')?> (<?=$topic['completed_lessons']?>/<?=$topic['total_lessons']?>)
											</span>
										</span>
									</a>
								</h4>
							</div>
							<div id="portalTopic<?=$topic['id']?>" class="accordion-body collapse <?=$index === 0 ? 'in' : ''?>">
								<div class="panel-body">
									<?php if (!empty($topic['lessons'])): ?>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-condensed mb-none">
												<thead>
													<tr>
														<th style="width: 40px;"><?=translate('sl')?></th>
														<th><?=translate('lesson_name')?></th>
														<th style="width: 120px;"><?=translate('status')?></th>
														<th style="width: 130px;"><?=translate('date')?></th>
														<th><?=translate('notes')?></th>
														<th style="width: 100px;"><?=translate('material')?></th>
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
										</div>
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
		<?php else: ?>
			<div class="alert alert-subl text-center"><?=translate('no_information_available')?></div>
		<?php endif; ?>
	</div>
</section>

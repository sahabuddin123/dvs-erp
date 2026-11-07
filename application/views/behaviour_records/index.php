<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter')?></h4>
	</header>
	<div class="panel-body">
		<form method="get" action="<?=base_url('behaviour_records')?>">
			<div class="row">
				<div class="col-md-5 mb-sm">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$classes = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $classes, $class_id, "class='form-control' id='filter_class_id' required data-plugin-selectTwo data-width='100%'");
					?>
				</div>
				<div class="col-md-5 mb-sm">
					<label class="control-label"><?=translate('section')?></label>
					<?php
						$sections = array('' => translate('all_select'));
						if (!empty($class_id)) {
							$sec_list = $this->app_lib->getSections($class_id, false);
							foreach ($sec_list as $s_id => $s_name) {
								if ($s_id !== '' && $s_id !== 'all') {
									$sections[$s_id] = $s_name;
								}
							}
						}
						echo form_dropdown("section_id", $sections, $section_id, "class='form-control' id='filter_section_id' data-plugin-selectTwo data-width='100%'");
					?>
				</div>
				<div class="col-md-2 mb-sm" style="margin-top: 25px;">
					<button type="submit" class="btn btn-default btn-block"><i class="fas fa-search"></i> <?=translate('filter')?></button>
				</div>
			</div>
		</form>
	</div>
</section>

<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> Behaviour Records List (আচরণ রেকর্ডসমূহ)</a>
					</li>
					<li>
						<a href="#create" data-toggle="tab"><i class="fas fa-plus-circle"></i> Assign Behaviour (নতুন আচরণ যোগ)</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="list" class="tab-pane active">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-condensed mb-none table-export">
								<thead>
									<tr>
										<th width="40"><?=translate('sl')?></th>
										<th><?=translate('student')?></th>
										<th><?=translate('class')?></th>
										<th><?=translate('roll')?></th>
										<th>Incident / আচরণ</th>
										<th>Points / পয়েন্ট</th>
										<th><?=translate('date')?></th>
										<th>Comment / মন্তব্য</th>
										<th>Assigned By</th>
										<th><?=translate('action')?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if (!empty($records)): 
										$count = 1;
										foreach ($records as $row):
									?>
									<tr>
										<td><?=$count++?></td>
										<td>
											<strong><?=html_escape($row['first_name'] . ' ' . $row['last_name'])?></strong><br>
											<small class="text-muted">Reg: <?=$row['register_no']?></small>
										</td>
										<td><?=$row['class_name'] . ' (' . $row['section_name'] . ')'?></td>
										<td><?=$row['roll'] ?: 'N/A'?></td>
										<td><strong><?=html_escape($row['title'])?></strong></td>
										<td>
											<?php if ($row['type'] == 'positive'): ?>
												<span class="label label-success" style="font-size: 12px; font-weight: bold;"><i class="fas fa-arrow-up"></i> +<?=$row['point']?> (ইতিবাচক)</span>
											<?php else: ?>
												<span class="label label-danger" style="font-size: 12px; font-weight: bold;"><i class="fas fa-arrow-down"></i> -<?=$row['point']?> (নেতিবাচক)</span>
											<?php endif; ?>
										</td>
										<td><?=_d($row['date'])?></td>
										<td><?=html_escape($row['comment'] ?: '-')?></td>
										<td><small><?=html_escape($row['staff_name'] ?: 'Admin')?></small></td>
										<td>
											<a href="<?=base_url('behaviour_records/delete/' . $row['id'])?>" class="btn btn-default btn-circle icon" onclick="confirm_modal('<?=base_url('behaviour_records/delete/' . $row['id'])?>'); return false;" data-toggle="tooltip" title="<?=translate('delete')?>">
												<i class="fas fa-trash-alt text-danger"></i>
											</a>
										</td>
									</tr>
									<?php 
										endforeach; 
									else: ?>
									<tr>
										<td colspan="10" class="text-center text-muted"><?=translate('no_information_available')?></td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<div id="create" class="tab-pane">
						<?php echo form_open('behaviour_records/save', array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?=$branch_id?>">
							
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('class')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										echo form_dropdown("class_id", $classes, "", "class='form-control' id='assign_class_id' required data-plugin-selectTwo data-width='100%'");
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('section')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<select name="section_id" id="assign_section_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
										<option value=""><?=translate('select')?></option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('student')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<select name="student_id" id="assign_student_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
										<option value=""><?=translate('select_class_first')?></option>
									</select>
									<span class="error"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Predefined Incident (সংরক্ষিত খাত)</label>
								<div class="col-md-6">
									<select name="incident_id" id="incident_select" class="form-control" data-plugin-selectTwo data-width="100%">
										<option value="">Custom Incident (ম্যানুয়ালি লিখুন)</option>
										<?php foreach ($incidents as $inc): ?>
										<option value="<?=$inc['id']?>" data-title="<?=html_escape($inc['title'])?>" data-point="<?=$inc['point']?>" data-type="<?=$inc['type']?>">
											<?=html_escape($inc['title'])?> (<?=$inc['type'] == 'positive' ? '+' : '-'?><?=$inc['point']?> pts)
										</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Incident Title (আচরণের বিবরণ) <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="title" id="incident_title" placeholder="যেমন: সহপাঠীকে সাহায্য করেছে / শ্রেণিতে নিয়মিত শৃঙ্খলা ভঙ্গ" required />
									<span class="error"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Type (আচরণের ধরন) <span class="required">*</span></label>
								<div class="col-md-6">
									<select name="type" id="incident_type" class="form-control" data-plugin-selectTwo data-width="100%" required>
										<option value="positive">Positive / ইতিবাচক (+)</option>
										<option value="negative">Negative / নেতিবাচক (-)</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Point / পয়েন্ট <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="point" id="incident_point" value="1" min="1" max="100" required />
									<span class="error"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="date" value="<?=date('Y-m-d')?>" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true, "endDate": "+0d" }' required />
									<span class="error"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Comment / বিস্তারিত মন্তব্য</label>
								<div class="col-md-6">
									<textarea class="form-control" name="comment" rows="3" placeholder="আচরণের কারণ বা অতিরিক্ত মন্তব্য..."></textarea>
								</div>
							</div>

							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-offset-3 col-md-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?=translate('save')?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	// Auto populate sections on class change for filter form
	$('#filter_class_id').on('change', function() {
		var classID = $(this).val();
		$('#filter_section_id').html('<option value=""><?=translate('loading')?>...</option>');
		if (classID) {
			$.ajax({
				url: base_url + 'ajax/getSectionByClass',
				type: 'POST',
				data: { class_id: classID },
				success: function(response) {
					$('#filter_section_id').html(response).trigger('change');
				}
			});
		} else {
			$('#filter_section_id').html('<option value=""><?=translate('all_select')?></option>').trigger('change');
		}
	});

	// Auto populate sections on class change for assign form
	$('#assign_class_id').on('change', function() {
		var classID = $(this).val();
		$('#assign_section_id').html('<option value=""><?=translate('loading')?>...</option>');
		$('#assign_student_id').html('<option value=""><?=translate('select_class_first')?></option>');
		if (classID) {
			$.ajax({
				url: base_url + 'ajax/getSectionByClass',
				type: 'POST',
				data: { class_id: classID },
				success: function(response) {
					$('#assign_section_id').html(response).trigger('change');
				}
			});
		}
	});

	// Auto populate students on section change
	$('#assign_section_id').on('change', function() {
		var classID = $('#assign_class_id').val();
		var sectionID = $(this).val();
		if (classID) {
			$('#assign_student_id').html('<option value=""><?=translate('loading')?>...</option>');
			$.ajax({
				url: base_url + 'behaviour_records/get_students_by_section',
				type: 'POST',
				data: { class_id: classID, section_id: sectionID },
				success: function(response) {
					$('#assign_student_id').html(response);
				}
			});
		}
	});

	// Predefined incident auto filler
	$('#incident_select').on('change', function() {
		var opt = $(this).find('option:selected');
		if (opt.val()) {
			$('#incident_title').val(opt.data('title'));
			$('#incident_point').val(opt.data('point'));
			$('#incident_type').val(opt.data('type')).trigger('change');
		}
	});
});
</script>


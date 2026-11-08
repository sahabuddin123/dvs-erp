<div class="row">
	<div class="col-md-12">
		<!-- Filter Panel -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-filter"></i> <?=translate('filter') . ' ' . translate('seat_plan')?>
				</h4>
			</header>
			<?php echo form_open(base_url('seat_plan'), array('method' => 'get', 'class' => 'validate')); ?>
			<div class="panel-body">
				<div class="row mb-sm">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="col-md-3 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' onchange='getClassByBranch(this.value)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
					<?php endif; ?>

					<div class="col-md-<?=is_superadmin_loggedin() ? '3' : '4'?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam')?> <span class="required">*</span></label>
							<select name="exam_id" id="filter_exam_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
								<option value=""><?=translate('select') . ' ' . translate('exam')?></option>
								<?php if (!empty($exams)): foreach ($exams as $exam): ?>
									<option value="<?=$exam['id']?>" <?=(isset($exam_id) && $exam_id == $exam['id']) ? 'selected' : ''?>>
										<?=html_escape($exam['name'])?> <?=!empty($exam['term_name']) ? ' (' . html_escape($exam['term_name']) . ')' : ''?>
									</option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>

					<div class="col-md-<?=is_superadmin_loggedin() ? '2' : '3'?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam_hall')?></label>
							<select name="hall_id" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('all_select')?></option>
								<?php if (!empty($halls)): foreach ($halls as $hall): ?>
									<option value="<?=$hall['id']?>" <?=(isset($hall_id) && $hall_id == $hall['id']) ? 'selected' : ''?>>
										<?=html_escape($hall['hall_no'])?> (<?=translate('capacity')?>: <?=$hall['seats']?>)
									</option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>

					<div class="col-md-<?=is_superadmin_loggedin() ? '2' : '3'?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id', $class_id), "class='form-control' id='filter_class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%'");
							?>
						</div>
					</div>

					<div class="col-md-<?=is_superadmin_loggedin() ? '2' : '2'?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?></label>
							<?php
								$arraySection = !empty($class_id) ? $this->app_lib->getSections($class_id) : array('' => translate('select_class_first'));
								echo form_dropdown("section_id", $arraySection, set_value('section_id', $section_id), "class='form-control' id='section_id' data-plugin-selectTwo data-width='100%'");
							?>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default mr-xs">
							<i class="fas fa-filter"></i> <?=translate('filter')?>
						</button>
						<a href="<?=base_url('seat_plan')?>" class="btn btn-default">
							<i class="fas fa-undo"></i> <?=translate('reset')?>
						</a>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>

		<?php if (!empty($exam_id)): ?>
		<!-- Action Toolbar and Capacity Summary -->
		<div class="row mb-md">
			<div class="col-md-12">
				<div class="d-flex justify-content-between align-items-center flex-wrap" style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom: 15px;">
					<div>
						<h4 style="margin: 0; font-weight: 700; color: #1e293b;">
							<i class="fas fa-th-large text-primary"></i> <?=translate('seat_plan_allocation')?>
						</h4>
						<small class="text-muted"><?=translate('exam')?>: <strong><?=!empty($seat_plans[0]['exam_name']) ? html_escape($seat_plans[0]['exam_name']) : translate('exam')?></strong> | <?=translate('allocated_students')?>: <strong><?=count($seat_plans)?></strong></small>
					</div>
					<div style="display:flex; gap:8px; flex-wrap:wrap;">
						<?php if (get_permission('seat_plan', 'is_add')): ?>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateSeatPlanModal">
							<i class="fas fa-magic"></i> <?=translate('generate_seat_plan')?>
						</button>
						<?php endif; ?>

						<?php if (!empty($seat_plans)): ?>
						<a href="<?=base_url('seat_plan/print_card?exam_id=' . $exam_id . (!empty($hall_id) ? '&hall_id=' . $hall_id : '') . (!empty($class_id) ? '&class_id=' . $class_id : ''))?>" class="btn btn-success" target="_blank">
							<i class="fas fa-id-card"></i> <?=translate('print_seat_cards')?>
						</a>

						<a href="<?=base_url('seat_plan/room_report?exam_id=' . $exam_id . (!empty($hall_id) ? '&hall_id=' . $hall_id : ''))?>" class="btn btn-info" target="_blank">
							<i class="fas fa-file-alt"></i> <?=translate('room_attendance_sheet')?>
						</a>

						<?php if (get_permission('seat_plan', 'is_delete')): ?>
						<button type="button" class="btn btn-danger" onclick="confirmClearPlan(<?=$exam_id?>, <?=!empty($hall_id) ? $hall_id : 'null'?>)">
							<i class="fas fa-trash-alt"></i> <?=translate('clear_seat_plan')?>
						</button>
						<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Hall Capacity Progress Cards -->
		<?php if (!empty($hall_summary)): ?>
		<div class="row mb-md">
			<?php foreach ($hall_summary as $hsum): 
				$badgeClass = ($hsum['percentage'] >= 100) ? 'danger' : (($hsum['percentage'] >= 75) ? 'warning' : 'success');
			?>
			<div class="col-md-3 col-sm-6 mb-sm">
				<div class="panel" style="border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
					<div class="panel-body p-sm">
						<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 5px;">
							<strong style="font-size: 14px; color:#1e293b;">
								<i class="fas fa-door-open text-primary"></i> <?=html_escape($hsum['hall_no'])?>
							</strong>
							<span class="badge badge-<?=$badgeClass?>"><?=$hsum['allocated']?> / <?=$hsum['capacity']?></span>
						</div>
						<div class="progress progress-xs mb-none" style="height: 6px; border-radius: 4px;">
							<div class="progress-bar progress-bar-<?=$badgeClass?>" role="progressbar" style="width: <?=$hsum['percentage']?>%;"></div>
						</div>
						<div class="text-muted mt-xs" style="font-size: 11px; display:flex; justify-content:space-between;">
							<span><?=translate('available_seats')?>: <strong><?=$hsum['remaining']?></strong></span>
							<span><?=$hsum['percentage']?>%</span>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<!-- Allocated Students Table -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-users"></i> <?=translate('hall_wise_seat_plan')?> (<?=count($seat_plans)?>)
				</h4>
			</header>
			<div class="panel-body">
				<?php if (!empty($seat_plans)): ?>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none" id="seatPlanDataTable">
						<thead>
							<tr style="background:#f8fafc;">
								<th class="text-center" style="width: 70px;"><?=translate('seat_no')?></th>
								<th class="text-center" style="width: 80px;"><?=translate('bench_no')?></th>
								<th style="width: 60px;"><?=translate('photo')?></th>
								<th><?=translate('student_name')?></th>
								<th><?=translate('register_no')?></th>
								<th class="text-center"><?=translate('roll')?></th>
								<th><?=translate('class')?> & <?=translate('section')?></th>
								<th><?=translate('exam_hall')?></th>
								<?php if (get_permission('seat_plan', 'is_edit') || get_permission('seat_plan', 'is_delete')): ?>
								<th class="text-center" style="width: 90px;"><?=translate('action')?></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($seat_plans as $row): 
								$photoUrl = !empty($row['photo']) && file_exists('uploads/images/student/' . $row['photo']) 
									? base_url('uploads/images/student/' . $row['photo']) 
									: base_url('uploads/app_image/default.jpg');
								$fullName = trim($row['first_name'] . ' ' . $row['last_name']);
							?>
							<tr>
								<td class="text-center">
									<span class="badge" style="background:#007F3D; color:#fff; font-size:12px; font-weight:600; padding:4px 8px;">
										<?=$row['seat_no']?>
									</span>
								</td>
								<td class="text-center">
									<strong style="color: #0f172a;"><?=$row['bench_no'] ? html_escape($row['bench_no']) : '-'?></strong>
								</td>
								<td class="text-center">
									<img src="<?=$photoUrl?>" style="width: 34px; height: 34px; object-fit: cover; border-radius: 50%; border: 1px solid #cbd5e1;" alt="photo">
								</td>
								<td>
									<strong><?=html_escape($fullName)?></strong>
								</td>
								<td>
									<code style="color: #0284c7; background: #e0f2fe; padding: 2px 6px; border-radius: 4px;"><?=$row['register_no']?></code>
								</td>
								<td class="text-center">
									<strong><?=$row['roll']?></strong>
								</td>
								<td>
									<?=html_escape($row['class_name'])?> <small class="text-muted">(<?=html_escape($row['section_name'])?>)</small>
								</td>
								<td>
									<span class="label label-primary-custom" style="background:#f1f5f9; color:#1e293b; border:1px solid #cbd5e1; padding:3px 8px; border-radius:4px; font-weight:600;">
										<i class="fas fa-door-open text-primary"></i> <?=html_escape($row['hall_no'])?>
									</span>
								</td>
								<?php if (get_permission('seat_plan', 'is_edit') || get_permission('seat_plan', 'is_delete')): ?>
								<td class="text-center">
									<?php if (get_permission('seat_plan', 'is_edit')): ?>
									<button type="button" class="btn btn-default btn-circle icon" title="<?=translate('edit')?>" 
										onclick="openEditSeatModal(<?=$row['id']?>, <?=$row['hall_id']?>, '<?=html_escape($row['seat_no'])?>', '<?=html_escape($row['bench_no'])?>', '<?=html_escape($fullName)?>')">
										<i class="fas fa-pen-nib"></i>
									</button>
									<?php endif; ?>

									<?php if (get_permission('seat_plan', 'is_delete')): ?>
									<a href="<?=base_url('seat_plan/delete_seat/' . $row['id'])?>" class="btn btn-danger btn-circle icon" title="<?=translate('delete')?>" onclick="return confirm('<?=translate('are_you_sure_to_delete')?>');">
										<i class="fas fa-trash-alt"></i>
									</a>
									<?php endif; ?>
								</td>
								<?php endif; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php else: ?>
				<div class="alert alert-subtle-warning text-center" style="background: #fffbeb; border: 1px solid #fde68a; color: #92400e; padding: 25px; border-radius: 8px;">
					<i class="fas fa-info-circle fa-2x mb-xs" style="display:block; margin: 0 auto 10px;"></i>
					<h4><?=translate('no_information_available')?></h4>
					<p><?=translate('no_seat_plan_found_for_selected_criteria')?></p>
					<?php if (get_permission('seat_plan', 'is_add')): ?>
					<button type="button" class="btn btn-primary mt-sm" data-toggle="modal" data-target="#generateSeatPlanModal">
						<i class="fas fa-magic"></i> <?=translate('generate_seat_plan')?>
					</button>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php else: ?>
		<div class="panel">
			<div class="panel-body text-center p-xl" style="padding: 40px;">
				<i class="fas fa-chair fa-3x text-muted mb-md" style="display:block; margin-bottom:15px; color:#cbd5e1;"></i>
				<h4 style="color:#64748b;"><?=translate('please_select_an_exam_to_view_or_generate_seat_plan')?></h4>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>

<!-- ========================================== -->
<!-- Modal: Generate Seat Plan Allocation      -->
<!-- ========================================== -->
<div class="modal fade" id="generateSeatPlanModal" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background: #007F3D; color: #fff;">
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" style="color:#fff; opacity:0.8;">&times;</button>
				<h4 class="modal-title" id="generateModalLabel">
					<i class="fas fa-magic"></i> <?=translate('generate_seat_plan')?>
				</h4>
			</div>
			<?php echo form_open(base_url('seat_plan/generate'), array('id' => 'generateSeatPlanForm', 'class' => 'validate')); ?>
			<input type="hidden" name="branch_id" value="<?=$branch_id?>">
			<input type="hidden" name="session_id" value="<?=$session_id?>">
			<div class="modal-body" style="padding: 20px 25px;">
				<!-- Exam Selection -->
				<div class="form-group mb-md">
					<label class="control-label"><strong><?=translate('exam')?> <span class="required">*</span></strong></label>
					<select name="exam_id" id="modal_exam_id" class="form-control" required style="width:100%;">
						<option value=""><?=translate('select') . ' ' . translate('exam')?></option>
						<?php if (!empty($exams)): foreach ($exams as $exam): ?>
							<option value="<?=$exam['id']?>" <?=(isset($exam_id) && $exam_id == $exam['id']) ? 'selected' : ''?>>
								<?=html_escape($exam['name'])?> <?=!empty($exam['term_name']) ? ' (' . html_escape($exam['term_name']) . ')' : ''?>
							</option>
						<?php endforeach; endif; ?>
					</select>
				</div>

				<div class="row">
					<!-- Select Exam Halls -->
					<div class="col-md-6 mb-md">
						<label class="control-label"><strong><?=translate('exam_hall')?> / <?=translate('room')?> <span class="required">*</span></strong></label>
						<p class="text-muted" style="font-size:12px; margin-bottom:6px;"><?=translate('select_one_or_more_halls_to_seat_students')?></p>
						<div style="max-height: 160px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; background: #f8fafc;">
							<?php if (!empty($halls)): foreach ($halls as $hall): ?>
							<div class="checkbox-custom checkbox-primary mb-xs">
								<input type="checkbox" name="hall_ids[]" id="modal_hall_<?=$hall['id']?>" value="<?=$hall['id']?>" checked>
								<label for="modal_hall_<?=$hall['id']?>" style="font-size: 13px;">
									<strong><?=html_escape($hall['hall_no'])?></strong> 
									<span class="badge" style="background:#0284c7; color:#fff; font-size:10px; padding:2px 6px;"><?=translate('capacity')?>: <?=$hall['seats']?></span>
								</label>
							</div>
							<?php endforeach; else: ?>
							<p class="text-danger mb-none"><?=translate('no_exam_halls_found')?>. <a href="<?=base_url('exam/hall')?>" target="_blank"><?=translate('create_hall')?></a></p>
							<?php endif; ?>
						</div>
					</div>

					<!-- Select Classes to Include -->
					<div class="col-md-6 mb-md">
						<label class="control-label"><strong><?=translate('class')?> <span class="required">*</span></strong></label>
						<p class="text-muted" style="font-size:12px; margin-bottom:6px;"><?=translate('select_classes_to_seat_in_these_halls')?></p>
						<div style="max-height: 160px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; background: #f8fafc;">
							<?php 
								$classList = $this->db->where('branch_id', $branch_id)->get('class')->result_array();
								if (!empty($classList)): foreach ($classList as $cls):
							?>
							<div class="checkbox-custom checkbox-primary mb-xs">
								<input type="checkbox" name="class_ids[]" id="modal_cls_<?=$cls['id']?>" value="<?=$cls['id']?>" <?=(isset($class_id) && $class_id == $cls['id']) ? 'checked' : ''?>>
								<label for="modal_cls_<?=$cls['id']?>" style="font-size: 13px;">
									<?=html_escape($cls['name'])?>
								</label>
							</div>
							<?php endforeach; endif; ?>
						</div>
					</div>
				</div>

				<hr style="margin: 10px 0 15px;">

				<!-- Allocation Strategy & Anti-Cheating -->
				<div class="row">
					<div class="col-md-6 mb-sm">
						<div class="form-group">
							<label class="control-label"><strong><?=translate('allocation_strategy')?></strong></label>
							<select name="strategy" class="form-control" style="width:100%;">
								<option value="alternate_class"><?=translate('alternate_classes')?></option>
								<option value="sequential"><?=translate('sequential_roll')?></option>
								<option value="alternate_section"><?=translate('alternate_sections')?></option>
								<option value="random"><?=translate('random_shuffle')?></option>
							</select>
							<small class="text-muted"><?=translate('interleaves_different_classes_so_adjacent_seats_differ')?></small>
						</div>
					</div>

					<div class="col-md-3 mb-sm">
						<div class="form-group">
							<label class="control-label"><strong><?=translate('seats_per_bench')?></strong></label>
							<select name="seats_per_bench" class="form-control">
								<option value="1">1 <?=translate('seat')?> / <?=translate('bench')?></option>
								<option value="2" selected>2 <?=translate('seats')?> / <?=translate('bench')?> (Standard)</option>
								<option value="3">3 <?=translate('seats')?> / <?=translate('bench')?></option>
								<option value="4">4 <?=translate('seats')?> / <?=translate('bench')?></option>
							</select>
						</div>
					</div>

					<div class="col-md-3 mb-sm">
						<div class="form-group">
							<label class="control-label"><strong><?=translate('bench_prefix')?></strong></label>
							<input type="text" name="bench_prefix" class="form-control" value="B-" placeholder="B-">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 mb-sm">
						<div class="form-group">
							<label class="control-label"><strong><?=translate('starting_seat')?></strong></label>
							<input type="number" name="starting_seat" class="form-control" value="1" min="1">
						</div>
					</div>

					<div class="col-md-8 mb-sm" style="padding-top: 25px;">
						<div class="checkbox-custom checkbox-danger">
							<input type="checkbox" name="overwrite" id="overwrite_seat_plan" value="1" checked>
							<label for="overwrite_seat_plan" style="font-weight: 600; color: #dc2626;">
								<?=translate('overwrite_existing_plan_for_selected_classes_halls')?>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="background:#f8fafc;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=translate('close')?></button>
				<button type="submit" class="btn btn-primary" id="btnSubmitGenerate">
					<i class="fas fa-cog"></i> <?=translate('generate_seat_plan')?>
				</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<!-- ========================================== -->
<!-- Modal: Edit Individual Student Seat       -->
<!-- ========================================== -->
<div class="modal fade" id="editSeatModal" role="dialog" aria-labelledby="editSeatModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background:#0f172a; color:#fff;">
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" style="color:#fff; opacity:0.8;">&times;</button>
				<h4 class="modal-title" id="editSeatModalLabel">
					<i class="fas fa-edit"></i> <?=translate('edit')?> <?=translate('seat_no')?>
				</h4>
			</div>
			<?php echo form_open(base_url('seat_plan/update_seat'), array('id' => 'editSeatForm')); ?>
			<input type="hidden" name="id" id="edit_seat_id" value="">
			<div class="modal-body">
				<p><strong><?=translate('student')?>:</strong> <span id="edit_student_name" class="text-primary" style="font-weight: 700;"></span></p>
				<div class="form-group mb-sm">
					<label class="control-label"><strong><?=translate('exam_hall')?> <span class="required">*</span></strong></label>
					<select name="hall_id" id="edit_hall_id" class="form-control" required style="width:100%;">
						<?php if (!empty($halls)): foreach ($halls as $hall): ?>
							<option value="<?=$hall['id']?>"><?=html_escape($hall['hall_no'])?> (<?=$hall['seats']?> seats)</option>
						<?php endforeach; endif; ?>
					</select>
				</div>
				<div class="row">
					<div class="col-md-6 mb-sm">
						<div class="form-group">
							<label class="control-label"><strong><?=translate('seat_no')?> <span class="required">*</span></strong></label>
							<input type="text" name="seat_no" id="edit_seat_no" class="form-control" required>
						</div>
					</div>
					<div class="col-md-6 mb-sm">
						<div class="form-group">
							<label class="control-label"><strong><?=translate('bench_no')?></strong></label>
							<input type="text" name="bench_no" id="edit_bench_no" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=translate('close')?></button>
				<button type="submit" class="btn btn-primary" id="btnSubmitEditSeat">
					<i class="fas fa-save"></i> <?=translate('save')?>
				</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
function openEditSeatModal(id, hallId, seatNo, benchNo, studentName) {
	$('#edit_seat_id').val(id);
	$('#edit_hall_id').val(hallId);
	$('#edit_seat_no').val(seatNo);
	$('#edit_bench_no').val(benchNo);
	$('#edit_student_name').text(studentName);
	$('#editSeatModal').modal('show');
}

function confirmClearPlan(examId, hallId) {
	if (confirm('<?=translate('clear_plan_confirm')?>')) {
		var form = document.createElement('form');
		form.method = 'POST';
		form.action = '<?=base_url('seat_plan/clear_plan')?>';

		var inputExam = document.createElement('input');
		inputExam.type = 'hidden';
		inputExam.name = 'exam_id';
		inputExam.value = examId;
		form.appendChild(inputExam);

		if (hallId) {
			var inputHall = document.createElement('input');
			inputHall.type = 'hidden';
			inputHall.name = 'hall_id';
			inputHall.value = hallId;
			form.appendChild(inputHall);
		}

		if (typeof csrfData !== 'undefined') {
			for (var key in csrfData) {
				var inputCsrf = document.createElement('input');
				inputCsrf.type = 'hidden';
				inputCsrf.name = key;
				inputCsrf.value = csrfData[key];
				form.appendChild(inputCsrf);
			}
		}

		document.body.appendChild(form);
		form.submit();
	}
}

$(document).ready(function() {
	// AJAX generate seat plan
	$('#generateSeatPlanForm').on('submit', function(e) {
		e.preventDefault();
		var $btn = $('#btnSubmitGenerate');
		$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?=translate("processing")?>...');

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				$btn.prop('disabled', false).html('<i class="fas fa-cog"></i> <?=translate("generate_seat_plan")?>');
				if (res.status === 'success') {
					$('#generateSeatPlanModal').modal('hide');
					window.location.href = res.url;
				} else {
					var err = res.error && res.error.error ? res.error.error : '<?=translate("failed_to_generate_seat_plan")?>';
					alert(err);
				}
			},
			error: function() {
				$btn.prop('disabled', false).html('<i class="fas fa-cog"></i> <?=translate("generate_seat_plan")?>');
				alert('Server communication error.');
			}
		});
	});

	// AJAX edit seat
	$('#editSeatForm').on('submit', function(e) {
		e.preventDefault();
		var $btn = $('#btnSubmitEditSeat');
		$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?=translate("saving")?>...');

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				$btn.prop('disabled', false).html('<i class="fas fa-save"></i> <?=translate("save")?>');
				if (res.status === 'success') {
					$('#editSeatModal').modal('hide');
					window.location.reload();
				} else {
					alert(res.message || '<?=translate("failed_to_update")?>');
				}
			},
			error: function() {
				$btn.prop('disabled', false).html('<i class="fas fa-save"></i> <?=translate("save")?>');
				alert('Server error.');
			}
		});
	});
});
</script>

<div class="row hidden-print">
	<div class="col-md-12">
		<!-- Filter Panel -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-filter"></i> <?=translate('filter') . ' ' . translate('room_attendance_sheet')?>
				</h4>
			</header>
			<?php echo form_open(base_url('seat_plan/room_report'), array('method' => 'get', 'class' => 'validate')); ?>
			<div class="panel-body">
				<div class="row mb-sm">
					<div class="col-md-6 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam')?> <span class="required">*</span></label>
							<select name="exam_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
								<option value=""><?=translate('select') . ' ' . translate('exam')?></option>
								<?php if (!empty($exams)): foreach ($exams as $exam): ?>
									<option value="<?=$exam['id']?>" <?=(isset($exam_id) && $exam_id == $exam['id']) ? 'selected' : ''?>>
										<?=html_escape($exam['name'])?> <?=!empty($exam['term_name']) ? ' (' . html_escape($exam['term_name']) . ')' : ''?>
									</option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>

					<div class="col-md-6 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam_hall')?> / <?=translate('room')?> <span class="required">*</span></label>
							<select name="hall_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
								<option value=""><?=translate('select') . ' ' . translate('exam_hall')?></option>
								<?php if (!empty($halls)): foreach ($halls as $hall): ?>
									<option value="<?=$hall['id']?>" <?=(isset($hall_id) && $hall_id == $hall['id']) ? 'selected' : ''?>>
										<?=html_escape($hall['hall_no'])?> (<?=translate('capacity')?>: <?=$hall['seats']?>)
									</option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-6">
						<a href="<?=base_url('seat_plan?exam_id=' . $exam_id)?>" class="btn btn-default">
							<i class="fas fa-arrow-left"></i> <?=translate('back_to')?> <?=translate('seat_allocation')?>
						</a>
					</div>
					<div class="col-md-6 text-right">
						<button type="submit" class="btn btn-default mr-xs">
							<i class="fas fa-filter"></i> <?=translate('filter')?>
						</button>
						<?php if (!empty($report_data) && !empty($report_data['students'])): ?>
						<button type="button" class="btn btn-primary" onclick="window.print();">
							<i class="fas fa-print"></i> <?=translate('print')?> <?=translate('room_attendance_sheet')?>
						</button>
						<?php endif; ?>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<?php if (!empty($report_data) && !empty($report_data['students'])): 
	$hall = $report_data['hall'];
	$exam = $report_data['exam'];
	$students = $report_data['students'];

	$schoolName = get_global_setting('institute_name');
	if (empty($schoolName)) $schoolName = "Dream Valley School";
	$schoolAddress = get_global_setting('address');
	$schoolPhone = get_global_setting('mobileno');
	$logoUrl = base_url('uploads/app_image/logo.png');
?>
<!-- Official Printable Room Sheet -->
<div class="attendance-sheet-wrapper">
	<div class="panel">
		<div class="panel-body sheet-print-body" style="padding: 25px 30px;">
			<!-- Sheet Header -->
			<div class="sheet-header">
				<div class="sheet-logo">
					<img src="<?=$logoUrl?>" alt="logo" onerror="this.style.display='none';">
				</div>
				<div class="sheet-title-center">
					<h2 class="school-title"><?=$schoolName?></h2>
					<?php if (!empty($schoolAddress)): ?>
					<p class="school-subtitle"><?=html_escape($schoolAddress)?></p>
					<?php endif; ?>
					<div class="sheet-title-badge">
						<?=translate('room_attendance_sheet')?> & <?=translate('seat_plan')?>
					</div>
				</div>
				<div class="sheet-right-stamp-box">
					<div class="stamp-box-placeholder">
						<span><?=translate('official_seal')?></span>
					</div>
				</div>
			</div>

			<!-- Meta Info Table -->
			<div class="sheet-meta-grid">
				<table class="table table-bordered meta-table">
					<tbody>
						<tr>
							<td style="width: 15%; background:#f8fafc;"><strong><?=translate('exam')?>:</strong></td>
							<td style="width: 35%;"><?=html_escape($exam['name'])?> <?=!empty($exam['term_name']) ? ' (' . html_escape($exam['term_name']) . ')' : ''?></td>
							<td style="width: 15%; background:#f8fafc;"><strong><?=translate('exam_hall')?> / <?=translate('room')?>:</strong></td>
							<td style="width: 35%; font-weight:700; color:#007F3D; font-size:14px;"><?=html_escape($hall['hall_no'])?></td>
						</tr>
						<tr>
							<td style="background:#f8fafc;"><strong><?=translate('date')?>:</strong></td>
							<td><?=date('d-M-Y')?></td>
							<td style="background:#f8fafc;"><strong><?=translate('total_seated')?> / <?=translate('capacity')?>:</strong></td>
							<td><strong><?=count($students)?></strong> / <?=$hall['seats']?></td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Student Seating & Attendance Table -->
			<div class="table-responsive">
				<table class="table table-bordered table-condensed sheet-main-table">
					<thead>
						<tr style="background: #f1f5f9;">
							<th class="text-center" style="width: 50px;"><?=translate('seat_no')?></th>
							<th class="text-center" style="width: 65px;"><?=translate('bench_no')?></th>
							<th class="text-center" style="width: 55px;"><?=translate('roll')?></th>
							<th style="width: 90px;"><?=translate('register_no')?></th>
							<th><?=translate('student_name')?></th>
							<th style="width: 130px;"><?=translate('class')?> & <?=translate('section')?></th>
							<th class="text-center" style="width: 100px;"><?=translate('script_no')?></th>
							<th class="text-center" style="width: 80px;"><?=translate('additional_sheet_no')?></th>
							<th class="text-center" style="width: 140px;"><?=translate('student_signature')?></th>
							<th class="text-center" style="width: 70px;"><?=translate('status')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($students as $stu): 
							$fullName = trim($stu['first_name'] . ' ' . $stu['last_name']);
						?>
						<tr>
							<td class="text-center" style="font-weight: 800; font-size: 13px; color:#007F3D;">
								<?=$stu['seat_no']?>
							</td>
							<td class="text-center" style="font-weight: 600;">
								<?=$stu['bench_no'] ? html_escape($stu['bench_no']) : '-'?>
							</td>
							<td class="text-center" style="font-weight: 700;">
								<?=$stu['roll']?>
							</td>
							<td>
								<span style="font-family: monospace;"><?=$stu['register_no']?></span>
							</td>
							<td>
								<strong><?=html_escape($fullName)?></strong>
							</td>
							<td>
								<?=html_escape($stu['class_name'])?> <small class="text-muted">(<?=html_escape($stu['section_name'])?>)</small>
							</td>
							<td class="text-center blank-fill-col"></td>
							<td class="text-center blank-fill-col"></td>
							<td class="text-center sig-col"></td>
							<td class="text-center status-col">
								<span class="box-check">[ &nbsp; ]</span>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<!-- Sheet Footer: Summary & Signatures -->
			<div class="sheet-summary-footer">
				<div class="row">
					<div class="col-xs-4">
						<div class="summary-stat-box">
							<table class="table table-bordered mb-none">
								<tr>
									<td style="background:#f8fafc; width:65%;"><strong><?=translate('total_seated')?>:</strong></td>
									<td><strong><?=count($students)?></strong></td>
								</tr>
								<tr>
									<td style="background:#f8fafc;"><strong><?=translate('present')?>:</strong></td>
									<td></td>
								</tr>
								<tr>
									<td style="background:#f8fafc;"><strong><?=translate('absent')?>:</strong></td>
									<td></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-xs-8">
						<div class="signatures-row" style="display:flex; justify-content:space-around; align-items:flex-end; height:80px; margin-top:20px;">
							<div class="sig-block text-center">
								<div class="sig-line" style="border-top:1px dashed #64748b; width:160px; margin-bottom:5px;"></div>
								<span style="font-size:12px; font-weight:600;"><?=translate('invigilator_signature')?></span>
							</div>
							<div class="sig-block text-center">
								<div class="sig-line" style="border-top:1px dashed #64748b; width:160px; margin-bottom:5px;"></div>
								<span style="font-size:12px; font-weight:600;"><?=translate('room_supervisor')?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif (!empty($exam_id) && !empty($hall_id)): ?>
<div class="panel">
	<div class="panel-body text-center p-xl">
		<i class="fas fa-file-alt fa-3x text-muted mb-md" style="display:block; margin-bottom:15px; color:#cbd5e1;"></i>
		<h4><?=translate('no_seat_plan_found_for_selected_criteria')?></h4>
		<p class="text-muted"><?=translate('no_students_allocated_to_this_hall')?></p>
		<a href="<?=base_url('seat_plan?exam_id=' . $exam_id)?>" class="btn btn-primary mt-sm">
			<i class="fas fa-magic"></i> <?=translate('seat_plan_allocation')?>
		</a>
	</div>
</div>
<?php endif; ?>

<style type="text/css">
.attendance-sheet-wrapper {
	margin-top: 15px;
}
.sheet-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 2px solid #007F3D;
	padding-bottom: 15px;
	margin-bottom: 15px;
}
.sheet-logo img {
	max-height: 60px;
	max-width: 90px;
	object-fit: contain;
}
.sheet-title-center {
	text-align: center;
	flex-grow: 1;
}
.school-title {
	margin: 0;
	font-size: 20px;
	font-weight: 800;
	color: #0f172a;
	text-transform: uppercase;
}
.school-subtitle {
	margin: 2px 0 6px;
	font-size: 12px;
	color: #475569;
}
.sheet-title-badge {
	display: inline-block;
	background: #007F3D;
	color: #ffffff;
	font-size: 12px;
	font-weight: 700;
	padding: 4px 14px;
	border-radius: 4px;
	letter-spacing: 0.5px;
	text-transform: uppercase;
}
.stamp-box-placeholder {
	width: 75px;
	height: 65px;
	border: 1px dashed #cbd5e1;
	border-radius: 4px;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 10px;
	color: #94a3b8;
	text-align: center;
}
.meta-table {
	margin-bottom: 15px;
	font-size: 12px;
}
.sheet-main-table {
	font-size: 12px;
	margin-bottom: 20px;
}
.sheet-main-table th {
	text-align: center;
	font-weight: 700;
	color: #1e293b;
	vertical-align: middle;
}
.sheet-main-table td {
	vertical-align: middle;
}
.blank-fill-col {
	background: #fafafa;
}
.sig-col {
	height: 38px;
	background: #fff;
}
.box-check {
	font-family: monospace;
	font-size: 11px;
	color: #64748b;
}

/* Print Specific */
@media print {
	body {
		background: #ffffff !important;
		font-family: Arial, sans-serif !important;
		color: #000000 !important;
	}
	.header, .sidebar-left, .page-header, .panel-footer, footer, .hidden-print {
		display: none !important;
	}
	.content-body {
		margin: 0 !important;
		padding: 0 !important;
	}
	.panel {
		border: none !important;
		box-shadow: none !important;
	}
	.sheet-print-body {
		padding: 0 !important;
	}
	.sheet-header {
		border-bottom: 2px solid #000000 !important;
	}
	.sheet-title-badge {
		background: #000000 !important;
		color: #ffffff !important;
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
	}
	table.table-bordered {
		border: 1px solid #000000 !important;
	}
	table.table-bordered th, table.table-bordered td {
		border: 1px solid #000000 !important;
	}
}
</style>

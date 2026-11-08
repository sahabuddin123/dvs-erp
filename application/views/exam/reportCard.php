<?php 
$marksheet_template = $this->marksheet_template_model->getTemplate($templateID, $branchID); 
if (empty($marksheet_template)) {
	$marksheet_template = [
		'top_space' => 10,
		'right_space' => 10,
		'bottom_space' => 10,
		'left_space' => 10,
		'page_layout' => 1,
		'background' => '',
		'remark' => 1,
		'subject_position' => 0,
		'result' => 1,
		'position' => 1,
		'attendance_percentage' => 0,
		'grading_scale' => 1,
		'photo_style' => 1,
		'photo_size' => 100,
		'header_content' => '<center><h3>' . translate('report_card') . '</h3></center>',
		'footer_content' => '',
	];
}
?>
<style type="text/css">
	.mark-container {
		height: 100%;
		min-width: 1000px;
	    position: relative;
	    z-index: 2;
	    margin: 0 auto;
	    font-size: 12px;
	    padding: <?=(!empty($marksheet_template['top_space']) ? $marksheet_template['top_space'] : 10) . 'px ' . (!empty($marksheet_template['right_space']) ? $marksheet_template['right_space'] : 10) . 'px ' . (!empty($marksheet_template['bottom_space']) ? $marksheet_template['bottom_space'] : 10) . 'px ' . (!empty($marksheet_template['left_space']) ? $marksheet_template['left_space'] : 10) . 'px'?>;
	}
	table {
	    border-collapse: collapse;
	    width: 100%;
	    margin: 0 auto;
	}

	@page {
		margin: -2px;
		size: <?php echo (!empty($marksheet_template['page_layout']) && $marksheet_template['page_layout'] == 1) ? 'portrait' : 'landscape'; ?>;
	}

	@media print {
		.table-bordered > thead > tr > th,
		.table-bordered > tbody > tr > th,
		.table-bordered > tfoot > tr > th,
		.table-bordered > thead > tr > td,
		.table-bordered > tbody > tr > td,
		.table-bordered > tfoot > tr > td {
		    border-color: #000 !important;
		    background: transparent !important;
		}
	}

	.table-bordered {
	    border-color: #000 !important;
	}

	.background {
		position: absolute;
		z-index: 0;
		width: 100%;
		height: 100%;
	<?php if (empty($marksheet_template['background'])) { ?>
		background: #fff;
	<?php } else { ?>
		background-image: url("<?=base_url('uploads/marksheet/' . $marksheet_template['background'])?>") !important;
		background-repeat: no-repeat !important;
		background-size: 100% 100% !important;
	<?php } ?>
	}
</style>

<?php
$extINTL = extension_loaded('intl');
$student_array = is_array($student_array) ? $student_array : (!empty($student_array) ? [$student_array] : []);
$len = count($student_array);
$i = 1;
if (!empty($student_array)) {
	foreach ($student_array as $sc => $studentID) {
		$result = $this->exam_model->getStudentReportCard($studentID, $examID, $sessionID, $class_id, $section_id);
		$student = $result['student'] ?? [];
		if (empty($student)) {
			continue;
		}
		$getMarksList = $result['exam'] ?? [];

		$enroll_id = !empty($student['enrollID']) ? $student['enrollID'] : 0;
		$rankDetail = $this->db->where(array('exam_id' => $examID, 'enroll_id' => $enroll_id))->get('exam_rank')->row();
		$getExam = $this->db->where(array('id' => $examID))->get('exam')->row_array();
		if (empty($getExam)) {
			continue;
		}
		$schoolYear = get_type_name_by_id('schoolyear', $sessionID, 'school_year');

		$extendsData = [];
		$extendsData['print_date'] = !empty($print_date) ? $print_date : date('Y-m-d');
		$extendsData['schoolYear'] = $schoolYear;
		$extendsData['exam_name'] = $getExam['name'] ?? '';
		$extendsData['teacher_comments'] = empty($rankDetail->teacher_comments) ? '' : $rankDetail->teacher_comments;
		$extendsData['principal_comments'] = empty($rankDetail->principal_comments) ? '' : $rankDetail->principal_comments;
		$header_content = $this->marksheet_template_model->tagsReplace($student, $marksheet_template, $extendsData, 'header_content');
		$footer_content = $this->marksheet_template_model->tagsReplace($student, $marksheet_template, $extendsData, 'footer_content');

		$markDistribution = !empty($getExam['mark_distribution']) ? json_decode($getExam['mark_distribution'], true) : [];
		if (!is_array($markDistribution)) {
			$markDistribution = [];
		}
		?>
<div style="position: relative; width: 100%; height: 100%; <?php echo ($i < $len) ? 'page-break-after:always;' : 'page-break-after:avoid;'; ?>"> 
	<div class="background"></div>
	<div class="mark-container">
		<?php echo $header_content ?>
		<table class="table table-condensed table-bordered mt-lg">
			<thead>
				<tr>
					<th><?=translate('subjects')?></th>
				<?php 
				foreach ($markDistribution as $id) {
					?>
					<th><?php echo get_type_name_by_id('exam_mark_distribution', $id) ?></th>
				<?php } ?>
				<?php if ($getExam['type_id'] == 1) { ?>
					<th><?=translate('total')?></th>
				<?php } elseif($getExam['type_id'] == 2) { ?>
					<th><?=translate('grade')?></th>
					<th><?=translate('point')?></th>
<?php if (!empty($marksheet_template['remark']) && $marksheet_template['remark'] == 1) { ?>
					<th><?=translate('remark')?></th>
<?php } ?>
				<?php } elseif ($getExam['type_id'] == 3) { ?>
					<th><?=translate('total')?></th>
					<th><?=translate('grade')?></th>
					<th><?=translate('point')?></th>
<?php if (!empty($marksheet_template['remark']) && $marksheet_template['remark'] == 1) { ?>
					<th><?=translate('remark')?></th>
<?php } ?>
				<?php } ?>
<?php if (!empty($marksheet_template['subject_position']) && $marksheet_template['subject_position'] == 1) { ?>
					<th><?=translate('subject_position')?></th>
<?php } ?>

				</tr>
			</thead>
			<tbody>
			<?php
			$colspan = count($markDistribution) + 1;
			$total_grade_point = 0;
			$grand_obtain_marks = 0;
			$grand_full_marks = 0;
			$total_subjects_graded = 0;
			$result_status = 1;
			$has_any_subject = !empty($getMarksList);
			if ($has_any_subject) {
				foreach ($getMarksList as $row) {
					$total_obtain_marks = 0;
					$total_full_marks = 0;
					$fullMarkDistribution = !empty($row['mark_distribution']) ? (is_array($row['mark_distribution']) ? $row['mark_distribution'] : json_decode($row['mark_distribution'], true)) : [];
					if (!is_array($fullMarkDistribution)) {
						$fullMarkDistribution = [];
					}
					$obtainedMark = !empty($row['get_mark']) ? (is_array($row['get_mark']) ? $row['get_mark'] : json_decode($row['get_mark'], true)) : [];
					if (!is_array($obtainedMark)) {
						$obtainedMark = [];
					}
					$is_absent = (!empty($row['get_abs']) && $row['get_abs'] == 'on');
					$has_any_marks = !empty($obtainedMark) || $is_absent;
					?>
				<tr>
					<td valign="middle" width="35%"><?=$row['subject_name'] ?? ''?></td>
					<?php 
					foreach ($markDistribution as $id) {
						$val = isset($fullMarkDistribution[$id]) ? $fullMarkDistribution[$id] : (isset($fullMarkDistribution[(string)$id]) ? $fullMarkDistribution[(string)$id] : null);
						$fullMark = $val ? floatval($val['full_mark'] ?? 0) : 0;
						$passMark = $val ? floatval($val['pass_mark'] ?? 0) : 0;
						
						$has_item_mark = isset($obtainedMark[$id]) && $obtainedMark[$id] !== '';
						if (!$has_item_mark && isset($obtainedMark[(string)$id]) && $obtainedMark[(string)$id] !== '') {
							$has_item_mark = true;
							$obtained_mark = floatval($obtainedMark[(string)$id]);
						} else if ($has_item_mark) {
							$obtained_mark = floatval($obtainedMark[$id]);
						} else {
							$obtained_mark = null;
						}

						if ($obtained_mark !== null) {
							if ($obtained_mark < $passMark) {
								$result_status = 0;
							}
							$total_obtain_marks += $obtained_mark;
						}
						$total_full_marks += $fullMark;
						?>
					<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3){ ?>
						<td valign="middle">
							<?php 
								if ($is_absent) {
									echo translate('absent');
								} elseif ($obtained_mark !== null) {
									echo $obtained_mark . ($fullMark > 0 ? '/' . $fullMark : '');
								} else {
									echo ($fullMark > 0 ? '-/' . $fullMark : 'N/A');
								}
							?>
						</td>
					<?php } if ($getExam['type_id'] == 2){ ?>
						<td valign="middle">
							<?php 
								if ($is_absent) {
									echo translate('absent');
								} elseif ($obtained_mark !== null && $fullMark > 0) {
									$percentage_grade = ($obtained_mark * 100) / $fullMark;
									$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
									echo !empty($grade['name']) ? $grade['name'] : '-';
								} else {
									echo 'N/A';
								}
							?>
						</td>
					<?php } ?>
					<?php } ?>
					<?php
					$grand_obtain_marks += $total_obtain_marks;
					$grand_full_marks += $total_full_marks;
					?>
					<?php if($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
						<td valign="middle"><?php echo $has_any_marks ? ($total_obtain_marks . "/" . $total_full_marks) : ("-/" . $total_full_marks); ?></td>
					<?php } if($getExam['type_id'] == 2) { 
						if ($has_any_marks && $total_full_marks > 0) {
							$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
							$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
							$total_grade_point += (!empty($grade['grade_point']) ? $grade['grade_point'] : 0);
							$total_subjects_graded++;
							$gradeName = !empty($grade['name']) ? $grade['name'] : '-';
							$gradePoint = !empty($grade['grade_point']) ? number_format($grade['grade_point'], 2, '.', '') : '0.00';
							$gradeRemark = !empty($grade['remark']) ? $grade['remark'] : '';
						} else {
							$gradeName = 'N/A';
							$gradePoint = '-';
							$gradeRemark = '';
						}
						?>
						<td valign="middle"><?=$gradeName?></td>
						<td valign="middle"><?=$gradePoint?></td>
	<?php if (!empty($marksheet_template['remark']) && $marksheet_template['remark'] == 1) { ?>
						<td valign="middle"><?=$gradeRemark?></td>
	<?php } ?>
					<?php } if ($getExam['type_id'] == 3) {
						if ($has_any_marks && $total_full_marks > 0) {
							$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
							$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
							$total_grade_point += (!empty($grade['grade_point']) ? $grade['grade_point'] : 0);
							$total_subjects_graded++;
							$gradeName = !empty($grade['name']) ? $grade['name'] : '-';
							$gradePoint = !empty($grade['grade_point']) ? number_format($grade['grade_point'], 2, '.', '') : '0.00';
							$gradeRemark = !empty($grade['remark']) ? $grade['remark'] : '';
						} else {
							$gradeName = 'N/A';
							$gradePoint = '-';
							$gradeRemark = '';
						}
						?>
						<td valign="middle"><?=$gradeName?></td>
						<td valign="middle"><?=$gradePoint?></td>
	<?php if (!empty($marksheet_template['remark']) && $marksheet_template['remark'] == 1) { ?>
						<td valign="middle"><?=$gradeRemark?></td>
	<?php } ?>
					<?php } ?>
	<?php if (!empty($marksheet_template['subject_position']) && $marksheet_template['subject_position'] == 1) {?>
						<td valign="middle"><?php echo ($has_any_marks && !empty($student['class_id'])) ? $this->exam_progress_model->getSubjectPosition($student['class_id'], $student['section_id'] ?? 0, [$examID], $sessionID, $row['subject_id'], $total_obtain_marks) : '-'; ?></td>
	<?php } ?>
					</tr>
				<?php } 
			} else { ?>
				<tr>
					<td colspan="<?=count($markDistribution) + 5?>" class="text-center text-muted"><?=translate('no_information_available')?></td>
				</tr>
			<?php } ?>

			<?php 
			if ($getExam['type_id'] == 2) {
				$colspan_extra = 1;
			} elseif ($getExam['type_id'] == 3) {
				$colspan_extra = 2;
			} else {
				$colspan_extra = 0;
			}
			$final_colspan = count($markDistribution) + 1 + $colspan_extra;
			?>

			<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
				<tr class="text-weight-semibold">
					<td valign="top"><?=translate('grand_total')?> :</td>
					<td valign="top" colspan="<?=$final_colspan?>"><?=$grand_obtain_marks . '/' . $grand_full_marks; ?>, <?=translate('average')?> : <?php $percentage = $grand_full_marks > 0 ? (($grand_obtain_marks * 100) / $grand_full_marks) : 0; echo number_format($percentage, 2, '.', '')?>%</td>
				</tr>
			<?php if ($extINTL == true && class_exists('NumberFormatter')) { ?>
				<tr class="text-weight-semibold">
					<td valign="top"><?=translate('grand_total_in_words')?> :</td>
					<td valign="top" colspan="<?=$final_colspan?>">
						<?php
						$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						echo ucwords($f->format($grand_obtain_marks));
						?>
					</td>
				</tr>
			<?php } ?>
			<?php } if ($getExam['type_id'] == 2) { ?>
				<tr class="text-weight-semibold">
					<td valign="top">GPA :</td>
					<td valign="top" colspan="<?=$final_colspan?>"><?=number_format(($total_subjects_graded > 0 ? ($total_grade_point / $total_subjects_graded) : 0), 2, '.', '')?></td>
				</tr>
			<?php } if ($getExam['type_id'] == 3) { ?>
				<tr class="text-weight-semibold">
					<td valign="top">GPA :</td>
					<td valign="top" colspan="<?=$final_colspan?>"><?=number_format(($total_subjects_graded > 0 ? ($total_grade_point / $total_subjects_graded) : 0), 2, '.', '')?></td>
				</tr>
			<?php } if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
<?php if (!empty($marksheet_template['result']) && $marksheet_template['result'] == 1) { ?>
				<tr class="text-weight-semibold">
					<td valign="top"><?=translate('result')?> :</td>
					<td valign="top" colspan="<?=$final_colspan?>"><?=$result_status == 0 ? translate('fail') : translate('pass'); ?></td>
				</tr>
			<?php } } ?>
<?php if (!empty($marksheet_template['position']) && $marksheet_template['position'] == 1) { ?>
				<tr class="text-weight-semibold">
					<td valign="top"><?=translate('position')?> :</td>
					<td valign="top" colspan="<?=$final_colspan?>"> <?php echo (!empty($rankDetail->rank) ? $rankDetail->rank : translate("not_generated"));?></td>
				</tr>
<?php } ?>
			</tbody>
		</table>
		
		<div style="width: 100%; display: flex;">
			<div style="width: 50%; padding-right: 15px;">
<?php
if (!empty($marksheet_template['attendance_percentage']) && $marksheet_template['attendance_percentage'] == 1 && !empty($student['enrollID'])) {
					$year = explode('-', (string)$schoolYear);
					$yearVal = !empty($year[0]) ? $year[0] : date('Y');
					$getTotalWorking = $this->db->where(array('enroll_id' => $student['enrollID'], 'year(date)' => $yearVal))->get('student_attendance')->num_rows();
					$getTotalAttendance = $this->db->where(array('enroll_id' => $student['enrollID'], 'status' => 'P', 'year(date)' => $yearVal))->get('student_attendance')->num_rows();
					$attenPercentage = empty($getTotalWorking) ? '0.00' : ($getTotalAttendance * 100) / $getTotalWorking;
					?>
				<table class="table table-bordered table-condensed">
					<tbody>
						<tr>
							<th colspan="2" class="text-center"><?=translate('attendance')?></th>
						</tr>
						<tr>
							<th style="width: 65%;"><?=translate('no_of_working_days')?></th>
							<td><?=$getTotalWorking?></td>
						</tr>
						<tr>
							<th style="width: 65%;"><?=translate('no_of_days_attended')?></th>
							<td><?=$getTotalAttendance?></td>
						</tr>
						<tr>
							<th style="width: 65%;"><?=translate('attendance_percentage')?></th>
							<td><?=number_format($attenPercentage, 2, '.', '') ?>%</td>
						</tr>
					</tbody>
				</table>
<?php } ?>
			</div>
	<?php
	if (!empty($marksheet_template['grading_scale']) && $marksheet_template['grading_scale'] == 1) {
		if ($getExam['type_id'] != 1) {
			?>
			<div style="width: 50%; padding-left: 15px;">
				<table class="table table-condensed table-bordered">
					<tbody>
						<tr>
							<th colspan="3" class="text-center"><?=translate('grading_scale')?></th>
						</tr>
						<tr>
							<th><?=translate('grade')?></th>
							<th><?=translate('min_percentage')?></th>
							<th><?=translate('max_percentage')?></th>
						</tr>
					<?php 
					$grade = $this->db->where('branch_id', $getExam['branch_id'])->get('grade')->result_array();
					foreach ($grade as $key => $row) {
					?>
						<tr>
							<td style="width: 30%;"><?=$row['name']?></td>
							<td style="width: 30%;"><?=$row['lower_mark']?>%</td>
							<td style="width: 30%;"><?=$row['upper_mark']?>%</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
	<?php } } ?>
		</div>
<?php echo $footer_content; ?>
	</div>
</div>
<?php $i++; } } ?>

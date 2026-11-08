<div class="row hidden-print">
	<div class="col-md-12">
		<!-- Filter Panel -->
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-filter"></i> <?=translate('filter') . ' ' . translate('print_seat_cards')?>
				</h4>
			</header>
			<?php echo form_open(base_url('seat_plan/print_card'), array('method' => 'get', 'class' => 'validate')); ?>
			<div class="panel-body">
				<div class="row mb-sm">
					<div class="col-md-3 mb-sm">
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

					<div class="col-md-3 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam_hall')?></label>
							<select name="hall_id" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('all_select')?></option>
								<?php if (!empty($halls)): foreach ($halls as $hall): ?>
									<option value="<?=$hall['id']?>" <?=(isset($hall_id) && $hall_id == $hall['id']) ? 'selected' : ''?>>
										<?=html_escape($hall['hall_no'])?>
									</option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>

					<div class="col-md-3 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id', $class_id), "class='form-control' id='filter_class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%'");
							?>
						</div>
					</div>

					<div class="col-md-3 mb-sm">
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
					<div class="col-md-6">
						<a href="<?=base_url('seat_plan?exam_id=' . $exam_id)?>" class="btn btn-default">
							<i class="fas fa-arrow-left"></i> <?=translate('back_to')?> <?=translate('seat_allocation')?>
						</a>
					</div>
					<div class="col-md-6 text-right">
						<button type="submit" class="btn btn-default mr-xs">
							<i class="fas fa-filter"></i> <?=translate('filter')?>
						</button>
						<?php if (!empty($cards)): ?>
						<button type="button" class="btn btn-primary" onclick="window.print();">
							<i class="fas fa-print"></i> <?=translate('print_seat_cards')?> (<?=count($cards)?>)
						</button>
						<?php endif; ?>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<?php if (!empty($cards)): ?>
<!-- Printable Cards Grid -->
<div class="seat-cards-container">
	<div class="row seat-cards-row">
		<?php 
			$schoolName = get_global_setting('institute_name');
			if (empty($schoolName)) $schoolName = "Dream Valley School";
			$logoUrl = base_url('uploads/app_image/logo.png');

			foreach ($cards as $index => $stu): 
				$photoUrl = !empty($stu['photo']) && file_exists('uploads/images/student/' . $stu['photo']) 
					? base_url('uploads/images/student/' . $stu['photo']) 
					: base_url('uploads/app_image/default.jpg');
				$fullName = trim($stu['first_name'] . ' ' . $stu['last_name']);
		?>
		<div class="col-md-6 col-sm-6 col-xs-12 seat-card-col">
			<div class="seat-card-box">
				<!-- Header -->
				<div class="card-header-bar">
					<div class="card-logo-wrap">
						<img src="<?=$logoUrl?>" alt="logo" class="card-school-logo" onerror="this.style.display='none';">
					</div>
					<div class="card-school-info">
						<h5 class="card-school-name"><?=$schoolName?></h5>
						<span class="card-badge-title"><?=translate('seat_card')?></span>
					</div>
				</div>

				<div class="card-exam-banner">
					<strong><?=html_escape($stu['exam_name'])?></strong>
				</div>

				<!-- Student Info -->
				<div class="card-student-body">
					<div class="card-photo-wrap">
						<img src="<?=$photoUrl?>" alt="photo" class="card-student-photo">
					</div>
					<div class="card-details-wrap">
						<div class="card-detail-line">
							<span class="line-label"><?=translate('name')?>:</span>
							<strong class="line-val student-name-text"><?=html_escape($fullName)?></strong>
						</div>
						<div class="card-detail-line">
							<span class="line-label"><?=translate('roll')?>:</span>
							<strong class="line-val roll-text"><?=$stu['roll']?></strong>
							<span class="line-sep">|</span>
							<span class="line-label"><?=translate('register_no')?>:</span>
							<span class="line-val reg-text"><?=$stu['register_no']?></span>
						</div>
						<div class="card-detail-line">
							<span class="line-label"><?=translate('class')?>:</span>
							<strong class="line-val"><?=html_escape($stu['class_name'])?></strong>
							<span class="line-sep">|</span>
							<span class="line-label"><?=translate('section')?>:</span>
							<strong class="line-val"><?=html_escape($stu['section_name'])?></strong>
						</div>
					</div>
				</div>

				<!-- Location & Seat Badges -->
				<div class="card-seat-footer">
					<div class="seat-pill hall-pill">
						<small><?=translate('hall_no')?></small>
						<strong><?=html_escape($stu['hall_no'])?></strong>
					</div>
					<div class="seat-pill bench-pill">
						<small><?=translate('bench_no')?></small>
						<strong><?=$stu['bench_no'] ? html_escape($stu['bench_no']) : '-'?></strong>
					</div>
					<div class="seat-pill seat-number-pill">
						<small><?=translate('seat_no')?></small>
						<strong class="highlight-seat"><?=$stu['seat_no']?></strong>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php elseif (!empty($exam_id)): ?>
<div class="panel">
	<div class="panel-body text-center p-xl">
		<i class="fas fa-id-card fa-3x text-muted mb-md" style="display:block; margin-bottom:15px; color:#cbd5e1;"></i>
		<h4><?=translate('no_seat_plan_found_for_selected_criteria')?></h4>
		<p class="text-muted"><?=translate('please_generate_seat_plan_first')?></p>
		<a href="<?=base_url('seat_plan?exam_id=' . $exam_id)?>" class="btn btn-primary mt-sm">
			<i class="fas fa-magic"></i> <?=translate('seat_plan_allocation')?>
		</a>
	</div>
</div>
<?php endif; ?>

<style type="text/css">
/* Seat Card Styles */
.seat-cards-container {
	margin-top: 15px;
}
.seat-card-col {
	margin-bottom: 20px;
	page-break-inside: avoid;
}
.seat-card-box {
	background: #ffffff;
	border: 2px dashed #94a3b8;
	border-radius: 8px;
	padding: 12px 14px;
	box-shadow: 0 1px 3px rgba(0,0,0,0.05);
	position: relative;
	transition: all 0.2s ease;
}
.seat-card-box:hover {
	border-color: #007F3D;
	box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.card-header-bar {
	display: flex;
	align-items: center;
	border-bottom: 2px solid #007F3D;
	padding-bottom: 6px;
	margin-bottom: 6px;
	gap: 10px;
}
.card-logo-wrap {
	flex-shrink: 0;
}
.card-school-logo {
	max-height: 38px;
	max-width: 50px;
	object-fit: contain;
}
.card-school-info {
	flex-grow: 1;
	display: flex;
	justify-content: space-between;
	align-items: center;
}
.card-school-name {
	margin: 0;
	font-size: 14px;
	font-weight: 700;
	color: #0f172a;
	text-transform: uppercase;
}
.card-badge-title {
	background: #007F3D;
	color: #ffffff;
	font-size: 10px;
	font-weight: 700;
	padding: 2px 8px;
	border-radius: 4px;
	letter-spacing: 0.5px;
	text-transform: uppercase;
}
.card-exam-banner {
	background: #f1f5f9;
	text-align: center;
	padding: 3px 6px;
	font-size: 11px;
	font-weight: 600;
	color: #334155;
	border-radius: 4px;
	margin-bottom: 8px;
}
.card-student-body {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 10px;
}
.card-photo-wrap {
	flex-shrink: 0;
}
.card-student-photo {
	width: 54px;
	height: 54px;
	border-radius: 6px;
	border: 1px solid #cbd5e1;
	object-fit: cover;
}
.card-details-wrap {
	flex-grow: 1;
	font-size: 12px;
	line-height: 1.45;
}
.card-detail-line {
	margin-bottom: 2px;
}
.line-label {
	color: #64748b;
	font-size: 11px;
}
.line-val {
	color: #0f172a;
}
.student-name-text {
	font-size: 13px;
	color: #0f172a;
}
.roll-text {
	color: #007F3D;
	font-size: 13px;
}
.reg-text {
	font-family: monospace;
	font-weight: 600;
	color: #0284c7;
}
.line-sep {
	margin: 0 4px;
	color: #cbd5e1;
}
.card-seat-footer {
	display: flex;
	justify-content: space-between;
	gap: 6px;
	border-top: 1px solid #e2e8f0;
	padding-top: 8px;
}
.seat-pill {
	flex: 1;
	text-align: center;
	background: #f8fafc;
	border: 1px solid #e2e8f0;
	border-radius: 6px;
	padding: 3px 4px;
}
.seat-pill small {
	display: block;
	font-size: 9px;
	color: #64748b;
	text-transform: uppercase;
	font-weight: 600;
}
.seat-pill strong {
	font-size: 13px;
	color: #0f172a;
}
.seat-number-pill {
	background: #007F3D !important;
	border-color: #007F3D !important;
}
.seat-number-pill small {
	color: #d1fae5 !important;
}
.seat-number-pill strong.highlight-seat {
	color: #ffffff !important;
	font-size: 15px;
	font-weight: 800;
}

/* Print Styles */
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
	.seat-cards-container {
		width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}
	.seat-card-col {
		width: 48% !important;
		float: left !important;
		margin: 1% !important;
		page-break-inside: avoid !important;
	}
	.seat-card-box {
		border: 1.5px dashed #000000 !important;
		box-shadow: none !important;
	}
	.card-badge-title, .seat-number-pill {
		background: #000000 !important;
		color: #ffffff !important;
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
	}
}
</style>

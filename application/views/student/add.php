<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
<?php if (is_superadmin_loggedin() ): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><?=translate('select_ground')?></h4>
	</header>
	<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
	<div class="panel-body">
		<div class="row mb-sm">
			<div class="col-md-offset-3 col-md-6">
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
						data-plugin-selectTwo data-width='100%'");
					?>
				</div>
			</div>
		</div>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-md-offset-10 col-md-2">
				<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
			</div>
		</div>
	</footer>
	<?php echo form_close();?>
</section>
<?php 
endif;
	if (!empty($branch_id)): 
		// Fetch Custom Fields for Student
		$all_custom_fields = getCustomFields('student', $branch_id);
		$cf_by_id = array();
		foreach ($all_custom_fields as $cf) {
			$cf_by_id[$cf['id']] = $cf;
		}
		
		// Helper to match custom field safely by ID or Bangla keywords
		if (!function_exists('dvs_find_cf')) {
			function dvs_find_cf($cfs, $preferred_id, $keywords = array()) {
				if (isset($cfs[$preferred_id])) {
					return $cfs[$preferred_id];
				}
				foreach ($cfs as $f) {
					$match = true;
					foreach ($keywords as $kw) {
						if (mb_stripos($f['field_label'], $kw) === false) {
							$match = false;
							break;
						}
					}
					if ($match) {
						return $f;
					}
				}
				return null;
			}
		}

		$cf_student_name_en = dvs_find_cf($cf_by_id, 1, array('পূর্ণ নাম'));
		$cf_father_name_en  = dvs_find_cf($cf_by_id, 2, array('পিতার', 'ইংরেজি'));
		$cf_mother_name_en  = dvs_find_cf($cf_by_id, 3, array('মাতার', 'ইংরেজি'));
		$cf_birth_reg_no    = dvs_find_cf($cf_by_id, 4, array('জন্ম নিবন্ধন'));
		$cf_issue_date      = dvs_find_cf($cf_by_id, 5, array('ইস্যু'));
		$cf_stipend         = dvs_find_cf($cf_by_id, 11, array('উপবৃত্তি'));
		$cf_student_age     = dvs_find_cf($cf_by_id, 12, array('বয়স'));

		$placed_cf_ids = array();
		if ($cf_student_name_en) $placed_cf_ids[] = $cf_student_name_en['id'];
		if ($cf_father_name_en)  $placed_cf_ids[] = $cf_father_name_en['id'];
		if ($cf_mother_name_en)  $placed_cf_ids[] = $cf_mother_name_en['id'];
		if ($cf_birth_reg_no)    $placed_cf_ids[] = $cf_birth_reg_no['id'];
		if ($cf_issue_date)      $placed_cf_ids[] = $cf_issue_date['id'];
		if ($cf_stipend)         $placed_cf_ids[] = $cf_stipend['id'];
		if ($cf_student_age)     $placed_cf_ids[] = $cf_student_age['id'];

 		if (is_superadmin_loggedin()) { ?>
<div class="row appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
<?php } else { ?>
<div class="row">
<?php } ?>	
	<div class="col-md-12">
		<!-- Custom Wizard Styles -->
		<style>
			.dvs-wizard-wrapper {
				background: #fff;
				border-radius: 12px;
				box-shadow: 0 4px 20px rgba(0,0,0,0.06);
				margin-bottom: 25px;
				overflow: hidden;
			}
			.dvs-wizard-header {
				background: linear-gradient(135deg, #007F3D 0%, #005f2e 100%);
				color: #fff;
				padding: 20px 25px 15px;
			}
			.dvs-wizard-header .title-area h3 {
				margin: 0 0 5px;
				font-size: 22px;
				font-weight: 700;
				color: #fff;
			}
			.dvs-wizard-header .title-area p {
				margin: 0;
				color: rgba(255,255,255,0.85);
				font-size: 13px;
			}
			.dvs-step-navigator {
				padding: 20px 20px 10px;
				background: #f8fafc;
				border-bottom: 1px solid #e2e8f0;
			}
			.dvs-step-list {
				display: flex;
				justify-content: space-between;
				list-style: none;
				padding: 0;
				margin: 0 0 15px;
				position: relative;
			}
			.dvs-step-item {
				flex: 1;
				text-align: center;
				position: relative;
				cursor: pointer;
				padding: 0 8px;
			}
			.dvs-step-item:not(:last-child):after {
				content: '';
				position: absolute;
				top: 22px;
				left: 50%;
				width: 100%;
				height: 3px;
				background: #e2e8f0;
				z-index: 1;
				transition: background 0.3s ease;
			}
			.dvs-step-item.completed:not(:last-child):after {
				background: #007F3D;
			}
			.dvs-step-badge {
				width: 44px;
				height: 44px;
				line-height: 40px;
				border-radius: 50%;
				background: #fff;
				border: 3px solid #cbd5e1;
				color: #64748b;
				display: inline-block;
				font-size: 16px;
				font-weight: 700;
				position: relative;
				z-index: 2;
				transition: all 0.3s ease;
				box-shadow: 0 2px 6px rgba(0,0,0,0.06);
			}
			.dvs-step-item.active .dvs-step-badge {
				background: #007F3D;
				border-color: #007F3D;
				color: #fff;
				box-shadow: 0 0 0 4px rgba(0, 127, 61, 0.2);
				transform: scale(1.08);
			}
			.dvs-step-item.completed .dvs-step-badge {
				background: #10b981;
				border-color: #10b981;
				color: #fff;
			}
			.dvs-step-label {
				margin-top: 8px;
				display: block;
			}
			.dvs-step-label .step-main {
				font-weight: 700;
				color: #475569;
				font-size: 13.5px;
				display: block;
			}
			.dvs-step-item.active .dvs-step-label .step-main {
				color: #007F3D;
			}
			.dvs-step-label .step-sub {
				color: #94a3b8;
				font-size: 11px;
				display: block;
			}
			.dvs-progress-track {
				height: 6px;
				background: #e2e8f0;
				border-radius: 10px;
				overflow: hidden;
				margin-bottom: 5px;
			}
			.dvs-progress-fill {
				height: 100%;
				background: linear-gradient(90deg, #007F3D 0%, #10b981 100%);
				width: 25%;
				transition: width 0.4s ease;
			}
			.wizard-step-pane {
				padding: 25px 25px 15px;
			}
			.dvs-step-header {
				border-bottom: 2px solid #f1f5f9;
				padding-bottom: 12px;
				margin-bottom: 22px;
			}
			.dvs-step-header h4 {
				margin: 0;
				font-size: 17px;
				font-weight: 700;
				color: #1e293b;
			}
			.dvs-step-header h4 i {
				color: #007F3D;
				margin-right: 8px;
			}
			.dvs-step-header p {
				margin: 4px 0 0;
				color: #64748b;
				font-size: 12.5px;
			}
			.dvs-nav-buttons {
				background: #f8fafc;
				padding: 16px 25px;
				border-top: 1px solid #e2e8f0;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.btn-dvs-prev {
				border-radius: 6px;
				padding: 9px 20px;
				font-weight: 600;
				color: #475569;
				background: #fff;
				border: 1px solid #cbd5e1;
				transition: all 0.2s;
			}
			.btn-dvs-prev:hover {
				background: #f1f5f9;
				color: #1e293b;
			}
			.btn-dvs-next {
				border-radius: 6px;
				padding: 9px 24px;
				font-weight: 600;
				color: #fff;
				background: #007F3D;
				border: none;
				box-shadow: 0 3px 8px rgba(0, 127, 61, 0.3);
				transition: all 0.2s;
			}
			.btn-dvs-next:hover {
				background: #005f2e;
				color: #fff;
				box-shadow: 0 4px 12px rgba(0, 127, 61, 0.4);
			}
			.btn-dvs-submit {
				border-radius: 6px;
				padding: 10px 28px;
				font-weight: 700;
				font-size: 15px;
				color: #fff;
				background: linear-gradient(135deg, #007F3D 0%, #059669 100%);
				border: none;
				box-shadow: 0 4px 14px rgba(0, 127, 61, 0.4);
				transition: all 0.2s;
			}
			.btn-dvs-submit:hover {
				background: linear-gradient(135deg, #005f2e 0%, #047857 100%);
				box-shadow: 0 6px 18px rgba(0, 127, 61, 0.5);
			}
			.dvs-card-highlight {
				background: #f0fdf4;
				border: 1px solid #bbf7d0;
				border-radius: 8px;
				padding: 15px 20px;
				margin-bottom: 20px;
			}
			.dvs-card-highlight h5 {
				margin: 0 0 5px;
				color: #166534;
				font-weight: 700;
				font-size: 14px;
			}
			.dvs-card-highlight p {
				margin: 0;
				color: #15803d;
				font-size: 12.5px;
			}
			.dvs-summary-box {
				background: #f8fafc;
				border: 1px solid #e2e8f0;
				border-radius: 8px;
				padding: 18px;
				margin-bottom: 20px;
			}
			.dvs-summary-title {
				font-size: 14px;
				font-weight: 700;
				color: #0f172a;
				border-bottom: 2px solid #e2e8f0;
				padding-bottom: 8px;
				margin-bottom: 12px;
			}
			.dvs-summary-title i {
				color: #007F3D;
				margin-right: 6px;
			}
			.dvs-summary-table {
				width: 100%;
				font-size: 13px;
			}
			.dvs-summary-table td {
				padding: 5px 8px;
			}
			.dvs-summary-table td.label-col {
				color: #64748b;
				font-weight: 600;
				width: 38%;
			}
			.dvs-summary-table td.val-col {
				color: #1e293b;
				font-weight: 600;
			}
			.field-invalid-highlight {
				border-color: #ef4444 !important;
				background-color: #fef2f2 !important;
				box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
			}
			@media (max-width: 768px) {
				.dvs-step-label { display: none; }
				.dvs-step-item:not(:last-child):after { top: 18px; }
				.dvs-step-badge { width: 36px; height: 36px; line-height: 32px; font-size: 13px; }
				.dvs-nav-buttons { flex-direction: column-reverse; gap: 10px; }
				.dvs-nav-buttons button { width: 100%; text-align: center; }
			}
		</style>

		<section class="panel dvs-wizard-wrapper">
			<?php echo form_open_multipart('student/save', array('class' => 'frm-submit-data', 'id' => 'studentAdmissionForm')); ?>
			
			<!-- Wizard Header -->
			<div class="dvs-wizard-header">
				<div class="row align-items-center">
					<div class="col-sm-8 title-area">
						<h3><i class="fas fa-user-graduate"></i> <?=translate('student_admission')?> (শিক্ষার্থী নতুন ভর্তি ফরম)</h3>
						<p>অনুগ্রহ করে নিচের ৪টি ধাপে শিক্ষার্থীর যাবতীয় সঠিক তথ্য পূরণ করে ভর্তি নিশ্চিত করুন।</p>
					</div>
					<div class="col-sm-4 text-right hidden-xs">
						<span class="badge" style="background: rgba(255,255,255,0.25); font-size: 13px; padding: 7px 14px; font-weight: 600;">
							<i class="fas fa-shield-alt"></i> ড্রিম ভ্যালী স্কুল ইআরপি
						</span>
					</div>
				</div>
			</div>

			<!-- Wizard Step Navigator Tabs -->
			<div class="dvs-step-navigator">
				<ul class="dvs-step-list" id="dvsStepList">
					<li class="dvs-step-item active" data-step="1">
						<div class="dvs-step-badge"><i class="fas fa-school"></i></div>
						<div class="dvs-step-label">
							<span class="step-main">ধাপ ১: একাডেমিক তথ্য</span>
							<span class="step-sub">Academic Information</span>
						</div>
					</li>
					<li class="dvs-step-item" data-step="2">
						<div class="dvs-step-badge"><i class="fas fa-user-graduate"></i></div>
						<div class="dvs-step-label">
							<span class="step-main">ধাপ ২: শিক্ষার্থীর তথ্য</span>
							<span class="step-sub">Student Details & Names</span>
						</div>
					</li>
					<li class="dvs-step-item" data-step="3">
						<div class="dvs-step-badge"><i class="fas fa-user-friends"></i></div>
						<div class="dvs-step-label">
							<span class="step-main">ধাপ ৩: অভিভাবকের তথ্য</span>
							<span class="step-sub">Parents & Guardian</span>
						</div>
					</li>
					<li class="dvs-step-item" data-step="4">
						<div class="dvs-step-badge"><i class="fas fa-check-circle"></i></div>
						<div class="dvs-step-label">
							<span class="step-main">ধাপ ৪: সুবিধা ও সাবমিট</span>
							<span class="step-sub">Review & Submission</span>
						</div>
					</li>
				</ul>
				<div class="dvs-progress-track">
					<div class="dvs-progress-fill" id="dvsProgressFill" style="width: 25%;"></div>
				</div>
			</div>

			<div class="panel-body" style="padding: 0;">

				<!-- ========================================== -->
				<!-- STEP 1: ACADEMIC DETAILS                   -->
				<!-- ========================================== -->
				<div class="wizard-step-pane" id="wizard-step-1" style="display: block;">
					<div class="dvs-step-header">
						<h4><i class="fas fa-graduation-cap"></i> ধাপ ১: একাডেমিক ও ভর্তি বিবরণ (Academic & Admission Details)</h4>
						<p>শিক্ষাবর্ষ, ভর্তি তারিখ, রোল এবং শ্রেণির তথ্য সঠিকভাবে নির্বাচন করুন।</p>
					</div>

					<?php
					$academic_year = get_session_id(); 
					$roll = $this->student_fields_model->getStatus('roll', $branch_id);
					$admission_date = $this->student_fields_model->getStatus('admission_date', $branch_id);
					?>
					
					<div class="row">
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('academic_year')?> (শিক্ষাবর্ষ) <span class="required">*</span></label>
								<?php
									$arrayYear = array("" => translate('select'));
									$years = $this->db->get('schoolyear')->result();
									foreach ($years as $year){
										$arrayYear[$year->id] = $year->school_year;
									}
									echo form_dropdown("year_id", $arrayYear, set_value('year_id', $academic_year), "class='form-control' id='academic_year_id'
									data-plugin-selectTwo data-width='100%' required");
								?>
								<span class="error"></span>
							</div>
						</div>
						
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('register_no')?> (ভর্তি / রেজিস্টার নং) <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-id-badge"></i></span>
									<input type="text" class="form-control" name="register_no" id="register_no" value="<?=set_value('register_no', $register_id)?>" required />
								</div>
								<span class="error"></span>
							</div>
						</div>
						
						<?php if ($roll['status']) { ?>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('roll')?> (রোল নং) <?php echo $roll['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-sort-numeric-down"></i></span>
									<input type="text" class="form-control" name="roll" id="roll" value="<?=set_value('roll')?>" <?php echo $roll['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($admission_date['status']) { ?>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('admission_date')?> (ভর্তির তারিখ) <?php echo $admission_date['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
									<input type="text" class="form-control" name="admission_date" id="admission_date" value="<?=set_value('admission_date', date('Y-m-d'))?>" data-plugin-datepicker
									data-plugin-options='{ "todayHighlight" : true }' <?php echo $admission_date['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<?php
					$category = $this->student_fields_model->getStatus('category', $branch_id);
					?>
					<div class="row mb-md">
						<?php if (is_superadmin_loggedin()): ?>
							<input type="hidden" name="branch_id" id="hidden_branch_id" value="<?php echo $branch_id ?>">
						<?php endif; ?>
						
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('class')?> (শ্রেণি) <span class="required">*</span></label>
								<?php
									$arrayClass = $this->app_lib->getClass($branch_id);
									echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
									data-plugin-selectTwo data-width='100%' required");
								?>
								<span class="error"></span>
							</div>
						</div>

						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('section')?> (শাখা) <span class="required">*</span></label>
								<?php
									$arraySection = $this->app_lib->getSections(set_value('class_id'), false);
									echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' 
									data-plugin-selectTwo data-width='100%' required");
								?>
								<span class="error"></span>
							</div>
						</div>

						<?php if ($category['status']) { ?>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('category')?> (শিক্ষার্থী ক্যাটাগরি) <?php echo $category['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<?php
									$arrayCategory = $this->app_lib->getStudentCategory($branch_id);
									echo form_dropdown("category_id", $arrayCategory, set_value('category_id'), "class='form-control'
									data-plugin-selectTwo data-width='100%' id='category_id' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Step 1 Nav Buttons -->
					<div class="dvs-nav-buttons">
						<div></div>
						<div>
							<button type="button" class="btn btn-dvs-next" onclick="dvsNextStep(1)">
								পরবর্তী ধাপ: শিক্ষার্থীর তথ্য (Next) <i class="fas fa-arrow-right"></i>
							</button>
						</div>
					</div>
				</div>

				<!-- ========================================== -->
				<!-- STEP 2: STUDENT DETAILS                    -->
				<!-- ========================================== -->
				<div class="wizard-step-pane" id="wizard-step-2" style="display: none;">
					<div class="dvs-step-header">
						<h4><i class="fas fa-user-edit"></i> ধাপ ২: শিক্ষার্থীর ব্যক্তিগত তথ্য ও পরিচিতি (Student Details & Identity)</h4>
						<p>নামের প্রথম ও শেষ অংশ বাংলায় লিখুন এবং পাশাপাশি ইংরেজিতে পূর্ণ নাম লিখুন।</p>
					</div>

					<div class="dvs-card-highlight">
						<h5><i class="fas fa-info-circle"></i> নাম লেখার নির্দেশনা:</h5>
						<p>প্রথম দুটি বক্সে শিক্ষার্থীর নাম <strong>বাংলায় (প্রথম অংশ ও শেষ অংশ)</strong> লিখুন এবং পাশের বক্সে <strong>ইংরেজিতে ক্যাপিটাল লেটারে পূর্ণ নাম (Full Name in English)</strong> লিখুন।</p>
					</div>

					<?php
					$last_name = $this->student_fields_model->getStatus('last_name', $branch_id);
					$gender = $this->student_fields_model->getStatus('gender', $branch_id);
					$blood_group = $this->student_fields_model->getStatus('blood_group', $branch_id);
					$birthday = $this->student_fields_model->getStatus('birthday', $branch_id);
					?>

					<!-- Row 1: Student Names (Bangla First Name, Bangla Last Name, English Full Name side-by-side) -->
					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">First Name (বাংলায়) / নাম (প্রথম অংশ) <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-user"></i></span>
									<input type="text" class="form-control" name="first_name" id="first_name" value="<?=set_value('first_name')?>" placeholder="যেমন: মুহাম্মদ / মোহাম্মদ" required />
								</div>
								<span class="error"></span>
							</div>
						</div>

						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Last Name (বাংলায়) / নাম (শেষ অংশ) <?php echo $last_name['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-user"></i></span>
									<input type="text" class="form-control" name="last_name" id="last_name" value="<?=set_value('last_name')?>" placeholder="যেমন: হাসান / আলি" <?php echo $last_name['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>

						<!-- Custom Field 1: Full Name in English -->
						<?php 
						if ($cf_student_name_en) {
							echo render_single_custom_field($cf_student_name_en, 'student', false, 4, 'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে) / Full Name', 'e.g. MD. HASAN ALI', 'fas fa-font');
						} else { ?>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে) <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-font"></i></span>
									<input type="text" class="form-control" name="custom_fields[student][1]" placeholder="e.g. MD. HASAN ALI" required />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Row 2: Gender, Blood Group, Birthday (Age auto-calculated in backend) -->
					<div class="row">
						<?php if ($gender['status']) { ?>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('gender')?> (লিঙ্গ) <?php echo $gender['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<?php
									$arrayGender = array(
										'male' => translate('male') . ' (ছাত্র)',
										'female' => translate('female') . ' (ছাত্রী)'
									);
									echo form_dropdown("gender", $arrayGender, set_value('gender'), "class='form-control' id='gender' data-plugin-selectTwo
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($blood_group['status']) { ?>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('blood_group')?> (রক্তের গ্রুপ) <?php echo $blood_group['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<?php
									$bloodArray = $this->app_lib->getBloodgroup();
									echo form_dropdown("blood_group", $bloodArray, set_value("blood_group"), "class='form-control populate' data-plugin-selectTwo 
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($birthday['status']) { ?>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('birthday')?> (জন্ম তারিখ) <?php echo $birthday['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
									<input type="text" autocomplete="off" class="form-control" name="birthday" id="birthday" value="<?=set_value('birthday')?>" data-plugin-datepicker
									data-plugin-options='{ "startView": 2 }' <?php echo $birthday['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Row 3: Birth Registration Info & Stipend -->
					<div class="row">
						<!-- Custom Field 4: Birth Registration No -->
						<?php 
						if ($cf_birth_reg_no) {
							echo render_single_custom_field($cf_birth_reg_no, 'student', false, 4, 'জন্ম নিবন্ধন নং (Birth Reg No)', '১৭ ডিজিটের জন্ম নিবন্ধন নম্বর', 'fas fa-certificate');
						}
						?>

						<!-- Custom Field 5: Issue Date -->
						<?php 
						if ($cf_issue_date) {
							echo render_single_custom_field($cf_issue_date, 'student', false, 4, 'নিবন্ধন ইস্যুর তারিখ (Issue Date)', 'YYYY-MM-DD', 'far fa-calendar-alt');
						}
						?>

						<!-- Custom Field 11: Stipend -->
						<?php 
						if ($cf_stipend) {
							echo render_single_custom_field($cf_stipend, 'student', false, 4, 'উপবৃত্তি প্রাপ্ত কিনা? (Stipend Eligible?)', null, 'fas fa-hand-holding-usd');
						}
						?>
					</div>

					<!-- Row 4: Religion, Mother Tongue, Mobile, Email -->
					<?php 
					$mother_tongue = $this->student_fields_model->getStatus('mother_tongue', $branch_id);
					$religion = $this->student_fields_model->getStatus('religion', $branch_id);
					$student_mobile_no = $this->student_fields_model->getStatus('student_mobile_no', $branch_id);
					$student_email = $this->student_fields_model->getStatus('student_email', $branch_id);
					?>
					<div class="row">
						<?php if ($religion['status']) { ?>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('religion')?> (ধর্ম) <?php echo $religion['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-pray"></i></span>
									<input type="text" class="form-control" name="religion" value="<?=set_value('religion', 'ইসলাম')?>" placeholder="যেমন: ইসলাম / হিন্দু" <?php echo $religion['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($mother_tongue['status']) { ?>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('mother_tongue')?> (মাতৃভাষা) <?php echo $mother_tongue['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-language"></i></span>
									<input type="text" class="form-control" name="mother_tongue" value="<?=set_value('mother_tongue', 'বাংলা')?>" <?php echo $mother_tongue['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($student_mobile_no['status']) { ?>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('mobile_no')?> (শিক্ষার্থীর মোবাইল নং) <?php echo $student_mobile_no['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-phone-alt"></i></span>
									<input type="text" class="form-control" name="mobileno" id="mobileno" value="<?=set_value('mobileno')?>" placeholder="017xxxxxxxx" <?php echo $student_mobile_no['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($student_email['status']) { ?>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('email')?> (ইমেইল) <?php echo $student_email['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-envelope"></i></span>
									<input type="email" class="form-control" name="email" id="email" value="<?=set_value('email')?>" placeholder="student@example.com" <?php echo $student_email['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Row 5: Addresses -->
					<?php 
					$present_address = $this->student_fields_model->getStatus('present_address', $branch_id);
					$permanent_address = $this->student_fields_model->getStatus('permanent_address', $branch_id);
					?>
					<div class="row">
						<?php if ($present_address['status']) { ?>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('present_address')?> (বর্তমান ঠিকানা) <?php echo $present_address['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<textarea name="current_address" rows="2" class="form-control" placeholder="গ্রাম/মহল্লা, ডাকঘর, উপজেলা, জেলা" <?php echo $present_address['required'] == 1 ? 'required' : ''; ?>><?=set_value('current_address')?></textarea>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($permanent_address['status']) { ?>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('permanent_address')?> (স্থায়ী ঠিকানা) <?php echo $permanent_address['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<textarea name="permanent_address" rows="2" class="form-control" placeholder="গ্রাম/মহল্লা, ডাকঘর, উপজেলা, জেলা" <?php echo $permanent_address['required'] == 1 ? 'required' : ''; ?>><?=set_value('permanent_address')?></textarea>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Row 6: City & State -->
					<?php 
					$city = $this->student_fields_model->getStatus('city', $branch_id);
					$state = $this->student_fields_model->getStatus('state', $branch_id);
					?>
					<div class="row">
						<?php if ($city['status']) { ?>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('city')?> (শহর / উপজেলা) <?php echo $city['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
									<input type="text" class="form-control" name="city" value="<?=set_value('city', 'সুবর্ণচর')?>" <?php echo $city['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>

						<?php if ($state['status']) { ?>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('state')?> (জেলা / বিভাগ) <?php echo $state['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-map"></i></span>
									<input type="text" class="form-control" name="state" value="<?=set_value('state', 'নোয়াখালী')?>" <?php echo $state['required'] == 1 ? 'required' : ''; ?> />
								</div>
								<span class="error"></span>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Row 7: Student Photo -->
					<?php 
					$student_photo = $this->student_fields_model->getStatus('student_photo', $branch_id);
					if ($student_photo['status']) {
					?>
					<div class="row">
						<div class="col-md-12 mb-sm">
							<div class="form-group">
								<label for="input-file-now"><?=translate('profile_picture')?> (শিক্ষার্থীর ছবি) <?php echo $student_photo['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<input type="file" name="user_photo" class="dropify" data-default-file="<?=get_image_url('student')?>" data-height="110" />
								<span class="error"></span>
							</div>
						</div>
					</div>
					<?php } ?>

					<!-- Row 8: Student Login Credentials -->
					<div class="<?=$getBranch['stu_generate'] == 1 || $getBranch['stu_generate'] == "" ? 'hidden-div' : '' ?>" id="stuLogin">
						<div class="headers-line mt-md">
							<i class="fas fa-user-lock"></i> <?=translate('login_details')?> (শিক্ষার্থী লগইন তথ্য)
						</div>
						<div class="row mb-md">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('username')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-user"></i></span>
										<input type="text" class="form-control" name="username" id="username" value="<?=set_value('username')?>" />
									</div>
									<span class="error"><?=form_error('username')?></span>
								</div>
							</div>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('password')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
										<input type="password" class="form-control" name="password" value="<?=set_value('password')?>" />
									</div>
									<span class="error"><?=form_error('password')?></span>
								</div>
							</div>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('retype_password')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
										<input type="password" class="form-control" name="retype_password" value="<?=set_value('retype_password')?>" />
									</div>
									<span class="error"><?=form_error('retype_password')?></span>
								</div>
							</div>
						</div>
					</div>

					<!-- Step 2 Nav Buttons -->
					<div class="dvs-nav-buttons">
						<div>
							<button type="button" class="btn btn-dvs-prev" onclick="dvsPrevStep(2)">
								<i class="fas fa-arrow-left"></i> পূর্ববর্তী: একাডেমিক তথ্য
							</button>
						</div>
						<div>
							<button type="button" class="btn btn-dvs-next" onclick="dvsNextStep(2)">
								পরবর্তী ধাপ: অভিভাবকের তথ্য (Next) <i class="fas fa-arrow-right"></i>
							</button>
						</div>
					</div>
				</div>

				<!-- ========================================== -->
				<!-- STEP 3: PARENT & GUARDIAN DETAILS          -->
				<!-- ========================================== -->
				<div class="wizard-step-pane" id="wizard-step-3" style="display: none;">
					<div class="dvs-step-header">
						<h4><i class="fas fa-user-friends"></i> ধাপ ৩: পিতা-মাতা ও অভিভাবকের বিবরণ (Parent & Guardian Details)</h4>
						<p>পিতা ও মাতার বাংলা এবং ইংরেজি নাম পাশাপাশি লিখুন এবং মূল অভিভাবকের তথ্য নিশ্চিত করুন।</p>
					</div>

					<!-- Existing Guardian Checkbox -->
					<div class="mb-md checkbox-replace">
						<label class="i-checks">
							<input type="checkbox" name="guardian_chk" id="chkGuardian" value="true" <?php if(set_value('guardian_chk') == true) echo 'checked';?>><i></i> 
							<strong><?=translate('guardian_already_exist')?> (পূর্বেই ডাটাবেজে পিতা/অভিভাবক নিবন্ধিত থাকলে টিক দিন)</strong>
						</label>
					</div>

					<!-- Existing Parent Select -->
					<div class="row" id="exist_list" <?php if(set_value('guardian_chk') != true) echo 'style="display: none;"'; ?>>
						<div class="col-md-12 mb-md">
							<label class="control-label"><?=translate('guardian')?> <span class="required">*</span></label>
							<div class="form-group">
								<?php
									$arrayParent = $this->app_lib->getSelectByBranch('parent', $branch_id);
									echo form_dropdown("parent_id", $arrayParent, set_value('parent_id'), "class='form-control' id='parent_id'
									data-plugin-selectTwo data-width='100%' ");
								?>
								<span class="error"><?=form_error('parent_id')?></span>
							</div>
						</div>
					</div>

					<?php 
					$guardian_name = $this->student_fields_model->getStatus('guardian_name', $branch_id);
					$guardian_relation = $this->student_fields_model->getStatus('guardian_relation', $branch_id);
					$father_name = $this->student_fields_model->getStatus('father_name', $branch_id);
					$mother_name = $this->student_fields_model->getStatus('mother_name', $branch_id);
					$guardian_occupation = $this->student_fields_model->getStatus('guardian_occupation', $branch_id);
					$guardian_income = $this->student_fields_model->getStatus('guardian_income', $branch_id);
					$guardian_education = $this->student_fields_model->getStatus('guardian_education', $branch_id);
					$guardian_city = $this->student_fields_model->getStatus('guardian_city', $branch_id);
					$guardian_state = $this->student_fields_model->getStatus('guardian_state', $branch_id);
					$guardian_mobile_no = $this->student_fields_model->getStatus('guardian_mobile_no', $branch_id);
					$guardian_email = $this->student_fields_model->getStatus('guardian_email', $branch_id);
					$guardian_address = $this->student_fields_model->getStatus('guardian_address', $branch_id);
					$guardian_photo = $this->student_fields_model->getStatus('guardian_photo', $branch_id);
					?>

					<div id="guardian_form" <?php if(set_value('guardian_chk') == true) echo 'style="display: none;"'; ?>>
						<!-- Row 1: Parents' Names in Bangla & English side-by-side -->
						<div class="row">
							<?php if ($father_name['status']) { ?>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label">পিতার নাম (বাংলায়) <?php echo $father_name['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-male"></i></span>
										<input type="text" class="form-control" name="father_name" id="father_name" value="<?=set_value('father_name')?>" placeholder="যেমন: মো: রফিকুল ইসলাম" <?php echo $father_name['required'] == 1 ? 'required' : ''; ?> />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<!-- Custom Field 2: Father Name in English -->
							<?php 
							if ($cf_father_name_en) {
								echo render_single_custom_field($cf_father_name_en, 'student', false, 3, 'পিতার নাম (ইংরেজিতে)', 'e.g. MD. RAFIQUL ISLAM', 'fas fa-font');
							} else { ?>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label">পিতার নাম (ইংরেজিতে) <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-font"></i></span>
										<input type="text" class="form-control" name="custom_fields[student][2]" placeholder="e.g. MD. RAFIQUL ISLAM" required />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($mother_name['status']) { ?>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label">মাতার নাম (বাংলায়) <?php echo $mother_name['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-female"></i></span>
										<input type="text" class="form-control" name="mother_name" id="mother_name" value="<?=set_value('mother_name')?>" placeholder="যেমন: ফাতেমা বেগম" <?php echo $mother_name['required'] == 1 ? 'required' : ''; ?> />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<!-- Custom Field 3: Mother Name in English -->
							<?php 
							if ($cf_mother_name_en) {
								echo render_single_custom_field($cf_mother_name_en, 'student', false, 3, 'মাতার নাম (ইংরেজিতে)', 'e.g. FATEMA BEGUM', 'fas fa-font');
							} else { ?>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label">মাতার নাম (ইংরেজিতে) <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-font"></i></span>
										<input type="text" class="form-control" name="custom_fields[student][3]" placeholder="e.g. FATEMA BEGUM" required />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>

						<!-- Row 2: Guardian Name, Relation, Occupation -->
						<div class="row">
							<?php if ($guardian_name['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label">অভিভাবকের নাম (Guardian Name) <?php echo $guardian_name['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-user-shield"></i></span>
										<input class="form-control" name="grd_name" id="grd_name" type="text" value="<?=set_value('grd_name')?>" placeholder="অভিভাবকের নাম" <?php echo $guardian_name['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($guardian_relation['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label">শিক্ষার্থীর সাথে সম্পর্ক (Relation) <?php echo $guardian_relation['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-hands-helping"></i></span>
										<input type="text" class="form-control" name="grd_relation" id="grd_relation" value="<?=set_value('grd_relation', 'পিতা')?>" placeholder="যেমন: পিতা / মাতা / ভাই / চাচা" <?php echo $guardian_relation['required'] == 1 ? 'required' : ''; ?> />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($guardian_occupation['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('occupation')?> (পেশা) <?php echo $guardian_occupation['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-briefcase"></i></span>
										<input class="form-control" name="grd_occupation" value="<?=set_value('grd_occupation')?>" type="text" placeholder="যেমন: শিক্ষক / ব্যবসায়ী / প্রবাসী" <?php echo $guardian_occupation['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>

						<!-- Row 3: Income, Education, Guardian Mobile -->
						<div class="row">
							<?php if ($guardian_income['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('income')?> (বাৎসরিক আয়) <?php echo $guardian_income['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-coins"></i></span>
										<input class="form-control" name="grd_income" value="<?=set_value('grd_income')?>" type="text" placeholder="যেমন: ৩,০০,০০০" <?php echo $guardian_income['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($guardian_education['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('education')?> (শিক্ষাগত যোগ্যতা) <?php echo $guardian_education['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-graduation-cap"></i></span>
										<input class="form-control" name="grd_education" value="<?=set_value('grd_education')?>" type="text" placeholder="যেমন: অনার্স / মাস্টার্স / এসএসসি" <?php echo $guardian_education['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($guardian_mobile_no['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mobile_no')?> (অভিভাবকের মোবাইল নং) <?php echo $guardian_mobile_no['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
										<input class="form-control" name="grd_mobileno" id="grd_mobileno" type="text" value="<?=set_value('grd_mobileno')?>" placeholder="017xxxxxxxx" <?php echo $guardian_mobile_no['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>

						<!-- Row 4: Guardian Email, City, State -->
						<div class="row">
							<?php if ($guardian_email['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('email')?> (অভিভাবকের ইমেইল) <?php echo $guardian_email['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
										<input type="email" class="form-control" name="grd_email" id="grd_email" value="<?=set_value('grd_email')?>" placeholder="guardian@example.com" <?php echo $guardian_email['required'] == 1 ? 'required' : ''; ?> />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($guardian_city['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('city')?> (শহর / উপজেলা) <?php echo $guardian_city['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
										<input class="form-control" name="grd_city" value="<?=set_value('grd_city', 'সুবর্ণচর')?>" type="text" <?php echo $guardian_city['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>

							<?php if ($guardian_state['status']) { ?>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('state')?> (জেলা / বিভাগ) <?php echo $guardian_state['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-map"></i></span>
										<input class="form-control" name="grd_state" value="<?=set_value('grd_state', 'নোয়াখালী')?>" type="text" <?php echo $guardian_state['required'] == 1 ? 'required' : ''; ?>>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>

						<!-- Row 5: Guardian Address -->
						<?php if ($guardian_address['status']) { ?>
						<div class="row">
							<div class="col-md-12 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('address')?> (অভিভাবকের ঠিকানা) <?php echo $guardian_address['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<textarea name="grd_address" rows="2" class="form-control" placeholder="অভিভাবকের বিস্তারিত ঠিকানা" <?php echo $guardian_address['required'] == 1 ? 'required' : ''; ?>><?=set_value('grd_address')?></textarea>
									<span class="error"></span>
								</div>
							</div>
						</div>
						<?php } ?>

						<!-- Row 6: Guardian Photo -->
						<?php if ($guardian_photo['status']) { ?>
						<div class="row">
							<div class="col-md-12 mb-sm">
								<div class="form-group">
									<label for="input-file-now"><?=translate('guardian_picture')?> (অভিভাবকের ছবি) <?php echo $guardian_photo['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="file" name="guardian_photo" class="dropify" data-default-file="<?=get_image_url('parent')?>" data-height="110" />
									<span class="error"></span>
								</div>
							</div>
						</div>
						<?php } ?>

						<!-- Row 7: Guardian Login Details -->
						<div class="<?=$getBranch['grd_generate'] == 1 || $getBranch['grd_generate'] == "" ? 'hidden-div' : ''?>" id="grdLogin">
							<div class="headers-line mt-md">
								<i class="fas fa-user-lock"></i> <?=translate('login_details')?> (অভিভাবক লগইন তথ্য)
							</div>
							<div class="row mb-lg">
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?=translate('usename')?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="far fa-user"></i></span>
											<input type="text" class="form-control" name="grd_username" id="grd_username" value="<?=set_value('grd_username')?>" />
										</div>
										<span class="error"><?=form_error('grd_username')?></span>
									</div>
								</div>
								<div class="col-md-3 mb-sm">
									<div class="form-group">
										<label class="control-label"><?=translate('password')?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
											<input type="password" class="form-control" name="grd_password" value="<?=set_value('grd_password')?>" />
										</div>
										<span class="error"><?=form_error('grd_password')?></span>
									</div>
								</div>
								<div class="col-md-3 mb-sm">
									<div class="form-group">
										<label class="control-label"><?=translate('retype_password')?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
											<input type="password" class="form-control" name="grd_retype_password" value="<?=set_value('grd_retype_password')?>" />
										</div>
										<span class="error"><?=form_error('grd_retype_password')?></span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Step 3 Nav Buttons -->
					<div class="dvs-nav-buttons">
						<div>
							<button type="button" class="btn btn-dvs-prev" onclick="dvsPrevStep(3)">
								<i class="fas fa-arrow-left"></i> পূর্ববর্তী: শিক্ষার্থীর তথ্য
							</button>
						</div>
						<div>
							<button type="button" class="btn btn-dvs-next" onclick="dvsNextStep(3)">
								পরবর্তী ধাপ: সুবিধা ও সাবমিট (Next) <i class="fas fa-arrow-right"></i>
							</button>
						</div>
					</div>
				</div>

				<!-- ========================================== -->
				<!-- STEP 4: FACILITIES, REVIEW & SUBMIT        -->
				<!-- ========================================== -->
				<div class="wizard-step-pane" id="wizard-step-4" style="display: none;">
					<div class="dvs-step-header">
						<h4><i class="fas fa-tasks"></i> ধাপ ৪: অন্যান্য সুযোগ-সুবিধা ও ভর্তি চূড়ান্তকরণ (Facilities & Submission)</h4>
						<p>পরিবহন, হোস্টেল বা পূর্ববর্তী বিদ্যালয়ের তথ্য দিন এবং সকল তথ্য এক নজরে যাচাই করে ভর্তি সংরক্ষণ করুন।</p>
					</div>

					<!-- Transport Details -->
					<?php if (moduleIsEnabled('transport')) { ?>
					<div class="headers-line">
						<i class="fas fa-bus-alt"></i> <?=translate('transport_details')?> (যাতায়াত / পরিবহন সুবিধা)
					</div>

					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('transport_route')?></label>
								<?php
									$arrayRoute = $this->app_lib->getSelectByBranch('transport_route', $branch_id);
									echo form_dropdown("route_id", $arrayRoute, set_value('route_id'), "class='form-control' id='route_id'
									data-plugin-selectTwo data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('vehicle_no')?></label>
								<?php
									$arrayVehicle = $this->app_lib->getVehicleByRoute(set_value('route_id'));
									echo form_dropdown("vehicle_id", $arrayVehicle, set_value('vehicle_id'), "class='form-control' id='vehicle_id'
									data-plugin-selectTwo data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('pickup_point')?></label>
								<?php
									$arrayStoppage = $this->app_lib->getStoppagePoinByRoute(set_value('route_id'));
									echo form_dropdown("stoppage_point_id", $arrayStoppage, set_value('stoppage_point_id'), "class='form-control' id='stoppage_point_id'
									data-plugin-selectTwo data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
					</div>
					<div class="row mb-md">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('fees_allocation') ?></label>
								<select class="form-control" multiple="multiple" name="transport_fees_month[]" data-plugin-multiselect data-plugin-options='{"numberDisplayed" : 6,"buttonClass": "btn btn-default cus-multiselect", "buttonWidth": "100%", "maxHeight": 400, "maxWidth": 400, "includeSelectAllOption": true, "nonSelectedText" : "<?=translate('select_month')?>" }'>
									<?php 
									if ($this->db->table_exists('transport_fee_fine')) {
										$cur_session_id = get_session_id();
										$this->db->where('branch_id', $branch_id);
										$this->db->where('session_id', $cur_session_id);
										$this->db->order_by('month', 'asc');
										$results = $this->db->get('transport_fee_fine')->result();
										$getMonths = $this->app_lib->getMonthslist();
										if (!empty($results)) {
											foreach ($results as $key => $value) {
												$monthName = isset($getMonths[$value->month]) ? $getMonths[$value->month] : $value->month;
												echo '<option value="'.$value->id.'">'.$monthName.'</option>';
											}
										}
									}
									?>
								</select>
								<span class="error"></span>
							</div>
						</div>
					</div>
					<?php } ?>

					<!-- Hostel Details -->
					<?php if (moduleIsEnabled('hostel')) { ?>
					<div class="headers-line">
						<i class="fas fa-hotel"></i> <?=translate('hostel_details')?> (আবাসিক / হোস্টেল সুবিধা)
					</div>
					
					<div class="row mb-md">
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('hostel_name')?></label>
								<?php
									$arrayHostel = $this->app_lib->getSelectByBranch('hostel', $branch_id);
									echo form_dropdown("hostel_id", $arrayHostel, set_value('hostel_id'), "class='form-control' id='hostel_id'
									data-plugin-selectTwo data-width='100%' ");
								?>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('room_name')?></label>
								<?php
									$arrayRoom = $this->app_lib->getRoomByHostel(set_value('hostel_id'));
									echo form_dropdown("room_id", $arrayRoom, set_value('room_id'), "class='form-control' id='room_id'
									data-plugin-selectTwo data-width='100%' ");
								?>
							</div>
						</div>
					</div>
					<?php } ?>

					<!-- Previous School Details -->
					<?php
					$previous_school_details = $this->student_fields_model->getStatus('previous_school_details', $branch_id);
					if ($previous_school_details['status']) {
					?>
					<div class="headers-line">
						<i class="fas fa-bezier-curve"></i> <?=translate('previous_school_details')?> (পূর্ববর্তী বিদ্যালয়ের তথ্য)
					</div>
					<div class="row">
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('school_name')?><?php echo $previous_school_details['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<input type="text" class="form-control" name="school_name" value="<?=set_value('school_name')?>" <?php echo $previous_school_details['required'] == 1 ? 'required' : ''; ?> />
								<span class="error"></span>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('qualification')?><?php echo $previous_school_details['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
								<input type="text" class="form-control" name="qualification" value="<?=set_value('qualification')?>" <?php echo $previous_school_details['required'] == 1 ? 'required' : ''; ?> />
								<span class="error"></span>
							</div>
						</div>
					</div>
					<div class="row mb-md">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label"><?=translate('remarks')?> (মন্তব্য)</label>
								<textarea name="previous_remarks" rows="2" class="form-control"><?=set_value('previous_remarks')?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>

					<!-- Other Custom Fields (if any extra custom field exists) -->
					<div class="row" id="customFields">
						<?php echo render_custom_Fields_excluding('student', $branch_id, false, 4, $placed_cf_ids); ?>
					</div>

					<!-- Visual Summary Review Card -->
					<div class="dvs-summary-box mt-md">
						<div class="dvs-summary-title">
							<i class="fas fa-clipboard-check"></i> ভর্তির তথ্যাবলী যাচাই ও পর্যালোচনা (Admission Details Review)
						</div>
						<div class="row">
							<div class="col-md-4">
								<table class="dvs-summary-table">
									<tr><td class="label-col">শ্রেণি (Class):</td><td class="val-col" id="sum_class">-</td></tr>
									<tr><td class="label-col">শাখা (Section):</td><td class="val-col" id="sum_section">-</td></tr>
									<tr><td class="label-col">রেজিস্টার নং:</td><td class="val-col" id="sum_reg">-</td></tr>
									<tr><td class="label-col">রোল নং:</td><td class="val-col" id="sum_roll">-</td></tr>
								</table>
							</div>
							<div class="col-md-4">
								<table class="dvs-summary-table">
									<tr><td class="label-col">শিক্ষার্থীর নাম (বাং):</td><td class="val-col" id="sum_name_bn">-</td></tr>
									<tr><td class="label-col">নাম (ইংরেজিতে):</td><td class="val-col" id="sum_name_en">-</td></tr>
									<tr><td class="label-col">লিঙ্গ ও জন্ম তারিখ:</td><td class="val-col" id="sum_gender_dob">-</td></tr>
									<tr><td class="label-col">মোবাইল নম্বর:</td><td class="val-col" id="sum_mobile">-</td></tr>
								</table>
							</div>
							<div class="col-md-4">
								<table class="dvs-summary-table">
									<tr><td class="label-col">পিতার নাম:</td><td class="val-col" id="sum_father">-</td></tr>
									<tr><td class="label-col">মাতার নাম:</td><td class="val-col" id="sum_mother">-</td></tr>
									<tr><td class="label-col">মূল অভিভাবক:</td><td class="val-col" id="sum_guardian">-</td></tr>
									<tr><td class="label-col">অভিভাবকের মোবাইল:</td><td class="val-col" id="sum_grd_mobile">-</td></tr>
								</table>
							</div>
						</div>
					</div>

					<div class="checkbox-replace mb-lg">
						<label class="i-checks">
							<input type="checkbox" id="confirmDataCorrect" checked><i></i> 
							<strong>আমি নিশ্চিত করছি যে উপরে প্রদত্ত সকল তথ্য সতর্কতার সাথে যাচাই করা হয়েছে এবং তা সম্পূর্ণ নির্ভুল।</strong>
						</label>
					</div>

					<!-- Step 4 Nav Buttons -->
					<div class="dvs-nav-buttons">
						<div>
							<button type="button" class="btn btn-dvs-prev" onclick="dvsPrevStep(4)">
								<i class="fas fa-arrow-left"></i> পূর্ববর্তী: অভিভাবকের তথ্য
							</button>
						</div>
						<div>
							<button type="submit" id="btnSubmitAdmission" data-loading-text="<i class='fas fa-spinner fa-spin'></i> সংরক্ষণ হচ্ছে..." class="btn btn-dvs-submit">
								<i class="fas fa-check-circle"></i> ভর্তি সম্পন্ন করুন ও সংরক্ষণ করুন (Confirm & Save)
							</button>
						</div>
					</div>
				</div>

			</div>
			<?php echo form_close();?>
		</section>
	</div>
</div>

<!-- Step Wizard JavaScript Controller -->
<script type="text/javascript">
var currentWizardStep = 1;
var totalWizardSteps = 4;

function dvsUpdateWizardUI(step) {
	currentWizardStep = step;
	
	// Show active step pane, hide others
	for (var i = 1; i <= totalWizardSteps; i++) {
		if (i === step) {
			$('#wizard-step-' + i).fadeIn(250);
		} else {
			$('#wizard-step-' + i).hide();
		}
	}

	// Update stepper badges
	$('#dvsStepList .dvs-step-item').each(function() {
		var itemStep = parseInt($(this).data('step'), 10);
		$(this).removeClass('active completed');
		if (itemStep < step) {
			$(this).addClass('completed');
			$(this).find('.dvs-step-badge').html('<i class="fas fa-check"></i>');
		} else if (itemStep === step) {
			$(this).addClass('active');
			var icon = (step === 1) ? 'fa-school' : (step === 2 ? 'fa-user-graduate' : (step === 3 ? 'fa-user-friends' : 'fa-check-circle'));
			$(this).find('.dvs-step-badge').html('<i class="fas ' + icon + '"></i>');
		} else {
			var icon = (itemStep === 1) ? 'fa-school' : (itemStep === 2 ? 'fa-user-graduate' : (itemStep === 3 ? 'fa-user-friends' : 'fa-check-circle'));
			$(this).find('.dvs-step-badge').html('<i class="fas ' + icon + '"></i>');
		}
	});

	// Update progress bar fill
	var progressPercent = (step / totalWizardSteps) * 100;
	$('#dvsProgressFill').css('width', progressPercent + '%');

	// Populate Step 4 Summary Review Card
	if (step === 4) {
		dvsPopulateSummary();
	}

	// Trigger Select2 & Dropify resize to fix dimensions inside newly visible panes
	$(window).trigger('resize');
	if ($.fn.select2) {
		$('#wizard-step-' + step + ' [data-plugin-selecttwo]').each(function() {
			var $el = $(this);
			$el.css('width', '100%');
		});
	}

	// Smooth scroll to top of wizard
	$('html, body').animate({
		scrollTop: $('.dvs-wizard-wrapper').offset().top - 80
	}, 350);
}

function dvsValidateStep(step) {
	var $pane = $('#wizard-step-' + step);
	var isValid = true;
	var $firstInvalid = null;

	// Reset prior highlights
	$pane.find('.field-invalid-highlight').removeClass('field-invalid-highlight');

	// Check standard required inputs
	$pane.find('input[required], select[required], textarea[required]').each(function() {
		var $field = $(this);
		// Ignore hidden fields or fields inside hidden wrappers (e.g. existing parent toggles)
		if (!$field.is(':visible') && $field.closest('.form-group').is(':hidden')) {
			return;
		}

		var val = $.trim($field.val());
		if (!val || val === '') {
			isValid = false;
			$field.addClass('field-invalid-highlight');
			$field.closest('.form-group').find('.error').text('এই তথ্যটি প্রদান করা বাধ্যতামূলক (Required)');
			if (!$firstInvalid) {
				$firstInvalid = $field;
			}
		} else {
			$field.closest('.form-group').find('.error').text('');
		}
	});

	// Step-specific validations
	if (step === 3) {
		var isExistingGrd = $('#chkGuardian').is(':checked');
		if (isExistingGrd) {
			var parentId = $('#parent_id').val();
			if (!parentId) {
				isValid = false;
				$('#parent_id').addClass('field-invalid-highlight');
				$('#parent_id').closest('.form-group').find('.error').text('অভিভাবক নির্বাচন করুন (Please select guardian)');
				if (!$firstInvalid) $firstInvalid = $('#parent_id');
			}
		}
	}

	if (!isValid && $firstInvalid) {
		$('html, body').animate({
			scrollTop: $firstInvalid.offset().top - 120
		}, 350);
		$firstInvalid.focus();
		if (typeof swal === 'function') {
			swal({
				title: "তথ্য অসম্পূর্ণ!",
				text: "অনুগ্রহ করে এই ধাপের লাল চিহ্নিত বাধ্যতামূলক ফিল্ডগুলো পূরণ করুন।",
				type: "warning",
				button: "ঠিক আছে"
			});
		} else {
			alert("অনুগ্রহ করে এই ধাপের লাল চিহ্নিত সকল বাধ্যতামূলক তথ্য পূরণ করুন।");
		}
	}

	return isValid;
}

function dvsNextStep(currentStep) {
	if (dvsValidateStep(currentStep)) {
		dvsUpdateWizardUI(currentStep + 1);
	}
}

function dvsPrevStep(currentStep) {
	if (currentStep > 1) {
		dvsUpdateWizardUI(currentStep - 1);
	}
}

function dvsCalculateAgeBangla(dobStr) {
	if (!dobStr) return '';
	var parts = dobStr.split('-');
	if (parts.length !== 3) return '';
	var y = parseInt(parts[0], 10), m = parseInt(parts[1], 10) - 1, d = parseInt(parts[2], 10);
	var birthDate = new Date(y, m, d);
	if (isNaN(birthDate.getTime())) return '';
	var today = new Date();
	var years = today.getFullYear() - birthDate.getFullYear();
	var months = today.getMonth() - birthDate.getMonth();
	var days = today.getDate() - birthDate.getDate();
	if (days < 0) { months--; }
	if (months < 0) { years--; months += 12; }
	if (years < 0) return '';
	var enDigits = ['0','1','2','3','4','5','6','7','8','9'];
	var bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
	var toBn = function(num) {
		return String(num).split('').map(function(ch) {
			var idx = enDigits.indexOf(ch);
			return idx !== -1 ? bnDigits[idx] : ch;
		}).join('');
	};
	var res = [];
	if (years > 0) res.push(toBn(years) + ' বছর');
	if (months > 0) res.push(toBn(months) + ' মাস');
	if (res.length === 0 && days > 0) res.push(toBn(days) + ' দিন');
	return res.length > 0 ? res.join(' ') : '০ বছর';
}

function dvsPopulateSummary() {
	var classText = $('#class_id option:selected').text();
	var sectionText = $('#section_id option:selected').text();
	var regNo = $('#register_no').val() || '-';
	var rollNo = $('#roll').val() || '-';

	var firstName = $('#first_name').val() || '';
	var lastName = $('#last_name').val() || '';
	var fullNameBn = $.trim(firstName + ' ' + lastName) || '-';

	var fullNameEn = $('input[name="custom_fields[student][1]"]').val() || '-';
	var gender = $('#gender option:selected').text() || '-';
	var dob = $('#birthday').val() || '-';
	var mobile = $('#mobileno').val() || '-';

	var dobDisplay = dob;
	if (dob && dob !== '-') {
		var ageBn = dvsCalculateAgeBangla(dob);
		if (ageBn) {
			dobDisplay += ' (বয়স: ' + ageBn + ')';
		}
	}

	var fatherName = $('#father_name').val() || '-';
	var motherName = $('#mother_name').val() || '-';
	var grdName = $('#chkGuardian').is(':checked') ? ($('#parent_id option:selected').text() || 'পূর্ব নিবন্ধিত') : ($('#grd_name').val() || '-');
	var grdMobile = $('#grd_mobileno').val() || '-';

	$('#sum_class').text(classText);
	$('#sum_section').text(sectionText);
	$('#sum_reg').text(regNo);
	$('#sum_roll').text(rollNo);

	$('#sum_name_bn').text(fullNameBn);
	$('#sum_name_en').text(fullNameEn);
	$('#sum_gender_dob').text(gender + ' / ' + dobDisplay);
	$('#sum_mobile').text(mobile);

	$('#sum_father').text(fatherName);
	$('#sum_mother').text(motherName);
	$('#sum_guardian').text(grdName);
	$('#sum_grd_mobile').text(grdMobile);
}

$(document).ready(function() {
	// Enable direct clicking on step pills to jump back to completed steps
	$('#dvsStepList .dvs-step-item').on('click', function() {
		var targetStep = parseInt($(this).data('step'), 10);
		if (targetStep < currentWizardStep) {
			dvsUpdateWizardUI(targetStep);
		} else if (targetStep > currentWizardStep) {
			if (dvsValidateStep(currentWizardStep)) {
				dvsUpdateWizardUI(targetStep);
			}
		}
	});

	// Remove highlight on input
	$(document).on('input change', '.field-invalid-highlight', function() {
		$(this).removeClass('field-invalid-highlight');
		$(this).closest('.form-group').find('.error').text('');
	});

	// Checkbox confirmation before submission
	$('#studentAdmissionForm').on('submit', function(e) {
		if (!$('#confirmDataCorrect').is(':checked')) {
			e.preventDefault();
			alert('ভর্তি নিশ্চিত করতে নিচের চেকবাক্সে টিক দিন।');
			return false;
		}
	});

	// Auto-jump to the step containing server validation errors
	$(document).ajaxSuccess(function(event, xhr, settings) {
		if (settings.url && settings.url.indexOf('student/save') !== -1) {
			try {
				var res = JSON.parse(xhr.responseText);
				if (res.status === 'fail' && res.error) {
					setTimeout(function() {
						for (var step = 1; step <= totalWizardSteps; step++) {
							var $pane = $('#wizard-step-' + step);
							var $errors = $pane.find('.error:not(:empty), .has-error');
							if ($errors.length > 0) {
								dvsUpdateWizardUI(step);
								$('html, body').animate({
									scrollTop: $errors.first().offset().top - 120
								}, 400);
								break;
							}
						}
					}, 150);
				}
			} catch (err) {}
		}
	});
});
</script>

<?php endif; ?>

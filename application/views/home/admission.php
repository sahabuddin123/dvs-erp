<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang === 'en' || $current_lang === 'english');

$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($is_en ? 'Online Admission (School)' : 'অনলাইন ভর্তি আবেদন (বিদ্যালয়)')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($is_en ? 'Online Admission (School)' : 'অনলাইন ভর্তি আবেদন (বিদ্যালয়)'));
$page_subtitle = $is_en ? "To apply online for admission, please fill out the application form step-by-step." : "অনলাইনে ভর্তির আবেদনের জন্য অনুগ্রহ করে নিচের ধাপগুলো সঠিকভাবে পূরণ করুন।";

// Fetch Online Custom Fields for Student
$all_online_custom_fields = getOnlineCustomFields('student', $branchID);
$cf_by_id = array();
foreach ($all_online_custom_fields as $cf) {
    $cf_by_id[$cf['id']] = $cf;
}

if (!function_exists('dvs_find_online_cf')) {
    function dvs_find_online_cf($cfs, $preferred_id, $keywords = array()) {
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

$cf_student_name_en = dvs_find_online_cf($cf_by_id, 1, array('পূর্ণ নাম', 'ইংরেজি'));
$cf_father_name_en  = dvs_find_online_cf($cf_by_id, 2, array('পিতার', 'ইংরেজি'));
$cf_mother_name_en  = dvs_find_online_cf($cf_by_id, 3, array('মাতার', 'ইংরেজি'));
$cf_birth_reg_no    = dvs_find_online_cf($cf_by_id, 4, array('জন্ম নিবন্ধন'));
$cf_issue_date      = dvs_find_online_cf($cf_by_id, 5, array('ইস্যু'));
$cf_stipend         = dvs_find_online_cf($cf_by_id, 11, array('উপবৃত্তি'));
$cf_student_age     = dvs_find_online_cf($cf_by_id, 12, array('বয়স'));

$placed_cf_ids = array();
if ($cf_student_name_en) $placed_cf_ids[] = $cf_student_name_en['id'];
if ($cf_father_name_en)  $placed_cf_ids[] = $cf_father_name_en['id'];
if ($cf_mother_name_en)  $placed_cf_ids[] = $cf_mother_name_en['id'];
if ($cf_birth_reg_no)    $placed_cf_ids[] = $cf_birth_reg_no['id'];
if ($cf_issue_date)      $placed_cf_ids[] = $cf_issue_date['id'];
if ($cf_stipend)         $placed_cf_ids[] = $cf_stipend['id'];
if ($cf_student_age)     $placed_cf_ids[] = $cf_student_age['id'];
?>

<!-- Page Header (DVS Match) -->
<script type="text/javascript">
window.getSectionByClass = function(class_id) {
    var $sec = $('#online_section_id, #section_id, select[name="section"], select[name="section_id"]');
    var isEn = <?php echo $is_en ? 'true' : 'false'; ?>;
    if (!class_id) {
        var placeholder = isEn ? 'Select Class First' : 'প্রথমে শ্রেণি নির্বাচন করুন';
        $sec.html('<option value="">' + placeholder + '</option>');
        if ($.fn.select2 && $sec.hasClass('select2-hidden-accessible')) {
            $sec.trigger('change.select2');
        }
        return;
    }

    var loadingText = isEn ? 'Loading Sections...' : 'শাখা লোড হচ্ছে...';
    $sec.html('<option value="">' + loadingText + '</option>');

    var postData = { class_id: class_id };
    if (typeof csrfData !== 'undefined' && csrfData) {
        $.extend(postData, csrfData);
    }

    var ajaxUrl = (typeof base_url !== 'undefined' ? base_url : '<?=base_url()?>') + 'home/getSectionByClass';

    $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: postData,
        dataType: 'html',
        success: function(response) {
            $sec.html(response);
            if ($.fn.select2 && $sec.hasClass('select2-hidden-accessible')) {
                $sec.trigger('change.select2');
            }
        },
        error: function() {
            var errorText = isEn ? 'No Sections Available' : 'শাখা পাওয়া যায়নি';
            $sec.html('<option value="">' + errorText + '</option>');
        }
    });
};
</script>
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Container Starts -->
<div class="container my-5 main-container">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6 mb-2 mb-sm-0">
            <span class="badge" style="background: rgba(0, 127, 61, 0.1); color: var(--primary-color); font-size: 14px; padding: 8px 14px; font-weight: 600;">
                <i class="fas fa-clock me-1"></i> <?php echo $is_en ? 'Academic Session: ' . date('Y') : 'শিক্ষাবর্ষ: ' . date('Y'); ?>
            </span>
        </div>
        <div class="col-sm-6 text-sm-end">
            <button type="button" class="btn text-white px-4 py-2" style="background: var(--primary-color); border-radius: 6px; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#admissionModal">
                <i class="fas fa-clipboard-check me-2"></i> <?php echo $is_en ? 'Check Admission Status' : 'ভর্তির অবস্থা যাচাই করুন'; ?>
            </button>
            <?php if (!empty($page_data['application_form_file'])) { ?>
                <a class="btn btn-outline-success ms-2 px-3 py-2" style="border-radius: 6px;" href="<?php echo base_url('home/download_application_form/' . rtrim(strtr(base64_encode($branchID), '+/', '-_'), '=')); ?>">
                    <i class="fas fa-download me-1"></i> <?php echo $is_en ? 'Download Form' : 'ফরম ডাউনলোড'; ?>
                </a> 
            <?php } ?>
        </div>
    </div>

    <!-- Custom Wizard Styling -->
    <style>
        .online-wizard-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .online-wizard-header {
            background: linear-gradient(135deg, #007F3D 0%, #005f2e 100%);
            color: #fff;
            padding: 22px 25px 18px;
        }
        .online-wizard-header h4 {
            margin: 0 0 5px;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }
        .online-wizard-header p {
            margin: 0;
            color: rgba(255,255,255,0.85);
            font-size: 13px;
        }
        .online-step-navigator {
            padding: 22px 20px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .online-step-list {
            display: flex;
            justify-content: space-between;
            list-style: none;
            padding: 0;
            margin: 0 0 15px;
            position: relative;
        }
        .online-step-item {
            flex: 1;
            text-align: center;
            position: relative;
            cursor: pointer;
            padding: 0 8px;
        }
        .online-step-item:not(:last-child):after {
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
        .online-step-item.completed:not(:last-child):after {
            background: #007F3D;
        }
        .online-step-badge {
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
        .online-step-item.active .online-step-badge {
            background: #007F3D;
            border-color: #007F3D;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(0, 127, 61, 0.2);
            transform: scale(1.08);
        }
        .online-step-item.completed .online-step-badge {
            background: #10b981;
            border-color: #10b981;
            color: #fff;
        }
        .online-step-label {
            margin-top: 8px;
            display: block;
        }
        .online-step-label .step-main {
            font-weight: 700;
            color: #475569;
            font-size: 13.5px;
            display: block;
        }
        .online-step-item.active .online-step-label .step-main {
            color: #007F3D;
        }
        .online-step-label .step-sub {
            color: #94a3b8;
            font-size: 11px;
            display: block;
        }
        .online-progress-track {
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 5px;
        }
        .online-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #007F3D 0%, #10b981 100%);
            width: 25%;
            transition: width 0.4s ease;
        }
        .wizard-step-pane {
            padding: 25px 25px 15px;
        }
        .online-pane-title {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
            margin-bottom: 22px;
        }
        .online-pane-title h5 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
        }
        .online-pane-title h5 i {
            color: #007F3D;
            margin-right: 8px;
        }
        .online-pane-title p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 12.5px;
        }
        .online-nav-footer {
            background: #f8fafc;
            padding: 18px 25px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-online-prev {
            border-radius: 6px;
            padding: 9px 22px;
            font-weight: 600;
            color: #475569;
            background: #fff;
            border: 1px solid #cbd5e1;
            transition: all 0.2s;
        }
        .btn-online-prev:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .btn-online-next {
            border-radius: 6px;
            padding: 9px 24px;
            font-weight: 600;
            color: #fff;
            background: #007F3D;
            border: none;
            box-shadow: 0 3px 8px rgba(0, 127, 61, 0.3);
            transition: all 0.2s;
        }
        .btn-online-next:hover {
            background: #005f2e;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 127, 61, 0.4);
        }
        .btn-online-submit {
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
        .btn-online-submit:hover {
            background: linear-gradient(135deg, #005f2e 0%, #047857 100%);
            box-shadow: 0 6px 18px rgba(0, 127, 61, 0.5);
        }
        .online-card-highlight {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }
        .online-card-highlight h6 {
            margin: 0 0 4px;
            color: #166534;
            font-weight: 700;
            font-size: 13.5px;
        }
        .online-card-highlight p {
            margin: 0;
            color: #15803d;
            font-size: 12.5px;
        }
        .online-summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 20px;
        }
        .online-summary-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .online-summary-title i {
            color: #007F3D;
            margin-right: 6px;
        }
        .online-summary-table {
            width: 100%;
            font-size: 13px;
        }
        .online-summary-table td {
            padding: 5px 8px;
        }
        .online-summary-table td.label-col {
            color: #64748b;
            font-weight: 600;
            width: 40%;
        }
        .online-summary-table td.val-col {
            color: #1e293b;
            font-weight: 600;
        }
        .field-invalid-highlight {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
        }
        @media (max-width: 768px) {
            .online-step-label { display: none; }
            .online-step-item:not(:last-child):after { top: 18px; }
            .online-step-badge { width: 36px; height: 36px; line-height: 32px; font-size: 13px; }
            .online-nav-footer { flex-direction: column-reverse; gap: 10px; }
            .online-nav-footer button { width: 100%; text-align: center; }
        }
    </style>

    <div class="online-wizard-card">
        <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data', 'id' => 'onlineAdmissionForm')); ?>
        
        <!-- Wizard Banner -->
        <div class="online-wizard-header">
            <h4><i class="fas fa-graduation-cap me-2"></i> <?php echo $is_en ? 'Online Student Admission Application' : 'শিক্ষার্থী অনলাইন ভর্তি আবেদন'; ?></h4>
            <p><?php echo $is_en ? 'Please complete all 4 steps carefully to submit your admission application.' : 'অনলাইনে ভর্তির আবেদন সম্পন্ন করতে অনুগ্রহ করে নিচের ৪টি ধাপ সতর্কতার সাথে পূরণ করুন।'; ?></p>
        </div>

        <!-- Wizard Stepper Tabs -->
        <div class="online-step-navigator">
            <ul class="online-step-list" id="onlineStepList">
                <li class="online-step-item active" data-step="1">
                    <div class="online-step-badge"><i class="fas fa-school"></i></div>
                    <div class="online-step-label">
                        <span class="step-main"><?php echo $is_en ? 'Step 1: Academic' : 'ধাপ ১: একাডেমিক'; ?></span>
                        <span class="step-sub"><?php echo $is_en ? 'Class & Admission' : 'শ্রেণি ও ভর্তির তথ্য'; ?></span>
                    </div>
                </li>
                <li class="online-step-item" data-step="2">
                    <div class="online-step-badge"><i class="fas fa-user-graduate"></i></div>
                    <div class="online-step-label">
                        <span class="step-main"><?php echo $is_en ? 'Step 2: Student' : 'ধাপ ২: শিক্ষার্থীর তথ্য'; ?></span>
                        <span class="step-sub"><?php echo $is_en ? 'Names & Identity' : 'নাম ও পরিচিতি'; ?></span>
                    </div>
                </li>
                <li class="online-step-item" data-step="3">
                    <div class="online-step-badge"><i class="fas fa-user-friends"></i></div>
                    <div class="online-step-label">
                        <span class="step-main"><?php echo $is_en ? 'Step 3: Parents' : 'ধাপ ৩: অভিভাবক'; ?></span>
                        <span class="step-sub"><?php echo $is_en ? 'Guardian & Contact' : 'পিতা-মাতা ও যোগাযোগ'; ?></span>
                    </div>
                </li>
                <li class="online-step-item" data-step="4">
                    <div class="online-step-badge"><i class="fas fa-check-circle"></i></div>
                    <div class="online-step-label">
                        <span class="step-main"><?php echo $is_en ? 'Step 4: Review' : 'ধাপ ৪: চূড়ান্তকরণ'; ?></span>
                        <span class="step-sub"><?php echo $is_en ? 'Review & Submit' : 'যাচাই ও সাবমিশন'; ?></span>
                    </div>
                </li>
            </ul>
            <div class="online-progress-track">
                <div class="online-progress-fill" id="onlineProgressFill" style="width: 25%;"></div>
            </div>
        </div>

        <div class="card-body p-0">

            <!-- ========================================== -->
            <!-- STEP 1: ACADEMIC DETAILS                   -->
            <!-- ========================================== -->
            <div class="wizard-step-pane" id="online-step-1" style="display: block;">
                <div class="online-pane-title">
                    <h5><i class="fas fa-school"></i> <?php echo $is_en ? 'Step 1: Academic & Class Details' : 'ধাপ ১: একাডেমিক ও শ্রেণির বিবরণ'; ?></h5>
                    <p><?php echo $is_en ? 'Select the school branch, class, section, and admission category.' : 'বিদ্যালয়ের নাম, কাঙ্ক্ষিত শ্রেণি, শাখা ও ক্যাটাগরি নির্বাচন করুন।'; ?></p>
                </div>

                <?php 
                $section = $this->student_fields_model->getOnlineStatus('section', $branchID);
                $admission_date = $this->student_fields_model->getOnlineStatus('admission_date', $branchID);
                $category = $this->student_fields_model->getOnlineStatus('category', $branchID);
                ?>

                <div class="row">
                    <div class="col-md-<?php echo $section['status'] == 1 ? '4' : '6' ?> mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'School Name' : 'বিদ্যালয়ের নাম'; ?> <span class="required text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-landmark"></i></span>
                                <?php
                                $branchSchoolName = get_type_name_by_id('branch', $branchID, 'school_name');
                                if (!$is_en && !empty($cms_setting['application_title'])) {
                                    $displaySchoolName = $cms_setting['application_title'];
                                } else {
                                    $displaySchoolName = !empty($branchSchoolName) ? $branchSchoolName : 'Dream Valley School';
                                }
                                ?>
                                <input type="text" class="form-control" name="schoolname" value="<?php echo html_escape($displaySchoolName); ?>" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-<?php echo $section['status'] == 1 ? '4' : '6' ?> mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Class' : 'শ্রেণি'; ?> <span class="required text-danger">*</span></label>
                            <?php
                                $arrayClass = $this->app_lib->getClass($branchID);
                                echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-select form-control' id='online_class_id' data-plugin-selectTwo onchange='getSectionByClass(this.value)' required");
                            ?>
                            <span class="error text-danger"></span>
                        </div>
                    </div>

                    <?php if ($section['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Section' : 'শাখা'; ?><?php echo $section['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <?php
                                $arraySection = $this->app_lib->getSections(set_value('class_id'), false);
                                echo form_dropdown("section", $arraySection, set_value('section'), "class='form-select form-control' data-plugin-selectTwo id='online_section_id' " . ($section['required'] == 1 ? 'required' : ''));
                            ?>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <?php if ($admission_date['status']) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Admission Date' : 'আবেদনের তারিখ'; ?><?php echo $admission_date['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                <input type="text" class="form-control" data-plugin-datepicker name="admission_date" id="online_admission_date" readonly value="<?php echo date('Y-m-d') ?>" autocomplete="off" <?php echo $admission_date['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($category['status']) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Category' : 'শিক্ষার্থী ক্যাটাগরি'; ?><?php echo $category['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <?php
                                $arrayCategory = $this->app_lib->getStudentCategory($branchID);
                                echo form_dropdown("category", $arrayCategory, set_value('category_id'), "class='form-select form-control'
                                data-plugin-selectTwo data-width='100%' id='online_category_id' data-minimum-results-for-search='Infinity' " . ($category['required'] == 1 ? 'required' : ''));
                            ?>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Step 1 Navigation Footer -->
                <div class="online-nav-footer">
                    <div></div>
                    <div>
                        <button type="button" class="btn btn-online-next" onclick="onlineNextStep(1)">
                            <?php echo $is_en ? 'Next: Student Details' : 'পরবর্তী ধাপ: শিক্ষার্থীর তথ্য'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 2: STUDENT DETAILS                    -->
            <!-- ========================================== -->
            <div class="wizard-step-pane" id="online-step-2" style="display: none;">
                <div class="online-pane-title">
                    <h5><i class="fas fa-user-edit"></i> <?php echo $is_en ? 'Step 2: Student Details & Identity' : 'ধাপ ২: শিক্ষার্থীর ব্যক্তিগত তথ্য ও পরিচিতি'; ?></h5>
                    <p><?php echo $is_en ? 'Enter names in Bengali and English side-by-side, along with birth and contact details.' : 'শিক্ষার্থীর নাম বাংলায় এবং ইংরেজিতে পাশাপাশি নির্ভুলভাবে পূরণ করুন।'; ?></p>
                </div>

                <div class="online-card-highlight">
                    <h6><i class="fas fa-info-circle me-1"></i> <?php echo $is_en ? 'Name Entry Guidelines:' : 'নাম লেখার নির্দেশনা:'; ?></h6>
                    <p><?php echo $is_en ? 'Please enter First Name and Last Name in Bengali, and Full Name in English (Capital Letters).' : 'প্রথম দুটি বক্সে শিক্ষার্থীর নাম বাংলায় (প্রথম অংশ ও শেষ অংশ) লিখুন এবং পাশের বক্সে ইংরেজিতে পূর্ণ নাম লিখুন।'; ?></p>
                </div>

                <?php 
                $last_name = $this->student_fields_model->getOnlineStatus('last_name', $branchID);
                $gender = $this->student_fields_model->getOnlineStatus('gender', $branchID);
                $birthday = $this->student_fields_model->getOnlineStatus('birthday', $branchID);
                $blood_group = $this->student_fields_model->getOnlineStatus('blood_group', $branchID);
                ?>

                <!-- Row 1: Names side-by-side -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">
                                <?php echo $is_en ? 'First Name (in Bengali)' : 'নামের প্রথম অংশ (বাংলায়)'; ?> <span class="required text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="first_name" id="online_first_name" value="<?=set_value('first_name')?>" placeholder="<?php echo $is_en ? 'e.g. Mohammad / Hasan' : 'যেমন: মুহাম্মদ / মোহাম্মদ'; ?>" autocomplete="off" required />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">
                                <?php echo $is_en ? 'Last Name (in Bengali)' : 'নামের শেষ অংশ (বাংলায়)'; ?><?php echo $last_name['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="last_name" id="online_last_name" value="<?=set_value('last_name')?>" placeholder="<?php echo $is_en ? 'e.g. Ali / Hossain' : 'যেমন: হাসান / আলি'; ?>" autocomplete="off" <?php echo $last_name['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>

                    <!-- Custom Field 1: Full Name in English -->
                    <?php 
                    if ($cf_student_name_en) {
                        $cf_lbl = $is_en ? 'Full Name (in English)' : 'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে)';
                        echo render_single_online_custom_field($cf_student_name_en, 'student', false, 4, $cf_lbl, 'e.g. MD. HASAN ALI', 'fas fa-font');
                    } else { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Full Name (in English)' : 'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে)'; ?> <span class="required text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-font"></i></span>
                                <input type="text" class="form-control" name="custom_fields[student][1]" placeholder="e.g. MD. HASAN ALI" required />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 2: Gender, Blood, DOB (Age auto-calculated in backend) -->
                <div class="row">
                    <?php if ($gender['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Gender' : 'লিঙ্গ'; ?><?php echo ($gender['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''); ?></label>
                            <?php
                                $arrayGender = array(
                                    '' => translate('select'),
                                    'male' => $is_en ? 'Male' : 'ছাত্র (ছেলে)',
                                    'female' => $is_en ? 'Female' : 'ছাত্রী (মেয়ে)'
                                );
                                echo form_dropdown("gender", $arrayGender, set_value('gender'), "class='form-select form-control' id='online_gender' data-plugin-selectTwo " . ($gender['required'] == 1 ? 'required' : ''));
                            ?>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($blood_group['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Blood Group' : 'রক্তের গ্রুপ'; ?><?php echo $blood_group['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <?php
                                $bloodArray = $this->app_lib->getBloodgroup();
                                echo form_dropdown("blood_group", $bloodArray, set_value("blood_group"), "class='form-select form-control populate' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' " . ($blood_group['required'] == 1 ? 'required' : ''));
                            ?>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($birthday['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Date of Birth' : 'জন্ম তারিখ'; ?><?php echo $birthday['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                <input type="text" class="form-control" data-plugin-datepicker name="birthday" readonly value="<?php echo set_value('birthday'); ?>" id="online_birthday" autocomplete="off" <?php echo $birthday['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 3: Birth Registration & Stipend -->
                <div class="row">
                    <!-- Custom Field 4: Birth Registration No -->
                    <?php 
                    if ($cf_birth_reg_no) {
                        $reg_lbl = $is_en ? 'Birth Registration No' : 'জন্ম নিবন্ধন নং';
                        $reg_ph  = $is_en ? '17-digit registration number' : '১৭ ডিজিটের জন্ম নিবন্ধন নম্বর';
                        echo render_single_online_custom_field($cf_birth_reg_no, 'student', false, 4, $reg_lbl, $reg_ph, 'fas fa-certificate');
                    }
                    ?>

                    <!-- Custom Field 5: Issue Date -->
                    <?php 
                    if ($cf_issue_date) {
                        $iss_lbl = $is_en ? 'Registration Issue Date' : 'নিবন্ধন ইস্যুর তারিখ';
                        echo render_single_online_custom_field($cf_issue_date, 'student', false, 4, $iss_lbl, 'YYYY-MM-DD', 'far fa-calendar-alt');
                    }
                    ?>

                    <!-- Custom Field 11: Stipend -->
                    <?php 
                    if ($cf_stipend) {
                        $stip_lbl = $is_en ? 'Stipend Eligible?' : 'উপবৃত্তি প্রাপ্ত কিনা?';
                        echo render_single_online_custom_field($cf_stipend, 'student', false, 4, $stip_lbl, null, 'fas fa-hand-holding-usd');
                    }
                    ?>
                </div>

                <!-- Row 4: Contact & Social Info -->
                <?php
                $student_mobileno = $this->student_fields_model->getOnlineStatus('student_mobile_no', $branchID); 
                $student_email = $this->student_fields_model->getOnlineStatus('student_email', $branchID); 
                $mother_tongue = $this->student_fields_model->getOnlineStatus('mother_tongue', $branchID); 
                $religion = $this->student_fields_model->getOnlineStatus('religion', $branchID); 
                $caste = $this->student_fields_model->getOnlineStatus('caste', $branchID); 
                ?>
                <div class="row">
                    <?php if ($student_mobileno['status']) { ?>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Student Mobile No' : 'শিক্ষার্থীর মোবাইল নম্বর'; ?><?php echo $student_mobileno['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" name="student_mobile_no" id="online_student_mobile_no" class="form-control" value="<?php echo set_value('student_mobile_no'); ?>" placeholder="017xxxxxxxx" autocomplete="off" <?php echo $student_mobileno['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($student_email['status']) { ?>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Student Email' : 'শিক্ষার্থীর ইমেইল'; ?><?php echo $student_email['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                <input type="email" name="student_email" id="online_student_email" class="form-control" value="<?=set_value('student_email')?>" placeholder="student@example.com" autocomplete="off" <?php echo $student_email['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($mother_tongue['status']) { ?>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Mother Tongue' : 'মাতৃভাষা'; ?><?php echo $mother_tongue['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-language"></i></span>
                                <input type="text" class="form-control" name="mother_tongue" value="<?=set_value('mother_tongue', $is_en ? 'Bengali' : 'বাংলা')?>" <?php echo $mother_tongue['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($religion['status']) { ?>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Religion' : 'ধর্ম'; ?><?php echo $religion['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-pray"></i></span>
                                <input type="text" class="form-control" name="religion" value="<?=set_value('religion', $is_en ? 'Islam' : 'ইসলাম')?>" <?php echo $religion['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 5: Addresses -->
                <?php
                $current_address = $this->student_fields_model->getOnlineStatus('present_address', $branchID);
                $permanent_address = $this->student_fields_model->getOnlineStatus('permanent_address', $branchID);
                ?>
                <div class="row">
                    <?php if ($current_address['status']) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Present Address' : 'বর্তমান ঠিকানা'; ?><?php echo $current_address['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <textarea class="form-control" name="present_address" rows="2" placeholder="<?php echo $is_en ? 'Village/Street, Post, Upazila, District' : 'গ্রাম/মহল্লা, ডাকঘর, উপজেলা, জেলা'; ?>" <?php echo $current_address['required'] == 1 ? 'required' : ''; ?>><?php echo set_value('present_address'); ?></textarea>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($permanent_address['status']) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Permanent Address' : 'স্থায়ী ঠিকানা'; ?><?php echo $permanent_address['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <textarea class="form-control" name="permanent_address" rows="2" placeholder="<?php echo $is_en ? 'Village/Street, Post, Upazila, District' : 'গ্রাম/মহল্লা, ডাকঘর, উপজেলা, জেলা'; ?>" <?php echo $permanent_address['required'] == 1 ? 'required' : ''; ?>><?php echo set_value('permanent_address'); ?></textarea>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 6: City & State -->
                <?php
                $city = $this->student_fields_model->getOnlineStatus('city', $branchID);
                $state = $this->student_fields_model->getOnlineStatus('state', $branchID);
                ?>
                <div class="row">
                    <?php if ($city['status']) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'City / Upazila' : 'শহর / উপজেলা'; ?><?php echo $city['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" name="city" value="<?=set_value('city', $is_en ? 'Subarnachar' : 'সুবর্ণচর')?>" <?php echo $city['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($state['status']) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'State / District' : 'জেলা / বিভাগ'; ?><?php echo $state['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map"></i></span>
                                <input type="text" class="form-control" name="state" value="<?=set_value('state', $is_en ? 'Noakhali' : 'নোয়াখালী')?>" <?php echo $state['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 7: Student Photo -->
                <?php
                $student_photo = $this->student_fields_model->getOnlineStatus('student_photo', $branchID); 
                if ($student_photo['status']) {
                ?>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Student Photo' : 'শিক্ষার্থীর ছবি'; ?><?php echo $student_photo['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="custom-file">
                                <input type="file" name="student_photo" class="form-control" id="photoFile" accept=".jpg,.jpeg,.png,.bmp" <?php echo $student_photo['required'] == 1 ? 'required' : ''; ?>>
                            </div>
                            <small class="text-muted"><?php echo $is_en ? 'Allowed formats: JPG, PNG, JPEG. Max size 2MB.' : 'অনুমোদিত ফরম্যাট: JPG, PNG, JPEG। সর্বোচ্চ সাইজ: ২ মেগাবাইট।'; ?></small>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Step 2 Navigation Footer -->
                <div class="online-nav-footer">
                    <div>
                        <button type="button" class="btn btn-online-prev" onclick="onlinePrevStep(2)">
                            <i class="fas fa-arrow-left me-1"></i> <?php echo $is_en ? 'Previous: Academic Details' : 'পূর্ববর্তী: একাডেমিক তথ্য'; ?>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-online-next" onclick="onlineNextStep(2)">
                            <?php echo $is_en ? 'Next: Parents & Guardian' : 'পরবর্তী ধাপ: অভিভাবকের তথ্য'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 3: PARENTS & GUARDIAN DETAILS         -->
            <!-- ========================================== -->
            <div class="wizard-step-pane" id="online-step-3" style="display: none;">
                <div class="online-pane-title">
                    <h5><i class="fas fa-user-friends"></i> <?php echo $is_en ? 'Step 3: Parents & Guardian Details' : 'ধাপ ৩: পিতা-মাতা ও অভিভাবকের বিবরণ'; ?></h5>
                    <p><?php echo $is_en ? 'Provide parents and primary guardian identification and contact details.' : 'পিতা-মাতার নাম (বাংলা ও ইংরেজি) এবং অভিভাবকের যোগাযোগের সঠিক তথ্য প্রদান করুন।'; ?></p>
                </div>

                <?php 
                $guardian_name = $this->student_fields_model->getOnlineStatus('guardian_name', $branchID);
                $guardian_relation = $this->student_fields_model->getOnlineStatus('guardian_relation', $branchID);
                $father_name = $this->student_fields_model->getOnlineStatus('father_name', $branchID);
                $mother_name = $this->student_fields_model->getOnlineStatus('mother_name', $branchID);
                $guardian_occupation = $this->student_fields_model->getOnlineStatus('guardian_occupation', $branchID);
                $guardian_income = $this->student_fields_model->getOnlineStatus('guardian_income', $branchID);
                $guardian_education = $this->student_fields_model->getOnlineStatus('guardian_education', $branchID);
                $guardian_email = $this->student_fields_model->getOnlineStatus('guardian_email', $branchID);
                $guardian_mobile_no = $this->student_fields_model->getOnlineStatus('guardian_mobile_no', $branchID);
                $guardian_address = $this->student_fields_model->getOnlineStatus('guardian_address', $branchID);
                $guardian_city = $this->student_fields_model->getOnlineStatus('guardian_city', $branchID);
                $guardian_state = $this->student_fields_model->getOnlineStatus('guardian_state', $branchID);
                $guardian_photo = $this->student_fields_model->getOnlineStatus('guardian_photo', $branchID);
                ?>

                <!-- Row 1: Father's Details (Name BN, Name EN, Mobile) -->
                <div class="row">
                    <?php if ($father_name['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">
                                <?php echo $is_en ? 'Father Name (in Bengali)' : 'পিতার নাম (বাংলায়)'; ?><?php echo $father_name['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-male"></i></span>
                                <input type="text" name="father_name" id="online_father_name" class="form-control" value="<?php echo set_value('father_name'); ?>" placeholder="<?php echo $is_en ? 'e.g. Md. Rafiqul Islam' : 'যেমন: মো: রফিকুল ইসলাম'; ?>" autocomplete="off" <?php echo $father_name['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"><?php echo form_error('father_name'); ?></span>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Custom Field 2: Father Name in English -->
                    <?php 
                    if ($cf_father_name_en) {
                        $f_lbl = $is_en ? 'Father Name (in English)' : 'পিতার নাম (ইংরেজিতে)';
                        echo render_single_online_custom_field($cf_father_name_en, 'student', false, 4, $f_lbl, 'e.g. MD. RAFIQUL ISLAM', 'fas fa-font');
                    } else { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Father Name (in English)' : 'পিতার নাম (ইংরেজিতে)'; ?> <span class="required text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-font"></i></span>
                                <input type="text" class="form-control" name="custom_fields[student][2]" placeholder="e.g. MD. RAFIQUL ISLAM" required />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? "Father's Mobile No" : 'পিতার মোবাইল নং'; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" name="father_mobile_no" id="online_father_mobile_no" value="<?php echo set_value('father_mobile_no'); ?>" placeholder="017xxxxxxxx" autocomplete="off" />
                            </div>
                            <span class="error text-danger"><?php echo form_error('father_mobile_no'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Mother's Details (Name BN, Name EN, Mobile) -->
                <div class="row">
                    <?php if ($mother_name['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">
                                <?php echo $is_en ? 'Mother Name (in Bengali)' : 'মাতার নাম (বাংলায়)'; ?><?php echo $mother_name['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-female"></i></span>
                                <input type="text" name="mother_name" id="online_mother_name" class="form-control" value="<?php echo set_value('mother_name'); ?>" placeholder="<?php echo $is_en ? 'e.g. Fatema Begum' : 'যেমন: ফাতেমা বেগম'; ?>" autocomplete="off" <?php echo $mother_name['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"><?php echo form_error('mother_name'); ?></span>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Custom Field 3: Mother Name in English -->
                    <?php 
                    if ($cf_mother_name_en) {
                        $m_lbl = $is_en ? 'Mother Name (in English)' : 'মাতার নাম (ইংরেজিতে)';
                        echo render_single_online_custom_field($cf_mother_name_en, 'student', false, 4, $m_lbl, 'e.g. FATEMA BEGUM', 'fas fa-font');
                    } else { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Mother Name (in English)' : 'মাতার নাম (ইংরেজিতে)'; ?> <span class="required text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-font"></i></span>
                                <input type="text" class="form-control" name="custom_fields[student][3]" placeholder="e.g. FATEMA BEGUM" required />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? "Mother's Mobile No" : 'মাতার মোবাইল নং'; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" name="mother_mobile_no" id="online_mother_mobile_no" value="<?php echo set_value('mother_mobile_no'); ?>" placeholder="018xxxxxxxx" autocomplete="off" />
                            </div>
                            <span class="error text-danger"><?php echo form_error('mother_mobile_no'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Guardian Name, Relation, Occupation -->
                <div class="row">
                    <?php if ($guardian_name['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Guardian Name' : 'অভিভাবকের নাম'; ?><?php echo $guardian_name['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                <input type="text" class="form-control" name="guardian_name" id="online_guardian_name" value="<?php echo set_value('guardian_name'); ?>" placeholder="<?php echo $is_en ? 'Legal Guardian Name' : 'মূল অভিভাবকের নাম'; ?>" autocomplete="off" <?php echo $guardian_name['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($guardian_relation['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Relation with Student' : 'শিক্ষার্থীর সাথে সম্পর্ক'; ?><?php echo $guardian_relation['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-hands-helping"></i></span>
                                <input type="text" name="guardian_relation" id="online_guardian_relation" class="form-control" value="<?php echo set_value('guardian_relation', $is_en ? 'Father' : 'পিতা'); ?>" placeholder="<?php echo $is_en ? 'e.g. Father / Mother / Brother' : 'যেমন: পিতা / মাতা / ভাই / চাচা'; ?>" autocomplete="off" <?php echo $guardian_relation['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($guardian_occupation['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Occupation' : 'পেশা'; ?><?php echo $guardian_occupation['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                <input type="text" class="form-control" name="guardian_occupation" value="<?php echo set_value('guardian_occupation'); ?>" placeholder="<?php echo $is_en ? 'e.g. Teacher / Businessman' : 'যেমন: শিক্ষক / ব্যবসায়ী / প্রবাসী'; ?>" autocomplete="off" <?php echo $guardian_occupation['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 3: Income, Education, Guardian Mobile -->
                <div class="row">
                    <?php if ($guardian_income['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Annual Income' : 'বাৎসরিক আয়'; ?><?php echo $guardian_income['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                <input class="form-control" name="guardian_income" value="<?php echo set_value('guardian_income'); ?>" type="text" placeholder="<?php echo $is_en ? 'e.g. 3,50,000' : 'যেমন: ৩,৫০,০০০'; ?>" autocomplete="off" <?php echo $guardian_income['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($guardian_education['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Education' : 'শিক্ষাগত যোগ্যতা'; ?><?php echo $guardian_education['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                <input type="text" class="form-control" name="guardian_education" value="<?php echo set_value('guardian_education'); ?>" placeholder="<?php echo $is_en ? 'e.g. Graduate / Masters' : 'যেমন: অনার্স / মাস্টার্স / এসএসসি'; ?>" autocomplete="off" <?php echo $guardian_education['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($guardian_mobile_no['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Guardian Mobile No' : 'অভিভাবকের মোবাইল নং'; ?><?php echo $guardian_mobile_no['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone-volume"></i></span>
                                <input type="text" class="form-control" name="guardian_mobile_no" id="online_guardian_mobile_no" value="<?php echo set_value('guardian_mobile_no'); ?>" placeholder="017xxxxxxxx" autocomplete="off" <?php echo $guardian_mobile_no['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 4: Guardian Email, City, State -->
                <div class="row">
                    <?php if ($guardian_email['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Guardian Email' : 'অভিভাবকের ইমেইল'; ?><?php echo $guardian_email['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                <input type="email" class="form-control" name="guardian_email" id="online_guardian_email" value="<?php echo set_value('guardian_email'); ?>" placeholder="guardian@example.com" autocomplete="off" <?php echo $guardian_email['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($guardian_city['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Guardian City / Upazila' : 'শহর / উপজেলা'; ?><?php echo $guardian_city['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input class="form-control" name="guardian_city" value="<?php echo set_value('guardian_city', $is_en ? 'Subarnachar' : 'সুবর্ণচর'); ?>" type="text" <?php echo $guardian_city['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($guardian_state['status']) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Guardian State / District' : 'জেলা / বিভাগ'; ?><?php echo $guardian_state['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map"></i></span>
                                <input class="form-control" name="guardian_state" value="<?php echo set_value('guardian_state', $is_en ? 'Noakhali' : 'নোয়াখালী'); ?>" type="text" <?php echo $guardian_state['required'] == 1 ? 'required' : ''; ?> />
                            </div>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Row 5: Guardian Address -->
                <?php if ($guardian_address['status']) { ?>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Guardian Address' : 'অভিভাবকের বিস্তারিত ঠিকানা'; ?><?php echo $guardian_address['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <textarea class="form-control" name="guardian_address" rows="2" placeholder="<?php echo $is_en ? 'Enter Detailed Address' : 'গ্রাম/মহল্লা, ডাকঘর, উপজেলা, জেলা'; ?>" <?php echo $guardian_address['required'] == 1 ? 'required' : ''; ?>><?php echo set_value('guardian_address'); ?></textarea>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Row 6: Father, Mother, Guardian Photos (Separate) -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><i class="fas fa-camera text-success"></i> <?php echo $is_en ? "Father's Photo" : 'পিতার ছবি'; ?></label>
                            <input type="file" class="form-control" name="father_photo" id="fatherPhoto" accept=".jpg,.jpeg,.png,.bmp" />
                            <small class="text-muted"><?php echo $is_en ? 'Allowed: JPG, PNG, JPEG. Max 2MB.' : 'অনুমোদিত: JPG, PNG, JPEG। সর্বোচ্চ ২MB'; ?></small>
                            <span class="error text-danger"><?php echo form_error('father_photo'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><i class="fas fa-camera text-success"></i> <?php echo $is_en ? "Mother's Photo" : 'মাতার ছবি'; ?></label>
                            <input type="file" class="form-control" name="mother_photo" id="motherPhoto" accept=".jpg,.jpeg,.png,.bmp" />
                            <small class="text-muted"><?php echo $is_en ? 'Allowed: JPG, PNG, JPEG. Max 2MB.' : 'অনুমোদিত: JPG, PNG, JPEG। সর্বোচ্চ ২MB'; ?></small>
                            <span class="error text-danger"><?php echo form_error('mother_photo'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><i class="fas fa-camera text-success"></i> <?php echo $is_en ? 'Guardian Photo' : 'অভিভাবকের ছবি'; ?><?php echo (isset($guardian_photo['required']) && $guardian_photo['required'] == 1) ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <input type="file" class="form-control" name="guardian_photo" id="guardianPhoto" accept=".jpg,.jpeg,.png,.bmp" <?php echo (isset($guardian_photo['required']) && $guardian_photo['required'] == 1) ? 'required' : ''; ?> />
                            <small class="text-muted"><?php echo $is_en ? 'Allowed: JPG, PNG, JPEG. Max 2MB.' : 'অনুমোদিত: JPG, PNG, JPEG। সর্বোচ্চ ২MB'; ?></small>
                            <span class="error text-danger"><?php echo form_error('guardian_photo'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Step 3 Navigation Footer -->
                <div class="online-nav-footer">
                    <div>
                        <button type="button" class="btn btn-online-prev" onclick="onlinePrevStep(3)">
                            <i class="fas fa-arrow-left me-1"></i> <?php echo $is_en ? 'Previous: Student Details' : 'পূর্ববর্তী: শিক্ষার্থীর তথ্য'; ?>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-online-next" onclick="onlineNextStep(3)">
                            <?php echo $is_en ? 'Next: Review & Submit' : 'পরবর্তী ধাপ: সুবিধা ও সাবমিট'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 4: REVIEW & FINAL SUBMISSION          -->
            <!-- ========================================== -->
            <div class="wizard-step-pane" id="online-step-4" style="display: none;">
                <div class="online-pane-title">
                    <h5><i class="fas fa-tasks"></i> <?php echo $is_en ? 'Step 4: Previous School, Review & Submit' : 'ধাপ ৪: পূর্ববর্তী বিদ্যালয়, কাগজপত্র ও চূড়ান্তকরণ'; ?></h5>
                    <p><?php echo $is_en ? 'Review all entered details, upload supporting documents and confirm submission.' : 'আবেদনের তথ্যাবলী পর্যালোচনা করুন এবং প্রয়োজনীয় কাগজপত্র আপলোড করে আবেদন সম্পন্ন করুন।'; ?></p>
                </div>

                <!-- Previous School Details (if enabled) -->
                <?php
                $previous_school_details = $this->student_fields_model->getOnlineStatus('previous_school_details', $branchID); 
                if ($previous_school_details['status']) {
                ?>
                <div class="card mb-4 border" style="border-radius: 8px;">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-history text-success me-2"></i> <?php echo $is_en ? 'Previous School Details' : 'পূর্ববর্তী বিদ্যালয়ের তথ্য'; ?></h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold"><?php echo $is_en ? 'Previous School Name' : 'পূর্ববর্তী বিদ্যালয়ের নাম'; ?><?php echo $previous_school_details['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                                    <input type="text" class="form-control" name="school_name" value="<?php echo set_value('school_name'); ?>" placeholder="<?php echo $is_en ? 'Name of last attended school' : 'সর্বশেষ অধ্যয়নরত বিদ্যালয়ের নাম'; ?>" <?php echo $previous_school_details['required'] == 1 ? 'required' : ''; ?> />
                                    <span class="error text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold"><?php echo $is_en ? 'Passed Class / Qualification' : 'উত্তীর্ণ শ্রেণি / যোগ্যতা'; ?><?php echo $previous_school_details['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                                    <input type="text" class="form-control" name="qualification" value="<?php echo set_value('qualification'); ?>" placeholder="<?php echo $is_en ? 'e.g. Class 4 Passed' : 'যেমন: চতুর্থ শ্রেণি উত্তীর্ণ'; ?>" <?php echo $previous_school_details['required'] == 1 ? 'required' : ''; ?> />
                                    <span class="error text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label fw-semibold"><?php echo $is_en ? 'Remarks' : 'মন্তব্য'; ?></label>
                                    <textarea name="previous_remarks" rows="2" class="form-control" placeholder="<?php echo $is_en ? 'Any additional remarks or achievements' : 'অতিরিক্ত কোনো তথ্য বা কৃতিত্ব (যদি থাকে)'; ?>"><?php echo set_value('previous_remarks'); ?></textarea>
                                    <span class="error text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Other Online Custom Fields -->
                <div class="row" id="customFields">
                    <?php echo render_online_custom_fields_excluding('student', $branchID, false, 4, $placed_cf_ids); ?>
                </div>

                <!-- Upload Documents (if enabled) -->
                <?php 
                $upload_documents = $this->student_fields_model->getOnlineStatus('upload_documents', $branchID); 
                if ($upload_documents['status']) { 
                ?>
                <div class="card mb-4 border" style="border-radius: 8px;">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 fw-bold"><i class="far fa-file-archive text-success me-2"></i> <?php echo $is_en ? 'Upload Documents' : 'প্রয়োজনীয় কাগজপত্র আপলোড'; ?></h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold"><?php echo $is_en ? 'Supporting Documents (Birth Certificate / Marksheet)' : 'প্রয়োজনীয় কাগজপত্র (জন্ম নিবন্ধন সনদ / প্রশংসাপত্র / মার্কশিট)'; ?><?php echo $upload_documents['required'] == 1 ? ' <span class="required text-danger">*</span>' : ''; ?></label>
                            <input type="file" name="upload_documents" class="form-control" id="documentFile" <?php echo $upload_documents['required'] == 1 ? 'required' : ''; ?> />
                            <small class="text-muted"><?php echo $is_en ? 'Allowed formats: PDF, JPG, PNG, ZIP. Max size 5MB.' : 'অনুমোদিত ফরম্যাট: PDF, JPG, PNG, ZIP। সর্বোচ্চ সাইজ: ৫ মেগাবাইট।'; ?></small>
                            <span class="error text-danger"></span>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Visual Summary Review Card -->
                <div class="online-summary-box">
                    <div class="online-summary-title">
                        <i class="fas fa-clipboard-check"></i> <?php echo $is_en ? 'Application Details Review' : 'আবেদনের তথ্যাবলী পর্যালোচনা ও নিশ্চিতকরণ'; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="online-summary-table">
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Class:' : 'শ্রেণি:'; ?></td>
                                    <td class="val-col" id="online_sum_class">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Section:' : 'শাখা:'; ?></td>
                                    <td class="val-col" id="online_sum_section">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Category:' : 'ক্যাটাগরি:'; ?></td>
                                    <td class="val-col" id="online_sum_category">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="online-summary-table">
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Name (Bengali):' : 'নাম (বাংলায়):'; ?></td>
                                    <td class="val-col" id="online_sum_name_bn">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Name (English):' : 'নাম (ইংরেজিতে):'; ?></td>
                                    <td class="val-col" id="online_sum_name_en">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Gender / DOB:' : 'লিঙ্গ ও জন্ম তারিখ:'; ?></td>
                                    <td class="val-col" id="online_sum_gender_dob">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Mobile No:' : 'মোবাইল নং:'; ?></td>
                                    <td class="val-col" id="online_sum_mobile">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="online-summary-table">
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Father Name:' : 'পিতার নাম:'; ?></td>
                                    <td class="val-col" id="online_sum_father">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Mother Name:' : 'মাতার নাম:'; ?></td>
                                    <td class="val-col" id="online_sum_mother">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Guardian Name:' : 'অভিভাবকের নাম:'; ?></td>
                                    <td class="val-col" id="online_sum_guardian">-</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><?php echo $is_en ? 'Guardian Mobile:' : 'অভিভাবকের মোবাইল:'; ?></td>
                                    <td class="val-col" id="online_sum_grd_mobile">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Captcha (if enabled) -->
                <?php if ($cms_setting['captcha_status'] == 'enable'): ?>
                <div class="form-group mb-3">
                    <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
                    <span class="error text-danger"></span>
                </div>
                <?php endif; ?>

                <!-- Terms & Conditions (if present) -->
                <?php if (!empty($page_data['terms_conditions_title'])) { ?>
                <div class="accordion mb-3" id="accordion-faqs">
                    <div class="card border">
                        <div class="card-header bg-light" id="faq1">
                            <h6 class="mb-0">
                                <a class="text-decoration-none text-dark fw-bold" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="cursor: pointer;">
                                    <i class="fas fa-chevron-down me-1 text-success"></i> <?php echo $page_data['terms_conditions_title']; ?>
                                </a>
                            </h6>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="faq1" data-bs-parent="#accordion-faqs">
                            <div class="card-body">
                                <?php echo $page_data['terms_conditions_description'] ?>
                            </div>
                        </div>                 
                    </div>
                </div>
                <?php } ?>

                <!-- Confirmation Checkbox -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="onlineConfirmData" checked required>
                    <label class="form-check-label fw-semibold" for="onlineConfirmData">
                        <?php echo $is_en ? 'I hereby declare that all information provided above is authentic, accurate and complete.' : 'আমি নিশ্চয়তা দিচ্ছি যে উপরে প্রদত্ত সকল তথ্য সতর্কতার সাথে যাচাই করা হয়েছে এবং তা সম্পূর্ণ নির্ভুল।'; ?>
                    </label>
                </div>

                <!-- Step 4 Navigation Footer -->
                <div class="online-nav-footer">
                    <div>
                        <button type="button" class="btn btn-online-prev" onclick="onlinePrevStep(4)">
                            <i class="fas fa-arrow-left me-1"></i> <?php echo $is_en ? 'Previous: Parents & Guardian' : 'পূর্ববর্তী: অভিভাবকের তথ্য'; ?>
                        </button>
                    </div>
                    <div>
                        <button type="submit" id="btnOnlineSubmit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> <?php echo $is_en ? 'Submitting...' : 'জমা হচ্ছে...'; ?>" class="btn btn-online-submit">
                            <i class="fas fa-paper-plane me-2"></i> <?php echo $is_en ? 'Submit Admission Application' : 'ভর্তির আবেদন সম্পন্ন করুন ও জমা দিন'; ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal: Check Admission Status -->
<div class="modal fade" id="admissionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <?php echo form_open('home/checkAdmissionStatus', array('class' => 'frm-submit-data')); ?>
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="exampleModalLabel">
                    <i class="fas fa-search me-2"></i> <?php echo $is_en ? 'Check Admission Status' : 'ভর্তির আবেদনের অবস্থা যাচাই'; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="form-label fw-semibold"><?php echo $is_en ? 'Enter Your Reference Number' : 'আপনার রেফারেন্স নম্বরটি লিখুন'; ?> <span class="required text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        <input type="text" class="form-control" name="refno" id="refno" placeholder="<?php echo $is_en ? 'e.g. 12345678' : 'যেমন: ১২৩৪৫৬৭৮'; ?>" autocomplete="off" required>
                    </div>
                    <span class="error text-danger"></span>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $is_en ? 'Close' : 'বন্ধ করুন'; ?></button>
                <button type="submit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> <?php echo $is_en ? 'Checking...' : 'যাচাই হচ্ছে...'; ?>" class="btn btn-success">
                    <i class="fas fa-check me-1"></i> <?php echo $is_en ? 'Check Now' : 'এখনই দেখুন'; ?>
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Frontend Wizard JavaScript Controller -->
<script type="text/javascript">
var onlineCurrentStep = 1;
var onlineTotalSteps = 4;
var isEnglishLang = <?php echo $is_en ? 'true' : 'false'; ?>;

function updateOnlineWizardUI(step) {
    onlineCurrentStep = step;

    // Show target pane, hide others
    for (var i = 1; i <= onlineTotalSteps; i++) {
        if (i === step) {
            $('#online-step-' + i).fadeIn(250);
        } else {
            $('#online-step-' + i).hide();
        }
    }

    // Update stepper badges
    $('#onlineStepList .online-step-item').each(function() {
        var itemStep = parseInt($(this).data('step'), 10);
        $(this).removeClass('active completed');
        if (itemStep < step) {
            $(this).addClass('completed');
            $(this).find('.online-step-badge').html('<i class="fas fa-check"></i>');
        } else if (itemStep === step) {
            $(this).addClass('active');
            var icon = (step === 1) ? 'fa-school' : (step === 2 ? 'fa-user-graduate' : (step === 3 ? 'fa-user-friends' : 'fa-check-circle'));
            $(this).find('.online-step-badge').html('<i class="fas ' + icon + '"></i>');
        } else {
            var icon = (itemStep === 1) ? 'fa-school' : (itemStep === 2 ? 'fa-user-graduate' : (itemStep === 3 ? 'fa-user-friends' : 'fa-check-circle'));
            $(this).find('.online-step-badge').html('<i class="fas ' + icon + '"></i>');
        }
    });

    // Update progress bar width
    var progressPercent = (step / onlineTotalSteps) * 100;
    $('#onlineProgressFill').css('width', progressPercent + '%');

    // Populate Step 4 Summary Review Box
    if (step === 4) {
        populateOnlineSummary();
    }

    // Fix select2 dimensions in newly visible pane
    $(window).trigger('resize');
    if ($.fn.select2) {
        $('#online-step-' + step + ' [data-plugin-selecttwo]').each(function() {
            var $el = $(this);
            $el.css('width', '100%');
        });
    }

    // Smooth scroll to top of wizard
    $('html, body').animate({
        scrollTop: $('.online-wizard-card').offset().top - 80
    }, 350);
}

function validateOnlineStep(step) {
    var $pane = $('#online-step-' + step);
    var isValid = true;
    var $firstInvalid = null;

    $pane.find('.field-invalid-highlight').removeClass('field-invalid-highlight');

    // Check required inputs
    $pane.find('input[required], select[required], textarea[required]').each(function() {
        var $field = $(this);
        if (!$field.is(':visible') && $field.closest('.form-group').is(':hidden')) {
            return;
        }

        var val = $.trim($field.val());
        if (!val || val === '') {
            isValid = false;
            $field.addClass('field-invalid-highlight');
            var errMsg = isEnglishLang ? 'This field is required.' : 'এই তথ্যটি প্রদান করা বাধ্যতামূলক।';
            $field.closest('.form-group').find('.error').text(errMsg);
            if (!$firstInvalid) {
                $firstInvalid = $field;
            }
        } else {
            $field.closest('.form-group').find('.error').text('');
        }
    });

    if (!isValid && $firstInvalid) {
        $('html, body').animate({
            scrollTop: $firstInvalid.offset().top - 120
        }, 350);
        $firstInvalid.focus();
        
        var alertTitle = isEnglishLang ? "Incomplete Form" : "তথ্য অসম্পূর্ণ!";
        var alertText = isEnglishLang ? "Please fill in all mandatory fields marked with an asterisk (*) in this step." : "অনুগ্রহ করে এই ধাপের লাল চিহ্নিত বাধ্যতামূলক ফিল্ডগুলো পূরণ করুন।";
        var btnText = isEnglishLang ? "OK" : "ঠিক আছে";

        if (typeof swal === 'function') {
            swal({
                title: alertTitle,
                text: alertText,
                icon: "warning",
                button: btnText
            });
        } else {
            alert(alertText);
        }
    }

    return isValid;
}

function onlineNextStep(currentStep) {
    if (validateOnlineStep(currentStep)) {
        updateOnlineWizardUI(currentStep + 1);
    }
}

function onlinePrevStep(currentStep) {
    if (currentStep > 1) {
        updateOnlineWizardUI(currentStep - 1);
    }
}

function calculateOnlineAge(dobStr, isEn) {
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
    if (isEn) {
        var resEn = [];
        if (years > 0) resEn.push(years + ' Year' + (years > 1 ? 's' : ''));
        if (months > 0) resEn.push(months + ' Month' + (months > 1 ? 's' : ''));
        if (resEn.length === 0 && days > 0) resEn.push(days + ' Day' + (days > 1 ? 's' : ''));
        return resEn.length > 0 ? resEn.join(' ') : '0 Year';
    } else {
        var enDigits = ['0','1','2','3','4','5','6','7','8','9'];
        var bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        var toBn = function(num) {
            return String(num).split('').map(function(ch) {
                var idx = enDigits.indexOf(ch);
                return idx !== -1 ? bnDigits[idx] : ch;
            }).join('');
        };
        var resBn = [];
        if (years > 0) resBn.push(toBn(years) + ' বছর');
        if (months > 0) resBn.push(toBn(months) + ' মাস');
        if (resBn.length === 0 && days > 0) resBn.push(toBn(days) + ' দিন');
        return resBn.length > 0 ? resBn.join(' ') : '০ বছর';
    }
}

function populateOnlineSummary() {
    var classText = $('#online_class_id option:selected').text();
    var sectionText = $('#online_section_id option:selected').text() || '-';
    var catText = $('#online_category_id option:selected').text() || '-';

    var firstName = $('#online_first_name').val() || '';
    var lastName = $('#online_last_name').val() || '';
    var fullNameBn = $.trim(firstName + ' ' + lastName) || '-';

    var fullNameEn = $('input[name="custom_fields[student][1]"]').val() || '-';
    var gender = $('#online_gender option:selected').text() || '-';
    var dob = $('#online_birthday').val() || '-';
    var mobile = $('#online_student_mobile_no').val() || '-';

    var fatherName = $('#online_father_name').val() || '-';
    var motherName = $('#online_mother_name').val() || '-';
    var grdName = $('#online_guardian_name').val() || '-';
    var grdMobile = $('#online_guardian_mobile_no').val() || '-';

    var isEn = <?php echo $is_en ? 'true' : 'false'; ?>;
    var dobDisplay = dob;
    if (dob && dob !== '-') {
        var ageText = calculateOnlineAge(dob, isEn);
        if (ageText) {
            dobDisplay += (isEn ? ' (Age: ' : ' (বয়স: ') + ageText + ')';
        }
    }

    $('#online_sum_class').text(classText);
    $('#online_sum_section').text(sectionText);
    $('#online_sum_category').text(catText);

    $('#online_sum_name_bn').text(fullNameBn);
    $('#online_sum_name_en').text(fullNameEn);
    $('#online_sum_gender_dob').text(gender + ' / ' + dobDisplay);
    $('#online_sum_mobile').text(mobile);

    var fatherMobile = $('#online_father_mobile_no').val();
    var motherMobile = $('#online_mother_mobile_no').val();
    $('#online_sum_father').text(fatherName + (fatherMobile ? ' (' + fatherMobile + ')' : ''));
    $('#online_sum_mother').text(motherName + (motherMobile ? ' (' + motherMobile + ')' : ''));
    $('#online_sum_guardian').text(grdName);
    $('#online_sum_grd_mobile').text(grdMobile);
}

$(document).ready(function() {
    // Direct clicking on completed step pills
    // Change event for class dropdown
    $('#online_class_id').on('change', function() {
        if (typeof getSectionByClass === 'function') {
            getSectionByClass($(this).val());
        }
    });

    $('#onlineStepList .online-step-item').on('click', function() {
        var targetStep = parseInt($(this).data('step'), 10);
        if (targetStep < onlineCurrentStep) {
            updateOnlineWizardUI(targetStep);
        } else if (targetStep > onlineCurrentStep) {
            if (validateOnlineStep(onlineCurrentStep)) {
                updateOnlineWizardUI(targetStep);
            }
        }
    });

    // Remove invalid highlight on input
    $(document).on('input change', '.field-invalid-highlight', function() {
        $(this).removeClass('field-invalid-highlight');
        $(this).closest('.form-group').find('.error').text('');
    });

    // Confirmation checkbox check
    $('#onlineAdmissionForm').on('submit', function(e) {
        if (!$('#onlineConfirmData').is(':checked')) {
            e.preventDefault();
            var alertMsg = isEnglishLang ? 'Please check the declaration box before submitting.' : 'আবেদন জমা দিতে নিচের নিশ্চয়তা চেকবাক্সে টিক দিন।';
            alert(alertMsg);
            return false;
        }
    });

    // Auto-jump to the step containing server validation errors
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url && settings.url.indexOf('admission') !== -1) {
            try {
                var res = JSON.parse(xhr.responseText);
                if (res.status === 'fail' && res.error) {
                    setTimeout(function() {
                        for (var step = 1; step <= onlineTotalSteps; step++) {
                            var $pane = $('#online-step-' + step);
                            var $errors = $pane.find('.error:not(:empty), .has-error');
                            if ($errors.length > 0) {
                                updateOnlineWizardUI(step);
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

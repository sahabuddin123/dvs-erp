<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
$is_en = ($current_lang === 'en');

$default_page_title = $is_en ? 'Examination Results' : 'পরীক্ষার ফলাফল';
$title = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'page_title', $default_page_title) 
    : (!empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title);

$default_desc = $is_en 
    ? 'Check and download academic marksheet and examination results for Char Hasan Islamia Dakhil Madrasah.' 
    : 'চর হাসান ইসলামিয়া দাখিল মাদরাসার শিক্ষার্থীদের বিভিন্ন সাময়িক, বার্ষিক ও নির্বাচনী পরীক্ষার ফলাফল ও বিস্তারিত নম্বরপত্র দেখুন।';

$description = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'description', $default_desc) 
    : (!empty($page_data['description']) && strpos($page_data['description'], 'Anim pariatur') === false ? $page_data['description'] : $default_desc);
?>
<script type="text/javascript" src="<?php echo base_url('assets/js/certificate.js?v=' . version_combine()) ?>"></script>

<style type="text/css">
/* Results Page Theme */
.results-page {
    color: #1e293b;
}

.page-header-icon {
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.85);
}

/* Tab Switcher */
.result-tab-wrapper {
    max-width: 960px;
    margin: -25px auto 30px auto;
    position: relative;
    z-index: 5;
}

.result-tab-nav {
    background: #ffffff;
    border-radius: 50px;
    padding: 6px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    display: inline-flex;
    border: 1px solid #e2e8f0;
}

.result-tab-btn {
    border-radius: 50px !important;
    padding: 10px 26px !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    border: none !important;
    color: #475569 !important;
    background: transparent !important;
    transition: all 0.3s ease !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
}

.result-tab-btn:hover {
    color: var(--primary-color, #007F3D) !important;
    background: #f8fafc !important;
}

.result-tab-btn.active {
    background: var(--primary-color, #007F3D) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(0, 127, 61, 0.35) !important;
}

.result-tab-btn .tab-badge {
    font-size: 0.72rem;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 700;
}

/* Search Card Container */
.results-search-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 35px;
    max-width: 960px;
    margin-left: auto;
    margin-right: auto;
}

.results-card-header {
    background: #ffffff;
    padding: 22px 28px;
    border-bottom: 1px solid #f1f5f9;
    border-left: 6px solid var(--primary-color, #007F3D);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.results-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.25rem;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

.results-card-header h5 i {
    color: var(--primary-color, #007F3D);
}

.results-form-body {
    padding: 28px 28px 24px 28px;
}

.results-form-label {
    font-weight: 600;
    font-size: 0.88rem;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.results-input-group {
    position: relative;
}

.results-input-group .input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1rem;
    z-index: 4;
    pointer-events: none;
}

.results-input-group .form-control,
.results-input-group select.form-control {
    padding-left: 42px !important;
    height: 48px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 0.93rem !important;
    transition: all 0.2s ease !important;
    background-color: #f8fafc !important;
}

.results-input-group .form-control:focus,
.results-input-group select.form-control:focus {
    border-color: var(--primary-color, #007F3D) !important;
    background-color: #ffffff !important;
    box-shadow: 0 0 0 4px rgba(0, 127, 61, 0.12) !important;
}

.results-btn-submit {
    background: var(--primary-color, #007F3D) !important;
    border-color: var(--primary-color, #007F3D) !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    padding: 13px 34px !important;
    font-weight: 700 !important;
    font-size: 1.02rem !important;
    box-shadow: 0 6px 18px rgba(0, 127, 61, 0.28) !important;
    transition: all 0.25s ease !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.results-btn-submit:hover {
    filter: brightness(1.08);
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(0, 127, 61, 0.36) !important;
    color: #ffffff !important;
}

/* Result Output Box */
#card_holder {
    max-width: 1060px;
    margin: 0 auto 40px auto;
}

.result-card-container {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
    padding: 24px;
    overflow: hidden;
}

.result-action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 18px;
    margin-bottom: 22px;
    border-bottom: 2px dashed #e2e8f0;
    flex-wrap: wrap;
    gap: 12px;
}

.btn-result-print {
    background: #0f172a !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 10px 22px !important;
    font-weight: 600 !important;
    font-size: 0.92rem !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.btn-result-print:hover {
    background: var(--primary-color, #007F3D) !important;
    color: #ffffff !important;
    transform: translateY(-1px);
}

/* Info Cards / Guidelines */
.info-section-grid {
    max-width: 960px;
    margin: 0 auto 50px auto;
}

.info-box-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s ease;
}

.info-box-card:hover {
    transform: translateY(-3px);
}

.info-box-card h6 {
    font-weight: 700;
    font-size: 1.05rem;
    color: #0f172a;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-box-card h6 i {
    color: var(--primary-color, #007F3D);
}

.grading-table {
    font-size: 0.84rem;
    margin-bottom: 0;
}

.grading-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    padding: 6px 8px;
}

.grading-table td {
    padding: 6px 8px;
    color: #1e293b;
}

.guideline-list {
    padding-left: 18px;
    margin-bottom: 0;
    color: #475569;
    font-size: 0.88rem;
    line-height: 1.6;
}

.guideline-list li {
    margin-bottom: 8px;
}
</style>

<div class="results-page">
    <!-- Page Header (DVS Match) -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-dark mb-2 px-3 py-2 fw-semibold" style="border-radius: 30px; font-size: 0.82rem;">
                        <i class="fas fa-poll text-success me-1"></i> <?php echo $is_en ? 'Results Portal' : 'পরীক্ষা ও ফলাফল পোর্টাল'; ?>
                    </span>
                    <h1 class="page-header-title text-white mb-2" style="font-weight: 800; font-size: 2.2rem;">
                        <?php echo html_escape($title); ?>
                    </h1>
                    <p class="text-white-50 mb-0" style="font-size: 0.98rem; max-width: 600px;">
                        <?php echo html_escape($description); ?>
                    </p>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-end">
                    <div class="page-header-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave Shape -->
        <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
            <path fill="var(--primary-color, #007F3D)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
        </svg>
    </div>

    <!-- Main Container -->
    <div class="container main-container">
        
        <?php $prefix = !empty($cms_setting['url_alias']) ? $cms_setting['url_alias'] . '/' : ''; ?>
        <!-- Tab Switcher: Academic Result vs Board Results -->
        <div class="result-tab-wrapper text-center">
            <div class="result-tab-nav">
                <a href="<?php echo base_url($prefix . 'exam_results'); ?>" class="result-tab-btn active">
                    <i class="fas fa-graduation-cap"></i>
                    <span><?php echo $is_en ? 'Academic Result' : 'একাডেমিক ফলাফল'; ?></span>
                </a>
                <a href="<?php echo base_url($prefix . 'board-results'); ?>" class="result-tab-btn">
                    <i class="fas fa-award"></i>
                    <span><?php echo $is_en ? 'Board Results' : 'বোর্ড পরীক্ষার ফলাফল'; ?></span>
                    <span class="tab-badge bg-warning text-dark"><?php echo $is_en ? 'Yearly' : 'দাখিল / সমাপনী'; ?></span>
                </a>
            </div>
        </div>

        <!-- Academic Result Search Card -->
        <div class="results-search-card">
            <div class="results-card-header">
                <h5>
                    <i class="fas fa-search-plus"></i>
                    <?php echo $is_en ? 'Search Academic Result & Marksheet' : 'একাডেমিক ফলাফল ও নম্বরপত্র অনুসন্ধান'; ?>
                </h5>
                <span class="badge bg-light text-muted border px-3 py-2" style="font-weight: 500; border-radius: 8px;">
                    <i class="fas fa-calendar-check text-success me-1"></i> <?php echo $is_en ? 'Session: ' . date('Y') : 'শিক্ষাবর্ষ: ' . date('Y'); ?>
                </span>
            </div>

            <div class="results-form-body">
                <?php echo form_open('home/examResultsPrintFn', array('class' => 'printIn', 'id' => 'examResultForm')); ?>
                
                <div class="row g-3">
                    <!-- Exam Selection -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group mb-0">
                            <label class="results-form-label">
                                <i class="fas fa-book-open text-muted"></i>
                                <?php echo $is_en ? 'Select Exam' : 'পরীক্ষা নির্বাচন করুন'; ?> <span class="text-danger">*</span>
                            </label>
                            <div class="results-input-group">
                                <i class="fas fa-file-signature input-icon"></i>
                                <?php
                                    $array = array();
                                    $resultExams = $this->home_model->getExamList($branchID);
                                    if (!empty($resultExams) && count($resultExams)) {
                                        $array[''] = $is_en ? '-- Choose Exam --' : '-- পরীক্ষা নির্বাচন করুন --';
                                        foreach ($resultExams as $row) {
                                            if (!empty($row['term_id']) && $row['term_id'] != 0) {
                                                $term = $this->db->select('name')->where('id', $row['term_id'])->get('exam_term')->row();
                                                $term_name = !empty($term->name) ? ' (' . $term->name . ')' : '';
                                                $name = $row['name'] . $term_name;
                                            } else {
                                                $name = $row['name'];
                                            }
                                            $array[$row['id']] = $name;
                                        }
                                    } else {
                                        $array[''] = $is_en ? 'No Active Exams Available' : 'কোনো পরীক্ষা পাওয়া যায়নি';
                                    }

                                    echo form_dropdown("exam_id", $array, set_value('exam_id'), "class='form-control' required id='exam_id'");
                                ?>
                            </div>
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>
                    </div>

                    <!-- Academic Year -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group mb-0">
                            <label class="results-form-label">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <?php echo $is_en ? 'Academic Session / Year' : 'শিক্ষাবর্ষ'; ?> <span class="text-danger">*</span>
                            </label>
                            <div class="results-input-group">
                                <i class="fas fa-history input-icon"></i>
                                <?php
                                    $arrayYear = array("" => ($is_en ? '-- Select Year --' : '-- শিক্ষাবর্ষ নির্বাচন --'));
                                    $years = $this->db->order_by('id', 'DESC')->get('schoolyear')->result();
                                    $defaultSession = !empty($global_config['session_id']) ? $global_config['session_id'] : get_session_id();
                                    foreach ($years as $yr) {
                                        $arrayYear[$yr->id] = $yr->school_year;
                                    }
                                    echo form_dropdown("session_id", $arrayYear, set_value('session_id', $defaultSession), "class='form-control' required id='session_id'");
                                ?>
                            </div>
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>
                    </div>

                    <!-- Student Register No -->
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group mb-0">
                            <label class="results-form-label">
                                <i class="fas fa-id-card-clip text-muted"></i>
                                <?php echo $is_en ? 'Student Register No / Roll' : 'শিক্ষার্থীর রেজিস্টার বা রোল নং'; ?> <span class="text-danger">*</span>
                            </label>
                            <div class="results-input-group">
                                <i class="fas fa-user-graduate input-icon"></i>
                                <input type="text" class="form-control" name="register_no" id="register_no" 
                                    value="<?=set_value('register_no')?>" 
                                    placeholder="<?php echo $is_en ? 'e.g. CHIDM1185 or 1185' : 'যেমন: CHIDM1185 বা 1185 বা রোল'; ?>" 
                                    autocomplete="off" required />
                            </div>
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="grade_scale" value="<?php echo !empty($page_data['grade_scale']) ? $page_data['grade_scale'] : '1'; ?>">
                <input type="hidden" name="attendance" value="<?php echo !empty($page_data['attendance']) ? $page_data['attendance'] : '0'; ?>">

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn results-btn-submit" data-loading-text="<i class='fas fa-spinner fa-spin me-2'></i> <?php echo $is_en ? 'Searching Result...' : 'ফলাফল অনুসন্ধান হচ্ছে...'; ?>">
                        <i class="fas fa-search me-1"></i>
                        <span><?php echo $is_en ? 'Search & View Marksheet' : 'ফলাফল ও নম্বরপত্র দেখুন'; ?></span>
                    </button>
                    <div class="small text-muted mt-2">
                        <i class="fas fa-shield-alt text-success me-1"></i>
                        <?php echo $is_en ? 'Supports English and Bengali numbers (e.g. 1185 or ১১৮৫)' : 'ইংরেজি ও বাংলা উভয় সংখ্যা সমর্থনযোগ্য (যেমন: 1185 বা ১১৮৫)'; ?>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>

        <!-- Result Container / Card Holder -->
        <div id="card_holder" style="display: none;">
            <div class="result-card-container">
                <div class="result-action-bar">
                    <div>
                        <span class="badge bg-success px-3 py-2" style="font-size: 0.88rem; border-radius: 8px;">
                            <i class="fas fa-check-circle me-1"></i> <?php echo $is_en ? 'Official Marksheet Found' : 'অনলাইন নম্বরপত্র প্রস্তুত'; ?>
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-result-print" id="print">
                            <i class="fas fa-print"></i> <?php echo $is_en ? 'Print Marksheet' : 'নম্বরপত্র প্রিন্ট করুন'; ?>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="$('#card_holder').slideUp(200);" style="border-radius: 10px;">
                            <i class="fas fa-times me-1"></i> <?php echo $is_en ? 'Close' : 'বন্ধ করুন'; ?>
                        </button>
                    </div>
                </div>
                <div id="card" class="table-responsive"></div>
            </div>
        </div>

        <!-- Information & Grading Scale Guide Section -->
        <div class="info-section-grid">
            <div class="row g-4">
                <!-- Grading System Scale -->
                <div class="col-lg-6">
                    <div class="info-box-card">
                        <h6>
                            <i class="fas fa-award"></i>
                            <?php echo $is_en ? 'Grading System & Scale (GPA 5.0)' : 'মাদরাসা শিক্ষা বোর্ড গ্রেডিং স্কেল (জিপিএ ৫.০)'; ?>
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm grading-table text-center align-middle">
                                <thead>
                                    <tr>
                                        <th><?php echo $is_en ? 'Marks Range' : 'নম্বর ব্যাপ্তি'; ?></th>
                                        <th><?php echo $is_en ? 'Letter Grade' : 'লেটার গ্রেড'; ?></th>
                                        <th><?php echo $is_en ? 'Grade Point' : 'গ্রেড পয়েন্ট'; ?></th>
                                        <th><?php echo $is_en ? 'Remark' : 'মন্তব্য'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td>৮০ - ১০০%</td>
                                        <td><strong>A+</strong></td>
                                        <td>৫.০০</td>
                                        <td>অসাধারণ (Outstanding)</td>
                                    </tr>
                                    <tr>
                                        <td>৭০ - ৭৯%</td>
                                        <td><strong>A</strong></td>
                                        <td>৪.০০</td>
                                        <td>খুব ভালো (Very Good)</td>
                                    </tr>
                                    <tr>
                                        <td>৬০ - ৬৯%</td>
                                        <td><strong>A-</strong></td>
                                        <td>৩.৫০</td>
                                        <td>ভালো (Good)</td>
                                    </tr>
                                    <tr>
                                        <td>৫০ - ৫৯%</td>
                                        <td><strong>B</strong></td>
                                        <td>৩.০০</td>
                                        <td>সন্তোষজনক (Satisfactory)</td>
                                    </tr>
                                    <tr>
                                        <td>৪০ - ৪৯%</td>
                                        <td><strong>C</strong></td>
                                        <td>২.০০</td>
                                        <td>উত্তীর্ণ (Pass)</td>
                                    </tr>
                                    <tr>
                                        <td>৩৩ - ৩৯%</td>
                                        <td><strong>D</strong></td>
                                        <td>১.০০</td>
                                        <td>ন্যূনতম (Minimum Pass)</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>০ - ৩২%</td>
                                        <td><strong>F</strong></td>
                                        <td>০.০০</td>
                                        <td>অনুত্তীর্ণ (Fail)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Guidelines & Notice -->
                <div class="col-lg-6">
                    <div class="info-box-card">
                        <h6>
                            <i class="fas fa-info-circle"></i>
                            <?php echo $is_en ? 'Result Instructions & Rules' : 'ফলাফল সংক্রান্ত জরুরি নির্দেশনাবলী'; ?>
                        </h6>
                        <ul class="guideline-list">
                            <li>
                                <strong><?php echo $is_en ? 'Marksheet Validity:' : 'নম্বরপত্রের কার্যকারিতা:'; ?></strong>
                                <?php echo $is_en 
                                    ? 'This online report card is published for informational purposes. For the official signed copy, contact the madrasah office.' 
                                    : 'অনলাইন নম্বরপত্রটি প্রাথমিক তথ্যের জন্য প্রকাশিত। প্রাতিষ্ঠানিক সিল ও অধ্যক্ষের স্বাক্ষরযুক্ত মূল সনদ মাদরাসা কার্যালয় থেকে সংগ্রহ করতে হবে।'; ?>
                            </li>
                            <li>
                                <strong><?php echo $is_en ? 'Pass Criteria:' : 'পাসের শর্তাবলী:'; ?></strong>
                                <?php echo $is_en 
                                    ? 'A student must score a minimum of 33 marks in both theoretical and practical components to pass each subject.' 
                                    : 'প্রতিটি বিষয়ে পাস করার জন্য তাত্ত্বিক ও ব্যবহারিক/মৌখিক অংশে পৃথকভাবে ন্যূনতম ৩৩% নম্বর প্রাপ্ত হতে হবে।'; ?>
                            </li>
                            <li>
                                <strong><?php echo $is_en ? 'Re-check Application:' : 'পুনর্নিরীক্ষণ আবেদন:'; ?></strong>
                                <?php echo $is_en 
                                    ? 'Any review or re-check request regarding published results must be submitted to the headmaster within 7 days of result publication.' 
                                    : 'ফলাফল সংক্রান্ত কোনো আপত্তি বা পুনর্নিরীক্ষণের আবেদন ফলাফল প্রকাশের ৭ কর্মদিবসের মধ্যে মাদরাসা অফিসে লিখিতভাবে জানাতে হবে।'; ?>
                            </li>
                            <li>
                                <strong><?php echo $is_en ? 'Helpline Support:' : 'সহায়তা ও যোগাযোগ:'; ?></strong>
                                <?php echo $is_en 
                                    ? 'If your result is not displayed or you experience technical issues, contact the IT office at charhasan1996@gmail.com or call 01727770321.' 
                                    : 'ফলাফল দেখতে কোনো ত্রুটি দেখা দিলে মাদরাসা আইটি ডেস্কে অথবা মোবাইল: +8801727770321 নম্বরে যোগাযোগ করুন।'; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('form.printIn').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');
            var $this = $(this);
            var originalBtnHtml = btn.html();
            var loadingText = btn.data('loading-text') || '<i class="fas fa-spinner fa-spin me-2"></i> অপেক্ষা করুন...';
            
            $("#card_holder").slideUp(150);
            
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    btn.prop('disabled', true).html(loadingText);
                },
                success: function (data) {
                    $('.error').html("");
                    if (data.status == "fail") {
                        $.each(data.error, function (index, value) {
                            $this.find("[name='" + index + "']").closest('.form-group').find('.error').html(value);
                        });
                    } else if (data.status == 0) {
                        if (typeof swal === 'function') {
                            swal({
                                text: data.error,
                                icon: 'warning',
                                button: "<?php echo $is_en ? 'OK' : 'ঠিক আছে'; ?>"
                            });
                        } else {
                            alert(data.error);
                        }
                    } else {
                        $('#card').html(data.card_data);
                        $("#card_holder").slideDown(300, function(){
                            $('html, body').animate({
                                scrollTop: $("#card_holder").offset().top - 80
                            }, 400);
                        });
                    }
                },
                error: function () {
                    alert("<?php echo $is_en ? 'An error occurred. Please try again.' : 'একটি সমস্যা দেখা দিয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।'; ?>");
                },
                complete: function () {
                    btn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });

        $('#print').on('click', function(e){
            certificate_printElem('card', false);
        });
    });
</script>

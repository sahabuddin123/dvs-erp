<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
$is_en = ($current_lang === 'en');

$default_page_title = $is_en ? 'Exam Routine' : 'পরীক্ষার রুটিন';
$title = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'page_title', $default_page_title) 
    : (!empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title);

// Subtitle
$subtitle = $is_en 
    ? 'Search and view examination timetable for all classes' 
    : 'সকল শ্রেণির পরীক্ষার সময়সূচি ও রুটিন অনুসন্ধান করুন';
?>

<style>
/* Exam Routine Styling */
.exam-routine-page {
    color: #1e293b;
}

.page-header-icon {
    font-size: 5rem;
    color: #273142;
}

.routine-filter-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    margin-bottom: 30px;
}

.routine-filter-header {
    background: #ffffff;
    padding: 16px 24px;
    border-bottom: 1px solid #f1f5f9;
    border-left: 5px solid var(--primary-color, #007F3D);
    display: flex;
    align-items: center;
    gap: 10px;
}

.routine-filter-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.15rem;
    color: var(--primary-color, #007F3D);
}

.routine-results-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.routine-results-header {
    background: #f8fafc;
    padding: 18px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

/* Empty / Not Found Box */
.routine-empty-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    padding: 50px 30px;
    text-align: center;
}

.routine-empty-icon {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: #fef2f2;
    color: #ef4444;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    margin-bottom: 20px;
}

.routine-initial-guide {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    padding: 40px 24px;
    text-align: center;
    color: #64748b;
    margin-top: 15px;
}

.routine-initial-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 14px;
}

/* Print Rules */
@media print {
    .top-bar, .school-header, .navbar, .notice-bar, footer, .page-header, .page-header-wave, .routine-filter-card, .btn-print-action, .routine-initial-guide {
        display: none !important;
    }
    .routine-results-card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        width: 100% !important;
    }
}
</style>

<!-- Page Header (DVS Theme Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-header-title fw-bold mb-1"><?php echo html_escape($title); ?></h1>
                <p class="text-muted mb-0 d-none d-md-block" style="font-size: 0.95rem;"><?php echo html_escape($subtitle); ?></p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Container Starts -->
<div class="container main-container exam-routine-page my-5 py-3">

    <!-- Search / Filter Card -->
    <div class="routine-filter-card">
        <div class="routine-filter-header">
            <i class="fas fa-filter"></i>
            <h5><?php echo $is_en ? 'Search Exam Routine' : 'পরীক্ষার রুটিন অনুসন্ধান'; ?></h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="get" class="row g-3 align-items-end">
                <!-- Exam Select -->
                <div class="col-md-5">
                    <label class="form-label fw-bold" style="color: #334155;">
                        <i class="fas fa-file-alt text-primary me-1"></i> <?php echo translate('exam'); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="exam_id" class="form-select form-select-lg rounded-3" style="font-size: 0.95rem;" required>
                        <option value=""><?php echo translate('select'); ?></option>
                        <?php if (!empty($exams)): foreach ($exams as $e): ?>
                            <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam == $e['id']) ? 'selected' : ''; ?>>
                                <?php echo html_escape($e['name']); ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- Class Select -->
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #334155;">
                        <i class="fas fa-graduation-cap text-success me-1"></i> <?php echo translate('class'); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="class_id" class="form-select form-select-lg rounded-3" style="font-size: 0.95rem;" required>
                        <option value=""><?php echo translate('select'); ?></option>
                        <?php if (!empty($classes)): foreach ($classes as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($selected_class == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo html_escape($c['name']); ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm" style="background-color: var(--primary-color); border: none; font-weight: 600; font-size: 1rem;">
                        <i class="fas fa-search me-1"></i> <?php echo $is_en ? 'View Routine' : 'রুটিন দেখুন'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CMS Page Description if genuine content entered by Admin -->
    <?php if (!empty($page_data['content']) && strpos($page_data['content'], 'রুটিন পাওয়া যায়নি') === false && strlen(trim(strip_tags($page_data['content']))) > 10): ?>
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <?php echo $page_data['content']; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Results Section: ONLY appears after search! -->
    <?php if (!empty($is_searched)): ?>
        <?php if (!empty($selected_exam) && !empty($selected_class)): ?>
            
            <?php if (!empty($timetable)): ?>
                <!-- Routine Found: Display Schedule Table -->
                <?php 
                    $exam_name = '';
                    foreach ($exams as $e) { if ($e['id'] == $selected_exam) { $exam_name = $e['name']; break; } }
                    $class_name = '';
                    foreach ($classes as $c) { if ($c['id'] == $selected_class) { $class_name = $c['name']; break; } }
                ?>
                <div class="routine-results-card" id="routinePrintArea">
                    <div class="routine-results-header">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                <?php echo html_escape($exam_name); ?> — <?php echo html_escape($class_name); ?>
                            </h4>
                            <p class="mb-0 text-muted small">
                                <i class="fas fa-school me-1"></i> <?php echo !empty($global_config['institute_name']) ? html_escape($global_config['institute_name']) : 'চর হাসান ইসলামিয়া দাখিল মাদরাসা'; ?>
                                <span class="mx-2">|</span>
                                <i class="far fa-clock me-1"></i> <?php echo $is_en ? 'Academic Timetable' : 'একাডেমিক সময়সূচি'; ?>
                            </p>
                        </div>
                        <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 btn-print-action" onclick="window.print();">
                            <i class="fas fa-print me-1"></i> <?php echo $is_en ? 'Print Routine' : 'প্রিন্ট করুন'; ?>
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead style="background: #f1f5f9; color: #334155; font-size: 0.9rem;">
                                    <tr>
                                        <th class="text-center py-3" style="width: 50px;">#</th>
                                        <th class="py-3"><?php echo translate('date'); ?></th>
                                        <th class="py-3"><?php echo translate('time'); ?></th>
                                        <th class="py-3"><?php echo translate('subject'); ?></th>
                                        <th class="py-3"><?php echo $is_en ? 'Hall / Room' : 'কক্ষ / হল নং'; ?></th>
                                        <th class="text-center py-3"><?php echo translate('full_marks'); ?></th>
                                        <th class="text-center py-3"><?php echo translate('passing_marks'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl = 1; foreach ($timetable as $t): ?>
                                        <tr>
                                            <td class="text-center fw-bold text-muted"><?php echo $sl++; ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="far fa-calendar-alt me-1 text-primary"></i>
                                                    <?php echo !empty($t['exam_date']) ? _d($t['exam_date']) : '-'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <i class="far fa-clock me-1 text-muted"></i>
                                                <?php echo !empty($t['time_start']) ? date('h:i A', strtotime($t['time_start'])) : ''; ?> - 
                                                <?php echo !empty($t['time_end']) ? date('h:i A', strtotime($t['time_end'])) : ''; ?>
                                            </td>
                                            <td>
                                                <strong style="color: #0f172a;"><?php echo html_escape($t['subject_name']); ?></strong>
                                                <?php if (!empty($t['subject_code'])): ?>
                                                    <small class="text-muted">(<?php echo html_escape($t['subject_code']); ?>)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                                    <i class="fas fa-door-open me-1"></i> <?php echo !empty($t['hall_no']) ? html_escape($t['hall_no']) : (!empty($t['hall_id']) ? 'Hall ' . $t['hall_id'] : '-'); ?>
                                                </span>
                                            </td>
                                            <td class="text-center fw-bold text-primary"><?php echo !empty($t['mark_distribution']) ? '-' : ($t['full_mark'] ?? '-'); ?></td>
                                            <td class="text-center text-secondary"><?php echo $t['pass_mark'] ?? '-'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Routine NOT Found: Exact requested bilingual message -->
                <div class="routine-empty-card">
                    <div class="routine-empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: #0f172a;">
                        <?php echo $is_en ? 'Exam routine not found.' : 'রুটিন পাওয়া যায়নি।'; ?>
                    </h4>
                    <p class="text-muted mb-3" style="max-width: 520px; margin: 0 auto; font-size: 0.95rem;">
                        <?php echo $is_en 
                            ? 'No examination routine found for the selected exam and class.' 
                            : 'নির্বাচিত পরীক্ষা ও শ্রেণির জন্য এখনও কোনো পরীক্ষার রুটিন তৈরি বা প্রকাশ করা হয়নি।'; ?>
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        <?php echo $is_en 
                            ? 'Please check back later or contact school administration.' 
                            : 'অনুগ্রহ করে অন্য কোনো শ্রেণি বা পরীক্ষা নির্বাচন করে পুনরায় চেষ্টা করুন অথবা কর্তৃপক্ষের সাথে যোগাযোগ করুন।'; ?>
                    </p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Incomplete selection alert -->
            <div class="alert alert-warning border-0 rounded-4 p-4 shadow-sm text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block text-warning"></i>
                <h5 class="fw-bold mb-1"><?php echo $is_en ? 'Please select both Exam and Class' : 'অনুগ্রহ করে পরীক্ষা এবং শ্রেণি উভয়টি নির্বাচন করুন'; ?></h5>
                <p class="mb-0 small text-muted"><?php echo $is_en ? 'To view the routine, select an exam and class and click View Routine.' : 'রুটিন দেখতে উপরের ফর্ম থেকে পরীক্ষা ও শ্রেণি নির্বাচন করে "রুটিন দেখুন" বাটনে ক্লিক করুন।'; ?></p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Initial Page Load (Before Searching): Guide Card -->
        <div class="routine-initial-guide">
            <div class="routine-initial-icon">
                <i class="fas fa-search"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">
                <?php echo $is_en ? 'Find Your Examination Schedule' : 'আপনার পরীক্ষার রুটিন অনুসন্ধান করুন'; ?>
            </h5>
            <p class="mb-0 text-muted small" style="max-width: 540px; margin: 0 auto;">
                <?php echo $is_en 
                    ? 'Select your Examination and Class from the dropdown above and click "View Routine" to display the timetable.' 
                    : 'পরীক্ষার রুটিন দেখতে উপরোক্ত ফর্ম থেকে কাঙ্ক্ষিত পরীক্ষা এবং শ্রেণি নির্বাচন করে "রুটিন দেখুন" বাটনে ক্লিক করুন।'; ?>
            </p>
        </div>
    <?php endif; ?>

</div>

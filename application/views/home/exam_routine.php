<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
$title = !empty($page_data['menu_title']) ? $page_data['menu_title'] : (!empty($page_data['page_title']) ? $page_data['page_title'] : (($current_lang == 'en') ? 'Exam Routine' : 'পরিক্ষার রুটিন'));
?>
<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($title); ?></h1>
                <p class="text-white-50 mb-0"><?php echo ($current_lang == 'en') ? 'View and download examination timetable' : 'সকল শ্রেণির পরিক্ষার সময়সূচি ও রুটিন দেখুন'; ?></p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-calendar-check"></i>
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
<div class="container px-md-0 main-container py-4">
    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom" style="border-left: 4px solid var(--primary-color);">
            <h5 class="mb-0" style="font-weight: 700; color: var(--primary-color);">
                <i class="fas fa-filter me-2"></i> <?php echo ($current_lang == 'en') ? 'Filter Exam Routine' : 'পরিক্ষার রুটিন অনুসন্ধান'; ?>
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="<?php echo base_url('exam-routine'); ?>" method="get" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold"><?php echo translate('exam'); ?> <span class="text-danger">*</span></label>
                    <select name="exam_id" class="form-select" required>
                        <option value=""><?php echo translate('select'); ?></option>
                        <?php if (!empty($exams)): foreach ($exams as $e): ?>
                            <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam == $e['id']) ? 'selected' : ''; ?>>
                                <?php echo html_escape($e['name']); ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold"><?php echo translate('class'); ?> <span class="text-danger">*</span></label>
                    <select name="class_id" class="form-select" required>
                        <option value=""><?php echo translate('select'); ?></option>
                        <?php if (!empty($classes)): foreach ($classes as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($selected_class == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo html_escape($c['name']); ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: var(--primary-color); border: none; font-weight: 600;">
                        <i class="fas fa-search me-1"></i> <?php echo ($current_lang == 'en') ? 'View Routine' : 'রুটিন দেখুন'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CMS Page Description if available -->
    <?php if (!empty($page_data['content'])): ?>
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <?php echo $page_data['content']; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Results Section -->
    <?php if (!empty($selected_exam) && !empty($selected_class)): ?>
        <div class="card shadow-sm border-0" id="routinePrintArea" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0" style="font-weight: 700; color: #1f2937;">
                    <i class="fas fa-calendar-days text-success me-2"></i> <?php echo ($current_lang == 'en') ? 'Examination Timetable' : 'পরিক্ষার সময়সূচি'; ?>
                </h5>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="window.print();">
                    <i class="fas fa-print me-1"></i> <?php echo ($current_lang == 'en') ? 'Print Routine' : 'প্রিন্ট করুন'; ?>
                </button>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($timetable)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th><?php echo translate('date'); ?></th>
                                    <th><?php echo translate('time'); ?></th>
                                    <th><?php echo translate('subject'); ?></th>
                                    <th><?php echo translate('room'); ?></th>
                                    <th class="text-center"><?php echo translate('full_marks'); ?></th>
                                    <th class="text-center"><?php echo translate('passing_marks'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl = 1; foreach ($timetable as $t): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo $sl++; ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
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
                                            <strong><?php echo html_escape($t['subject_name']); ?></strong>
                                            <?php if (!empty($t['subject_code'])): ?>
                                                <small class="text-muted">(<?php echo html_escape($t['subject_code']); ?>)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo !empty($t['hall_no']) ? html_escape($t['hall_no']) : '-'; ?></td>
                                        <td class="text-center fw-bold text-primary"><?php echo !empty($t['mark_distribution']) ? '-' : ($t['full_mark'] ?? '-'); ?></td>
                                        <td class="text-center text-secondary"><?php echo $t['pass_mark'] ?? '-'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times text-warning display-4 mb-3"></i>
                        <h5 class="fw-bold text-muted"><?php echo ($current_lang == 'en') ? 'No Routine Schedule Found' : 'এই পরিক্ষা ও শ্রেণির জন্য এখনও রুটিন তৈরি করা হয়নি'; ?></h5>
                        <p class="text-muted small"><?php echo ($current_lang == 'en') ? 'Please check back later or contact school administration.' : 'অনুগ্রহ করে পরবর্তীতে লক্ষ্য রাখুন অথবা স্কুল কর্তৃপক্ষের সাথে যোগাযোগ করুন।'; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
@media print {
    .top-bar, .school-header, .navbar, .notice-bar, footer, .card-header button, form {
        display: none !important;
    }
    .page-header {
        padding: 20px 0 !important;
        background: none !important;
        color: #000 !important;
    }
    .page-header-wave {
        display: none !important;
    }
    #routinePrintArea {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>

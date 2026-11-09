<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
$is_en = ($current_lang === 'en');

$default_page_title = $is_en ? 'Board Examination Results' : 'বোর্ড পরীক্ষার ফলাফল';
$title = !empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title;

$default_desc = $is_en 
    ? 'Year-wise academic performance and statistics of Dakhil & Ebtedayee board examinations.' 
    : 'বাংলাদেশ মাদরাসা শিক্ষা বোর্ডের অধীনে বিগত বছরসমূহের দাখিল ও ইবতেদায়ী সমাপনী পরীক্ষার ফলাফল ও মেধা পরিসংখ্যান।';
$description = !empty($page_data['description']) ? $page_data['description'] : $default_desc;

function to_bn_number($num) {
    $en = array('0','1','2','3','4','5','6','7','8','9','.');
    $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯','.');
    return str_replace($en, $bn, (string)$num);
}
?>

<style type="text/css">
.board-page {
    color: #1e293b;
}

.page-header-icon {
    font-size: 5rem;
    color: #273142;
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

/* Stats Highlight Banner */
.stats-banner {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    padding: 26px;
    margin-bottom: 35px;
}

.stat-metric-box {
    text-align: center;
    padding: 15px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #edf2f7;
    transition: all 0.25s ease;
}

.stat-metric-box:hover {
    background: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    border-color: var(--primary-color, #007F3D);
}

.stat-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.stat-icon-primary {
    background: rgba(0, 127, 61, 0.12);
    color: var(--primary-color, #007F3D);
}

.stat-icon-gold {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
}

.stat-icon-blue {
    background: rgba(37, 99, 235, 0.12);
    color: #2563eb;
}

.stat-icon-emerald {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

.stat-number {
    font-size: 1.85rem;
    font-weight: 800;
    line-height: 1.2;
    color: #0f172a;
    margin-bottom: 2px;
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0;
}

/* Year Filter Navigation */
.year-filter-wrapper {
    margin-bottom: 30px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
}

.btn-year-filter {
    border-radius: 30px;
    padding: 7px 20px;
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    transition: all 0.2s ease;
}

.btn-year-filter:hover {
    border-color: var(--primary-color, #007F3D);
    color: var(--primary-color, #007F3D);
    background: #f8fafc;
}

.btn-year-filter.active {
    background: var(--primary-color, #007F3D);
    border-color: var(--primary-color, #007F3D);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 127, 61, 0.25);
}

/* Board Result Cards */
.board-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.board-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 35px rgba(0, 0, 0, 0.08);
}

.board-card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    padding: 22px 28px;
    border-bottom: 1px solid #edf2f7;
    border-left: 6px solid var(--primary-color, #007F3D);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.board-card-title {
    font-weight: 800;
    font-size: 1.35rem;
    color: #0f172a;
    margin-bottom: 4px;
}

.board-card-meta {
    font-size: 0.85rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.pass-badge {
    background: linear-gradient(135deg, #007F3D 0%, #059669 100%);
    color: #ffffff;
    border-radius: 14px;
    padding: 10px 18px;
    text-align: center;
    box-shadow: 0 6px 16px rgba(0, 127, 61, 0.28);
}

.pass-badge .pass-rate-val {
    font-size: 1.45rem;
    font-weight: 800;
    line-height: 1;
    display: block;
}

.pass-badge .pass-rate-txt {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
}

.board-card-body {
    padding: 28px;
}

/* Metric Row Inside Card */
.result-metric-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .result-metric-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.metric-pill {
    background: #f8fafc;
    border-radius: 12px;
    padding: 14px;
    text-align: center;
    border: 1px solid #f1f5f9;
}

.metric-pill.gpa5-pill {
    background: #fffbeb;
    border-color: #fde68a;
}

.metric-pill .metric-val {
    font-size: 1.55rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}

.metric-pill.gpa5-pill .metric-val {
    color: #b45309;
}

.metric-pill .metric-name {
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    margin-top: 4px;
    margin-bottom: 0;
}

/* Grade Distribution Bar */
.grade-dist-section {
    background: #f8fafc;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 20px;
    border: 1px solid #eef2f6;
}

.grade-dist-header {
    font-size: 0.86rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.grade-badge-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.grade-pill {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.grade-pill.pill-gpa5 {
    background: #fef3c7;
    border-color: #fcd34d;
    color: #92400e;
}

.board-card-footer {
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.board-desc-text {
    font-size: 0.92rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 0;
    flex: 1;
}

.btn-download-result {
    background: #ffffff;
    color: var(--primary-color, #007F3D);
    border: 1.5px solid var(--primary-color, #007F3D);
    border-radius: 10px;
    padding: 8px 18px;
    font-size: 0.88rem;
    font-weight: 600;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.btn-download-result:hover {
    background: var(--primary-color, #007F3D);
    color: #ffffff;
    transform: translateY(-2px);
}
</style>

<div class="board-page">
    <!-- Page Header (DVS Match) -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge mb-2 px-3 py-2 fw-semibold" style="background-color: rgba(var(--primary-rgb, 0, 127, 61), 0.1); color: var(--primary-color); border-radius: 30px; font-size: 0.82rem;">
                        <i class="fas fa-award me-1"></i> <?php echo $is_en ? 'Madrasah Board Results' : 'মাদরাসা শিক্ষা বোর্ড ফলাফল'; ?>
                    </span>
                    <h1 class="page-header-title mb-2" style="font-weight: 800; font-size: 2.2rem;">
                        <?php echo html_escape($title); ?>
                    </h1>
                    <p class="text-muted mb-0" style="font-size: 0.98rem; max-width: 620px;">
                        <?php echo html_escape($description); ?>
                    </p>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-end">
                    <div class="page-header-icon">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave Shape -->
        <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
            <path fill="var(--primary-color, #007F3D)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
        </svg>
    </div>

    <!-- Main Content -->
    <div class="container main-container">

        <?php $prefix = !empty($cms_setting['url_alias']) ? $cms_setting['url_alias'] . '/' : ''; ?>
        <!-- Tab Switcher: Academic Result vs Board Results -->
        <div class="result-tab-wrapper text-center">
            <div class="result-tab-nav">
                <a href="<?php echo base_url($prefix . 'exam_results'); ?>" class="result-tab-btn">
                    <i class="fas fa-graduation-cap"></i>
                    <span><?php echo $is_en ? 'Academic Result' : 'একাডেমিক ফলাফল'; ?></span>
                </a>
                <a href="<?php echo base_url($prefix . 'board-results'); ?>" class="result-tab-btn active">
                    <i class="fas fa-award"></i>
                    <span><?php echo $is_en ? 'Board Results' : 'বোর্ড পরীক্ষার ফলাফল'; ?></span>
                    <span class="tab-badge bg-warning text-dark"><?php echo $is_en ? 'Yearly' : 'দাখিল / সমাপনী'; ?></span>
                </a>
            </div>
        </div>

        <!-- Overall Summary Stats Banner -->
        <?php if (!empty($stats)): ?>
        <div class="stats-banner">
            <div class="row g-3">
                <!-- Total Students -->
                <div class="col-6 col-lg-3">
                    <div class="stat-metric-box">
                        <div class="stat-icon-wrap stat-icon-blue">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-number">
                            <?php echo $is_en ? $stats['total_students'] : to_bn_number($stats['total_students']); ?>
                        </div>
                        <p class="stat-label"><?php echo $is_en ? 'Total Examinees' : 'মোট পরীক্ষার্থী'; ?></p>
                    </div>
                </div>

                <!-- Total Passed -->
                <div class="col-6 col-lg-3">
                    <div class="stat-metric-box">
                        <div class="stat-icon-wrap stat-icon-primary">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="stat-number">
                            <?php echo $is_en ? $stats['total_passed'] : to_bn_number($stats['total_passed']); ?>
                        </div>
                        <p class="stat-label"><?php echo $is_en ? 'Total Passed' : 'মোট উত্তীর্ণ শিক্ষার্থী'; ?></p>
                    </div>
                </div>

                <!-- Overall Pass Rate -->
                <div class="col-6 col-lg-3">
                    <div class="stat-metric-box">
                        <div class="stat-icon-wrap stat-icon-emerald">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stat-number text-success">
                            <?php echo $is_en ? $stats['overall_pass_rate'] . '%' : to_bn_number($stats['overall_pass_rate']) . '%'; ?>
                        </div>
                        <p class="stat-label"><?php echo $is_en ? 'Overall Pass Rate' : 'গড় পাসের হার'; ?></p>
                    </div>
                </div>

                <!-- Total GPA-5 -->
                <div class="col-6 col-lg-3">
                    <div class="stat-metric-box">
                        <div class="stat-icon-wrap stat-icon-gold">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number text-warning">
                            <?php echo $is_en ? $stats['total_gpa5'] : to_bn_number($stats['total_gpa5']); ?>
                        </div>
                        <p class="stat-label"><?php echo $is_en ? 'Total GPA 5.0 (A+)' : 'মোট জিপিএ ৫.০০ (A+)'; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Year Filter Buttons -->
        <?php if (!empty($years) && count($years) > 1): ?>
        <div class="year-filter-wrapper">
            <button type="button" class="btn btn-year-filter active" data-year="all">
                <i class="fas fa-th-large me-1"></i> <?php echo $is_en ? 'All Years' : 'সকল বছর'; ?>
            </button>
            <?php foreach ($years as $yr): ?>
                <button type="button" class="btn btn-year-filter" data-year="<?php echo $yr; ?>">
                    <i class="fas fa-calendar-alt me-1"></i> <?php echo $is_en ? $yr : to_bn_number($yr); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Board Results Card List -->
        <?php if (!empty($board_results)): ?>
            <div class="board-results-container">
                <?php foreach ($board_results as $row): 
                    $exam_title = ($is_en && !empty($row['exam_name_en'])) ? $row['exam_name_en'] : $row['exam_name'];
                    $yr_display = $is_en ? $row['year'] : to_bn_number($row['year']);
                    $pass_rate = floatval($row['pass_percentage']);
                    $pass_rate_disp = $is_en ? $pass_rate . '%' : to_bn_number($pass_rate) . '%';
                ?>
                    <div class="board-card board-result-item" data-year="<?php echo $row['year']; ?>">
                        <!-- Card Header -->
                        <div class="board-card-header">
                            <div>
                                <h3 class="board-card-title">
                                    <i class="fas fa-graduation-cap text-success me-2"></i>
                                    <?php echo html_escape($exam_title); ?> - <?php echo $yr_display; ?>
                                </h3>
                                <div class="board-card-meta">
                                    <span>
                                        <i class="fas fa-university text-muted me-1"></i>
                                        <?php echo $is_en ? 'Bangladesh Madrasah Education Board, Dhaka' : 'বাংলাদেশ মাদরাসা শিক্ষা বোর্ড, ঢাকা'; ?>
                                    </span>
                                    <span>•</span>
                                    <span>
                                        <i class="fas fa-calendar-check text-muted me-1"></i>
                                        <?php echo $is_en ? 'Year: ' . $row['year'] : 'পরীক্ষার বছর: ' . to_bn_number($row['year']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="pass-badge">
                                <span class="pass-rate-val"><?php echo $pass_rate_disp; ?></span>
                                <span class="pass-rate-txt"><?php echo $is_en ? 'Pass Rate' : 'পাসের হার'; ?></span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="board-card-body">
                            <!-- Metrics Grid -->
                            <div class="result-metric-grid">
                                <div class="metric-pill">
                                    <div class="metric-val"><?php echo $is_en ? $row['total_students'] : to_bn_number($row['total_students']); ?></div>
                                    <p class="metric-name"><?php echo $is_en ? 'Total Examinees' : 'মোট পরীক্ষার্থী'; ?></p>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-val text-success"><?php echo $is_en ? $row['passed_students'] : to_bn_number($row['passed_students']); ?></div>
                                    <p class="metric-name"><?php echo $is_en ? 'Passed' : 'উত্তীর্ণ'; ?></p>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-val text-danger"><?php echo $is_en ? $row['failed_students'] : to_bn_number($row['failed_students']); ?></div>
                                    <p class="metric-name"><?php echo $is_en ? 'Failed' : 'অনুত্তীর্ণ'; ?></p>
                                </div>
                                <div class="metric-pill gpa5-pill">
                                    <div class="metric-val"><?php echo $is_en ? $row['gpa_5'] : to_bn_number($row['gpa_5']); ?></div>
                                    <p class="metric-name"><i class="fas fa-star text-warning me-1"></i> <?php echo $is_en ? 'GPA 5.0 (A+)' : 'জিপিএ ৫.০০ (A+)'; ?></p>
                                </div>
                            </div>

                            <!-- Grade Breakdown -->
                            <div class="grade-dist-section">
                                <div class="grade-dist-header">
                                    <span>
                                        <i class="fas fa-chart-bar text-success me-1"></i>
                                        <?php echo $is_en ? 'Grade Distribution Breakdown:' : 'গ্রেড ভিত্তিক ফলাফল বিন্যাস:'; ?>
                                    </span>
                                </div>
                                <div class="grade-badge-pills">
                                    <span class="grade-pill pill-gpa5">
                                        <i class="fas fa-award text-warning"></i> 
                                        <strong>GPA 5.0 (A+):</strong> <?php echo $is_en ? $row['gpa_5'] : to_bn_number($row['gpa_5']); ?> <?php echo $is_en ? 'students' : 'জন'; ?>
                                    </span>
                                    <span class="grade-pill">
                                        <strong>A (4.0):</strong> <?php echo $is_en ? $row['grade_a'] : to_bn_number($row['grade_a']); ?> <?php echo $is_en ? 'students' : 'জন'; ?>
                                    </span>
                                    <span class="grade-pill">
                                        <strong>A- (3.5):</strong> <?php echo $is_en ? $row['grade_a_minus'] : to_bn_number($row['grade_a_minus']); ?> <?php echo $is_en ? 'students' : 'জন'; ?>
                                    </span>
                                    <?php if (!empty($row['grade_b']) && $row['grade_b'] > 0): ?>
                                    <span class="grade-pill">
                                        <strong>B (3.0):</strong> <?php echo $is_en ? $row['grade_b'] : to_bn_number($row['grade_b']); ?> <?php echo $is_en ? 'students' : 'জন'; ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($row['grade_c']) && $row['grade_c'] > 0): ?>
                                    <span class="grade-pill">
                                        <strong>C (2.0):</strong> <?php echo $is_en ? $row['grade_c'] : to_bn_number($row['grade_c']); ?> <?php echo $is_en ? 'students' : 'জন'; ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Footer: Description + Result File Download -->
                            <div class="board-card-footer">
                                <?php if (!empty($row['description'])): ?>
                                    <p class="board-desc-text">
                                        <i class="fas fa-quote-left text-muted me-1"></i>
                                        <?php echo nl2br(html_escape($row['description'])); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($row['result_file'])): ?>
                                    <div>
                                        <a href="<?php echo base_url('uploads/frontend/board_results/' . $row['result_file']); ?>" target="_blank" class="btn-download-result">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <span><?php echo $is_en ? 'Download Result Sheet (PDF)' : 'ফলাফল শিট ডাউনলোড'; ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded-4 border shadow-sm my-4">
                <i class="fas fa-poll text-muted mb-3" style="font-size: 3.5rem;"></i>
                <h5 class="fw-bold text-dark"><?php echo $is_en ? 'No Board Results Published Yet' : 'এখনও কোনো বোর্ড পরীক্ষার ফলাফল প্রকাশিত হয়নি'; ?></h5>
                <p class="text-muted small"><?php echo $is_en ? 'Results will be updated as soon as released by the madrasah board.' : 'মাদরাসা শিক্ষা বোর্ডের ফলাফল প্রকাশিত হওয়ামাত্রই এখানে হালনাগাদ করা হবে।'; ?></p>
            </div>
        <?php endif; ?>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-year-filter').on('click', function() {
            $('.btn-year-filter').removeClass('active');
            $(this).addClass('active');

            var selectedYear = $(this).data('year');
            if (selectedYear === 'all') {
                $('.board-result-item').fadeIn(250);
            } else {
                $('.board-result-item').hide();
                $('.board-result-item[data-year="' + selectedYear + '"]').fadeIn(250);
            }
        });
    });
</script>

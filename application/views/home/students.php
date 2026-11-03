<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Students' : 'শিক্ষার্থী')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Students' : 'শিক্ষার্থী'));
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="text-white opacity-80 mb-0 mt-2">
                    <?php echo ($current_lang == 'en') ? 'Browse enrolled students across various school sections' : 'বিদ্যালয়ের বিভিন্ন শাখার শিক্ষার্থীদের তালিকা ও তথ্য'; ?>
                </p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Students Section -->
<section class="section my-5 py-5 bg-light" style="min-height: 500px;">
    <div class="container">
        <!-- Info Card -->
        <div class="card shadow-sm mb-5 border-0" style="border-radius: 8px; background: #fff; border-left: 4px solid #5650de !important;">
            <div class="card-body p-4 p-md-4">
                <div class="d-flex align-items-center">
                    <div class="me-3 d-none d-sm-block text-primary" style="font-size: 28px; color: #5650de !important;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;">
                            <?php echo ($current_lang == 'en') ? 'Student Section Directory' : 'শিক্ষার্থী শাখা ডিরেক্টরি'; ?>
                        </h5>
                        <p class="mb-0 text-muted" style="font-size: 0.95rem;">
                            <?php echo ($current_lang == 'en') 
                                ? 'Browse students by school sections below. Click on any section to select a class and view the list of enrolled students.' 
                                : 'বিদ্যালয়ের বিভিন্ন শাখার শিক্ষার্থীদের তথ্য দেখতে নিচে আপনার কাঙ্ক্ষিত শাখা নির্বাচন করুন। এরপর শ্রেণি বাছাই করে শিক্ষার্থীদের সম্পূর্ণ তালিকা দেখতে পাবেন।'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Sections Grid -->
        <?php if (!empty($sections)) { ?>
        <div class="row g-4">
            <?php 
            foreach ($sections as $sec) { 
                $count = isset($student_counts[$sec['id']]) ? $student_counts[$sec['id']] : 0;
                
                // Choose relevant icon based on name
                $icon = 'fa-layer-group';
                $sec_lower = strtolower($sec['name']);
                if (strpos($sec_lower, 'boy') !== false || strpos($sec_lower, 'বালক') !== false) {
                    $icon = 'fa-male';
                } elseif (strpos($sec_lower, 'girl') !== false || strpos($sec_lower, 'বালিকা') !== false) {
                    $icon = 'fa-female';
                } elseif (strpos($sec_lower, 'hifz') !== false || strpos($sec_lower, 'তাহফিজ') !== false) {
                    $icon = 'fa-quran';
                }
            ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?php echo base_url('students/section/' . $sec['id']); ?>" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-0 section-hover-card" style="border-radius: 10px; transition: all 0.3s ease; border-top: 4px solid #5650de;">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box" style="width: 48px; height: 48px; background: #eef2ff; color: #5650de; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <span class="badge border" style="background-color: #f8fafc; color: #475569; font-size: 0.8rem; font-weight: 600;">
                                    <?php echo ($current_lang == 'en') ? 'Section' : 'শাখা'; ?>
                                </span>
                            </div>

                            <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.15rem; line-height: 1.4;">
                                <?php echo html_escape($sec['name']); ?>
                            </h5>

                            <p class="card-text text-muted mb-3 small flex-grow-1">
                                <?php 
                                if (!empty($sec['capacity'])) {
                                    echo ($current_lang == 'en') ? 'Class capacity: ' . $sec['capacity'] . ' seats' : 'প্রতি শ্রেণিতে আসন সংখ্যা: ' . $sec['capacity'] . ' জন';
                                } else {
                                    echo ($current_lang == 'en') ? 'Enrolled students in this section' : 'এই শাখায় অধ্যায়নরত শিক্ষার্থীবৃন্দ';
                                }
                                ?>
                            </p>

                            <?php if ($count > 0) { ?>
                            <div class="mb-3">
                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.82rem; font-weight: 600;">
                                    <i class="fas fa-user-check me-1"></i> <?php echo ($current_lang == 'en') ? $count . ' Students' : $count . ' জন শিক্ষার্থী'; ?>
                                </span>
                            </div>
                            <?php } ?>

                            <div class="pt-2">
                                <span class="btn btn-sm text-white w-100 shadow-sm py-2" style="background-color: #5650de; font-weight: 500; border-radius: 6px;">
                                    <?php echo ($current_lang == 'en') ? 'View Students' : 'শিক্ষার্থী দেখুন'; ?> <i class="fas fa-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="alert alert-info shadow-sm" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px; padding: 1.5rem;">
            <h5 class="alert-heading fw-bold mb-1"><?php echo ($current_lang == 'en') ? 'No sections found' : 'কোনও শাখা পাওয়া যায়নি'; ?></h5>
            <p class="mb-0"><?php echo ($current_lang == 'en') ? 'No sections have been configured in the system.' : 'সিস্টেমে এখনও কোনো শাখা যোগ করা হয়নি।'; ?></p>
        </div>
        <?php } ?>
    </div>
</section>

<style>
.section-hover-card {
    background: #fff;
    cursor: pointer;
}
.section-hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px -4px rgba(86, 80, 222, 0.2) !important;
    border-top-color: #4338ca !important;
}
.section-hover-card:hover .btn {
    background-color: #4338ca !important;
}
</style>

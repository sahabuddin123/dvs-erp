<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Students' : 'শিক্ষার্থী')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Students' : 'শিক্ষার্থী'));
$page_subtitle = function_exists('get_lang_field') ? get_lang_field($page_data, 'title', ($current_lang == 'en' ? 'Student Information' : 'শিক্ষার্থীর তথ্য')) : (!empty($page_data['title']) ? $page_data['title'] : ($current_lang == 'en' ? 'Student Information' : 'শিক্ষার্থীর তথ্য'));
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <?php if (!empty($page_subtitle)) { ?>
                    <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
                <?php } ?>
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
<section class="section my-5 py-5 bg-light">
    <div class="container">
        <!-- Info Card -->
        <div class="card shadow-sm mb-5 border-0" style="border-radius: 12px;">
            <div class="card-body p-4 p-md-5">
                <div class="post-content" id="description">
                    <p class="mb-0 text-muted fs-6">
                        <i class="fas fa-info-circle text-success me-2"></i>
                        <?php echo ($current_lang == 'en') ? 'Student categories and details are managed from the admin panel.' : 'শিক্ষার্থী বিভাগ এবং বিবরণগুলো এডমিন প্যানেল থেকে পরিচালিত হয়।'; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Student Departments / Shifts Grid -->
        <div class="row g-4">
            <!-- Morning Bangla -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="badge px-3 py-2" style="background-color: #e8f5e9; color: var(--primary-color); border-radius: 20px;">
                                <i class="fas fa-sun me-1"></i> <?php echo ($current_lang == 'en') ? 'Morning Shift' : 'সকাল শিফট'; ?>
                            </span>
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-2">
                            <?php echo ($current_lang == 'en') ? 'Morning Bangla' : 'সকাল বাংলা'; ?>
                        </h4>
                        <p class="card-text text-muted mb-4">
                            <?php echo ($current_lang == 'en') ? 'Enrolled students in Morning Bangla shift.' : 'সকাল বাংলা বিভাগে ভর্তি শিক্ষার্থীরা।'; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                        <a href="<?php echo base_url('admission'); ?>" class="btn btn-sm text-white px-3 py-2 w-100" style="background-color: var(--primary-color); border-radius: 8px;">
                            <?php echo ($current_lang == 'en') ? 'View Department' : 'বিভাগ দেখুন'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Day Bangla -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="badge px-3 py-2" style="background-color: #e8f5e9; color: var(--primary-color); border-radius: 20px;">
                                <i class="fas fa-cloud-sun me-1"></i> <?php echo ($current_lang == 'en') ? 'Day Shift' : 'দিন শিফট'; ?>
                            </span>
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-2">
                            <?php echo ($current_lang == 'en') ? 'Day Bangla' : 'দিন বাংলা'; ?>
                        </h4>
                        <p class="card-text text-muted mb-4">
                            <?php echo ($current_lang == 'en') ? 'Enrolled students in Day Bangla shift.' : 'দিন বাংলা বিভাগে ভর্তি শিক্ষার্থীরা।'; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                        <a href="<?php echo base_url('admission'); ?>" class="btn btn-sm text-white px-3 py-2 w-100" style="background-color: var(--primary-color); border-radius: 8px;">
                            <?php echo ($current_lang == 'en') ? 'View Department' : 'বিভাগ দেখুন'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Morning English -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="badge px-3 py-2" style="background-color: #fef3c7; color: #b45309; border-radius: 20px;">
                                <i class="fas fa-sun me-1"></i> <?php echo ($current_lang == 'en') ? 'Morning Shift' : 'সকাল শিফট'; ?>
                            </span>
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-2">
                            <?php echo ($current_lang == 'en') ? 'Morning English' : 'সকাল ইংরেজি'; ?>
                        </h4>
                        <p class="card-text text-muted mb-4">
                            <?php echo ($current_lang == 'en') ? 'Enrolled students in Morning English shift.' : 'সকাল ইংরেজি বিভাগে ভর্তি শিক্ষার্থীরা।'; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                        <a href="<?php echo base_url('admission'); ?>" class="btn btn-sm text-white px-3 py-2 w-100" style="background-color: var(--primary-color); border-radius: 8px;">
                            <?php echo ($current_lang == 'en') ? 'View Department' : 'বিভাগ দেখুন'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Day English -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="badge px-3 py-2" style="background-color: #fef3c7; color: #b45309; border-radius: 20px;">
                                <i class="fas fa-cloud-sun me-1"></i> <?php echo ($current_lang == 'en') ? 'Day Shift' : 'দিন শিফট'; ?>
                            </span>
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-2">
                            <?php echo ($current_lang == 'en') ? 'Day English' : 'দিন ইংরেজি'; ?>
                        </h4>
                        <p class="card-text text-muted mb-4">
                            <?php echo ($current_lang == 'en') ? 'Enrolled students in Day English shift.' : 'দিন ইংরেজি বিভাগে ভর্তি শিক্ষার্থীরা।'; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                        <a href="<?php echo base_url('admission'); ?>" class="btn btn-sm text-white px-3 py-2 w-100" style="background-color: var(--primary-color); border-radius: 8px;">
                            <?php echo ($current_lang == 'en') ? 'View Department' : 'বিভাগ দেখুন'; ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

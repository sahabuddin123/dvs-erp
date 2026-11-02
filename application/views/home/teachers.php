<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Teachers Information' : 'শিক্ষকদের তথ্য')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'শিক্ষকদের তথ্য');
$date_display = date('M d, Y');
?>

<!-- Page Header (Live Site Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Teachers Content -->
<section class="section bg-light my-5 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4" style="border: none; border-radius: 8px;">
                    <!-- Card Header -->
                    <div class="card-header bg-white py-3" style="border-bottom: 1px solid #edf2f7;">
                        <div class="d-md-flex justify-content-between align-items-center">
                            <h3 class="mb-md-0 mb-2 fw-bold" style="color: #1e293b; font-size: 1.5rem;"><?php echo html_escape($page_title); ?></h3>
                            <div>
                                <span class="badge text-white p-2 me-2" style="background-color: var(--primary-color) !important; font-size: 0.85rem;">
                                    <i class="fas fa-users me-1"></i>
                                </span>
                                <span class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?php echo $date_display; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5">
                        <?php if (empty($doctor_list)) { ?>
                            <div class="alert alert-info" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 6px;">
                                <?php echo ($current_lang == 'en') ? 'No teachers found.' : 'কোন শিক্ষক পাওয়া যায়নি।'; ?>
                            </div>
                        <?php } else { ?>
                            <!-- Teachers Grid -->
                            <div class="row g-4 mt-2">
                                <?php foreach ($doctor_list as $t) { 
                                    $t_photo = !empty($t['photo']) ? get_image_url('staff', $t['photo']) : base_url('uploads/app_image/default.png');
                                ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 shadow-sm border-0 member-card" style="border-radius: 8px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                            <div class="card-body text-center p-4 d-flex flex-column">
                                                <div class="mb-3 mx-auto" style="width: 100%; height: 200px; overflow: hidden; border-radius: 6px; background-color: #f8fafc;">
                                                    <img src="<?php echo $t_photo; ?>" alt="<?php echo html_escape($t['name']); ?>" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                                                </div>
                                                <h5 class="mb-1 fw-bold" style="color: #1e293b; font-size: 1.15rem;"><?php echo html_escape($t['name']); ?></h5>
                                                <p class="text-muted small mb-1"><?php echo html_escape(!empty($t['designation_name']) ? $t['designation_name'] : translate('teacher')); ?></p>
                                                <p class="small mb-3" style="color: var(--primary-color) !important; font-weight: 500;"><?php echo html_escape(!empty($t['department_name']) ? $t['department_name'] : ''); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <!-- Back Button -->
                        <div class="row <?php echo empty($doctor_list) ? 'mt-4' : 'mt-5'; ?>">
                            <div class="col-md-6 d-flex align-items-center">
                                <button onclick="history.go(-1)" class="btn btn-outline-primary" style="border-color: var(--primary-color); color: var(--primary-color);">
                                    <i class="fas fa-arrow-left me-2"></i> <?php echo ($current_lang == 'en') ? 'Back' : 'ফিরে যান'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.member-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}
</style>
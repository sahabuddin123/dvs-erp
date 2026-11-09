<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Staff' : 'কর্মকর্তা-কর্মচারী')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'কর্মকর্তা-কর্মচারী');
$content = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', ($current_lang == 'en' ? '<p>Staff member information is shown here. You can update page content dynamically.</p>' : '<p>স্টাফ সদস্যের তথ্য এখানে দেখানো হয়। আপনি পেজের বিষয়বস্তু ডাইনামিকভাবে আপডেট করতে পারেন।</p>')) : '<p>স্টাফ সদস্যের তথ্য এখানে দেখানো হয়। আপনি পেজের বিষয়বস্তু ডাইনামিকভাবে আপডেট করতে পারেন।</p>';
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
                    <i class="fas fa-user-group"></i>
                </div>
            </div>
        </div>
    </div>
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Staff Content -->
<section class="section bg-light my-5 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4" style="border: none; border-radius: 8px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="post-content mb-4" id="description" style="color: #4a4e57; font-size: 1.05rem; line-height: 1.7;">
                            <?php echo $content; ?>
                        </div>

                        <?php if (!empty($staff_list)) { ?>
                            <!-- Staff Grid -->
                            <div class="row g-4 mt-2">
                                <?php foreach ($staff_list as $s) { 
                                    $s_photo = !empty($s['photo']) ? get_image_url('staff', $s['photo']) : base_url('uploads/app_image/default.png');
                                ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 shadow-sm border-0 member-card" style="border-radius: 8px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                            <div class="card-body text-center p-4 d-flex flex-column">
                                                <div class="mb-3 mx-auto" style="width: 100%; height: 200px; overflow: hidden; border-radius: 6px; background-color: #f8fafc;">
                                                    <img src="<?php echo $s_photo; ?>" alt="<?php echo html_escape($s['name']); ?>" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                                                </div>
                                                <h5 class="mb-1 fw-bold" style="color: #1e293b; font-size: 1.15rem;"><?php echo html_escape($s['name']); ?></h5>
                                                <p class="text-muted small mb-1"><?php echo html_escape(!empty($s['designation_name']) ? $s['designation_name'] : (!empty($s['role_name']) ? $s['role_name'] : translate('staff'))); ?></p>
                                                <p class="small mb-3" style="color: var(--primary-color) !important; font-weight: 500;"><?php echo html_escape(!empty($s['department_name']) ? $s['department_name'] : ''); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

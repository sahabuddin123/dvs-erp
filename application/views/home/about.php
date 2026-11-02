<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$service = $this->db->get_where('front_cms_services', array('branch_id' => $branchID))->row_array(); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'About Us' : 'আমাদের সম্পর্কে')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'আমাদের সম্পর্কে');
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <?php if (!empty($page_data['title'])) { ?>
                    <p class="text-white-50 mb-0 mt-2"><?php echo html_escape($page_data['title']); ?></p>
                <?php } ?>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-school"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main About Content -->
<section class="section py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 col-sm-12">
                <?php if (!empty($page_data['title'])) { ?>
                    <h5 class="fw-bold mb-2" style="color: var(--primary-color);"><?php echo $page_data['title']; ?></h5>
                <?php } ?>
                <?php if (!empty($page_data['subtitle'])) { ?>
                    <h2 class="fw-bold mb-4" style="color: #1e293b;"><?php echo $page_data['subtitle']; ?></h2>
                <?php } ?>
                <div class="lead-text lh-lg text-muted" style="font-size: 1.05rem;">
                    <?php echo $page_data['content']; ?>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 text-center">
                <?php 
                $about_img = !empty($page_data['about_image']) ? base_url('uploads/frontend/about/' . $page_data['about_image']) : base_url('assets/frontend/dvs/img/slider/01.png');
                ?>
                <img src="<?php echo $about_img; ?>" alt="About School" class="img-fluid rounded shadow-sm" style="max-height: 450px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- About Featured Services Section -->
<?php if (!empty($service)) { ?>
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h5 class="fw-bold" style="color: var(--primary-color);"><?php echo $service['title']; ?></h5>
            <h2 class="fw-bold" style="color: #1e293b;"><?php echo $service['subtitle']; ?></h2>
        </div>
        <div class="row g-4">
            <?php
            $services_list = $this->db->where('branch_id', $branchID)->get('front_cms_services_list')->result_array();
            foreach ($services_list as $key => $value) {
            ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm border-0 p-4" style="border-radius: 10px;">
                    <div class="mb-3 text-success fs-1">
                        <i class="<?php echo $value['icon']; ?>"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: #1e293b;"><?php echo $value['title']; ?></h4>
                    <p class="text-muted mb-0"><?php echo $value['description']; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>

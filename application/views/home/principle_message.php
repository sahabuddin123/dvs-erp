<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$default_inst_name = !empty($global_config['institute_name']) ? $global_config['institute_name'] : (!empty($branch['school_name']) ? $branch['school_name'] : 'চর হাসান ইসলামিয়া দাখিল মাদরাসা');
$app_title = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', $default_inst_name) : $default_inst_name;
$default_address = !empty($global_config['address']) ? $global_config['address'] : (!empty($branch['address']) ? $branch['address'] : 'গ্রাম: চর হাসান, পো: পশ্চিম চর জব্বর, সুবর্ণচর, নোয়াখালী।');
$app_address = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'address', $default_address) : $default_address;

// Principal details (bilingual)
if ($current_lang == 'en') {
    $principal_name = 'Mawlana Md. Mokammel Hossain';
    $principal_designation = 'Superintendent';
    $heading_title = 'Message';
    $page_subtitle = 'Superintendent, Char Hasan Islamia Dakhil Madrasah';
} else {
    $principal_name = 'মাওলানা মোঃ মোকাম্মেল হোসেন';
    $principal_designation = 'সুপারিনটেনডেন্ট';
    $heading_title = 'বাণী';
    $page_subtitle = 'সুপারিনটেনডেন্ট, চর হাসান ইসলামিয়া দাখিল মাদরাসা';
}

// Image resolution
$img_src = base_url('uploads/frontend/home_page/super.jpg');
if (!file_exists(FCPATH . 'uploads/frontend/home_page/super.jpg')) {
    if (file_exists(FCPATH . 'uploads/frontend/images/super.jpg')) {
        $img_src = base_url('uploads/frontend/images/super.jpg');
    }
}

// Message resolution
$full_message = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', '') : (!empty($page_data['content']) ? $page_data['content'] : '');

// If full_message contains old raw grid wrapper, clean it up
if (strpos($full_message, 'col-md-6') !== false) {
    $clean_msg = preg_replace('/<div class="col-md-6">\s*<img[^>]*>\s*<\/div>/is', '', $full_message);
    $clean_msg = preg_replace('/<div class="container">\s*<div class="row">\s*<div class="col-md-6">/is', '', $clean_msg);
    $clean_msg = preg_replace('/<\/div>\s*<\/div>\s*<\/div>\s*$/is', '', trim($clean_msg));
    $full_message = trim($clean_msg);
}
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($principal_name); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-comment"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Content (Exact Match to 2nd Screenshot) -->
<div class="container my-5 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-5 border-0" style="border-radius: 12px; background: #fff;">
                <div class="card-body p-4 p-md-5">
                    <div class="row">
                        <!-- Left Profile Column -->
                        <div class="col-md-4 mb-4 mb-md-0 text-center">
                            <div class="text-center shadow p-4 rounded bg-white">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo html_escape($principal_name); ?>" 
                                    class="img-fluid rounded profile-image mb-3 shadow-sm" 
                                    style="max-width: 200px; width: 100%; height: auto; object-fit: cover; border-radius: 8px;">
                                <h4 class="fw-bold mb-1" style="color: #1e293b;"><?php echo html_escape($principal_name); ?></h4>
                                <p class="text-muted mt-1" style="padding-bottom: 2rem; line-height: 1.6;">
                                    <?php echo html_escape($principal_designation); ?><br>
                                    <?php echo html_escape($app_title); ?><br>
                                    <?php if (!empty($app_address)) { echo html_escape($app_address); } ?>
                                </p>
                            </div>
                        </div>

                        <!-- Right Message Column -->
                        <div class="col-md-8 ps-md-4">
                            <div class="message-content">
                                <div class="mb-4 text-md-start text-center">
                                    <h2 class="fw-bold" style="color: #1e293b;"><?php echo $heading_title; ?></h2>
                                </div>
                                <div class="text-justify lh-lg" style="color: #334155; font-size: 1.05rem; line-height: 1.85;">
                                    <?php echo $full_message; ?>
                                </div>
                                <div class="mt-4 pt-3 border-top">
                                    <a href="<?php echo base_url(); ?>" class="btn text-light" style="background-color: var(--primary-color); border-radius: 6px; font-weight: 500; padding: 7px 22px;">
                                        <i class="fas fa-arrow-left me-2"></i> <?php echo ($current_lang == 'en') ? 'Back' : 'ফিরে যান'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

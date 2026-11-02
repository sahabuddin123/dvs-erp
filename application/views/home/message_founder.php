<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';

// Resolve founder name and designation based on current language
if ($current_lang == 'en') {
    $founder_name = !empty($founder['name_en']) ? $founder['name_en'] : (!empty($founder['name']) ? $founder['name'] : 'Founder');
    $founder_designation = !empty($founder['surname_en']) ? $founder['surname_en'] : (!empty($founder['surname']) ? $founder['surname'] : 'Director');
} else {
    $founder_name = !empty($founder['name']) ? $founder['name'] : (!empty($founder['name_en']) ? $founder['name_en'] : 'পরিচালক');
    $founder_designation = !empty($founder['surname']) ? $founder['surname'] : (!empty($founder['surname_en']) ? $founder['surname_en'] : 'পরিচালক');
}

// Resolve Image
$img_src = base_url('assets/frontend/dvs/storage/founders/' . $founder['image']);
if (!empty($founder['image']) && file_exists(FCPATH . 'assets/frontend/dvs/storage/founders/' . $founder['image'])) {
    $img_src = base_url('assets/frontend/dvs/storage/founders/' . $founder['image']);
} elseif (!empty($founder['image']) && file_exists(FCPATH . 'uploads/frontend/testimonial/' . $founder['image'])) {
    $img_src = base_url('uploads/frontend/testimonial/' . $founder['image']);
}

// Resolve full message text directly from database (admin panel editable)
$full_message = function_exists('get_lang_field') ? get_lang_field($founder, 'description') : (!empty($founder['description']) ? $founder['description'] : '');
if (empty($full_message)) {
    $num_key = (string) $founder_num_id;
    if (!empty($messages_data[$current_lang][$num_key]['message'])) {
        $full_message = $messages_data[$current_lang][$num_key]['message'];
    } elseif (!empty($messages_data['bn'][$num_key]['message'])) {
        $full_message = $messages_data['bn'][$num_key]['message'];
    }
}
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($founder_name); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($founder_designation); ?></p>
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
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Content -->
<div class="container my-5 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-5 border-0" style="border-radius: 12px; background: #fff;">
                <div class="card-body p-4 p-md-5">
                    <div class="row">
                        <!-- Left Profile Column -->
                        <div class="col-md-4 mb-4 mb-md-0 text-center">
                            <div class="text-center shadow p-4 rounded bg-white">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo html_escape($founder_name); ?>" 
                                    class="img-fluid rounded profile-image mb-3" 
                                    style="max-width: 200px; width: 100%; height: auto; object-fit: cover;">
                                <h4 class="fw-bold mb-1" style="color: #1e293b;"><?php echo html_escape($founder_name); ?></h4>
                                <p class="text-muted mt-1" style="padding-bottom: 2rem; line-height: 1.6;">
                                    <?php echo html_escape($founder_designation); ?><br>
                                    <?php echo ($current_lang == 'en') ? 'Dream Valley School' : 'ড্রিম ভ্যালী স্কুল'; ?><br>
                                    <?php echo ($current_lang == 'en') ? 'Charbata, Subarnachar, Noakhali.' : 'চরবাটা, সুবর্ণচর, নোয়াখালী।'; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Right Message Column -->
                        <div class="col-md-8 ps-md-4">
                            <div class="message-content">
                                <div class="mb-4 text-md-start text-center">
                                    <h2 class="fw-bold" style="color: #1e293b;"><?php echo ($current_lang == 'en') ? 'Message' : 'বাণী'; ?></h2>
                                </div>
                                <div class="text-justify lh-lg" style="color: #334155; font-size: 1.05rem; line-height: 1.8;">
                                    <?php echo $full_message; ?>
                                </div>
                                <div class="mt-4 pt-3 border-top">
                                    <a href="<?php echo base_url('governing-body'); ?>" class="btn text-light" style="background-color: var(--dark-color); border-radius: 6px; font-weight: 500; padding: 7px 22px;">
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


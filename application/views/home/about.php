<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$service = $this->db->get_where('front_cms_services', array('branch_id' => $branchID))->row_array(); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'About Us' : 'আমাদের সম্পর্কে')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'আমাদের সম্পর্কে');

$about_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'title', ($current_lang == 'en' ? 'Welcome' : 'স্বাগতম')) : (!empty($page_data['title']) ? $page_data['title'] : 'স্বাগতম');
$about_subtitle = function_exists('get_lang_field') ? get_lang_field($page_data, 'subtitle', ($current_lang == 'en' ? 'Char Hasan Islamia Dakhil Madrasah' : 'চর হাসান ইসলামিয়া দাখিল মাদরাসা।')) : (!empty($page_data['subtitle']) ? $page_data['subtitle'] : 'চর হাসান ইসলামিয়া দাখিল মাদরাসা।');
$about_content = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', '') : (!empty($page_data['content']) ? $page_data['content'] : '');

// Safe image resolution
$about_img = base_url('uploads/frontend/about/about1.jpg');
if (!empty($page_data['about_image']) && file_exists(FCPATH . 'uploads/frontend/about/' . $page_data['about_image'])) {
    $about_img = base_url('uploads/frontend/about/' . $page_data['about_image']);
} elseif (file_exists(FCPATH . 'uploads/frontend/about/about1.png')) {
    $about_img = base_url('uploads/frontend/about/about1.png');
}
?>

<style>
    .about-header-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(var(--primary-rgb, 220, 33, 19), 0.08);
        color: var(--primary-color);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.88rem;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .about-image-wrapper {
        position: relative;
        display: inline-block;
        border-radius: 20px;
        overflow: visible;
    }
    .about-image-wrapper img {
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: transform 0.35s ease;
    }
    .about-image-wrapper:hover img {
        transform: translateY(-4px);
    }
    .about-floating-badge {
        position: absolute;
        bottom: -20px;
        left: -20px;
        background: #ffffff;
        padding: 14px 20px;
        border-radius: 14px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid var(--primary-color);
        z-index: 2;
    }
    @media (max-width: 767.98px) {
        .about-floating-badge {
            position: relative;
            bottom: auto;
            left: auto;
            margin-top: 15px;
            display: inline-flex;
        }
    }
    .pillar-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        padding: 28px 24px;
        height: 100%;
    }
    .pillar-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        border-color: rgba(var(--primary-rgb, 220, 33, 19), 0.3);
    }
    .pillar-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: rgba(var(--primary-rgb, 220, 33, 19), 0.08);
        color: var(--primary-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 18px;
        transition: all 0.3s ease;
    }
    .pillar-card:hover .pillar-icon-box {
        background: var(--primary-color);
        color: #ffffff;
        transform: rotate(6deg) scale(1.08);
    }
    .feature-check-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 1rem;
        color: #334155;
    }
    .about-cta-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .about-cta-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: var(--primary-color);
    }
</style>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="about-header-pill">
                    <i class="fas fa-university"></i> 
                    <?php echo ($current_lang == 'en') ? 'Institution Profile & History' : 'পরিচিতি ও পটভূমি'; ?>
                </div>
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="text-muted mb-0" style="font-size: 1.05rem;">
                    <?php echo ($current_lang == 'en') 
                        ? 'Committed to nurturing honest, knowledgeable, and patriotic citizens through Islamic and contemporary education.' 
                        : 'পবিত্র কুরআন-সুন্নাহর আলোকবর্তিকা ও আধুনিক শিক্ষার যুগোপযোগী মেলবন্ধনে এক অনন্য দ্বীনি শিক্ষাপ্রতিষ্ঠান।'; ?>
                </p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-school"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main About Content -->
<section class="section py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Left Text Column -->
            <div class="col-lg-6 col-sm-12">
                <div class="pe-lg-3">
                    <?php if (!empty($about_title)): ?>
                        <span class="badge rounded-pill bg-light text-primary px-3 py-2 fw-semibold mb-3 border">
                            <i class="fas fa-star text-warning me-1"></i> <?php echo html_escape($about_title); ?>
                        </span>
                    <?php endif; ?>

                    <h2 class="fw-bold mb-3" style="color: #0f172a; font-size: 2.15rem; line-height: 1.35;">
                        <?php echo html_escape($about_subtitle); ?>
                    </h2>

                    <div class="lead-text text-muted mb-4" style="font-size: 1.05rem; line-height: 1.9; text-align: justify; color: #334155 !important;">
                        <?php echo $about_content; ?>
                    </div>

                    <!-- Checklist Features -->
                    <div class="row g-2 mb-4">
                        <div class="col-sm-6">
                            <div class="feature-check-item">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <span><?php echo ($current_lang == 'en') ? 'Integrated Curriculum' : 'দ্বীনি ও আধুনিক সমন্বিত পাঠ্যক্রম'; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-check-item">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <span><?php echo ($current_lang == 'en') ? 'Serene & Safe Campus' : 'কোলাহলমুক্ত সবুজ ও নিরাপদ পরিবেশ'; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-check-item">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <span><?php echo ($current_lang == 'en') ? 'Experienced Faculty' : 'অভিজ্ঞ ও নিবেদিত শিক্ষকবৃন্দ'; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-check-item">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <span><?php echo ($current_lang == 'en') ? 'Moral Character Building' : 'নৈতিক চরিত্র ও সহশিক্ষা কার্যক্রম'; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="<?php echo base_url('history'); ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                            <i class="fas fa-book-open me-2"></i> <?php echo ($current_lang == 'en') ? 'Read Madrasah History' : 'মাদ্রাসার পূর্ণাঙ্গ ইতিহাস'; ?>
                        </a>
                        <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold">
                            <i class="fas fa-envelope me-2"></i> <?php echo ($current_lang == 'en') ? 'Contact Us' : 'যোগাযোগ করুন'; ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Image Column -->
            <div class="col-lg-6 col-sm-12 text-center">
                <div class="about-image-wrapper">
                    <img src="<?php echo $about_img; ?>" 
                         alt="<?php echo html_escape($about_subtitle); ?>" 
                         class="img-fluid" 
                         onerror="this.onerror=null;this.src='<?php echo base_url('uploads/frontend/about/about1.png'); ?>';" 
                         style="max-height: 460px; width: 100%; object-fit: cover;">
                    <div class="about-floating-badge">
                        <div style="font-size: 2rem; color: #f59e0b;">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold text-dark" style="font-size: 1.1rem;">
                                <?php echo ($current_lang == 'en') ? 'Est. 1979' : 'প্রতিষ্ঠিত ১৯৭৯'; ?>
                            </div>
                            <small class="text-muted">
                                <?php echo ($current_lang == 'en') ? 'MPO Enlisted & Recognized' : 'এমপিওভুক্ত ও সুনামধন্য প্রতিষ্ঠান'; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4 Core Pillars Section -->
<section class="py-5" style="background-color: #f8fafc; border-top: 1px solid #edf2f7; border-bottom: 1px solid #edf2f7;">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="badge rounded-pill bg-white text-primary px-3 py-2 fw-semibold mb-2 border shadow-sm">
                <i class="fas fa-bullseye me-1"></i> <?php echo ($current_lang == 'en') ? 'Our Core Foundations' : 'আমাদের মূল লক্ষ্য ও বৈশিষ্ট্যসমূহ'; ?>
            </span>
            <h2 class="fw-bold" style="color: #0f172a; font-size: 1.85rem;">
                <?php echo ($current_lang == 'en') ? 'Why Choose Char Hasan Islamia Dakhil Madrasah' : 'কেন চর হাসান ইসলামিয়া দাখিল মাদরাসা অনন্য'; ?>
            </h2>
        </div>

        <div class="row g-4">
            <!-- Pillar 1 -->
            <div class="col-lg-3 col-md-6">
                <div class="pillar-card text-center">
                    <div class="pillar-icon-box mx-auto">
                        <i class="fas fa-quran"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2"><?php echo ($current_lang == 'en') ? 'Authentic Islamic Pedagogy' : 'সহীহ দ্বীনি শিক্ষা'; ?></h5>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        <?php echo ($current_lang == 'en') 
                            ? 'Rooted in the Quran and Sunnah to nurture deep moral responsibility, honesty, and piety.' 
                            : 'পবিত্র কুরআন ও সুন্নাহর আলোকে শিক্ষার্থীদের নৈতিক মূল্যবোধ, সততা, শৃঙ্খলা ও তাকওয়া শিক্ষা প্রদান।'; ?>
                    </p>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="col-lg-3 col-md-6">
                <div class="pillar-card text-center">
                    <div class="pillar-icon-box mx-auto">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2"><?php echo ($current_lang == 'en') ? 'Modern & Tech Education' : 'আধুনিক ও তথ্যপ্রযুক্তি'; ?></h5>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        <?php echo ($current_lang == 'en') 
                            ? 'Empowering students with ICT, science, and analytical thinking to thrive in the modern era.' 
                            : 'তথ্যপ্রযুক্তি, বিজ্ঞান ও আধুনিক জ্ঞান অর্জনে শিক্ষার্থীদের বিশ্বমানের সমোপযোগী দক্ষতায় গড়ে তোলা।'; ?>
                    </p>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="col-lg-3 col-md-6">
                <div class="pillar-card text-center">
                    <div class="pillar-icon-box mx-auto">
                        <i class="fas fa-tree"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2"><?php echo ($current_lang == 'en') ? 'Serene Campus Setting' : 'মনোরম ও নিরাপদ ক্যাম্পাস'; ?></h5>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        <?php echo ($current_lang == 'en') 
                            ? 'A lush green, safe, and undisturbed natural environment ideal for learning and growth.' 
                            : 'নোয়াখালীর সুবর্ণচরে শান্ত, সবুজ-শ্যামল, কোলাহলমুক্ত ও সর্ব্বোচ্চ শিক্ষাবান্ধব নিরাপদ পরিবেশ।'; ?>
                    </p>
                </div>
            </div>

            <!-- Pillar 4 -->
            <div class="col-lg-3 col-md-6">
                <div class="pillar-card text-center">
                    <div class="pillar-icon-box mx-auto">
                        <i class="fas fa-award"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2"><?php echo ($current_lang == 'en') ? 'Excellence & Recognition' : 'সাফল্য ও স্বীকৃতি'; ?></h5>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        <?php echo ($current_lang == 'en') 
                            ? 'MPO-enlisted and highly reputable institution dedicated to consistent academic achievement.' 
                            : 'বাংলাদেশ সরকার কর্তৃক এমপিওভুক্ত ও জাতীয় পর্যায়ে প্রশংসিত ঐতিহ্যবাহী শিক্ষা প্রতিষ্ঠান।'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Featured Services Section -->
<?php if (!empty($service)): ?>
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="section-title text-center mb-5">
            <h5 class="fw-bold" style="color: var(--primary-color);"><?php echo html_escape(get_lang_field($service, 'title')); ?></h5>
            <h2 class="fw-bold" style="color: #0f172a; font-size: 1.85rem;"><?php echo html_escape(get_lang_field($service, 'subtitle')); ?></h2>
        </div>
        <div class="row g-4">
            <?php
            $services_list = $this->db->where('branch_id', $branchID)->get('front_cms_services_list')->result_array();
            foreach ($services_list as $key => $value):
            ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm border-0 p-4" style="border-radius: 14px; background: #ffffff; border: 1px solid #edf2f7 !important; transition: transform 0.25s ease;">
                    <div class="mb-3 fs-1" style="color: var(--primary-color);">
                        <i class="<?php echo $value['icon']; ?>"></i>
                    </div>
                    <h4 class="fw-bold mb-2 text-dark" style="font-size: 1.15rem;"><?php echo html_escape(get_lang_field($value, 'title')); ?></h4>
                    <p class="text-muted mb-0" style="line-height: 1.7; font-size: 0.95rem;"><?php echo html_escape(get_lang_field($value, 'description')); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Quick Admission / Contact CTA Banner -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="about-cta-banner p-4 p-md-5 shadow-lg">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="fas fa-graduation-cap text-primary me-1"></i> 
                        <?php echo ($current_lang == 'en') ? 'Admission Open' : 'নতুন শিক্ষাবর্ষে ভর্তি চলছে'; ?>
                    </span>
                    <h3 class="fw-bold text-white mb-2">
                        <?php echo ($current_lang == 'en') ? 'Build a Bright Future with Religious & Modern Education' : 'আপনার সন্তানের দ্বীনি ও আধুনিক শিক্ষার সোনালী ভবিষ্যৎ নিশ্চিত করুন'; ?>
                    </h3>
                    <p class="text-white-50 mb-0" style="font-size: 1rem;">
                        <?php echo ($current_lang == 'en') 
                            ? 'Apply online today or visit our madrasah campus for direct admission guidance.' 
                            : 'ঘরে বসেই অনলাইনের মাধ্যমে আবেদন করুন অথবা সরাসরি আমাদের ক্যাম্পাসে এসে যোগাযোগ করুন।'; ?>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="<?php echo base_url('admission'); ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                            <i class="fas fa-pencil-alt me-1"></i> <?php echo ($current_lang == 'en') ? 'Apply Online' : 'অনলাইন ভর্তি আবেদন'; ?>
                        </a>
                        <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                            <i class="fas fa-phone-alt me-1"></i> <?php echo ($current_lang == 'en') ? 'Contact Office' : 'অফিসে যোগাযোগ'; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

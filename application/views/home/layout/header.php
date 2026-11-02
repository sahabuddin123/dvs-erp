<?php 
$current_lang = get_current_lang();
$app_title = get_lang_field($cms_setting, 'application_title', 'ড্রিম ভ্যালী স্কুল');
$app_address = get_lang_field($cms_setting, 'address', 'চরবাটা, সুবর্ণচর, নোয়াখালী');
?>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 d-none d-md-block">
                <div class="contact-info">
                    <span><i class="fas fa-phone"></i> <?php echo !empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : '+880 1845 746454'; ?></span>
                    <span class="ms-4"><i class="fas fa-envelope"></i> <?php echo !empty($cms_setting['email']) ? $cms_setting['email'] : 'info@dreamvalleyschool.com'; ?></span>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-flex justify-content-end align-items-center">
                <div class="school-info d-inline-flex align-items-center">
                    <span class="ms-4"><i class="fas fa-calendar"></i> Founded: 2026</span>
                </div>
                <!-- Language Switcher Dropdown (Green Pill Button) -->
                <div class="topbar-lang-dropdown d-inline-block ms-4">
                    <button class="btn btn-sm dropdown-toggle text-white" type="button" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: var(--primary-color) !important; border-radius: 50px; padding: 0.35rem 0.9rem; font-size: 0.82rem; border: none; font-weight: 500;">
                        <?php echo ($current_lang == 'en') ? 'English' : 'বাংলা'; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="langDropdown">
                        <li>
                            <a class="dropdown-item <?php echo ($current_lang == 'bn') ? 'active' : ''; ?>" href="<?php echo base_url('home/lang/bn'); ?>">
                                বাংলা
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo ($current_lang == 'en') ? 'active' : ''; ?>" href="<?php echo base_url('home/lang/en'); ?>">
                                English
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 d-flex d-md-none justify-content-between align-items-center topbar-mobile-row">
                <div class="contact-info mobile-topbar-contact">
                    <span><i class="fas fa-phone"></i> <?php echo !empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : '+880 1845 746454'; ?></span>
                </div>
                <div class="mobile-lang-switcher btn-group" role="group" aria-label="Language switcher">
                    <a href="<?php echo base_url('home/lang/bn'); ?>" class="btn btn-sm lang-btn <?php echo ($current_lang == 'bn') ? 'active' : ''; ?>" style="font-size: 11px; padding: 2px 8px;">বাংলা</a>
                    <a href="<?php echo base_url('home/lang/en'); ?>" class="btn btn-sm lang-btn <?php echo ($current_lang == 'en') ? 'active' : ''; ?>" style="font-size: 11px; padding: 2px 8px;">EN</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main School Header -->
<div class="school-header d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="logo-section d-flex align-items-center">
                   <a href="<?php echo base_url(); ?>" class="me-3"> 
                        <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/settings/logo_light_1773127288.png'); ?>" alt="Logo" style="max-height: 80px;">
                    </a>
                    <div class="school-info">
                        <h1 style="color: var(--primary-color); font-weight: 800; font-size: 1.85rem; margin-bottom: 2px;">
                            <?php echo $app_title; ?>
                        </h1>
                        <p class="motto mb-0" style="color: #6b7280; font-size: 13px; font-style: italic; line-height: 1.4; margin-top: 2px;">
                            <?php 
                            if ($current_lang == 'en') {
                                echo 'Where dreams take wing in knowledge,<br>hearts blossom in the light of Quran';
                            } else {
                                echo 'স্বপ্ন ডানা মেলুক জ্ঞানের আলোয়,<br>হৃদয় বিকশিত হোক কুরআনের আলোয়';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <div class="quick-buttons">
                    <button class="btn-quick" onclick="location.href = '<?php echo base_url('message-founder/1'); ?>'">
                        <i class="fas fa-user-tie me-1"></i> <?php echo translate('directors_message'); ?>
                    </button>
                    <button class="btn-quick ms-2" onclick="location.href = '<?php echo base_url('career'); ?>'">
                        <i class="fas fa-briefcase me-1"></i> <?php echo translate('career'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notice Marquee Bar -->
<div class="notice-bar" style="background: var(--primary-color)">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="notice-label" style="font-weight: 700; white-space: nowrap; color: #fff; padding-right: 20px;">
                🔔 <?php echo translate('notice'); ?>:
            </div>
            <div class="marquee-container" style="flex: 1; overflow: hidden;">
                <div class="marquee">
                    <?php 
                    $ticker_notices = $this->db->order_by('id', 'desc')->limit(10)->get('front_cms_news_list')->result_array();
                    if (!empty($ticker_notices)) {
                        foreach ($ticker_notices as $t_notice) { 
                            $notice_title = get_lang_field($t_notice, 'title');
                    ?>
                        <span class="marquee-item"><a href="<?php echo base_url('events'); ?>" class="text-light" style="text-decoration: none;"><?php echo html_escape($notice_title); ?></a></span>
                    <?php }
                    } else { ?>
                        <span class="marquee-item text-light"><?php echo ($current_lang == 'en') ? 'Welcome to Dream Valley School' : 'ড্রিম ভ্যালী স্কুলে আপনাকে স্বাগতম'; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-lg-none" href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/settings/logo_light_1773127288.png'); ?>" alt="Logo" height="40" class="me-2">
            <span style="font-weight: 700; color: var(--primary-color); font-size: 1.1rem;"><?php echo $app_title; ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar"
            aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse d-none d-lg-block" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == '' || uri_string() == 'home') ? 'active' : ''; ?>" href="<?php echo base_url(); ?>">
                        <i class="fas fa-home me-1"></i> <?php echo translate('home'); ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo (strpos(uri_string(), 'about') !== false || strpos(uri_string(), 'history') !== false || strpos(uri_string(), 'governing') !== false || strpos(uri_string(), 'teachers') !== false || strpos(uri_string(), 'staff') !== false) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-info-circle me-1"></i> <?php echo translate('about_us'); ?>
                    </a>
                    <ul class="dropdown-menu shadow border-0">
                        <li><a class="dropdown-item" href="<?php echo base_url('history'); ?>"><i class="fas fa-landmark me-2 text-success"></i> <?php echo translate('institution_history'); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('governing-body'); ?>"><i class="fas fa-users-cog me-2 text-success"></i> <?php echo translate('governing_body'); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('teachers'); ?>"><i class="fas fa-chalkboard-teacher me-2 text-success"></i> <?php echo translate('teachers_information'); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('staff'); ?>"><i class="fas fa-user-group me-2 text-success"></i> <?php echo translate('staff_information'); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo (strpos(uri_string(), 'exam_results') !== false || strpos(uri_string(), 'routine') !== false) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-graduation-cap me-1"></i> <?php echo translate('academic'); ?>
                    </a>
                    <ul class="dropdown-menu shadow border-0">
                        <li><a class="dropdown-item" href="<?php echo base_url('events'); ?>"><i class="fas fa-calendar-alt me-2 text-success"></i> <?php echo translate('academic_calendar'); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('exam_results'); ?>"><i class="fas fa-award me-2 text-success"></i> <?php echo translate('exam_results'); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('about'); ?>"><i class="fas fa-clock me-2 text-success"></i> <?php echo translate('class_routine'); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos(uri_string(), 'notice') !== false || strpos(uri_string(), 'news') !== false) ? 'active' : ''; ?>" href="<?php echo base_url('notice'); ?>">
                        <i class="fas fa-bell me-1"></i> <?php echo translate('notice_board'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos(uri_string(), 'student') !== false) ? 'active' : ''; ?>" href="<?php echo base_url('students'); ?>">
                        <i class="fas fa-graduation-cap me-1"></i> <?php echo translate('student_information'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos(uri_string(), 'admission') !== false) ? 'active' : ''; ?>" href="<?php echo base_url('admission'); ?>">
                        <i class="fas fa-file-signature me-1"></i> <?php echo translate('online_admission'); ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo (strpos(uri_string(), 'gallery') !== false) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-images me-1"></i> <?php echo translate('gallery'); ?>
                    </a>
                    <ul class="dropdown-menu shadow border-0">
                        <li><a class="dropdown-item" href="<?php echo base_url('gallery'); ?>"><i class="fas fa-image me-2 text-success"></i> <?php echo translate('photo_gallery'); ?></a></li>
                        <li><a class="dropdown-item" href="#video-gallery-section"><i class="fas fa-video me-2 text-success"></i> <?php echo translate('video_gallery'); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos(uri_string(), 'contact') !== false) ? 'active' : ''; ?>" href="<?php echo base_url('contact'); ?>">
                        <i class="fas fa-phone me-1"></i> <?php echo translate('contact'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Offcanvas Menu -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileNavbar" aria-labelledby="mobileNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileNavbarLabel" style="font-weight: 700; color: var(--primary-color);">
                <i class="fas fa-bars me-2"></i> <?php echo translate('menu'); ?>
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url(); ?>"><i class="fas fa-home me-2"></i> <?php echo translate('home'); ?></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#mobileAbout" aria-expanded="false">
                        <i class="fas fa-info-circle me-2"></i> <?php echo translate('about_us'); ?>
                    </a>
                    <div class="collapse" id="mobileAbout">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('history'); ?>"><i class="fas fa-landmark me-2"></i> <?php echo translate('institution_history'); ?></a></li>
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('governing-body'); ?>"><i class="fas fa-users-cog me-2"></i> <?php echo translate('governing_body'); ?></a></li>
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('teachers'); ?>"><i class="fas fa-chalkboard-user me-2"></i> <?php echo translate('teachers_information'); ?></a></li>
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('staff'); ?>"><i class="fas fa-user-group me-2"></i> <?php echo translate('staff_information'); ?></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#mobileAcademic" aria-expanded="false">
                        <i class="fas fa-book me-2"></i> <?php echo translate('academic'); ?>
                    </a>
                    <div class="collapse" id="mobileAcademic">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('events'); ?>"><i class="fas fa-calendar-alt me-2"></i> <?php echo translate('academic_calendar'); ?></a></li>
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('exam_results'); ?>"><i class="fas fa-award me-2"></i> <?php echo translate('exam_results'); ?></a></li>
                            <li class="nav-item"><a class="nav-link py-1" href="<?php echo base_url('about'); ?>"><i class="fas fa-clock me-2"></i> <?php echo translate('class_routine'); ?></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('notice'); ?>"><i class="fas fa-bell me-2"></i> <?php echo translate('notice_board'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('students'); ?>"><i class="fas fa-graduation-cap me-2"></i> <?php echo translate('student_information'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admission'); ?>"><i class="fas fa-file-signature me-2"></i> <?php echo translate('online_admission'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('gallery'); ?>"><i class="fas fa-images me-2"></i> <?php echo translate('gallery'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('contact'); ?>"><i class="fas fa-phone me-2"></i> <?php echo translate('contact'); ?></a>
                </li>
                <li class="nav-item mt-3 pt-3 border-top">
                    <?php if (!is_loggedin()) { ?>
                        <a class="btn btn-primary w-100" style="background: var(--primary-color); border: none;" href="<?php echo base_url('authentication'); ?>">
                            <i class="fas fa-user-lock me-2"></i> <?php echo translate('login'); ?>
                        </a>
                    <?php } else { ?>
                        <a class="btn btn-primary w-100" style="background: var(--primary-color); border: none;" href="<?php echo base_url('dashboard'); ?>">
                            <i class="fas fa-tachometer-alt me-2"></i> <?php echo translate('dashboard'); ?>
                        </a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
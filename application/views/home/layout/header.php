<?php 
$current_lang = get_current_lang();
$default_name = !empty($global_config['institute_name']) ? $global_config['institute_name'] : (!empty($branch['school_name']) ? $branch['school_name'] : 'School');
$app_title = get_lang_field($cms_setting, 'application_title', $default_name);
$app_address = get_lang_field($cms_setting, 'address', (!empty($global_config['address']) ? $global_config['address'] : (!empty($branch['address']) ? $branch['address'] : '')));
$app_phone = !empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : (!empty($global_config['mobileno']) ? $global_config['mobileno'] : (!empty($branch['mobileno']) ? $branch['mobileno'] : ''));
$app_email = !empty($cms_setting['email']) ? $cms_setting['email'] : (!empty($global_config['institute_email']) ? $global_config['institute_email'] : (!empty($branch['email']) ? $branch['email'] : ''));
?>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 d-none d-md-block">
                <div class="contact-info">
                    <?php if (!empty($app_phone)) { ?>
                        <span><i class="fas fa-phone"></i> <?php echo html_escape($app_phone); ?></span>
                    <?php } ?>
                    <?php if (!empty($app_email)) { ?>
                        <span class="ms-4"><i class="fas fa-envelope"></i> <?php echo html_escape($app_email); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-flex justify-content-end align-items-center">
                <div class="school-info d-inline-flex align-items-center">
                    <span class="ms-4"><i class="fas fa-calendar-alt"></i> <?php echo translate('academic_year'); ?>: <?php echo date('Y'); ?></span>
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
                    <?php if (!empty($app_phone)) { ?>
                        <span><i class="fas fa-phone"></i> <?php echo html_escape($app_phone); ?></span>
                    <?php } ?>
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
                        <img src="<?php echo get_frontend_logo_url('light'); ?>" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/logo.png'); ?>';" alt="Logo" style="max-height: 80px;">
                    </a>
                    <div class="school-info">
                        <h1 style="color: var(--primary-color); font-weight: 800; font-size: 1.85rem; margin-bottom: 2px;">
                            <?php echo html_escape($app_title); ?>
                        </h1>
                        <?php 
                        $tagline = get_lang_field($cms_setting, 'site_tagline');
                        if (!empty($tagline)):
                        ?>
                        <p class="motto mb-0" style="color: #6b7280; font-size: 13px; font-style: italic; line-height: 1.4; margin-top: 2px;">
                            <?php echo $tagline; ?>
                        </p>
                        <?php endif; ?>
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

<?php 
$header_branchID = isset($branchID) ? $branchID : (isset($cms_setting['branch_id']) ? $cms_setting['branch_id'] : (isset($this->home_model) ? $this->home_model->getDefaultBranch() : 1));
$ticker_notices = $this->db->where('show_web', 1)
    ->group_start()
        ->where('branch_id', $header_branchID)
        ->or_where('branch_id', 0)
    ->group_end()
    ->order_by('id', 'desc')
    ->limit(10)
    ->get('front_cms_news_list')
    ->result_array();

if (empty($ticker_notices)) {
    $ticker_notices = $this->db->where('show_web', 1)->order_by('id', 'desc')->limit(10)->get('front_cms_news_list')->result_array();
}
?>
<?php if (!empty($ticker_notices)): ?>
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
                    foreach ($ticker_notices as $t_notice) { 
                        $notice_title = get_lang_field($t_notice, 'title');
                        $notice_ticker_url = !empty($t_notice['alias']) ? base_url('notice-details/' . $t_notice['alias']) : base_url('notice-details/' . $t_notice['id']);
                    ?>
                        <span class="marquee-item"><a href="<?php echo $notice_ticker_url; ?>" class="text-light" style="text-decoration: none;"><?php echo html_escape($notice_title); ?></a></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
if (!function_exists('get_dvs_menu_icon')) {
    function get_dvs_menu_icon($alias, $title = '') {
        $key = strtolower(trim((string)$alias));
        $title_lower = mb_strtolower(trim((string)$title), 'UTF-8');
        
        $map = array(
            '' => 'fas fa-home',
            'home' => 'fas fa-home',
            'about' => 'fas fa-info-circle',
            'teachers' => 'fas fa-chalkboard-teacher',
            'history' => 'fas fa-landmark',
            'governing-body' => 'fas fa-users-cog',
            'governing_body' => 'fas fa-users-cog',
            'staff' => 'fas fa-user-group',
            'faq' => 'fas fa-question-circle',
            'principle-message' => 'fas fa-user-tie',
            'recognition' => 'fas fa-award',
            'events' => 'fas fa-calendar-alt',
            'achievements' => 'fas fa-trophy',
            'exam-routine' => 'fas fa-calendar-check',
            'class-routine' => 'fas fa-clock',
            'academic-record' => 'fas fa-clipboard-list',
            'admission' => 'fas fa-file-signature',
            'gallery' => 'fas fa-images',
            'contact' => 'fas fa-phone',
            'admit_card' => 'fas fa-id-card',
            'admit-card' => 'fas fa-id-card',
            'exam_results' => 'fas fa-poll',
            'certificates' => 'fas fa-certificate',
            'certificate' => 'fas fa-certificate',
            'news' => 'fas fa-bell',
            'notice' => 'fas fa-bell',
            'students' => 'fas fa-graduation-cap',
        );
        
        if (isset($map[$key])) {
            return $map[$key];
        }
        
        if (mb_strpos($title_lower, 'হোম') !== false) return 'fas fa-home';
        if (mb_strpos($title_lower, 'সম্পর্কে') !== false) return 'fas fa-info-circle';
        if (mb_strpos($title_lower, 'ইতিহাস') !== false) return 'fas fa-landmark';
        if (mb_strpos($title_lower, 'পরিচালক') !== false || mb_strpos($title_lower, 'কমিটি') !== false) return 'fas fa-users-cog';
        if (mb_strpos($title_lower, 'শিক্ষক') !== false) return 'fas fa-chalkboard-teacher';
        if (mb_strpos($title_lower, 'কর্মকর্তা') !== false || mb_strpos($title_lower, 'কর্মচারী') !== false) return 'fas fa-user-group';
        if (mb_strpos($title_lower, 'বার্তা') !== false) return 'fas fa-user-tie';
        if (mb_strpos($title_lower, 'স্বীকৃতি') !== false) return 'fas fa-award';
        if (mb_strpos($title_lower, 'রুটিন') !== false) return 'fas fa-calendar-check';
        if (mb_strpos($title_lower, 'অর্জন') !== false) return 'fas fa-trophy';
        if (mb_strpos($title_lower, 'ইভেন্ট') !== false) return 'fas fa-calendar-alt';
        if (mb_strpos($title_lower, 'সনদ') !== false || mb_strpos($title_lower, 'প্রশংসাপত্র') !== false) return 'fas fa-certificate';
        if (mb_strpos($title_lower, 'ভর্তি') !== false) return 'fas fa-file-signature';
        if (mb_strpos($title_lower, 'গ্যালারি') !== false || mb_strpos($title_lower, 'গ্যালারী') !== false) return 'fas fa-images';
        if (mb_strpos($title_lower, 'যোগাযোগ') !== false) return 'fas fa-phone';
        if (mb_strpos($title_lower, 'প্রবেশপত্র') !== false) return 'fas fa-id-card';
        if (mb_strpos($title_lower, 'ফলাফল') !== false) return 'fas fa-poll';
        if (mb_strpos($title_lower, 'নোটিশ') !== false) return 'fas fa-bell';
        if (mb_strpos($title_lower, 'শিক্ষার্থী') !== false || mb_strpos($title_lower, 'ছাত্র') !== false) return 'fas fa-graduation-cap';
        if (mb_strpos($title_lower, 'প্রশ্ন') !== false) return 'fas fa-question-circle';
        
        return 'fas fa-circle-chevron-right';
    }
}

// Ensure $main_menu is loaded
if (empty($main_menu)) {
    $header_branchID = isset($branchID) ? $branchID : (isset($cms_setting['branch_id']) ? $cms_setting['branch_id'] : $this->home_model->getDefaultBranch());
    $main_menu = $this->home_model->menuList('', $header_branchID);
}

// Helper to determine active state
$current_uri = trim(uri_string(), '/');
if (substr($current_uri, 0, 4) === 'cms/') {
    $current_uri = substr($current_uri, 4);
}
?>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-lg-none" href="<?php echo base_url(); ?>">
            <img src="<?php echo get_frontend_logo_url('light'); ?>" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/logo.png'); ?>';" alt="Logo" height="40" class="me-2">
            <span style="font-weight: 700; color: var(--primary-color); font-size: 1.1rem;"><?php echo html_escape($app_title); ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar"
            aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse d-none d-lg-block" id="navbarNav">
            <ul class="navbar-nav mx-auto align-items-center">
                <?php if (!empty($main_menu)): foreach ($main_menu as $item): 
                    $has_sub = !empty($item['submenu']);
                    $item_title = get_lang_field($item, 'title');
                    $item_icon = get_dvs_menu_icon($item['alias'] ?? '', $item_title);
                    
                    // Determine active state
                    $is_item_active = false;
                    if (empty($item['alias'])) {
                        $is_item_active = ($current_uri === '' || $current_uri === 'home' || $current_uri === 'cms');
                    } else {
                        $is_item_active = ($current_uri === $item['alias'] || strpos($current_uri, $item['alias']) !== false);
                    }
                    if ($has_sub) {
                        foreach ($item['submenu'] as $s_check) {
                            if (!empty($s_check['alias']) && ($current_uri === $s_check['alias'] || strpos($current_uri, $s_check['alias']) !== false)) {
                                $is_item_active = true;
                                break;
                            }
                        }
                    }
                    $active_class = $is_item_active ? 'active' : '';
                    $target = !empty($item['open_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '';
                ?>
                    <?php if ($has_sub): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo $active_class; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?php echo $item_icon; ?> me-1"></i> <?php echo html_escape($item_title); ?>
                            </a>
                            <ul class="dropdown-menu shadow border-0">
                                <?php foreach ($item['submenu'] as $sub): 
                                    $sub_title = get_lang_field($sub, 'title');
                                    $sub_icon = get_dvs_menu_icon($sub['alias'] ?? '', $sub_title);
                                    $sub_active = (!empty($sub['alias']) && ($current_uri === $sub['alias'] || strpos($current_uri, $sub['alias']) !== false)) ? 'active' : '';
                                    $sub_target = !empty($sub['open_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '';
                                ?>
                                    <li>
                                        <a class="dropdown-item <?php echo $sub_active; ?>" href="<?php echo $sub['url']; ?>" <?php echo $sub_target; ?>>
                                            <i class="<?php echo $sub_icon; ?> me-2 text-success"></i> <?php echo html_escape($sub_title); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_class; ?>" href="<?php echo $item['url']; ?>" <?php echo $target; ?>>
                                <i class="<?php echo $item_icon; ?> me-1"></i> <?php echo html_escape($item_title); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; endif; ?>
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
                <?php if (!empty($main_menu)): foreach ($main_menu as $item): 
                    $has_sub = !empty($item['submenu']);
                    $item_title = get_lang_field($item, 'title');
                    $item_icon = get_dvs_menu_icon($item['alias'] ?? '', $item_title);
                    
                    $is_item_active = false;
                    if (empty($item['alias'])) {
                        $is_item_active = ($current_uri === '' || $current_uri === 'home' || $current_uri === 'cms');
                    } else {
                        $is_item_active = ($current_uri === $item['alias'] || strpos($current_uri, $item['alias']) !== false);
                    }
                    if ($has_sub) {
                        foreach ($item['submenu'] as $s_check) {
                            if (!empty($s_check['alias']) && ($current_uri === $s_check['alias'] || strpos($current_uri, $s_check['alias']) !== false)) {
                                $is_item_active = true;
                                break;
                            }
                        }
                    }
                    $active_class = $is_item_active ? 'active' : '';
                    $target = !empty($item['open_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '';
                ?>
                    <?php if ($has_sub): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo $active_class; ?>" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#mobileSub_<?php echo $item['id']; ?>" aria-expanded="<?php echo $is_item_active ? 'true' : 'false'; ?>">
                                <i class="<?php echo $item_icon; ?> me-2"></i> <?php echo html_escape($item_title); ?>
                            </a>
                            <div class="collapse <?php echo $is_item_active ? 'show' : ''; ?>" id="mobileSub_<?php echo $item['id']; ?>">
                                <ul class="nav flex-column ms-3">
                                    <?php foreach ($item['submenu'] as $sub): 
                                        $sub_title = get_lang_field($sub, 'title');
                                        $sub_icon = get_dvs_menu_icon($sub['alias'] ?? '', $sub_title);
                                        $sub_active = (!empty($sub['alias']) && ($current_uri === $sub['alias'] || strpos($current_uri, $sub['alias']) !== false)) ? 'active' : '';
                                        $sub_target = !empty($sub['open_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '';
                                    ?>
                                        <li class="nav-item">
                                            <a class="nav-link py-1 <?php echo $sub_active; ?>" href="<?php echo $sub['url']; ?>" <?php echo $sub_target; ?>>
                                                <i class="<?php echo $sub_icon; ?> me-2 text-success"></i> <?php echo html_escape($sub_title); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_class; ?>" href="<?php echo $item['url']; ?>" <?php echo $target; ?>>
                                <i class="<?php echo $item_icon; ?> me-2"></i> <?php echo html_escape($item_title); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; endif; ?>

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

<style>
.navbar-nav {
    flex-wrap: wrap;
    justify-content: center;
    gap: 2px 4px;
}
.navbar .nav-link {
    font-size: 0.94rem;
    font-weight: 600;
    padding: 0.45rem 0.60rem !important;
    color: #374151;
    border-radius: 6px;
    transition: all 0.2s ease-in-out;
    white-space: nowrap;
}
.navbar .nav-link:hover, .navbar .nav-link.active {
    color: var(--primary-color) !important;
    background-color: rgba(0, 127, 61, 0.08);
}
.dropdown-menu {
    min-width: 220px;
    padding: 8px 0;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
    border: 1px solid rgba(0,0,0,0.06) !important;
}
.dropdown-item {
    font-size: 0.90rem;
    font-weight: 500;
    padding: 7px 16px;
    transition: all 0.2s;
}
.dropdown-item:hover, .dropdown-item.active {
    background-color: rgba(0, 127, 61, 0.1);
    color: var(--primary-color) !important;
}
</style>
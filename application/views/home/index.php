<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
?>

<!-- Hero Section -->
<section class="hero-section">
    <video autoplay muted loop playsinline>
        <source src="<?php echo base_url('assets/frontend/dvs/videos/school.mp4'); ?>" type="video/mp4">
    </video>
    <div class="hero-overlay">
        <h1>
            <?php echo html_escape(get_lang_field($cms_setting, 'application_title', 'ড্রিম ভ্যালী স্কুল')); ?>
        </h1>
        <p class="subtitle" style="font-size: 1.25rem; font-weight: 500; margin-bottom: 12px;">
            <?php echo html_escape(get_lang_field($cms_setting, 'address', 'চরবাটা, সুবর্ণচর, নোয়াখালী')); ?>
        </p>
        <p class="subtitle" style="font-size: 1.15rem; font-style: italic; line-height: 1.6; margin-bottom: 25px;">
            <?php 
            if (get_current_lang() == 'en') {
                echo 'Where dreams take wing in knowledge,<br>hearts blossom in the light of Quran';
            } else {
                echo 'স্বপ্ন ডানা মেলুক জ্ঞানের আলোয়,<br>হৃদয় বিকশিত হোক কুরআনের আলোয়';
            }
            ?>
        </p>
        <div class="btn-group">
            <a href="<?php echo base_url('admission'); ?>" class="btn btn-primary-hero">
                <i class="fas fa-edit me-1"></i> <?php echo translate('apply_for_admission'); ?>
            </a>
            <a href="<?php echo base_url('about'); ?>" class="btn btn-outline-light-custom">
                <i class="fas fa-book-open me-1"></i> <?php echo translate('our_history'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Highlighted Action Buttons -->
<section class="highlighted-buttons">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('notice'); ?>" class="btn-highlight btn-blue">
                    <i class="fas fa-bullhorn me-1"></i> <span><?php echo translate('latest_notice'); ?></span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('exam_results'); ?>" class="btn-highlight btn-green">
                    <i class="fas fa-check-circle me-1"></i> <span><?php echo translate('exam_results'); ?></span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('admission'); ?>" class="btn-highlight btn-orange">
                    <i class="fas fa-edit me-1"></i> <span><?php echo translate('online_application'); ?></span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('career'); ?>" class="btn-highlight btn-red">
                    <i class="fas fa-briefcase me-1"></i> <span><?php echo translate('career_portal'); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Slider and Notice Board Section -->
<section class="slider-notice-section py-4">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <!-- Left: Reception / Campus Slider -->
            <div class="col-lg-8">
                <div class="slider-container rounded overflow-hidden shadow-sm h-100" style="border: 1px solid #e2e8f0; height: 460px;">
                    <div class="owl-carousel owl-theme h-100" id="homeSlider">
                        <div class="item position-relative">
                            <img src="<?php echo base_url('assets/frontend/dvs/storage/sliders/nVHaqYexQf7HCFWv7BFZfn2Xx5yW9Xf6tqvnqHhg.jpg'); ?>" alt="DVS Reception" style="width: 100%; height: 460px; object-fit: cover;">
                            <div class="slider-caption" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0, 0, 0, 0.6); color: #fff; padding: 10px 15px 28px 15px; text-align: center; font-weight: 600; font-size: 1.05rem;">
                                Reception
                            </div>
                        </div>
                        <div class="item position-relative">
                            <img src="<?php echo base_url('assets/frontend/dvs/storage/sliders/6kbKQrv3ICnSDqnVIzLvDnmYp5PPGRSawBkN7Oym.jpg'); ?>" alt="DVS Campus" style="width: 100%; height: 460px; object-fit: cover;">
                            <div class="slider-caption" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0, 0, 0, 0.6); color: #fff; padding: 10px 15px 28px 15px; text-align: center; font-weight: 600; font-size: 1.05rem;">
                                Campus
                            </div>
                        </div>
                        <div class="item position-relative">
                            <img src="<?php echo base_url('assets/frontend/dvs/storage/sliders/42eh59Wg2yP9XdRl11ObJxPbJiuVXC6qFew2xcKI.jpg'); ?>" alt="DVS Building" style="width: 100%; height: 460px; object-fit: cover;">
                            <div class="slider-caption" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0, 0, 0, 0.6); color: #fff; padding: 10px 15px 28px 15px; text-align: center; font-weight: 600; font-size: 1.05rem;">
                                Campus Building
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Notice Board -->
            <div class="col-lg-4">
                <div class="notice-board bg-white rounded shadow-sm d-flex flex-column justify-content-between h-100" style="padding: 24px 28px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); height: 460px;">
                    <div>
                        <div class="border-bottom pb-2 mb-2" style="border-bottom: 3px solid var(--primary-color) !important;">
                            <h4 class="mb-0" style="color: var(--primary-color); font-weight: 700; font-size: 1.25rem;">
                                <i class="fas fa-clipboard-list me-2"></i> <?php echo translate('notice_board'); ?>
                            </h4>
                        </div>
                        <div class="notice-list">
                            <?php 
                            $board_notices = $this->db->order_by('id', 'desc')->limit(5)->get('front_cms_news_list')->result_array();
                            if (!empty($board_notices)) {
                                foreach ($board_notices as $bn) { 
                                    $notice_date = !empty($bn['date']) ? $bn['date'] : $bn['created_at'];
                                    $date_str = date('M, d', strtotime($notice_date));
                                    $notice_item_url = !empty($bn['alias']) ? base_url('notice-details/' . $bn['alias']) : base_url('notice-details/' . $bn['id']);
                            ?>
                                <div class="notice-item d-flex align-items-center gap-3 py-2 border-bottom" style="border-bottom: 1px solid #e5e7eb;">
                                    <div class="notice-date" style="background-color: var(--primary-color); color: white; padding: 8px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; min-width: 65px; text-align: center;">
                                        <?php echo $date_str; ?>
                                    </div>
                                    <a href="<?php echo $notice_item_url; ?>" class="notice-title" style="color: #374151; font-weight: 600; font-size: 0.92rem; text-decoration: none; flex: 1; line-height: 1.4;">
                                        <?php echo html_escape(get_lang_field($bn, 'title')); ?>
                                    </a>
                                </div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo base_url('notice'); ?>" class="btn w-100 text-white" style="background-color: var(--primary-color); font-weight: 600; border-radius: 4px; padding: 10px; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
                            ➔ <?php echo translate('view_all_notices'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Leadership Messages (নেতৃত্বের বার্তা) -->
<section class="leadership-section py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="section-title mb-4">
            <h2><i class="fas fa-comments"></i> <?php echo (get_current_lang() == 'en') ? 'Messages from Leadership' : 'নেতৃত্বের বার্তা'; ?></h2>
        </div>

        <?php
        // Fetch leaders dynamically from front_cms_testimonial
        $db_leaders = $this->db->order_by('rank', 'asc')->get_where('front_cms_testimonial', array('branch_id' => 1))->result_array();
        
        // Authentic 7 founders matching dreamvalleyschool.com
        $default_leaders = [
            ['name' => 'আরিফ বিল্লাহ', 'name_en' => 'Arif Billah', 'surname' => 'পরিচালক', 'surname_en' => 'Director', 'image' => 'aPTqVQNfK3dn20yKcX7UGP0MtZ25KVHJIccRMLyH.jpg', 'rank' => 1],
            ['name' => 'নোমান সিদ্দিকী', 'name_en' => 'Noman Siddiqui', 'surname' => 'ডিরেক্টর (কারিকুলাম অ্যান্ড একাডেমিক)', 'surname_en' => 'Director (Curriculum & Academic)', 'image' => 'yPgOckzU4JGSDMKFvho1hGvXGL3gTlz6Zi9IqrXT.jpg', 'rank' => 2],
            ['name' => 'সাইফুর রাসুল', 'name_en' => 'Saifur Rasul', 'surname' => 'কো-অর্ডিনেটর', 'surname_en' => 'Coordinator', 'image' => 'cKnOAg3AGny28G9DRD1pf2AdVa3hRpRBsvgPYcX7.jpg', 'rank' => 3],
            ['name' => 'মোবারক হোসেন', 'name_en' => 'Mobarak Hossain', 'surname' => 'ডিরেক্টর (ডেভেলপমেন্ট)', 'surname_en' => 'Director (Development)', 'image' => 'oUeADvQttECpHpYn1WCeWgTan7MnSUjgP7cm2Mqm.png', 'rank' => 4],
            ['name' => 'এ এইচ রাজু', 'name_en' => 'A H Raju', 'surname' => 'ডিরেক্টর (মিডিয়া)', 'surname_en' => 'Director (Media)', 'image' => '9nc23Jjq4C6KkQKdvphVh2aOpGtfIjOMSniESWap.png', 'rank' => 5],
            ['name' => 'আনছার উল্লাহ আল মাসুম', 'name_en' => 'Ansar Ullah Al Masum', 'surname' => 'ডিরেক্টর (মার্কেটিং)', 'surname_en' => 'Director (Marketing)', 'image' => 'RfiEb7Ph0cL1PKIZuwDCoIpiTnw6OQLidZxrhNiJ.jpg', 'rank' => 6],
            ['name' => 'আবুল হাসেম', 'name_en' => 'Abul Hashem', 'surname' => 'ডিরেক্টর', 'surname_en' => 'Director', 'image' => '4AsJWZzPctGqP8vDTvzbXDSJc7i15wPQNsPGGf6E.jpg', 'rank' => 7]
        ];

        $leaders_to_show = !empty($db_leaders) ? $db_leaders : $default_leaders;
        ?>

        <div class="row g-4 justify-content-center">
            <?php 
            $count = count($leaders_to_show);
            foreach ($leaders_to_show as $index => $leader) { 
                // Determine image path
                $img_file = $leader['image'];
                if (file_exists(FCPATH . 'assets/frontend/dvs/storage/founders/' . $img_file)) {
                    $img_src = base_url('assets/frontend/dvs/storage/founders/' . $img_file);
                } elseif (file_exists(FCPATH . 'uploads/frontend/testimonial/' . $img_file)) {
                    $img_src = base_url('uploads/frontend/testimonial/' . $img_file);
                } else {
                    $img_src = base_url('assets/frontend/dvs/storage/founders/4AsJWZzPctGqP8vDTvzbXDSJc7i15wPQNsPGGf6E.jpg');
                }

                // If last odd card, center it in row
                $col_class = ($index == 6 && $count == 7) ? "col-lg-6 col-md-8 mx-auto" : "col-lg-6 col-md-6";
            ?>
                <div class="<?php echo $col_class; ?>">
                    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 8px; border-left: 4px solid var(--primary-color) !important;">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div style="flex-shrink: 0; width: 135px; height: 160px; border-radius: 6px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo html_escape(get_lang_field($leader, 'name')); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fw-bold" style="color: var(--primary-color); font-size: 1.25rem;">
                                    <?php echo html_escape(get_lang_field($leader, 'name')); ?>
                                </h5>
                                <div class="mb-3">
                                    <span class="badge rounded-pill text-white" style="background-color: var(--primary-color); font-weight: 500; font-size: 11px; padding: 4px 12px;">
                                        <?php echo html_escape(get_lang_field($leader, 'surname')); ?>
                                    </span>
                                </div>
                                <?php 
                                $url_map = [
                                    15 => 1,
                                    18 => 2,
                                    17 => 3,
                                    20 => 4,
                                    19 => 5,
                                    16 => 6,
                                    21 => 7,
                                ];
                                $leader_url_id = isset($leader['id']) && isset($url_map[$leader['id']]) ? $url_map[$leader['id']] : (!empty($leader['rank']) ? $leader['rank'] : ($index + 1));
                                ?>
                                <div>
                                    <a href="<?php echo base_url('message-founder/' . $leader_url_id); ?>" class="btn btn-sm text-white" style="background-color: #d97706; font-weight: 600; padding: 5px 16px; border-radius: 4px; font-size: 13px; text-decoration: none;">
                                        <?php echo translate('read_full_message'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div class="section-title">
                    <h2><i class="fas fa-hands-praying"></i> <?php echo html_escape(get_lang_field($wellcome, 'title', 'Welcome to Dream Valley')); ?></h2>
                </div>
                <p class="text-justify pr-4">
                    <?php 
                    $default_welcome_bn = "ড্রিম ভ্যালি স্কুল ২০২৫ সালে নোয়াখালীর সুবর্ণচরের চরবাটায় প্রতিষ্ঠিত হয় একটি মহৎ স্বপ্ন ও সুদূরপ্রসারী লক্ষ্যকে সামনে রেখে। আমাদের উদ্দেশ্য হলো এমন একটি প্রজন্ম গড়ে তোলা, যারা হবে পরিবেশ সচেতন, নৈতিক মূল্যবোধে সমৃদ্ধ এবং ভবিষ্যতের চ্যালেঞ্জ মোকাবিলায় দক্ষ। আধুনিক প্রযুক্তিনির্ভর শিক্ষা, নৈতিকতা এবং পরিবেশ সচেতনতার সমন্বয়ে মানসম্মত শিক্ষা প্রদানই আমাদের অঙ্গীকার। আমরা সৃজনশীলতা, উদ্ভাবনী চিন্তাধারা এবং নেতৃত্বগুণ বিকাশে গুরুত্ব দিয়ে শিক্ষার্থীদের জন্য একটি প্রাণবন্ত ও অনুপ্রেরণামূলক শিক্ষার পরিবেশ নিশ্চিত করি। ইন্টারেক্টিভ লার্নিং, ব্যবহারিক শিক্ষা এবং সামাজিক সম্পৃক্ততার মাধ্যমে শিক্ষার্থীদের বাস্তব জীবনের জন্য প্রস্তুত করে তোলা আমাদের অন্যতম লক্ষ্য।\n\nড্রিম ভ্যালি স্কুলে আমরা বিশ্বাস করি, প্রতিটি শিশুর মধ্যে অসীম সম্ভাবনা নিহিত রয়েছে। সেই সম্ভাবনাকে বিকশিত করার লক্ষ্যে আমরা একটি বিশেষায়িত ইসলামিক কারিকুলাম অনুসরণ করি, যার অন্যতম গুরুত্বপূর্ণ অংশ হলো আমাদের স্বতন্ত্র হিফজুল কুরআন বিভাগ। আমরা এমন একটি ভারসাম্যপূর্ণ শিক্ষাব্যবস্থা গড়ে তুলতে বদ্ধপরিকর, যেখানে শিক্ষার্থীরা একদিকে আধুনিক শিক্ষায় দক্ষতা অর্জন করবে, অন্যদিকে পবিত্র কুরআনের আলোয় নিজেদের আধ্যাত্মিক ও নৈতিক ভিত্তিকে সুদৃঢ় করবে। আধুনিক জ্ঞান, প্রযুক্তিগত দক্ষতা এবং ইসলামিক মূল্যবোধের সমন্বয়ের মাধ্যমে আমরা এমন শিক্ষার্থী গড়ে তুলতে চাই, যারা নিজেদের, সমাজ এবং দেশের উন্নয়নে ইতিবাচক ভূমিকা রাখবে এবং একটি সুন্দর, টেকসই ও সমৃদ্ধ ভবিষ্যৎ নির্মাণে নেতৃত্ব দেবে। 🌿📚🌙";
                    $default_welcome_en = "Dream Valley School was established in 2025 at Charbata, Subarnachar, Noakhali, with a vision to create a new generation of environmentally conscious and future-ready learners. Our institution is dedicated to providing quality education that blends modern technology with moral and environmental awareness. We focus on nurturing creativity, innovation, and leadership among students through interactive learning, practical experience, and community engagement. At Dream Valley School, we believe in empowering young minds through a specialized Islamic curriculum that includes a dedicated Hifz-ul-Quran section. Our goal is to provide a balanced education where students can achieve academic excellence while building a strong spiritual foundation with the light of the Quran. By fostering both modern skills and Islamic values, we prepare our students to build a sustainable and prosperous tomorrow for themselves, their community, and the planet. 🌿 📖";
                    $default_welcome = (get_current_lang() == 'en') ? $default_welcome_en : $default_welcome_bn;
                    echo nl2br(html_escape(get_lang_field($wellcome, 'description', $default_welcome)));
                    ?>
                </p>
                <a href="<?php echo base_url('about'); ?>" class="btn"
                    style="background-color: var(--primary-color); color: white; border: none;">
                    <i class="fas fa-arrow-right"></i> <?php echo (get_current_lang() == 'en') ? 'Learn about more' : 'আমাদের সম্পর্কে আরও জানুন'; ?>
                </a>
            </div>
            <div class="col-lg-6 mb-4">
                <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/settings/welcome_image_1781274557.jpg'); ?>" alt="Welcome" class="img-fluid" style="border-radius: 8px;">
            </div>
        </div>
    </div>
</section>

<!-- Latest Events Section (সর্বশেষ ইভেন্ট) -->
<section class="news-section">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-newspaper"></i> <?php echo ($current_lang == 'en') ? 'Latest Events' : 'সর্বশেষ ইভেন্ট'; ?></h2>
            <p><?php echo ($current_lang == 'en') ? 'Stay informed about the latest events at our school.' : 'আমাদের স্কুলের সর্বশেষ ইভেন্ট সম্পর্কে অবহিত থাকুন।'; ?></p>
        </div>
        <div class="category-filter">
            <button class="btn btn-primary filter-btn" data-category="all"
                style="background-color: var(--primary-color); border: none;"><?php echo ($current_lang == 'en') ? 'All' : 'সব'; ?></button>
            <button class="btn btn-outline-secondary filter-btn"
                data-category="eventdata4"><?php echo ($current_lang == 'en') ? 'Competition' : 'প্রতিযোগিতা'; ?></button>
            <button class="btn btn-outline-secondary filter-btn"
                data-category="eventdata2">Quran Recitation Competition</button>
            <button class="btn btn-outline-secondary filter-btn"
                data-category="eventdata3">Arabic language</button>
            <button class="btn btn-outline-secondary filter-btn"
                data-category="eventdata1">Science Fair</button>
        </div>

        <div class="news-slider row" id="newsCardsContainer">
            <div class="col-md-6 col-lg-4 mb-4 news-item" data-category="eventdata4">
                <div class="news-card">
                    <img src="<?php echo base_url('assets/frontend/dvs/storage/events/Mno2PfUN00OsT7ROoqUxOimoQHqqNetXAgIDM87W.jpg'); ?>" alt="Talent Hunt 2026">
                    <div class="news-card-body">
                        <span class="news-category"><i class="fas fa-trophy"></i>
                            <?php echo ($current_lang == 'en') ? 'Competition' : 'প্রতিযোগিতা'; ?></span>
                        <h4><i class="fas fa-award"></i> Talent Hunt 2026</h4>
                        <small class="text-muted"><i class="fas fa-calendar"></i>
                            July 31, 2026 11:47 PM</small>
                        <br><br>
                        <a href="<?php echo base_url('events'); ?>" class="read-more"><i
                                class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Event Details' : 'ইভেন্ট বিস্তারিত'; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 news-item" data-category="eventdata2">
                <div class="news-card">
                    <img src="<?php echo base_url('assets/frontend/dvs/storage/events/Zs1DH0nIMTS5cKWUMeYbzvCkqMspid1FPVgLkP82.png'); ?>" alt="Quran Recitation Competition">
                    <div class="news-card-body">
                        <span class="news-category">Quran Recitation Competition</span>
                        <h4>Quran Recitation Competition</h4>
                        <small class="text-muted"><i class="fas fa-calendar"></i>
                            December 31, 2025 10:00 AM</small>
                        <br><br>
                        <a href="<?php echo base_url('events'); ?>" class="read-more"><i
                                class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Event Details' : 'ইভেন্ট বিস্তারিত'; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 news-item" data-category="eventdata3">
                <div class="news-card">
                    <img src="<?php echo base_url('assets/frontend/dvs/storage/events/ZqtpDeyl4ymG9bnI4LYThUsmn3C5wz5szxHhHkZF.jpg'); ?>" alt="Arabic Fair">
                    <div class="news-card-body">
                        <span class="news-category">Arabic language</span>
                        <h4>Arabic Fair</h4>
                        <small class="text-muted"><i class="fas fa-calendar"></i>
                            December 31, 2025 10:00 AM</small>
                        <br><br>
                        <a href="<?php echo base_url('events'); ?>" class="read-more"><i
                                class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Event Details' : 'ইভেন্ট বিস্তারিত'; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 news-item" data-category="eventdata1">
                <div class="news-card">
                    <img src="<?php echo base_url('assets/frontend/dvs/storage/events/5hMJasdzo9ywqzCMgWDKotpc00a7l8RsTtCWNRFY.jpg'); ?>" alt="Science Fair">
                    <div class="news-card-body">
                        <span class="news-category">Science Fair</span>
                        <h4>Science Fair</h4>
                        <small class="text-muted"><i class="fas fa-calendar"></i>
                            December 30, 2025 10:00 AM</small>
                        <br><br>
                        <a href="<?php echo base_url('events'); ?>" class="read-more"><i
                                class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Event Details' : 'ইভেন্ট বিস্তারিত'; ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo base_url('events'); ?>" class="btn"
                style="background-color: var(--primary-color); color: white; border: none; padding: 12px 40px; font-weight: 600; border-radius: 6px;">
                <i class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'View More Events' : 'আরো ইভেন্ট দেখুন'; ?>
            </a>
        </div>
    </div>
</section>

<!-- Video Gallery Section (ভিডিও গ্যালারী) -->
<section class="video-gallery-section py-5 bg-white" id="video-gallery-section">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-video"></i> <?php echo ($current_lang == 'en') ? 'Video Gallery' : 'ভিডিও গ্যালারী'; ?></h2>
            <p><?php echo ($current_lang == 'en') ? 'Browse our video gallery for recent videos.' : 'আমাদের ভিডিও গ্যালারি থেকে সাম্প্রতিক ভিডিওগুলো দেখুন।'; ?></p>
        </div>

        <div class="row g-4">
            <!-- Video 1 -->
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="card video-card shadow-sm">
                    <div class="position-relative">
                        <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/videos/hMqdHC9RqSxAAyUNDCBTQRSuRVKaMTRkADVlbUgo.png'); ?>" class="card-img-top" alt="National Anthem">
                        <button class="btn btn-play" data-youtube-url="https://www.youtube.com/watch?v=xy2kRkcn-rc&amp;list=RDxy2kRkcn-rc&amp;start_radio=1"><i class="fas fa-play"></i></button>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="mb-0 fw-semibold" style="font-size: 0.95rem; color: #1e293b;">National Anthem</h6>
                    </div>
                </div>
            </div>

            <!-- Video 2 -->
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="card video-card shadow-sm">
                    <div class="position-relative">
                        <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/videos/BbDGm6s6Mbe6QfX3Ns6QlxTKY4FZYKOtskADGO7c.png'); ?>" class="card-img-top" alt="Campus Video">
                        <button class="btn btn-play" data-youtube-url="https://www.youtube.com/watch?v=8BI8S-hDJG0"><i class="fas fa-play"></i></button>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="mb-0 fw-semibold" style="font-size: 0.95rem; color: #1e293b;">Campus Video</h6>
                    </div>
                </div>
            </div>

            <!-- Video 3 -->
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="card video-card shadow-sm">
                    <div class="position-relative">
                        <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/videos/XNtHfQ5xOPAUrEJcWn9fZ5brl1WbSmlneInkYaSG.jpg'); ?>" class="card-img-top" alt="Our Services">
                        <button class="btn btn-play" data-youtube-url="https://www.youtube.com/watch?v=v-Yi2RRNS3A"><i class="fas fa-play"></i></button>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="mb-0 fw-semibold" style="font-size: 0.95rem; color: #1e293b;">Our Services</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Modal (custom player popup using Plyr) -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" style="right:10px;top:10px;color:white;z-index:1051;background-color:rgba(255,255,255,0.8);"></button>
                <div id="playerContainer" class="p-0"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var playerInstance = null;
    var modalEl = document.getElementById('videoModal');
    var bsModal = null;
    if (modalEl && typeof bootstrap !== 'undefined') {
        bsModal = new bootstrap.Modal(modalEl);
    }

    function getYouTubeId(url){
        if(!url) return null;
        var m = url.match(/(?:v=|youtu\.be\/|embed\/)([A-Za-z0-9_-]+)/);
        return m && m[1] ? m[1] : null;
    }

    function buildSrc(id){
        var origin = window.location.origin;
        return 'https://www.youtube.com/embed/' + id + '?rel=0&modestbranding=1&iv_load_policy=3&enablejsapi=1&origin=' + encodeURIComponent(origin);
    }

    document.querySelectorAll('.btn-play').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            var url = btn.getAttribute('data-youtube-url');
            var id = getYouTubeId(url);
            if(!id) return;

            var container = document.getElementById('playerContainer');
            container.innerHTML = '<div class="plyr__video-embed" id="playerEmbed">'
                + '<iframe src="' + buildSrc(id) + '" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>'
                + '</div>';

            try {
                if (typeof Plyr !== 'undefined') {
                    playerInstance = new Plyr('#playerEmbed', {
                        controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
                        autoplay: true
                    });
                }
            } catch(err) {
                console.error(err);
            }

            if (bsModal) bsModal.show();
        });
    });

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function(){
            var container = document.getElementById('playerContainer');
            if (container) container.innerHTML = '';
            if (playerInstance) {
                try { playerInstance.destroy(); } catch(e){}
                playerInstance = null;
            }
        });
    }
});
</script>

<!-- Photo Gallery Section (ফটো গ্যালারী) -->
<section class="gallery-section py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-image"></i> <?php echo ($current_lang == 'en') ? 'Photo Gallery' : 'ফটো গ্যালারী'; ?></h2>
            <p><?php echo ($current_lang == 'en') ? 'See the moments captured at our school.' : 'আমাদের স্কুলে ধারণ করা মুহূর্তগুলো দেখুন।'; ?></p>
        </div>

        <?php
        $albums_photos = [
            ['title' => 'School-playground', 'title_en' => 'School Playground', 'subtitle' => 'Science Fair', 'img' => 'VVrjVFgyFGcDDTDTo242IIgyxYaL1LAhoQDsPcAH.png'],
            ['title' => 'Science Fair', 'title_en' => 'Science Fair', 'subtitle' => 'Science Fair', 'img' => 'y9fB1H8Lj62dgVoBqU1nQCsqPXA4fMtZwZjsQL6P.jpg'],
            ['title' => 'Reception Desk', 'title_en' => 'Reception Desk', 'subtitle' => 'Campus', 'img' => 'ewmBM075sXOywbDltHPoJQsgq5FzX9NOMixGnU4g.png'],
            ['title' => 'Campus Building', 'title_en' => 'Campus Building', 'subtitle' => 'Campus', 'img' => '7AvZ2ZDpLYtuGCk3kSrGYQM4iFHU9FyPsKAeLKK1.jpg'],
            ['title' => 'Science Project', 'title_en' => 'Science Project', 'subtitle' => 'Science Fair', 'img' => 'zWwvxvyjrDcqS5zZTTrsP9IPM10l4OsXSpsyKkCl.png'],
            ['title' => 'Lab Equipment', 'title_en' => 'Lab Equipment', 'subtitle' => 'Science Fair', 'img' => 'O6AEkanFBx4vifuJBgKSNPf7XmIWfZP6A9Ejhbrl.jpg'],
            ['title' => 'Science Model', 'title_en' => 'Science Model', 'subtitle' => 'Science Fair', 'img' => 'kggkHIcaSjICaP4QM6Yv0XStGhGbVRWjuhWqgjUw.jpg'],
            ['title' => 'Exhibition', 'title_en' => 'Exhibition', 'subtitle' => 'Science Fair', 'img' => '8mk42qS6mVHLZ49biHQlrwJgfkYkFOyKDXrvkzlE.jpg']
        ];
        ?>

        <div class="row g-4 gallery-container">
            <?php foreach ($albums_photos as $ap) { 
                $img_url = base_url('assets/frontend/dvs/storage/albums/' . $ap['img']);
                $photo_title = get_lang_field($ap, 'title');
                $photo_sub = isset($ap['subtitle']) ? $ap['subtitle'] : '';
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 gallery-item campus">
                    <a href="<?php echo $img_url; ?>" class="gallery-popup" title="<?php echo html_escape($photo_title); ?>">
                        <div class="gallery-card">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo html_escape($photo_title); ?>" class="img-fluid">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <h5><?php echo html_escape($photo_title); ?></h5>
                                    <?php if (!empty($photo_sub)) { ?>
                                        <p><?php echo html_escape($photo_sub); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo base_url('gallery'); ?>" class="btn"
                style="background-color: var(--primary-color); color: white; border: none; padding: 12px 40px; font-weight: 600; border-radius: 6px;">
                <i class="fas fa-images"></i> <?php echo ($current_lang == 'en') ? 'View More Gallery' : 'আরো ফটো দেখুন'; ?>
            </a>
        </div>
    </div>
</section>

<!-- Achievements and Statistics (আমাদের অর্জন ও পরিসংখ্যান) -->
<section class="achievements-section py-5 bg-white">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-star"></i> <?php echo ($current_lang == 'en') ? 'Our Achievements and Statistics' : 'আমাদের অর্জন ও পরিসংখ্যান'; ?></h2>
        </div>
        <div class="row mb-5">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-number">0</div>
                    <div class="stat-label"><?php echo ($current_lang == 'en') ? 'Total Students' : 'মোট শিক্ষার্থী'; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card green">
                    <i class="fas fa-medal"></i>
                    <div class="stat-number">0</div>
                    <div class="stat-label"><?php echo ($current_lang == 'en') ? 'Graduates' : 'স্নাতক হয়েছেন'; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card purple">
                    <i class="fas fa-trophy"></i>
                    <div class="stat-number">0</div>
                    <div class="stat-label"><?php echo ($current_lang == 'en') ? 'Awards' : 'পুরস্কারসমূহ'; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card orange">
                    <i class="fas fa-book"></i>
                    <div class="stat-number">0</div>
                    <div class="stat-label"><?php echo ($current_lang == 'en') ? 'Teaching Staff' : 'শিক্ষকমণ্ডলী'; ?></div>
                </div>
            </div>
        </div>

        <div class="visitor-stats">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <h5 class="fw-bold"><i class="fas fa-eye"></i> <?php echo ($current_lang == 'en') ? 'Visitor Statistics' : 'পরিদর্শনকারীদের পরিসংখ্যান'; ?></h5>
                    <div class="mt-3">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;"><?php echo ($current_lang == 'en') ? 'Total Visitors' : 'মোট পরিদর্শনকারী'; ?></p>
                        <h3 style="color: var(--primary-color); font-weight: 700;">672</h3>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;"><?php echo ($current_lang == 'en') ? 'This Month' : 'এই মাসে'; ?></p>
                        <h4 style="color: #10b981; font-weight: 700;">54</h4>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;"><?php echo ($current_lang == 'en') ? 'Today' : 'আজ'; ?></p>
                        <h4 style="color: #f59e0b; font-weight: 700;">13</h4>
                    </div>
                </div>
                <div class="col-md-8">
                    <h5 class="fw-bold"><i class="fas fa-chart-bar"></i> <?php echo ($current_lang == 'en') ? 'Academic Performance 2025' : 'একাডেমিক পারফরম্যান্স 2025'; ?></h5>
                    <div class="row mt-4">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div style="background-color: #dbeafe; border-radius: 8px; padding: 20px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 500;"><?php echo ($current_lang == 'en') ? 'Total Students' : 'মোট শিক্ষার্থী'; ?></small>
                                <h4 style="color: var(--primary-color); font-weight: 700; margin-bottom: 0;">0</h4>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div style="background-color: #dcfce7; border-radius: 8px; padding: 20px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 500;"><?php echo ($current_lang == 'en') ? 'Passed' : 'পাস করেছেন'; ?></small>
                                <h4 style="color: #10b981; font-weight: 700; margin-bottom: 0;">0</h4>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="background-color: #ede9fe; border-radius: 8px; padding: 20px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 500;">GPA- 5</small>
                                <h4 style="color: #8b5cf6; font-weight: 700; margin-bottom: 0;">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

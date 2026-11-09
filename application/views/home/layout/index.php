<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <?php
        $default_inst_name = !empty($global_config['institute_name']) ? $global_config['institute_name'] : (!empty($branch['school_name']) ? $branch['school_name'] : 'School');
        $site_name = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', $default_inst_name) : $default_inst_name;
        $meta_kw = !empty($page_data['meta_keyword']) ? $page_data['meta_keyword'] : 'school, college, education';
        $meta_desc = !empty($page_data['meta_description']) ? $page_data['meta_description'] : $site_name;
    ?>
    <meta name="keyword" content="<?php echo html_escape($meta_kw); ?>">
    <meta name="description" content="<?php echo html_escape($meta_desc); ?>">
    <link rel="canonical" href="<?php echo current_url(); ?>">
    
    <!-- Open Graph / Social Sharing Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="<?php echo html_escape($site_name); ?>">
    <meta property="og:title" content="<?php echo html_escape(!empty($page_data['page_title']) ? $page_data['page_title'] : $site_name); ?>">
    <meta property="og:description" content="<?php echo html_escape($meta_desc); ?>">
    <meta property="og:url" content="<?php echo current_url(); ?>">
    <?php if (!empty($page_data['meta_image'])): ?>
    <meta property="og:image" content="<?php echo html_escape($page_data['meta_image']); ?>">
    <?php endif; ?>

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo html_escape(!empty($page_data['page_title']) ? $page_data['page_title'] : $site_name); ?>">
    <meta name="twitter:description" content="<?php echo html_escape($meta_desc); ?>">
    <?php if (!empty($page_data['meta_image'])): ?>
    <meta name="twitter:image" content="<?php echo html_escape($page_data['meta_image']); ?>">
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png'); ?>">
    <?php 
        $current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
        $curr_page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title') : (!empty($page_data['page_title']) ? $page_data['page_title'] : '');
        $curr_site_title = html_escape($site_name);
        $full_page_title = (!empty($curr_page_title) ? $curr_page_title . " - " : "") . $curr_site_title;
    ?>
    <title><?php echo $full_page_title; ?></title>

    <!-- Schema.org Structured Data (JSON-LD for Search Engines & AI Crawlers) -->
    <?php
        $schema_phone = !empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : (!empty($global_config['mobileno']) ? $global_config['mobileno'] : '+8801727770321');
        $schema_email = !empty($cms_setting['email']) ? $cms_setting['email'] : (!empty($global_config['institute_email']) ? $global_config['institute_email'] : 'charhasan1996@gmail.com');
        $schema_address = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'address', 'গ্রাম: চর হাসান, পো: পশ্চিম চর জব্বর, সুবর্ণচর, নোয়াখালী') : 'গ্রাম: চর হাসান, পো: পশ্চিম চর জব্বর, সুবর্ণচর, নোয়াখালী';
        $schema_logo = !empty($cms_setting['logo']) ? base_url('uploads/frontend/images/' . $cms_setting['logo']) : base_url('assets/images/favicon.png');
        
        $json_ld = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => ['EducationalOrganization', 'School'],
                    '@id' => base_url('#organization'),
                    'name' => 'চর হাসান ইসলামিয়া দাখিল মাদরাসা',
                    'alternateName' => ['Char Hasan Islamia Dakhil Madrasah', 'CHIDM', 'ড্রিম ভ্যালি স্কুল', 'Dream Valley School'],
                    'url' => base_url(),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => $schema_logo
                    ],
                    'telephone' => $schema_phone,
                    'email' => $schema_email,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $schema_address,
                        'addressLocality' => 'Subarnachar',
                        'addressRegion' => 'Noakhali',
                        'addressCountry' => 'BD'
                    ],
                    'foundingDate' => '1996'
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => current_url() . '#webpage',
                    'url' => current_url(),
                    'name' => $full_page_title,
                    'description' => $meta_desc,
                    'inLanguage' => ['bn', 'en'],
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        '@id' => base_url('#website'),
                        'name' => $site_name,
                        'url' => base_url()
                    ]
                ]
            ]
        ];
        if (!empty($cms_setting['facebook_url'])) {
            $json_ld['@graph'][0]['sameAs'] = [$cms_setting['facebook_url']];
        }
    ?>
    <script type="application/ld+json">
    <?php echo json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6.4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - Poppins & Hind Siliguri -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">

    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">

    <!-- Plyr CSS for custom video player -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    <!-- DVS Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/dvs/css/style.css'); ?>">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSRF Setup & Global Helpers -->
    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>";
        var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
        $(function($) {
            $.ajaxSetup({
                data: csrfData
            });
        });

        // Global getSectionByClass definition available early on all frontend pages
        window.getSectionByClass = window.getSectionByClass || function(class_id) {
            var $sec = $('#online_section_id, #section_id, select[name="section"], select[name="section_id"]');
            var isEn = <?php echo ($current_lang == 'en') ? 'true' : 'false'; ?>;
            if (!class_id) {
                var placeholder = isEn ? 'Select Class First' : 'প্রথমে শ্রেণি নির্বাচন করুন';
                $sec.html('<option value="">' + placeholder + '</option>');
                if ($.fn.select2 && $sec.hasClass('select2-hidden-accessible')) {
                    $sec.trigger('change.select2');
                }
                return;
            }
            var loadingText = isEn ? 'Loading Sections...' : 'শাখা লোড হচ্ছে...';
            $sec.html('<option value="">' + loadingText + '</option>');
            var postData = { class_id: class_id };
            if (typeof csrfData !== 'undefined' && csrfData) {
                $.extend(postData, csrfData);
            }
            var ajaxUrl = (typeof base_url !== 'undefined' ? base_url : '<?=base_url()?>') + 'home/getSectionByClass';
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: postData,
                dataType: 'html',
                success: function(response) {
                    $sec.html(response);
                    if ($.fn.select2 && $sec.hasClass('select2-hidden-accessible')) {
                        $sec.trigger('change.select2');
                    }
                },
                error: function() {
                    $sec.html('<option value="">' + (isEn ? 'Failed to load' : 'লোড করা যায়নি') + '</option>');
                }
            });
        };
    </script>

    <!-- DVS Root Theme Variables -->
    <style>
        :root {
            --primary-color: <?php echo !empty($cms_setting['primary_color']) ? $cms_setting['primary_color'] : '#007F3D'; ?>;
            --secondary-color: <?php echo !empty($cms_setting['hover_color']) ? $cms_setting['hover_color'] : '#005f2e'; ?>;
            --success-color: #007F3D;
            --accent-color: #d88c09;
            --info-color: #0284c7;
            --warning-color: #f59e0b;
            --danger-color: #dc2626;
            --light-color: #f8fafc;
            --dark-color: #c79f22;
            --body-color: #334155;
            --body-bg: #ffffff;
            --muted-text: #64748b;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
            --header-bg: #f8fafc;
            --footer-bg: #0f172a;
            --hover-color: <?php echo !empty($cms_setting['hover_color']) ? $cms_setting['hover_color'] : '#005f2e'; ?>;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
            --font-base: 'Poppins', sans-serif;
            --font-bn: "Hind Siliguri", sans-serif;
            --font-size-base: 16px;
            --line-height-base: 1.6;
        }
        
        body {
            font-family: 'Poppins', 'Hind Siliguri', sans-serif;
            color: var(--body-color);
            background-color: var(--body-bg);
            margin: 0;
            padding: 0;
        }

        html[lang="en"],
        html[lang="en"] body,
        html[lang="en"] input,
        html[lang="en"] button,
        html[lang="en"] select,
        html[lang="en"] textarea,
        html[lang="en"] h1,
        html[lang="en"] h2,
        html[lang="en"] h3,
        html[lang="en"] h4,
        html[lang="en"] h5,
        html[lang="en"] h6,
        html[lang="en"] p,
        html[lang="en"] a,
        html[lang="en"] span,
        html[lang="en"] li,
        html[lang="en"] .btn,
        html[lang="en"] .nav-link,
        html[lang="en"] .dropdown-item {
            font-family: 'Poppins', sans-serif !important;
        }

        html[lang="bn"],
        html[lang="bn"] body {
            font-family: 'Poppins', 'Hind Siliguri', sans-serif;
        }

        [lang="en"],
        .font-en,
        .en {
            font-family: 'Poppins', sans-serif !important;
        }

        .page-header-wave path { fill: var(--primary-color) !important; }
        <?php if (empty($cms_setting['page_background'])): ?>
        /* DVS Clean White Theme Defensive Header Styles */
        .page-header { background: #fff !important; }
        .page-header .page-header-title { color: var(--dark-color, #c79f22) !important; }
        .page-header .page-header-subtitle,
        .page-header p.text-white-50,
        .page-header p.text-white,
        .page-header .text-white-50,
        .page-header .text-white:not(.btn):not(.badge) { color: #64748b !important; }
        .page-header .breadcrumb-item,
        .page-header .breadcrumb-item a,
        .page-header .breadcrumb-item a.text-white-50,
        .page-header .breadcrumb-item a.text-white { color: #64748b !important; }
        .page-header .breadcrumb-item.active,
        .page-header .breadcrumb-item.active.text-white { color: var(--dark-color, #c79f22) !important; font-weight: 600; }
        .page-header .page-header-icon { color: #273142 !important; }
        <?php else: ?>
        .page-header {
            background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.55)), url('<?=base_url("uploads/frontend/images/" . $cms_setting["page_background"])?>') !important;
            background-size: cover !important;
            background-position: center !important;
        }
        .page-header .page-header-title { color: #fff !important; }
        .page-header .page-header-subtitle { color: rgba(255,255,255,0.85) !important; }
        .page-header .breadcrumb-item a { color: rgba(255,255,255,0.75) !important; }
        .page-header .breadcrumb-item.active { color: #fff !important; font-weight: 600; }
        .page-header .page-header-icon { color: rgba(255,255,255,0.85) !important; }
        <?php endif; ?>
    </style>
</head>
<body>
    <?php $this->load->view('home/layout/header'); ?>
    <?php echo $main_contents; ?>
    <?php $this->load->view('home/layout/footer'); ?>
</body>
</html>
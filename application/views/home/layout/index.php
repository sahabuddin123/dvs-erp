<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="keyword" content="<?php echo !empty($page_data['meta_keyword']) ? $page_data['meta_keyword'] : 'school, college, education, dream valley school'; ?>">
    <meta name="description" content="<?php echo !empty($page_data['meta_description']) ? $page_data['meta_description'] : 'ড্রিম ভ্যালী স্কুল - যেখানে স্বপ্ন ডানা মেলে'; ?>">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png'); ?>">
    <?php 
        $current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
        $curr_page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title') : (!empty($page_data['page_title']) ? $page_data['page_title'] : '');
        $curr_site_title = ($current_lang == 'en') ? 'Dream Valley School' : 'ড্রিম ভ্যালী স্কুল (Dream Valley School)';
    ?>
    <title><?php echo (!empty($curr_page_title) ? $curr_page_title . " - " : "") . $curr_site_title; ?></title>

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
    </style>
</head>
<body>
    <?php $this->load->view('home/layout/header'); ?>
    <?php echo $main_contents; ?>
    <?php $this->load->view('home/layout/footer'); ?>
</body>
</html>
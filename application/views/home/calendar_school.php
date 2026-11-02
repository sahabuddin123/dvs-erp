<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = ($current_lang == 'en') ? 'Academic Calendar (School)' : 'একাডেমিক ক্যালেন্ডার (স্কুল)';
$page_subtitle = ($current_lang == 'en') ? 'Academic Calendar of the subscribed academic year' : 'সাবস্ক্রাইব করা শিক্ষাবর্ষের অ্যাকাডেমিক ক্যালেন্ডার';
$content_text = ($current_lang == 'en') ? 'View the academic calendar for our school session.' : 'এখানে আমাদের স্কুল সেশনের একাডেমিক ক্যালেন্ডার দেখুন।';
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Calendar Container -->
<section class="section bg-light my-5 py-5" style="min-height: 500px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px; background: #fff;">
                    <div class="card-body p-4 p-md-5">
                        <div class="post-content mb-4" id="description" style="color: #334155; font-size: 1.05rem; line-height: 1.8;">
                            <p><?php echo $content_text; ?></p>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6 d-flex align-items-center">
                                <button onclick="history.go(-1)" class="btn text-white" style="background-color: var(--dark-color); border-radius: 6px; font-weight: 500; padding: 7px 20px;">
                                    <i class="fas fa-arrow-left me-2"></i> <?php echo ($current_lang == 'en') ? 'Back' : 'ফিরে যান'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

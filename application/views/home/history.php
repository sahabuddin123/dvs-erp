<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Our History' : 'আমাদের ইতিহাস')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'আমাদের ইতিহাস');
$content = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', '') : (!empty($page_data['content']) ? $page_data['content'] : '');
?>

<!-- History Section -->
<section class="section bg-light my-5 py-5 history-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-5">
                    <div class="card-body p-4 p-md-5">
                        <div class="section-title mb-4 text-center">
                            <h2><?php echo html_escape($page_title); ?></h2>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-12" id="details">
                                <?php if (strpos($content, 'history-content') !== false) { ?>
                                    <?php echo $content; ?>
                                <?php } else { ?>
                                    <div class="history-content">
                                        <div class="timeline-content">
                                            <?php echo $content; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

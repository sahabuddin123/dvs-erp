<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_data['page_title']); ?></h1>
                <?php if (!empty($page_data['title'])) { ?><p class="text-white-50 mb-0 mt-2"><?php echo html_escape($page_data['title']); ?></p><?php } ?>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>
<!-- Main Container Starts -->
<div class="container px-md-0 main-container">
    <!-- Content Starts -->
    <h3 class="main-heading2 mt-0"><?php echo $page_data['title']; ?></h3>
    <?php echo $page_data['description']; ?>
    <!-- Content Ends -->
    <!-- FAQ's Accordions Starts -->
    <div class="accordion" id="accordion-faqs">
        <?php 
            $faq_list = $this->db->where('branch_id', $branchID)->get('front_cms_faq_list')->result_array();
            foreach ($faq_list as $key => $value) {
            ?>
        <div class="card">
            <!-- Card Header Starts -->
            <div class="card-header" id="faq<?php echo $key; ?>">
                <h5 class="card-title" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key; ?>" aria-expanded="false" aria-controls="collapse<?php echo $key; ?>">
                    <a><?php echo $value['title'] ?></a>
                </h5>
            </div>
            <div id="collapse<?php echo $key; ?>" class="collapse" aria-labelledby="faq<?php echo $key; ?>" data-parent="#accordion-faqs">
                <div class="card-body">
                    <?php echo $value['description']; ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <!-- Accordion #5 Ends -->
</div>
<!-- Main Container Ends -->
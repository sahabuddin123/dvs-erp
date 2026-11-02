<?php $current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/certificate.js?v=' . version_combine()) ?>"></script>
<style type="text/css">
    #print {
        margin-bottom: 20px;
        margin-top: 0px;
        padding: 2px 15px;
        font-size: 14px;
        font-weight: 500;
    }
</style>
<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape(!empty($page_data['page_title']) ? $page_data['page_title'] : (($current_lang == 'en') ? 'Exam Results' : 'পরীক্ষার ফলাফল')); ?></h1>
                
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-chart-line"></i>
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
    <p><?php echo $page_data['description']; ?></p>
    <?php echo form_open('home/examResultsPrintFn', array('class' => 'printIn')); ?>
    <div class="box2 form-box">
        <div class="row">
            <div class="col-md-4 mb-sm">
                <div class="form-group">
                    <label class="control-label"> <?=translate('exam')?> <span class="required">*</span></label>
                    <?php
                        $array = array();
                        $result = $this->home_model->getExamList($branchID);
                        if (count($result)) {
                            $array[''] = translate('select');
                            foreach ($result as $row) {
                                if ($row['term_id'] != 0) {
                                    $term = $this->db->select('name')->where('id', $row['term_id'])->get('exam_term')->row()->name;
                                    $name = $row['name'] . ' (' . $term . ')';
                                } else {
                                    $name = $row['name'];
                                }
                                $array[$row['id']] = $name;
                            }
                        } else {
                            $array[0] = translate('no_information_available');
                        }

                        echo form_dropdown("exam_id", $array, set_value('exam_id'), "class='form-control' data-plugin-selectTwo");
                    ?>
                    <span class="error"></span>
                </div>
            </div>
            <div class="col-md-4 mb-sm">
                <div class="form-group">
                    <label class="control-label"> <?=translate('academic_year')?> <span class="required">*</span></label>
                        <?php
                        $arrayYear = array("" => translate('select'));
                        $years = $this->db->get('schoolyear')->result();
                        foreach ($years as $year) {
                            $arrayYear[$year->id] = $year->school_year;
                        }
                        echo form_dropdown("session_id", $arrayYear, set_value('session_id', $global_config['session_id']), "class='form-control'
                        data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
                        ?>
                    <span class="error"></span>
                </div>
            </div>
            <div class="col-md-4 mb-sm">
                <div class="form-group">
                    <label class="control-label"> <?=translate('register_no')?> <span class="required">*</span></label>
                    <input type="text" class="form-control" name="register_no" value="<?=set_value('register_no')?>" autocomplete="off" />
                    <span class="error"></span>
                </div>
            </div>
        </div>
        <input type="hidden" name="grade_scale" value="<?php echo $page_data['grade_scale']; ?>">
        <input type="hidden" name="attendance" value="<?php echo $page_data['attendance']; ?>">
        <button type="submit" class="btn btn-1" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-plus-circle"></i> <?=translate('submit')?></button>
    </div>
    <?php echo form_close(); ?>
    <div class="row">
        <div class="col-md-12">
            <div id="card_holder" style="display: none;">
                <div class="box2 form-box">
                    <button type="button" class="btn btn-1" id="print"><i class="fas fa-print"></i> <?=translate('print')?></button>
                    <div id="card"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Container Ends -->

<script type="text/javascript">
    $(document).ready(function () {
        $('form.printIn').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');
            var $this = $(this);
            $("#card_holder").hide();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    btn.button('loading');
                },
                success: function (data) {
                    $('.error').html("");
                    if (data.status == "fail") {
                        $.each(data.error, function (index, value) {
                            $this.find("[name='" + index + "']").parents('.form-group').find('.error').html(value);
                        });
                        btn.button('reset');
                    } else if (data.status == 0) {
                        btn.button('reset');
                        swal({
                            toast: true,
                            position: 'top-end',
                            type: 'error',
                            title: data.error,
                            confirmButtonClass: 'btn btn-default',
                            buttonsStyling: false,
                            timer: 8000
                        });
                    } else {
                        $('#card').html(data.card_data);
                        $("#card_holder").show(200);
                    }
                },
                error: function () {
                    btn.button('reset');
                    alert("An error occured, please try again");
                },
                complete: function () {
                    btn.button('reset');
                }
            });
        });

        $('#print').on('click', function(e){
            certificate_printElem('card', false);
        });
    });
</script>
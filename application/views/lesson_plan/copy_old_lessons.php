<?php
$is_bn = ($this->session->userdata('set_lang') == 'bengali');
?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#copy" data-toggle="tab"><i class="fas fa-copy"></i> <?=translate('copy_old_lessons')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="copy" class="tab-pane active">
				<?php echo form_open(base_url('lesson_plan/copy_old_lessons'), array('class' => 'frm-submit')); ?>
					<div class="row">
						<!-- Left: Source (Copy From) -->
						<div class="col-md-6">
							<div class="panel panel-bordered" style="border: 1px solid #bbf7d0; border-radius: 8px;">
								<div class="panel-heading" style="background: #f0fdf4; border-bottom: 2px solid #007F3D; padding: 12px 16px;">
									<h4 class="panel-title" style="color: #166534; font-weight: 700;">
										<i class="fas fa-arrow-circle-right text-success"></i> <?=translate('copy_from') ?? ($is_bn ? 'যেখান থেকে কপি করবেন (উৎস)' : 'Copy From (Source)')?>
									</h4>
								</div>
								<div class="panel-body" style="padding: 16px;">
									<div class="form-group mb-md">
										<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
										<?php
											$arrayClass = $this->app_lib->getClass($branch_id);
											echo form_dropdown("from_class_id", $arrayClass, "", "class='form-control' id='from_class_id'
											data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
										?>
										<span class="error"></span>
									</div>
									<div class="form-group mb-md">
										<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
										<select name="from_section_id" id="from_section_id" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
											<option value=""><?=translate('select_class_first')?></option>
										</select>
										<span class="error"></span>
									</div>
									<div class="form-group mb-md">
										<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
										<select name="from_subject_id" id="from_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
											<option value=""><?=translate('select_class_first')?></option>
										</select>
										<span class="error"></span>
									</div>
								</div>
							</div>
						</div>

						<!-- Right: Destination (Copy To) -->
						<div class="col-md-6">
							<div class="panel panel-bordered" style="border: 1px solid #bfdbfe; border-radius: 8px;">
								<div class="panel-heading" style="background: #eff6ff; border-bottom: 2px solid #2563eb; padding: 12px 16px;">
									<h4 class="panel-title" style="color: #1e40af; font-weight: 700;">
										<i class="fas fa-download text-primary"></i> <?=translate('copy_to') ?? ($is_bn ? 'যেখানে কপি করবেন (গন্তব্য)' : 'Copy To (Destination)')?>
									</h4>
								</div>
								<div class="panel-body" style="padding: 16px;">
									<div class="form-group mb-md">
										<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
										<?php
											echo form_dropdown("to_class_id", $arrayClass, "", "class='form-control' id='to_class_id'
											data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
										?>
										<span class="error"></span>
									</div>
									<div class="form-group mb-md">
										<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
										<select name="to_section_id" id="to_section_id" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
											<option value=""><?=translate('select_class_first')?></option>
										</select>
										<span class="error"></span>
									</div>
									<div class="form-group mb-md">
										<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
										<select name="to_subject_id" id="to_subject_id" class="form-control" data-plugin-selectTwo data-width="100%">
											<option value=""><?=translate('select_class_first')?></option>
										</select>
										<span class="error"></span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Topics and Lessons Selection Box (Dynamically Loaded) -->
					<div class="row">
						<div class="col-md-12">
							<div id="topicsContainer">
								<div class="alert alert-subtle alert-info text-center" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 25px; color: #64748b;">
									<i class="fas fa-hand-pointer fa-2x mb-xs text-primary"></i>
									<p style="margin: 6px 0 0; font-size: 14px; font-weight: 500;">
										<?=translate('select_source_subject_to_view_lessons') ?? ($is_bn ? 'বাম পাশের উৎস ফরম থেকে শ্রেণি, শাখা ও বিষয় নির্বাচন করলে সংশ্লিষ্ট পাঠ ও টপিকগুলো এখানে প্রদর্শিত হবে।' : 'Select source Class, Section and Subject on the left to view available topics and lessons.')?>
									</p>
								</div>
							</div>
							<span class="error" id="topics_error"></span>
						</div>
					</div>

					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-offset-4 col-md-4">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-copy"></i> <?=translate('copy_old_lessons')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function () {
	// Source Class -> Sections & Subjects
	$('#from_class_id').on('change', function() {
		var classID = $(this).val();
		getSectionByClass(classID, '#from_section_id');
		getSubjectByClass(classID, '#from_subject_id');
		$('#topicsContainer').html('<div class="alert alert-subtle alert-info text-center" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; color: #64748b;"><i class="fas fa-hand-pointer fa-2x mb-xs text-primary"></i><p style="margin: 6px 0 0; font-size: 14px; font-weight: 500;"><?=translate("select_source_subject_to_view_lessons") ?? ($is_bn ? "অনুগ্রহ করে বিষয় নির্বাচন করুন।" : "Please select subject.")?></p></div>');
	});

	$('#from_section_id, #from_subject_id').on('change', function() {
		loadSourceTopics();
	});

	// Destination Class -> Sections & Subjects
	$('#to_class_id').on('change', function() {
		var classID = $(this).val();
		getSectionByClass(classID, '#to_section_id');
		getSubjectByClass(classID, '#to_subject_id');
	});

	function getSectionByClass(class_id, selector) {
		$.ajax({
			url: base_url + 'ajax/getSectionByClass',
			type: 'POST',
			data: { class_id: class_id },
			success: function (response) {
				$(selector).html(response).trigger('change.select2');
			}
		});
	}

	function getSubjectByClass(class_id, selector) {
		$.ajax({
			url: base_url + 'ajax/getDataByBranch',
			type: 'POST',
			data: {
				table: 'subject',
				branch_id: '<?=$branch_id?>',
				class_id: class_id
			},
			success: function (response) {
				$(selector).html(response).trigger('change.select2');
			}
		});
	}

	function loadSourceTopics() {
		var classID = $('#from_class_id').val();
		var sectionID = $('#from_section_id').val();
		var subjectID = $('#from_subject_id').val();

		if (classID && sectionID && subjectID) {
			$('#topicsContainer').html('<div class="text-center" style="padding: 30px;"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p style="margin-top: 8px;"><?=translate("loading")?>...</p></div>');
			$.ajax({
				url: base_url + 'lesson_plan/getTopicsForCopy',
				type: 'POST',
				data: {
					class_id: classID,
					section_id: sectionID,
					subject_id: subjectID
				},
				success: function(response) {
					$('#topicsContainer').html(response);
				},
				error: function() {
					$('#topicsContainer').html('<div class="alert alert-danger text-center"><?=translate("error_loading_data")?></div>');
				}
			});
		}
	}
});
</script>

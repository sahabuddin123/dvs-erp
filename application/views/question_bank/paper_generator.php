<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
?>

<div class="row">
	<div class="col-md-5">
		<!-- Generation Constraints Form -->
		<section class="panel">
			<header class="panel-heading" style="background: linear-gradient(135deg, #007F3D 0%, #15803d 100%); color: #fff;">
				<h2 class="panel-title" style="color: #fff;">
					<i class="fas fa-magic"></i> <?=$is_bn ? 'প্রশ্নপত্র জেনারেটরের সেটিংস ও শর্তাবলী' : 'Paper Generator Constraints'?>
				</h2>
			</header>
			<div class="panel-body">
				<form id="paperConstraintForm">
					<!-- Exam Meta -->
					<div class="form-group mb-sm">
						<label class="control-label"><?=$is_bn ? 'পরীক্ষার শিরোনাম' : 'Exam Title'?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="title" id="gen_title" placeholder="<?=$is_bn ? 'যেমন: ১ম সাময়িক পরীক্ষা ২০২৬ বা বার্ষিক পরীক্ষা' : 'e.g. Annual Examination 2026'?>" value="<?=$is_bn ? 'বার্ষিক পরীক্ষা ২০২৬' : 'Annual Examination 2026'?>" required />
					</div>

					<div class="row">
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?> <span class="required">*</span></label>
								<?php
									echo form_dropdown("class_id", $classList, set_value('class_id'), "class='form-control' id='gen_class_id'
									data-plugin-selectTwo data-width='100%' required");
								?>
							</div>
						</div>
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?> <span class="required">*</span></label>
								<select class="form-control" name="subject_id" id="gen_subject_id" data-plugin-selectTwo data-width="100%" required>
									<option value=""><?=translate('select')?></option>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'সময় (মিনিট)' : 'Duration (Mins)'?> <span class="required">*</span></label>
								<input type="number" class="form-control" name="duration_minutes" value="120" required />
							</div>
						</div>
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'পূর্ণমান (Total Marks)' : 'Total Marks'?> <span class="required">*</span></label>
								<input type="number" step="1" class="form-control" name="total_marks" value="100" required />
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'পরীক্ষার তারিখ' : 'Exam Date'?></label>
								<input type="text" class="form-control" name="exam_date" data-plugin-datepicker value="<?=date('Y-m-d')?>" />
							</div>
						</div>
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'পাস নম্বর' : 'Pass Marks'?></label>
								<input type="number" class="form-control" name="pass_marks" value="40" />
							</div>
						</div>
					</div>

					<div class="form-group mb-sm">
						<label class="control-label"><?=$is_bn ? 'প্রশ্নপত্রের বিন্যাস' : 'Paper Layout'?></label>
						<select class="form-control" name="paper_format" data-plugin-selectTwo data-width="100%">
							<option value="single_column"><?=$is_bn ? 'এক কলাম বিশিষ্ট (Standard Single Column)' : 'Single Column Format'?></option>
							<option value="two_column"><?=$is_bn ? 'দুই কলাম বিশিষ্ট (Compact Two Column)' : 'Two Column Format'?></option>
						</select>
					</div>

					<!-- Question Types Breakdown -->
					<div class="headers-line" style="margin-top: 15px; margin-bottom: 10px;">
						<i class="fas fa-list-ol"></i> <?=$is_bn ? 'প্রশ্ন সংখ্যার বিভাজন (Question Breakdown)' : 'Question Quantity Breakdown'?>
					</div>

					<div class="row">
						<div class="col-xs-6 mb-xs">
							<label style="font-size: 12px; margin-bottom: 2px;"><?=$is_bn ? 'এমসিকিউ সংখ্যা' : 'MCQs Qty'?></label>
							<input type="number" min="0" class="form-control input-sm" name="mcq_qty" value="15" />
						</div>
						<div class="col-xs-6 mb-xs">
							<label style="font-size: 12px; margin-bottom: 2px;"><?=$is_bn ? 'সত্য/মিথ্যা সংখ্যা' : 'True/False Qty'?></label>
							<input type="number" min="0" class="form-control input-sm" name="tf_qty" value="5" />
						</div>
					</div>

					<div class="row">
						<div class="col-xs-6 mb-xs">
							<label style="font-size: 12px; margin-bottom: 2px;"><?=$is_bn ? 'শূন্যস্থান পূরণ' : 'Fill Blanks Qty'?></label>
							<input type="number" min="0" class="form-control input-sm" name="blank_qty" value="5" />
						</div>
						<div class="col-xs-6 mb-xs">
							<label style="font-size: 12px; margin-bottom: 2px;"><?=$is_bn ? 'বাম-ডান মিলকরণ' : 'Matching Qty'?></label>
							<input type="number" min="0" class="form-control input-sm" name="match_qty" value="1" />
						</div>
					</div>

					<div class="row">
						<div class="col-xs-6 mb-sm">
							<label style="font-size: 12px; margin-bottom: 2px;"><?=$is_bn ? 'সংক্ষিপ্ত প্রশ্ন' : 'Short Answer Qty'?></label>
							<input type="number" min="0" class="form-control input-sm" name="short_qty" value="5" />
						</div>
						<div class="col-xs-6 mb-sm">
							<label style="font-size: 12px; margin-bottom: 2px;"><?=$is_bn ? 'রচনামূলক/সৃজনশীল' : 'Long/Creative Qty'?></label>
							<input type="number" min="0" class="form-control input-sm" name="long_qty" value="3" />
						</div>
					</div>

					<!-- General Instructions -->
					<div class="form-group mb-md">
						<label class="control-label"><?=$is_bn ? 'পরীক্ষার্থীদের জন্য সাধারণ নির্দেশাবলী' : 'General Instructions'?></label>
						<textarea class="form-control" name="instructions" rows="2"><?=$is_bn ? 'সবগুলো প্রশ্নের উত্তর দিতে হবে। ডান পাশের সংখ্যা প্রশ্নের পূর্ণমান জ্ঞাপক।' : 'Answer all questions. Figures in the right margin indicate full marks.'?></textarea>
					</div>

					<button type="submit" class="btn btn-success btn-block" id="btnGeneratePaper" style="font-size: 14px; font-weight: 700; padding: 10px;">
						<i class="fas fa-cogs"></i> <?=$is_bn ? 'এক ক্লিকে স্বয়ংক্রিয় প্রশ্নপত্র সাজান' : 'Generate Paper in 1-Click'?>
					</button>
				</form>
			</div>
		</section>
	</div>

	<!-- Generated Live Paper Preview & Final Save -->
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fas fa-file-alt text-primary"></i> <?=$is_bn ? 'প্রশ্নপত্রের লাইভ ড্রাফট ও পর্যালোচনা' : 'Live Paper Draft Preview'?></h2>
			</header>
			<div class="panel-body" style="min-height: 520px;">
				<!-- Empty Placeholder -->
				<div id="paperEmptyState" class="text-center" style="padding: 110px 20px; color: #94a3b8;">
					<i class="fas fa-file-invoice fa-4x" style="opacity: 0.3; margin-bottom: 15px;"></i>
					<h4 style="color: #64748b; font-weight: 600;">
						<?=$is_bn ? 'কোনো প্রশ্নপত্র জেনারেট করা হয়নি' : 'No Paper Generated Yet'?>
					</h4>
					<p style="max-width: 380px; margin: 0 auto; font-size: 13px;">
						<?=$is_bn ? 'বাম পাশের শর্তাবলী পূরণ করে "এক ক্লিকে স্বয়ংক্রিয় প্রশ্নপত্র সাজান" বাটনে চাপুন।' : 'Fill out constraints on the left and click "Generate Paper in 1-Click".'?>
					</p>
				</div>

				<!-- Loading Spinner -->
				<div id="paperLoadingState" class="text-center" style="display: none; padding: 120px 20px;">
					<i class="fas fa-spinner fa-spin fa-3x text-primary" style="margin-bottom: 15px;"></i>
					<h4 style="font-weight: 700; color: #1e293b;">
						<?=$is_bn ? 'প্রশ্ন ভাণ্ডার থেকে উপযুক্ত প্রশ্ন বাছাই হচ্ছে...' : 'Selecting matching questions from bank...'?>
					</h4>
				</div>

				<!-- Live Preview Area -->
				<div id="paperPreviewArea" style="display: none;">
					<?php echo form_open('question_bank/paper_save', array('id' => 'saveFinalPaperForm')); ?>
					<input type="hidden" name="class_id" id="save_class_id" />
					<input type="hidden" name="subject_id" id="save_subject_id" />
					<input type="hidden" name="title" id="save_title" />
					<input type="hidden" name="duration_minutes" id="save_duration" />
					<input type="hidden" name="total_marks" id="save_total_marks" />
					<input type="hidden" name="pass_marks" id="save_pass_marks" />
					<input type="hidden" name="exam_date" id="save_exam_date" />
					<input type="hidden" name="paper_format" id="save_paper_format" />
					<input type="hidden" name="instructions" id="save_instructions" />

					<!-- Header Preview Box -->
					<div class="text-center" style="border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 18px;">
						<h3 style="margin: 0; font-weight: 800; color: #007F3D;">DREAM VALLEY SCHOOL</h3>
						<h4 id="previewExamTitle" style="margin: 4px 0; font-weight: 700;">-</h4>
						<div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 13px; margin-top: 8px;">
							<span><?=$is_bn ? 'শ্রেণি: ' : 'Class: '?><span id="previewClassName">-</span></span>
							<span><?=$is_bn ? 'বিষয়: ' : 'Subject: '?><span id="previewSubjectName">-</span></span>
							<span><?=$is_bn ? 'সময়: ' : 'Time: '?><span id="previewDuration">-</span></span>
							<span><?=$is_bn ? 'পূর্ণমান: ' : 'Full Marks: '?><span id="previewMarks">-</span></span>
						</div>
					</div>

					<div id="previewSectionsList"></div>

					<div class="text-right" style="margin-top: 25px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
						<button type="submit" class="btn btn-primary btn-lg" style="font-weight: 700;">
							<i class="fas fa-check-circle"></i> <?=$is_bn ? 'প্রশ্নপত্রটি সংরক্ষণ করুন ও প্রিন্ট ভিউ দেখুন' : 'Save Paper & Go to Print View'?>
						</button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#gen_class_id').on('change', function() {
			var classID = $(this).val();
			$('#gen_subject_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#gen_subject_id').html(data);
					}
				});
			}
		});

		$('#paperConstraintForm').on('submit', function(e) {
			e.preventDefault();
			var classID = $('#gen_class_id').val();
			var subjectID = $('#gen_subject_id').val();
			if (!classID || !subjectID) {
				alert('<?=$is_bn ? "অনুগ্রহ করে শ্রেণি ও বিষয় নির্বাচন করুন" : "Please select Class and Subject"?>');
				return;
			}

			$('#paperEmptyState').hide();
			$('#paperPreviewArea').hide();
			$('#paperLoadingState').show();

			$.ajax({
				url: "<?=base_url('question_bank/paper_generate_preview_ajax')?>",
				type: "POST",
				data: $(this).serialize(),
				dataType: "json",
				success: function(res) {
					$('#paperLoadingState').hide();
					if (res.status === 'success' && res.sections && res.sections.length > 0) {
						renderLivePaperDraft(res.sections);
					} else {
						$('#paperEmptyState').show();
						alert('<?=$is_bn ? "নির্বাচিত শ্রেণি ও বিষয়ের জন্য পর্যাপ্ত প্রশ্ন পাওয়া যায়নি। দয়া করে প্রথমে প্রশ্ন ভাণ্ডারে প্রশ্ন যোগ করুন বা এআই জেনারেটর ব্যবহার করুন।" : "Not enough questions found in repository for this criteria. Please add questions first."?>');
					}
				},
				error: function() {
					$('#paperLoadingState').hide();
					$('#paperEmptyState').show();
					alert('Error generating preview');
				}
			});
		});
	});

	function renderLivePaperDraft(sections) {
		// Populate hidden form inputs
		$('#save_class_id').val($('#gen_class_id').val());
		$('#save_subject_id').val($('#gen_subject_id').val());
		$('#save_title').val($('#gen_title').val());
		$('#save_duration').val($('input[name="duration_minutes"]').val());
		$('#save_total_marks').val($('input[name="total_marks"]').val());
		$('#save_pass_marks').val($('input[name="pass_marks"]').val());
		$('#save_exam_date').val($('input[name="exam_date"]').val());
		$('#save_paper_format').val($('select[name="paper_format"]').val());
		$('#save_instructions').val($('textarea[name="instructions"]').val());

		// Header textual preview
		$('#previewExamTitle').text($('#gen_title').val());
		$('#previewClassName').text($('#gen_class_id option:selected').text());
		$('#previewSubjectName').text($('#gen_subject_id option:selected').text());
		$('#previewDuration').text($('input[name="duration_minutes"]').val() + ' <?=$is_bn ? "মিনিট" : "Mins"?>');
		$('#previewMarks').text($('input[name="total_marks"]').val());

		var html = '';
		$.each(sections, function(sIdx, sec) {
			html += '<div class="paper-section mb-lg" style="margin-bottom: 20px;">';
			html += '<div style="background: #f1f5f9; padding: 6px 12px; border-left: 4px solid #007F3D; margin-bottom: 12px;">';
			html += '<strong style="font-size: 14px; color: #0f172a;">' + escapeHtml(sec.name) + '</strong>';
			if (sec.instructions) {
				html += '<span style="font-size: 12px; color: #64748b; margin-left: 10px;">(' + escapeHtml(sec.instructions) + ')</span>';
			}
			html += '</div>';

			$.each(sec.questions, function(qIdx, q) {
				var qNum = qIdx + 1;
				html += '<div class="paper-q-item" style="margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px dashed #e2e8f0;">';
				html += '<div style="display: flex; justify-content: space-between; align-items: flex-start;">';
				html += '<div style="flex-grow: 1;">';
				html += '<strong>' + qNum + '. </strong> ' + escapeHtml(q.question_text);
				html += '</div>';
				html += '<div style="font-weight: 700; color: #007F3D; margin-left: 15px; white-space: nowrap;">';
				html += '[' + (q.marks ? q.marks : '1') + ']';
				html += '</div>';
				html += '</div>';

				// Hidden inputs to link this question upon submission
				html += '<input type="hidden" name="selected_questions[' + q.id + '][selected]" value="1" />';
				html += '<input type="hidden" name="selected_questions[' + q.id + '][section]" value="' + escapeHtml(sec.name) + '" />';
				html += '<input type="hidden" name="selected_questions[' + q.id + '][section_instructions]" value="' + escapeHtml(sec.instructions) + '" />';
				html += '<input type="hidden" name="selected_questions[' + q.id + '][marks]" value="' + (q.marks ? q.marks : '1') + '" />';

				// MCQ options display
				if (q.type === 'mcq' && q.options) {
					html += '<div class="row" style="margin-top: 6px; padding-left: 20px;">';
					$.each(q.options, function(optIdx, opt) {
						html += '<div class="col-xs-6" style="margin-bottom: 4px; font-size: 12.5px;">';
						html += '(' + opt.option_key + ') ' + escapeHtml(opt.option_text);
						html += '</div>';
					});
					html += '</div>';
				} else if (q.type === 'matching' && q.options) {
					html += '<table class="table table-bordered table-condensed mt-xs mb-none" style="font-size: 12px; margin-left: 20px; width: 90%;">';
					$.each(q.options, function(optIdx, opt) {
						html += '<tr><td width="50%">' + escapeHtml(opt.option_text) + '</td><td width="50%">' + escapeHtml(opt.match_pair) + '</td></tr>';
					});
					html += '</table>';
				}

				html += '</div>';
			});

			html += '</div>';
		});

		$('#previewSectionsList').html(html);
		$('#paperPreviewArea').show();
	}

	function escapeHtml(text) {
		if (!text) return '';
		return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
	}
</script>

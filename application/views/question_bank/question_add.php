<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
?>

<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<div class="pull-right">
					<a href="<?=base_url('question_bank')?>" class="btn btn-default btn-xs">
						<i class="fas fa-arrow-left"></i> <?=$is_bn ? 'প্রশ্ন ভাণ্ডারে ফিরুন' : 'Back to Repository'?>
					</a>
				</div>
				<h2 class="panel-title"><i class="fas fa-plus-circle text-primary"></i> <?=$is_bn ? 'নতুন প্রশ্ন যোগ করুন' : 'Add New Question'?></h2>
			</header>

			<?php echo form_open('question_bank/question_save', array('class' => 'form-horizontal form-bordered validate', 'id' => 'qbQuestionForm')); ?>
			<div class="panel-body">
				<!-- Step 1: Academic Organization -->
				<div class="headers-line mt-none">
					<i class="fas fa-graduation-cap"></i> <?=$is_bn ? 'একাডেমিক তথ্য ও শ্রেণি বিন্যাস' : 'Academic Classification'?>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							echo form_dropdown("class_id", $classList, set_value('class_id'), "class='form-control' id='qb_class_id'
							data-plugin-selectTwo data-width='100%' required");
						?>
						<span class="error"></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="subject_id" id="qb_subject_id" data-plugin-selectTwo data-width="100%" required>
							<option value=""><?=translate('select')?></option>
						</select>
						<span class="error"></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'অধ্যায় (ঐচ্ছিক)' : 'Chapter (Optional)'?></label>
					<div class="col-md-6">
						<div class="input-group">
							<select class="form-control" name="chapter_id" id="qb_chapter_id" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select')?></option>
							</select>
							<span class="input-group-btn">
								<a href="<?=base_url('question_bank/chapters_topics')?>" target="_blank" class="btn btn-default" title="<?=$is_bn ? 'নতুন অধ্যায় যোগ করুন' : 'Manage Chapters'?>">
									<i class="fas fa-plus"></i>
								</a>
							</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'টপিক / বিষয়বস্তু' : 'Topic (Optional)'?></label>
					<div class="col-md-6">
						<select class="form-control" name="topic_id" id="qb_topic_id" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
						</select>
					</div>
				</div>

				<!-- Step 2: Question Type & Difficulty -->
				<div class="headers-line">
					<i class="fas fa-sliders-h"></i> <?=$is_bn ? 'প্রশ্নের ধরন, মান ও বৈশিষ্ট্য' : 'Question Parameters & Taxonomy'?>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'প্রশ্নের ধরন' : 'Question Type'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="type" id="qb_type_select" data-plugin-selectTwo data-width="100%" required>
							<?php foreach ($types as $key => $name): ?>
								<option value="<?=$key?>"><?=$name?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'কঠিন্যের স্তর' : 'Difficulty Level'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="difficulty" data-plugin-selectTwo data-width="100%" required>
							<?php foreach ($difficulties as $dKey => $dVal): ?>
								<option value="<?=$dKey?>" <?=$dKey == 'medium' ? 'selected' : ''?>><?=$dVal['label']?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'ব্লুমের ট্যাক্সোনমি' : 'Bloom\'s Taxonomy'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="taxonomy" data-plugin-selectTwo data-width="100%" required>
							<?php foreach ($taxonomies as $tKey => $tVal): ?>
								<option value="<?=$tKey?>" <?=$tKey == 'remembering' ? 'selected' : ''?>><?=$tVal['label']?></option>
							<?php endforeach; ?>
						</select>
						<span class="help-block" style="font-size: 11px; margin-bottom: 0;">
							<?=$is_bn ? 'জ্ঞানমূলক, অনুধাবনমূলক, প্রয়োগমূলক বা উচ্চতর দক্ষতার স্তর চিহ্নিত করুন' : 'Identify the cognitive level: Knowledge, Comprehension, Application, or Higher Order'?>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'প্রশ্নের মান (নম্বর)' : 'Marks'?> <span class="required">*</span></label>
					<div class="col-md-3">
						<input type="number" step="0.25" min="0.25" class="form-control" name="marks" id="qb_marks" value="1.00" required />
					</div>
					<label class="col-md-1 control-label text-right" style="padding-left: 0;"><?=$is_bn ? 'নেগেটিভ মার্ক' : 'Neg Mark'?></label>
					<div class="col-md-2">
						<input type="number" step="0.25" min="0" class="form-control" name="negative_marks" value="0.00" />
					</div>
				</div>

				<!-- Step 3: Question Text & Content -->
				<div class="headers-line">
					<i class="fas fa-edit"></i> <?=$is_bn ? 'প্রশ্নের মূল বিবরণ ও উত্তর' : 'Question Statement & Options'?>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'প্রশ্নের মূল টেক্সট' : 'Question Statement'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<textarea class="form-control" name="question_text" rows="4" placeholder="<?=$is_bn ? 'এখানে মূল প্রশ্নটি লিখুন... (সৃজনশীল প্রশ্নের ক্ষেত্রে উদ্দীপক সহ লিখুন)' : 'Enter the question text here...'?>" required></textarea>
						<span class="error"></span>
					</div>
				</div>

				<!-- Dynamic Area: Multiple Choice Questions (MCQ) -->
				<div id="type_area_mcq" class="qb-type-pane">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'এমসিকিউ বিকল্পসমূহ' : 'MCQ Options'?></label>
						<div class="col-md-8">
							<div class="alert alert-subtle alert-info" style="padding: 8px 12px; margin-bottom: 12px; font-size: 12px;">
								<i class="fas fa-info-circle"></i> <?=$is_bn ? 'অপশনের বাম পাশের রেডিও বাটনে ক্লিক করে সঠিক উত্তরটি চিহ্নিত করুন।' : 'Select the radio button next to the correct answer.'?>
							</div>

							<div id="mcq_options_container">
								<?php for ($i = 0; $i < 4; $i++): $letter = chr(65 + $i); ?>
									<div class="input-group mb-sm mcq-opt-row">
										<span class="input-group-addon" style="background: #f1f5f9; font-weight: 700; width: 45px;">
											<?=$letter?>
										</span>
										<input type="text" class="form-control" name="mcq_options[]" placeholder="<?=$is_bn ? 'বিকল্প ' . $letter : 'Option ' . $letter?>" required />
										<span class="input-group-addon" style="background: #f8fafc; cursor: pointer;" title="<?=$is_bn ? 'সঠিক উত্তর হিসেবে চিহ্নিত করুন' : 'Mark as correct'?>">
											<label style="margin: 0; cursor: pointer;">
												<input type="radio" name="mcq_correct" value="<?=$i?>" <?=$i === 0 ? 'checked' : ''?> />
												<span style="font-size: 11px; margin-left: 4px; font-weight: 600; color: #166534;"><?=$is_bn ? 'সঠিক উত্তর' : 'Correct'?></span>
											</label>
										</span>
									</div>
								<?php endfor; ?>
							</div>

							<button type="button" class="btn btn-default btn-xs" onclick="addMcqOption()">
								<i class="fas fa-plus"></i> <?=$is_bn ? 'আরেকটি বিকল্প যোগ করুন' : 'Add Another Option'?>
							</button>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: True / False -->
				<div id="type_area_true_false" class="qb-type-pane" style="display: none;">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'সঠিক উত্তর' : 'Correct Answer'?></label>
						<div class="col-md-6">
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="tf_true" name="tf_correct" value="true" checked />
								<label for="tf_true" style="font-weight: 700; color: #15803d;"><?=$is_bn ? 'সত্য (True)' : 'True'?></label>
							</div>
							<div class="radio-custom radio-danger radio-inline" style="margin-left: 20px;">
								<input type="radio" id="tf_false" name="tf_correct" value="false" />
								<label for="tf_false" style="font-weight: 700; color: #b91c1c;"><?=$is_bn ? 'মিথ্যা (False)' : 'False'?></label>
							</div>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: Fill in the Blanks -->
				<div id="type_area_fill_blank" class="qb-type-pane" style="display: none;">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'শূন্যস্থানের সঠিক শব্দ' : 'Blank Answers'?></label>
						<div class="col-md-8">
							<div id="blank_container">
								<div class="input-group mb-sm blank-row">
									<span class="input-group-addon"><?=$is_bn ? '১ম শূন্যস্থান' : 'Blank 1'?></span>
									<input type="text" class="form-control" name="blank_answers[]" placeholder="<?=$is_bn ? 'সঠিক শব্দটি লিখুন' : 'Correct word or term'?>" />
								</div>
							</div>
							<button type="button" class="btn btn-default btn-xs" onclick="addBlankRow()">
								<i class="fas fa-plus"></i> <?=$is_bn ? 'আরেকটি শূন্যস্থান যোগ করুন' : 'Add Another Blank'?>
							</button>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: Matching Pairs -->
				<div id="type_area_matching" class="qb-type-pane" style="display: none;">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'মিলকরণ জোড়াসমূহ' : 'Matching Pairs'?></label>
						<div class="col-md-8">
							<div class="alert alert-subtle alert-info" style="padding: 8px 12px; margin-bottom: 12px; font-size: 12px;">
								<i class="fas fa-info-circle"></i> <?=$is_bn ? 'বাম কলামে প্রথম অংশ এবং ডান কলামে তার সংশ্লিষ্ট সঠিক অংশ লিখুন।' : 'Enter Column A item on the left and matching Column B item on the right.'?>
							</div>
							<div id="match_container">
								<?php for ($m = 1; $m <= 3; $m++): ?>
									<div class="row mb-sm match-row">
										<div class="col-xs-6">
											<input type="text" class="form-control" name="match_left[]" placeholder="<?=$is_bn ? 'কলাম ক (আইটেম ' . $m . ')' : 'Column A (Item ' . $m . ')'?>" />
										</div>
										<div class="col-xs-6">
											<input type="text" class="form-control" name="match_right[]" placeholder="<?=$is_bn ? 'কলাম খ (সঠিক মিল ' . $m . ')' : 'Column B (Match ' . $m . ')'?>" />
										</div>
									</div>
								<?php endfor; ?>
							</div>
							<button type="button" class="btn btn-default btn-xs" onclick="addMatchRow()">
								<i class="fas fa-plus"></i> <?=$is_bn ? 'আরেকটি জোড়া যোগ করুন' : 'Add Another Pair'?>
							</button>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: Short / Descriptive / Long Answer -->
				<div id="type_area_written" class="qb-type-pane" style="display: none;">
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'নমুনা উত্তর বা মূল্যায়ন গাইড' : 'Model Answer & Rubric'?></label>
						<div class="col-md-8">
							<textarea class="form-control" name="model_answer" rows="4" placeholder="<?=$is_bn ? 'শিক্ষক বা পরীক্ষকের জন্য আদর্শ নমুনা উত্তর বা নম্বর বিভাজনের নিয়ম লিখুন...' : 'Enter model answer or marking guide for teachers...'?>"></textarea>
						</div>
					</div>
				</div>

				<!-- Explanation / Hints -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'ব্যাখ্যা বা সমাধান সূত্র (ঐচ্ছিক)' : 'Explanation / Hint'?></label>
					<div class="col-md-8">
						<textarea class="form-control" name="explanation" rows="2" placeholder="<?=$is_bn ? 'শিক্ষার্থীদের বোঝার সুবিধার্থে পাঠ্যবই বা সূত্রের রেফারেন্স...' : 'Reference from textbook or conceptual explanation...'?>"></textarea>
					</div>
				</div>
			</div>

			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-3 col-md-8">
						<button type="submit" class="btn btn-primary" id="btnSaveQuestion">
							<i class="fas fa-save"></i> <?=$is_bn ? 'সংরক্ষণ করুন' : 'Save Question'?>
						</button>
						<a href="<?=base_url('question_bank')?>" class="btn btn-default" style="margin-left: 8px;">
							<?=$is_bn ? 'বাতিল' : 'Cancel'?>
						</a>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Dependent Dropdown: Class -> Subject
		$('#qb_class_id').on('change', function() {
			var classID = $(this).val();
			$('#qb_subject_id').html('<option value=""><?=translate("select")?></option>');
			$('#qb_chapter_id').html('<option value=""><?=translate("select")?></option>');
			$('#qb_topic_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#qb_subject_id').html(data);
					}
				});
			}
		});

		// Dependent Dropdown: Subject -> Chapters
		$('#qb_subject_id').on('change', function() {
			var classID = $('#qb_class_id').val();
			var subjectID = $(this).val();
			$('#qb_chapter_id').html('<option value=""><?=translate("select")?></option>');
			$('#qb_topic_id').html('<option value=""><?=translate("select")?></option>');
			if (classID && subjectID) {
				$.ajax({
					url: "<?=base_url('question_bank/get_chapters_by_subject')?>",
					type: "POST",
					data: {class_id: classID, subject_id: subjectID},
					success: function(data) {
						$('#qb_chapter_id').html(data);
					}
				});
			}
		});

		// Dependent Dropdown: Chapter -> Topics
		$('#qb_chapter_id').on('change', function() {
			var chapterID = $(this).val();
			$('#qb_topic_id').html('<option value=""><?=translate("select")?></option>');
			if (chapterID) {
				$.ajax({
					url: "<?=base_url('question_bank/get_topics_by_chapter')?>",
					type: "POST",
					data: {chapter_id: chapterID},
					success: function(data) {
						$('#qb_topic_id').html(data);
					}
				});
			}
		});

		// Toggle Question Type Options
		$('#qb_type_select').on('change', function() {
			var qType = $(this).val();
			$('.qb-type-pane').hide();

			if (qType === 'mcq') {
				$('#type_area_mcq').show();
				$('#qb_marks').val('1.00');
			} else if (qType === 'true_false') {
				$('#type_area_true_false').show();
				$('#qb_marks').val('1.00');
			} else if (qType === 'fill_blank') {
				$('#type_area_fill_blank').show();
				$('#qb_marks').val('1.00');
			} else if (qType === 'matching') {
				$('#type_area_matching').show();
				$('#qb_marks').val('4.00');
			} else if (qType === 'short_answer') {
				$('#type_area_written').show();
				$('#qb_marks').val('2.00');
			} else if (qType === 'long_answer') {
				$('#type_area_written').show();
				$('#qb_marks').val('10.00');
			}
		});
	});

	// Dynamic MCQ options
	function addMcqOption() {
		var count = $('#mcq_options_container .mcq-opt-row').length;
		if (count >= 8) {
			alert('Maximum 8 options allowed.');
			return;
		}
		var letter = String.fromCharCode(65 + count);
		var html = '<div class="input-group mb-sm mcq-opt-row">' +
			'<span class="input-group-addon" style="background: #f1f5f9; font-weight: 700; width: 45px;">' + letter + '</span>' +
			'<input type="text" class="form-control" name="mcq_options[]" placeholder="<?=$is_bn ? "বিকল্প " : "Option "?>' + letter + '" required />' +
			'<span class="input-group-addon" style="background: #f8fafc; cursor: pointer;">' +
			'<label style="margin: 0; cursor: pointer;"><input type="radio" name="mcq_correct" value="' + count + '" /><span style="font-size: 11px; margin-left: 4px; font-weight: 600; color: #166534;"><?=$is_bn ? "সঠিক উত্তর" : "Correct"?></span></label>' +
			'</span>' +
			'<span class="input-group-btn"><button type="button" class="btn btn-danger" onclick="$(this).closest(\'.mcq-opt-row\').remove()"><i class="fas fa-trash"></i></button></span>' +
			'</div>';
		$('#mcq_options_container').append(html);
	}

	function addBlankRow() {
		var count = $('#blank_container .blank-row').length + 1;
		var html = '<div class="input-group mb-sm blank-row">' +
			'<span class="input-group-addon"><?=$is_bn ? "শূন্যস্থান " : "Blank "?>' + count + '</span>' +
			'<input type="text" class="form-control" name="blank_answers[]" placeholder="<?=$is_bn ? "সঠিক শব্দ" : "Correct word"?>" />' +
			'<span class="input-group-btn"><button type="button" class="btn btn-danger" onclick="$(this).closest(\'.blank-row\').remove()"><i class="fas fa-trash"></i></button></span>' +
			'</div>';
		$('#blank_container').append(html);
	}

	function addMatchRow() {
		var count = $('#match_container .match-row').length + 1;
		var html = '<div class="row mb-sm match-row">' +
			'<div class="col-xs-5"><input type="text" class="form-control" name="match_left[]" placeholder="<?=$is_bn ? "কলাম ক" : "Column A"?> (' + count + ')" /></div>' +
			'<div class="col-xs-5"><input type="text" class="form-control" name="match_right[]" placeholder="<?=$is_bn ? "কলাম খ" : "Column B"?> (' + count + ')" /></div>' +
			'<div class="col-xs-2"><button type="button" class="btn btn-danger btn-block" onclick="$(this).closest(\'.match-row\').remove()"><i class="fas fa-trash"></i></button></div>' +
			'</div>';
		$('#match_container').append(html);
	}
</script>

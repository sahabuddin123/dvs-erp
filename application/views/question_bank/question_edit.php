<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$q = $question;
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
				<h2 class="panel-title"><i class="fas fa-edit text-warning"></i> <?=$is_bn ? 'প্রশ্ন সম্পাদনা করুন' : 'Edit Question'?></h2>
			</header>

			<?php echo form_open('question_bank/question_save', array('class' => 'form-horizontal form-bordered validate', 'id' => 'qbQuestionEditForm')); ?>
			<input type="hidden" name="id" value="<?=$q['id']?>" />

			<div class="panel-body">
				<!-- Step 1: Academic Organization -->
				<div class="headers-line mt-none">
					<i class="fas fa-graduation-cap"></i> <?=$is_bn ? 'একাডেমিক তথ্য ও শ্রেণি বিন্যাস' : 'Academic Classification'?>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							echo form_dropdown("class_id", $classList, set_value('class_id', $q['class_id']), "class='form-control' id='qb_class_id'
							data-plugin-selectTwo data-width='100%' required");
						?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="subject_id" id="qb_subject_id" data-plugin-selectTwo data-width="100%" required>
							<option value=""><?=translate('select')?></option>
							<?php foreach ($subjectList as $sub): ?>
								<option value="<?=$sub['subject_id']?>" <?=$q['subject_id'] == $sub['subject_id'] ? 'selected' : ''?>><?=html_escape($sub['subjectname'])?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'অধ্যায় (ঐচ্ছিক)' : 'Chapter (Optional)'?></label>
					<div class="col-md-6">
						<select class="form-control" name="chapter_id" id="qb_chapter_id" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($chapterList as $ch): ?>
								<option value="<?=$ch['id']?>" <?=$q['chapter_id'] == $ch['id'] ? 'selected' : ''?>><?=html_escape((!empty($ch['chapter_no']) ? $ch['chapter_no'] . ': ' : '') . $ch['name'])?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'টপিক / বিষয়বস্তু' : 'Topic (Optional)'?></label>
					<div class="col-md-6">
						<select class="form-control" name="topic_id" id="qb_topic_id" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($topicList as $tp): ?>
								<option value="<?=$tp['id']?>" <?=$q['topic_id'] == $tp['id'] ? 'selected' : ''?>><?=html_escape($tp['name'])?></option>
							<?php endforeach; ?>
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
								<option value="<?=$key?>" <?=$q['type'] == $key ? 'selected' : ''?>><?=$name?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'কঠিন্যের স্তর' : 'Difficulty Level'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="difficulty" data-plugin-selectTwo data-width="100%" required>
							<?php foreach ($difficulties as $dKey => $dVal): ?>
								<option value="<?=$dKey?>" <?=$q['difficulty'] == $dKey ? 'selected' : ''?>><?=$dVal['label']?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'ব্লুমের ট্যাক্সোনমি' : 'Bloom\'s Taxonomy'?> <span class="required">*</span></label>
					<div class="col-md-6">
						<select class="form-control" name="taxonomy" data-plugin-selectTwo data-width="100%" required>
							<?php foreach ($taxonomies as $tKey => $tVal): ?>
								<option value="<?=$tKey?>" <?=$q['taxonomy'] == $tKey ? 'selected' : ''?>><?=$tVal['label']?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'প্রশ্নের মান (নম্বর)' : 'Marks'?> <span class="required">*</span></label>
					<div class="col-md-3">
						<input type="number" step="0.25" min="0.25" class="form-control" name="marks" id="qb_marks" value="<?=$q['marks']?>" required />
					</div>
					<label class="col-md-1 control-label text-right" style="padding-left: 0;"><?=$is_bn ? 'নেগেটিভ মার্ক' : 'Neg Mark'?></label>
					<div class="col-md-2">
						<input type="number" step="0.25" min="0" class="form-control" name="negative_marks" value="<?=$q['negative_marks']?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'অনুমোদন স্ট্যাটাস' : 'Status'?></label>
					<div class="col-md-6">
						<select class="form-control" name="status" data-plugin-selectTwo data-width="100%">
							<option value="approved" <?=$q['status'] == 'approved' ? 'selected' : ''?>><?=$is_bn ? 'অনুমোদিত (Approved)' : 'Approved'?></option>
							<option value="review" <?=$q['status'] == 'review' ? 'selected' : ''?>><?=$is_bn ? 'পর্যালোচনায় (Review)' : 'Under Review'?></option>
							<option value="draft" <?=$q['status'] == 'draft' ? 'selected' : ''?>><?=$is_bn ? 'ড্রাফট (Draft)' : 'Draft'?></option>
							<option value="rejected" <?=$q['status'] == 'rejected' ? 'selected' : ''?>><?=$is_bn ? 'বাতিল (Rejected)' : 'Rejected'?></option>
						</select>
					</div>
				</div>

				<!-- Step 3: Question Text & Content -->
				<div class="headers-line">
					<i class="fas fa-edit"></i> <?=$is_bn ? 'প্রশ্নের মূল বিবরণ ও উত্তর' : 'Question Statement & Options'?>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'প্রশ্নের মূল টেক্সট' : 'Question Statement'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<textarea class="form-control" name="question_text" rows="4" required><?=html_escape($q['question_text'])?></textarea>
					</div>
				</div>

				<!-- Dynamic Area: MCQ -->
				<div id="type_area_mcq" class="qb-type-pane" <?=$q['type'] !== 'mcq' ? 'style="display:none;"' : ''?>>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'এমসিকিউ বিকল্পসমূহ' : 'MCQ Options'?></label>
						<div class="col-md-8">
							<div id="mcq_options_container">
								<?php 
								$opts = ($q['type'] === 'mcq' && !empty($q['options'])) ? $q['options'] : array();
								if (empty($opts)) {
									for ($i = 0; $i < 4; $i++) {
										$opts[] = array('option_key' => chr(65 + $i), 'option_text' => '', 'is_correct' => $i === 0 ? 1 : 0);
									}
								}
								foreach ($opts as $idx => $opt): 
									$letter = chr(65 + $idx);
								?>
									<div class="input-group mb-sm mcq-opt-row">
										<span class="input-group-addon" style="background: #f1f5f9; font-weight: 700; width: 45px;"><?=$letter?></span>
										<input type="text" class="form-control" name="mcq_options[]" value="<?=html_escape($opt['option_text'])?>" required />
										<span class="input-group-addon" style="background: #f8fafc; cursor: pointer;">
											<label style="margin: 0; cursor: pointer;">
												<input type="radio" name="mcq_correct" value="<?=$idx?>" <?=$opt['is_correct'] ? 'checked' : ''?> />
												<span style="font-size: 11px; margin-left: 4px; font-weight: 600; color: #166534;"><?=$is_bn ? 'সঠিক উত্তর' : 'Correct'?></span>
											</label>
										</span>
										<?php if ($idx >= 4): ?>
											<span class="input-group-btn"><button type="button" class="btn btn-danger" onclick="$(this).closest('.mcq-opt-row').remove()"><i class="fas fa-trash"></i></button></span>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
							<button type="button" class="btn btn-default btn-xs" onclick="addMcqOption()">
								<i class="fas fa-plus"></i> <?=$is_bn ? 'আরেকটি বিকল্প যোগ করুন' : 'Add Another Option'?>
							</button>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: True / False -->
				<div id="type_area_true_false" class="qb-type-pane" <?=$q['type'] !== 'true_false' ? 'style="display:none;"' : ''?>>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'সঠিক উত্তর' : 'Correct Answer'?></label>
						<div class="col-md-6">
							<?php
								$tfCorrect = 'true';
								if ($q['type'] === 'true_false' && !empty($q['options'])) {
									foreach ($q['options'] as $opt) {
										if ($opt['option_key'] === 'false' && $opt['is_correct']) {
											$tfCorrect = 'false';
										}
									}
								}
							?>
							<div class="radio-custom radio-success radio-inline">
								<input type="radio" id="tf_true" name="tf_correct" value="true" <?=$tfCorrect === 'true' ? 'checked' : ''?> />
								<label for="tf_true" style="font-weight: 700; color: #15803d;"><?=$is_bn ? 'সত্য (True)' : 'True'?></label>
							</div>
							<div class="radio-custom radio-danger radio-inline" style="margin-left: 20px;">
								<input type="radio" id="tf_false" name="tf_correct" value="false" <?=$tfCorrect === 'false' ? 'checked' : ''?> />
								<label for="tf_false" style="font-weight: 700; color: #b91c1c;"><?=$is_bn ? 'মিথ্যা (False)' : 'False'?></label>
							</div>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: Fill in Blanks -->
				<div id="type_area_fill_blank" class="qb-type-pane" <?=$q['type'] !== 'fill_blank' ? 'style="display:none;"' : ''?>>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'শূন্যস্থানের সঠিক শব্দ' : 'Blank Answers'?></label>
						<div class="col-md-8">
							<div id="blank_container">
								<?php 
								$blanks = ($q['type'] === 'fill_blank' && !empty($q['options'])) ? $q['options'] : array(array('option_text' => ''));
								foreach ($blanks as $bIdx => $bOpt): 
								?>
									<div class="input-group mb-sm blank-row">
										<span class="input-group-addon"><?=$is_bn ? 'শূন্যস্থান ' . ($bIdx + 1) : 'Blank ' . ($bIdx + 1)?></span>
										<input type="text" class="form-control" name="blank_answers[]" value="<?=html_escape($bOpt['option_text'])?>" />
										<?php if ($bIdx > 0): ?>
											<span class="input-group-btn"><button type="button" class="btn btn-danger" onclick="$(this).closest('.blank-row').remove()"><i class="fas fa-trash"></i></button></span>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
							<button type="button" class="btn btn-default btn-xs" onclick="addBlankRow()">
								<i class="fas fa-plus"></i> <?=$is_bn ? 'আরেকটি শূন্যস্থান যোগ করুন' : 'Add Another Blank'?>
							</button>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: Matching -->
				<div id="type_area_matching" class="qb-type-pane" <?=$q['type'] !== 'matching' ? 'style="display:none;"' : ''?>>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'মিলকরণ জোড়াসমূহ' : 'Matching Pairs'?></label>
						<div class="col-md-8">
							<div id="match_container">
								<?php 
								$matches = ($q['type'] === 'matching' && !empty($q['options'])) ? $q['options'] : array();
								if (empty($matches)) {
									for ($m = 1; $m <= 3; $m++) {
										$matches[] = array('option_text' => '', 'match_pair' => '');
									}
								}
								foreach ($matches as $mIdx => $mOpt):
								?>
									<div class="row mb-sm match-row">
										<div class="col-xs-5"><input type="text" class="form-control" name="match_left[]" value="<?=html_escape($mOpt['option_text'])?>" placeholder="<?=$is_bn ? 'কলাম ক' : 'Column A'?>" /></div>
										<div class="col-xs-5"><input type="text" class="form-control" name="match_right[]" value="<?=html_escape($mOpt['match_pair'])?>" placeholder="<?=$is_bn ? 'কলাম খ' : 'Column B'?>" /></div>
										<div class="col-xs-2"><button type="button" class="btn btn-danger btn-block" onclick="$(this).closest('.match-row').remove()"><i class="fas fa-trash"></i></button></div>
									</div>
								<?php endforeach; ?>
							</div>
							<button type="button" class="btn btn-default btn-xs" onclick="addMatchRow()">
								<i class="fas fa-plus"></i> <?=$is_bn ? 'আরেকটি জোড়া যোগ করুন' : 'Add Another Pair'?>
							</button>
						</div>
					</div>
				</div>

				<!-- Dynamic Area: Written -->
				<div id="type_area_written" class="qb-type-pane" <?=($q['type'] !== 'short_answer' && $q['type'] !== 'long_answer') ? 'style="display:none;"' : ''?>>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=$is_bn ? 'নমুনা উত্তর বা মূল্যায়ন গাইড' : 'Model Answer & Rubric'?></label>
						<div class="col-md-8">
							<textarea class="form-control" name="model_answer" rows="4"><?=(!empty($q['options']) && isset($q['options'][0])) ? html_escape($q['options'][0]['option_text']) : ''?></textarea>
						</div>
					</div>
				</div>

				<!-- Explanation -->
				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'ব্যাখ্যা বা সমাধান সূত্র (ঐচ্ছিক)' : 'Explanation / Hint'?></label>
					<div class="col-md-8">
						<textarea class="form-control" name="explanation" rows="2"><?=html_escape($q['explanation'])?></textarea>
					</div>
				</div>
			</div>

			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-3 col-md-8">
						<button type="submit" class="btn btn-primary">
							<i class="fas fa-save"></i> <?=$is_bn ? 'পরিবর্তন সংরক্ষণ করুন' : 'Update Question'?>
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
		$('#qb_class_id').on('change', function() {
			var classID = $(this).val();
			$('#qb_subject_id').html('<option value=""><?=translate("select")?></option>');
			$('#qb_chapter_id').html('<option value=""><?=translate("select")?></option>');
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

		$('#qb_subject_id').on('change', function() {
			var classID = $('#qb_class_id').val();
			var subjectID = $(this).val();
			$('#qb_chapter_id').html('<option value=""><?=translate("select")?></option>');
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

		$('#qb_type_select').on('change', function() {
			var qType = $(this).val();
			$('.qb-type-pane').hide();
			if (qType === 'mcq') $('#type_area_mcq').show();
			else if (qType === 'true_false') $('#type_area_true_false').show();
			else if (qType === 'fill_blank') $('#type_area_fill_blank').show();
			else if (qType === 'matching') $('#type_area_matching').show();
			else if (qType === 'short_answer' || qType === 'long_answer') $('#type_area_written').show();
		});
	});

	function addMcqOption() {
		var count = $('#mcq_options_container .mcq-opt-row').length;
		if (count >= 8) { alert('Maximum 8 options allowed.'); return; }
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

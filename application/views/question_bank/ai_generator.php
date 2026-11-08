<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$has_api_key = !empty($ai_settings['api_key']);
?>

<div class="row">
	<div class="col-md-5">
		<!-- AI Generator Settings Card -->
		<section class="panel">
			<header class="panel-heading" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff;">
				<div class="pull-right">
					<a href="<?=base_url('question_bank/settings')?>" class="btn btn-xs" style="background: rgba(255,255,255,0.2); color: #fff;">
						<i class="fas fa-cog"></i> <?=$is_bn ? 'এআই কনফিগ' : 'AI Config'?>
					</a>
				</div>
				<h2 class="panel-title" style="color: #fff;">
					<i class="fas fa-magic text-warning"></i> <?=$is_bn ? 'এআই দিয়ে প্রশ্ন প্রণয়ন' : 'AI Question Generator'?>
				</h2>
			</header>
			<div class="panel-body">
				<?php if (!$has_api_key): ?>
					<div class="alert alert-subtle alert-warning mb-md" style="padding: 10px 14px; font-size: 12px; background: #fffbeb; border: 1px solid #fde68a; color: #92400e;">
						<i class="fas fa-info-circle"></i> <?=$is_bn ? 'কোনো এআই এপিআই কি সংরক্ষিত নেই। সিস্টেম স্বয়ংক্রিয়ভাবে কারিকুলাম ভিত্তিক স্মার্ট প্রশ্ন তৈরি করবে। জেমিনি এপিআই কি যুক্ত করতে উপরের <strong>এআই কনফিগ</strong> অপশনে যান।' : 'No AI API Key configured. System will generate smart curriculum-based questions. Configure Gemini API Key in <strong>AI Config</strong> for direct live LLM generation.'?>
					</div>
				<?php endif; ?>

				<form id="aiGenerateForm">
					<div class="form-group mb-sm">
						<label class="control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?> <span class="required">*</span></label>
						<?php
							echo form_dropdown("class_id", $classList, set_value('class_id'), "class='form-control' id='ai_class_id'
							data-plugin-selectTwo data-width='100%' required");
						?>
					</div>

					<div class="form-group mb-sm">
						<label class="control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?> <span class="required">*</span></label>
						<select class="form-control" name="subject_id" id="ai_subject_id" data-plugin-selectTwo data-width="100%" required>
							<option value=""><?=translate('select')?></option>
						</select>
					</div>

					<div class="form-group mb-sm">
						<label class="control-label"><?=$is_bn ? 'অধ্যায় বা বিষয়বস্তুর নাম (Topic)' : 'Chapter / Topic Name'?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="topic" id="ai_topic" placeholder="<?=$is_bn ? 'যেমন: সালোকসংশ্লেষণ, ভগ্নাংশের যোগ, ১৯৭১ এর মুক্তিযুদ্ধ' : 'e.g. Photosynthesis, Fractions, Solar System'?>" required />
					</div>

					<div class="row">
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'প্রশ্নের ধরন' : 'Question Type'?></label>
								<select class="form-control" name="type" id="ai_type" data-plugin-selectTwo data-width="100%">
									<?php foreach ($types as $key => $name): ?>
										<option value="<?=$key?>"><?=$name?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'প্রশ্নের সংখ্যা' : 'Quantity'?></label>
								<select class="form-control" name="quantity" id="ai_qty" data-plugin-selectTwo data-width="100%">
									<option value="5" selected><?=$is_bn ? '৫ টি প্রশ্ন' : '5 Questions'?></option>
									<option value="10"><?=$is_bn ? '১০ টি প্রশ্ন' : '10 Questions'?></option>
									<option value="15"><?=$is_bn ? '১৫ টি প্রশ্ন' : '15 Questions'?></option>
									<option value="20"><?=$is_bn ? '২০ টি প্রশ্ন' : '20 Questions'?></option>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'কঠিন্যের স্তর' : 'Difficulty'?></label>
								<select class="form-control" name="difficulty" id="ai_diff" data-plugin-selectTwo data-width="100%">
									<?php foreach ($difficulties as $dKey => $dVal): ?>
										<option value="<?=$dKey?>" <?=$dKey == 'medium' ? 'selected' : ''?>><?=$dVal['label']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=$is_bn ? 'ট্যাক্সোনমি লেভেল' : 'Taxonomy'?></label>
								<select class="form-control" name="taxonomy" id="ai_tax" data-plugin-selectTwo data-width="100%">
									<?php foreach ($taxonomies as $tKey => $tVal): ?>
										<option value="<?=$tKey?>" <?=$tKey == 'remembering' ? 'selected' : ''?>><?=$tVal['label']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group mb-sm">
						<label class="control-label"><?=$is_bn ? 'প্রশ্নের ভাষা' : 'Language'?></label>
						<select class="form-control" name="language" id="ai_lang" data-plugin-selectTwo data-width="100%">
							<option value="bengali" <?=$is_bn ? 'selected' : ''?>>বাংলা (Bengali)</option>
							<option value="english" <?=!$is_bn ? 'selected' : ''?>>English</option>
						</select>
					</div>

					<div class="form-group mb-md">
						<label class="control-label">
							<?=$is_bn ? 'পাঠ্যবইয়ের অনুচ্ছেদ বা কাস্টম টেক্সট (ঐচ্ছিক)' : 'Syllabus Excerpt or Reference Text (Optional)'?>
						</label>
						<textarea class="form-control" name="source_text" id="ai_source_text" rows="3" placeholder="<?=$is_bn ? 'পাঠ্যবইয়ের কোনো অনুচ্ছেদ বা কবিতা এখানে পেস্ট করলে এআই হুবহু ওই অনুচ্ছেদ থেকে প্রশ্ন তৈরি করবে...' : 'Paste textbook passage here to generate questions specifically from it...'?>"></textarea>
					</div>

					<button type="submit" class="btn btn-primary btn-block" id="btnAiGenerate" style="font-size: 14px; font-weight: 600; padding: 10px;">
						<i class="fas fa-magic"></i> <?=$is_bn ? 'এআই দিয়ে প্রশ্ন জেনারেট করুন' : 'Generate Questions with AI'?>
					</button>
				</form>
			</div>
		</section>
	</div>

	<!-- Results Preview & 1-Click Import Panel -->
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<div class="pull-right" id="aiImportActions" style="display: none;">
					<button type="button" class="btn btn-xs btn-success" onclick="importGeneratedQuestions()">
						<i class="fas fa-download"></i> <?=$is_bn ? 'প্রশ্ন ভাণ্ডারে যুক্ত করুন' : 'Import to Question Bank'?>
					</button>
				</div>
				<h2 class="panel-title"><i class="fas fa-eye text-primary"></i> <?=$is_bn ? 'জেনারেটকৃত প্রশ্নের ফলাফল ও পর্যালোচনা' : 'Generated Questions Preview'?></h2>
			</header>
			<div class="panel-body" id="aiResultsContainer" style="min-height: 480px;">
				<!-- Initial Empty State -->
				<div id="aiEmptyState" class="text-center" style="padding: 90px 20px; color: #94a3b8;">
					<i class="fas fa-robot fa-4x" style="opacity: 0.3; margin-bottom: 15px;"></i>
					<h4 style="color: #64748b; font-weight: 600; margin-bottom: 8px;">
						<?=$is_bn ? 'কোনো প্রশ্ন জেনারেট করা হয়নি' : 'No Questions Generated Yet'?>
					</h4>
					<p style="max-width: 360px; margin: 0 auto; font-size: 13px;">
						<?=$is_bn ? 'বাম পাশের ফর্মে বিষয় ও টপিক নির্বাচন করে "এআই দিয়ে প্রশ্ন জেনারেট করুন" বাটনে ক্লিক করুন।' : 'Select subject and topic from the left form, then click "Generate Questions with AI".'?>
					</p>
				</div>

				<!-- Loading State -->
				<div id="aiLoadingState" class="text-center" style="display: none; padding: 100px 20px;">
					<i class="fas fa-spinner fa-spin fa-3x text-primary" style="margin-bottom: 15px;"></i>
					<h4 style="font-weight: 700; color: #1e293b;">
						<?=$is_bn ? 'এআই প্রশ্ন তৈরি করছে...' : 'AI is Authoring Questions...'?>
					</h4>
					<p style="color: #64748b; font-size: 13px;">
						<?=$is_bn ? 'শিক্ষাক্রম ও ব্লুমের ট্যাক্সোনমি বিশ্লেষণ করে মানসম্মত প্রশ্নপত্র সাজানো হচ্ছে।' : 'Analyzing curriculum standards and Bloom\'s taxonomy to create quality items.'?>
					</p>
				</div>

				<!-- Generated Cards Container -->
				<div id="aiCardsContainer" style="display: none;"></div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
	var currentGeneratedQuestions = [];

	$(document).ready(function() {
		$('#ai_class_id').on('change', function() {
			var classID = $(this).val();
			$('#ai_subject_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#ai_subject_id').html(data);
					}
				});
			}
		});

		$('#aiGenerateForm').on('submit', function(e) {
			e.preventDefault();
			var classID = $('#ai_class_id').val();
			var subjectID = $('#ai_subject_id').val();
			var topic = $('#ai_topic').val();

			if (!classID || !subjectID || !topic) {
				alert('<?=$is_bn ? "অনুগ্রহ করে শ্রেণি, বিষয় এবং টপিক পূরণ করুন" : "Please select Class, Subject and Topic"?>');
				return;
			}

			$('#aiEmptyState').hide();
			$('#aiCardsContainer').hide().html('');
			$('#aiLoadingState').show();
			$('#btnAiGenerate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?=$is_bn ? "প্রশ্ন তৈরি হচ্ছে..." : "Generating..."?>');
			$('#aiImportActions').hide();

			$.ajax({
				url: "<?=base_url('question_bank/ai_generate_ajax')?>",
				type: "POST",
				data: $(this).serialize(),
				dataType: "json",
				success: function(res) {
					$('#aiLoadingState').hide();
					$('#btnAiGenerate').prop('disabled', false).html('<i class="fas fa-magic"></i> <?=$is_bn ? "এআই দিয়ে প্রশ্ন জেনারেট করুন" : "Generate Questions with AI"?>');

					if (res.status === 'success' || res.status === 'fallback') {
						currentGeneratedQuestions = res.data;
						renderGeneratedQuestions(res.data);
					} else {
						$('#aiEmptyState').show();
						alert(res.message || 'Error generating questions');
					}
				},
				error: function() {
					$('#aiLoadingState').hide();
					$('#aiEmptyState').show();
					$('#btnAiGenerate').prop('disabled', false).html('<i class="fas fa-magic"></i> <?=$is_bn ? "এআই দিয়ে প্রশ্ন জেনারেট করুন" : "Generate Questions with AI"?>');
					alert('<?=$is_bn ? "এআই সার্ভারের সাথে সংযোগে সমস্যা হয়েছে।" : "Connection error with AI server."?>');
				}
			});
		});
	});

	function renderGeneratedQuestions(questions) {
		if (!questions || questions.length === 0) {
			$('#aiEmptyState').show();
			return;
		}

		var html = '';
		$.each(questions, function(idx, q) {
			var num = idx + 1;
			html += '<div class="panel panel-bordered mb-md qb-gen-card" data-idx="' + idx + '" style="border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">';
			html += '<div class="panel-heading" style="background:#f8fafc; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">';
			html += '<div>';
			html += '<span class="badge" style="background:#007F3D; margin-right: 6px;">Q #' + num + '</span>';
			html += '<span class="label label-info" style="margin-right: 4px;">' + (q.type ? q.type.toUpperCase() : 'MCQ') + '</span>';
			html += '<span class="label label-warning">' + (q.difficulty ? q.difficulty : 'Medium') + '</span>';
			html += '</div>';
			html += '<div style="font-size: 11px; color:#64748b;">';
			html += '<i class="fas fa-star text-warning"></i> ' + (q.marks ? q.marks : '1.0') + ' <?=$is_bn ? "নম্বর" : "Marks"?>';
			html += '</div>';
			html += '</div>';

			html += '<div class="panel-body" style="padding: 14px;">';
			html += '<div class="form-group mb-sm">';
			html += '<textarea class="form-control gen-q-text" rows="2" style="font-weight: 600; font-size: 13.5px; color: #1e293b;">' + escapeHtml(q.question_text) + '</textarea>';
			html += '</div>';

			// Options based on Type
			if (q.type === 'mcq' && q.options) {
				html += '<div class="row" style="margin-top: 10px;">';
				$.each(q.options, function(oIdx, opt) {
					var isCorr = opt.is_correct ? true : false;
					html += '<div class="col-sm-6 mb-xs">';
					html += '<div class="input-group input-group-sm">';
					html += '<span class="input-group-addon" style="font-weight: 700; background: ' + (isCorr ? '#86efac' : '#f1f5f9') + ';">' + opt.key + '</span>';
					html += '<input type="text" class="form-control gen-opt-text" value="' + escapeHtml(opt.text) + '" />';
					html += '</div>';
					html += '</div>';
				});
				html += '</div>';
			} else if (q.type === 'true_false') {
				html += '<div style="margin-top: 8px;"><strong><?=$is_bn ? "সঠিক উত্তর: " : "Answer: "?></strong>';
				if (q.options) {
					$.each(q.options, function(oIdx, opt) {
						if (opt.is_correct) html += '<span class="label label-success" style="margin-left: 6px;">' + opt.text + '</span>';
					});
				}
				html += '</div>';
			} else if (q.type === 'fill_blank' && q.fill_blanks) {
				html += '<div style="margin-top: 8px;"><strong><?=$is_bn ? "শূন্যস্থান উত্তর: " : "Blank: "?></strong> <span class="badge" style="background:#dcfce7; color:#166534;">' + q.fill_blanks.join(', ') + '</span></div>';
			} else if (q.type === 'matching' && q.match_pairs) {
				html += '<table class="table table-bordered table-condensed mt-xs mb-none" style="font-size: 12px;">';
				$.each(q.match_pairs, function(pIdx, pair) {
					html += '<tr><td width="50%"><strong>' + escapeHtml(pair.left) + '</strong></td><td width="50%" style="color:#007F3D;"><i class="fas fa-arrow-right"></i> ' + escapeHtml(pair.right) + '</td></tr>';
				});
				html += '</table>';
			} else if (q.model_answer) {
				html += '<div style="background:#f1f5f9; padding: 8px 12px; border-radius: 4px; font-size: 12px; margin-top: 6px;"><strong><?=$is_bn ? "নমুনা উত্তর: " : "Model Answer: "?></strong>' + escapeHtml(q.model_answer) + '</div>';
			}

			if (q.explanation) {
				html += '<div style="margin-top: 8px; font-size: 11.5px; color: #1e40af;"><i class="fas fa-lightbulb"></i> ' + escapeHtml(q.explanation) + '</div>';
			}

			html += '</div></div>';
		});

		$('#aiCardsContainer').html(html).show();
		$('#aiImportActions').show();
	}

	function importGeneratedQuestions() {
		if (!currentGeneratedQuestions || currentGeneratedQuestions.length === 0) {
			alert('No questions to import.');
			return;
		}

		// Update any edited text in array
		$('.qb-gen-card').each(function() {
			var idx = $(this).data('idx');
			var newText = $(this).find('.gen-q-text').val();
			if (currentGeneratedQuestions[idx]) {
				currentGeneratedQuestions[idx].question_text = newText;
			}
		});

		var classID = $('#ai_class_id').val();
		var subjectID = $('#ai_subject_id').val();

		var btn = $('#aiImportActions button');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?=$is_bn ? "সংরক্ষণ হচ্ছে..." : "Importing..."?>');

		$.ajax({
			url: "<?=base_url('question_bank/ai_import_ajax')?>",
			type: "POST",
			data: {
				class_id: classID,
				subject_id: subjectID,
				questions_json: JSON.stringify(currentGeneratedQuestions)
			},
			dataType: "json",
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-download"></i> <?=$is_bn ? "প্রশ্ন ভাণ্ডারে যুক্ত করুন" : "Import to Question Bank"?>');
				if (res.status === 'success') {
					alert(res.message);
					window.location.href = "<?=base_url('question_bank')?>?class_id=" + classID + "&subject_id=" + subjectID;
				} else {
					alert(res.message);
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-download"></i> <?=$is_bn ? "প্রশ্ন ভাণ্ডারে যুক্ত করুন" : "Import to Question Bank"?>');
				alert('Error importing questions');
			}
		});
	}

	function escapeHtml(text) {
		if (!text) return '';
		return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
	}
</script>

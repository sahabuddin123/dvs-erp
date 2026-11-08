<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$can_add = get_permission('question_bank', 'is_add');
$can_edit = get_permission('question_bank', 'is_edit');
$can_del = get_permission('question_bank', 'is_delete');
$is_admin = (is_superadmin_loggedin() || is_admin_loggedin());
?>

<!-- Quick Stats Cards -->
<div class="row">
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fas fa-layer-group"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=$is_bn ? 'মোট সংগৃহীত প্রশ্ন' : 'Total Questions'?></h4>
							<div class="info">
								<strong class="amount"><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $stats['total']).'</span>' : $stats['total']?></strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-success">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-success">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=$is_bn ? 'অনুমোদিত প্রশ্ন' : 'Approved Questions'?></h4>
							<div class="info">
								<strong class="amount"><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $stats['approved']).'</span>' : $stats['approved']?></strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-warning">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-warning">
							<i class="fas fa-hourglass-half"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=$is_bn ? 'পর্যালোচনায় অপেক্ষমাণ' : 'Pending Review'?></h4>
							<div class="info">
								<strong class="amount"><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $stats['review']).'</span>' : $stats['review']?></strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-3 col-sm-6">
		<section class="panel panel-featured-left panel-featured-info">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-info">
							<i class="fas fa-file-invoice"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><?=$is_bn ? 'তৈরিকৃত প্রশ্নপত্র' : 'Exam Papers'?></h4>
							<div class="info">
								<strong class="amount"><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $stats['papers']).'</span>' : $stats['papers']?></strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- Filter Panel -->
<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions">
			<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
		</div>
		<h2 class="panel-title"><i class="fas fa-filter"></i> <?=$is_bn ? 'প্রশ্ন অনুসন্ধান ও ফিল্টারিং' : 'Filter Questions'?></h2>
	</header>
	<?php echo form_open('question_bank', array('method' => 'get')); ?>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?></label>
					<?php
						echo form_dropdown("class_id", $classList, set_value('class_id', $class_id), "class='form-control' id='filter_class_id'
						data-plugin-selectTwo data-width='100%'");
					?>
				</div>
			</div>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?></label>
					<select class="form-control" name="subject_id" id="filter_subject_id" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($subjectList as $sub): ?>
							<option value="<?=$sub['subject_id']?>" <?=$subject_id == $sub['subject_id'] ? 'selected' : ''?>><?=html_escape($sub['subjectname'])?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'অধ্যায়' : 'Chapter'?></label>
					<select class="form-control" name="chapter_id" id="filter_chapter_id" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($chapterList as $ch): ?>
							<option value="<?=$ch['id']?>" <?=$chapter_id == $ch['id'] ? 'selected' : ''?>><?=html_escape((!empty($ch['chapter_no']) ? $ch['chapter_no'] . ': ' : '') . $ch['name'])?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'প্রশ্নের ধরন' : 'Question Type'?></label>
					<select class="form-control" name="type" data-plugin-selectTwo data-width="100%">
						<option value=""><?=$is_bn ? 'সকল ধরন' : 'All Types'?></option>
						<?php foreach ($types as $key => $name): ?>
							<option value="<?=$key?>" <?=$type == $key ? 'selected' : ''?>><?=$name?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'কঠিন্যের স্তর' : 'Difficulty Level'?></label>
					<select class="form-control" name="difficulty" data-plugin-selectTwo data-width="100%">
						<option value=""><?=$is_bn ? 'সকল স্তর' : 'All Difficulties'?></option>
						<?php foreach ($difficulties as $dKey => $dVal): ?>
							<option value="<?=$dKey?>" <?=$difficulty == $dKey ? 'selected' : ''?>><?=$dVal['label']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'ট্যাক্সোনমি লেভেল' : 'Bloom\'s Taxonomy'?></label>
					<select class="form-control" name="taxonomy" data-plugin-selectTwo data-width="100%">
						<option value=""><?=$is_bn ? 'সকল ট্যাক্সোনমি' : 'All Cognitive Levels'?></option>
						<?php foreach ($taxonomies as $tKey => $tVal): ?>
							<option value="<?=$tKey?>" <?=$taxonomy == $tKey ? 'selected' : ''?>><?=$tVal['label']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'অনুমোদন স্ট্যাটাস' : 'Approval Status'?></label>
					<select class="form-control" name="status" data-plugin-selectTwo data-width="100%">
						<option value=""><?=$is_bn ? 'সকল স্ট্যাটাস' : 'All Statuses'?></option>
						<option value="approved" <?=$status == 'approved' ? 'selected' : ''?>><?=$is_bn ? 'অনুমোদিত' : 'Approved'?></option>
						<option value="review" <?=$status == 'review' ? 'selected' : ''?>><?=$is_bn ? 'পর্যালোচনায়' : 'Under Review'?></option>
						<option value="draft" <?=$status == 'draft' ? 'selected' : ''?>><?=$is_bn ? 'ড্রাফট' : 'Draft'?></option>
						<option value="rejected" <?=$status == 'rejected' ? 'selected' : ''?>><?=$is_bn ? 'বাতিলকৃত' : 'Rejected'?></option>
					</select>
				</div>
			</div>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'কীওয়ার্ড দিয়ে খুঁজুন' : 'Keyword Search'?></label>
					<input type="text" name="search" class="form-control" placeholder="<?=$is_bn ? 'যেমন: সালোকসংশ্লেষণ...' : 'Search question text...'?>" value="<?=html_escape($search)?>">
				</div>
			</div>
		</div>
	</div>
	<footer class="panel-footer text-right">
		<a href="<?=base_url('question_bank')?>" class="btn btn-default"><i class="fas fa-undo"></i> <?=$is_bn ? 'রিসেট' : 'Reset'?></a>
		<button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> <?=$is_bn ? 'ফিল্টার করুন' : 'Filter'?></button>
	</footer>
	<?php echo form_close(); ?>
</section>

<!-- Questions Repository Table -->
<section class="panel">
	<header class="panel-heading">
		<div class="pull-right">
			<?php if ($can_add || $is_admin): ?>
				<a href="<?=base_url('question_bank/ai_generator')?>" class="btn btn-xs btn-warning" style="font-weight: 600;">
					<i class="fas fa-magic"></i> <?=$is_bn ? 'এআই দিয়ে প্রশ্ন তৈরি করুন' : 'Generate with AI'?>
				</a>
				<a href="<?=base_url('question_bank/question_add')?>" class="btn btn-xs btn-primary">
					<i class="fas fa-plus-circle"></i> <?=$is_bn ? 'ম্যানুয়ালি প্রশ্ন যোগ করুন' : 'Add Question'?>
				</a>
			<?php endif; ?>
			<a href="<?=base_url('question_bank/paper_generator')?>" class="btn btn-xs btn-success">
				<i class="fas fa-file-invoice"></i> <?=$is_bn ? 'প্রশ্নপত্র তৈরি করুন' : 'Generate Paper'?>
			</a>
		</div>
		<h2 class="panel-title"><i class="fas fa-list-ul"></i> <?=$is_bn ? 'সংগৃহীত প্রশ্নাবলীর তালিকা' : 'Question Repository List'?></h2>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped mb-none table-export">
				<thead>
					<tr>
						<th width="40">#</th>
						<th><?=$is_bn ? 'প্রশ্নের বিবরণ' : 'Question'?></th>
						<th width="150"><?=$is_bn ? 'শ্রেণি ও বিষয়' : 'Class & Subject'?></th>
						<th width="130"><?=$is_bn ? 'প্রশ্নের ধরন' : 'Type'?></th>
						<th width="120"><?=$is_bn ? 'কঠিন্য ও ট্যাক্সোনমি' : 'Difficulty & Bloom'?></th>
						<th width="70" class="text-center"><?=$is_bn ? 'মান' : 'Marks'?></th>
						<th width="90" class="text-center"><?=$is_bn ? 'স্ট্যাটাস' : 'Status'?></th>
						<th width="130" class="text-center"><?=$is_bn ? 'অ্যাকশন' : 'Action'?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($questions)): ?>
						<?php $sl = 1; foreach ($questions as $q): ?>
							<tr>
								<td><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $sl++).'</span>' : $sl++?></td>
								<td>
									<div style="font-weight: 600; font-size: 13.5px; color: #1e293b; margin-bottom: 4px;">
										<?=nl2br(html_escape(mb_substr($q['question_text'], 0, 160))) . (mb_strlen($q['question_text']) > 160 ? '...' : '')?>
									</div>
									<div style="font-size: 11px; color: #64748b;">
										<?php if (!empty($q['chapter_name'])): ?>
											<span class="badge" style="background:#e0f2fe; color:#0369a1; padding:3px 7px;"><i class="fas fa-book-open"></i> <?=html_escape($q['chapter_name'])?></span>
										<?php endif; ?>
										<?php if (!empty($q['topic_name'])): ?>
											<span class="badge" style="background:#f3e8ff; color:#7e22ce; padding:3px 7px;"><i class="fas fa-tag"></i> <?=html_escape($q['topic_name'])?></span>
										<?php endif; ?>
										<?php if ($q['source'] === 'ai_generated'): ?>
											<span class="badge" style="background:#fef3c7; color:#b45309; padding:3px 7px;"><i class="fas fa-robot"></i> AI Generated</span>
										<?php endif; ?>
										<span style="margin-left: 5px;"><i class="far fa-user"></i> <?=html_escape(!empty($q['author_name']) ? $q['author_name'] : 'System')?></span>
									</div>
								</td>
								<td>
									<strong><?=html_escape($q['class_name'])?></strong><br>
									<span class="text-muted" style="font-size: 11.5px;"><?=html_escape($q['subject_name'])?></span>
								</td>
								<td>
									<span class="label label-info" style="font-size: 11px;">
										<?=isset($types[$q['type']]) ? $types[$q['type']] : ucfirst($q['type'])?>
									</span>
									<?php if ($q['type'] === 'mcq'): ?>
										<div style="font-size: 11px; color: #64748b; margin-top: 3px;">
											<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], count($q['options'])).'</span> টি অপশন' : count($q['options']) . ' Options'?>
										</div>
									<?php endif; ?>
								</td>
								<td>
									<?php 
										$dBadge = isset($difficulties[$q['difficulty']]) ? $difficulties[$q['difficulty']]['badge'] : 'label-default';
										$dLabel = isset($difficulties[$q['difficulty']]) ? $difficulties[$q['difficulty']]['label'] : ucfirst($q['difficulty']);
										$tLabel = isset($taxonomies[$q['taxonomy']]) ? $taxonomies[$q['taxonomy']]['label'] : ucfirst($q['taxonomy']);
									?>
									<span class="label <?=$dBadge?>" style="font-size: 10.5px; display:inline-block; margin-bottom: 2px;"><?=$dLabel?></span><br>
									<span class="text-muted" style="font-size: 10.5px;"><?=$tLabel?></span>
								</td>
								<td class="text-center font-weight-bold" style="font-size: 13px; color: #007F3D;">
									<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], rtrim(rtrim($q['marks'], '0'), '.')).'</span>' : rtrim(rtrim($q['marks'], '0'), '.')?>
								</td>
								<td class="text-center">
									<?php if ($q['status'] === 'approved'): ?>
										<span class="label label-success"><i class="fas fa-check"></i> <?=$is_bn ? 'অনুমোদিত' : 'Approved'?></span>
									<?php elseif ($q['status'] === 'review'): ?>
										<span class="label label-warning"><i class="fas fa-clock"></i> <?=$is_bn ? 'পর্যালোচনায়' : 'Review'?></span>
									<?php elseif ($q['status'] === 'rejected'): ?>
										<span class="label label-danger"><i class="fas fa-times"></i> <?=$is_bn ? 'বাতিল' : 'Rejected'?></span>
									<?php else: ?>
										<span class="label label-default"><?=$is_bn ? 'ড্রাফট' : 'Draft'?></span>
									<?php endif; ?>
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-default btn-circle icon" onclick="qbPreviewQuestion(<?=$q['id']?>)" title="<?=$is_bn ? 'বিস্তারিত দেখুন' : 'Preview'?>">
										<i class="fas fa-eye text-primary"></i>
									</button>

									<?php if ($can_edit || $is_admin): ?>
										<a href="<?=base_url('question_bank/question_edit/' . $q['id'])?>" class="btn btn-default btn-circle icon" title="<?=$is_bn ? 'সম্পাদনা করুন' : 'Edit'?>">
											<i class="fas fa-pen-nib text-warning"></i>
										</a>
									<?php endif; ?>

									<?php if ($can_del || $is_admin): ?>
										<?php echo btn_delete('question_bank/question_delete/' . $q['id']); ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="8" class="text-center text-muted" style="padding: 30px;">
								<i class="fas fa-folder-open fa-3x" style="opacity: 0.3; margin-bottom: 10px;"></i>
								<p><?=$is_bn ? 'কোনো প্রশ্ন পাওয়া যায়নি। উপরে নতুন প্রশ্ন তৈরি করুন বা এআই জেনারেটর ব্যবহার করুন।' : 'No questions found. Add questions manually or generate with AI.'?></p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<!-- Question Preview Modal -->
<div class="modal fade" id="qbPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="qbPreviewContent">
			<div class="modal-body text-center" style="padding: 40px;">
				<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
				<p style="margin-top: 10px;"><?=$is_bn ? 'প্রশ্ন লোড হচ্ছে...' : 'Loading question...'?></p>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filter_class_id').on('change', function() {
			var classID = $(this).val();
			$('#filter_subject_id').html('<option value=""><?=translate("select")?></option>');
			$('#filter_chapter_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#filter_subject_id').html(data);
					}
				});
			}
		});

		$('#filter_subject_id').on('change', function() {
			var classID = $('#filter_class_id').val();
			var subjectID = $(this).val();
			$('#filter_chapter_id').html('<option value=""><?=translate("select")?></option>');
			if (classID && subjectID) {
				$.ajax({
					url: "<?=base_url('question_bank/get_chapters_by_subject')?>",
					type: "POST",
					data: {class_id: classID, subject_id: subjectID},
					success: function(data) {
						$('#filter_chapter_id').html(data);
					}
				});
			}
		});
	});

	function qbPreviewQuestion(id) {
		$('#qbPreviewModal').modal('show');
		$('#qbPreviewContent').html('<div class="modal-body text-center" style="padding: 40px;"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p style="margin-top: 10px;"><?=$is_bn ? "প্রশ্ন লোড হচ্ছে..." : "Loading question..."?></p></div>');
		$.ajax({
			url: "<?=base_url('question_bank/question_preview_ajax/')?>" + id,
			type: "GET",
			success: function(res) {
				$('#qbPreviewContent').html(res);
			},
			error: function() {
				$('#qbPreviewContent').html('<div class="modal-body text-center text-danger" style="padding: 30px;"><i class="fas fa-exclamation-triangle fa-2x"></i><p style="margin-top: 10px;"><?=$is_bn ? "প্রশ্ন লোড করতে ব্যর্থ হয়েছে" : "Failed to load question"?></p></div>');
			}
		});
	}
</script>

<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$is_admin = (is_superadmin_loggedin() || is_admin_loggedin() || get_permission('question_bank', 'is_edit'));
$q = $question;
?>
<div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 6px 6px 0 0;">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title font-weight-bold" style="color: #1e293b;">
		<i class="fas fa-question-circle text-primary"></i> <?=$is_bn ? 'প্রশ্নের বিস্তারিত বিবরণ' : 'Question Preview'?>
		<span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:12px; margin-left: 8px;">
			<?=isset($types[$q['type']]) ? $types[$q['type']] : ucfirst($q['type'])?>
		</span>
	</h4>
</div>

<div class="modal-body" style="padding: 24px;">
	<!-- Meta Badges -->
	<div style="margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
		<span class="badge" style="background: #007F3D; color: #fff; padding: 5px 10px;">
			<i class="fas fa-school"></i> <?=html_escape($q['class_name'])?>
		</span>
		<span class="badge" style="background: #6366f1; color: #fff; padding: 5px 10px;">
			<i class="fas fa-book"></i> <?=html_escape($q['subject_name'])?>
		</span>
		<?php if (!empty($q['chapter_name'])): ?>
			<span class="badge" style="background: #0284c7; color: #fff; padding: 5px 10px;">
				<i class="fas fa-book-open"></i> <?=html_escape($q['chapter_name'])?>
			</span>
		<?php endif; ?>
		<?php if (!empty($q['topic_name'])): ?>
			<span class="badge" style="background: #9333ea; color: #fff; padding: 5px 10px;">
				<i class="fas fa-tag"></i> <?=html_escape($q['topic_name'])?>
			</span>
		<?php endif; ?>
		<?php 
			$dBadge = isset($difficulties[$q['difficulty']]) ? $difficulties[$q['difficulty']]['badge'] : 'label-default';
			$dLabel = isset($difficulties[$q['difficulty']]) ? $difficulties[$q['difficulty']]['label'] : ucfirst($q['difficulty']);
			$tLabel = isset($taxonomies[$q['taxonomy']]) ? $taxonomies[$q['taxonomy']]['label'] : ucfirst($q['taxonomy']);
		?>
		<span class="label <?=$dBadge?>" style="font-size: 11px; padding: 4px 8px;"><?=$dLabel?></span>
		<span class="label label-info" style="font-size: 11px; padding: 4px 8px;"><?=$tLabel?></span>
		<span class="badge" style="background: #f1f5f9; color: #007F3D; font-weight: 700; border: 1px solid #cbd5e1; padding: 5px 10px;">
			<?=$is_bn ? 'মান: ' . '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], rtrim(rtrim($q['marks'], '0'), '.')).'</span>' : 'Marks: ' . rtrim(rtrim($q['marks'], '0'), '.')?>
		</span>
	</div>

	<!-- Question Text -->
	<div style="background: #f8fafc; border-left: 4px solid #007F3D; border-radius: 6px; padding: 16px; margin-bottom: 20px;">
		<h5 style="margin-top: 0; font-weight: 700; color: #0f172a; font-size: 15px; line-height: 1.6;">
			<?=nl2br(html_escape($q['question_text']))?>
		</h5>
	</div>

	<!-- Options Presentation based on Type -->
	<?php if ($q['type'] === 'mcq' && !empty($q['options'])): ?>
		<h6 class="font-weight-bold" style="color: #475569; margin-bottom: 12px; font-size: 13px;">
			<i class="fas fa-check-square"></i> <?=$is_bn ? 'বিকল্পসমূহ (Options):' : 'Options:'?>
		</h6>
		<div class="row">
			<?php foreach ($q['options'] as $opt): ?>
				<div class="col-md-6 mb-sm">
					<div style="padding: 10px 14px; border-radius: 6px; border: 1px solid <?=$opt['is_correct'] ? '#86efac' : '#e2e8f0'?>; background: <?=$opt['is_correct'] ? '#f0fdf4' : '#fff'?>; display: flex; align-items: center; justify-content: space-between;">
						<div>
							<strong style="color: <?=$opt['is_correct'] ? '#15803d' : '#64748b'?>; margin-right: 8px;"><?=html_escape($opt['option_key'])?>.</strong>
							<span style="color: <?=$opt['is_correct'] ? '#166534' : '#1e293b'?>; font-weight: <?=$opt['is_correct'] ? '600' : 'normal'?>;"><?=html_escape($opt['option_text'])?></span>
						</div>
						<?php if ($opt['is_correct']): ?>
							<span class="label label-success" style="font-size: 11px;"><i class="fas fa-check"></i> <?=$is_bn ? 'সঠিক উত্তর' : 'Correct Answer'?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php elseif ($q['type'] === 'true_false' && !empty($q['options'])): ?>
		<h6 class="font-weight-bold" style="color: #475569; margin-bottom: 12px; font-size: 13px;">
			<i class="fas fa-toggle-on"></i> <?=$is_bn ? 'সঠিক উত্তর:' : 'Correct Answer:'?>
		</h6>
		<div style="display: flex; gap: 15px;">
			<?php foreach ($q['options'] as $opt): ?>
				<div style="padding: 10px 20px; border-radius: 6px; border: 1px solid <?=$opt['is_correct'] ? '#86efac' : '#e2e8f0'?>; background: <?=$opt['is_correct'] ? '#f0fdf4' : '#fff'?>;">
					<strong><?=html_escape($opt['option_text'])?></strong>
					<?php if ($opt['is_correct']): ?>
						<span class="label label-success" style="margin-left: 8px;"><i class="fas fa-check"></i></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php elseif ($q['type'] === 'fill_blank' && !empty($q['options'])): ?>
		<h6 class="font-weight-bold" style="color: #475569; margin-bottom: 12px; font-size: 13px;">
			<i class="fas fa-pen-alt"></i> <?=$is_bn ? 'শূন্যস্থানের সঠিক শব্দ:' : 'Correct Words for Blank:'?>
		</h6>
		<div style="display: flex; flex-wrap: wrap; gap: 8px;">
			<?php foreach ($q['options'] as $opt): ?>
				<span class="badge" style="background:#dcfce7; color:#15803d; font-size:13px; padding:6px 12px; border:1px solid #86efac;">
					<i class="fas fa-key"></i> <?=html_escape($opt['option_text'])?>
				</span>
			<?php endforeach; ?>
		</div>
	<?php elseif ($q['type'] === 'matching' && !empty($q['options'])): ?>
		<h6 class="font-weight-bold" style="color: #475569; margin-bottom: 12px; font-size: 13px;">
			<i class="fas fa-arrows-alt-h"></i> <?=$is_bn ? 'বাম-ডান মিলকরণের জোড়া:' : 'Matching Pairs:'?>
		</h6>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background: #f1f5f9;">
					<th width="50%"><?=$is_bn ? 'কলাম ক (Column A)' : 'Column A'?></th>
					<th width="50%"><?=$is_bn ? 'কলাম খ (Column B - Match)' : 'Column B - Match'?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($q['options'] as $opt): ?>
					<tr>
						<td><strong><?=html_escape($opt['option_text'])?></strong></td>
						<td style="color: #007F3D;"><i class="fas fa-arrow-right"></i> <?=html_escape($opt['match_pair'])?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php elseif (($q['type'] === 'short_answer' || $q['type'] === 'long_answer') && !empty($q['options'])): ?>
		<h6 class="font-weight-bold" style="color: #475569; margin-bottom: 8px; font-size: 13px;">
			<i class="fas fa-file-alt"></i> <?=$is_bn ? 'আদর্শ নমুনা উত্তর বা মূল্যায়ন নির্দেশিকা:' : 'Model Answer & Rubric:'?>
		</h6>
		<div style="background: #f1f5f9; border-radius: 6px; padding: 14px; font-size: 13px; color: #334155; line-height: 1.6;">
			<?=nl2br(html_escape($q['options'][0]['option_text']))?>
		</div>
	<?php endif; ?>

	<!-- Explanation / Hint -->
	<?php if (!empty($q['explanation'])): ?>
		<div style="margin-top: 18px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 12px 16px; color: #1e40af; font-size: 12.5px;">
			<strong><i class="fas fa-lightbulb"></i> <?=$is_bn ? 'ব্যাখ্যা বা সমাধান সূত্র:' : 'Explanation / Solution Hint:'?></strong>
			<p style="margin: 4px 0 0;"><?=nl2br(html_escape($q['explanation']))?></p>
		</div>
	<?php endif; ?>
</div>

<div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
	<div style="font-size: 12px; color: #64748b;">
		<i class="far fa-user"></i> <?=html_escape(!empty($q['author_name']) ? $q['author_name'] : 'System')?> | 
		<i class="far fa-clock"></i> <?=date('d M Y, h:i A', strtotime($q['created_at']))?>
	</div>
	<div>
		<?php if ($is_admin && $q['status'] === 'review'): ?>
			<button type="button" class="btn btn-sm btn-success" onclick="qbUpdateStatus(<?=$q['id']?>, 'approved')">
				<i class="fas fa-check"></i> <?=$is_bn ? 'অনুমোদন করুন' : 'Approve'?>
			</button>
			<button type="button" class="btn btn-sm btn-danger" onclick="qbUpdateStatus(<?=$q['id']?>, 'rejected')">
				<i class="fas fa-times"></i> <?=$is_bn ? 'বাতিল করুন' : 'Reject'?>
			</button>
		<?php endif; ?>
		<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?=$is_bn ? 'বন্ধ করুন' : 'Close'?></button>
	</div>
</div>

<script type="text/javascript">
	function qbUpdateStatus(id, status) {
		$.ajax({
			url: "<?=base_url('question_bank/question_status_change')?>",
			type: "POST",
			data: {question_id: id, status: status},
			dataType: "json",
			success: function(res) {
				if (res.status === 'success') {
					$('#qbPreviewModal').modal('hide');
					location.reload();
				} else {
					alert(res.message);
				}
			}
		});
	}
</script>

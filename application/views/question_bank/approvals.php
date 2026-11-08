<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
?>

<section class="panel">
	<header class="panel-heading">
		<div class="pull-right">
			<a href="<?=base_url('question_bank')?>" class="btn btn-default btn-xs">
				<i class="fas fa-arrow-left"></i> <?=$is_bn ? 'প্রশ্ন সংগ্রহশালায় ফিরুন' : 'Back to Repository'?>
			</a>
		</div>
		<h2 class="panel-title"><i class="fas fa-check-double text-success"></i> <?=$is_bn ? 'প্রশ্ন পর্যালোচনা ও অনুমোদন কিউ' : 'Question Review & Approval Queue'?></h2>
	</header>
	<div class="panel-body">
		<div class="alert alert-subtle alert-info" style="padding: 10px 14px; font-size: 13px; margin-bottom: 20px;">
			<i class="fas fa-info-circle"></i> <?=$is_bn ? 'শিক্ষকগণ কর্তৃক জমাকৃত নতুন প্রশ্নসমূহ এখানে পর্যালোচনার জন্য জমা হয়। অনুমোদন দিলে তা প্রধান প্রশ্ন ভাণ্ডারে যুক্ত হবে এবং পরীক্ষার প্রশ্নপত্রে ব্যবহার করা যাবে।' : 'Questions submitted by teachers appear here for departmental review and approval before inclusion in official examination papers.'?>
		</div>

		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped mb-none table-export">
				<thead>
					<tr>
						<th width="40">#</th>
						<th><?=$is_bn ? 'প্রশ্নের বিবরণ' : 'Question Statement'?></th>
						<th width="140"><?=$is_bn ? 'শ্রেণি ও বিষয়' : 'Class & Subject'?></th>
						<th width="120"><?=$is_bn ? 'লেখক / শিক্ষক' : 'Author Teacher'?></th>
						<th width="120"><?=$is_bn ? 'ধরন ও স্তর' : 'Type & Level'?></th>
						<th width="70" class="text-center"><?=$is_bn ? 'মান' : 'Marks'?></th>
						<th width="150" class="text-center"><?=$is_bn ? 'অনুমোদন অ্যাকশন' : 'Approval Actions'?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($questions)): ?>
						<?php $sl = 1; foreach ($questions as $q): ?>
							<tr id="review_row_<?=$q['id']?>">
								<td><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $sl++).'</span>' : $sl++?></td>
								<td>
									<div style="font-weight: 600; font-size: 13.5px; color: #1e293b; margin-bottom: 4px;">
										<?=nl2br(html_escape(mb_substr($q['question_text'], 0, 150))) . (mb_strlen($q['question_text']) > 150 ? '...' : '')?>
									</div>
									<div style="font-size: 11px; color: #64748b;">
										<i class="far fa-clock"></i> <?=date('d M Y, h:i A', strtotime($q['created_at']))?>
									</div>
								</td>
								<td>
									<strong><?=html_escape($q['class_name'])?></strong><br>
									<span class="text-muted" style="font-size: 11.5px;"><?=html_escape($q['subject_name'])?></span>
								</td>
								<td>
									<i class="far fa-user text-muted"></i> <?=html_escape(!empty($q['author_name']) ? $q['author_name'] : 'Teacher')?>
								</td>
								<td>
									<span class="label label-info" style="font-size: 10.5px;"><?=isset($types[$q['type']]) ? $types[$q['type']] : ucfirst($q['type'])?></span><br>
									<span class="text-muted" style="font-size: 10.5px;"><?=ucfirst($q['difficulty'])?></span>
								</td>
								<td class="text-center font-weight-bold" style="color: #007F3D;">
									<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], rtrim(rtrim($q['marks'], '0'), '.')).'</span>' : rtrim(rtrim($q['marks'], '0'), '.')?>
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-default btn-xs" onclick="qbPreviewQuestion(<?=$q['id']?>)" title="<?=$is_bn ? 'বিস্তারিত দেখুন' : 'Preview'?>">
										<i class="fas fa-eye text-primary"></i>
									</button>
									<button type="button" class="btn btn-success btn-xs" onclick="qbQuickApprove(<?=$q['id']?>, 'approved')" title="<?=$is_bn ? 'অনুমোদন দিন' : 'Approve'?>">
										<i class="fas fa-check"></i> <?=$is_bn ? 'অনুমোদন' : 'Approve'?>
									</button>
									<button type="button" class="btn btn-danger btn-xs" onclick="qbQuickApprove(<?=$q['id']?>, 'rejected')" title="<?=$is_bn ? 'বাতিল করুন' : 'Reject'?>">
										<i class="fas fa-times"></i>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="7" class="text-center text-muted" style="padding: 40px;">
								<i class="fas fa-check-circle fa-3x" style="color: #10b981; opacity: 0.6; margin-bottom: 10px;"></i>
								<h4 style="font-weight: 600; color: #1e293b;"><?=$is_bn ? 'পর্যালোচনার জন্য কোনো প্রশ্ন জমা নেই' : 'No Questions Pending Review'?></h4>
								<p><?=$is_bn ? 'সব প্রশ্ন অনুমোদিত অবস্থায় আছে।' : 'All questions are up to date and approved.'?></p>
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
		<div class="modal-content" id="qbPreviewContent"></div>
	</div>
</div>

<script type="text/javascript">
	function qbPreviewQuestion(id) {
		$('#qbPreviewModal').modal('show');
		$('#qbPreviewContent').html('<div class="modal-body text-center" style="padding: 40px;"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p style="margin-top: 10px;"><?=$is_bn ? "প্রশ্ন লোড হচ্ছে..." : "Loading question..."?></p></div>');
		$.ajax({
			url: "<?=base_url('question_bank/question_preview_ajax/')?>" + id,
			type: "GET",
			success: function(res) {
				$('#qbPreviewContent').html(res);
			}
		});
	}

	function qbQuickApprove(id, status) {
		$.ajax({
			url: "<?=base_url('question_bank/question_status_change')?>",
			type: "POST",
			data: {question_id: id, status: status},
			dataType: "json",
			success: function(res) {
				if (res.status === 'success') {
					$('#review_row_' + id).fadeOut(300, function() { $(this).remove(); });
				} else {
					alert(res.message);
				}
			}
		});
	}
</script>

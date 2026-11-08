<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$can_del = get_permission('question_bank', 'is_delete');
$is_admin = (is_superadmin_loggedin() || is_admin_loggedin());
?>

<!-- Filter Panel -->
<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fas fa-filter"></i> <?=$is_bn ? 'প্রশ্নপত্র অনুসন্ধান ও ফিল্টারিং' : 'Filter Papers'?></h2>
	</header>
	<?php echo form_open('question_bank/papers_list', array('method' => 'get')); ?>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-5 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?></label>
					<?php
						echo form_dropdown("class_id", $classList, set_value('class_id', $class_id), "class='form-control' id='flt_class_id'
						data-plugin-selectTwo data-width='100%'");
					?>
				</div>
			</div>
			<div class="col-md-5 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?></label>
					<select class="form-control" name="subject_id" id="flt_subject_id" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($subjectList as $sub): ?>
							<option value="<?=$sub['subject_id']?>" <?=$subject_id == $sub['subject_id'] ? 'selected' : ''?>><?=html_escape($sub['subjectname'])?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-2 mb-sm text-right" style="padding-top: 24px;">
				<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> <?=$is_bn ? 'ফিল্টার' : 'Filter'?></button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</section>

<!-- Papers List Panel -->
<section class="panel">
	<header class="panel-heading">
		<div class="pull-right">
			<a href="<?=base_url('question_bank/paper_generator')?>" class="btn btn-success btn-xs">
				<i class="fas fa-plus-circle"></i> <?=$is_bn ? 'নতুন প্রশ্নপত্র প্রণয়ন করুন' : 'Generate New Paper'?>
			</a>
		</div>
		<h2 class="panel-title"><i class="fas fa-file-invoice"></i> <?=$is_bn ? 'তৈরিকৃত প্রশ্নপত্রের তালিকা' : 'Generated Exam Papers'?></h2>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped mb-none table-export">
				<thead>
					<tr>
						<th width="40">#</th>
						<th><?=$is_bn ? 'প্রশ্নপত্রের শিরোনাম' : 'Exam Title'?></th>
						<th width="140"><?=$is_bn ? 'শ্রেণি ও বিষয়' : 'Class & Subject'?></th>
						<th width="110" class="text-center"><?=$is_bn ? 'সময় ও পূর্ণমান' : 'Duration & Marks'?></th>
						<th width="100" class="text-center"><?=$is_bn ? 'প্রশ্ন সংখ্যা' : 'Questions'?></th>
						<th width="110" class="text-center"><?=$is_bn ? 'পরীক্ষার তারিখ' : 'Exam Date'?></th>
						<th width="120"><?=$is_bn ? 'তৈরি করেছেন' : 'Author'?></th>
						<th width="110" class="text-center"><?=$is_bn ? 'অ্যাকশন' : 'Action'?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($papers)): ?>
						<?php $sl = 1; foreach ($papers as $p): ?>
							<tr>
								<td><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $sl++).'</span>' : $sl++?></td>
								<td>
									<strong style="font-size: 14px; color: #1e293b;"><?=html_escape($p['title'])?></strong>
									<?php if (!empty($p['term'])): ?>
										<br><span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:11px;"><?=html_escape($p['term'])?></span>
									<?php endif; ?>
								</td>
								<td>
									<strong><?=html_escape($p['class_name'])?></strong><br>
									<span class="text-muted" style="font-size: 11.5px;"><?=html_escape($p['subject_name'])?></span>
								</td>
								<td class="text-center">
									<span style="font-weight: 700; color: #007F3D;">
										<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], rtrim(rtrim($p['total_marks'], '0'), '.')).'</span>' : rtrim(rtrim($p['total_marks'], '0'), '.')?> <?=$is_bn ? 'নম্বর' : 'Marks'?>
									</span><br>
									<span class="text-muted" style="font-size: 11px;">
										<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $p['duration_minutes']).'</span> মিনিট' : $p['duration_minutes'] . ' Mins'?>
									</span>
								</td>
								<td class="text-center">
									<span class="badge" style="background: #f1f5f9; color: #334155; font-size: 12px; border: 1px solid #cbd5e1;">
										<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $p['question_count']).'</span> টি' : $p['question_count']?>
									</span>
								</td>
								<td class="text-center" style="font-size: 12px;">
									<?=(!empty($p['exam_date']) && $p['exam_date'] !== '0000-00-00') ? date('d M Y', strtotime($p['exam_date'])) : '-'?>
								</td>
								<td style="font-size: 12px;">
									<i class="far fa-user text-muted"></i> <?=html_escape(!empty($p['author_name']) ? $p['author_name'] : 'Admin')?>
								</td>
								<td class="text-center">
									<a href="<?=base_url('question_bank/paper_view/' . $p['id'])?>" class="btn btn-default btn-circle icon" title="<?=$is_bn ? 'প্রশ্নপত্র দেখুন ও প্রিন্ট করুন' : 'View & Print Paper'?>">
										<i class="fas fa-print text-primary"></i>
									</a>
									<?php if ($can_del || $is_admin): ?>
										<?php echo btn_delete('question_bank/paper_delete/' . $p['id']); ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="8" class="text-center text-muted" style="padding: 40px;">
								<i class="fas fa-file-invoice fa-3x" style="opacity: 0.3; margin-bottom: 12px;"></i>
								<p><?=$is_bn ? 'কোনো প্রশ্নপত্র পাওয়া যায়নি। উপরের "নতুন প্রশ্নপত্র প্রণয়ন করুন" বাটনে চাপুন।' : 'No exam papers generated yet. Click "Generate New Paper" above.'?></p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$('#flt_class_id').on('change', function() {
			var classID = $(this).val();
			$('#flt_subject_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#flt_subject_id').html(data);
					}
				});
			}
		});
	});
</script>

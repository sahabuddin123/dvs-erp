<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
?>
<section class="panel">
	<header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
		<h4 class="panel-title" style="margin: 0;">
			<i class="fas fa-folder-open text-primary"></i> <?=translate('draft_admissions')?>
		</h4>
		<div class="panel-btn">
			<a href="<?=base_url('student/add')?>" class="btn btn-default btn-xs" style="font-weight: 600;">
				<i class="fas fa-plus-circle text-primary"></i> <?=translate('create_admission')?>
			</a>
		</div>
	</header>
	<div class="panel-body">
		<div class="row mb-md">
			<div class="col-md-12">
				<div class="alert alert-info mb-md" style="border-left: 4px solid #007F3D; background: #f0fdf4; color: #166534;">
					<i class="fas fa-info-circle"></i> 
					<?=$is_bn ? 'যেসব শিক্ষার্থীর ভর্তি ফরম পূরণের সময় কোনো ধাপে (যেমন: ধাপ ১ থেকে ধাপ ৪ বা ৫) ড্রাফট হিসেবে সংরক্ষণ হয়েছে বা সম্পন্ন করা যায়নি, সেগুলো এখানে জমা থাকে। আপনি <strong>"ভর্তি সম্পন্ন করুন"</strong> বাটনে ক্লিক করে সরাসরি পূর্বের তথ্যসহ ফরমের নির্দিষ্ট ধাপ থেকে ভর্তি চূড়ান্ত করতে পারবেন।' : 'Incomplete or auto-saved admission applications from any wizard step are saved here. Click <strong>"Resume Admission"</strong> to continue where you left off and complete enrollment.'?>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table-export" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="50" class="text-center"><?=translate('sl')?></th>
						<th><?=translate('register_no')?> / <?=translate('roll')?></th>
						<th><?=translate('student_name')?></th>
						<th><?=translate('class')?> & <?=translate('section')?></th>
						<th><?=translate('guardian_name')?> & <?=translate('mobile_no')?></th>
						<th class="text-center"><?=translate('last_saved_step')?></th>
						<th><?=translate('date')?> & <?=translate('time')?></th>
						<th width="150" class="text-center"><?=translate('action')?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$count = 1;
					if (!empty($drafts)):
						foreach($drafts as $row):
							$last_step = !empty($row['last_step']) ? (int)$row['last_step'] : 1;
							$step_labels_bn = array(
								1 => 'ধাপ ১: একাডেমি',
								2 => 'ধাপ ২: ব্যক্তিগত তথ্য',
								3 => 'ধাপ ৩: অভিভাবকের তথ্য',
								4 => 'ধাপ ৪: অন্যান্য সুবিধা',
								5 => 'ধাপ ৫: ভর্তি ফি ও পেমেন্ট',
							);
							$step_labels_en = array(
								1 => 'Step 1: Academic',
								2 => 'Step 2: Student Details',
								3 => 'Step 3: Parents & Guardian',
								4 => 'Step 4: Facilities',
								5 => 'Step 5: Fee & Payment',
							);
							$step_label = $is_bn ? (isset($step_labels_bn[$last_step]) ? $step_labels_bn[$last_step] : 'ধাপ ' . $last_step) : (isset($step_labels_en[$last_step]) ? $step_labels_en[$last_step] : 'Step ' . $last_step);
							
							$badge_color = ($last_step >= 4) ? '#10b981' : (($last_step >= 2) ? '#3b82f6' : '#f59e0b');
					?>
					<tr>
						<td class="text-center"><?=$count++?></td>
						<td>
							<strong><?=!empty($row['register_no']) ? html_escape($row['register_no']) : '<span class="text-muted">-</span>'?></strong>
							<?php if (!empty($row['roll'])): ?>
								<br><small class="text-muted"><?=translate('roll')?>: <?=html_escape($row['roll'])?></small>
							<?php endif; ?>
						</td>
						<td>
							<strong style="color: #007F3D; font-size: 14px;"><?=!empty($row['fullname']) ? html_escape($row['fullname']) : translate('unnamed_student')?></strong>
							<?php if (!empty($row['branch_name']) && is_superadmin_loggedin()): ?>
								<br><small class="text-muted"><i class="fas fa-school"></i> <?=html_escape($row['branch_name'])?></small>
							<?php endif; ?>
						</td>
						<td>
							<?php if (!empty($row['class_name'])): ?>
								<span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600; font-size: 12px;">
									<?=html_escape($row['class_name'])?>
									<?=!empty($row['section_name']) ? ' (' . html_escape($row['section_name']) . ')' : ''?>
								</span>
							<?php else: ?>
								<span class="text-muted"><?=translate('not_selected')?></span>
							<?php endif; ?>
						</td>
						<td>
							<?php if (!empty($row['guardian_name'])): ?>
								<strong><?=html_escape($row['guardian_name'])?></strong>
								<?php if (!empty($row['guardian_relation'])): ?>
									<small class="text-muted">(<?=html_escape($row['guardian_relation'])?>)</small>
								<?php endif; ?>
							<?php endif; ?>
							<?php
							$contact_no = !empty($row['guardian_phone']) ? $row['guardian_phone'] : (!empty($row['mobile_no']) ? $row['mobile_no'] : '');
							if (!empty($contact_no)):
							?>
								<br><small class="text-muted"><i class="fas fa-phone-alt"></i> <?=html_escape($contact_no)?></small>
							<?php elseif (empty($row['guardian_name'])): ?>
								<span class="text-muted">-</span>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<span class="badge" style="background: <?=$badge_color?>; color: #fff; font-weight: 600; font-size: 11px; padding: 4px 10px; border-radius: 12px;">
								<i class="fas fa-check-circle"></i> <?=$step_label?>
							</span>
						</td>
						<td>
							<small class="text-dark">
								<i class="far fa-calendar-alt"></i> <?=!empty($row['updated_at']) ? date('d M, Y', strtotime($row['updated_at'])) : date('d M, Y', strtotime($row['created_at']))?><br>
								<i class="far fa-clock text-muted"></i> <?=!empty($row['updated_at']) ? date('h:i A', strtotime($row['updated_at'])) : date('h:i A', strtotime($row['created_at']))?>
							</small>
						</td>
						<td class="text-center">
							<a href="<?=base_url('student/add?draft_id=' . $row['id'])?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="<?=$is_bn ? 'ভর্তি সম্পন্ন করুন' : 'Resume Admission'?>" style="background: #007F3D; border-color: #007F3D; font-weight: 600; padding: 4px 8px; margin-right: 4px;">
								<i class="fas fa-edit"></i> <?=$is_bn ? 'সম্পন্ন করুন' : 'Resume'?>
							</a>
							<?php if (get_permission('student', 'is_add') || get_permission('student', 'is_delete')): ?>
								<?=btn_delete('student/delete_admission_draft/' . $row['id'])?>
							<?php endif; ?>
						</td>
					</tr>
					<?php
						endforeach;
					endif;
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>


<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$can_view = get_permission('question_bank', 'is_view');
$can_add = get_permission('question_bank', 'is_add');
$is_admin = (is_superadmin_loggedin() || is_admin_loggedin());
?>
<?php if ($can_view || $is_admin): ?>
<li class="nav-parent <?php if ($main_menu == 'question_bank') echo 'nav-expanded nav-active'; ?>">
	<a>
		<i class="fas fa-layer-group"></i><span><?=$is_bn ? 'প্রশ্ন ভাণ্ডার ও প্রশ্নপত্র' : 'Question Bank Pro'?></span>
	</a>
	<ul class="nav nav-children">
		<li class="<?php if ($sub_page == 'question_bank/index') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'প্রশ্ন সংগ্রহশালা' : 'Question Repository'?></span>
			</a>
		</li>
		<?php if ($can_add || $is_admin): ?>
		<li class="<?php if ($sub_page == 'question_bank/question_add') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/question_add')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'নতুন প্রশ্ন তৈরি' : 'Add Question'?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'question_bank/ai_generator') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/ai_generator')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'এআই প্রশ্ন জেনারেটর' : 'AI Question Generator'?> <span class="badge badge-warning" style="background:#f59e0b; font-size:10px; margin-left:3px;">AI</span></span>
			</a>
		</li>
		<?php endif; ?>
		<li class="<?php if ($sub_page == 'question_bank/chapters_topics') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/chapters_topics')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'অধ্যায় ও পাঠ্যসূচি' : 'Chapters & Topics'?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'question_bank/paper_generator') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/paper_generator')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'স্বয়ংক্রিয় প্রশ্নপত্র প্রণয়ন' : 'Paper Generator'?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'question_bank/papers_list' || $sub_page == 'question_bank/paper_view') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/papers_list')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'তৈরিকৃত প্রশ্নপত্রসমূহ' : 'Generated Papers'?></span>
			</a>
		</li>
		<?php if ($is_admin): ?>
		<li class="<?php if ($sub_page == 'question_bank/approvals') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/approvals')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'প্রশ্ন পর্যালোচনা ও অনুমোদন' : 'Review & Approvals'?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'question_bank/settings') echo 'nav-active'; ?>">
			<a href="<?=base_url('question_bank/settings')?>">
				<span><i class="fas fa-caret-right"></i><?=$is_bn ? 'এআই সেটিংস' : 'AI & Settings'?></span>
			</a>
		</li>
		<?php endif; ?>
	</ul>
</li>
<?php endif; ?>


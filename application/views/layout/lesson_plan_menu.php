<?php if (get_permission('lesson_topic', 'is_view') || get_permission('lesson_manage', 'is_view') || get_permission('lesson_syllabus', 'is_view') || get_permission('lesson_overview', 'is_view') || is_superadmin_loggedin()) { ?>
<li class="nav-parent <?php if ($main_menu == 'lesson_plan') echo 'nav-expanded nav-active';?>">
	<a>
		<i class="fas fa-chalkboard-teacher"></i><span><?=translate('lesson_plan')?></span>
	</a>
	<ul class="nav nav-children">
		<?php if (get_permission('lesson_topic', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'lesson_plan/topic' || $sub_page == 'lesson_plan/topic_edit') echo 'nav-active';?>">
			<a href="<?=base_url('lesson_plan/topic')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('topic')?></span>
			</a>
		</li>
		<?php } ?>
		<?php if (get_permission('lesson_manage', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'lesson_plan/lesson' || $sub_page == 'lesson_plan/lesson_edit') echo 'nav-active';?>">
			<a href="<?=base_url('lesson_plan/lesson')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('lesson')?></span>
			</a>
		</li>
		<?php } ?>
		<?php if (get_permission('lesson_syllabus', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'lesson_plan/manage') echo 'nav-active';?>">
			<a href="<?=base_url('lesson_plan/manage')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('manage_syllabus')?></span>
			</a>
		</li>
		<?php } ?>
		<?php if (get_permission('lesson_overview', 'is_view') || is_superadmin_loggedin()) { ?>
		<li class="<?php if ($sub_page == 'lesson_plan/overview') echo 'nav-active';?>">
			<a href="<?=base_url('lesson_plan/overview')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('overview_report')?></span>
			</a>
		</li>
		<?php } ?>
	</ul>
</li>
<?php } ?>

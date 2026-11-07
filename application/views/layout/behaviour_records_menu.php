<li class="nav-parent <?php if ($main_menu == 'behaviour_records') echo 'nav-expanded nav-active'; ?>">
	<a>
		<i class="fas fa-traffic-light"></i><span><?=translate('behaviour_records')?></span>
	</a>
	<ul class="nav nav-children">
		<li class="<?php if ($sub_page == 'behaviour_records/index') echo 'nav-active'; ?>">
			<a href="<?=base_url('behaviour_records')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('assign_behaviour')?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'behaviour_records/incidents') echo 'nav-active'; ?>">
			<a href="<?=base_url('behaviour_records/incidents')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('incidents')?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'behaviour_records/reports') echo 'nav-active'; ?>">
			<a href="<?=base_url('behaviour_records/reports')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('reports')?></span>
			</a>
		</li>
	</ul>
</li>


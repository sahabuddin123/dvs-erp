<li class="nav-parent <?php if ($main_menu == 'whatsapp_messaging') echo 'nav-expanded nav-active'; ?>">
	<a>
		<i class="fab fa-whatsapp" style="color: #25D366; font-size: 16px;"></i><span><?=translate('whatsapp_messaging')?></span>
	</a>
	<ul class="nav nav-children">
		<li class="<?php if ($sub_page == 'whatsapp_messaging/send' || $sub_page == 'whatsapp_messaging/queue') echo 'nav-active'; ?>">
			<a href="<?=base_url('whatsapp_messaging/send')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('send_message')?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'whatsapp_messaging/templates') echo 'nav-active'; ?>">
			<a href="<?=base_url('whatsapp_messaging/templates')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('templates')?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'whatsapp_messaging/reports') echo 'nav-active'; ?>">
			<a href="<?=base_url('whatsapp_messaging/reports')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('reports')?></span>
			</a>
		</li>
		<li class="<?php if ($sub_page == 'whatsapp_messaging/settings') echo 'nav-active'; ?>">
			<a href="<?=base_url('whatsapp_messaging/settings')?>">
				<span><i class="fas fa-caret-right"></i><?=translate('settings')?></span>
			</a>
		</li>
	</ul>
</li>


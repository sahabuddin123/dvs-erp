<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="panel-title">
					<i class="fab fa-whatsapp" style="color: #25D366;"></i> WhatsApp বার্তা প্রেরণ কিউ (Dispatcher Queue)
				</h4>
				<div>
					<a href="<?=base_url('whatsapp_messaging/send')?>" class="btn btn-default btn-xs"><i class="fas fa-arrow-left"></i> নতুন বার্তা তৈরি করুন</a>
					<a href="<?=base_url('whatsapp_messaging/reports')?>" class="btn btn-default btn-xs"><i class="fas fa-list"></i> মেসেজ রিপোর্ট দেখুন</a>
				</div>
			</header>
			<div class="panel-body">
				<div class="alert alert-info">
					<i class="fab fa-whatsapp fa-lg" style="color: #25D366; margin-right: 6px;"></i>
					<strong>নির্দেশনা:</strong> নিচে প্রাপকদের জন্য বার্তা প্রস্তুত রয়েছে। <strong>"WhatsApp-এ পাঠান"</strong> বাটনে ক্লিক করলে স্বয়ংক্রিয়ভাবে WhatsApp Web বা মোবাইল অ্যাপে প্রাপকের নম্বর ও সাজানো মেসেজসহ ওপেন হবে।
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr style="background: #f8fafc;">
								<th width="50">#</th>
								<th>প্রাপকের নাম</th>
								<th>ভূমিকা / রোল</th>
								<th>মোবাইল নম্বর</th>
								<th>বার্তা প্রিভিউ</th>
								<th width="200" class="text-center">অ্যাকশন</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if (!empty($dispatch_list)):
								foreach ($dispatch_list as $row):
							?>
							<tr id="row_<?=$row['log_id']?>">
								<td><?=$i++?></td>
								<td><strong><?=html_escape($row['name'])?></strong></td>
								<td><span class="label label-primary"><?=ucfirst(html_escape($row['role']))?></span></td>
								<td><code><?=html_escape($row['formatted_phone'])?></code></td>
								<td>
									<div style="max-width: 450px; font-size: 13px; color: #334155; white-space: pre-wrap; background: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;"><?=html_escape($row['message'])?></div>
								</td>
								<td class="text-center">
									<a href="<?=$row['whatsapp_url']?>" 
									   target="_blank" 
									   class="btn btn-sm btn-success btn-send-wa" 
									   data-log-id="<?=$row['log_id']?>"
									   style="background: #25D366; border-color: #25D366; font-weight: 600;">
										<i class="fab fa-whatsapp"></i> WhatsApp-এ পাঠান
									</a>
									<span class="label label-success status-badge" id="status_<?=$row['log_id']?>" style="display: none; margin-top: 5px;">
										<i class="fas fa-check-double"></i> পাঠানো হয়েছে
									</span>
								</td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted">কিউতে কোনো মেসেজ পাওয়া যায়নি।</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('.btn-send-wa').on('click', function() {
		var $btn = $(this);
		var logId = $btn.data('log-id');
		$btn.removeClass('btn-success').addClass('btn-default').html('<i class="fas fa-check"></i> আবার পাঠান');
		$('#status_' + logId).fadeIn();
		$('#row_' + logId).css('background-color', '#f0fdf4');

		// Update log status via AJAX
		$.ajax({
			url: base_url + 'whatsapp_messaging/update_log_status',
			type: 'POST',
			data: { log_id: logId, status: 'sent' },
			dataType: 'json'
		});
	});
});
</script>

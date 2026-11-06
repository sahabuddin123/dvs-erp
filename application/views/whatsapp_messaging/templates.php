<div class="row">
	<!-- Templates List -->
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-file-alt"></i> WhatsApp মেসেজ টেমপ্লেট তালিকা</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th>#</th>
								<th>শিরোনাম (Title)</th>
								<th>ক্যাটাগরি</th>
								<th>টেমপ্লেট বিবরণ</th>
								<th>অ্যাকশন</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if (!empty($templates)):
								foreach ($templates as $tpl):
							?>
							<tr>
								<td><?=$i++?></td>
								<td><strong><?=html_escape($tpl['title'])?></strong></td>
								<td><span class="label label-info"><?=ucfirst(html_escape($tpl['category']))?></span></td>
								<td>
									<small style="color: #475569; display: block; max-width: 320px; white-space: pre-wrap;"><?=html_escape($tpl['template_body'])?></small>
								</td>
								<td>
									<button type="button" class="btn btn-default btn-circle btn-sm edit-tpl-btn" 
											data-id="<?=$tpl['id']?>" 
											data-title="<?=html_escape($tpl['title'])?>" 
											data-category="<?=html_escape($tpl['category'])?>" 
											data-body="<?=html_escape($tpl['template_body'])?>">
										<i class="fas fa-edit"></i>
									</button>
									<a href="<?=base_url('whatsapp_messaging/template_delete/'.$tpl['id'])?>" class="btn btn-danger btn-circle btn-sm" onclick="return confirm('আপনি কি নিশ্চিত এই টেমপ্লেটটি মুছে ফেলতে চান?');">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="5" class="text-center text-muted">কোনো টেমপ্লেট তৈরি করা হয়নি।</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>

	<!-- Add / Edit Template Form -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title" id="form_title"><i class="fas fa-plus-circle"></i> নতুন টেমপ্লেট যুক্ত করুন</h4>
			</header>
			<?php echo form_open('whatsapp_messaging/templates', ['class' => 'form-horizontal form-bordered validate']); ?>
				<input type="hidden" name="id" id="tpl_id" value="">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label">শিরোনাম <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="title" id="tpl_title" placeholder="যেমন: ফি পরিশোধের তাগিদ" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">ক্যাটাগরি</label>
						<div class="col-md-9">
							<select class="form-control" name="category" id="tpl_category" data-plugin-selectTwo data-width="100%">
								<option value="general">সাধারণ (General)</option>
								<option value="attendance">উপস্থিতি (Attendance)</option>
								<option value="fees">ফি ও একাউন্টিং (Fees)</option>
								<option value="exam">পরীক্ষা ও রেজাল্ট (Exam)</option>
								<option value="homework">হোমওয়ার্ক (Homework)</option>
								<option value="event">ছুটি ও ইভেন্ট (Event / Holiday)</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">সহায়ক ট্যাগ</label>
						<div class="col-md-9">
							<div class="tag-buttons" style="margin-bottom: 6px;">
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{name}"><code>{name}</code></button>
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{student_name}"><code>{student_name}</code></button>
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{roll}"><code>{roll}</code></button>
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{class}"><code>{class}</code></button>
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{section}"><code>{section}</code></button>
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{school}"><code>{school}</code></button>
								<button type="button" class="btn btn-default btn-xs insert-tpl-tag" data-tag="{date}"><code>{date}</code></button>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">মেসেজ বডি <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea class="form-control" name="template_body" id="tpl_body" rows="6" placeholder="মেসেজের ড্রাফট লিখুন..." required></textarea>
						</div>
					</div>
				</div>

				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-3 col-md-9">
							<button type="submit" class="btn btn-default"><i class="fas fa-save"></i> সংরক্ষণ করুন</button>
							<button type="button" class="btn btn-light" id="btn_cancel_edit" style="display: none;">বাতিল</button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('.insert-tpl-tag').on('click', function() {
		var tag = $(this).data('tag');
		var textarea = document.getElementById('tpl_body');
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var text = textarea.value;
		textarea.value = text.substring(0, start) + tag + text.substring(end);
		textarea.selectionStart = textarea.selectionEnd = start + tag.length;
		textarea.focus();
	});

	$('.edit-tpl-btn').on('click', function() {
		var id = $(this).data('id');
		var title = $(this).data('title');
		var category = $(this).data('category');
		var body = $(this).data('body');

		$('#tpl_id').val(id);
		$('#tpl_title').val(title);
		$('#tpl_category').val(category).trigger('change');
		$('#tpl_body').val(body);

		$('#form_title').html('<i class="fas fa-edit"></i> টেমপ্লেট সম্পাদনা করুন');
		$('#btn_cancel_edit').show();
	});

	$('#btn_cancel_edit').on('click', function() {
		$('#tpl_id').val('');
		$('#tpl_title').val('');
		$('#tpl_category').val('general').trigger('change');
		$('#tpl_body').val('');
		$('#form_title').html('<i class="fas fa-plus-circle"></i> নতুন টেমপ্লেট যুক্ত করুন');
		$(this).hide();
	});
});
</script>


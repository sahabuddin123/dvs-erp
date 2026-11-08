<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
?>

<div class="row">
	<div class="col-md-12">
		<!-- Filter bar -->
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fas fa-filter"></i> <?=$is_bn ? 'শ্রেণি ও বিষয় নির্বাচন করুন' : 'Select Class & Subject'?></h2>
			</header>
			<?php echo form_open('question_bank/chapters_topics', array('method' => 'get')); ?>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-5 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?> <span class="required">*</span></label>
							<?php
								echo form_dropdown("class_id", $classList, set_value('class_id', $class_id), "class='form-control' id='ch_class_id'
								data-plugin-selectTwo data-width='100%' required");
							?>
						</div>
					</div>
					<div class="col-md-5 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?> <span class="required">*</span></label>
							<select class="form-control" name="subject_id" id="ch_subject_id" data-plugin-selectTwo data-width="100%" required>
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
	</div>
</div>

<div class="row">
	<!-- Add Chapter Form -->
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fas fa-plus-circle text-primary"></i> <?=$is_bn ? 'নতুন অধ্যায় যোগ করুন' : 'Add Chapter'?></h2>
			</header>
			<?php echo form_open('question_bank/chapter_save', array('class' => 'form-horizontal form-bordered validate')); ?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'শ্রেণি' : 'Class'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<?php
							echo form_dropdown("class_id", $classList, set_value('class_id', $class_id), "class='form-control' id='add_ch_class_id'
							data-plugin-selectTwo data-width='100%' required");
						?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'বিষয়' : 'Subject'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<select class="form-control" name="subject_id" id="add_ch_subject_id" data-plugin-selectTwo data-width="100%" required>
							<option value=""><?=translate('select')?></option>
							<?php foreach ($subjectList as $sub): ?>
								<option value="<?=$sub['subject_id']?>" <?=$subject_id == $sub['subject_id'] ? 'selected' : ''?>><?=html_escape($sub['subjectname'])?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'অধ্যায় নম্বর' : 'Chapter No'?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="chapter_no" placeholder="<?=$is_bn ? 'যেমন: ১ম অধ্যায় বা Chapter 1' : 'e.g. Chapter 1'?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'অধ্যায়ের নাম' : 'Chapter Name'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="name" placeholder="<?=$is_bn ? 'যেমন: উদ্ভিদ ও প্রাণীর খাদ্য' : 'e.g. Plant & Animal Nutrition'?>" required />
					</div>
				</div>
			</div>
			<footer class="panel-footer text-right">
				<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?=$is_bn ? 'সংরক্ষণ করুন' : 'Save Chapter'?></button>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

	<!-- Chapters & Topics List -->
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fas fa-book-open text-primary"></i> <?=$is_bn ? 'অধ্যায় ও সংশ্লিষ্ট টপিকসমূহ' : 'Chapters & Topics Tree'?></h2>
			</header>
			<div class="panel-body">
				<?php if (!empty($chapters)): ?>
					<div class="panel-group" id="chapterAccordion">
						<?php foreach ($chapters as $idx => $ch): ?>
							<div class="panel panel-accordion">
								<div class="panel-heading" style="background: #f8fafc; border: 1px solid #e2e8f0;">
									<h4 class="panel-title" style="display: flex; justify-content: space-between; align-items: center;">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#chapterAccordion" href="#chCollapse<?=$ch['id']?>" style="color: #1e293b; font-weight: 600;">
											<i class="fas fa-folder text-warning"></i>
											<?=html_escape((!empty($ch['chapter_no']) ? $ch['chapter_no'] . ': ' : '') . $ch['name'])?>
											<span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:11px; margin-left:8px;">
												<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $ch['total_topics']).'</span> টপিক' : $ch['total_topics'] . ' Topics'?>
											</span>
											<span class="badge" style="background:#dcfce7; color:#15803d; font-size:11px; margin-left:4px;">
												<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $ch['total_questions']).'</span> প্রশ্ন' : $ch['total_questions'] . ' Questions'?>
											</span>
										</a>
										<div>
											<button type="button" class="btn btn-xs btn-primary" onclick="openAddTopicModal(<?=$ch['id']?>, '<?=html_escape(addslashes($ch['name']))?>')" title="<?=$is_bn ? 'নতুন টপিক যোগ করুন' : 'Add Topic'?>">
												<i class="fas fa-plus"></i> <?=$is_bn ? 'টপিক' : 'Topic'?>
											</button>
											<a href="<?=base_url('question_bank/chapter_delete/' . $ch['id'])?>" class="btn btn-xs btn-danger" onclick="return confirm('<?=$is_bn ? "এই অধ্যায় এবং এর আওতাধীন টপিকসমূহ মুছে ফেলতে চান?" : "Delete chapter and its topics?"?>')" title="<?=$is_bn ? 'মুছুন' : 'Delete'?>">
												<i class="fas fa-trash"></i>
											</a>
										</div>
									</h4>
								</div>
								<div id="chCollapse<?=$ch['id']?>" class="accordion-body collapse <?=$idx === 0 ? 'in' : ''?>">
									<div class="panel-body" style="padding: 12px 16px;">
										<?php 
										$ci = &get_instance();
										$topics = $ci->question_bank_model->getTopics($ch['id']);
										?>
										<?php if (!empty($topics)): ?>
											<ul class="list-group mb-none">
												<?php foreach ($topics as $tp): ?>
													<li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px;">
														<span>
															<i class="fas fa-angle-right text-muted" style="margin-right: 6px;"></i>
															<?=html_escape($tp['name'])?>
															<span class="badge" style="background:#f1f5f9; color:#475569; font-size:10.5px; margin-left: 6px;">
																<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $tp['total_questions']).'</span> প্রশ্ন' : $tp['total_questions'] . ' Qs'?>
															</span>
														</span>
														<a href="<?=base_url('question_bank/topic_delete/' . $tp['id'])?>" class="btn btn-xs btn-link text-danger" onclick="return confirm('Delete topic?')" title="Delete">
															<i class="fas fa-times"></i>
														</a>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php else: ?>
											<p class="text-muted mb-none" style="font-size: 12.5px;">
												<i class="fas fa-info-circle"></i> <?=$is_bn ? 'এই অধ্যায়ে এখনো কোনো টপিক তৈরি করা হয়নি। উপরের "+ টপিক" বাটনে ক্লিক করে যোগ করুন।' : 'No topics yet. Click "+ Topic" above to add.'?>
											</p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="text-center text-muted" style="padding: 50px 20px;">
						<i class="fas fa-book fa-3x" style="opacity: 0.3; margin-bottom: 12px;"></i>
						<p><?=$is_bn ? 'কোনো অধ্যায় পাওয়া যায়নি। বাম পাশের ফর্মে নতুন অধ্যায় যোগ করুন।' : 'No chapters found. Add chapters using the form on the left.'?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
</div>

<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title font-weight-bold">
					<i class="fas fa-plus-circle text-primary"></i> <?=$is_bn ? 'নতুন টপিক যোগ করুন' : 'Add Topic'?>
				</h4>
			</div>
			<?php echo form_open('question_bank/topic_save', array('class' => 'validate')); ?>
			<input type="hidden" name="chapter_id" id="modal_topic_chapter_id" value="" />
			<div class="modal-body">
				<div class="form-group mb-md">
					<label class="control-label"><?=$is_bn ? 'নির্বাচিত অধ্যায়' : 'Selected Chapter'?></label>
					<input type="text" class="form-control" id="modal_topic_chapter_name" readonly style="background:#f8fafc; font-weight: 600;" />
				</div>
				<div class="form-group">
					<label class="control-label"><?=$is_bn ? 'টপিক বা উপ-শিরোনাম' : 'Topic Name'?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" placeholder="<?=$is_bn ? 'যেমন: ক্লোরোফিলের ভূমিকা' : 'e.g. Role of Chlorophyll'?>" required />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=$is_bn ? 'বাতিল' : 'Cancel'?></button>
				<button type="submit" class="btn btn-primary"><?=$is_bn ? 'টপিক সংরক্ষণ করুন' : 'Save Topic'?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#ch_class_id').on('change', function() {
			var classID = $(this).val();
			$('#ch_subject_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#ch_subject_id').html(data);
					}
				});
			}
		});

		$('#add_ch_class_id').on('change', function() {
			var classID = $(this).val();
			$('#add_ch_subject_id').html('<option value=""><?=translate("select")?></option>');
			if (classID) {
				$.ajax({
					url: "<?=base_url('ajax/getSubjectByClass')?>",
					type: "POST",
					data: {class_id: classID},
					success: function(data) {
						$('#add_ch_subject_id').html(data);
					}
				});
			}
		});
	});

	function openAddTopicModal(chapterId, chapterName) {
		$('#modal_topic_chapter_id').val(chapterId);
		$('#modal_topic_chapter_name').val(chapterName);
		$('#addTopicModal').modal('show');
	}
</script>

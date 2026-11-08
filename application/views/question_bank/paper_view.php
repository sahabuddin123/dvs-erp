<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$p = $paper;
?>

<!-- Print Toolbar (hidden during print) -->
<div class="row no-print mb-md">
	<div class="col-md-12">
		<div style="background: #fff; padding: 12px 18px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
			<div>
				<a href="<?=base_url('question_bank/papers_list')?>" class="btn btn-default btn-sm">
					<i class="fas fa-arrow-left"></i> <?=$is_bn ? 'প্রশ্নপত্রের তালিকায় ফিরুন' : 'Back to Papers'?>
				</a>
			</div>
			<div style="display: flex; gap: 8px;">
				<button type="button" class="btn btn-info btn-sm" id="btnToggleAnswerKey" onclick="toggleAnswerKey()">
					<i class="fas fa-key"></i> <span id="ansKeyBtnText"><?=$is_bn ? 'শিক্ষকের উত্তরমালার শিট দেখুন' : 'Show Answer Key'?></span>
				</button>
				<button type="button" class="btn btn-success btn-sm font-weight-bold" onclick="window.print()">
					<i class="fas fa-print"></i> <?=$is_bn ? 'এ৪ (A4) প্রিন্ট করুন' : 'Print Question Paper'?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Printable Exam Paper Sheet -->
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-body qb-paper-print-sheet" style="padding: 40px; background: #fff; color: #000; min-height: 800px; font-size: 13.5px; line-height: 1.6;">

				<!-- Exam Header -->
				<div class="text-center qb-paper-header" style="border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px;">
					<h2 style="margin: 0; font-weight: 800; font-size: 24px; color: #007F3D; letter-spacing: 0.5px;">
						DREAM VALLEY SCHOOL
					</h2>
					<div style="font-size: 13px; font-weight: 600; color: #333; margin-top: 2px;">
						<?=$is_bn ? 'খাসেরহাট মেইন রোড, মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী' : 'Khaserhat Main Road, Master Para, Charbata, Subarnachar, Noakhali'?>
					</div>
					<h3 style="margin: 8px 0 4px; font-weight: 700; font-size: 18px; text-decoration: underline;">
						<?=html_escape($p['title'])?>
					</h3>

					<!-- Academic Meta Bar -->
					<div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 13.5px; margin-top: 10px; border-top: 1px solid #ddd; padding-top: 6px;">
						<div>
							<?=$is_bn ? 'শ্রেণি: ' : 'Class: '?><span><?=html_escape($p['class_name'])?></span>
						</div>
						<div>
							<?=$is_bn ? 'বিষয়: ' : 'Subject: '?><span><?=html_escape($p['subject_name'])?></span>
						</div>
						<div>
							<?=$is_bn ? 'সময়: ' : 'Time: '?><span><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $p['duration_minutes']).'</span> মিনিট' : $p['duration_minutes'] . ' Minutes'?></span>
						</div>
						<div>
							<?=$is_bn ? 'পূর্ণমান: ' : 'Full Marks: '?><span><?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], rtrim(rtrim($p['total_marks'], '0'), '.')).'</span>' : rtrim(rtrim($p['total_marks'], '0'), '.')?></span>
						</div>
					</div>
				</div>

				<!-- Instructions -->
				<?php if (!empty($p['instructions'])): ?>
					<div class="qb-paper-instructions text-center" style="font-style: italic; font-size: 12.5px; margin-bottom: 20px; color: #222;">
						[ <?=html_escape($p['instructions'])?> ]
					</div>
				<?php endif; ?>

				<!-- Sections and Questions -->
				<?php if (!empty($p['sections'])): ?>
					<?php foreach ($p['sections'] as $secName => $secData): ?>
						<div class="qb-section-block" style="margin-bottom: 25px;">
							<div class="qb-section-header" style="text-align: center; font-weight: 800; font-size: 14.5px; margin-bottom: 12px; text-decoration: underline;">
								<?=html_escape($secName)?>
								<?php if (!empty($secData['instructions'])): ?>
									<div style="font-size: 12px; font-weight: normal; font-style: italic; text-decoration: none; margin-top: 2px;">
										(<?=html_escape($secData['instructions'])?>)
									</div>
								<?php endif; ?>
							</div>

							<!-- Questions in this Section -->
							<div class="qb-questions-list">
								<?php $qCount = 1; foreach ($secData['questions'] as $q): ?>
									<div class="qb-question-item" style="margin-bottom: 14px; page-break-inside: avoid;">
										<div style="display: flex; justify-content: space-between; align-items: flex-start;">
											<div style="flex-grow: 1; padding-right: 15px;">
												<strong style="margin-right: 4px;">
													<?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $qCount++).'</span>.' : $qCount++ . '.'?>
												</strong>
												<?=nl2br(html_escape($q['question_text']))?>
											</div>
											<div style="font-weight: 700; white-space: nowrap; text-align: right;">
												[ <?=$is_bn ? '<span class="font-noto">'.str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], rtrim(rtrim($q['marks'], '0'), '.')).'</span>' : rtrim(rtrim($q['marks'], '0'), '.')?> ]
											</div>
										</div>

										<!-- MCQ Options -->
										<?php if ($q['type'] === 'mcq' && !empty($q['options'])): ?>
											<div class="row" style="margin-top: 5px; padding-left: 20px;">
												<?php foreach ($q['options'] as $opt): ?>
													<div class="col-xs-6 col-sm-3" style="font-size: 12.5px; margin-bottom: 3px;">
														(<?=html_escape($opt['option_key'])?>) <?=html_escape($opt['option_text'])?>
													</div>
												<?php endforeach; ?>
											</div>
										<?php elseif ($q['type'] === 'matching' && !empty($q['options'])): ?>
											<!-- Matching Columns -->
											<table class="table table-bordered table-condensed mt-xs mb-none" style="font-size: 12px; margin-left: 20px; width: 85%;">
												<thead>
													<tr style="background:#f8fafc;">
														<th width="50%"><?=$is_bn ? 'কলাম ক' : 'Column A'?></th>
														<th width="50%"><?=$is_bn ? 'কলাম খ' : 'Column B'?></th>
													</tr>
												</thead>
												<tbody>
													<?php 
													// Shuffle right column for real exam testing
													$rightShuffled = array_column($q['options'], 'match_pair');
													shuffle($rightShuffled);
													foreach ($q['options'] as $idx => $opt): 
													?>
														<tr>
															<td>(<?=$idx + 1?>) <?=html_escape($opt['option_text'])?></td>
															<td>(<?=chr(65 + $idx)?>) <?=html_escape(isset($rightShuffled[$idx]) ? $rightShuffled[$idx] : '')?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<!-- Teacher's Answer Key Section (Toggleable) -->
				<div id="qbAnswerKeySection" style="display: none; border-top: 2px dashed #007F3D; margin-top: 35px; padding-top: 20px; page-break-before: always;">
					<div class="text-center" style="margin-bottom: 15px;">
						<span class="badge" style="background: #007F3D; font-size: 14px; padding: 6px 14px;">
							<i class="fas fa-key"></i> <?=$is_bn ? 'শিক্ষকদের ব্যবহারের জন্য আদর্শ উত্তরমালা ও মূল্যায়ন নির্দেশিকা' : 'Official Teacher\'s Answer Key & Marking Scheme'?>
						</span>
					</div>

					<?php foreach ($p['sections'] as $secName => $secData): ?>
						<h5 style="font-weight: 700; color: #007F3D; margin-top: 15px; border-bottom: 1px solid #ddd; padding-bottom: 4px;">
							<?=html_escape($secName)?>
						</h5>
						<div style="font-size: 12.5px; padding-left: 10px;">
							<?php $ansCount = 1; foreach ($secData['questions'] as $q): ?>
								<div style="margin-bottom: 8px;">
									<strong><?=$ansCount++?>.</strong>
									<?php if ($q['type'] === 'mcq'): ?>
										<?php 
										$correctOpt = '';
										foreach ($q['options'] as $opt) {
											if ($opt['is_correct']) {
												$correctOpt = '(' . $opt['option_key'] . ') ' . $opt['option_text'];
												break;
											}
										}
										?>
										<span style="color: #15803d; font-weight: 600; margin-left: 6px;"><?=$correctOpt?></span>
									<?php elseif ($q['type'] === 'true_false'): ?>
										<?php 
										$tfAns = '';
										foreach ($q['options'] as $opt) {
											if ($opt['is_correct']) {
												$tfAns = $opt['option_text'];
												break;
											}
										}
										?>
										<span style="color: #15803d; font-weight: 600; margin-left: 6px;"><?=$tfAns?></span>
									<?php elseif ($q['type'] === 'fill_blank'): ?>
										<?php 
										$blanks = array_column($q['options'], 'option_text');
										?>
										<span style="color: #15803d; font-weight: 600; margin-left: 6px;"><?=implode(', ', $blanks)?></span>
									<?php elseif ($q['type'] === 'matching'): ?>
										<div style="padding-left: 15px; margin-top: 4px;">
											<?php foreach ($q['options'] as $opt): ?>
												<div><?=html_escape($opt['option_text'])?> <i class="fas fa-arrow-right text-success"></i> <strong><?=html_escape($opt['match_pair'])?></strong></div>
											<?php endforeach; ?>
										</div>
									<?php elseif (!empty($q['options']) && isset($q['options'][0])): ?>
										<div style="background: #f8fafc; padding: 6px 10px; border-radius: 4px; margin-top: 4px;">
											<?=nl2br(html_escape($q['options'][0]['option_text']))?>
										</div>
									<?php endif; ?>

									<?php if (!empty($q['explanation'])): ?>
										<div style="font-size: 11px; color: #1e40af; margin-left: 15px;">
											<em>Hint/Source: <?=html_escape($q['explanation'])?></em>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
</div>

<style>
@media print {
	/* Hide ERP navigation, sidebar, topbar and non-print toolbar */
	.header, .sidebar-left, .page-header, .no-print, .panel-footer, footer, #btnToggleAnswerKey {
		display: none !important;
	}
	.content-body {
		margin-left: 0 !important;
		padding: 0 !important;
	}
	body, html {
		background: #fff !important;
		color: #000 !important;
		font-size: 12pt !important;
	}
	.panel {
		border: none !important;
		box-shadow: none !important;
		margin: 0 !important;
		padding: 0 !important;
	}
	.qb-paper-print-sheet {
		padding: 0 !important;
		width: 100% !important;
	}
	.qb-question-item {
		page-break-inside: avoid !important;
	}
	@page {
		size: A4 portrait;
		margin: 1.5cm 1.2cm;
	}
}
</style>

<script type="text/javascript">
	function toggleAnswerKey() {
		var sec = $('#qbAnswerKeySection');
		var txt = $('#ansKeyBtnText');
		if (sec.is(':visible')) {
			sec.hide();
			txt.text('<?=$is_bn ? "শিক্ষকের উত্তরমালার শিট দেখুন" : "Show Answer Key"?>');
		} else {
			sec.show();
			txt.text('<?=$is_bn ? "উত্তরমালার শিট লুকান" : "Hide Answer Key"?>');
		}
	}
</script>

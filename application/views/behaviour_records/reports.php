<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-filter"></i> <?=translate('filter')?></h4>
	</header>
	<div class="panel-body">
		<form method="get" action="<?=base_url('behaviour_records/reports')?>">
			<div class="row">
				<div class="col-md-5 mb-sm">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$classes = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $classes, $class_id, "class='form-control' id='rep_class_id' required data-plugin-selectTwo data-width='100%'");
					?>
				</div>
				<div class="col-md-5 mb-sm">
					<label class="control-label"><?=translate('section')?></label>
					<?php
						$sections = array('' => translate('all_select'));
						if (!empty($class_id)) {
							$sec_list = $this->app_lib->getSections($class_id, false);
							foreach ($sec_list as $s_id => $s_name) {
								if ($s_id !== '' && $s_id !== 'all') {
									$sections[$s_id] = $s_name;
								}
							}
						}
						echo form_dropdown("section_id", $sections, $section_id, "class='form-control' id='rep_section_id' data-plugin-selectTwo data-width='100%'");
					?>
				</div>
				<div class="col-md-2 mb-sm" style="margin-top: 25px;">
					<button type="submit" class="btn btn-default btn-block"><i class="fas fa-search"></i> <?=translate('view_report')?></button>
				</div>
			</div>
		</form>
	</div>
</section>

<?php if (!empty($class_id)): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-chart-line"></i> <?=translate('behaviour_summary_report')?></h4>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed mb-none table-export">
				<thead>
					<tr>
						<th width="40"><?=translate('sl')?></th>
						<th><?=translate('roll')?></th>
						<th><?=translate('student')?></th>
						<th><?=translate('register_no')?></th>
						<th><?=translate('class')?> & <?=translate('section')?></th>
						<th><?=translate('total_incidents')?></th>
						<th><?=translate('positive_points')?></th>
						<th><?=translate('negative_points')?></th>
						<th><?=translate('net_score')?></th>
						<th><?=translate('conduct_status')?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (!empty($report)): 
						$count = 1;
						foreach ($report as $row):
							$net = $row['net_points'];
					?>
					<tr>
						<td><?=$count++?></td>
						<td><?=$row['roll'] ?: 'N/A'?></td>
						<td><strong><?=html_escape($row['first_name'] . ' ' . $row['last_name'])?></strong></td>
						<td><?=$row['register_no']?></td>
						<td><?=$row['class_name'] . ' (' . $row['section_name'] . ')'?></td>
						<td><span class="badge badge-default"><?=$row['total_incidents']?></span></td>
						<td><span class="text-success" style="font-weight: bold;"><i class="fas fa-arrow-up"></i> +<?=$row['pos_points']?></span></td>
						<td><span class="text-danger" style="font-weight: bold;"><i class="fas fa-arrow-down"></i> -<?=$row['neg_points']?></span></td>
						<td>
							<strong style="font-size: 14px; color: <?=$net >= 0 ? '#16a34a' : '#dc2626'?>;">
								<?=$net >= 0 ? '+' : ''?><?=$net?>
							</strong>
						</td>
						<td>
							<?php if ($net >= 20): ?>
								<span class="label label-success" style="font-weight: bold;"><i class="fas fa-star"></i> <?=translate('excellent')?></span>
							<?php elseif ($net >= 10): ?>
								<span class="label label-info" style="font-weight: bold;"><i class="fas fa-thumbs-up"></i> <?=translate('very_good')?></span>
							<?php elseif ($net >= 0): ?>
								<span class="label label-primary" style="font-weight: bold;"><i class="fas fa-check"></i> <?=translate('good')?></span>
							<?php elseif ($net >= -5): ?>
								<span class="label label-warning" style="font-weight: bold;"><i class="fas fa-exclamation-triangle"></i> <?=translate('needs_attention')?></span>
							<?php else: ?>
								<span class="label label-danger" style="font-weight: bold;"><i class="fas fa-exclamation-circle"></i> <?=translate('critical')?></span>
							<?php endif; ?>
						</td>
					</tr>
					<?php 
						endforeach; 
					else: ?>
					<tr>
						<td colspan="10" class="text-center text-muted"><?=translate('no_information_available')?></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#rep_class_id').on('change', function() {
		var classID = $(this).val();
		$('#rep_section_id').html('<option value=""><?=translate('loading')?>...</option>');
		if (classID) {
			$.ajax({
				url: base_url + 'ajax/getSectionByClass',
				type: 'POST',
				data: { class_id: classID },
				success: function(response) {
					$('#rep_section_id').html(response).trigger('change');
				}
			});
		} else {
			$('#rep_section_id').html('<option value=""><?=translate('all_select')?></option>').trigger('change');
		}
	});
});
</script>


<div class="row">
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-plus-circle"></i> Add Behaviour Incident (আচরণের নতুন খাত)</h4>
			</header>
			<?php echo form_open('behaviour_records/incident_save', array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
				<input type="hidden" name="branch_id" value="<?=$branch_id?>">
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label">Incident Title (শিরোনাম) <span class="required">*</span></label>
						<input type="text" class="form-control" name="title" placeholder="যেমন: ক্লাসে সক্রিয় অংশগ্রহণ / দেরিতে আসা" required />
						<span class="error"></span>
					</div>

					<div class="form-group">
						<label class="control-label">Type (আচরণের ধরন) <span class="required">*</span></label>
						<select name="type" class="form-control" data-plugin-selectTwo data-width="100%" required>
							<option value="positive">Positive / ইতিবাচক (+)</option>
							<option value="negative">Negative / নেতিবাচক (-)</option>
						</select>
						<span class="error"></span>
					</div>

					<div class="form-group">
						<label class="control-label">Default Point / পয়েন্ট <span class="required">*</span></label>
						<input type="number" class="form-control" name="point" value="5" min="1" max="100" required />
						<span class="error"></span>
					</div>

					<div class="form-group">
						<label class="control-label">Description / বিবরণ</label>
						<textarea class="form-control" name="description" rows="3" placeholder="আচরণটির সংক্ষিপ্ত বিবরণ..."></textarea>
					</div>
				</div>
				<footer class="panel-footer">
					<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?=translate('save')?>
					</button>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> Behaviour Incidents List (সংরক্ষিত আচরণের খাতসমূহ)</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th width="40"><?=translate('sl')?></th>
								<th><?=translate('title')?></th>
								<th>Type / ধরন</th>
								<th>Point / পয়েন্ট</th>
								<th><?=translate('description')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if (!empty($incidents)): 
								$count = 1;
								foreach ($incidents as $row):
							?>
							<tr>
								<td><?=$count++?></td>
								<td><strong><?=html_escape($row['title'])?></strong></td>
								<td>
									<?php if ($row['type'] == 'positive'): ?>
										<span class="label label-success" style="font-weight: 600;"><i class="fas fa-check"></i> Positive</span>
									<?php else: ?>
										<span class="label label-danger" style="font-weight: 600;"><i class="fas fa-times"></i> Negative</span>
									<?php endif; ?>
								</td>
								<td>
									<strong style="color: <?=$row['type'] == 'positive' ? '#16a34a' : '#dc2626'?>;">
										<?=$row['type'] == 'positive' ? '+' : '-'?><?=$row['point']?>
									</strong>
								</td>
								<td><?=html_escape($row['description'] ?: '-')?></td>
								<td>
									<a href="<?=base_url('behaviour_records/incident_delete/' . $row['id'])?>" class="btn btn-default btn-circle icon" onclick="confirm_modal('<?=base_url('behaviour_records/incident_delete/' . $row['id'])?>'); return false;" data-toggle="tooltip" title="<?=translate('delete')?>">
										<i class="fas fa-trash-alt text-danger"></i>
									</a>
								</td>
							</tr>
							<?php 
								endforeach;
							else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted"><?=translate('no_information_available')?></td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>


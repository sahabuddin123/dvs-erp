<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-network-wired"></i> <?php echo translate('dns_instruction'); ?> Configuration</h4>
			</header>
			<?php echo form_open('custom_domain/dns_instruction', array('class' => 'form-horizontal form-bordered validate')); ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Instruction Title</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="<?php echo html_escape($instruction['title']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Module Status</label>
						<div class="col-md-6">
							<select name="status" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value="1" <?php echo $instruction['status'] == 1 ? 'selected' : ''; ?>><?php echo translate('enable'); ?></option>
								<option value="0" <?php echo $instruction['status'] == 0 ? 'selected' : ''; ?>><?php echo translate('disable'); ?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Instructions Description</label>
						<div class="col-md-6">
							<textarea class="form-control" name="instruction" rows="4"><?php echo html_escape($instruction['instruction']); ?></textarea>
						</div>
					</div>

					<!-- DNS Records Configuration -->
					<div class="headers-line">
						<i class="fas fa-server"></i> DNS Records to Point
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">DNS Box Title</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="dns_title" value="<?php echo html_escape($instruction['dns_title']); ?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Record 1 (CNAME)</label>
						<div class="col-md-2">
							<input type="text" class="form-control" name="dns_type_1" value="<?php echo html_escape($instruction['dns_type_1']); ?>" placeholder="CNAME Record" />
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control" name="dns_host_1" value="<?php echo html_escape($instruction['dns_host_1']); ?>" placeholder="Host (e.g. www)" />
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control" name="dns_value_1" value="<?php echo html_escape($instruction['dns_value_1']); ?>" placeholder="Value / Target Domain" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Record 2 (A Record)</label>
						<div class="col-md-2">
							<input type="text" class="form-control" name="dns_type_2" value="<?php echo html_escape($instruction['dns_type_2']); ?>" placeholder="A Record" />
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control" name="dns_host_2" value="<?php echo html_escape($instruction['dns_host_2']); ?>" placeholder="Host (e.g. @)" />
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control" name="dns_value_2" value="<?php echo html_escape($instruction['dns_value_2']); ?>" placeholder="Server IP Address" />
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" name="submit" value="save" class="btn btn-default btn-block"><i class="fas fa-check"></i> <?php echo translate('save'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

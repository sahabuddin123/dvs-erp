<div class="row">
    <!-- Add Device Form -->
    <div class="col-md-5">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-plus-circle"></i> Add ZKTeco Device</h4>
            </header>
            <form action="<?=base_url('biometric/devices')?>" method="post">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                <div class="panel-body">
                    <?php if (is_superadmin_loggedin()) { ?>
                    <div class="form-group mb-md">
                        <label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
                        <?php
                            $arrayBranch = $this->app_lib->getSelectList('branch');
                            echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-plugin-selectTwo data-width='100%'");
                        ?>
                        <span class="error"><?=form_error('branch_id')?></span>
                    </div>
                    <?php } ?>

                    <div class="form-group mb-md">
                        <label class="control-label">Device Name / Location <span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?=set_value('name')?>" placeholder="e.g. Main Gate Terminal" required />
                        <span class="error"><?=form_error('name')?></span>
                    </div>

                    <div class="form-group mb-md">
                        <label class="control-label">IP Address <span class="required">*</span></label>
                        <input type="text" class="form-control" name="ip_address" value="<?=set_value('ip_address', '192.168.1.201')?>" placeholder="192.168.1.201" required />
                        <span class="error"><?=form_error('ip_address')?></span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-md">
                                <label class="control-label">Port <span class="required">*</span></label>
                                <input type="number" class="form-control" name="port" value="<?=set_value('port', '4370')?>" required />
                                <span class="error"><?=form_error('port')?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-md">
                                <label class="control-label">Protocol</label>
                                <select name="protocol" class="form-control">
                                    <option value="UDP" selected>UDP (Recommended)</option>
                                    <option value="TCP">TCP</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-md">
                        <label class="control-label">Terminal Use For</label>
                        <select name="device_type" class="form-control">
                            <option value="both" selected>Both (Students & Staff)</option>
                            <option value="student">Students Only</option>
                            <option value="staff">Staff Only</option>
                        </select>
                    </div>

                    <div class="form-group mb-md">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" name="auto_clear" value="1" id="auto_clear" />
                            <label for="auto_clear">Clear attendance memory on device after successful sync</label>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Device</button>
                </footer>
            </form>
        </section>
    </div>

    <!-- Devices List -->
    <div class="col-md-7">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-list"></i> Configured Devices</h4>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>IP Address</th>
                                <th>Port</th>
                                <th>For</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($devices)) { foreach ($devices as $row) { ?>
                            <tr>
                                <td><strong><?=html_escape($row['name'])?></strong></td>
                                <td><code><?=html_escape($row['ip_address'])?></code></td>
                                <td><?=html_escape($row['port'])?></td>
                                <td><span class="label label-info"><?=ucfirst($row['device_type'])?></span></td>
                                <td>
                                    <?php if ($row['status']) { ?>
                                        <span class="label label-success">Active</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">Inactive</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?=base_url('biometric/sync/' . $row['id'])?>" class="btn btn-primary btn-xs" title="Sync now">
                                        <i class="fas fa-sync"></i>
                                    </a>
                                    <a href="<?=base_url('biometric/device_edit/' . $row['id'])?>" class="btn btn-default btn-xs" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?=base_url('biometric/device_delete/' . $row['id'])?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this device?');" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No devices configured yet. Use the form on the left to add your first ZKTeco device.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

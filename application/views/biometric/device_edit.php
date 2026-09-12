<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-edit"></i> Edit ZKTeco Biometric Device</h4>
            </header>
            <form action="<?=base_url('biometric/device_edit/' . $device['id'])?>" method="post">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                <div class="panel-body">
                    <div class="form-group mb-md">
                        <label class="control-label">Device Name / Location <span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?=set_value('name', $device['name'])?>" required />
                        <span class="error"><?=form_error('name')?></span>
                    </div>

                    <div class="form-group mb-md">
                        <label class="control-label">IP Address <span class="required">*</span></label>
                        <input type="text" class="form-control" name="ip_address" value="<?=set_value('ip_address', $device['ip_address'])?>" required />
                        <span class="error"><?=form_error('ip_address')?></span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-md">
                                <label class="control-label">Port <span class="required">*</span></label>
                                <input type="number" class="form-control" name="port" value="<?=set_value('port', $device['port'])?>" required />
                                <span class="error"><?=form_error('port')?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-md">
                                <label class="control-label">Protocol</label>
                                <select name="protocol" class="form-control">
                                    <option value="UDP" <?=$device['protocol'] == 'UDP' ? 'selected' : ''?>>UDP (Recommended)</option>
                                    <option value="TCP" <?=$device['protocol'] == 'TCP' ? 'selected' : ''?>>TCP</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-md">
                        <label class="control-label">Terminal Use For</label>
                        <select name="device_type" class="form-control">
                            <option value="both" <?=$device['device_type'] == 'both' ? 'selected' : ''?>>Both (Students & Staff)</option>
                            <option value="student" <?=$device['device_type'] == 'student' ? 'selected' : ''?>>Students Only</option>
                            <option value="staff" <?=$device['device_type'] == 'staff' ? 'selected' : ''?>>Staff Only</option>
                        </select>
                    </div>

                    <div class="form-group mb-md">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" <?=$device['status'] == 1 ? 'selected' : ''?>>Active</option>
                            <option value="0" <?=$device['status'] == 0 ? 'selected' : ''?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group mb-md">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" name="auto_clear" value="1" id="auto_clear" <?=$device['auto_clear'] ? 'checked' : ''?> />
                            <label for="auto_clear">Clear attendance memory on device after successful sync</label>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <a href="<?=base_url('biometric/devices')?>" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Device</button>
                </footer>
            </form>
        </section>
    </div>
</div>

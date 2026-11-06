<!doctype html>
<html lang="bn">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width,initial-scale=1" name="viewport">
	<meta name="keywords" content="">
	<meta name="description" content="<?php echo $global_config['institute_name'] ?>">
	<meta name="author" content="<?php echo $global_config['institute_name'] ?>">
	<title><?php echo translate('two_factor_authentication');?> | <?php echo $global_config['institute_name'];?></title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    
    <!-- Web Fonts  -->
	<link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700');?>" rel="stylesheet"> 
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
	<script src="<?php echo base_url('assets/vendor/jquery/jquery.js');?>"></script>
	
	<!-- sweetalert js/css -->
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
	<script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
	<!-- login page style css -->
	<link rel="stylesheet" href="<?php echo base_url('assets/login_page/css/style.css');?>">
	<script type="text/javascript">
		var base_url = '<?php echo base_url() ?>';
	</script>
    <style>
        .tfa-shield-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: rgba(0, 127, 61, 0.1);
            border: 2px solid #007F3D;
            border-radius: 50%;
            color: #007F3D;
            font-size: 32px;
            margin-bottom: 12px;
        }
        .otp-input-field {
            font-size: 28px !important;
            letter-spacing: 12px !important;
            font-weight: 800 !important;
            text-align: center !important;
            font-family: 'Courier New', Courier, monospace !important;
            height: 54px !important;
            color: #007F3D !important;
            border: 2px solid #007F3D !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 10px rgba(0, 127, 61, 0.1) !important;
            transition: all 0.3s ease;
        }
        .otp-input-field:focus {
            border-color: #005f2e !important;
            box-shadow: 0 0 12px rgba(0, 127, 61, 0.35) !important;
            background-color: #f0fdf4 !important;
        }
        .tfa-hint-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #475569;
            text-align: center;
            line-height: 1.5;
        }
        .tfa-timer {
            font-size: 13px;
            color: #e11d48;
            font-weight: 600;
            margin: 12px 0;
            text-align: center;
        }
        .btn-verify-login {
            background: #007F3D !important;
            border-color: #007F3D !important;
            color: #ffffff !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            padding: 11px 20px !important;
            border-radius: 25px !important;
            transition: all 0.3s ease;
        }
        .btn-verify-login:hover {
            background: #005f2e !important;
            border-color: #005f2e !important;
            box-shadow: 0 4px 14px rgba(0, 127, 61, 0.35);
        }
        .resend-box {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
	<body>
        <div class="auth-main">
            <div class="container">
                <div class="slideIn">
                    <!-- image and information -->
                    <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-12 col-xs-12 no-padding fitxt-center">
                        <div class="image-area">
                        <div class="content">
                            <div class="image-hader">
                                <h2><?php echo translate('welcome_to');?></h2>
                            </div>
                            <div class="center img-hol-p">
                                <img src="<?=$this->application_model->getBranchImage($branch_id, 'logo')?>" height="60" alt="School">
                            </div>
                            <div class="address">
                                <p><?php echo $global_config['address'];?></p>
                            </div>			
                            <div class="f-social-links center">
                                <a href="<?php echo $global_config['facebook_url'];?>" target="_blank">
                                    <span class="fab fa-facebook-f"></span>
                                </a>
                                <a href="<?php echo $global_config['twitter_url'];?>" target="_blank">
                                    <span class="fab fa-twitter"></span>
                                </a>
                                <a href="<?php echo $global_config['linkedin_url'];?>" target="_blank">
                                    <span class="fab fa-linkedin-in"></span>
                                </a>
                                <a href="<?php echo $global_config['youtube_url'];?>" target="_blank">
                                    <span class="fab fa-youtube"></span>
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- 2FA Verification Form -->
                    <div class="col-lg-6 col-lg-offset-right-1 col-md-6 col-md-offset-right-1 col-sm-12 col-xs-12 no-padding">
                        <div class="sign-area">
                            <div class="sign-hader text-center" style="margin-bottom: 15px;">
                                <div class="tfa-shield-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h3 style="font-weight: 700; color: #1e293b; margin-top: 5px; margin-bottom: 4px;">Two-Factor Authentication</h3>
                                <p style="color: #64748b; font-size: 13px; margin-bottom: 0;">দ্বি-স্তর বিশিষ্ট নিরাপত্তা যাচাইকরণ</p>
                            </div>

                            <div class="tfa-hint-badge">
                                <?php
                                $masked_email = '';
                                if (!empty($email)) {
                                    $parts = explode('@', $email);
                                    $masked_email = (strlen($parts[0]) > 3 ? substr($parts[0], 0, 3) : substr($parts[0], 0, 1)) . '•••@' . (isset($parts[1]) ? $parts[1] : '');
                                }
                                $masked_mobile = '';
                                if (!empty($mobile)) {
                                    $masked_mobile = substr($mobile, 0, 4) . '•••' . substr($mobile, -3);
                                }
                                ?>
                                <?php if ($auth_method == 'sms' && !empty($masked_mobile)): ?>
                                    <i class="fas fa-comment-dots text-success"></i> আপনার মোবাইল নম্বর <strong><?=$masked_mobile?></strong>-এ ৬-সংখ্যার সিকিউরিটি কোড পাঠানো হয়েছে।
                                <?php elseif ($auth_method == 'both' && !empty($masked_email) && !empty($masked_mobile)): ?>
                                    <i class="fas fa-paper-plane text-success"></i> আপনার ইমেইল <strong><?=$masked_email?></strong> ও মোবাইল <strong><?=$masked_mobile?></strong>-এ সিকিউরিটি কোড পাঠানো হয়েছে।
                                <?php else: ?>
                                    <i class="fas fa-envelope text-success"></i> আপনার নিবন্ধিত ইমেইল <strong><?=(!empty($masked_email) ? $masked_email : 'অ্যাকাউন্টে')?></strong> ৬-সংখ্যার সিকিউরিটি কোড পাঠানো হয়েছে।
                                <?php endif; ?>
                            </div>

                            <?php echo form_open($this->uri->uri_string()); ?>
                                <div class="form-group text-center">
                                    <label style="font-weight: 600; color: #334155; margin-bottom: 8px;">
                                        ৬-সংখ্যার ওটিপি (OTP) কোডটি লিখুন:
                                    </label>
                                    <input type="text" 
                                           name="code" 
                                           id="otp_code_input" 
                                           class="form-control otp-input-field" 
                                           placeholder="••••••" 
                                           maxlength="6" 
                                           autocomplete="one-time-code" 
                                           inputmode="numeric" 
                                           pattern="[0-9]*" 
                                           autofocus 
                                           required />
                                </div>

                                <div class="tfa-timer">
                                    <i class="far fa-clock"></i> মেয়াদ বাকি: <span id="countdown_timer">--:--</span>
                                </div>

                                <div class="form-group" style="margin-top: 15px;">
                                    <button type="submit" id="btn_verify_submit" class="btn btn-block btn-verify-login">
                                        <i class="fas fa-check-circle"></i> যাচাই করুন এবং প্রবেশ করুন (Verify & Login)
                                    </button>
                                </div>

                                <div class="resend-box">
                                    <a href="<?=base_url($this->authentication_model->getSegment(1) . 'two_fa_resend')?>" id="btn_resend_code" style="color: #007F3D; font-weight: 600; font-size: 13px; text-decoration: none;">
                                        <i class="fas fa-redo"></i> কোড পাননি? পুনরায় কোড পাঠান (Resend Code)
                                    </a>
                                    <div id="resend_cooldown" style="display:none; font-size: 12px; color: #64748b; margin-top: 4px;">
                                        পুনরায় কোড পাঠাতে অপেক্ষা করুন: <span id="resend_timer">60</span> সেকেন্ড
                                    </div>
                                </div>

                                <div style="margin-top: 20px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 14px;">
                                    <a href="<?=base_url($this->authentication_model->getSegment(1) . 'authentication/logout')?>" style="color: #94a3b8; font-size: 13px; text-decoration: none;">
                                        <i class="fas fa-arrow-left"></i> লগইন পেজে ফিরে যান (Back to Login)
                                    </a>
                                </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
		<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery-placeholder/jquery-placeholder.js');?>"></script>
		<!-- backstretch js -->
		<script src="<?php echo base_url('assets/login_page/js/jquery.backstretch.min.js');?>"></script>
		<script src="<?php echo base_url('assets/login_page/js/custom.js');?>"></script>

		<?php
		$alertclass = "";
		if($this->session->flashdata('alert-message-success')){
			$alertclass = "success";
		} else if ($this->session->flashdata('alert-message-error')){
			$alertclass = "error";
		} else if ($this->session->flashdata('alert-message-info')){
			$alertclass = "info";
		} else if ($this->session->flashdata('alert-message-warning')){
			$alertclass = "warning";
		}
		if($alertclass != ''):
			$alert_message = $this->session->flashdata('alert-message-'. $alertclass);
		?>
			<script type="text/javascript">
				swal({
					toast: true,
					position: 'top-end',
					type: '<?php echo $alertclass;?>',
					title: '<?php echo $alert_message;?>',
					showConfirmButton: false,
					timer: 4000
				});
			</script>
		<?php endif; ?>

        <script type="text/javascript">
            $(document).ready(function() {
                // Auto format / numeric only input for OTP
                $('#otp_code_input').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 6) {
                        $('#btn_verify_submit').focus();
                    }
                });

                // Countdown Timer
                var expireTimestamp = <?=isset($expires_at) ? (int)$expires_at : (time() + 600)?>;
                function updateTimer() {
                    var now = Math.floor(Date.now() / 1000);
                    var diff = expireTimestamp - now;
                    if (diff <= 0) {
                        $('#countdown_timer').html('<span style="color: #dc2626;">মেয়াদ শেষ (Expired)</span>');
                        return;
                    }
                    var minutes = Math.floor(diff / 60);
                    var seconds = diff % 60;
                    $('#countdown_timer').text((minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds);
                    setTimeout(updateTimer, 1000);
                }
                updateTimer();

                // Resend Cooldown Management
                var lastSent = <?=(int)$this->session->userdata('2FA_LAST_SENT') ?: time()?>;
                var nowTs = Math.floor(Date.now() / 1000);
                var elapsed = nowTs - lastSent;
                var cooldown = 60 - elapsed;

                if (cooldown > 0) {
                    $('#btn_resend_code').hide();
                    $('#resend_cooldown').show();
                    var cdInterval = setInterval(function() {
                        cooldown--;
                        $('#resend_timer').text(cooldown);
                        if (cooldown <= 0) {
                            clearInterval(cdInterval);
                            $('#resend_cooldown').hide();
                            $('#btn_resend_code').show();
                        }
                    }, 1000);
                }
            });
        </script>
	</body>
</html>

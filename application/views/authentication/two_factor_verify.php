<?php
$current_lang = $this->session->userdata('set_lang');
if (empty($current_lang)) {
    $current_lang = get_global_setting('translation');
}
if (empty($current_lang)) {
    $current_lang = 'bengali';
}
$is_bn = ($current_lang === 'bengali');
?>
<!doctype html>
<html lang="<?=$is_bn ? 'bn' : 'en'?>">
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
	<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        <?php if ($is_bn): ?>
        @font-face {
            font-family: 'DVSNumerals';
            src: local('Noto Sans Bengali'), local('Hind Siliguri');
            unicode-range: U+0030-0039, U+09E6-09EF;
        }
        body, h1, h2, h3, h4, h5, h6, p, span, label, input, button, a {
            font-family: 'DVSNumerals', 'Hind Siliguri', 'Signika', sans-serif !important;
        }
        <?php else: ?>
        body, h1, h2, h3, h4, h5, h6, p, span, label, input, button, a {
            font-family: 'Signika', sans-serif !important;
        }
        <?php endif; ?>
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
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #f1f5f9 !important;
            text-align: center;
            line-height: 1.5;
        }
        .tfa-hint-badge strong {
            color: #4ade80 !important;
        }
        .tfa-timer {
            font-size: 13px;
            color: #f87171 !important;
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
                            <div class="sign-hader text-center" style="margin-bottom: 20px;">
                                <div class="tfa-shield-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h3 style="font-weight: 700; color: #ffffff !important; margin-top: 5px; margin-bottom: 4px;"><?php echo translate('two_factor_authentication'); ?></h3>
                                <p style="color: #cbd5e1 !important; font-size: 13px; margin-bottom: 0;">
                                    <?php 
                                    if (!empty($instruction)) {
                                        echo html_escape($instruction);
                                    } elseif ($auth_method === 'app') {
                                        echo translate('download_google_authenticator_app_in_your_mobile_device');
                                    } else {
                                        echo translate('a_unique_code_has_been_sent_to_your_email_address');
                                    }
                                    ?>
                                </p>
                            </div>

                            <?php echo form_open($this->uri->uri_string()); ?>
                                <div class="form-group">
                                    <div class="input-group input-group-icon" style="margin-bottom: 5px;">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fas fa-key"></i></span>
                                        </span>
                                        <input type="text" 
                                               name="code" 
                                               id="otp_code_input" 
                                               class="form-control" 
                                               placeholder="<?=translate('authentication_code')?>" 
                                               maxlength="6" 
                                               autocomplete="one-time-code" 
                                               inputmode="numeric" 
                                               pattern="[0-9]*" 
                                               style="height: 48px; font-size: 18px; font-weight: 600; text-align: center; letter-spacing: 4px;"
                                               autofocus 
                                               required />
                                    </div>
                                </div>

                                <?php if (!empty($show_remember)): ?>
                                <div class="checkbox-custom checkbox-default" style="margin-top: 10px; margin-bottom: 15px; text-align: left;">
                                    <input type="checkbox" name="remember_browser" id="remember_browser" value="1">
                                    <label for="remember_browser" style="color: #cbd5e1; font-size: 13px; font-weight: normal; cursor: pointer;">
                                        <?=translate('do_not_ask_again_on_this_browser')?>
                                    </label>
                                </div>
                                <?php endif; ?>

                                <?php if ($auth_method !== 'app' && !empty($expires_at)): ?>
                                <div class="tfa-timer mb-sm">
                                    <i class="far fa-clock"></i> <?php echo translate('time_remaining'); ?>: <span id="countdown_timer">--:--</span>
                                </div>
                                <?php endif; ?>

                                <div class="form-group" style="margin-top: 15px;">
                                    <button type="submit" id="btn_verify_submit" class="btn btn-block btn-verify-login" style="height: 46px; font-size: 16px; font-weight: 700;">
                                        <i class="fas fa-check-circle"></i> <?=translate('verify')?>
                                    </button>
                                </div>

                                <?php if ($auth_method !== 'app'): ?>
                                <div class="resend-box" style="margin-top: 10px; text-align: center;">
                                    <a href="<?=base_url($this->authentication_model->getSegment(1) . 'two_fa_resend')?>" id="btn_resend_code" style="color: #4ade80 !important; font-weight: 600; font-size: 13px; text-decoration: none;">
                                        <i class="fas fa-redo"></i> <?php echo translate('resend_code'); ?>
                                    </a>
                                    <div id="resend_cooldown" style="display:none; font-size: 12px; color: #94a3b8; margin-top: 4px;">
                                        <?php echo translate('resend_cooldown_wait'); ?>: <span id="resend_timer">60</span> <?php echo translate('seconds'); ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div style="margin-top: 20px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.12); padding-top: 14px;">
                                    <a href="<?=base_url($this->authentication_model->getSegment(1) . 'authentication/logout')?>" style="color: #94a3b8 !important; font-size: 13px; text-decoration: none;">
                                        <i class="fas fa-arrow-left"></i> <?php echo translate('back_to_login'); ?>
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
                        $('#countdown_timer').html('<span style="color: #dc2626;"><?php echo translate('code_expired'); ?></span>');
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


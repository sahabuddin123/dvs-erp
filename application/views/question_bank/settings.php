<?php
$is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
$s = $settings;
?>

<div class="row">
	<div class="col-md-8">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fas fa-sliders-h text-primary"></i> <?=$is_bn ? 'এআই কনফিগারেশন ও সেটিংস' : 'AI Engine Configuration'?></h2>
			</header>
			<?php echo form_open('question_bank/settings', array('class' => 'form-horizontal form-bordered validate')); ?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'এআই সেবা প্রদানকারী' : 'AI Provider'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<select class="form-control" name="api_provider" id="api_provider" data-plugin-selectTwo data-width="100%">
							<option value="gemini" <?=$s['api_provider'] == 'gemini' ? 'selected' : ''?>>Google Gemini API (<?=$is_bn ? 'সুপারিশকৃত ও দ্রুততম' : 'Recommended & Fast'?>)</option>
							<option value="openai" <?=$s['api_provider'] == 'openai' ? 'selected' : ''?>>OpenAI (ChatGPT API)</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'এআই এপিআই কি (API Key)' : 'API Key'?> <span class="required">*</span></label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"><i class="fas fa-key"></i></span>
							<input type="password" class="form-control" name="api_key" id="api_key" value="<?=html_escape($s['api_key'])?>" placeholder="AIzaSy... or sk-..." />
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" onclick="toggleApiKeyVisibility()"><i class="fas fa-eye" id="eyeIcon"></i></button>
							</span>
						</div>
						<span class="help-block" style="font-size: 11.5px; color: #64748b; margin-bottom: 0;">
							<?=$is_bn ? 'গুগল এআই স্টুডিও থেকে বিনামূল্যে জেমিনি এপিআই কি পাওয়া যায়: <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>' : 'Get free Gemini API Key at: <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>'?>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'মডেলের নাম (Model)' : 'Model Name'?></label>
					<div class="col-md-8">
						<select class="form-control" name="model_name" id="model_name" data-plugin-selectTwo data-width="100%">
							<option value="gemini-1.5-flash" <?=$s['model_name'] == 'gemini-1.5-flash' ? 'selected' : ''?>>gemini-1.5-flash (<?=$is_bn ? 'উচ্চ গতি ও নির্ভুল' : 'Fast & High Quality'?>)</option>
							<option value="gemini-2.0-flash" <?=$s['model_name'] == 'gemini-2.0-flash' ? 'selected' : ''?>>gemini-2.0-flash (<?=$is_bn ? 'লেটেস্ট ভার্সন' : 'Latest'?>)</option>
							<option value="gemini-1.5-pro" <?=$s['model_name'] == 'gemini-1.5-pro' ? 'selected' : ''?>>gemini-1.5-pro (<?=$is_bn ? 'উচ্চতর বিশ্লেষণ' : 'Advanced Reasoning'?>)</option>
							<option value="gpt-4o-mini" <?=$s['model_name'] == 'gpt-4o-mini' ? 'selected' : ''?>>gpt-4o-mini (OpenAI)</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'ডিফল্ট ভাষা' : 'Default Language'?></label>
					<div class="col-md-8">
						<select class="form-control" name="default_language" data-plugin-selectTwo data-width="100%">
							<option value="bengali" <?=$s['default_language'] == 'bengali' ? 'selected' : ''?>>বাংলা (Bengali)</option>
							<option value="english" <?=$s['default_language'] == 'english' ? 'selected' : ''?>>English</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"><?=$is_bn ? 'কাস্টম নির্দেশনা (System Prompt)' : 'Custom Prompt'?></label>
					<div class="col-md-8">
						<textarea class="form-control" name="custom_system_prompt" rows="3" placeholder="<?=$is_bn ? 'যেমন: জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB) এর সিলেবাস অনুযায়ী প্রশ্ন প্রণয়ন করতে হবে...' : 'e.g. Adhere to school curriculum standards...'?>"><?=html_escape($s['custom_system_prompt'])?></textarea>
						<span class="help-block" style="font-size: 11.5px; margin-bottom: 0;">
							<?=$is_bn ? 'প্রতিষ্ঠানের বিশেষ কোনো প্রশ্ন প্রণয়ন নির্দেশনা থাকলে এখানে উল্লেখ করতে পারেন।' : 'Optional custom guidelines given to the AI during question generation.'?>
						</span>
					</div>
				</div>
			</div>

			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-3 col-md-8">
						<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?=$is_bn ? 'সেটিংস সংরক্ষণ করুন' : 'Save Settings'?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

	<!-- Information Card -->
	<div class="col-md-4">
		<section class="panel">
			<header class="panel-heading" style="background: #f8fafc;">
				<h2 class="panel-title" style="font-size: 15px;"><i class="fas fa-info-circle text-info"></i> <?=$is_bn ? 'এআই নির্দেশিকা' : 'AI Setup Guide'?></h2>
			</header>
			<div class="panel-body" style="font-size: 13px; line-height: 1.6; color: #334155;">
				<p>
					<?=$is_bn ? '<strong>ড্রিম ভ্যালি স্কুল ইআরপি</strong> প্রশ্ন ভাণ্ডার মডিউলে আধুনিক এআই সুবিধা যুক্ত করা হয়েছে। এটি শিক্ষকদের কয়েক সেকেন্ডের মধ্যে যে কোনো বিষয়ের এমসিকিউ, সৃজনশীল ও রচনামূলক প্রশ্ন তৈরি করতে সহায়তা করে।' : '<strong>Dream Valley School ERP</strong> Question Bank is powered by state-of-the-art AI. Teachers can generate high-quality MCQs, creative, and short questions in seconds.'?>
				</p>
				<hr style="margin: 12px 0;">
				<h5 style="font-weight: 700; color: #007F3D;"><i class="fas fa-key"></i> <?=$is_bn ? 'বিনামূল্যে এপিআই কি পাওয়ার উপায়:' : 'How to get free API Key:'?></h5>
				<ol style="padding-left: 18px; margin-bottom: 0;">
					<li><a href="https://aistudio.google.com/app/apikey" target="_blank" style="font-weight: 600;">aistudio.google.com</a> <?=$is_bn ? 'ভিজিট করুন' : 'visit'?></li>
					<li><?=$is_bn ? 'আপনার গুগল অ্যাকাউন্ট দিয়ে সাইন ইন করুন' : 'Sign in with your Google Account'?></li>
					<li><?=$is_bn ? '<strong>Create API Key</strong> বাটনে ক্লিক করে কি কপি করুন' : 'Click <strong>Create API Key</strong> and copy the key'?></li>
					<li><?=$is_bn ? 'বাম পাশের ফর্মে পেস্ট করে সেভ করুন' : 'Paste into the left form and click Save'?></li>
				</ol>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
	function toggleApiKeyVisibility() {
		var input = document.getElementById('api_key');
		var icon = document.getElementById('eyeIcon');
		if (input.type === 'password') {
			input.type = 'text';
			icon.className = 'fas fa-eye-slash';
		} else {
			input.type = 'password';
			icon.className = 'fas fa-eye';
		}
	}
</script>


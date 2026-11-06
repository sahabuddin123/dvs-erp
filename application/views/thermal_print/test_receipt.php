<?php
$width = (isset($settings->printer_width) && $settings->printer_width == '58mm') ? '58mm' : '80mm';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test Thermal Print</title>
	<style type="text/css">
		@page { size: <?=$width?> auto; margin: 0mm !important; }
		@media print { html, body { width: <?=$width?> !important; margin: 0 auto !important; padding: 0 !important; } }
		body { width: <?=$width?>; max-width: <?=$width?>; margin: 0 auto; padding: 4mm 3mm; font-family: 'Courier New', Courier, monospace; font-size: 11px; line-height: 1.35; color: #000; }
		.text-center { text-align: center; }
		.dashed-divider { border-top: 1px dashed #000; margin: 5px 0; }
		.double-divider { border-top: 2px solid #000; margin: 5px 0; }
	</style>
</head>
<body onload="window.print()">
	<div class="text-center">
		<strong style="font-size: 13px;"><?=(!empty($settings->header_title) ? html_escape($settings->header_title) : 'ড্রিম ভ্যালি স্কুল')?></strong><br/>
		<small><?=html_escape($settings->header_subtitle)?></small><br/>
		<small><?=html_escape($settings->address)?></small>
	</div>
	<div class="double-divider"></div>
	<div class="text-center" style="font-weight: bold;">
		** POS THERMAL TEST RECEIPT **
	</div>
	<div class="dashed-divider"></div>
	<table style="width: 100%; font-size: 11px;">
		<tr><td>রোল সাইজ (Roll Width):</td><td style="text-align: right;"><strong><?=$width?></strong></td></tr>
		<tr><td>তারিখ ও সময়:</td><td style="text-align: right;"><?=date('d/m/Y h:i A')?></td></tr>
		<tr><td>টেস্ট স্ট্যাটাস:</td><td style="text-align: right; color: green;">SUCCESS (সফল)</td></tr>
	</table>
	<div class="dashed-divider"></div>
	<div class="text-center" style="font-size: 10px; margin-top: 10px;">
		থার্মাল প্রিন্টার সফলভাবে সংযুক্ত এবং প্রস্তুত রয়েছে।
	</div>
	<div class="double-divider"></div>
</body>
</html>


<?php

$url = '/privacy-policy';

$cookiePage = get_page_by_title('Cookie Policy');
$privacyPage = get_page_by_title('Privacy Policy');

if($cookiePage != null) {
	$cookieStatus = get_post_status($cookiePage->ID);

	if($cookieStatus == 'publish') {
		$url = '/cookie-policy';
	}
}
?>
<div id="wp-notification" class="closed">
	<div class="wp-notification-container">
		<p>By clicking "Accept All Cookies", you agree to the storing of cookies on your device to enhance site navigation, analyse site usage, assist in our marketing efforts, and for personalised advertising.</p>
		<div>
			<a href="<?= site_url($url) ?>/">More Information</a>
			<span id="wp-notification-toggle">Accept All Cookies</span>
		</div>
	</div>
</div>
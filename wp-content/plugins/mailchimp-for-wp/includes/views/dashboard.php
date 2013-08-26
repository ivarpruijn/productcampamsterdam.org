<div id="mc4wp_admin" class="wrap">

	<h1>MailChimp for WordPress - Configuration</h1>

	<ul id="mc4wp-nav">
		<li><a <?php if($tab == 'api-settings') echo 'class="active"'; ?> data-target="api-settings" href="admin.php?page=mailchimp-for-wp&tab=api-settings">API settings</a></li>
		<li><a <?php if($tab == 'checkbox-settings') echo 'class="active"'; ?> data-target="checkbox-settings" href="admin.php?page=mailchimp-for-wp&tab=checkbox-settings">Checkbox settings</a></li>
		<li><a <?php if($tab == 'form-settings') echo 'class="active"'; ?> data-target="form-settings" href="admin.php?page=mailchimp-for-wp&tab=form-settings">Form settings</a></li>
	</ul>

	<h2 style="display:none;"></h2>
	<?php settings_errors(); ?>

	<div style="float:left; width:70%;">

		<form method="post" action="options.php">
				
			<?php settings_fields( 'mc4wp_options_group' ); ?>

			<div id="mc4wp-tabs">

				<?php 				
					// include tab pages
					foreach($tabs as $t) {
						require "$t.php";
					}
						
				?>

			</div>

		</form>

		<p class="copyright-notice">I would like to remind you that this plugin is <b>not</b> developed by or affiliated with MailChimp in any way. My name is <a href="http://www.dannyvankooten.com/">Danny van Kooten</a>, I am a Dutch freelance webdeveloper thinking you might like a plugin like this. :-)</p>

	</div>

	<div style="width:26%; float:right; margin-left:3%;">
		
		<div class="box donatebox">
			<h3>Donate $10, $20 or $50</h3>
			<p>I spent countless hours developing this plugin for <b>FREE</b>. If you like it, consider donating a small token of your appreciation. It is much appreciated!</p>
					
			<form class="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="business" value="AP87UHXWPNBBU">
				<input type="hidden" name="lc" value="US">
				<input type="hidden" name="item_name" value="Danny van Kooten">
				<input type="hidden" name="item_number" value="MailChimp for WordPress">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
			</form>

			<p>Alternatively, you can: </p>
            <ul>
                <li><a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/mailchim-for-wp?rate=5#postform">Give a 5&#9733; review on WordPress.org</a></li>
                <li><a target="_blank" href="http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/">Link to the plugin page on my website</a></li>
                <li><a target="_blank" href="http://twitter.com/?status=I%20manage%20my%20%23WordPress%20sign-up%20forms%20using%20MailChimp%20for%20WP%20and%20I%20love%20it%20-%20check%20it%20out!%20http%3A%2F%2Fwordpress.org%2Fplugins%2Fmailchimp-for-wp%2F">Tweet about MailChimp for WP</a></li>
            </ul>
        </div>

        <div id="mc4wp-info-tabs">
			<div class="info-tab info-tab-form-settings" <?php if($tab != 'form-settings') { echo 'style="display:none;"'; } ?>>
				<h4>Notes regarding the form designer</h4>
				<p>At a minimum, your form should include an EMAIL field and a submit button.</p>
				
				<p>Add other fields if you like but keep in mind that...</p>
					<ul class="ul-square">
						<li>...all field names should be uppercased</li>
						<li>... field names should match your MailChimp lists merge fields tags</li>
					</ul>


				<p><strong>Special form strings</strong></p>
				<table>
					<tr>
						<th>%N%</th><td>The form instance number. Useful when you have more than one form on a certain page.</td>
					</tr>
					<tr>
						<th>%IP_ADDRESS%</th><td>The IP adress of the visitor.</td>
					</tr>
					<tr>
						<th>%DATE%</th><td>The current date (dd/mm/yyyy).</td>
					</tr>
				</table>

				<p><strong>Visual appearance</strong></p>
				<p>Alter the visual appearance of the form by applying CSS rules to <b>form.mc4wp-form</b>. Add these CSS rules to your theme's stylesheet
					 which can in most cases be found here: <em><?php echo get_stylesheet_directory(); ?>/style.css</em>.</p>
					 <p>The <a href="http://wordpress.org/plugins/mailchimp-for-wp/faq/" target="_blank">MailChimp for WP FAQ</a> lists the various CSS selectors you can use to target the different elements.</p>
			</div>
		</div>

		<div>
			<h3>Looking for support?</h3>
        	<p>Having trouble? Please use the <a href="http://wordpress.org/support/plugin/mailchimp-for-wp">support forums</a> on WordPress.org.</p>
		</div>

	</div>

	

</div>
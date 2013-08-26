<div id="mc4wp-tab-api-settings" class="mc4wp-tab <?php if($tab == 'api-settings') { echo 'active'; } ?>">

	<h2>API Settings <?php if($connected) { ?><span class="status connected">CONNECTED</span> <?php } else { ?><span class="status not_connected">NOT CONNECTED</span><?php } ?></h2>
	<table class="form-table">

		<tr valign="top">
			<th scope="row"><label for="mailchimp_api_key">MailChimp API Key</label> <a target="_blank" href="http://admin.mailchimp.com/account/api">(?)</a></th>
			<td><input type="text" size="50" placeholder="Your MailChimp API key" id="mailchimp_api_key" name="mc4wp[mailchimp_api_key]" value="<?php echo $opts['mailchimp_api_key']; ?>" /></td>
		</tr>

	</table>

	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

	<?php if($connected) { ?>
	<h3>MailChimp data</h3>
	<p>The table below shows your cached MailChimp lists configuration. If you made any changes in your MailChimp configuration that is not yet represented in the table below, please renew the cache manually by hitting the "renew cached data" button.</p>

	<h4>Lists</h4>
	<table class="wp-list-table widefat">
		<thead>
			<tr>
				<th scope="col">ID</th><th scope="col">Name</th>
			</tr>
		</thead>
		<tbody>
			<?php if($lists && is_array($lists)) { ?>
			<?php foreach($lists as $l) { ?>
			<tr valign="top">
				<td><?php echo $l['id']; ?></td>
				<td><?php echo $l['name']; ?></td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr><td colspan="3"><p>No lists...</p></tr></td>
			<?php } ?>
		</tbody>
	</table>

	<p><a href="<?php echo get_admin_url(null, 'admin.php?page=mailchimp-for-wp&renew-cached-data'); ?>" class="button">Renew cached data</a></p>
	<?php } ?>

</div>


<div id="mc4wp-tab-mailchimp-settings" class="mc4wp-tab <?php if($tab == 'mailchimp-settings') { echo 'active'; } ?>">
	<h2>MailChimp settings</h2>
	<?php if($connected) { ?>

		<?php if(empty($opts['mailchimp_lists'])) { ?>
		<p class="alert warning"><b>Notice:</b> You must select atleast 1 list to which commenters should be subscribed.</p>
		<?php } ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">Lists</th>
				<td>
					<?php // loop through lists
					foreach($lists['data'] as $l) {
						?><input type="checkbox" id="list_<?php echo $l['id']; ?>_cb" name="mc4wp[mailchimp_lists][<?php echo $l['id']; ?>]" value="<?php echo $l['id']; ?>" <?php if(array_key_exists($l['id'], $opts['mailchimp_lists'])) echo 'checked="checked"'; ?>> <label for="list_<?php echo $l['id']; ?>_cb"><?php echo $l['name']; ?></label><br /><?php
					} ?>
				</td>
				<td class="desc">Select the lists to which your commenters should be subscribed</td>
			</tr>
			<tr valign="top">
				<th scope="row">Double opt-in?</th>
					<td><input type="radio" id="mc4wp_double_optin_1" name="mc4wp[mailchimp_double_optin]" value="1" <?php if($opts['mailchimp_double_optin'] == 1) echo 'checked="checked"'; ?> /> <label for="mc4wp_double_optin_1">Yes</label> &nbsp; <input type="radio" id="mc4wp_double_optin_0" name="mc4wp[mailchimp_double_optin]" value="0" <?php if($opts['mailchimp_double_optin'] == 0) echo 'checked="checked"'; ?> /> <label for="mc4wp_double_optin_0">No</label></td>
					<td class="desc"></td>
			</tr>
		</table>


	<?php } else { ?>
		
		<p>Please provide a valid API key first.</p>

	<?php } // end if connected ?>

	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	
</div>
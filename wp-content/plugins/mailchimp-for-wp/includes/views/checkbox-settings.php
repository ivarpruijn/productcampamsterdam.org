<div id="mc4wp-tab-checkbox-settings" class="mc4wp-tab <?php if($tab == 'checkbox-settings') { echo 'active'; } ?>">
	<h2>Checkbox Settings</h2>	

	<?php if(!$connected) { ?>
		<p class="alert warning"><b>Notice:</b> Please make sure the plugin is connected to MailChimp first.</p>
	<?php } ?>	

	<?php if(empty($opts['checkbox_lists'])) { ?>
		<p class="alert warning"><b>Notice:</b> You must select atleast 1 list to subscribe to.</p>
	<?php } ?>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">Lists</th>
				
					<?php // loop through lists
					if(!$connected) { 
						?><td colspan="2">Please connect to MailChimp first.</td><?php
					} else { ?>
						<td>
						<?php foreach($lists as $l) {
							?><input type="checkbox" id="mc4wp_checkbox_list_<?php echo $l['id']; ?>_cb" name="mc4wp[checkbox_lists][<?php echo $l['id']; ?>]" value="<?php echo $l['id']; ?>" <?php if(array_key_exists($l['id'], $opts['checkbox_lists'])) echo 'checked="checked"'; ?>> <label for="mc4wp_checkbox_list_<?php echo $l['id']; ?>_cb"><?php echo $l['name']; ?></label><br /><?php
						} ?>
						</td>
						<td class="desc">Select MailChimp to which commenters should be subscribed</td>
					<?php
					} ?>
				
			</tr>
			<tr valign="top">
				<th scope="row">Double opt-in?</th>
				<td><input type="radio" id="mc4wp_checkbox_double_optin_1" name="mc4wp[checkbox_double_optin]" value="1" <?php if($opts['checkbox_double_optin'] == 1) echo 'checked="checked"'; ?> /> <label for="mc4wp_checkbox_double_optin_1">Yes</label> &nbsp; <input type="radio" id="mc4wp_checkbox_double_optin_0" name="mc4wp[checkbox_double_optin]" value="0" <?php if($opts['checkbox_double_optin'] == 0) echo 'checked="checked"'; ?> /> <label for="mc4wp_checkbox_double_optin_0">No</label></td>
				<td class="desc"></td>
			</tr>
			<tr valign="top">
				<th scope="row">Add the checkbox to these forms</th>
				<td colspan="2">
					<label><input name="mc4wp[checkbox_show_at_comment_form]" value="1" type="checkbox" <?php if($opts['checkbox_show_at_comment_form']) echo 'checked '; ?>> Comment form</label> &nbsp; 
					<label><input name="mc4wp[checkbox_show_at_registration_form]" value="1" type="checkbox" <?php if($opts['checkbox_show_at_registration_form']) echo 'checked '; ?>> Registration form</label> &nbsp; 
					<?php if(is_multisite()) { ?><label><input name="mc4wp[checkbox_show_at_ms_form]" value="1" type="checkbox" <?php if($opts['checkbox_show_at_ms_form']) echo 'checked '; ?>> Multisite form</label> &nbsp; <?php } ?>
					<?php if($runs_buddypress) { ?><label><input name="mc4wp[checkbox_show_at_bp_form]" value="1" type="checkbox" <?php if($opts['checkbox_show_at_bp_form']) echo 'checked '; ?>> BuddyPress form</label> &nbsp; <?php } ?>
					<label><input name="mc4wp[checkbox_show_at_other_forms]" value="1" type="checkbox" <?php if($opts['checkbox_show_at_other_forms']) echo 'checked '; ?>> Other forms (manual)</label> &nbsp; 
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_checkbox_label">Checkbox label text</label></th>
				<td colspan="2"><input type="text"  class="widefat" id="mc4wp_checkbox_label" name="mc4wp[checkbox_label]" value="<?php echo esc_attr($opts['checkbox_label']); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Pre-check the checkbox?</th>
				<td><input type="radio" id="mc4wp_checkbox_precheck_1" name="mc4wp[checkbox_precheck]" value="1" <?php if($opts['checkbox_precheck'] == 1) echo 'checked="checked"'; ?> /> <label for="mc4wp_checkbox_precheck_1">Yes</label> &nbsp; <input type="radio" id="mc4wp_checkbox_precheck_0" name="mc4wp[checkbox_precheck]" value="0" <?php if($opts['checkbox_precheck'] == 0) echo 'checked="checked"'; ?> /> <label for="mc4wp_checkbox_precheck_0">No</label></td>
				<td class="desc"></td>
			</tr>
			<tr valign="top">
				<th scope="row">Load some default CSS?</th>
				<td><input type="radio" id="mc4wp_checbox_css_1" name="mc4wp[checkbox_css]" value="1" <?php if($opts['checkbox_css'] == 1) echo 'checked="checked"'; ?> /> <label for="mc4wp_checbox_css_1">Yes</label> &nbsp; <input type="radio" id="mc4wp_checbox_css_0" name="mc4wp[checkbox_css]" value="0" <?php if($opts['checkbox_css'] == 0) echo 'checked="checked"'; ?> /> <label for="mc4wp_checbox_css_0">No</label></td>
				<td class="desc">Tick "yes" if the checkbox appears in a weird place.</td>
			</tr>
			<tr valign="top">
				<td colspan="3"><p>Custom or additional styling can be applied by styling the paragraph element with ID <b>#mc4wp-checkbox</b> or it's child elements.</p></td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

	</div>
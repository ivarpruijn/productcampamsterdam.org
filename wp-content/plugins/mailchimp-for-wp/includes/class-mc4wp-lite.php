<?php

class MC4WP_Lite
{
	private static $instance;
	private static $mc_api;
	private $options = array();
	public $checkbox, $form;

	public static function get_instance()
	{
		if(!isset(self::$instance)) {
			self::$instance = new MC4WP_Lite();
		}

		return self::$instance;
	}

	public function __construct()
	{
		$defaults = array(
			'mailchimp_api_key' => '',
			'checkbox_label' => 'Sign me up for the newsletter!', 'checkbox_precheck' => 1, 'checkbox_css' => 0, 
			'checkbox_show_at_comment_form' => 0, 'checkbox_show_at_registration_form' => 0, 'checkbox_show_at_ms_form' => 0, 'checkbox_show_at_bp_form' => 0, 'checkbox_show_at_other_forms' => 0,
			'checkbox_lists' => array(), 'checkbox_double_optin' => 1,
			'form_usage' => 0, 'form_css' => 0, 'form_markup' => "<p>\n\t<label for=\"mc4wp_f%N%_email\">Email address: </label>\n\t<input type=\"email\" id=\"mc4wp_f%N%_email\" name=\"EMAIL\" required placeholder=\"Your email address\" />\n</p>\n\n<p>\n\t<input type=\"submit\" value=\"Sign up\" />\n</p>",
			'form_text_success' => 'Thank you, your sign-up request was successful! Please check your e-mail inbox.', 'form_text_error' => 'Oops. Something went wrong. Please try again later.',
			'form_text_invalid_email' => 'Please provide a valid email address.', 'form_text_already_subscribed' => "Given email address is already subscribed, thank you!", 
			'form_redirect' => '', 'form_lists' => array(), 'form_double_optin' => 1, 'form_hide_after_success' => 0
		);

		$this->options = $opts = array_merge($defaults, (array) get_option('mc4wp'));

		// compatibility
		// transfer old general mailchimp settings
		if(isset($opts['mailchimp_lists']) && !empty($opts['mailchimp_lists'])) {
			$this->options['checkbox_lists'] = $this->options['form_lists'] = $opts['mailchimp_lists'];
		}
		if(isset($opts['mailchimp_double_optin'])) {
			$this->options['checkbox_double_optin'] = $this->options['form_double_optin'] = $opts['mailchimp_double_optin'];
		}

		if($opts['checkbox_show_at_comment_form'] || $opts['checkbox_show_at_registration_form'] || $opts['checkbox_show_at_bp_form'] || $opts['checkbox_show_at_ms_form'] || $opts['checkbox_show_at_other_forms']) {
			require_once 'class-mc4wp-lite-checkbox.php';
			$this->checkbox = new MC4WP_Lite_Checkbox($this);
		}

		// load form functionality
		if($opts['form_usage']) {
			require_once 'class-mc4wp-lite-form.php';
			$this->form = new MC4WP_Lite_Form($this);
		}

	}

	public function get_options() 
	{
		return $this->options;
	}

	public function get_mc_api()
	{
		if(!isset(self::$mc_api)) {

			// Only load MailChimp API if it has not been loaded yet
			// other plugins may have already at this point.
			if(!class_exists("MCAPI")) {
				require_once 'class-MCAPI.php';
			}
			
			self::$mc_api = new MCAPI($this->options['mailchimp_api_key']);
		}

		return self::$mc_api;
	}

	public function subscribe($type, $email, array $merge_vars = array(), array $data = array())
	{
		$mc = $this->get_mc_api();
		$opts = $this->get_options();

		$lists = $opts[$type . '_lists'];
		
		if(empty($lists)) {
			return 'no_lists_selected';
		}

		// add ip address to merge vars
		if(isset($data['ip'])) {
			$merge_vars['OPTINIP'] = $data['ip'];
		}

		// guess all three name kinds
		if(isset($data['name'])) {
			
			$name = $data['name'];
			$merge_vars['NAME'] = $name;

			if(!isset($merge_vars['FNAME']) && !isset($merge_vars['LNAME'])) {
				// try to fill first and last name fields as well
				$strpos = strpos($name, ' ');

				if($strpos) {
					$merge_vars['FNAME'] = substr($name, 0, $strpos);
					$merge_vars['LNAME'] = substr($name, $strpos);
				} else {
					$merge_vars['FNAME'] = $name;
					$merge_vars['LNAME'] = '...';
				}
			}
		}

		foreach($lists as $list) {
			$result = $mc->listSubscribe($list, $email, $merge_vars, 'html', $opts[$type . '_double_optin']);
		}

		if($mc->errorCode) {

			if($mc->errorCode == 214) {
				return 'already_subscribed';
			}

			if($mc->errorCode >= 250 && $mc->errorCode <= 254 && current_user_can('manage_options')) {
				return 'merge_field_error';
			}

			return false;
		}
		
		// flawed
		// this will only return the result of the last list a subscribe attempt has been sent to
		return $result;
	}


}
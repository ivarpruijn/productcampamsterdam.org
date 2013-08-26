<?php

class MC4WP_Lite_Form
{
	private $options;
	private $form_instance_number = 1;
	private $did_request = false;
	private $request_succes = false; 
	private $error = null;
	private $success = null;
	private $submitted_form_instance = 0;

	public function __construct(MC4WP_Lite $mc4wp) 
	{
		$this->options = $opts = $mc4wp->get_options();

		if($opts['form_css']) {
			add_action( 'wp_enqueue_scripts', array($this, 'load_stylesheet') );
		}
		
		add_shortcode('mc4wp-form', array($this, 'output_form'));

		// enable shortcodes in text widgets
		add_filter( 'widget_text', 'shortcode_unautop');
		add_filter( 'widget_text', 'do_shortcode', 11);

		if(isset($_POST['mc4wp_form_submit'])) {

			// change $_POST['name'] to something else, to fix WP bug
			// maybe later ok?

			add_action('init', array($this, 'subscribe'));
		}
	}

	public function load_stylesheet()
	{
		wp_enqueue_style( 'mc4wp-form-reset', plugins_url('mailchimp-for-wp/css/form.css') );
	}

	public function output_form($atts, $content = null)
	{
		$mc4wp = MC4WP_Lite::get_instance();
		$opts = $this->options;
		$is_admin = current_user_can('manage_options');

		// add some useful css classes
		$css_classes = ' ';
		if($this->error) $css_classes .= 'mc4wp-form-error ';
		if($this->success) $css_classes .= 'mc4wp-form-success ';

		$content = '<form method="post" action="'. $this->get_current_page_url() .'#mc4wp-form-'. $this->form_instance_number .'" id="mc4wp-form-'.$this->form_instance_number.'" class="mc4wp-form form'.$css_classes.'">';


		// maybe hide the form
		if(!($this->success && $opts['form_hide_after_success'])) {
			$form_markup = $this->options['form_markup'];
			// replace special values
			$form_markup = str_replace('%N%', $this->form_instance_number, $form_markup);
			$form_markup = str_replace('%IP_ADDRESS%', $this->get_ip_address(), $form_markup);
			$form_markup = str_replace('%DATE%', date('dd/mm/yyyy'), $form_markup);

			$content .= $form_markup;

			// hidden fields
			$content .= '<textarea name="mc4wp_required_but_not_really" style="display: none;"></textarea>';
			$content .= '<input type="hidden" name="mc4wp_form_submit" value="1" />';
			$content .= '<input type="hidden" name="mc4wp_form_instance" value="'. $this->form_instance_number .'" />';
		}		

		if($this->form_instance_number == $this->submitted_form_instance) {
			
			if($this->success) {
				$content .= '<p class="mc4wp-alert mc4wp-success">' . $opts['form_text_success'] . '</p>';
			} elseif($this->error) {
				
				$e = $this->error;

				if($e == 'already_subscribed') {
					$text = (empty($opts['form_text_already_subscribed'])) ? $mc4wp->get_mc_api()->errorMessage : $opts['form_text_already_subscribed'];
					$content .= '<p class="mc4wp-alert mc4wp-notice">'. $text .'</p>';
				}elseif(isset($opts['form_text_' . $e]) && !empty($opts['form_text_'. $e] )) {
					$content .= '<p class="mc4wp-alert mc4wp-error">' . $opts['form_text_' . $e] . '</p>';
				} else {
					$content .= '<p class="mc4wp-alert mc4wp-error">' . $opts['form_text_error'];

					if($is_admin) {
						$content .= $this->get_admin_notice($e);
					} 

					$content .= '</p>';
				}			
			}
			// endif
		}

		$content .= "</form>";

		// increase form instance number in case there is more than one form on a page
		$this->form_instance_number++;

		return $content;
	}

	public function subscribe()
	{
		$mc4wp = MC4WP_Lite::get_instance();
		$opts = $this->options; 

		$this->submitted_form_instance = $_POST['mc4wp_form_instance'];

		// for backwards compatibility, uppercase all post variables
		foreach($_POST as $name => $value) {
			// only uppercase variables which are not already uppercased
			// skip the mc4wp necessary form vars
	        if($name === strtoupper($name) || in_array($name, array('mc4wp_form_instance', 'mc4wp_required_but_not_really', 'mc4wp_form_submit'))) continue;
			$uppercased_name = strtoupper($name);

			// set new (uppercased) $_POST variable
			$_POST[$uppercased_name] = $value;

			// unset old post variable
			unset($_POST[$name]);			
	    }

		if(!isset($_POST['EMAIL']) || !is_email($_POST['EMAIL'])) { 
			// no (valid) e-mail address has been given

			$this->error = 'invalid_email';
			return false;
		}

		if(isset($_POST['mc4wp_required_but_not_really']) && !empty($_POST['mc4wp_required_but_not_really'])) {
			// spam bot filled the honeypot field
			return false;
		}

		$email = $_POST['EMAIL'];

		// setup merge vars
		$merge_vars = array();

		foreach($_POST as $name => $value) {

			// only add uppercases fields to merge variables array
			if($name == 'EMAIL' || $name !== strtoupper($name)) { continue; }

			$name = strtoupper($name);
			$merge_vars[$name] = $value;

		}

		$result = $mc4wp->subscribe('form', $email, $merge_vars);

		if($result === true) { 
			$this->success = true;

			// check if we want to redirect the visitor
			if(!empty($opts['form_redirect'])) {
				wp_redirect($opts['form_redirect']);
				exit;
			}

			return true;
		} else {

			$this->error = $result;
			return false;
		}

		
	}

	private function get_ip_address()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip =  $_SERVER['HTTP_CLIENT_IP'];
		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	private function get_current_page_url() {
		$page_url = 'http';

		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) { $page_url .= 's'; }

		$page_url .= '://';

		if (!isset($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
			if (isset($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'] .='?'.$_SERVER['QUERY_STRING']; }
		}

		if($_SERVER['SERVER_PORT'] != '80') {
			$page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}

		return $page_url;
	}

	private function get_admin_notice($error) {
		$api = MC4WP_Lite::get_instance()->get_mc_api();
		$notices = array();
		$notices['merge_field_error'] = 'There seems to be a problem with your merge fields. Make sure all required merge fields are present in your sign-up form.';
		$notices['no_lists_selected'] = 'No lists have been selected. <a href="'. get_admin_url(null, "admin.php?page=mailchimp-for-wp&tab=form-settings") .'">Edit your form settings</a> and select at least one list.';

		$notice = '<br /><br /><strong>Admins only: </strong>';
		
		if(isset($notices[$error])) {
			$has_notice = true;
			$notice .= $notices[$error];
		}
		if($api->errorMessage && !empty($api->errorMessage)) {
			$has_notice = true;
			$notice .= '<br /><br />MailChimp returned the following error message: ' . $api->errorMessage;
		}

		return ($has_notice) ? $notice : '';
	}

	

	

}
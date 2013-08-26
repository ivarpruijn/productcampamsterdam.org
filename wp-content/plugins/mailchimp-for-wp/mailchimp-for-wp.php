<?php
/*
Plugin Name: MailChimp for WP Lite
Plugin URI: http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/
Description: Complete MailChimp integration for WordPress. Sign-up forms, widgets, comment checkboxes, etc..
Version: 0.8.2
Author: Danny van Kooten
Author URI: http://dannyvanKooten.com
License: GPL v3

MailChimp for WordPress
Copyright (C) 2012-2013, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// frontend AND backend
require_once 'includes/class-mc4wp-lite.php';
require_once 'includes/functions.php';

$MC4WP = MC4WP_Lite::get_instance();

if(is_admin()) {
	// frontend only
	require_once 'includes/class-mc4wp-lite-admin.php';
	$MC4WP_Admin = new MC4WP_Lite_Admin($MC4WP);
}
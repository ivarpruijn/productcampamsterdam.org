=== Plugin Name ===
Contributors: DvanKooten
Donate link: http://dannyvankooten.com/donate/
Tags: mailchimp, newsletter, mailinglist, email, email list, form, widget form, sign-up form, subscribe form, comments, comment form, mailchimp widget, buddypress, multisite
Requires at least: 3.1
Tested up to: 3.6
Stable tag: 0.8.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Thé ultimate MailChimp plugin! Includes a form shortcode, form widget and a comment form checkbox to grow your MailChimp list(s).

== Description ==

= MailChimp for WordPress Lite =

This plugin provides you with various ways to grow your MailChimp list(s). Add a form to your posts or pages by using the `[mc4wp-form]` shortcode, use this shortcode in your text widgets to show a form in your widget areas or add a "Sign-me up to our newsletter" checkbox to your comment or registration forms. 

Configuring is easy, all you need is your MailChimp API key!

**Features:**

* Embed sign-up forms to your pages or posts by using a simple shortcode `[mc4wp-form]`
* Add a MailChimp sign-up form to your widget areas by using the shortcode in a text widget
* Adds a "sign-up to our newsletter" checkbox to your comment form or registration form
* Sign-up requests from bots will be ignored (honeypot, Akismet, default spam protection).
* Includes a simple way to design forms, add as many fields as you like.
* Uses the MailChimp API, blazingly fast and reliable.
* Configuring is extremely easy, all you need is your MailChimp API key.
* The checkbox is compatible with BuddyPress and MultiSite registration forms.
* Compatible with Contact Form 7, use `[mc4wp_checkbox]` inside your CF7 forms.
* Add the checkbox to ANY form you like and this plugin will take care of the rest.

**More info:**

* [MailChimp for WordPress](http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/)
* Check out more [WordPress plugins](http://dannyvankooten.com/wordpress-plugins/) by Danny van Kooten
* You should follow [Danny on Twitter](http://twitter.com/DannyvanKooten) for lightning fast support and updates.

**MailChimp Sign-Up Form**
The plugin comes packed with an easy to way to build a form just like you want it. You have the possibility to add as many fields as you like and customize labels, placeholders, initial values etc..

Use the `[mc4wp-form]` shortcode to use this form in your posts, pages or text widgets.

**Sign-Up Checkbox**
Commenters and subscribers are valuable visitors who are most likely interested to be on your mailinglist. This plugin makes it easy for them, all they have to do is check a single checkbox when commenting or registering on your website!

You can add this checkbox to ANY form you like, including Contact Form 7 forms. This plugin will then take care of subscribing the person who submitted the form.

== Installation ==

1. In your WordPress admin panel, go to Plugins > New Plugin, search for "MailChimp for WP" and click "Install now"
1. Alternatively, download the plugin and upload the contents of mailchimp-for-wp.zip to your plugins directory, which usually is `/wp-content/plugins/`.
1. Activate the plugin
1. Fill in your MailChimp API key in the plugin's options.
1. Select at least one list to subscribe visitors to.
1. (Optional) Select where the checkbox should show up.
1. (Optional) Design a form and include it in your posts, pages or text widgets.

== Frequently Asked Questions ==

= What does this plugin do? =
This plugin gives you the possibility to easily create a sign-up form and show this form in various places on your website. Also, this plugin can add a checkbox to your comment form that makes it easy for commenters to subscribe to your MailChimp newsletter. All they have to do is check one checkbox and they will be added to your mailinglist(s).

For a complete list of plugin features, take a look here: [MailChimp for WordPress](http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/).

= Why does the checkbox not show up at my comment form? =
Your theme probably does not support the necessary comment hook this plugin uses to add the checkbox to your comment form. You can manually place the checkbox by placing the following code snippet inside the form tags of your theme's comment form.
 `<?php if(function_exists('mc4wp_show_checkbox')) { mc4wp_show_checkbox(); }?>`
 Your theme folder can be found by browsing to `/wp-content/themes/your-theme-name/`.

= Where can I find my MailChimp API key? =
[http://kb.mailchimp.com/article/where-can-i-find-my-api-key](http://kb.mailchimp.com/article/where-can-i-find-my-api-key)

= How can I style the sign-up form? =
You can use the following CSS selectors to style the sign-up form to your likings. Just add your CSS rules to your theme's stylesheet, usually found in `/wp-content/themes/your-theme-name/style.css`.

`
form.mc4wp-form{ ... } /* the form element */
form.mc4wp-form p { ... } /* form paragraphs */
form.mc4wp-form label { ... } /* labels */
form.mc4wp-form input { ... } /* input fields */
form.mc4wp-form input[type=submit] { ... } /* submit button */
form.mc4wp-form p.alert { ... } /* success & error messages */
form.mc4wp-form p.success { ... } /* success message */
form.mc4wp-form p.error { ... } /* error messages */
` 

= The shortcode [mc4wp-form] is not working. Why? =
Make sure to go to **form settings** in the plugin settings screen. There you have to check a checkbox that says "load form functionality". This will make the plugin load the necessary code.

= Can I add a checkbox to this form by plugin ...? =
Yes, you can. Go to checkbox and tick the checkbox that says "show checkbox at other forms (manual)". Then, include ANY field with name attribute `mc4wp-do-subscribe` and the plugin will take care of the rest. 

Example: 
`<input type="checkbox" name="mc4wp-do-subscribe" value="1" id="mc4wp-checkbox" /><label for="mc4wp-checkbox">Subscribe to our newsletter</label>`.

Make sure your form contains an email field with any of the following names: 
`email, e-mail, emailaddress, user_email, your-email, your_email, signup_email, emailadres` 

Note: when using Contact Form 7 you can use "[mc4wp_checkbox]" inside your CF7 form template.

= How do I add subscribers to certain interest groups? =
The following snippet should get you started.

`<input type="hidden" name="GROUPINGS[0][id]" value="INSERT GROUPING ID HERE" />
<input type="hidden" name="GROUPINGS[0][groups]" value="INSERT GROUPING GROUP ID'S HERE (SEPARATED BY COMMA)" />`

Alternatively, update to [MailChimp for WP Pro](#coming-soon) where you can manage interest groups from the settings screen.

== Screenshots ==

1. The MC4WP options page.
1. The MC4WP form options page.

== Upgrade Notice ==
The CSS classes of the MC4WP form have been changed. Upgrade any custom CSS classes you might have in your theme's stylesheet. <em>(prefix <strong>mc4wp-</strong>)</em>

== Changelog ==

= 0.8.2 =
* Improved: Namespaced form CSS classes
* Improved: Improved error messages
* Improved: It is now easier to add fields to your form mark-up by using the wizard. You can choose presets etc.
* Improved: All field names that are of importance for MailChimp should now be uppercased (backwards compatibility is included)
* Improved: Fields named added through the wizard are now validated and sanitized
* Improved: Added caching to the backend which makes it way faster
* Improved: Various usability improvements

= 0.8.1 =
* Fixed: typo in form success message
* Improved: various little improvements
* Added: option to hide the form after a successful sign-up

= 0.8 =
* Changed links to show your appreciation for this plugin.
* Changed function name, `mc4wp_checkbox()` is now `mc4wp_show_checkbox()` (!!!)
* Improved: CSS reset now works for registration forms as well.
* Improved: Code, removed unnecessary code, only load classes when not existing yet, etc.
* Improved: hooked into user_register to allow third-party registration form plugins.
* Added: Shortcode for usage inside Contact Form 7 form templates `[mc4wp_checkbox]`
* Added: Catch-all, hook into ANY form using ANY input field with name attribute `mc4wp-do-subscribe` and value `1`.
* Fixed: Subscribe from Multisite sign-up
* Fixed: 404 page when no e-mail given.


= 0.7 =
* Improved: small backend JavaScript improvements / fixes
* Improved: configuration tabs on options page now work with JavaScript disabled as well
* Added: form and checkbox can now subscribe to different lists
* Added: Error messages for WP Administrators (for debugging)
* Added: `mc4wp_show_checkbox()` function to manually add the checkbox to a comment form.

= 0.6.2 =
* Fixed: Double quotes now enabled in text labels and success / error messages (which enables the use of JavaScript)
* Fixed: Sign-up form failing silently without showing error.

= 0.6.1 =
* Fixed: error notices
* Added: some default CSS for success and error notices
* Added: notice when form mark-up does not contain email field

= 0.6 =
* Fixed: cannot redeclare class MCAPI
* Fixed: scroll to form element
* Added: notice when copying the form mark-up instead of using `[mc4wp-form]`
* Added: CSS classes to form success and error message(s).
* Removed: Static element ID on form success and error message(s) for W3C validity when more than one form on 1 page.

= 0.5 =
* Fixed W3C invalid value "true" for attribute "required"
* Added scroll to form element after form submit.
* Added option to redirect visitors after they subscribed using the sign-up form.

= 0.4.1 =
* Fixed correct and more specific error messages
* Fixed form designer, hidden fields no longer wrapped in paragraph tags
* Added text fields to form designer
* Added error message when email address was already on the list
* Added debug message when there is a problem with one of the (required) merge fields

= 0.4 =
* Improved dashboard, it now has different tabs for the different settings.
* Improved guessing of first and last name.
* Fixed debugging statements on settings page
* Added settings link on plugins overview page
* Added form functionality
* Added form shortcode
* Added necessary filters for shortcodes to work inside text widgets
* Added spam honeypot to form to ignore bot sign-ups
* Added error & success messages to form
* Added Freddy icon to menu

= 0.3 =
* Fixed the missing argument bug when submitting a comment for some users.
* Added support for regular, BuddyPress and MultiSite registration forms.

= 0.2 =
* Fixed small bug where name of comment author was not correctly assigned
* Improved CSS reset for checkbox

= 0.1 =
* BETA release
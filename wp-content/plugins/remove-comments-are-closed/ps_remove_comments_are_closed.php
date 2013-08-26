<?php
/*
Plugin Name: Remove "Comments are closed"
Plugin URI: http://soderlind.no/archives/2012/01/11/wordpress-plugin-remove-comments-are-closed/
Description: On posts where comments are closed, the plugin will remove the text 'Comments are closed.' The plugin supports any languages/text domains, and will remove the text from themes and plugins.
Author: Per Soderlind
Version: 1.2
Author URI: http://soderlind.no/
*/
 
add_filter('gettext', 'ps_remove_comments_are_closed', 20, 3);
 
function ps_remove_comments_are_closed($translated_text, $untranslated_text, $domain) {
    if ( $untranslated_text == 'Comments are closed.' ) {
        return '';
    }
    return $translated_text;
}
?>
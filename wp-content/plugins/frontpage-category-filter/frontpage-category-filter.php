<?php 
/*
 Plugin Name: Frontpage category filter
 Plugin URI: http://wppluginspool.com/wpplugins/wp-filter-post-categories/
 Description: This plugin allows you to choose which post categories youe site will show on the homepage. Just go to settings and deselect the categories that you want to hide.
 Version: 1.0.2
 Author: Cristian Merli
 Author URI: http://wppluginspool.com
 */

/*
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; version 2 of the License.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


class MerlicFilterCategoryLite {

	/**
	 * Set show all categories by default
	 */
	public function init() {
		$all_categories = get_categories('hide_empty=0');
		
		if (count($all_categories) > 0) {
			foreach ($all_categories as $category) {
				$cat_ID[] = $category->cat_ID;
			}
			add_option('merlic_filtercategory_allowed', implode(',', $cat_ID));
		}
		
	}
	
	/**
	 * Filter categories from homepage, showing only posts that belong to selected categories
	 * @param object $query
	 * @return object $query
	 */
	public function filter( $query ) {
		$featured_category_id = get_option('merlic_filtercategory_allowed', true);
		
	 	if ( $query->is_home AND $query->get('post_type') != 'nav_menu_item' ) {
	        $query->set('category__in', explode(',', $featured_category_id));
	        //$query->set('posts_per_page', get_option('merlic_filtercategory_postslimit'));
	    }
		
	    return $query;		
  }
	
	/**
	 * Callback function for admin_menu action
	 */
	public function settings_menu() {
		add_options_page("Frontpage category filter", "Frontpage category filter", 'manage_options', 'merlic_filtercategory_admin', array('MerlicFilterCategoryLite', 'draw_settings'));
	}
	
	/**
	 * Draws the settings page and manages the stored options
	 */
	public function draw_settings() {
	
		$all_categories = get_categories('hide_empty=0');
		
		$homepage = get_page(get_option('page_on_front'));
		
		//check if the form has been submitted
		if ($_POST['merlic_filtercategory_save']) {
		
			//save page meta data here
			if (count($_POST['merlic_filtercategory_allowed']) > 0) update_option('merlic_filtercategory_allowed', implode(',', $_POST['merlic_filtercategory_allowed']));
			else
				delete_option('merlic_filtercategory_allowed');
				
			//save page meta data here
			if (count($_POST['shortcode']) > 0) {
				$shortcode = '[wp_filter_posts cat="'.implode(',', $_POST['shortcode']).'"'.(is_int($_POST['posts_limit']) ? ' limit="'.$_POST['posts_limit'].'"' : '').' title_style="'.$_POST['title_style'].'"]';
			}
			else
				$shortcode = '';
				
			update_option('merlic_filtercategory_show_as', $_POST['merlic_filtercategory_show_as']);
			
			$save_message = __('Changes have been saved');
			
		}
		
		//display the form
		$output = '
			<div class="wrap">
				<h2>'.__('Frontpage category filter settings').'</h2>
				<p>Uncheck the categories that you want to hide from your posts page</p>
		';
		
		$output .= '
			<form method="POST" accept-charset="utf-8" target="_self" action="'.$_SERVER['REQUEST_URI'].'">
				<table class="form-table">
					<tr valign="top"><th scope="row"><label><b>'.__('Page').'</b></label></th><td><b>'.__('Categories').'</b></td></tr>
					<tr valign="top">
	                    <th scope="row"><label>'.__('Default Post Page').'</label></th>
						<td>'.self::draw_categories($all_categories).'</td>
					</tr>
					<tr valign="top">
	                    <th scope="row"><label>'.__('Other pages').'</label></th>
						<td>To show posts from certain categories on other pages you need to upgrade to the <a href="http://wppluginspool.com/wp-filter-post-categories/">full version</a>.</td>
					</tr>
		';
		
		$output .= '<tr><td>&nbsp;</td><td><i>'.$save_message.'</i></td></tr>'."\n";

		$output .= '<tr><td>&nbsp;</td><td><input class="button-primary" type="submit" name="merlic_filtercategory_save" value="'.__('Save Changes').'" /></td></tr>'."\n";
		$output .= '</table>'."\n";
		$output .= '</form>'."\n";
		
		$output .= '<br /><h4>More plugins from the same author</h4>';
		$output .= 'Please visit <a href="http://wppluginspool.com">Wordpress Plugins Store</a> for more plugins.';
		$output .= '<br/><h4>Free Ebooks offered</h4>';
		$output .= 'Please visit <a href="http://thedollarebook.com">The Dollar Ebook</a> for free ebooks from the author.';
		$output .= '
			</div>
		';
		
		echo $output;
	}
	
	/**
	 *
	 * @param object $page The page object
	 * @param array $categories The category objects
	 * @return string A list of checkboxes, one for each category
	 */
	private function draw_categories( $categories ) {
		if (count($categories) > 0) {
		
			foreach ($categories as $category) {
				//get the allowed categories for this page that have been previously saved
				$allowed_categories = get_option('merlic_filtercategory_allowed', true);
				$allowed_categories_array = explode(',', $allowed_categories);
				
				if (in_array($category->cat_ID, $allowed_categories_array)) $checked = 'checked = "checked"';
				else
					$checked = '';
					
				//draw the checkbox
				$checkboxes .= '<input type="checkbox" name="merlic_filtercategory_allowed[]" value="'.$category->cat_ID.'" '.$checked.'> '.$category->name.'<br/>';
			}
		}
		return $checkboxes;
	}
	
	private function println( $text ) {
		if (is_array($text) or is_object($text)) {
			echo '<pre>';
			print_r($text);
			echo '</pre>';
		}
		else {
			echo '<pre>';
			echo $text;
			echo '</pre>';
		}
		
		echo '<br />'."\n";
	}
	
	
}


add_action('pre_get_posts', array('MerlicFilterCategoryLite', 'filter'), 1);
add_action('admin_menu', array('MerlicFilterCategoryLite', 'settings_menu'));
add_action('init', array('MerlicFilterCategoryLite', 'init'));

?>

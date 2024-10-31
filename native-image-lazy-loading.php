<?php
/**
 * Plugin Name: Native Image Lazy Loading
 * Plugin URI:	https://github.com/jdmdigital/JDM-Native-Lazy-Loading
 * Description: Automatically add the new `loading` attribute to images within your content to support native image lazy loading.
 * Version:     1.1
 * Author:      JDM Digital
 * Author URI:  https://jdmdigital.co
 * License:     GPL2
 */

// If this file is called directly, abandon ship.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'NATIVELAZYLOADING_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'NATIVELAZYLOADING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* == Build Settings Page and Declare Options == 
 * @since v1.1
 */
require_once(NATIVELAZYLOADING_PLUGIN_PATH . 'settings.php');



/* == Pretty Much the Whole Plugin Here == */


// Same (mostly) signature as str_replace(), but only replaces the FIRST match
// @since v1.1
if(!function_exists('str_replace_first')) {
	function str_replace_first($search, $replace, $subject) {
		$search = '/'.preg_quote($search, '/').'/';
		return preg_replace($search, $replace, $subject, 1);
	}
}


if(!function_exists('jdm_native_lazy_loading')) {
	add_filter('the_content','jdm_native_lazy_loading');
	
	function jdm_native_lazy_loading($content) {
		$firstImg = esc_attr(get_option('jdmnll_1stimg'));
		$nthimg = esc_attr(get_option('jdmnll_nthimg'));
		
		if($firstImg == $nthimg) {
			// Replace all matches with the loading setting
			$content = str_replace('<img','<img loading="'.$nthimg.'"', $content);
		} else {
			// Replace all matches with the nth image loading setting
			$content = str_replace('<img','<img loading="'.$nthimg.'"', $content);
			// Then, Replace just the FIRST image match with the firstImg setting
			$content = str_replace_first('<img','<img loading="'.$firstImg.'"', $content);	
		}
		
		return $content;
	}
	
}


// Add Settings link under plugin description on WP Plugins Page
function jdmnll_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .admin_url( 'options-general.php?page=jdm-native-lazy-loading' ) .'">' . __('Settings') . '</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'jdmnll_add_plugin_page_settings_link');
 
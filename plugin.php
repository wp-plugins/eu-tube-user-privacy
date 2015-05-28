<?php 
/*
 * Plugin Name: EU Tube User Privacy
 * Description: Changes embedded YouTube videos to use the enhanced privacy settings.
 * Author: ema-digital
 * Text Domain: ema-youtube-nocookie
 * Version: 0.2.0
 *
 * @package   EUTubeUserPrivacy
 * @version   0.2.0
 */
if( class_exists('EMAYoutubeNocookie') === false ) {
	require_once(dirname(__FILE__) . '/lib/EMAYoutubeNocookie.php');

	/**
	 * Adds hooks to WP page. Called by the init function
	 */ 
	function ema_youtube_nocookie_hooks() {
		add_filter('the_content', 'EMAYoutubeNocookie::changeDomain');
		add_filter('widget_text', 'EMAYoutubeNocookie::changeDomain');
	
		// add compatibility for the advanced custom fields plugin
		if( function_exists('get_field') ) {
			add_filter('acf/format_value', 'EMAYoutubeNocookie::changeDomain', 10, 3);
			add_filter('acf/load_value', 'EMAYoutubeNocookie::changeDomain', 10, 3);
		}
	}
	
	/**
	 * Loads the stuff and calls the hooks function
	 */
	function ema_youtube_nocookie_init() {
		global $polylang;
	
		if( isset( $polylang ) && is_object( $polylang->model ) && function_exists( 'pll_current_language' ) ) {
			require_once(dirname(__FILE__) . '/lib/EMAYoutubeNocookieAdmin.php');
			$privacy = get_option( 'ema_youtube_register_locale' );
			$current_language = pll_current_language('locale');
			
			if( isset( $privacy['locale'] ) && array_key_exists( strval( $current_language ), $privacy['locale'] ) ) {
				ema_youtube_nocookie_hooks();
			}
		}
		else {
			ema_youtube_nocookie_hooks();
		}
	}
	add_action( 'plugins_loaded', 'ema_youtube_nocookie_init' );
}

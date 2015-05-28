<?php
/**
 * Adds and admin panel with a form. Conditionally loaded in the
 * plugin.php file if polylang is installed on the site.
 */
class EMAYoutubeNocookieAdmin {
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;

		/**
		 * Start up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}

		/**
		 * Add options page
		 */
		public function add_plugin_page() {
			// This page will be under "Settings"
			add_options_page(
				'EU Tube User Privacy', // page title
				'EU Tube User Privacy', // menu title
				'manage_options', 
				'ema-youtube-nocookie-setting-admin', // query string var for page
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Options page callback
		 */
		public function create_admin_page() {
			// Set class property
			$this->options = get_option( 'ema_youtube_register_locale' );
			?>
			<div class="wrap">
				<h2><?php _e( 'EU Tube User Privacy', 'ema-youtube-nocookie' ); ?></h2>
				<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'ema_youtube_settings_group' );   
					do_settings_sections( 'ema-youtube-nocookie-setting-admin' );
					submit_button(); 
				?>
				</form>
			</div>
			<?php
		}

		/**
		 * Register and add settings
		 */
		public function page_init() {   
			register_setting(
				'ema_youtube_settings_group', // Option group
				'ema_youtube_register_locale', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
			
			add_settings_section(
				'ema_youtube_settings_section', // ID
				'YouTube No-cookie Settings', // Title
				array( $this, 'print_section_info' ), // Callback
				'ema-youtube-nocookie-setting-admin' // Page
			);  

			add_settings_field(
				'locale', // ID
				'Use youtube-nocookie.com for the following locales', // Title 
				array( $this, 'locale_callback' ), // Callback
				'ema-youtube-nocookie-setting-admin', // Page
				'ema_youtube_settings_section' // Section           
			);      
			
			wp_register_style( 'ema-youtube-nocookie', plugins_url( 'css/style.css', dirname( __FILE__ ) ), array(), '0.2.0' );
			wp_enqueue_style( 'ema-youtube-nocookie' );
		}

		/**
		 * Sanitize each setting field as needed
		 *
		 * @param array $input Contains all settings fields as array keys
		 */
		public function sanitize( $input ) {
				$new_input = array();
				if ( isset( $input['locale'] ) ){
					$new_input['locale'] = $input['locale'];
					foreach( $input['locale'] as $key => $val ) {
						$key = esc_html( $key );
						$new_input['locale'][$key] = esc_html( $input['locale'][$key] );
					}
				}
				
				return $new_input;
		}

		/** 
		 * Print the Section text
		 */
		public function print_section_info() {
			_e( 'Enter your settings below:', 'ema-youtube-nocookie' );
		}

		/** 
		 * Get the settings option array and print one of its values
		 */
		public function locale_callback() {
			global $polylang;
			echo '<div class="ema-youtube-nocookie-locales">';
			foreach( $polylang->model->get_languages_list() as $key => $val ) {
				printf(
					'<p><input type="checkbox" id="locale-%1$s" name="ema_youtube_register_locale[locale][%1$s]" value="1" %4$s /><label for="locale-%1$s">%3$s (%1$s)<span class="ema-youtube-nocookie-flag">%2$s</span></label></p>',
					esc_html( $val->locale ),
					$val->flag,
					esc_html( $val->name ),
					isset( $this->options['locale'][$val->locale] ) ? checked( 1, esc_html( $this->options['locale'][$val->locale] ), false ) : ''
				);
			}
			echo '</div>';
		}
}

if( is_admin() ) {
	$ema_youtube_nocookie_admin = new EMAYoutubeNocookieAdmin();
}
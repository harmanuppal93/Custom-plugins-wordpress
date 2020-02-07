<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Acf7db_reply_back
 * @subpackage Acf7db_reply_back/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Acf7db_reply_back
 * @subpackage Acf7db_reply_back/includes
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Acf7db_reply_back_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'acf7db_reply_back',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

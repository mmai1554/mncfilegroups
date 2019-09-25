<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://mainetcare.de
 * @since      1.0.0
 *
 * @package    Mncfilegroups
 * @subpackage Mncfilegroups/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mncfilegroups
 * @subpackage Mncfilegroups/includes
 * @author     MaiNetCare GmbH <info@mainetcare.com>
 */
class Mncfilegroups_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mncfilegroups',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

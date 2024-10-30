<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Message_Notification_For_Contact_Form_7
 * @subpackage Message_Notification_For_Contact_Form_7/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Message_Notification_For_Contact_Form_7
 * @subpackage Message_Notification_For_Contact_Form_7/includes
 * @author     whatso <hi@whatso.net>
 */
class Message_Notification_For_Contact_Form_7_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'message-notification-for-contact-form-7',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

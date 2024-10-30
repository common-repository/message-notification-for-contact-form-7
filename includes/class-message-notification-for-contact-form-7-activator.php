<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Message_Notification_For_Contact_Form_7
 * @subpackage Message_Notification_For_Contact_Form_7/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Message_Notification_For_Contact_Form_7
 * @subpackage Message_Notification_For_Contact_Form_7/includes
 * @author     whatso <hi@whatso.net>
 */
class Message_Notification_For_Contact_Form_7_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		//whatso order notification
		global $wpdb;
		$table_prefix = $wpdb->prefix;
		$tblname1 = 'message_notification_details';
		$wp_order_table = $table_prefix . "$tblname1";
		$charset_collate = $wpdb->get_charset_collate();

		$db_result1 = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wp_order_table));
		if (strtolower($db_result1) !== strtolower($wp_order_table)) {

			$tbl1 = "CREATE TABLE $wp_order_table (
			`id`              		BIGINT(20) NOT NULL auto_increment,
			`user_type`       		VARCHAR(50) NULL DEFAULT NULL,
			`create_date_time`  	DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`message_api_request`	LONGTEXT NULL DEFAULT NULL,
			`message_api_response`  LONGTEXT NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
			)$charset_collate;";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($tbl1);
		}
	}
}

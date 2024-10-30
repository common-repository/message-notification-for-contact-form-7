<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Message_Notification_For_Contact_Form_7
 * @subpackage Message_Notification_For_Contact_Form_7/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Message_Notification_For_Contact_Form_7
 * @subpackage Message_Notification_For_Contact_Form_7/includes
 * @author     whatso <hi@whatso.net>
 */
class Message_Notification_For_Contact_Form_7
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Message_Notification_For_Contact_Form_7_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('MESSAGE_NOTIFICATION_FOR_CONTACT_FORM_7_VERSION')) {
			$this->version = MESSAGE_NOTIFICATION_FOR_CONTACT_FORM_7_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'message-notification-for-contact-form-7';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Message_Notification_For_Contact_Form_7_Loader. Orchestrates the hooks of the plugin.
	 * - Message_Notification_For_Contact_Form_7_i18n. Defines internationalization functionality.
	 * - Message_Notification_For_Contact_Form_7_Admin. Defines all hooks for the admin area.
	 * - Message_Notification_For_Contact_Form_7_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-message-notification-for-contact-form-7-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-message-notification-for-contact-form-7-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-message-notification-for-contact-form-7-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		
		$this->loader = new Message_Notification_For_Contact_Form_7_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Message_Notification_For_Contact_Form_7_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Message_Notification_For_Contact_Form_7_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Message_Notification_For_Contact_Form_7_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		//add action for menu
		$this->loader->add_action('admin_menu', $plugin_admin, 'Add_menu');
		//add action for user credentials
		$this->loader->add_action('whatso_user_credentials', $plugin_admin, 'whatso_get_user_credentials');
		add_filter('admin_footer_text', array($this, 'updatefooteradmin'));

		
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Message_Notification_For_Contact_Form_7_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}


	//to get user data
	public static function whatso_get_user_credentials($whatso_emailid)
	{
		$data_decoded = array("emailId" => $whatso_emailid, "forWhichFunctionality" => "api");

		$data = json_encode($data_decoded);

		$url = esc_url("https://webapi.whatso.net/api/UnAuthorized/get-api-credentials");

		$response = wp_remote_post($url, array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json; charset=utf-8', 'WPRequest' => 'abach34h4h2h11h3h'
			),
			'body' => $data
		));
		if (is_array($response) and isset($response['body'])) {

			$response_obj = json_decode($response['body']);
			if (is_object($response_obj) && isset($response_obj->message) && $response_obj->message === 'Success') {
				return $response_obj->message;
			} else {
				return false;
			}
		}
	}

	
	public function updatefooteradmin($default)
	{
		global $pagenow;

		$setting_pages = array(
			'message-notification-wpcf7'

		);


		$post_type = filter_input(INPUT_GET, 'post_type');
		if (!$post_type) {
			$post_type = get_post_type(filter_input(INPUT_GET, 'post'));
		}

		if ('admin.php' === $pagenow && isset($_GET['page']) && in_array(sanitize_text_field($_GET['page']), $setting_pages)) {
			echo ' ' . esc_attr('by') . ' <a href="https://www.whatso.net/" target="_blank">Whatso </a> Message Notification via Contact Form 7 V 1.0';
		}
	}

	
}
function wpcf7_before_send_mail_function( $contact_form, $abort, $submission ) {
	$store_name = get_bloginfo('name');
	global $wpdb;
	$detail_table = $wpdb->prefix . 'message_notification_details';

    $data = (array) $submission->get_posted_data();

	if ( isset( $data['your-name'] ) ) {
		$firstname=$submission->get_posted_data('your-name');
	}
	else{
		$firstname=" ";
	}

	if ( isset( $data['your-email'] ) ) {
	$your_email = $submission->get_posted_data('your-email');
	}
	else{
		$your_email=" ";
	}
	if ( isset( $data['your-subject'] ) ) {
	$your_subject = $submission->get_posted_data('your-subject');
	}
	else{
		$your_subject=" ";
	}
	if ( isset( $data['your-message'] ) ) {
	$your_message = $submission->get_posted_data('your-message');
	}
	else{
		$your_message=" ";
	}
	if ( isset( $data['telephone'] ) ) {
	$telephone = $submission->get_posted_data('telephone');
	}
	else{
		$telephone=" ";
	}

	// do something       
	if (empty(get_option('test1')) || !empty(get_option('test1'))) {
		$update_notifications_arr = array(
			'firstname'=>$firstname,
			'your_email'   =>  $your_email,
			'your_subject'   =>  $your_subject,
			'your_message'   =>  $your_message,
			'telephone'   =>  $telephone,
		);
		$result = update_option('test1', wp_json_encode($update_notifications_arr));
	}

	if (!empty(get_option('admin_settings'))) {
		$data = get_option('admin_settings');
		$data = json_decode($data);
		$whatso_username = $data->whatso_username;
		$whatso_password = $data->whatso_password;
		$admin_mobileno = $data->admin_mobileno;
		$admin_message = $data->admin_message;
		$enable_notification = $data->enable_notification;
		$from_number = $data->from_number;

		if ($enable_notification === "1") {
			
			$admin_message = str_replace('{storename}', $store_name, $admin_message);
			$admin_message = str_replace('{customersubject}', $your_subject, $admin_message);
			$admin_message = str_replace('{customermessage}', $your_message, $admin_message);		
			$admin_message = str_replace('{customeremail}', $your_email, $admin_message);
			$admin_message = str_replace('{customernumber}', $telephone, $admin_message);
			$admin_message = preg_replace("/\r\n/", "<br>", $admin_message);


			$data_decoded = array(
				"Username" => $whatso_username, "Password" => $whatso_password, "MessageText" => $admin_message, "MobileNumbers" => $admin_mobileno, "ScheduleDate" => '', "FromNumber" => $from_number,
				"Channel" => '1'
			);

			$data = json_encode($data_decoded);

			$url = esc_url("https://api.whatso.net/api/v2/SendMessage");

			$response = wp_remote_post($url, array(
				'method' => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8', 'WPRequest' => 'abach34h4h2h11h3h'
				),
				'body' => $data
			));
			if (is_array($response) and isset($response['body'])) {

				$response_obj = json_decode($response['body']);
				if (is_object($response_obj)) {
					//code to update whatso_order_notification_details
					$insert_array = array(
						'user_type' => 'admin',
						'message_api_request' => $data,
						'message_api_response' =>  wp_json_encode($response_obj),
					);

					$wpdb->insert($detail_table, $insert_array);
				}
			}
		}
	}
}

add_filter( 'wpcf7_before_send_mail', 'wpcf7_before_send_mail_function', 10, 3 );
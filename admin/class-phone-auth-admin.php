<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Phone_Auth
 * @subpackage Phone_Auth/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Phone_Auth
 * @subpackage Phone_Auth/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Phone_Auth_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phone_Auth_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phone_Auth_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/phone-auth-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phone_Auth_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phone_Auth_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/phone-auth-admin.js', array( 'jquery' ), $this->version, false );

	}

	function phone_auth_menu(){
		add_options_page( "Phone Auth", "Phone Auth", "manage_options", "phone-auth", [$this, "menuapage_cb"], null );
		// options
		add_settings_section( 'phone_auth_option_section', '', '', 'phone_auth_option_page' );

		// Firebase apiKey
		add_settings_field( 'firebase_apiKey', 'Firebase apiKey', [$this,'fn_firebase_apiKey'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_apiKey');

		// Firebase authDomain
		add_settings_field( 'firebase_authDomain', 'Firebase authDomain', [$this,'fn_firebase_authDomain'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_authDomain');

		// Firebase projectId
		add_settings_field( 'firebase_projectId', 'Firebase projectId', [$this,'fn_firebase_projectId'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_projectId');

		// Firebase storageBucket
		add_settings_field( 'firebase_storageBucket', 'Firebase storageBucket', [$this,'fn_firebase_storageBucket'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_storageBucket');

		// Firebase messagingSenderId
		add_settings_field( 'firebase_messagingSenderId', 'Firebase messagingSenderId', [$this,'fn_firebase_messagingSenderId'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_messagingSenderId');

		// Firebase appId
		add_settings_field( 'firebase_appId', 'Firebase appId', [$this,'fn_firebase_appId'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_appId');

		// Firebase measurementId
		add_settings_field( 'firebase_measurementId', 'Firebase measurementId', [$this,'fn_firebase_measurementId'], 'phone_auth_option_page', 'phone_auth_option_section');
		register_setting( 'phone_auth_option_section', 'firebase_measurementId');
	}

	function fn_firebase_apiKey(){
		echo '<input type="text" name="firebase_apiKey" value="'.get_option("firebase_apiKey").'">';
	}
	function fn_firebase_authDomain(){
		echo '<input type="text" name="firebase_authDomain" value="'.get_option("firebase_authDomain").'">';
	}
	function fn_firebase_projectId(){
		echo '<input type="text" name="firebase_projectId" value="'.get_option("firebase_projectId").'">';
	}
	function fn_firebase_storageBucket(){
		echo '<input type="text" name="firebase_storageBucket" value="'.get_option("firebase_storageBucket").'">';
	}
	function fn_firebase_messagingSenderId(){
		echo '<input type="text" name="firebase_messagingSenderId" value="'.get_option("firebase_messagingSenderId").'">';
	}
	function fn_firebase_appId(){
		echo '<input type="text" name="firebase_appId" value="'.get_option("firebase_appId").'">';
	}
	function fn_firebase_measurementId(){
		echo '<input type="text" name="firebase_measurementId" value="'.get_option("firebase_measurementId").'">';
	}

	function menuapage_cb(){
		?>
		<div id="phone_auth">
			<h3>Settings</h3>
			<hr>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'phone_auth_option_section' );
				do_settings_sections( 'phone_auth_option_page' );
				?>
				<p>
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
			</form>
		</div>
		<?php
	}

}

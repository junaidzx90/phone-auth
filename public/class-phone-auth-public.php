<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Phone_Auth
 * @subpackage Phone_Auth/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Phone_Auth
 * @subpackage Phone_Auth/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Phone_Auth_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'phone_auth', [$this, 'phone_auth'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		global $post;
		if ( stripos($post->post_content, 'phone_auth') ){
			wp_enqueue_style( 'materialize', plugin_dir_url( __FILE__ ) . 'css/materialize.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'animate', plugin_dir_url( __FILE__ ) . 'css/animate.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'phone-auth', plugin_dir_url( __FILE__ ) . 'css/phone-auth-public.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		global $post;
		if ( stripos($post->post_content, 'phone_auth') ){
			wp_enqueue_script( 'firebase', plugin_dir_url( __FILE__ ) . 'js/firebase.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( "phone-auth", plugin_dir_url( __FILE__ ) . 'js/phone-auth-public.js', array( 'jquery', 'firebase' ), $this->version, true );

			wp_localize_script( 'phone-auth', 'authajax', array(
				"ajaxurl" => admin_url("admin-ajax.php"),
				"nonce" => wp_create_nonce( "phone_nonce" ),
				"config" => [
					"apiKey" => get_option("firebase_apiKey"),
					"authDomain" => get_option("firebase_authDomain"),
					"projectId" => get_option("firebase_projectId"),
					"storageBucket" => get_option("firebase_storageBucket"),
					"messagingSenderId" => get_option("firebase_messagingSenderId"),
					"appId" => get_option("firebase_appId"),
					"measurementId" => get_option("firebase_measurementId")
				]
			) );
		}
	}

	function phone_auth(){
		ob_start();

		if(isset($_GET['action']) && $_GET['action'] === 'signup'){
			require_once plugin_dir_path( __FILE__ )."partials/phone-auth-register.php";
		}else{
			require_once plugin_dir_path( __FILE__ )."partials/phone-auth-login.php";
		}
		
		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}

	function check_user_existing(){
		if(!wp_verify_nonce( $_GET["nonce"], "phone_nonce" )){
			die("Invalid Request");
		}

		if(isset($_GET['number'])){
			$number = intval($_GET['number']);
			$number = str_replace("88", "", $number);
			$exist = get_user_by( 'login', $number );

			if($exist){
				echo json_encode(array("error" => "ফোন নম্বরটি ইতিমধ্যেই ব্যবহার করা হয়েছে."));
				die;
			}

			echo json_encode(array("success" => "Success"));
			die;
		}
	}

	function create_user(){
		if(!wp_verify_nonce( $_POST["nonce"], "phone_nonce" )){
			die("Invalid Request");
		}

		if(isset($_POST['data'])){
			$data = $_POST['data'];

			$number = intval($data['number']);
			$number = str_replace("88", "", $number);
			$user_role = sanitize_text_field($data['user_role']);
			$password = sanitize_text_field($data['password']);

			$exist = get_user_by( 'login', $number );

			if($exist){
				echo json_encode(array("error" => "ফোন নম্বরটি ইতিমধ্যেই ব্যবহার করা হয়েছে."));
				die;
			}else{
				$userdata = array(
					'user_login'           => $number, 
					'display_name'            => 'Temp user',
					'user_pass'            => $password,
					'show_admin_bar_front' => false,
					'role'	=> $user_role
				);
				  
				$user_id = wp_insert_user( $userdata );
				if($user_id){
					echo json_encode(array("success" => "Success"));
					die;
				}
			}
			
		}

		echo json_encode(array("error" => "Error! Try again"));
		die;
	}

	function login_user(){
		if(!wp_verify_nonce( $_POST["nonce"], "phone_nonce" )){
			die("Invalid Request");
		}

		if(isset($_POST['data'])){
			$data = $_POST['data'];

			$number = intval($data['number']);
			$number = str_replace("88", "", $number);
			$password = sanitize_text_field($data['password']);

			$args = array(
				'user_login'    => $number,
				'user_password' => $password,
				'remember'      => true
			);
		 
			$user = wp_signon( $args, false );
			
			if ( !is_wp_error( $user ) ) {
				if($user->roles[0] === 'driver'){
					echo json_encode(array("success" => home_url( '/trent' )));
					die;
				}elseif($user->roles[0] === 'client'){
					echo json_encode(array("success" => home_url( '/profile' )));
					die;
				}else{
					echo json_encode(array("success" => home_url(  )));
					die;
				}
			}else{
				echo json_encode(array("error" => $user->get_error_message()));
				die;
			}
		}
	}
}

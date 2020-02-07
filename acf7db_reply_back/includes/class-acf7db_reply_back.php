<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Acf7db_reply_back
 * @subpackage Acf7db_reply_back/includes
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
 * @package    Acf7db_reply_back
 * @subpackage Acf7db_reply_back/includes
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Acf7db_reply_back {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Acf7db_reply_back_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION_ACF7_DB_RB' ) ) {
			$this->version = PLUGIN_NAME_VERSION_ACF7_DB_RB;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'acf7db_reply_back';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		// $this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Acf7db_reply_back_Loader. Orchestrates the hooks of the plugin.
	 * - Acf7db_reply_back_i18n. Defines internationalization functionality.
	 * - Acf7db_reply_back_Admin. Defines all hooks for the admin area.
	 * - Acf7db_reply_back_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-acf7db_reply_back-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-acf7db_reply_back-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-acf7db_reply_back-admin.php';
		
		$this->loader = new Acf7db_reply_back_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Acf7db_reply_back_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Acf7db_reply_back_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Acf7db_reply_back_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		//Display reply back link here
		$this->loader->add_action('vsz_cf7_admin_after_body_field',$plugin_admin, 'vsz_cf7_rb_admin_after_body_edit_field_func', 30, 2);
		
		//Display edit entry form here
		$this->loader->add_action('vsz_cf7_after_admin_form', $plugin_admin,'vsz_cf7_rb_after_admin_edit_values_form_callback', 30);
		
		//Add additional table header here
		$this->loader->add_action('vsz_cf7_admin_after_heading_field',$plugin_admin, 'vsz_cf7_rb_admin_after_heading_field_callback', 30, 2);
		
		//Call Ajax for display entry related form information in edit form
		$this->loader->add_action('wp_ajax_acf7_db_rb_send_mail',$plugin_admin, 'acf7_db_rb_send_mail_callback');
		
		// Display error messsage
		$this->loader->add_action( 'admin_notices', $plugin_admin,'acf7_db_rb_plugin_display_message',0 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Acf7db_reply_back_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Acf7db_reply_back_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

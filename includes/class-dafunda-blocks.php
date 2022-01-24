<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/dafundacom/dafunda-blocks/
 * @since    0.0.1
 *
 * @package    dafunda_blocks
 * @subpackage dafunda_blocks/includes
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
 * @since    0.0.1
 * @package    dafunda_blocks
 * @subpackage dafunda_blocks/includes
 * @author     Imtiaz Rayhan <imtiazrayhan@gmail.com>
 */
class Dafunda_Blocks {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      Dafunda_Blocks_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dafunda_Blocks_Loader. Orchestrates the hooks of the plugin.
	 * - Dafunda_Blocks_i18n. Defines internationalization functionality.
	 * - Dafunda_Blocks_Admin. Defines all hooks for the admin area.
	 * - Dafunda_Blocks_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once DAFUNDA_BLOCKS_PATH . 'includes/class-dafunda-blocks-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once DAFUNDA_BLOCKS_PATH . 'includes/class-dafunda-blocks-i18n.php';
		require_once DAFUNDA_BLOCKS_PATH . 'includes/class-dafunda-blocks-category.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once DAFUNDA_BLOCKS_PATH . 'admin/class-dafunda-blocks-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin help area.
		 */
		require_once DAFUNDA_BLOCKS_PATH . 'admin/class-dafunda-blocks-help.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once DAFUNDA_BLOCKS_PATH . 'public/class-dafunda-blocks-public.php';

		$this->loader = new Dafunda_Blocks_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dafunda_Blocks_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dafunda_Blocks_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dafunda_Blocks_Admin();
		$plugin_help = new Dafunda_Blocks_Help();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'on_admin_init' );

		// Styles and Scripts for Help Page.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_help, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_help, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_admin_menus' );
		$this->loader->add_action( 'admin_menu', $plugin_help, 'register_help_admin_menu' );

		// Ajax hooks.
		$this->loader->add_action( 'wp_ajax_toggle_block_status', $plugin_admin, 'toggle_block_status' );

		// Insert blocks setting.
		$this->loader->add_action( 'admin_head', $plugin_admin, 'insert_blocks_settings' );

		//Review Notice
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'DafundaBlocks_review_notice' );
		$this->loader->add_action( 'wp_ajax_DafundaBlocksReviewNoticeHide', $plugin_admin, 'DafundaBlocks_hide_review_notify' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dafunda_Blocks_Public();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.2
	 * @return    Dafunda_Blocks_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
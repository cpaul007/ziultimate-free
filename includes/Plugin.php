<?php
namespace ZiUltimate;

use ZiUltimate\Requirements;
use ZiUltimate\RegisterElements;
use ZiUltimate\Admin\Admin;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Plugin {
	/**
	 * ZiUltimate instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

    /**
	 * Plugin data from header comments
	 *
	 * @var string
	 */
	private $version = null;

	/**
	 * Project root path
	 *
	 * @var string
	 */
	private $project_root_path = null;

	/**
	 * Project root url
	 *
	 * @var string
	 */
	private $project_root_url = null;

    /**
     * Holds a refference to the plugin data
     *
     * @var array
     */
	public $plugin_data = [];


    /**
     * Holds a refference to the plugin path
     *
     * @var string
     */
	public $plugin_file = null;


    /**
     * Main class constructor
     *
     * @param string $path The plugin path.
     */
    public function __construct( $path ) {
        $this->plugin_file       = $path;
        $this->project_root_path = trailingslashit( dirname( $path ) );
        $this->project_root_url  = plugin_dir_url( $path );
        $this->plugin_data       = $this->set_plugin_data( $path );
        $this->version           = $this->plugin_data['Version'];

        self::$instance = $this;

        add_action( 'init', 			[ $this, 'on_wp_init' ] );
        add_action( 'plugins_loaded', 	[ $this, 'on_plugins_loaded' ] );
    }

	/**
	 * Will instantiate all plugin dependencies
	 *
	 * @return void
	 */
	public function init_plugin() {

		if( isset( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], 'zion_builder_active' ) ) {
			add_action( 'wp_head', [ $this, 'enqueue_editor_style' ] );
		}

		new Admin();
		new License();
		new RegisterElements();
		new Helpers();
	}

	/**
	 * loading the editor style for ziultimate elements
	 *
	 * @return void
	 */
	public function enqueue_editor_style() {
		echo '<link rel="stylesheet" id="zu-editor-styles-css" href="' . $this->get_root_url() . 'assets/css/editor-global.css?ver=' . time() . '" media="all"/>';
	}

    /**
     * Will fire after the plugins are loaded and will initialize this plugin
     *
     * @return void
     */
    public function on_plugins_loaded() {
		// Check for requirements
		if ( Requirements::passed_requirements() ) {
			add_action( 'zionbuilder/loaded', [ $this, 'init_plugin' ] );
		}

		add_filter( 'all_plugins', [ $this, 'modify_plugin_branding' ] );
	}

    /**
	 * Will load plugin text domain
	 *
	 * @return void
	 */
	public function on_wp_init() {
		load_plugin_textdomain( 'ziultimate', false, $this->project_root_path . '/languages' );
	}

    /**
	 * Instance.
	 *
	 * Always load a single instance of the Plugin class
	 *
	 * @access public
	 * @static
	 *
	 * @return Plugin an instance of the class
	 */
	public static function instance() {
		return self::$instance;
	}

	/**
	 * Retrieve the project root path
	 *
	 * @return string
	 */
	public function get_root_path() {
		return $this->project_root_path;
	}

	/**
	 * Retrieve the project root path
	 *
	 * @return string
	 */
	public function get_plugin_file() {
		return $this->plugin_file;
	}


	/**
	 * Retrieve the project root url
	 *
	 * @return string
	 */
	public function get_root_url() {
		return $this->project_root_url;
	}

	/**
	 * Retrieve the project version
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the project data
	 *
	 * @param mixed $type
	 *
	 * @return string
	 */
	public function get_plugin_data( $type ) {
		if ( isset( $this->plugin_data[$type] ) ) {
			return $this->plugin_data[$type];
		}

		return null;
	}


	/**
	 * Will set the plugin data
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public function set_plugin_data( $path ) {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugin_data( $path );
	}

	/**
	 * Adding the class to WP Forms input fields
	 */
	public function zu_wpforms_field_properties( $properties, $field, $form_data ) {
		$choices = $field['choices'];

		foreach ( $choices as $key => $choice ) {
			$properties['inputs'][ $key ]['label']['class'][] = 'zu-cbrb-label';
		}

		return $properties;
	}

	/**
	 * Update plugin branding.
	 *
	 * @since 1.3.0
	 * @return array
	 */
	public function modify_plugin_branding( $all_plugins ) {
		$plugin_slug = plugin_basename( $this->plugin_file );
		
		$zuwl = get_option('zuwl');

		if( $zuwl ) {
			$all_plugins[$plugin_slug]['Name'] = ! empty( $zuwl['plugin_name'] ) ? esc_html( $zuwl['plugin_name'] ) : $all_plugins[$plugin_slug]['Name'];
			$all_plugins[$plugin_slug]['PluginURI'] = ! empty( $zuwl['plugin_uri'] ) ? esc_html( $zuwl['plugin_uri'] ) : $all_plugins[$plugin_slug]['PluginURI'];
			$all_plugins[$plugin_slug]['Author'] = ! empty( $zuwl['author_name'] ) ? esc_html( $zuwl['author_name'] ) : $all_plugins[$plugin_slug]['Author'];
			$all_plugins[$plugin_slug]['AuthorURI'] = ! empty( $zuwl['author_uri'] ) ? esc_html( $zuwl['author_uri'] ) : $all_plugins[$plugin_slug]['AuthorURI'];
			$all_plugins[$plugin_slug]['Description'] = ! empty( $zuwl['plugin_desc'] ) ? esc_html( $zuwl['plugin_desc'] ) : $all_plugins[$plugin_slug]['Description'];
		}

		$all_plugins[$plugin_slug]['Title'] = $all_plugins[$plugin_slug]['Name'];
		
		return $all_plugins;
	}
}
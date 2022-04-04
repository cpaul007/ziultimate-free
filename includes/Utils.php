<?php

namespace ZiUltimate;

use ZiUltimate\Plugin;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}


/**
 * Will handle several util methods
 */
class Utils {

	/**
	 * Get File Path
	 *
	 * Will return the file path starting with the plugin directory for the given path
	 *
	 * @param string $path The path that will be appended to the plugin path
	 *
	 * @return string
	 */
	public static function get_file_path( $path = '' ) {
		return Plugin::instance()->get_root_path() . $path;
	}


	/**
	 * Get File URL
	 *
	 * Will return the file url starting with the plugin directory for the given path
	 *
	 * @param string $path The path that will be appended to the plugin URL
	 *
	 * @return string
	 */
	public static function get_file_url( $path = '' ) {
		return Plugin::instance()->get_root_url() . $path;
	}



	/**
	 * Get Directory Info
	 *
	 * Returns the directory url and path for a given file/path
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public static function get_file_url_from_path( $path ) {
		// Set base URI
		$theme_base = get_template_directory();

		// Normalize paths
		$theme_base = wp_normalize_path( $theme_base );
		$path       = wp_normalize_path( $path );

		$is_theme       = preg_match( '#' . $theme_base . '#', $path );
		$directory_uri  = ( $is_theme ) ? get_template_directory_uri() : WP_PLUGIN_URL;
		$directory_path = ( $is_theme ) ? $theme_base : WP_PLUGIN_DIR;
		$fw_basename    = str_replace( wp_normalize_path( $directory_path ), '', $path );

		return $directory_uri . $fw_basename;
	}
}

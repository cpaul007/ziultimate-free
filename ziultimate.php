<?php
/**
 * A 3rd Party Addon for Zion Builder.
 * 
 * @wordpress-plugin
 * Plugin Name: 	ZiUltimate
 * Plugin URI: 		https://www.ziultimate.com
 * Description: 	A third party add-on of Zion Builder elements.
 * Author: 			Paul Chinmoy
 * Author URI: 		https://www.paulchinmoy.com
 * Tested up to:    5.9.2
 * Version: 		1.4.13
 * WC tested up to: 5.9.0
 *
 * License: 		GPLv2 or later
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: 	ziultimate
 * Domain Path: 	languages  
 */

/**
 * Copyright (c) 2022 Paul Chinmoy. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

require __DIR__ . '/vendor/autoload.php';
require dirname( __FILE__ ) . '/includes/Plugin.php';

new ZiUltimate\Plugin( __FILE__ );
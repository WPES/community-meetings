<?php
/**
 * Plugin Name: WPCommunity
 * Plugin URI: https://wpgranada.es/wpcommunity/
 * Description: 
 * Author: wpgranada
 * Author URI: https://wpgranada/
 * Version: 0.1
 * Text Domain: wpcommunity
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WordPress
 */

// * Loads translation
load_plugin_textdomain( 'wpcommunity', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

// * Includes Libraries for Closemarketing
//require_once dirname( __FILE__ ) . '/includes/.php';
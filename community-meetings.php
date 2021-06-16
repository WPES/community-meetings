<?php defined('ABSPATH') or die('Not today.');
/**
 * Plugin Name: Community meetings
 * Plugin URI: https://github.com/WPES/meetings/
 * Description: Plugin that creates a meeting content to fill it up and relate with speakers, sponsors and places.
 * Author: WP Spain Community
 * Author URI: https://wpgranada.es/
 * Version: 0.5
 * Text Domain: community-meetings
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with This program. If not, see https://www.gnu.org/licenses/gpl-3.0.txt.
*/

final class METGS_init {

    public $plugin_name = 'Community meetings';
    public $version = '0.5';

    function __construct(){

    }

    public function load(){
        $this->pluginConstants();
        $this->contentConstants();

        $this->includes();
        $this->inits();
    }

    private function pluginConstants(){

        // Plugin prefix
        if ( ! defined( 'METGS_PREFIX' ) ) {
            define( 'METGS_PREFIX', 'METGS' );
        }

        // Plugin version
        if ( ! defined( 'METGS_VERSION' ) ) {
            define( 'METGS_VERSION', $this->version );
        }

        // Plugin Folder Path
        if ( ! defined( 'METGS_PLUGIN_DIR' ) ) {
            define( 'METGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }

        // Plugin Admin Path
        if ( ! defined( 'METGS_PLUGIN_ADMIN_DIR' ) ) {
            define( 'METGS_PLUGIN_ADMIN_DIR', METGS_PLUGIN_DIR.'admin/' );
        }

        // Plugin Public Path
        if ( ! defined( 'METGS_PLUGIN_PUBLIC_DIR' ) ) {
            define( 'METGS_PLUGIN_PUBLIC_DIR', METGS_PLUGIN_DIR.'public/' );
        }

        // Plugin Functions Path
        if ( ! defined( 'METGS_PLUGIN_FUNCTION_DIR' ) ) {
            define( 'METGS_PLUGIN_FUNCTION_DIR', METGS_PLUGIN_DIR.'functions/' );
        }

	    // Plugin Templates Path
	    if ( ! defined( 'METGS_PLUGIN_TEMPLATES_DIR' ) ) {
		    define( 'METGS_PLUGIN_TEMPLATES_DIR', METGS_PLUGIN_DIR.'templates/' );
	    }

        // Plugin Folder URL
        if ( ! defined( 'METGS_PLUGIN_URL' ) ) {
            define( 'METGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }

        // Plugin Admin URL
        if ( ! defined( 'METGS_PLUGIN_ADMIN_URL' ) ) {
            define( 'METGS_PLUGIN_ADMIN_URL', METGS_PLUGIN_URL.'admin/' );
        }

	    // Plugin Functions URL
	    if ( ! defined( 'METGS_PLUGIN_FUNCTION_URL' ) ) {
		    define( 'METGS_PLUGIN_FUNCTION_URL', METGS_PLUGIN_URL.'functions/' );
	    }
        
        // Plugin Public URL
        if ( ! defined( 'METGS_PLUGIN_PUBLIC_URL' ) ) {
            define( 'METGS_PLUGIN_PUBLIC_URL', METGS_PLUGIN_URL.'public/' );
        }

        // Plugin Root File
        if ( ! defined( 'METGS_PLUGIN_FILE' ) ) {
            define( 'METGS_PLUGIN_FILE', __FILE__ );
        }

    }

    function contentConstants(){

        if ( ! defined('METGS_CPT_MEETING') ) {
            define( 'METGS_CPT_MEETING', 'metgs_meeting' );
        }

        if ( ! defined( 'METGS_TAX_SPEAKER' ) ) {
            define( 'METGS_TAX_SPEAKER', 'metgs_speaker' );
        }
        
        if ( ! defined( 'METGS_TAX_SPONSOR' ) ) {
            define( 'METGS_TAX_SPONSOR', 'metgs_sponsor' );
        }
        
        if ( ! defined( 'METGS_TAX_PLACE' ) ) {
            define( 'METGS_TAX_PLACE', 'metgs_place' );
        }

    }

    private function includes(){
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_admin.php';
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_public.php';
        require_once METGS_PLUGIN_FUNCTION_DIR . 'METGS_functions.php';
    }

    private function inits(){
        $admin = new METGS_admin();
        $admin->load();

        $public = new METGS_public();
        $public->load();

        $functions = new METGS_functions();
        $functions->load();
    }

}

function metgs_init(){
    $metgs = new METGS_init();
    $metgs->load();
}
metgs_init();

//Plugin activation
function METGS_activate(){
    if ( ! get_option( 'metgs_flush_rewrite_rules_flag' ) ) {
        update_option( 'metgs_flush_rewrite_rules_flag', true, true );
    }
}
register_activation_hook( __FILE__, 'METGS_activate' );

add_action( 'init', 'metgs_flush_rewrite_rules_maybe', 50 );
function metgs_flush_rewrite_rules_maybe(){
    if ( get_option( 'metgs_flush_rewrite_rules_flag' ) ) {
        flush_rewrite_rules();
        update_option( 'metgs_flush_rewrite_rules_flag', false, true );
    }
}

//Plugin deactivation
function METGS_deactivate(){
    delete_option('metgs_flush_rewrite_rules_flag');
}
register_deactivation_hook( __FILE__, 'METGS_deactivate' );

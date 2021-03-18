<?php

class METGS_public
{
    var $class_prefix=METGS_PREFIX;

    public function load() {
        $this->constants();
        $this->includes();
        $this->inits();
    }

    private function constants() {

    }

    private function includes() {
        //CPTs connects with front
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_public_cpt.php';
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_meeting.php';

        //Taxonomies connects with front
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_public_taxonomies.php';
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_speaker.php';
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_sponsor.php';
        require_once METGS_PLUGIN_PUBLIC_DIR . 'METGS_place.php';
    }

    private function inits(){
        add_action( 'wp_enqueue_scripts', array( $this, 'registerCSSScripts' ) );
        //add_action( 'wp_enqueue_scripts', array( $this, 'registerJSScripts' ) );
    }

    function registerCSSScripts( $hook ) {
        wp_register_style( $this->class_prefix.'-public-style', plugins_url( '/css/style.css', __FILE__ ), array(), '1.0', 'screen' );
        wp_enqueue_style( $this->class_prefix.'-public-style' );
    }

    function registerJSScripts( $hook ){
        wp_enqueue_script('jquery');
        wp_register_script($this->class_prefix.'-public-js', plugins_url('/js/public.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script($this->class_prefix.'-public-js');
    }


}

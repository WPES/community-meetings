<?php defined('ABSPATH') or die('Not today.');

class METGS_admin{
    var $class_prefix=METGS_PREFIX;

    public function load(){
        $this->constants();
        $this->includes();
        $this->inits();
    }

    private function constants(){

    }

    private function includes(){

        //CPTs register, and their configuration.
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_admin_cpt.php';
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_cpt_meeting.php';

        //Taxonomies register, and their configuration.
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_admin_taxonomies.php';
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_taxonomy_speaker.php';
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_taxonomy_sponsor.php';
        require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_taxonomy_place.php';

        //Custom options page on backend
        //require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_admin_optionsPage.php';
        //require_once METGS_PLUGIN_ADMIN_DIR . 'METGS_optionsPage_meetings.php';

    }

    private function inits(){

        $cpt_meetings = new METGS_cpt_meeting();
        $cpt_meetings->initCPT();

        $taxonomy_speaker = new METGS_taxonomy_speaker();
        $taxonomy_speaker->initTaxonomy();
        $taxonomy_sponsor = new METGS_taxonomy_sponsor();
        $taxonomy_sponsor->initTaxonomy();
        $taxonomy_place = new METGS_taxonomy_place();
        $taxonomy_place->initTaxonomy();

        /*$optionsPage = new METGS_optionsPage_meetings();
        $optionsPage->init();*/
        
    }
}

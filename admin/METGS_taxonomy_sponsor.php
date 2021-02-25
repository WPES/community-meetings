<?php defined('ABSPATH') or die('Not today.');

class METGS_taxonomy_sponsor extends METGS_admin_taxonomies
{
    public $cpt_meetings = METGS_CPT_MEETING;
    public $taxonomy = METGS_TAX_SPONSOR;
    public $taxonomy_rewrite = 'sponsor';

    function __construct(){

    }

    public function initTaxonomy(){
        
      add_action( 'init', array($this,'taxonomy_register') );

      add_action( 'init', array($this,'add_taxonomy_metaboxes') );

      add_action( 'init', array($this,'add_taxonomy_columns') );

    }

    function taxonomy_register(){

        $labels = array(
            'name'              => __( 'Sponsors', 'meetings' ),
            'singular_name'     => __( 'Sponsor', 'meetings' ),
            'search_items'      => __( 'Search sponsor', 'meetings' ),
            'all_items'         => __( 'All sponsors', 'meetings' ),
            'parent_item'       => __( 'Parent sponsor', 'meetings' ),
            'parent_item_colon' => __( 'Parent sponsor:', 'meetings' ),
            'edit_item'         => __( 'Edit sponsor', 'meetings' ),
            'update_item'       => __( 'Update sponsor', 'meetings' ),
            'add_new_item'      => __( 'Add new sponsor', 'meetings' ),
            'new_item_name'     => __( 'New sponsor', 'meetings' ),
            'menu_name'         => __( 'Sponsors', 'meetings' ),
        );

        $rewrite = array(
            'slug'                       => $this->taxonomy_rewrite,
            'with_front'                 => true,
            'hierarchical'               => false,
        );

        $args = $this->getStandardPublicTaxonomyArgs($labels);
        $args['hierarchical'] = false;
        $args['rewrite'] = $rewrite;

        register_taxonomy( $this->taxonomy, $this->cpt_meetings, $args );

    }

    function add_taxonomy_metaboxes(){
        $prefix = '_metgs_';
    }
}




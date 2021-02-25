<?php defined('ABSPATH') or die('Not today.');

class METGS_taxonomy_place extends METGS_admin_taxonomies {
    public $cpt_meetings = METGS_CPT_MEETING;
    public $taxonomy = METGS_TAX_PLACE;
    public $taxonomy_rewrite = 'place';

    function __construct(){

    }

    public function initTaxonomy(){

      add_action( 'init', array($this,'taxonomy_register') );

      add_action( 'init', array($this,'add_taxonomy_metaboxes') );

      add_action( 'init', array($this,'add_taxonomy_columns') );

    }

    function taxonomy_register(){

        $labels = array(
            'name'              => __( 'Places', 'meetings' ),
            'singular_name'     => __( 'Place', 'meetings' ),
            'search_items'      => __( 'Search place', 'meetings' ),
            'all_items'         => __( 'All places', 'meetings' ),
            'parent_item'       => __( 'Parent place', 'meetings' ),
            'parent_item_colon' => __( 'Parent place:', 'meetings' ),
            'edit_item'         => __( 'Edit place', 'meetings' ),
            'update_item'       => __( 'Update place', 'meetings' ),
            'add_new_item'      => __( 'Add new place', 'meetings' ),
            'new_item_name'     => __( 'New place', 'meetings' ),
            'menu_name'         => __( 'Places', 'meetings' ),
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




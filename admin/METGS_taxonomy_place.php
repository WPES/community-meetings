<?php

class METGS_taxonomy_place extends METGS_admin_taxonomies
{
    public $cpt_meetings = METGS_CPT_MEETING;
    public $taxonomy = METGS_TAX_PLACE;
    public $taxonomy_rewrite = 'place';

    function __construct()
    {

    }

    public function initTaxonomy()
    {
      add_action( 'init', array($this,'taxonomy_register') );
      add_action( 'init', array($this,'add_taxonomy_metaboxes') );

      add_action( 'init', array($this,'add_taxonomy_columns') );
    }

    function taxonomy_register() {
        $labels = array(
            'name'              => __( 'Places', 'metgs' ),
            'singular_name'     => __( 'Place', 'metgs' ),
            'search_items'      => __( 'Search place', 'metgs' ),
            'all_items'         => __( 'All places', 'metgs' ),
            'parent_item'       => __( 'Parent place', 'metgs' ),
            'parent_item_colon' => __( 'Parent place:', 'metgs' ),
            'edit_item'         => __( 'Edit place', 'metgs' ),
            'update_item'       => __( 'Update place', 'metgs' ),
            'add_new_item'      => __( 'Add new place', 'metgs' ),
            'new_item_name'     => __( 'New place', 'metgs' ),
            'menu_name'         => __( 'Places', 'metgs' ),
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




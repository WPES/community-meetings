<?php defined('ABSPATH') or die('Not today.');

class METGS_taxonomy_speaker extends METGS_admin_taxonomies
{
    public $cpt_meetings = METGS_CPT_MEETING;
    public $taxonomy = METGS_TAX_SPEAKER;
    public $taxonomy_rewrite = 'speaker';

    function __construct(){

    }

    public function initTaxonomy(){

      add_action( 'init', array($this,'taxonomy_register') );

      add_action( 'init', array($this,'add_taxonomy_metaboxes') );

      add_action( 'init', array($this,'add_taxonomy_columns') );

    }

    function taxonomy_register(){
        $labels = array(
            'name'              => __( 'Speakers', 'metgs' ),
            'singular_name'     => __( 'Speaker', 'metgs' ),
            'search_items'      => __( 'Search speakers', 'metgs' ),
            'all_items'         => __( 'All speakers', 'metgs' ),
            'parent_item'       => __( 'Parent speaker', 'metgs' ),
            'parent_item_colon' => __( 'Parent speaker:', 'metgs' ),
            'edit_item'         => __( 'Edit speaker', 'metgs' ),
            'update_item'       => __( 'Update speaker', 'metgs' ),
            'add_new_item'      => __( 'Add new speaker', 'metgs' ),
            'new_item_name'     => __( 'New speaker', 'metgs' ),
            'menu_name'         => __( 'Speakers', 'metgs' ),
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

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
            'name'              => __( 'Speakers', 'meetings' ),
            'singular_name'     => __( 'Speaker', 'meetings' ),
            'search_items'      => __( 'Search speakers', 'meetings' ),
            'all_items'         => __( 'All speakers', 'meetings' ),
            'parent_item'       => __( 'Parent speaker', 'meetings' ),
            'parent_item_colon' => __( 'Parent speaker:', 'meetings' ),
            'edit_item'         => __( 'Edit speaker', 'meetings' ),
            'update_item'       => __( 'Update speaker', 'meetings' ),
            'add_new_item'      => __( 'Add new speaker', 'meetings' ),
            'new_item_name'     => __( 'New speaker', 'meetings' ),
            'menu_name'         => __( 'Speakers', 'meetings' ),
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

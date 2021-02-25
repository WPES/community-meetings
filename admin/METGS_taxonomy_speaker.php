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
      add_action( $this->taxonomy.'_add_form_fields', array($this,'add_create_metaboxes'), 10, 1 );
      add_action( $this->taxonomy.'_edit_form_fields', array($this,'add_edit_metaboxes'), 10, 2 );
      add_action( 'edit_'.$this->taxonomy, array($this,'save_metaboxes') );
      add_action( 'create_'.$this->taxonomy, array($this,'save_metaboxes') );
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

    function add_create_metaboxes($taxonomy){
        echo '<div class="form-field term-meta-text-wrap">';
        $this->add_metaboxes($taxonomy);
        echo '</div>';
    }

    function add_edit_metaboxes($term, $taxonomy){
        echo '<tr class="form-field term-meta-text-wrap">';
        $this->add_metaboxes($taxonomy, $term->term_id);
        echo '</tr>';
    }

    function add_metaboxes($taxonomy, $term_id=0){
        //TODO nonce //wp_nonce_field(basename(__FILE__), 'term_meta_text_nonce');
        //TODO
        //TODO Image

        $inputObj = new METGS_functions_inputs($this->prefix.'_social_links', $term_id, 'taxonomy');
        $inputObj->setInput(false, __('Social links', 'metgs'));
        $inputObj->showSocialLinks();
    }

    function save_metaboxes($term_id){
        $inputObj = new METGS_functions_inputs($this->prefix.'_social_links', $term_id, 'taxonomy');
        $inputObj->saveSocialLinks();
    }

}

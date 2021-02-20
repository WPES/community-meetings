<?php defined('ABSPATH') or die('Not today.');

class METGS_admin_taxonomies{

    function __construct(){

    }

    public function initTaxonomies(){

    }

    function getStandardPublicTaxonomyArgs($labels){
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'publicly_queryable'=> true,
            'rewrite'           => false,
            'show_in_rest'      => true, // Needed for tax to appear in Gutenberg editor.
        );
        return $args;
    }

    function add_taxonomy_columns(){
        //Add image column
        add_filter('manage_edit-'.$this->taxonomy.'_columns', array($this, 'add_image_to_taxonomy_column'));
        add_filter( 'manage_'.$this->taxonomy.'_custom_column', array($this, 'add_image_to_taxonomy_column_content'), 10, 3 );
    }

    function add_image_to_taxonomy_column($columns){
        $columns['_metgs_image'] = __('Image','metgs');
        return $columns;
    }

    function add_image_to_taxonomy_column_content( $content, $column_name, $term_id ){
        $fieldkey = '_metgs_image';
        if ( $fieldkey == $column_name ) {
            $imgid = get_term_meta($term_id, $fieldkey, true);
            if(!empty($imgid)){
                $content=wp_get_attachment_image($imgid);
            } else {
                $content='';
            }
        }
        return $content;
    }

    function getTerms($taxonomy){
        return get_terms( array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ) );
    }

}

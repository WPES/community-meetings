<?php

class METGS_cpt_meeting extends METGS_admin_cpt
{

    public $cpt = METGS_CPT_MEETING;
    public $rewrite = 'meeting';
    public $taxonomy_speaker = METGS_TAX_SPEAKER;
    public $taxonomy_sponsor = METGS_TAX_SPONSOR;
    public $taxonomy_place = METGS_TAX_PLACE;

    function __construct() {

    }

    public function initCPT() {
        add_action('init', array($this, 'cpt_register'));
        add_action('init', array($this, 'add_cpt_metaboxes'));
    }

    function cpt_register() {
        $labels = array(
            'name'               => __( 'Meetups', 'metgs' ),
            'singular_name'      => __( 'Meetup', 'metgs' ),
            'add_new'            => __( 'Add New Meetup', 'metgs' ),
            'add_new_item'       => __( 'Add New Meetup', 'metgs' ),
            'edit_item'          => __( 'Edit Meetup', 'metgs' ),
            'new_item'           => __( 'New Meetup', 'metgs' ),
            'view_item'          => __( 'View Meetup', 'metgs' ),
            'search_items'       => __( 'Search Meetups', 'metgs' ),
            'not_found'          => __( 'Not found', 'metgs' ),
            'not_found_in_trash' => __( 'Not found in trash', 'metgs' ),
            'menu_name'          => __( 'Meetups', 'metgs' ),
        );
        $rewrite = array(
            'slug'                  => $this->rewrite,
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_rest'       => true, // Adds gutenberg support.
            'query_var'          => true,
            'rewrite'            => $rewrite,
            'has_archive'        => true,
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        );
        register_post_type($this->cpt, $args);
    }

    function add_cpt_metaboxes() {
        $prefix = '_metgs_';
    }


}

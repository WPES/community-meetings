<?php defined('ABSPATH') or die('Not today.');

class METGS_cpt_meeting extends METGS_admin_cpt {

    public $cpt = METGS_CPT_MEETING;
    public $rewrite = 'meeting';
    public $taxonomy_speaker = METGS_TAX_SPEAKER;
    public $taxonomy_sponsor = METGS_TAX_SPONSOR;
    public $taxonomy_place = METGS_TAX_PLACE;
    public $prefix = METGS_PREFIX;

    function __construct(){

    }

    public function initCPT(){
        add_action('init', array($this, 'cpt_register'));
        add_action('add_meta_boxes', array($this, 'add_metaboxes'));
        add_action('save_post', array($this, 'save_metaboxes'), 10, 2);
    }

    function cpt_register(){

        $labels = array(
            'name'               => __( 'Meetups', 'meetings' ),
            'singular_name'      => __( 'Meetup', 'meetings' ),
            'add_new'            => __( 'Add New Meetup', 'meetings' ),
            'add_new_item'       => __( 'Add New Meetup', 'meetings' ),
            'edit_item'          => __( 'Edit Meetup', 'meetings' ),
            'new_item'           => __( 'New Meetup', 'meetings' ),
            'view_item'          => __( 'View Meetup', 'meetings' ),
            'search_items'       => __( 'Search Meetups', 'meetings' ),
            'not_found'          => __( 'Not found', 'meetings' ),
            'not_found_in_trash' => __( 'Not found in trash', 'meetings' ),
            'menu_name'          => __( 'Meetups', 'meetings' ),
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

    function add_metaboxes(){
            add_meta_box(
                $this->prefix.'_meetingdetails',
                __('Meeting details','meetings'),
                array($this, 'show_metaboxes_meetingdetails'),  // Content callback, must be of type callable
                $this->cpt                            // Post type
            );
    }

    function show_metaboxes_meetingdetails( $post ) {
        $inputObj = new METGS_functions_inputs($this->prefix.'_startdatetime', $post->ID);
        $inputObj->setInput(false, __('Meeting start', 'meetings'));
        $inputObj->showDatetime();

        $inputObj = new METGS_functions_inputs($this->prefix.'_meetup_event_id', $post->ID);
        $inputObj->setInput(false, __('Meetup event', 'meetings'));
        $inputObj->showMeetupEvent();
    }

    function save_metaboxes($post_id, $post){
        if($this->verifyOnSave($post_id, $post)) {
            $inputObj = new METGS_functions_inputs($this->prefix.'_startdatetime', $post_id);
            $inputObj->saveDatetime();

            $inputObj = new METGS_functions_inputs($this->prefix.'_meetup_event_id', $post_id);
            $inputObj->save();
        }
    }
}

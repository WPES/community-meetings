<?php defined('ABSPATH') or die('Not today.');

class METGS_cpt_meeting extends METGS_admin_cpt {

    public $cpt = METGS_CPT_MEETING;
    public $rewrite = 'meeting';
    public $taxonomy_speaker = METGS_TAX_SPEAKER;
    public $taxonomy_sponsor = METGS_TAX_SPONSOR;
    public $taxonomy_place = METGS_TAX_PLACE;

    function __construct(){
		parent::__construct();
    }

    public function initCPT(){
	    add_filter( 'archive_template', array($this, 'archive_page_template') ); //Settings view template
	    add_action( 'pre_get_posts', array($this, 'pre_get_posts'), 1 ); //Settings view pre_get_posts

	    add_action('init', array($this, 'cpt_register'));
        add_action('add_meta_boxes', array($this, 'add_metaboxes'));
        add_action('save_post', array($this, 'save_metaboxes'), 10, 2);
	    remove_filter( 'the_content', 'wpautop' );
	    add_filter( 'the_content', array($this, 'add_to_content'), 26 );
	    add_filter( 'the_content', 'wpautop');
    }

	function archive_page_template( $page_template ) {
		if ( is_post_type_archive( $this->cpt ) ) {
			$expectedTemplate = 'archive-'.$this->cpt.'.php';
			if(strpos($page_template, $expectedTemplate, strlen($page_template) - strlen($expectedTemplate)) === false){
				$page_template = METGS_PLUGIN_TEMPLATES_DIR . '/'.$expectedTemplate;
			}
		}
		return $page_template;
	}

	function pre_get_posts( $query ) {
    	$settingsObj = new METGS_Settings_Page();
    	if($settingsObj->get_archive_page_view_option()=='pre_get_posts') {
		    add_filter( 'get_the_post_type_description', array($this, 'archive_description_add_links'));

		    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( $this->cpt ) ) {
			    $query->set( 'posts_per_page', 2 );

			    if ( ! empty( $_GET['metgs_historic'] ) && $_GET['metgs_historic'] == 1 ) {
				    $compare = '<=';
				    $order   = 'DESC';
			    } else {
				    $compare = '>=';
				    $order   = 'ASC';
			    }

			    $datekey    = $this->prefix . '_startdatetime';
			    $meta_query = array(
				    'datestart' => array(
					    'key'     => $datekey,
					    'value'   => $this->timeNow() - ( HOUR_IN_SECONDS * 4 ),
					    'compare' => $compare,
				    ),
			    );
			    $query->set( 'meta_query', $meta_query );

			    $order = array(
				    'datestart' => $order
			    );
			    $query->set( 'orderby', $order );

			    return;
		    }
	    }
	}

	function archive_description_add_links($description){
		$value=1;
		$scheduled=true;
		if(!empty($_GET['metgs_historic']) && $_GET['metgs_historic']==1){
			$value=0;
			$scheduled=false;
		}
		$url = add_query_arg('metgs_historic', $value);

		$html='';
		$html .= '<div class="metgs-archive-links">';
		if ( ! $scheduled ) {
			$html .= '<div class="metgs-archive-link-previous">';
			$html .= __( 'Previous meetings', 'community-meetings' );
			$html .= '</div>';
			$html .= '<div class="metgs-archive-link-scheduled">';
			$html .= '<a href="' . $url . '">' . __( 'Go to scheduled meetings', 'community-meetings' ) . '</a>';
			$html .= '</div>';
		} else {
			$html .= '<div class="metgs-archive-link-scheduled">';
			$html .= __( 'Scheduled meetings', 'community-meetings' );
			$html .= '</div>';
			$html .= '<div class="metgs-archive-link-previous">';
			$html .= '<a href="' . $url . '">' . __( 'Go to previous meetings', 'community-meetings' ) . '</a>';
			$html .= '</div>';
		}
		$html .= '</div>';

		$description.=$html;
    	return $description;
	}

    function cpt_register(){
    	$settings = new METGS_Settings_Page();
    	$archivePageViewOption = $settings->get_archive_page_view_option();

        $labels = array(
            'name'               => __( 'Meetups', 'community-meetings' ),
            'singular_name'      => __( 'Meetup', 'community-meetings' ),
            'add_new'            => __( 'Add New Meetup', 'community-meetings' ),
            'add_new_item'       => __( 'Add New Meetup', 'community-meetings' ),
            'edit_item'          => __( 'Edit Meetup', 'community-meetings' ),
            'new_item'           => __( 'New Meetup', 'community-meetings' ),
            'view_item'          => __( 'View Meetup', 'community-meetings' ),
            'search_items'       => __( 'Search Meetups', 'community-meetings' ),
            'not_found'          => __( 'Not found', 'community-meetings' ),
            'not_found_in_trash' => __( 'Not found in trash', 'community-meetings' ),
            'menu_name'          => __( 'Meetups', 'community-meetings' ),
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

        if($archivePageViewOption=='no_archive'){
	        $args['has_archive'] = false;
        }

        register_post_type($this->cpt, $args);
    }

    function add_metaboxes(){
            add_meta_box(
                $this->prefix.'_meetingdetails',
                __('Meeting details','community-meetings'),
                array($this, 'show_metaboxes_meetingdetails'),  // Content callback, must be of type callable
                $this->cpt                            // Post type
            );
    }

    function show_metaboxes_meetingdetails( $post ) {
        $inputObj = new METGS_functions_inputs($this->prefix.'_startdatetime', $post->ID);
        $inputObj->setInput(false, __('Meeting start', 'community-meetings'));
        $inputObj->showDatetime();

        $inputObj = new METGS_functions_inputs($this->prefix.'_meetup_event_url', $post->ID);
        $inputObj->setInput(false, __('Meetup event URL', 'community-meetings'));
        $inputObj->showUrl();
    }

    function save_metaboxes($post_id, $post){
        if($this->verifyOnSave($post_id, $post)) {
            $inputObj = new METGS_functions_inputs($this->prefix.'_startdatetime', $post_id);
            $inputObj->saveDatetime();

            $inputObj = new METGS_functions_inputs($this->prefix.'_meetup_event_url', $post_id);
            $inputObj->save('url');
        }
    }

    function add_to_content($content){
    	$additionalContent = '';
	    if ( is_singular($this->cpt) && in_the_loop() && is_main_query() ) {
	    	ob_start();
		    echo '<div class="metgs-meetings">';
		        echo '<div class="metgs-box-title">'.__('Meeting', 'community-meetings').'</div>';
			    echo '<div class="metgs-box">';
			    $meetingObj = new METGS_meeting(get_queried_object_id());
				$meetingObj->showInfo();
			    echo '</div>';
		    echo '</div>';

		    $speakers = get_the_terms(get_the_ID(), METGS_TAX_SPEAKER);
		    if(!empty($speakers)){
		    	echo '<div class="metgs-speakers">';
		    	echo '<div class="metgs-box-title">'.__('Speakers', 'community-meetings').'</div>';
		    	echo '<div class="metgs-box">';
		    	foreach ($speakers as $speaker){
				    $speakerObj = new METGS_speaker($speaker);
		    		$speakerObj->showInfo();
			    }
		    	echo '</div>';
		    	echo '</div>';
		    }
		    $sponsors = get_the_terms(get_the_ID(), METGS_TAX_SPONSOR);
		    if(!empty($sponsors)){
			    echo '<div class="metgs-sponsors">';
			    echo '<div class="metgs-box-title">'.__('Sponsors', 'community-meetings').'</div>';
			    echo '<div class="metgs-box">';
			    foreach ($sponsors as $sponsor){
				    $sponsorObj = new METGS_sponsor($sponsor);
				    $sponsorObj->showInfo();
			    }
			    echo '</div>';
			    echo '</div>';
		    }
		    $places = get_the_terms(get_the_ID(), METGS_TAX_PLACE);
		    if(!empty($places)){
			    echo '<div class="metgs-places">';
			    echo '<div class="metgs-box-title">'.__('Places', 'community-meetings').'</div>';
			    echo '<div class="metgs-box">';
			    foreach ($places as $place){
				    $placeObj = new METGS_place($place);
				    $placeObj->showInfo();
			    }
			    echo '</div>';
		    }
		    $additionalContent = ob_get_clean();
	    }
	    return $content.$additionalContent;
    }
}

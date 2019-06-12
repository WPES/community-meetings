<?php
/**
 * Library to admin
 *
 * Registers post types and tools for admin
 *
 * @package    WordPress
 * @author     WPGranada <comunidad@wpgranada.es>
 * @copyright  2019 WPGranada
 * @version    0.1
 */

/**
 * Class for admin.
 *
 * @since 0.1
 */
class WPC_Admin {

	/**
	 * Construct of Class
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'cpt_meetup' ) );
		add_action( 'init', array( $this, 'cpt_sponsor' ) );
		add_action( 'init', array( $this, 'cpt_speaker' ) );
		add_action( 'init', array( $this, 'cpt_place' ) );
		add_action( 'init', array( $this, 'cpt_podcast' ) );
	}

	/**
	 * # Functions
	 * ---------------------------------------------------------------------------------------------------- */

	/**
	 * Register Post Type POST Meetups
	 *
	 * @return void
	 **/
	public function cpt_meetup() {
		$labels = array(
			'name'               => __( 'Meetups', 'wpcommunity' ),
			'singular_name'      => __( 'Meetup', 'wpcommunity' ),
			'add_new'            => __( 'Add New Meetup', 'wpcommunity' ),
			'add_new_item'       => __( 'Add New Meetup', 'wpcommunity' ),
			'edit_item'          => __( 'Edit Meetup', 'wpcommunity' ),
			'new_item'           => __( 'New Meetup', 'wpcommunity' ),
			'view_item'          => __( 'View Meetup', 'wpcommunity' ),
			'search_items'       => __( 'Search Meetups', 'wpcommunity' ),
			'not_found'          => __( 'Not found', 'wpcommunity' ),
			'not_found_in_trash' => __( 'Not found in trash', 'wpcommunity' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_rest'       => true, // Adds gutenberg support.
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => __( 'meetups', 'wpcommunity' ),
				'with_front' => false,
			),
			'has_archive'        => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-format-chat',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		);
		register_post_type( 'meetup', $args );
	}
	/**
	 * Register Post Type POST Sponsors
	 *
	 * @return void
	 **/
	public function cpt_sponsor() {
		$labels = array(
			'name'               => __( 'Sponsors', 'wpcommunity' ),
			'singular_name'      => __( 'Sponsor', 'wpcommunity' ),
			'add_new'            => __( 'Add New Sponsor', 'wpcommunity' ),
			'add_new_item'       => __( 'Add New Sponsor', 'wpcommunity' ),
			'edit_item'          => __( 'Edit Sponsor', 'wpcommunity' ),
			'new_item'           => __( 'New Sponsor', 'wpcommunity' ),
			'view_item'          => __( 'View Sponsor', 'wpcommunity' ),
			'search_items'       => __( 'Search Sponsors', 'wpcommunity' ),
			'not_found'          => __( 'Not found', 'wpcommunity' ),
			'not_found_in_trash' => __( 'Not found in trash', 'wpcommunity' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_rest'       => true, // Adds gutenberg support.
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => __( 'sponsors', 'wpcommunity' ),
				'with_front' => false,
			),
			'has_archive'        => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-awards',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		);
		register_post_type( 'sponsor', $args );
	}

	/**
	 * Register Post Type POST Speakers
	 *
	 * @return void
	 **/
	public function cpt_speaker() {
		$labels = array(
			'name'               => __( 'Speakers', 'wpcommunity' ),
			'singular_name'      => __( 'Speaker', 'wpcommunity' ),
			'add_new'            => __( 'Add New Speaker', 'wpcommunity' ),
			'add_new_item'       => __( 'Add New Speaker', 'wpcommunity' ),
			'edit_item'          => __( 'Edit Speaker', 'wpcommunity' ),
			'new_item'           => __( 'New Speaker', 'wpcommunity' ),
			'view_item'          => __( 'View Speaker', 'wpcommunity' ),
			'search_items'       => __( 'Search Speakers', 'wpcommunity' ),
			'not_found'          => __( 'Not found', 'wpcommunity' ),
			'not_found_in_trash' => __( 'Not found in trash', 'wpcommunity' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_rest'       => true, // Adds gutenberg support.
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => __( 'speakers', 'wpcommunity' ),
				'with_front' => false,
			),
			'has_archive'        => false,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-megaphone', // https://developer.wordpress.org/resource/dashicons/.
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		);
		register_post_type( 'speaker', $args );
	}

	/**
	 * Register Post Type POST Places
	 *
	 * @return void
	 **/
	public function cpt_place() {
		$labels = array(
			'name'               => __( 'Places', 'wpcommunity' ),
			'singular_name'      => __( 'Place', 'wpcommunity' ),
			'add_new'            => __( 'Add New Place', 'wpcommunity' ),
			'add_new_item'       => __( 'Add New Place', 'wpcommunity' ),
			'edit_item'          => __( 'Edit Place', 'wpcommunity' ),
			'new_item'           => __( 'New Place', 'wpcommunity' ),
			'view_item'          => __( 'View Place', 'wpcommunity' ),
			'search_items'       => __( 'Search Places', 'wpcommunity' ),
			'not_found'          => __( 'Not found', 'wpcommunity' ),
			'not_found_in_trash' => __( 'Not found in trash', 'wpcommunity' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => __( 'places', 'wpcommunity' ),
				'with_front' => false,
			),
			'has_archive'        => false,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-pressthis', // https://developer.wordpress.org/resource/dashicons/.
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		);
		register_post_type( 'place', $args );
	}

	/**
	 * Register Post Type POST Podcast
	 *
	 * @return void
	 **/
	public function cpt_podcast() {
		$labels = array(
			'name'               => __( 'Podcasts', 'wpcommunity' ),
			'singular_name'      => __( 'Podcast', 'wpcommunity' ),
			'add_new'            => __( 'Add New Podcast', 'wpcommunity' ),
			'add_new_item'       => __( 'Add New Podcast', 'wpcommunity' ),
			'edit_item'          => __( 'Edit Podcast', 'wpcommunity' ),
			'new_item'           => __( 'New Podcast', 'wpcommunity' ),
			'view_item'          => __( 'View Podcast', 'wpcommunity' ),
			'search_items'       => __( 'Search Podcasts', 'wpcommunity' ),
			'not_found'          => __( 'Not found', 'wpcommunity' ),
			'not_found_in_trash' => __( 'Not found in trash', 'wpcommunity' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => __( 'podcasts', 'wpcommunity' ),
				'with_front' => false,
			),
			'has_archive'        => false,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-format-audio', // https://developer.wordpress.org/resource/dashicons/.
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		);
		register_post_type( 'podcast', $args );
	}
}

new WPC_Admin();

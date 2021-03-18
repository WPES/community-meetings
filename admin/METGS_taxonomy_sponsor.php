<?php defined( 'ABSPATH' ) or die( 'Not today.' );

class METGS_taxonomy_sponsor extends METGS_admin_taxonomies {
	public $cpt_meetings = METGS_CPT_MEETING;
	public $taxonomy = METGS_TAX_SPONSOR;
	public $taxonomy_rewrite = 'sponsor';

	function __construct() {

	}

	public function initTaxonomy() {
		add_action( 'init', array( $this, 'taxonomy_register' ) );
		add_filter('get_the_archive_description', array($this,'archiveDescription'));
		parent::initTaxonomies();
	}

	function taxonomy_register() {

		$labels = array(
			'name'              => __( 'Sponsors', 'meetings' ),
			'singular_name'     => __( 'Sponsor', 'meetings' ),
			'search_items'      => __( 'Search sponsor', 'meetings' ),
			'all_items'         => __( 'All sponsors', 'meetings' ),
			'parent_item'       => __( 'Parent sponsor', 'meetings' ),
			'parent_item_colon' => __( 'Parent sponsor:', 'meetings' ),
			'edit_item'         => __( 'Edit sponsor', 'meetings' ),
			'update_item'       => __( 'Update sponsor', 'meetings' ),
			'add_new_item'      => __( 'Add new sponsor', 'meetings' ),
			'new_item_name'     => __( 'New sponsor', 'meetings' ),
			'menu_name'         => __( 'Sponsors', 'meetings' ),
		);

		$rewrite = array(
			'slug'         => $this->taxonomy_rewrite,
			'with_front'   => true,
			'hierarchical' => false,
		);

		$args                 = $this->getStandardPublicTaxonomyArgs( $labels );
		$args['hierarchical'] = false;
		$args['rewrite']      = $rewrite;

		register_taxonomy( $this->taxonomy, $this->cpt_meetings, $args );

	}

	function add_metaboxes($taxonomy, $term_id=0){
		$inputObj = new METGS_functions_inputs($this->prefix.'_claim', $term_id, 'taxonomy');
		$inputObj->setInput(false, __('Claim', 'meetings'));
		$inputObj->showText();

		$inputObj = new METGS_functions_inputs($this->prefix.'_social_links', $term_id, 'taxonomy');
		$inputObj->setInput(false, __('Social links', 'meetings'));
		$inputObj->showSocialLinks();

		$inputObj = new METGS_functions_inputs($this->prefix.'_image', $term_id, 'taxonomy');
		$inputObj->setInput(false, __('Logo', 'meetings'));
		$inputObj->showImage();
	}

	function archiveDescription($description){
		if(is_tax($this->taxonomy)){
			ob_start();
			$placeObj = new METGS_sponsor(get_queried_object_id());
			$placeObj->showInfo();
			$description = ob_get_clean();
		}
		return $description;
	}

	function add_metaboxes_scripts($screen){
		if ( ( $screen->base == 'term' || $screen->base == 'edit-tags' ) && $screen->taxonomy == $this->taxonomy ) {
			add_action( 'admin_enqueue_scripts', array( 'METGS_functions_inputs', 'enqueueImageScripts' ) );
		}
	}

	function save_metaboxes($term_id){
		$inputObj = new METGS_functions_inputs($this->prefix.'_claim', $term_id, 'taxonomy');
		$inputObj->save();

		$inputObj = new METGS_functions_inputs($this->prefix.'_social_links', $term_id, 'taxonomy');
		$inputObj->saveSocialLinks();

		$inputObj = new METGS_functions_inputs($this->prefix.'_image', $term_id, 'taxonomy');
		$inputObj->save();
	}
}




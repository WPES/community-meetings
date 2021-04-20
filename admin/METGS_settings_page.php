<?php defined('ABSPATH') or die('Not today.');
/**
 * Library for admin settings
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2021 Closemarketing
 * @version    1.0
 */

/**
 * Library for Admin Settings
 */
class METGS_Settings_Page {
	/**
	 * Settings
	 *
	 * @var array
	 */
	private $meetup_settings;

	public $prefix = METGS_PREFIX;

	public function __construct() {
	}

	function get_options(){
	    if(empty($this->meetup_settings)) {
		    $this->meetup_settings = get_option( $this->prefix . '_options' );
	    }
	}

	function init(){
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'update_option', array( $this, 'updated_archive_page_view_option' ), 10, 3 ); //Flush rewrite rules when archive_page_view_option is updated.
    }

	public function add_plugin_page() {
		add_submenu_page(
			'edit.php?post_type=metgs_meeting',
			__( 'Settings', 'meetings' ),
			__( 'Settings', 'meetings' ),
			'manage_options',
			'meetings_admin',
			array( $this, 'create_admin_page' ),
		);
	}

	public function create_admin_page() {
		$results = $this->get_meetup_options( $this->meetup_settings['meetup_url'] );
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Meetings Settings', 'meetings' ); ?>
			</h2>
			<p></p>
			<?php
			settings_errors();
			?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'meetings_settings' );
				do_settings_sections( 'meetings-admin' );
				submit_button( __( 'Save settings', 'meetings' ), 'primary', 'submit_settings' );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Init for page
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting( 'meetings_settings', $this->prefix.'_options', array( $this, 'sanitize_fields' ) );

		add_settings_section(
			'metgs_setting_view',
			__( 'View', 'meetings' ),
			'',
			'meetings-admin'
		);

		add_settings_field(
			'archive_page_view_option',
			__( 'Archive page type', 'meetings' ),
			array( $this, 'meetup_field_archive_page_view_option' ),
			'meetings-admin',
			'metgs_setting_view'
		);

		add_settings_section(
			'metgs_setting_section',
			__( 'Settings', 'meetings' ),
			array( $this, 'metgs_section_info' ),
			'meetings-admin'
		);

		add_settings_field(
			'meetup_url',
			__( 'Meetup URL', 'meetings' ),
			array( $this, 'meetup_url_callback' ),
			'meetings-admin',
			'metgs_setting_section'
		);
	}

	/**
	 * Sanitize fiels before saves in DB
	 *
	 * @param array $input Input fields.
	 * @return array
	 */
	public function sanitize_fields( $input ) {
		$sanitary_values = array();

		if ( isset( $input['archive_page_view_option'] ) ) {
			$sanitary_values['archive_page_view_option'] = sanitize_key( $input['archive_page_view_option'] );
		}

		if ( isset( $input['meetup_url'] ) ) {
			$sanitary_values['meetup_url'] = sanitize_text_field( $input['meetup_url'] );
		}

		return $sanitary_values;
	}

	function get_archive_page_view_option(){
	    $this->get_options();
		if(isset( $this->meetup_settings['archive_page_view_option'])){
			return $this->meetup_settings['archive_page_view_option'];
		}
		return 'pre_get_posts';
    }

    function updated_archive_page_view_option($option, $old_value, $value){
	    if($option==$this->prefix . '_options'){
		    if($old_value['archive_page_view_option']!=$value['archive_page_view_option']){
			    update_option( 'metgs_flush_rewrite_rules_flag', true, true );
		    }
	    }
    }

	public function meetup_field_archive_page_view_option() {
		$options = array(
			'pre_get_posts' => __('Show following events in order.', 'meetings'),
			'archive' => __('Show default archive page.', 'meetings'),
			'no_archive' => __('Don\'t show archive page.', 'meetings'),
			'template' => __('Show community meetings template.', 'meetings'),
		);

		echo '<select name="'.$this->prefix.'_options[archive_page_view_option]'.'" id="metgs_archive_page_view_option">';
		if ( ! empty( $options ) ) {
			foreach ( $options as $optionKey => $optionName ) {
				$selected = '';
				if ( $optionKey == $this->get_archive_page_view_option() ) {
					$selected = ' selected';
				}
				echo '<option value="' . esc_attr($optionKey) . '"' . $selected . '>' . esc_html($optionName) . '</option>';
			}
		}
		echo '</select>';
	}

	public function metgs_section_info() {
		echo sprintf( esc_html__( 'Put the connection API key settings in order to connect and sync products. You can go here <a href="%s" target="_blank">Meetings API</a>. ', 'meetings' ), 'https://www.meetup.com/' );
	}

	function get_meetup_url(){
		$this->get_options();
		if(isset( $this->meetup_settings['meetup_url'])){
			return $this->meetup_settings['meetup_url'];
		}
		return '';
	}

	public function meetup_url_callback() {
		printf( '<input class="regular-text" type="text" name="'.$this->prefix.'_options[meetup_url]" id="meetup_url" value="%s">', esc_attr( $this->get_meetup_url()));
	}

	private function get_meetup_options( $url ) {
		$results = array();
		if ( ! empty( $url ) ) {
			$transientName = 'METGS_settings_page-get_meetup_options-'.md5($url);
			if ( false === ( $results = get_transient( $transientName ) ) ) {
				$dom     = new DOMDocument();
				$context = stream_context_create(
					array(
						'http' => array(
							'follow_location' => false,
						),
						'ssl'  => array(
							'verify_peer'      => false,
							'verify_peer_name' => false,
						),
					)
				);
				libxml_use_internal_errors( true );
				libxml_set_streams_context( $context );

				$dom->loadHTMLFile( $url );
				$finder    = new DomXPath( $dom );
				$classname = "groupHomeHeaderInfo-memberLink";
				$nodes     = $finder->query( "//*[contains(@class, '$classname')]" );
				$tmp_dom   = new DOMDocument();
				foreach ( $nodes as $node ) {
					$tmp_dom->appendChild( $tmp_dom->importNode( $node, true ) );
				}
				$html_var = trim( $tmp_dom->saveHTML() );
				$string   = preg_replace( '/<[^>]*>/', '', $html_var );
				$string   = str_replace( '.', '', $string );
				preg_match_all( '!\d+!', $string, $matches );
				$results['members'] = $matches[0];
				set_transient( $transientName, $results, 4 * HOUR_IN_SECONDS );
			}
		}
		return $results;
	}
}
if ( is_admin() ) {
	$meetings_admin = new METGS_Settings_Page();
	$meetings_admin->init();
}

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
	/**
	 * Construct of class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Adds plugin page.
	 *
	 * @return void
	 */
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

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->meetup_settings = get_option( 'meetings' );
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
		register_setting( 'meetings_settings', 'meetings', array( $this, 'sanitize_fields' ) );

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

		if ( isset( $input['meetup_url'] ) ) {
			$sanitary_values['meetup_url'] = sanitize_text_field( $input['meetup_url'] );
		}

		return $sanitary_values;
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function metgs_section_info() {
		echo sprintf( esc_html__( 'Put the connection API key settings in order to connect and sync products. You can go here <a href="%s" target="_blank">Meetings API</a>. ', 'meetings' ), 'https://www.meetup.com/' );
	}

	/**
	 * Metgs URL Callback
	 *
	 * @return void
	 */
	public function meetup_url_callback() {
		printf( '<input class="regular-text" type="text" name="meetings[meetup_url]" id="meetup_url" value="%s">', ( isset( $this->meetup_settings['meetup_url'] ) ? esc_attr( $this->meetup_settings['meetup_url'] ) : '' ) );
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
}
